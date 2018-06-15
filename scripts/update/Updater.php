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
     * @return void
     */
    public function update($initialVersion) {

    }
}
