<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

return [
    'name' => 'taoMobileApp',
    'label' => 'TAO Mobile App',
    'description' => 'Extension providing server-side components for the TAO Mobile App.',
    'license' => 'Proprietary',
    'version' => '1.0.0',
    'author' => 'Open Assessment Technologies',
    'requires' => [
        'taoCe' => '>=3.18.0',
        'tao' => '>=19.1.0',
        'taoQtiTest' => '>=25.7.0',
        'taoDelivery' => '>=9.12.0',
        'taoDeliveryRdf' => '>=5.0.0'
    ],
    'install' => [
        'php' => [
            oat\taoQtiTest\scripts\install\SetNewTestRunner::class,
            oat\taoMobileApp\scripts\install\RegisterMobileAssemblerService::class,
            oat\taoMobileApp\scripts\install\RegisterDeleteDeliveryExecutionService::class
        ]
    ],
    'acl' => [
        ['grant', 'http://www.tao.lu/Ontologies/TAOMobileApp.rdf#TaoMobileAppManagerRole', ['ext'=>'taoMobileApp']]
    ],
    'routes' => [],
    'update' => 'oat\\taoMobileApp\\scripts\\update\\Updater',
    'managementRole' => 'http://www.tao.lu/Ontologies/TAOMobileApp.rdf#TaoMobileAppManagerRole'
];
