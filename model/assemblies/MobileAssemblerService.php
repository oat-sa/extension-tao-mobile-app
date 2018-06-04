<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\model\assemblies;

use core_kernel_classes_Resource;
use oat\oatbox\user\User;
use oat\taoDelivery\model\AssignmentService;
use oat\taoDelivery\model\execution\Delete\DeliveryExecutionDeleteRequest;
use oat\taoDelivery\model\execution\Delete\DeliveryExecutionDeleteService;
use oat\taoDelivery\model\execution\DeliveryExecutionInterface;
use oat\taoDelivery\model\execution\StateServiceInterface;
use oat\taoDeliveryRdf\model\guest\GuestTestUser;
use oat\taoDeliveryRdf\model\import\AssemblerService;
use oat\taoQtiTest\models\runner\QtiRunnerService;
use oat\taoQtiTest\models\runner\RunnerServiceContext;

/**
 * Class MobileAssemblerService
 *
 * An extension of the TAO AssemblerService class aiming at compiling Mobile App compliant
 * Delivery Assemblies.
 *
 * @package oat\taoMobileApp\model\assemblies
 */
class MobileAssemblerService extends AssemblerService
{
    /**
     * Export Compiled Mobile App compliant delivery
     *
     * This method performs the additional behaviour to make a TAO Assembly compliant
     * with the TAO Mobile App.
     *
     * @param string $path
     * @param core_kernel_classes_Resource $compiledDelivery
     * @param \ZipArchive $zipArchive
     * @throws \Exception
     */
    protected function doExportCompiledDelivery($path, core_kernel_classes_Resource $compiledDelivery, \ZipArchive $zipArchive)
    {
        parent::doExportCompiledDelivery($path, $compiledDelivery, $zipArchive);
        AssembliesUtils::transformToMobileAssembly($zipArchive);

        // We now have to instantiate a Delivery Execution of this Delivery, in order to be able
        // to provide the initialization data to an assembly consumer.

        try {
            // 1. Initialize the Delivery Execution.
            $user = new GuestTestUser();
            $deliveryExecution = $this->createDeliveryExecution($compiledDelivery, $user);

            // 2. Retrieve QTI Test Runner context information.
            $runnerContext = $this->retrieveRunnerContext($deliveryExecution, $user);

            // 3. Initialize QTI Test Runner execution.
            /** @var QtiRunnerService $runnerService */
            $runnerService = $this->getServiceLocator()->get(QtiRunnerService::SERVICE_ID);
            $runnerService->initServiceContext($runnerContext);
            $runnerService->init($runnerContext);

            // 4. Retrieve required runtime information from the assembly.
            $testDataStructure = $this->buildRuntimeData($runnerContext);

            // 5. Finalize assembly.
            $zipArchive->addFromString('testData.json', json_encode($testDataStructure, JSON_PRETTY_PRINT));

            // 6. Delete execution data.
            $this->removeExecutionData($deliveryExecution, $runnerContext);

        } catch (\Exception $e) {
            throw new \common_Exception(
                "An unexpected error occurred while instantiating the TAO Mobile App Assembly Delivery execution.",
                0,
                $e
            );
        }
    }

    /**
     * @param RunnerServiceContext $runnerServiceContext
     * @return array
     */
    protected function buildRuntimeData(RunnerServiceContext $runnerServiceContext)
    {
        $runnerService = $this->getServiceLocator()->get(QtiRunnerService::SERVICE_ID);

        return [
            'testData' => $runnerService->getTestData($runnerServiceContext),
            'testContext' => $runnerService->getTestContext($runnerServiceContext),
            'testMap' => $runnerService->getTestMap($runnerServiceContext)
        ];
    }

    /**
     * @param DeliveryExecutionInterface $deliveryExecution
     * @param User $user
     * @return \oat\taoQtiTest\models\runner\QtiRunnerServiceContext
     * @throws \common_Exception
     * @throws \common_exception_Error
     * @throws \common_exception_NotFound
     */
    protected function retrieveRunnerContext(DeliveryExecutionInterface $deliveryExecution, User $user)
    {
        /** @var AssignmentService $assignmentService */
        $assignmentService = $this->getServiceLocator()->get(AssignmentService::SERVICE_ID);
        $runtime = $assignmentService->getRuntime($deliveryExecution->getDelivery()->getUri());
        $contextParams = \tao_models_classes_service_ServiceCallHelper::getInputValues($runtime, []);

        /** @var QtiRunnerService $runnerService */
        $runnerService = $this->getServiceLocator()->get(QtiRunnerService::SERVICE_ID);
        $runnerContext = $runnerService->getServiceContext(
            $contextParams['QtiTestDefinition'],
            $contextParams['QtiTestCompilation'],
            $deliveryExecution->getIdentifier(),
            $user->getIdentifier()
        );

        return $runnerContext;
    }

    /**
     * @param core_kernel_classes_Resource $compiledDelivery
     * @param User $user
     * @return DeliveryExecutionInterface
     */
    protected function createDeliveryExecution(core_kernel_classes_Resource $compiledDelivery, User $user)
    {
        $deliveryUri = $compiledDelivery->getUri();

        /** @var StateServiceInterface $stateService */
        $deliveryExecutionStateService = $this->getServiceLocator()->get(StateServiceInterface::SERVICE_ID);
        $deliveryExecution = $deliveryExecutionStateService->createDeliveryExecution(
            $deliveryUri,
            $user,
            "TAO Mobile App Assembly Delivery Execution '${deliveryUri}'"
        );

        return $deliveryExecution;
    }

    /**
     * @param DeliveryExecutionInterface $deliveryExecution
     * @param RunnerServiceContext $runnerServiceContext
     * @throws \Exception
     */
    protected function removeExecutionData(DeliveryExecutionInterface $deliveryExecution, RunnerServiceContext $runnerServiceContext)
    {
        /** @var DeliveryExecutionDeleteService $deleteDeliveryExecutionService */
        $deleteDeliveryExecutionService = $this->getServiceLocator()->get(DeliveryExecutionDeleteService::SERVICE_ID);
        $deleteDeliveryExecutionRequest = new DeliveryExecutionDeleteRequest(
            $deliveryExecution->getDelivery(),
            $deliveryExecution,
            $runnerServiceContext->getTestSession()
        );
        $deleteDeliveryExecutionService->execute($deleteDeliveryExecutionRequest);
    }
}