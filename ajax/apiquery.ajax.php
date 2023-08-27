<?php
session_start();
require_once dirname( __DIR__ ).'/modelos/conexion.php';

// Obtén la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

// Obtén la consulta del cuerpo de la solicitud
$query = $data['query'];

// Realiza cualquier procesamiento adicional que necesites con la consulta
// ...

try {
    $stmt = Conexion::conectar()->prepare($query);

    $stmt->execute();

    if ($stmt) {
        $stmt -> fetch(PDO::FETCH_ASSOC);
    } else {
        return "error";
    }
} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}


// Aquí puedes ejecutar la consulta en la base de datos y obtener los resultados

// Prepara la respuesta
$response = array(
  'status' => 'success',
  'data' => $stmt
);

// Envía la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
