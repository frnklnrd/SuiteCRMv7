<?php

$sugar_config['dbconfig'] = [
    'db_host_name' => '127.0.0.1',
    'db_host_instance' => 'SQLEXPRESS',
    'db_user_name' => 'root',
    'db_password' => '',
    'db_name' => 'suitecrm',
    'db_type' => 'mysql',
    'db_port' => '',
    'db_manager' => 'MysqliManager',
];

$sugar_config['site_url'] = 'http://127.0.0.1/application/backend/suitecrm';

// php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'
$sugar_config['oauth2_encryption_key'] = '4ORR78mmQJORTmq1MIuZ37xdeuDc0SkvhXJD5o95z9k=';

$sugar_config['developerMode'] = true;