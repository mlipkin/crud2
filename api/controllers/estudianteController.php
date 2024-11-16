<?php

require_once '../models/estudiantes.php';
require_once '../config/database.php';

class EstudianteController {
    private $db;
    private $estudiante;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->estudiante = new Estudiante($this->db);
    }

    // Obtener todos los estudiantes
    public function index() {
        $estudiantes = $this->estudiante->getAll();
        echo json_encode($estudiantes);
    }

    // Obtener un estudiante por ID
    public function show($id) {
        $estudiante = $this->estudiante->getById($id);
        echo json_encode($estudiante);
    }

    // Crear un nuevo estudiante
    public function store() {
        $data = json_decode(file_get_contents("php://input"));
    
        $this->estudiante->setNombre($data->estudiante_nombre);
        $this->estudiante->setEmail($data->estudiante_mail);
        $this->estudiante->setTelefono($data->estudiante_telefono);
    
        if ($this->estudiante->create()) {
            echo json_encode(["message" => "Estudiante creado exitosamente.",
                              "status"  => 201]);  
        } else {
            echo json_encode(["message" => "No se pudo crear el estudiante.",
                              "status"  => 500]);
        }
    }

    // Actualizar un estudiante existente
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        // Cargar los datos actuales del estudiante
        $estudianteData = $this->estudiante->getById($id);

        if ($estudianteData) {
            $this->estudiante->setId($id);
            $this->estudiante->setNombre($data->estudiante_nombre ?? $estudianteData['estudiante_nombre']);
            $this->estudiante->setEmail($data->estudiante_mail ?? $estudianteData['estudiante_mail']);
            $this->estudiante->setTelefono($data->estudiante_telefono ?? $estudianteData['estudiante_telefono']);
    
            if ($this->estudiante->update()) {
                echo json_encode(["message" => "Estudiante actualizado exitosamente.",
                                  "status"  => 201]);
            } else {
                echo json_encode(["message" => "No se pudo actualizar el estudiante.",
                                  "status"  => 500]);
            }
        } else {
            echo json_encode(["message" => "Estudiante no encontrado.",
                              "status"  => 404]);
        }
    }
    
    // Eliminar un estudiante
    public function delete($id) {
        if ($this->estudiante->delete($id)) {
            echo json_encode(["message" => "Estudiante eliminado exitosamente.",
                              "status"  => 201]);
        } else {
            echo json_encode(["message" => "No se pudo eliminar el estudiante.",
                              "status"  => 500]);
        }
    }
}
