<?php

class Libro {
    private $conn;
    private $table_name = "Libros";

    public $libro_id;
    private $isbn;
    private $libro_titulo;
    private $libro_autor;
    private $libro_editorial;
    private $genero_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->libro_id;
    }

    public function getIsbn() {
        return $this->isbn;
    }

    public function getTitulo() {
        return $this->libro_titulo;
    }

    public function getAutor() {
        return $this->libro_autor;
    }

    public function getEditorial() {
        return $this->libro_editorial;
    }

    public function getGeneroId() {
        return $this->genero_id;
    }

    // Setters
    public function setId($id) {
        $this->libro_id = htmlspecialchars(strip_tags($id));
    }

    public function setIsbn($isbn) {
        $this->isbn = htmlspecialchars(strip_tags($isbn));
    }

    public function setTitulo($titulo) {
        $this->libro_titulo = htmlspecialchars(strip_tags($titulo));
    }

    public function setAutor($autor) {
        $this->libro_autor = htmlspecialchars(strip_tags($autor));
    }

    public function setEditorial($editorial) {
        $this->libro_editorial = htmlspecialchars(strip_tags($editorial));
    }

    public function setGeneroId($genero_id) {
        $this->genero_id = htmlspecialchars(strip_tags($genero_id));
    }

    // Obtener todos los libros
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return ["message" => "No hay libros disponibles"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener libros: " . $e->getMessage()];
        }
    }

    // Obtener libro por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE libro_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return ["message" => "El libro no existe"];
            }
            return $result;
        } catch (PDOException $e) {
            return ["error" => "Error al obtener libro: " . $e->getMessage()];
        }
    }

    // Crear libro
    public function create() {
        try {
            $query = "INSERT INTO " . $this->table_name . " (ISBN, libro_titulo, libro_autor, libro_editorial, genero_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->isbn);
            $stmt->bindParam(2, $this->libro_titulo);
            $stmt->bindParam(3, $this->libro_autor);
            $stmt->bindParam(4, $this->libro_editorial);
            $stmt->bindParam(5, $this->genero_id);

            if ($stmt->execute()) {
                return ["message" => "Libro creado exitosamente"];
            }
            return ["error" => "No se pudo crear el libro"];
        } catch (PDOException $e) {
            return ["error" => "Error al crear libro: " . $e->getMessage()];
        }
    }

    // Actualizar libro
    public function update() {
        try {
            $query = "UPDATE " . $this->table_name . " SET ISBN = ?, libro_titulo = ?, libro_autor = ?, libro_editorial = ?, genero_id = ? WHERE libro_id = ?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->isbn);
            $stmt->bindParam(2, $this->libro_titulo);
            $stmt->bindParam(3, $this->libro_autor);
            $stmt->bindParam(4, $this->libro_editorial);
            $stmt->bindParam(5, $this->genero_id);
            $stmt->bindParam(6, $this->libro_id);

            if ($stmt->execute()) {
                return ["message" => "Libro actualizado exitosamente"];
            }
            return ["error" => "No se pudo actualizar el libro"];
        } catch (PDOException $e) {
            return ["error" => "Error al actualizar libro: " . $e->getMessage()];
        }
    }

    // Eliminar libro
    public function delete($id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE libro_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id);

            if ($stmt->execute()) {
                return ["message" => "Libro eliminado exitosamente"];
            }
            return ["error" => "No se pudo eliminar el libro"];
        } catch (PDOException $e) {
            return ["error" => "Error al eliminar libro: " . $e->getMessage()];
        }
    }
}
