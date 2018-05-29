<?php

namespace oat\taoMobileApp\model\assemblies;

class AssembliesUtils
{
    public static function transformToMobileAssembly(\ZipArchive $zipArchive)
    {
        $files = \tao_helpers_File::getAllZipNames($zipArchive);
        $map = self::sortItemAssemblyFiles($zipArchive, $files);
        $renameMap = [];
        foreach ($map as $privatePath => $publicPath) {
            $jsonItem = json_decode($zipArchive->getFromName("${privatePath}/en-US/item.json"), true);
            $itemIdentifier = self::getItemIdentifierFromJson($jsonItem);

            $itemLanguages = self::getLanguagesFromItemPrivateDirectory($files, $privatePath);
            $itemLanguagesToExclude = [];

            if (count($itemLanguages) > 1) {
                $itemLanguagesToExclude = $itemLanguages;
                unset($itemLanguagesToExclude[0]);
            }

            foreach ($itemLanguagesToExclude as $itemLanguageToExclude) {
                $quoted = preg_quote("${privatePath}/${itemLanguageToExclude}/", '/');
                \tao_helpers_File::excludeFromZip($zipArchive, "/${quoted}.+/");
            }

            $renameMap[$privatePath . '/' . $itemLanguages[0]] = $itemIdentifier;

            if ($publicPath !== null) {
                $renameMap[$publicPath . '/' . $itemLanguages[0]] = $itemIdentifier;

                foreach ($itemLanguagesToExclude as $itemLanguageToExclude) {
                    $quoted = preg_quote("${publicPath}/${itemLanguageToExclude}/", '/');
                    \tao_helpers_File::excludeFromZip($zipArchive, "/${quoted}.+/");
                }
            }
        }

        foreach ($renameMap as $oldname => $newname) {
            \tao_helpers_File::renameInZip($zipArchive, $oldname, $newname);
        }

        \tao_helpers_File::excludeFromZip($zipArchive, '/delivery\.rdf$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/manifest\.json$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/\.idx$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/.php$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/\.xml$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/\.index/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/adaptive-section-map\.json$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/compilation-info\.json$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/test-index\.json$/');
        \tao_helpers_File::excludeFromZip($zipArchive, '/\/$/');
    }

    public static function sortItemAssemblyFiles(\ZipArchive $zipArchive, array $files)
    {
        $manifest = json_decode($zipArchive->getFromName('manifest.json'), true);
        $keys = array_keys($manifest['dir']);
        $map = [];

        for ($i = 0; $i < count($keys); $i++) {
            if (preg_match('/-$/', $keys[$i]) === 1) {
                // Private directory.
                $zipPath = $manifest['dir'][$keys[$i]];
                if (self::isItemPrivateDirectory($files, $zipPath)) {
                    $map[$zipPath] = null;

                    if (isset($keys[$i + 1])) {
                        $nextZipPath = $manifest['dir'][$keys[$i + 1]];

                        if (self::isDirectoryAvailable($files, $nextZipPath) || self::isItemPrivateDirectory($files, $nextZipPath)) {
                            $map[$zipPath] = $nextZipPath;
                        }
                    }
                }
            }
        }

        return $map;
    }

    public static function isItemPrivateDirectory(array $zipFiles, $path)
    {
        foreach ($zipFiles as $zipFile) {
            $quotedPath = preg_quote($path . '/', '/');
            $quotedItem = preg_quote('/item.json', '/');
            $pattern = "/^${quotedPath}\w+-\w+${quotedItem}/";

            if (preg_match($pattern, $zipFile) === 1) {
                return true;
            }
        }

        return false;
    }

    public static function isDirectoryAvailable(array $zipFiles, $path)
    {
        foreach ($zipFiles as $zipFile) {
            $quotedPath = preg_quote($path, '/');
            $pattern = "/^${quotedPath}/";

            if (preg_match($pattern, $zipFile) === 1) {
                return true;
            }
        }

        return false;
    }

    public static function getItemIdentifierFromJson($jsonItem)
    {
        if (isset($jsonItem['data']) && isset($jsonItem['data']['identifier'])) {
            return $jsonItem['data']['identifier'];
        } else {
            return false;
        }
    }

    public static function getLanguagesFromItemPrivateDirectory(array $zipFiles, $path)
    {
        $languages = [];

        foreach ($zipFiles as $zipFile) {
            $quotedPath = preg_quote($path, '/');
            $pattern = '/^' . "${quotedPath}\/(\w+-\w+)\//";
            $matches = [];
            preg_match($pattern, $zipFile, $matches);
            if (isset($matches[1])) {
                $languages[] = $matches[1];
            }
        }

        return array_unique($languages);
    }
}