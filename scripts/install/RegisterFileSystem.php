<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\scripts\install;

use oat\oatbox\extension\InstallAction;
use oat\oatbox\filesystem\FileSystemService;

/**
 * Class RegisterFileSystem
 *
 * @package oat\taoDeliveryRdf
 */
class RegisterFileSystem extends InstallAction
{
    /**
     * @param $params
     * @throws \common_Exception
     * @throws \oat\oatbox\service\exception\InvalidServiceManagerException
     */
    public function __invoke($params)
    {
        $serviceManager = $this->getServiceManager();
        /** @var FileSystemService $service */
        $service = $serviceManager->get(FileSystemService::SERVICE_ID);
        $service->createFileSystem('mobileAssemblyExport');
        $serviceManager->register(FileSystemService::SERVICE_ID, $service);
    }
}