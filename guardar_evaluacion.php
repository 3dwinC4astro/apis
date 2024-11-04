<?php
$host = 'bqiqscknwgwayykud3x4-mysql.services.clever-cloud.com';
$dbname = 'bqiqscknwgwayykud3x4';
$user = 'ujcxuevnoxsjlttb';
$password = 'BFy8QtjmFA8iyisgbYV9';
$port = '3306';

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Validar que los datos no estén vacíos
if (empty($data['identificacion']) || empty($data['nombre']) || empty($data['edad']) || empty($data['sexo']) ||
    empty($data['colesterol_total']) || empty($data['colesterol_hdl']) || empty($data['presion_arterial_sistolica'])) {
    http_response_code(400);
    echo json_encode(["message" => "Todos los campos son requeridos."]);
    exit();
}

// Validar tipos de datos
$data['fumador'] = isset($data['fumador']) ? (int)(bool)$data['fumador'] : 0; // Convertir a 0 o 1
$data['diabetes'] = isset($data['diabetes']) ? (int)(bool)$data['diabetes'] : 0; // Convertir a 0 o 1

// Obtener la fecha actual
$fechaRegistro = date('Y-m-d');

// Preparar la consulta SQL
$stmt = $conn->prepare("INSERT INTO evaluaciones (identificacion, nombre, edad, sexo, colesterol_total, colesterol_hdl, presion_arterial_sistolica, fumador, diabetes, fecha, riesgo_cardiovascular) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isissddiiss",
    $data['identificacion'], 
    $data['nombre'], 
    $data['edad'], 
    $data['sexo'], 
    $data['colesterol_total'], 
    $data['colesterol_hdl'], 
    $data['presion_arterial_sistolica'], 
    $data['fumador'], 
    $data['diabetes'],
    $fechaRegistro,
    $data['riesgo']
);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(["message" => "Datos guardados correctamente."]);
} else {
    echo json_encode(["message" => "Error al guardar los datos: " . $stmt->error]);
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
