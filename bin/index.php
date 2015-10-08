<?php

$user = exec('whoami');

if($user == 'root')
    $configPath = '/root/.hostr/';
else
    $configPath = '/home/'.exec('whoami').'/.hostr/';

if(!file_exists($configPath)){
    mkdir($configPath);
    file_put_contents($configPath.'.env', 'HOSTS_FILE_PATH="/etc/hosts"
BACKUP_HOSTS_FILE_PATH="/etc/hosts"');
}

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv($configPath);
$dotenv->load();

$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/../config/bindings.php');
$container = $containerBuilder->build();

$application = new Symfony\Component\Console\Application('Hostr', '1.1.0');

$application->add($container->get('Hostr\Commands\AddCommand'));
$application->add($container->get('Hostr\Commands\ShowCommand'));
$application->add($container->get('Hostr\Commands\TidyCommand'));
$application->add($container->get('Hostr\Commands\CheckCommand'));
$application->add($container->get('Hostr\Commands\RemoveCommand'));
$application->add($container->get('Hostr\Commands\BackupCommand'));
$application->add($container->get('Hostr\Commands\RestoreCommand'));

$application->run();
