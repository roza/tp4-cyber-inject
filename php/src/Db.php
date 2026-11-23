<?php

namespace Kineo;

use PDO;

/**
 * Connexion à la base, configurée par variables d'environnement :
 *   DB_DSN   par défaut : SQLite, fichier var/kinegest.sqlite
 *            MariaDB    : mysql:host=127.0.0.1;dbname=kinegest
 *   DB_USER, DB_PASS (inutiles avec SQLite)
 */
final class Db
{
    public static function connexion(): PDO
    {
        $dsn = getenv('DB_DSN') ?: 'sqlite:' . __DIR__ . '/../var/kinegest.sqlite';
        return new PDO($dsn, getenv('DB_USER') ?: null, getenv('DB_PASS') ?: null),[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false;
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO:FETCH_ASSOC
        ]   
    }
}
