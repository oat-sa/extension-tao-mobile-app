<?php

namespace oat\taoMobileApp\test\unit\model\assemblies;

use oat\taoMobileApp\model\assemblies\AssembliesUtils;

class AssembliesUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testTransformToMobileAssembly()
    {
        $archivePath = __DIR__ . '/../../../samples/assemblies/assembly-simple-languages.zip';
        $testArchivePath = __DIR__ . '/../../../samples/assemblies/assembly-simple-test.zip';;
        copy($archivePath, $testArchivePath);
        $zipArchive = new \ZipArchive();
        $zipArchive->open($testArchivePath, \ZipArchive::CREATE);
        AssembliesUtils::transformToMobileAssembly($zipArchive);

        $zipArchive->close();
    }
}