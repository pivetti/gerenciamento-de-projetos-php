<?php

namespace utils;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

class Conexao {
    private static $entityManager;

    public static function getEntityManager() {
        if (self::$entityManager === null) {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [realpath(__DIR__ . '/../model')],
                isDevMode: true,
            );
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();

            $connectionParams = [
                'driver' => $_ENV['DB_DRIVER'],
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
            ];

            if (!empty($_ENV['DB_SSLMODE'])) {
                $connectionParams['sslmode'] = $_ENV['DB_SSLMODE'];
            }

            if (($_ENV['DB_DRIVER'] ?? '') === 'pdo_pgsql' && str_contains($_ENV['DB_HOST'] ?? '', '.neon.tech')) {
                $endpointId = explode('.', $_ENV['DB_HOST'])[0];

                // Workaround para libpq antigo do XAMPP, que nao envia SNI para o Neon.
                $connectionParams['dbname'] .= ' options=endpoint=' . $endpointId;
            }

            $connection = DriverManager::getConnection($connectionParams, $config);

            self::$entityManager = new EntityManager($connection, $config);
        }
        return self::$entityManager;
    }
}
