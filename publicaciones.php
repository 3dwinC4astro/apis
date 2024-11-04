<?php
// Configuración de la base de datos
$host = 'bqiqscknwgwayykud3x4-mysql.services.clever-cloud.com';
$dbname = 'bqiqscknwgwayykud3x4';
$user = 'ujcxuevnoxsjlttb';
$password = 'BFy8QtjmFA8iyisgbYV9';
$port = '3306';

// Configurar los encabezados de CORS
header('Access-Control-Allow-Origin: *'); // Permitir solicitudes desde cualquier origen
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Métodos permitidos
header('Access-Control-Allow-Headers: Content-Type'); // Encabezados permitidos
header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    http_response_code(500); // Código de error del servidor
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Definir la consulta
$sql = "SELECT titulo, contenido, imagen, fecha_creacion FROM publicaciones";
$result = $conn->query($sql);

// Crear un array para almacenar las publicaciones
$publicaciones = [];

// Verificar si hay resultados y agregarlos al array
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publicaciones[] = $row;
    }
}

// Devolver los datos en formato JSON
echo json_encode($publicaciones);

// Cerrar la conexión
$conn->close();
?>
