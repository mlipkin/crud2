<?php

require_once '../models/libros.php'; // Asegúrate de que la ruta al modelo sea correcta
require_once '../config/database.php';

class LibroController {
    private $db;
    private $libro;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->libro = new Libro($this->db);
    }

    // Obtener todos los libros
    public function index() {
        $libros = $this->libro->getAll();
        echo json_encode($libros);
    }

    // Obtener un libro por ID
    public function show($id) {
        $libro = $this->libro->getById($id);
        echo json_encode($libro);
    }

    // Crear un nuevo libro
    public function store() {
        $data = json_decode(file_get_contents("php://input"));
        
        $this->libro->setTitulo($data->libro_titulo);
        $this->libro->setAutor($data->libro_autor);
        $this->libro->setEditorial($data->libro_editorial);
        $this->libro->setISBN($data->isbn);
        $this->libro->setGeneroId($data->genero_id);

        if ($this->libro->create()) {
            echo json_encode(["message" => "Libro creado exitosamente.",
                              "status"  => 201]);  
        } else {
            echo json_encode(["message" => "No se pudo crear el libro.",
                              "status"  => 500]);
        }
    }

    // Actualizar un libro existente
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        // Primero debes cargar los datos del libro a actualizar
        $libroData = $this->libro->getById($id);

        if ($libroData) {
            $this->libro->setId($id);
            $this->libro->setTitulo($data->libro_titulo ?? $libroData['libro_titulo']);
            $this->libro->setAutor($data->libro_autor ?? $libroData['libro_autor']);
            $this->libro->setEditorial($data->libro_editorial ?? $libroData['libro_editorial']);
            $this->libro->setISBN($data->isbn ?? $libroData['isbn']);
            $this->libro->setGeneroId($data->genero_id ?? $libroData['genero_id']);

            if ($this->libro->update()) {
                echo json_encode(["message" => "Libro actualizado exitosamente.",
                                  "status"  => 201]);
            } else {
                echo json_encode(["message" => "No se pudo actualizar el libro.",
                                  "status"  => 500]);
            }
        } else {
            echo json_encode(["message" => "Libro no encontrado.",
                              "status"  => 404]);
        }
    }

    // Eliminar un libro
    public function delete($id) {
        if ($this->libro->delete($id)) {
            echo json_encode(["message" => "Libro eliminado exitosamente.",
                              "status"  => 201]);
        } else {
            echo json_encode(["message" => "No se pudo eliminar el libro.",
                              "status"  => 500]);
        }
    }
}
