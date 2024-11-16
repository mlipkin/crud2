<?php

require_once '../models/genero.php';
require_once '../config/database.php';

class GeneroController {
    private $db;
    private $genero;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->genero = new Genero($this->db);
    }

    // Listar todos los géneros
    public function index() {
        $genero = $this->genero->getAll();
        echo json_encode($genero);
    }

    // Mostrar un género específico
    public function show($id) {
        $genero = $this->genero->getById($id);
        echo json_encode($genero);
    }

    // Crear un nuevo género
    public function store() {
        $data = json_decode(file_get_contents("php://input"));

        $this->genero->setGenero($data->genero);

        if ($this->genero->create()) {
            echo json_encode([
                "message" => "Género creado exitosamente.",
                "status" => 201
            ]);
        } else {
            echo json_encode([
                "message" => "No se pudo crear el género.",
                "status" => 500
            ]);
        }
    }

    // Actualizar un género existente
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        // Obtener los datos actuales del género
        $generoData = $this->genero->getById($id);

        if ($generoData) {
            $this->genero->setId($id);
            $this->genero->setGenero($data->genero ?? $generoData['genero']); // Actualizar si hay nuevos datos

            if ($this->genero->update()) {
                echo json_encode([
                    "message" => "Género actualizado exitosamente.",
                    "status" => 201
                ]);
            } else {
                echo json_encode([
                    "message" => "No se pudo actualizar el género.",
                    "status" => 500
                ]);
            }
        } else {
            echo json_encode([
                "message" => "Género no encontrado.",
                "status" => 404
            ]);
        }
    }

    // Eliminar un género
    public function delete($id) {
        if ($this->genero->delete($id)) {
            echo json_encode([
                "message" => "Género eliminado exitosamente.",
                "status" => 201
            ]);
        } else {
            echo json_encode([
                "message" => "No se pudo eliminar el género.",
                "status" => 500
            ]);
        }
    }
}
