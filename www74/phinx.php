<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST') ?: 'mysql',
            'name' => getenv('DB_NAME') ?: 'php',
            'user' => getenv('DB_USER') ?: 'root',
            'pass' => getenv('DB_PASS') ?: '123456',
            'port' => getenv('DB_PORT') ?: 3306,
            'charset' => 'utf8'
        ]
    ],
    'version_order' => 'creation'
];
