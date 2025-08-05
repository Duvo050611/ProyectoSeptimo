<?php 
session_start();
require_once '../../conexionbd.php';

// Security: Validate session
if (!isset($_SESSION['login']) || !is_array($_SESSION['login']) || !isset($_SESSION['login']['id_usua'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

if (!isset($_SESSION['pac']) || !is_numeric($_SESSION['pac'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de atención no válido']);
    exit;
}

$usuario = $_SESSION['login'];
$id_usua = $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];

$output = array();

// Base query - usando la tabla correcta
$sql = "SELECT id_trans_graf, hora, sistg, diastg, fcardg, frespg, satg, tempg, fecha_g, cuenta 
        FROM dat_trans_grafico 
        WHERE id_atencion = ?";

// Count total records
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$totalResult = $stmt->get_result();
$total_all_rows = $totalResult->num_rows;
$stmt->close();

$columns = array(
    0 => 'id_trans_graf',
    1 => 'fecha_g',
    2 => 'hora',
    3 => 'sistg',
    4 => 'fcardg',
    5 => 'frespg',
    6 => 'satg',
    7 => 'tempg'
);

// Add ordering
if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    if (isset($columns[$column_name])) {
        $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
    }
} else {
    $sql .= " ORDER BY id_trans_graf DESC";
}

// Add pagination
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $start = intval($_POST['start']);
    $length = intval($_POST['length']);
    $sql .= " LIMIT ?, ?";
}

// Execute query with parameters
$stmt = $conexion->prepare($sql);
if (isset($_POST['length']) && $_POST['length'] != -1) {
    $stmt->bind_param("iii", $id_atencion, $start, $length);
} else {
    $stmt->bind_param("i", $id_atencion);
}
$stmt->execute();
$result = $stmt->get_result();
$count_rows = $result->num_rows;

$data = array();
// Calcular el contador inicial basado en la paginación
$contador = isset($_POST['start']) ? intval($_POST['start']) + 1 : 1;
while ($row = $result->fetch_assoc()) {
    $sub_array = array();
    $sub_array[] = $contador; // Usar contador secuencial en lugar del ID real
    $sub_array[] = htmlspecialchars($row['fecha_g'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = htmlspecialchars($row['hora'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = htmlspecialchars($row['sistg'], ENT_QUOTES, 'UTF-8') . '/' . htmlspecialchars($row['diastg'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = htmlspecialchars($row['fcardg'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = htmlspecialchars($row['frespg'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = htmlspecialchars($row['satg'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = htmlspecialchars($row['tempg'], ENT_QUOTES, 'UTF-8');
    $sub_array[] = '
        <div class="action-buttons-container">
            <a href="javascript:void(0);" 
               data-id="' . htmlspecialchars($row['id_trans_graf'], ENT_QUOTES, 'UTF-8') . '" 
               class="btn btn-action btn-edit-modern editbtnS" 
               title="Editar registro">
                <i class="fas fa-edit"></i>
                <span>Editar</span>
            </a>
            <a href="javascript:void(0);" 
               data-id="' . htmlspecialchars($row['id_trans_graf'], ENT_QUOTES, 'UTF-8') . '" 
               class="btn btn-action btn-delete-modern deleteBtnS" 
               title="Eliminar registro">
                <i class="fas fa-trash-alt"></i>
                <span>Eliminar</span>
            </a>
        </div>
    ';
    $data[] = $sub_array;
    $contador++; // Incrementar contador
}

$stmt->close();

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' => $total_all_rows,
    'data' => $data,
);

echo json_encode($output);
?>
