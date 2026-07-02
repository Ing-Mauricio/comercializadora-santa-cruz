<?php
/**
 * Clase base Model
 * Todos los modelos heredan la conexión PDO
 */
class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
