<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

$paths = ['src/Entity'];
$isDevMode = true;

// the connection configuration
$dbParams = [
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'host' => 'db',
    'password' => 'rootroot',
    'dbname' => 'app',
];

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);

Type::addType('uuid_binary', 'Ramsey\Uuid\Doctrine\UuidBinaryType');
$entityManager->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('uuid_binary', 'binary');
