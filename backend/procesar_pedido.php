<?php
// backend/procesar_pedido.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'Datos vacíos']);
    exit;
}

try {
    // Insertar cliente si no existe
    $email = $conn->real_escape_string($data['email']);
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $name = $conn->real_escape_string($data['name']);
        $phone = $conn->real_escape_string($data['phone']);
        $address = $conn->real_escape_string($data['address']);
        $city = $conn->real_escape_string($data['city']);
        $zip = $conn->real_escape_string($data['zip']);
        
        $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, direccion, ciudad, codigo_postal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $phone, $address, $city, $zip);
        $stmt->execute();
        $cliente_id = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $cliente_id = $row['id'];
    }

    // Insertar pedido
    $total = floatval($data['total']);
    $fecha = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("INSERT INTO pedidos (cliente_id, total, estado, fecha_pedido) VALUES (?, ?, ?, ?)");
    $estado = 'Pendiente';
    $stmt->bind_param("idss", $cliente_id, $total, $estado, $fecha);
    $stmt->execute();
    $pedido_id = $conn->insert_id;

    // Insertar items del pedido
    if (isset($data['cart']) && is_array($data['cart'])) {
        foreach ($data['cart'] as $item) {
            $producto_id = intval($item['id']);
            $cantidad = intval($item['qty']);
            $precio_unitario = floatval($item['price']);
            $color = isset($item['color']) ? $conn->real_escape_string($item['color']) : '';
            $talla = isset($item['size']) ? $conn->real_escape_string($item['size']) : '';
            
            $stmt = $conn->prepare("INSERT INTO pedidos_items (pedido_id, producto_id, cantidad, precio_unitario, color, talla) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidss", $pedido_id, $producto_id, $cantidad, $precio_unitario, $color, $talla);
            $stmt->execute();
        }
    }

    echo json_encode([
        'success' => true,
        'pedido_id' => $pedido_id,
        'mensaje' => 'Pedido registrado correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
