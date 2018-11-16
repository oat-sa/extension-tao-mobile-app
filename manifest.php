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
        'taoCe'               => '>=5.0.0',
        'tao'                 => '>=21.0.0',
        'taoQtiTest'          => '>=29.2.0',
        'taoDelivery'         => '>=9.12.0',
        'taoDeliveryRdf'      => '>=5.0.0',
        'taoStaticDeliveries' => '>=0.0.1',
        'taoSync'             => '>=2.0.0',
        'taoTestCenter'       => '>=4.1.0',
        'taoOauth'            => '>=1.1.0',
        'taoOffline'          => '>=1.3.0'
    ],
    'install' => [
        'php' => [
            oat\taoQtiTest\scripts\install\SetNewTestRunner::class,
            oat\taoSync\scripts\tool\RegisterHandShakeAuthAdapter::class,
            oat\taoOffline\scripts\tools\byOrganisationId\RegisterSyncServiceByOrgId::class,
            oat\taoOffline\scripts\tools\byOrganisationId\SetupSyncFormByOrgId::class
        ]
    ],
    'acl' => [
        ['grant', 'http://www.tao.lu/Ontologies/TAOMobileApp.rdf#TaoMobileAppManagerRole', ['ext'=>'taoMobileApp']],
        ['grant', 'http://www.tao.lu/Ontologies/generis.rdf#taoSyncManager', ['ext'=>'taoStaticDeliveries', 'mod' => 'RestStaticDeliveryExporter']]
    ],
    'routes' => [],
    'update' => 'oat\\taoMobileApp\\scripts\\update\\Updater',
    'managementRole' => 'http://www.tao.lu/Ontologies/TAOMobileApp.rdf#TaoMobileAppManagerRole'
];
