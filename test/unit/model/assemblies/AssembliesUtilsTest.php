<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\test\unit\model\assemblies;

use oat\taoMobileApp\model\assemblies\AssembliesUtils;

class AssembliesUtilsTest extends \PHPUnit_Framework_TestCase
{
    private $path;

    public function setUp()
    {
        // Setup a filesystem environment.
        $tmp = sys_get_temp_dir();
        $this->path = "${tmp}/.taoMobileApp/test/assemblies/utils";
        @mkdir($this->path, 0777, true);
    }

    protected function tearDown()
    {
        \tao_helpers_File::remove($this->path, true);
    }

    public function testTransformToMobileAssemblySimple()
    {
        // Prepare...
        $archivePath = __DIR__ . '/../../../samples/assemblies/assembly-simple.zip';
        $testArchivePath = $this->path . '/assembly-simple-test.zip';;
        copy($archivePath, $testArchivePath);
        $zipArchive = new \ZipArchive();
        $zipArchive->open($testArchivePath, \ZipArchive::CREATE);
        AssembliesUtils::transformToMobileAssembly($zipArchive);
        $zipArchive->close();

        // Test on a flushed archive.
        $zipArchive = new \ZipArchive();
        $zipArchive->open($testArchivePath, \ZipArchive::CREATE);

        $this->assertFalse($zipArchive->getFromName('delivery.rdf'));
        $this->assertFalse($zipArchive->getFromName('manifest.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/item.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/portableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/variableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/oat-300x150.png'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/tao-user-styles.css'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q02/item.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q02/portableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q02/variableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q03/item.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q03/portableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q03/variableElements.json'));

        $zipArchive->close();
    }

    public function testTransformToMobileAssemblyMultipleLanguages()
    {
        // Prepare...
        $archivePath = __DIR__ . '/../../../samples/assemblies/assembly-simple-languages.zip';
        $testArchivePath = $this->path . '/assembly-simple-test.zip';;
        copy($archivePath, $testArchivePath);
        $zipArchive = new \ZipArchive();
        $zipArchive->open($testArchivePath, \ZipArchive::CREATE);
        AssembliesUtils::transformToMobileAssembly($zipArchive);
        $zipArchive->close();

        // Test on a flushed archive.
        $zipArchive = new \ZipArchive();
        $zipArchive->open($testArchivePath, \ZipArchive::CREATE);

        $this->assertFalse($zipArchive->getFromName('delivery.rdf'));
        $this->assertFalse($zipArchive->getFromName('manifest.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/item.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/portableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/variableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/oat-300x150.png'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q01/tao-user-styles.css'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q02/item.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q02/portableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q02/variableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q03/item.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q03/portableElements.json'));
        $this->assertNotFalse($zipArchive->getFromName('Item-Q03/variableElements.json'));

        $zipArchive->close();
    }
}