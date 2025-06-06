<?php

declare(strict_types=1);

namespace App\core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnexion(): PDO
    {

        if (self::$pdo === null) {
            $env = $_ENV;
            if (!isset($env['DB_HOST'], $env['DB_NAME'], $env['DB_USER'], $env['DB_PASSWORD'], $env['DB_PORT'])) {
                throw new \RuntimeException('Database environment variables');
            }
            $db_host = $env['DB_HOST'];
            $db_name = $env['DB_NAME'];
            $db_user = $env['DB_USER'];
            $db_password = $env['DB_PASSWORD'];
            $db_port = $env['DB_PORT'];

            try {
                self::$pdo = new PDO(
                    "mysql:host=$db_host;dbname=$db_name;port=$db_port",
                    $db_user,
                    $db_password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $pdo_ex) {
                die("Erreur de connexion : " . $pdo_ex->getMessage());
            }
        }
        // rend la connexion disponible
        return self::$pdo;
    }
}
