<?php

namespace utils;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use RuntimeException;

class Conexao {
    private static $entityManager;

    public static function getEntityManager() {
        if (self::$entityManager === null) {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [realpath(__DIR__ . '/../model')],
                isDevMode: true,
            );
            $rootPath = dirname(__DIR__, 2);

            if (file_exists($rootPath . '/.env')) {
                $dotenv = Dotenv::createImmutable($rootPath);
                $dotenv->safeLoad();
            }

            $driver = self::env('DB_DRIVER');
            $host = self::env('DB_HOST');

            $connectionParams = [
                'driver' => $driver,
                'host' => $host,
                'port' => self::env('DB_PORT'),
                'dbname' => self::env('DB_NAME'),
                'user' => self::env('DB_USER'),
                'password' => self::env('DB_PASSWORD'),
            ];

            $sslMode = self::env('DB_SSLMODE', '');

            if ($sslMode !== '') {
                $connectionParams['sslmode'] = $sslMode;
            }

            if ($driver === 'pdo_pgsql' && str_contains($host, '.neon.tech')) {
                $endpointId = explode('.', $host)[0];

                // Workaround para libpq antigo do XAMPP, que nao envia SNI para o Neon.
                $connectionParams['dbname'] .= ' options=endpoint=' . $endpointId;
            }

            $connection = DriverManager::getConnection($connectionParams, $config);

            self::$entityManager = new EntityManager($connection, $config);
        }
        return self::$entityManager;
    }

    private static function env(string $key, ?string $default = null): string
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null || $value === '') {
            if ($default !== null) {
                return $default;
            }

            throw new RuntimeException('Variavel de ambiente obrigatoria nao configurada: ' . $key);
        }

        return (string) $value;
    }
}
