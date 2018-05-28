<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA.
 */

return [
    'name' => 'taoMobileApp',
    'label' => 'TAO Mobile App',
    'description' => 'Extension providing server-side components for the TAO Mobile App.',
    'license' => 'Proprietary',
    'version' => '0.1.0',
    'author' => 'Open Assessment Technologies',
    'requires' => [
        'taoCe' => '3.18.0',
        'tao' => '>=19.1.0'
    ],
    'acl' => [],
    'routes' => [],
    'update' => 'oat\\taoMobileApp\\scripts\\update\\Updater'
];
