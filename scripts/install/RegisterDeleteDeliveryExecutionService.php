<?php

namespace oat\taoMobileApp\scripts\install;

use oat\oatbox\extension\InstallAction;
use oat\taoDelivery\model\execution\Delete\DeliveryExecutionDeleteService;
use common_report_Report as Report;

class RegisterDeleteDeliveryExecutionService extends InstallAction
{
    public function __invoke($params)
    {
        $deliveryExecutionDelete = new DeliveryExecutionDeleteService([
            DeliveryExecutionDeleteService::OPTION_DELETE_DELIVERY_EXECUTION_DATA_SERVICES => [
                'taoQtiTest/ExtendedStateService',
                'taoQtiTest/TestSessionService',
                'taoQtiTest/QtiTimerFactory',
                'taoQtiTest/QtiRunnerService',
            ],
        ]);

        $this->getServiceManager()->register(DeliveryExecutionDeleteService::SERVICE_ID, $deliveryExecutionDelete);

        return new Report(
            Report::TYPE_SUCCESS,
            "Service 'DeliveryExecutionDeleteService' successfully registered."
        );
    }
}