<?php

namespace oat\taoMobileApp\scripts\install;

use oat\oatbox\extension\InstallAction;
use oat\taoMobileApp\model\assemblies\MobileAssemblerService;
use common_report_Report as Report;

class RegisterMobileAssemblerService extends InstallAction
{
    public function __invoke($params)
    {
        $assemblerService = new MobileAssemblerService();
        $this->getServiceManager()->register(
            MobileAssemblerService::SERVICE_ID,
            $assemblerService
        );

        return new Report(
            Report::TYPE_SUCCESS,
            "Service 'MobileAssemblerService' successfully registered."
        );
    }
}