<?php
class Database {
    private static $instance = null;

    public static function getConnection(): mysqli {
        if (self::$instance === null) {
            self::$instance = new mysqli("127.0.0.1", "root", "", "vouch_db", 3307);
            if (self::$instance->connect_error) {
                die("Connection failed: " . self::$instance->connect_error);
            }
        }
        return self::$instance;
    }
}