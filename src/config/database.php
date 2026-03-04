<?php
/**
 * Database configuration & PDO connection helper.
 *
 * Usage:
 *   require_once __DIR__ . '/../config/database.php';
 *   $pdo = Database::connect();
 */

class Database
{
    private static ?PDO $instance = null;

    // Connection parameters — match docker-compose.yml
    private const HOST   = 'db';           // Docker service name
    private const PORT   = 3306;
    private const DBNAME = 'sports_borrow';
    private const USER   = 'root';
    private const PASS   = 'root';

    /**
     * Return a singleton PDO instance.
     */
    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                self::HOST,
                self::PORT,
                self::DBNAME
            );

            self::$instance = new PDO($dsn, self::USER, self::PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return self::$instance;
    }
}
