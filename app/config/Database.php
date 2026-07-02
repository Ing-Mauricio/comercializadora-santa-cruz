<?php
/**
 * Clase Database
 * Maneja la conexión PDO a MySQL (patrón Singleton)
 */
class Database
{
    // ==== CONFIGURACIÓN: ajusta estos datos según tu XAMPP ====
    private static $host   = 'localhost';
    private static $dbname = 'comercializadora';
    private static $user   = 'root';
    private static $pass   = '';
    // ============================================================

    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8mb4';
                self::$connection = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES    => false,
                ]);
            } catch (PDOException $e) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
