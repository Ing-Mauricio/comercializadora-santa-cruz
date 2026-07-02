<?php
class Cliente extends Model
{
    public function all(string $busqueda = '')
    {
        if ($busqueda !== '') {
            $stmt = $this->db->prepare("SELECT * FROM clientes WHERE estado = 1 AND (nombre LIKE ? OR apellido LIKE ? OR nit_ci LIKE ?) ORDER BY id DESC");
            $like = "%$busqueda%";
            $stmt->execute([$like, $like, $like]);
        } else {
            $stmt = $this->db->query("SELECT * FROM clientes WHERE estado = 1 ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("INSERT INTO clientes (nombre, apellido, nit_ci, email, telefono, direccion) VALUES (?,?,?,?,?,?)");
        return $stmt->execute([
            $data['nombre'], $data['apellido'], $data['nit_ci'],
            $data['email'], $data['telefono'], $data['direccion']
        ]);
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->db->prepare("UPDATE clientes SET nombre=?, apellido=?, nit_ci=?, email=?, telefono=?, direccion=? WHERE id=?");
        return $stmt->execute([
            $data['nombre'], $data['apellido'], $data['nit_ci'],
            $data['email'], $data['telefono'], $data['direccion'], $id
        ]);
    }

    public function delete(int $id)
    {
        // Borrado lógico para conservar historial en ventas
        $stmt = $this->db->prepare("UPDATE clientes SET estado = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function total()
    {
        return $this->db->query("SELECT COUNT(*) as total FROM clientes WHERE estado = 1")->fetch()['total'];
    }
}
