<?php

require_once '../models/stock.php';
require_once '../config/database.php';

class StockController {
    private $db;
    private $stock;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->stock = new Stock($this->db);
    }

    // Obtener todos los registros de stock
    public function index() {
        $stockItems = $this->stock->getAll();
        echo json_encode($stockItems);
    }

    // Obtener un registro de stock por ID
    public function show($stock_id) {
        $stockItem = $this->stock->getById($stock_id);
        echo json_encode($stockItem);
    }

    // Crear un nuevo registro de stock
    public function store() {
        $data = json_decode(file_get_contents("php://input"));
    
        $this->stock->setLibroId($data->libro_id);
        $this->stock->setCantidadTotal($data->cantidad_total);
        $this->stock->setCantidadDisponible($data->cantidad_disponible);

        if ($this->stock->create()) {
            echo json_encode(["message" => "Registro de stock creado exitosamente.",
                              "status"  => 201]);  
        } else {
            echo json_encode(["message" => "No se pudo crear el registro de stock.",
                              "status"  => 500]);
        }
    }

    // Actualizar un registro de stock existente
    public function update($stock_id) {
        $data = json_decode(file_get_contents("php://input"));
        
        // Cargar los datos actuales del registro de stock
        $stockData = $this->stock->getById($stock_id);
        
        if ($stockData) {
            $this->stock->setStockId($stock_id);
            $this->stock->setLibroId($data->libro_id ?? $stockData['libro_id']);
            $this->stock->setCantidadTotal($data->cantidad_total ?? $stockData['cantidad_total']);
            $this->stock->setCantidadDisponible($data->cantidad_disponible ?? $stockData['cantidad_disponible']);
    
            if ($this->stock->update()) {
                echo json_encode(["message" => "Registro de stock actualizado exitosamente.",
                                  "status"  => 201]);
            } else {
                echo json_encode(["message" => "No se pudo actualizar el registro de stock.",
                                  "status"  => 500]);
            }
        } else {
            echo json_encode(["message" => "Registro de stock no encontrado.",
                              "status"  => 404]);
        }
    }
    
    // Eliminar un registro de stock
    public function delete($stock_id) {
        if ($this->stock->delete($stock_id)) {
            echo json_encode(["message" => "Registro de stock eliminado exitosamente.",
                              "status"  => 201]);
        } else {
            echo json_encode(["message" => "No se pudo eliminar el registro de stock.",
                              "status"  => 500]);
        }
    }
}
