<?php

class Genero {
    private $conn;
    private $table_name = "Genero";

    public $genero_id;
    private $genero;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->genero_id;
    }

    public function getGenero() {
        return $this->genero;
    }

    // Setters
    public function setId($genero_id) {
        $this->genero_id = htmlspecialchars(strip_tags($genero_id));
    }

    public function setGenero($genero) {
        $this->genero = htmlspecialchars(strip_tags($genero));
    }

    // Obtener todos los géneros
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ["message" => "No hay géneros disponibles"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener géneros: " . $e->getMessage()];
        }
    }

    // Obtener género por ID
    public function getById($genero_id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE genero_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $genero_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return ["message" => "Género no existe"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener género: " . $e->getMessage()];
        }
    }

    // Crear género
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (genero) VALUES (?)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->genero);

            if ($stmt->execute()) {
                return ["message" => "Género creado exitosamente"];
            }
            return ["error" => "No se pudo crear el género"];
        } catch (PDOException $e) {
            return ["error" => "Error al crear género: " . $e->getMessage()];
        }
    }

    // Actualizar género
    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET genero = ? WHERE genero_id = ?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->genero);
            $stmt->bindParam(2, $this->genero_id);

            if ($stmt->execute()) {
                return ["message" => "Género actualizado exitosamente"];
            }
            return ["error" => "No se pudo actualizar el género"];
        } catch (PDOException $e) {
            return ["error" => "Error al actualizar género: " . $e->getMessage()];
        }
    }

    // Eliminar género
    public function delete($genero_id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE genero_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $genero_id);

            if ($stmt->execute()) {
                return ["message" => "Género eliminado exitosamente"];
            }
            return ["error" => "No se pudo eliminar el género"];
        } catch (PDOException $e) {
            return ["error" => "Error al eliminar género: " . $e->getMessage()];
        }
    }
}
