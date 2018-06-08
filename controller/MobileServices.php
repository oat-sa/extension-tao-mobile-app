<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\controller;

use oat\taoMobileApp\model\assemblies\MobileAssembler;

class MobileServices extends \tao_actions_RestController
{
    /**
     * @return array
     */
    protected function getAcceptableMimeTypes()
    {
        return ['application/zip'];
    }

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
            /** @var MobileAssembler $mobileAssembler */
            $mobileAssembler = $this->getServiceLocator()->get(MobileAssembler::SERVICE_ID);
            $data = $mobileAssembler->exportCompiledDelivery($deliveryResource);

            \tao_helpers_Http::returnStream($data->readPsrStream());

        } catch (\Exception $e) {
            $this->returnFailure($e);
        }
    }
}