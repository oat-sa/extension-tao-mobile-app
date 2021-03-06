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
 *
 * @deprecated use migrations instead. See https://github.com/oat-sa/generis/wiki/Tao-Update-Process
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

        $this->skip('0.0.0', '1.0.0');

        //Updater files are deprecated. Please use migrations.
        //See: https://github.com/oat-sa/generis/wiki/Tao-Update-Process
        $this->setVersion($this->getExtension()->getManifest()->getVersion());
    }
}
