<?php return array(
    'root' => array(
        'name' => 'biotime/api',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => NULL,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'biotime/api' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => NULL,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'curl/curl' => array(
            'pretty_version' => '2.5.0',
            'version' => '2.5.0.0',
            'reference' => 'c4f8799c471e43b7c782c77d5c6e178d0465e210',
            'type' => 'library',
            'install_path' => __DIR__ . '/../curl/curl',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'rtgroup/dbconnect' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => '5f75e457d9e522c9b080526e83e9c098ad015756',
            'type' => 'library',
            'install_path' => __DIR__ . '/../rtgroup/dbconnect',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'rtgroup/http-router' => array(
            'pretty_version' => 'dev-master',
            'version' => 'dev-master',
            'reference' => 'd5b51a7c5e9e698e94c812bc19a4926dc2e5a895',
            'type' => 'library',
            'install_path' => __DIR__ . '/../rtgroup/http-router',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
    ),
);
