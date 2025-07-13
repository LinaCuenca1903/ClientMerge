<?php
class Conexion {
    public static function conectar() {
        try {
            $conn = new PDO("mysql:host=localhost;dbname=crm_callcenter", "root", "");
            $conn->exec("set names utf8");
            return $conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>
