<?php

class Estudiante {
    private $conn;
    private $table_name = "Estudiantes";

    public $estudiante_id;
    private $estudiante_nombre;
    private $estudiante_mail;
    private $estudiante_telefono;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->estudiante_id;
    }

    public function getNombre() {
        return $this->estudiante_nombre;
    }

    public function getEmail() {
        return $this->estudiante_mail;
    }

    public function getTelefono() {
        return $this->estudiante_telefono;
    }

    // Setters
    public function setId($estudiante_id) {
        $this->estudiante_id = htmlspecialchars(strip_tags($estudiante_id));
    }

    public function setNombre($estudiante_nombre) {
        $this->estudiante_nombre = htmlspecialchars(strip_tags($estudiante_nombre));
    }

    public function setEmail($estudiante_mail) {
        if (filter_var($estudiante_mail, FILTER_VALIDATE_EMAIL)) {
            $this->estudiante_mail = $estudiante_mail;
        } else {
            throw new Exception("Email no válido");
        }
    }

    public function setTelefono($estudiante_telefono) {
        $this->estudiante_telefono = htmlspecialchars(strip_tags($estudiante_telefono));
    }

    // Obtener todos los estudiantes
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ["message" => "No hay estudiantes disponibles"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener estudiantes: " . $e->getMessage()];
        }
    }

    // Obtener estudiante por ID
    public function getById($estudiante_id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE estudiante_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $estudiante_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return ["message" => "Estudiante no existe"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener estudiante: " . $e->getMessage()];
        }
    }

    // Crear estudiante
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (estudiante_nombre, estudiante_mail, estudiante_telefono) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->estudiante_nombre);
            $stmt->bindParam(2, $this->estudiante_mail);
            $stmt->bindParam(3, $this->estudiante_telefono);

            if ($stmt->execute()) {
                return ["message" => "Estudiante creado exitosamente"];
            }
            return ["error" => "No se pudo crear el estudiante"];
        } catch (PDOException $e) {
            return ["error" => "Error al crear estudiante: " . $e->getMessage()];
        }
    }

    // Actualizar estudiante
    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET estudiante_nombre = ?, estudiante_mail = ?, estudiante_telefono = ? WHERE estudiante_id = ?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->estudiante_nombre);
            $stmt->bindParam(2, $this->estudiante_mail);
            $stmt->bindParam(3, $this->estudiante_telefono);
            $stmt->bindParam(4, $this->estudiante_id);

            if ($stmt->execute()) {
                return ["message" => "Estudiante actualizado exitosamente"];
            }
            return ["error" => "No se pudo actualizar el estudiante"];
        } catch (PDOException $e) {
            return ["error" => "Error al actualizar estudiante: " . $e->getMessage()];
        }
    }

    // Eliminar estudiante
    public function delete($estudiante_id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE estudiante_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $estudiante_id);

            if ($stmt->execute()) {
                return ["message" => "Estudiante eliminado exitosamente"];
            }
            return ["error" => "No se pudo eliminar el estudiante"];
        } catch (PDOException $e) {
            return ["error" => "Error al eliminar estudiante: " . $e->getMessage()];
        }
    }
}
