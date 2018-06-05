<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\scripts\update;

use oat\taoDelivery\model\execution\Delete\DeliveryExecutionDeleteService;
use oat\taoMobileApp\model\assemblies\MobileAssemblerService;

/**
 * TAO Mobile App Extension Updater.
 * 
 * This class provides an implementation of the Generis
 * Extension Updater aiming at updating the TAO Mobile App Extension.
 */
class Updater extends \common_ext_ExtensionUpdater {

    /**
     * Update the Extension
     * 
     * Calling this method will update the TAO Mobile App Extension from
     * an $initialVersion to a target version.
     * 
     * @param string $initialVersion
     * @see \common_ext_ExtensionUpdater
     * @throws \common_Exception
     * @return void
     */
    public function update($initialVersion) {

        if ($this->isVersion('0.1.0')) {

            $assemblerService = new MobileAssemblerService();
            $this->getServiceManager()->register(MobileAssemblerService::SERVICE_ID, $assemblerService);

            $deliveryExecutionDelete = new DeliveryExecutionDeleteService([
                DeliveryExecutionDeleteService::OPTION_DELETE_DELIVERY_EXECUTION_DATA_SERVICES => [
                    'taoQtiTest/ExtendedStateService',
                    'taoQtiTest/TestSessionService',
                    'taoQtiTest/QtiTimerFactory',
                    'taoQtiTest/QtiRunnerService',
                ],
            ]);

            $this->getServiceManager()->register(DeliveryExecutionDeleteService::SERVICE_ID, $deliveryExecutionDelete);

            $this->setVersion('1.0.0');
        }
    }
}
