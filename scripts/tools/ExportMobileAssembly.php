<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

namespace oat\taoMobileApp\scripts\tools;

use oat\oatbox\extension\script\ScriptAction;
use oat\taoMobileApp\model\assemblies\MobileAssembler;
use common_report_Report as Report;

/**
 * Class ExportMobileAssembly
 *
 * This script aims at exporting Mobile Assemblies.
 *
 * Required Arguments:
 *  -i deliveryIdentifier, --deliveryIdentifier deliveryIdentifier
 *    The identifier of the Delivery to be exported as a Mobile Assembly
 *
 * Optional Arguments:
 *  -d destination, --destination destination
 *    A destination path on the local file system.
 *  -h help, --help help
 *    Prints a help statement
 *
 * @package oat\taoMobileApp\scripts\tools
 */
class ExportMobileAssembly extends ScriptAction
{
    /**
     * @return string
     */
    protected function provideDescription()
    {
        return 'TAO Mobile App - Export Mobile Assembly';
    }

    /**
     * @return array
     */
    protected function provideUsage()
    {
        return [
            'prefix' => 'h',
            'longPrefix' => 'help',
            'description' => 'Prints a help statement'
        ];
    }

    /**
     * @return array
     */
    protected function provideOptions()
    {
        return [
            'deliveryIdentifier' => [
                'prefix' => 'i',
                'longPrefix' => 'deliveryIdentifier',
                'required' => true,
                'description' => 'The identifier of the Delivery to be exported as a Mobile Assembly'
            ],
            'destination' => [
                'prefix' => 'd',
                'longPrefix' => 'destination',
                'required' => false,
                'description' => 'A destination path on the local file system.'
            ]
        ];
    }

    /**
     * @return \common_report_Report
     * @throws \Exception
     * @throws \common_Exception
     */
    protected function run()
    {
        // Main report.
        $report = new Report(
            Report::TYPE_INFO,
            "Script ended gracefully."
        );


        /** @var MobileAssembler $mobileAssembler */
        $mobileAssembler = $this->getServiceLocator()->get(MobileAssembler::SERVICE_ID);
        $file = $mobileAssembler->exportCompiledDelivery(new \core_kernel_classes_Resource($this->getOption('deliveryIdentifier')));

        $report->add(
            new Report(Report::TYPE_INFO, "Mobile Assembly exported in shared file system with file name '" . $file->getBasename()  . "'")
        );

        if ($this->hasOption('destination')) {
            $source = $file->readStream();
            if (($dest = @fopen($this->getOption('destination'), 'w')) !== false) {
                stream_copy_to_stream($source, $dest);

                $report->add(
                    new Report(Report::TYPE_INFO, "Mobile Assembly copied at '" . $this->getOption('destination') . "'.")
                );

                @fclose($source);
                @fclose($dest);
            } else {
                return new Report(Report::TYPE_ERROR, "Destination '" . $this->getOption('destination') . "' could not be open.");
            }
        }

        return $report;
    }
}