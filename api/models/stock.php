<?php

class Stock {
    private $conn;
    private $table_name = "stock";

    public $stock_id;
    private $libro_id;
    private $cantidad_total;
    private $cantidad_disponible;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getStockId() {
        return $this->stock_id;
    }

    public function getLibroId() {
        return $this->libro_id;
    }

    public function getCantidadTotal() {
        return $this->cantidad_total;
    }

    public function getCantidadDisponible() {
        return $this->cantidad_disponible;
    }

    // Setters
    public function setStockId($stock_id) {
        $this->stock_id = htmlspecialchars(strip_tags($stock_id));
    }

    public function setLibroId($libro_id) {
        $this->libro_id = htmlspecialchars(strip_tags($libro_id));
    }

    public function setCantidadTotal($cantidad_total) {
        $this->cantidad_total = htmlspecialchars(strip_tags($cantidad_total));
    }

    public function setCantidadDisponible($cantidad_disponible) {
        $this->cantidad_disponible = htmlspecialchars(strip_tags($cantidad_disponible));
    }

    // Obtener todos los registros de stock
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ["message" => "No hay registros de stock disponibles"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener registros de stock: " . $e->getMessage()];
        }
    }

    // Obtener registro de stock por ID
    public function getById($stock_id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE stock_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $stock_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return ["message" => "Registro de stock no existe"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener registro de stock: " . $e->getMessage()];
        }
    }

    // Crear registro de stock
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (libro_id, cantidad_total, cantidad_disponible) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->libro_id);
            $stmt->bindParam(2, $this->cantidad_total);
            $stmt->bindParam(3, $this->cantidad_disponible);

            if ($stmt->execute()) {
                return ["message" => "Registro de stock creado exitosamente"];
            }
            return ["error" => "No se pudo crear el registro de stock"];
        } catch (PDOException $e) {
            return ["error" => "Error al crear registro de stock: " . $e->getMessage()];
        }
    }

    // Actualizar registro de stock
    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET libro_id = ?, cantidad_total = ?, cantidad_disponible = ? WHERE stock_id = ?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->libro_id);
            $stmt->bindParam(2, $this->cantidad_total);
            $stmt->bindParam(3, $this->cantidad_disponible);
            $stmt->bindParam(4, $this->stock_id);

            if ($stmt->execute()) {
                return ["message" => "Registro de stock actualizado exitosamente"];
            }
            return ["error" => "No se pudo actualizar el registro de stock"];
        } catch (PDOException $e) {
            return ["error" => "Error al actualizar registro de stock: " . $e->getMessage()];
        }
    }

    // Eliminar registro de stock
    public function delete($stock_id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE stock_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $stock_id);

            if ($stmt->execute()) {
                return ["message" => "Registro de stock eliminado exitosamente"];
            }
            return ["error" => "No se pudo eliminar el registro de stock"];
        } catch (PDOException $e) {
            return ["error" => "Error al eliminar registro de stock: " . $e->getMessage()];
        }
    }
}
