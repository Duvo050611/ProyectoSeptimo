<?php
session_start();
include "../../conexionbd.php";

echo "<h2>Test Eliminar Signos Vitales</h2>";

// Verificar conexión a la base de datos
if (!$conexion) {
    echo "<p style='color: red;'>ERROR: No hay conexión a la base de datos</p>";
    exit;
}

echo "<p style='color: blue;'>✓ Conexión a la base de datos exitosa</p>";

// Mostrar información de sesión
echo "<h3>Información de sesión:</h3>";
echo "<p>Session login: " . (isset($_SESSION['login']) ? "SÍ" : "NO") . "</p>";
if (isset($_SESSION['pac'])) {
    echo "<p>Paciente seleccionado: " . $_SESSION['pac'] . "</p>";
}

// Procesar eliminación ANTES de mostrar la tabla
if ($_POST) {
    echo "<h3>Procesando eliminación...</h3>";
    $id_registro = (int)$_POST['id_registro'];
    echo "<p>ID recibido: $id_registro</p>";
    
    if ($id_registro > 0) {
        // Primero verificar si existe
        $check_sql = "SELECT * FROM dat_trans_grafico WHERE id_trans_graf = $id_registro";
        $check_result = $conexion->query($check_sql);
        
        if ($check_result) {
            echo "<p>Consulta de verificación ejecutada correctamente</p>";
            if ($check_result->num_rows > 0) {
                echo "<p style='color: blue;'>✓ Registro encontrado, procediendo a eliminar...</p>";
                
                // Intentar eliminar
                $delete_sql = "DELETE FROM dat_trans_grafico WHERE id_trans_graf = $id_registro";
                echo "<p>Ejecutando: $delete_sql</p>";
                
                if ($conexion->query($delete_sql)) {
                    if ($conexion->affected_rows > 0) {
                        echo "<p style='color: green;'>✓ Registro ID $id_registro eliminado correctamente. Filas afectadas: " . $conexion->affected_rows . "</p>";
                    } else {
                        echo "<p style='color: orange;'>⚠ No se eliminó ningún registro. El ID $id_registro podría no existir.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>✗ Error al eliminar: " . $conexion->error . "</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ No se encontró el registro con ID $id_registro</p>";
            }
        } else {
            echo "<p style='color: red;'>✗ Error en la consulta de verificación: " . $conexion->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ ID de registro inválido.</p>";
    }
    
    echo "<br><a href='test_eliminar.php'>Recargar página para ver cambios</a><br><br>";
}

// Mostrar todos los registros actuales
echo "<h3>Registros actuales en dat_trans_grafico:</h3>";
$sql = "SELECT id_trans_graf, id_atencion, fecha_g, sistg, diastg FROM dat_trans_grafico ORDER BY id_trans_graf";
echo "<p>Ejecutando consulta: $sql</p>";
$result = $conexion->query($sql);

if (!$result) {
    echo "<p style='color: red;'>Error en la consulta: " . $conexion->error . "</p>";
} else {
    echo "<p style='color: blue;'>Consulta ejecutada correctamente. Registros encontrados: " . $result->num_rows . "</p>";
    
    if ($result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr style='background-color: #f0f0f0;'><th>ID</th><th>Atención</th><th>Fecha</th><th>Sistólica</th><th>Diastólica</th><th>Acción</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_trans_graf'] . "</td>";
            echo "<td>" . $row['id_atencion'] . "</td>";
            echo "<td>" . $row['fecha_g'] . "</td>";
            echo "<td>" . $row['sistg'] . "</td>";
            echo "<td>" . $row['diastg'] . "</td>";
            echo "<td><button onclick='eliminarRegistro(" . $row['id_trans_graf'] . ")' style='background-color: red; color: white; padding: 5px 10px;'>Eliminar</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay registros en la tabla dat_trans_grafico.</p>";
    }
}
?>

<script>
function eliminarRegistro(id) {
    console.log('Función eliminarRegistro llamada con ID:', id);
    
    if (confirm('¿Eliminar el registro ID ' + id + '?')) {
        console.log('Usuario confirmó eliminación');
        
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_registro';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        
        console.log('Enviando formulario con ID:', id);
        form.submit();
    } else {
        console.log('Usuario canceló eliminación');
    }
}

// Verificar que JavaScript se carga correctamente
console.log('JavaScript de test_eliminar.php cargado correctamente');
</script>

<style>
table {
    margin: 20px 0;
    border-collapse: collapse;
}
th, td {
    padding: 8px 12px;
    text-align: left;
    border: 1px solid #ddd;
}
th {
    background-color: #f2f2f2;
    font-weight: bold;
}
button {
    cursor: pointer;
    border: none;
    border-radius: 3px;
}
button:hover {
    opacity: 0.8;
}
</style>
