<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\controller;


use oat\taoDeliveryRdf\model\AssemblerServiceInterface;

class MobileServices extends \tao_actions_RestController
{
    /**
     * @throws \common_exception_BadRequest
     * @throws \common_exception_MethodNotAllowed
     * @throws \common_exception_NotFound
     * @throws \common_exception_NotImplemented
     */
    public function assembly()
    {
        if ($this->getRequestMethod() != \Request::HTTP_GET) {
            throw new \common_exception_MethodNotAllowed("Only GET method is accepted by the 'Assembly' TAO Mobile App Service.");
        }

        // Retrieve delivery information.
        $deliveryIdentifier = $this->getRequestParameter('deliveryIdentifier');
        if (empty($deliveryIdentifier)) {
            throw new \common_exception_BadRequest("Missing 'deliveryIdentifier' parameter for the 'Assembly' TAO Mobile App Service.");
        }

        // Retrieve delivery from storage.
        $deliveryResource = new \core_kernel_classes_Resource($deliveryIdentifier);
        if (!$deliveryResource->exists()) {
            throw new \common_exception_NotFound("Delivery resource with identifier '${deliveryIdentifier}' could not be found while invoking the 'Assembly' TAO Mobile App Service.");
        }

        try {
            /** @var AssemblerServiceInterface $assemblerService */
            $assemblerService = $this->getServiceLocator()->get(AssemblerServiceInterface::SERVICE_ID);
            $fileSystem = $assemblerService->getFileSystem();

            // Export mobile assembly in shared file system.
            $fsExportPath = self::fsExportPath($deliveryIdentifier);
            $assemblerService->exportCompiledDelivery($deliveryResource, $fsExportPath);

            // Return archive to invoker.
            \tao_helpers_Http::returnStream($fileSystem->readStream($fsExportPath));
        } catch (\Exception $e) {
            $this->returnFailure($e);
        }
    }

    private static function fsExportPath($deliveryIdentifier)
    {
        return md5($deliveryIdentifier) . '.zip';
    }
}