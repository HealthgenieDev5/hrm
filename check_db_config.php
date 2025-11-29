<?php
require 'vendor/autoload.php';
$config = new \Config\Database();
echo 'Database: ' . $config->default['database'] . PHP_EOL;
echo 'Hostname: ' . $config->default['hostname'] . PHP_EOL;
echo 'Username: ' . $config->default['username'] . PHP_EOL;

