<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Incluir controladores
require_once 'api\controllers\estudianteController.php';
require_once 'api\controllers\librosController.php';
require_once 'api\controllers\generoController.php';
require_once 'api\controllers\stockController.php';
// require_once 'C:\path\to\InvoiceController.php';

// Instancias de controladores
$estudianteController = new EstudianteController();
$libroController = new LibroController();
$generoController = new GeneroController();
$stockController = new StockController();
// $invoiceController = new InvoiceController();

$requestMethod = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Verifica la ruta principal
$resource = $uri[1];
$id = isset($uri[2]) && is_numeric($uri[2]) ? (int) $uri[2] : null;

if ($resource === '') {
    echo json_encode(['message' => 'Bienvenido a la API']);
    exit();
}

if (!in_array($resource, ['estudiantes', 'genero','libros', 'stock', 'invoices'])) {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(["error" => "Recurso no encontrado"]);
    exit();
}

if ($requestMethod == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Selección del controlador en función del recurso solicitado
switch ($resource) {
    case 'estudiantes':
        handleEstudianteRoutes($estudianteController, $requestMethod, $id);
        break;

    case 'libros':
        handleLibrosRoutes($libroController, $requestMethod, $id);
        break;

    case 'genero':
        handleGeneroRoutes($generoController, $requestMethod, $id);
        break;

    case 'stock':
        handleStockRoutes($stockController, $requestMethod, $id);
        break;
        
    // case 'invoices':
    //     handleInvoiceRoutes($invoiceController, $requestMethod, $id);
    //     break;
}

// Funciones de manejo de rutas
function handleEstudianteRoutes($controller, $method, $id) {
    switch ($method) {
        case 'GET':
            $id ? $controller->show($id) : $controller->index();
            break;
        case 'POST':
            $controller->store();
            break;
        case 'PUT':
            $id ? $controller->update($id) : header("HTTP/1.1 400 Bad Request");
            break;
        case 'DELETE':
            $id ? $controller->delete($id) : header("HTTP/1.1 400 Bad Request");
            break;
        default:
            header("HTTP/1.1 405 Method Not Allowed");
    }
}

function handleLibrosRoutes($controller, $method, $id) {
    switch ($method) {
        case 'GET':
            $id ? $controller->show($id) : $controller->index();
            break;
        case 'POST':
            $controller->store();
            break;
        case 'PUT':
            $id ? $controller->update($id) : header("HTTP/1.1 400 Bad Request");
            break;
        case 'DELETE':
            $id ? $controller->delete($id) : header("HTTP/1.1 400 Bad Request");
            break;
        default:
            header("HTTP/1.1 405 Method Not Allowed");
    }
}

function handleGeneroRoutes($controller, $method, $id) {
    switch ($method) {
        case 'GET':
            $id ? $controller->show($id) : $controller->index();
            break;
        case 'POST':
            $controller->store();
            break;
        case 'PUT':
            $id ? $controller->update($id) : header("HTTP/1.1 400 Bad Request");
            break;
        case 'DELETE':
            $id ? $controller->delete($id) : header("HTTP/1.1 400 Bad Request");
            break;
        default:
            header("HTTP/1.1 405 Method Not Allowed");
    }
}

function handleStockRoutes($controller, $method, $id) {
    switch ($method) {
        case 'GET':
            $id ? $controller->show($id) : $controller->index();
            break;
        case 'POST':
            $controller->store();
            break;
        case 'PUT':
            $id ? $controller->update($id) : header("HTTP/1.1 400 Bad Request");
            break;
        case 'DELETE':
            $id ? $controller->delete($id) : header("HTTP/1.1 400 Bad Request");
            break;
        default:
            header("HTTP/1.1 405 Method Not Allowed");
    }
}

// function handleInvoiceRoutes($controller, $method, $id) {
//     switch ($method) {
//         case 'GET':
//             $id ? $controller->show($id) : $controller->index();
//             break;
//         case 'POST':
//             $controller->store();
//             break;
//         case 'PUT':
//             $id ? $controller->update($id) : header("HTTP/1.1 400 Bad Request");
//             break;
//         case 'DELETE':
//             $id ? $controller->delete($id) : header("HTTP/1.1 400 Bad Request");
//             break;
//         default:
//             header("HTTP/1.1 405 Method Not Allowed");
//     }
// }
?>
