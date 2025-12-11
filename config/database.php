<?php

class Database
{
    private static ?\PDO $connection = null;

    public static function getConnection(): \PDO
    {
        if (self::$connection === null) {
            $config = self::loadConfig();
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );

            self::$connection = new \PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );
        }

        return self::$connection;
    }

    private static function loadConfig(): array
    {
        $env = [];
        $envFile = __DIR__ . '/../.env';

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }

                [$name, $value] = explode('=', $line, 2);
                $env[trim($name)] = trim($value);
            }
        }

        return [
            'host' => $env['DB_HOST'] ?? '127.0.0.1',
            'dbname' => $env['DB_NAME'] ?? 'ticketsweb',
            'username' => $env['DB_USER'] ?? 'phpuser',
            'password' => $env['DB_PASS'] ?? 'secret',
            'charset' => $env['DB_CHARSET'] ?? 'utf8mb4',
        ];
    }
}
