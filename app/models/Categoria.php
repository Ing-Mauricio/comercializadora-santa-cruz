<?php
class Categoria extends Model
{
    public function all()
    {
        return $this->db->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?,?)");
        return $stmt->execute([$data['nombre'], $data['descripcion']]);
    }
}
