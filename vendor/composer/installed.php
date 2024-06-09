<?php return array(
    'root' => array(
        'name' => 'vendor/package-drive',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'project',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'danrovito/pokephp' => array(
            'pretty_version' => '1.3',
            'version' => '1.3.0.0',
            'reference' => 'd2a1d5a97f241a249a5f101d80042f3a4bf9d430',
            'type' => 'library',
            'install_path' => __DIR__ . '/../danrovito/pokephp',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'monolog/monolog' => array(
            'pretty_version' => '2.x-dev',
            'version' => '2.9999999.9999999.9999999-dev',
            'reference' => '884aa47a055ea3e8e351ceaf4dfc67f52fff44c0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../monolog/monolog',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'fe5ea303b0887d5caefd3d431c3e61ad47037001',
            'type' => 'library',
            'install_path' => __DIR__ . '/../psr/log',
            'aliases' => array(
                0 => '3.x-dev',
            ),
            'dev_requirement' => false,
        ),
        'psr/log-implementation' => array(
            'dev_requirement' => false,
            'provided' => array(
                0 => '1.0.0 || 2.0.0 || 3.0.0',
            ),
        ),
        'vendor/package-drive' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'project',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
