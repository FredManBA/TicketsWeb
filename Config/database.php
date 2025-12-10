<?php

use PDO;

/**
 * Gestiona la conexión PDO a la base de datos usando un singleton sencillo.
 */
class Database
{
    private static $connection = null;

    private function __construct()
    {
    }

    /**
     * Devuelve una instancia única de PDO configurada.
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = self::loadConfig();

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
            self::$connection = new PDO($dsn, $config['username'], $config['password']);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$connection;
    }

    private static function loadConfig(): array
    {
        $envFile = __DIR__ . '/../.env';
        $env = [];

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                $env[$name] = $value;
            }
        }

        return [
            'host' => $env['DB_HOST'] ?? '127.0.0.1',
            'dbname' => $env['DB_NAME'] ?? 'mydatabase',
            'username' => $env['DB_USER'] ?? 'root',
            'password' => $env['DB_PASS'] ?? 'toor',
            'charset' => $env['DB_CHARSET'] ?? 'utf8mb4',
        ];
    }
}
