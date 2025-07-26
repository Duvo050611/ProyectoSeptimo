<!DOCTYPE html>
<html>
<head>
    <title>Test Eliminación Simple</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
    <h2>Test Eliminación de Signos Vitales</h2>
    
    <?php
    session_start();
    include "../../conexionbd.php";
    
    // Mostrar algunos registros de prueba
    $sql = "SELECT id_trans_graf, fecha_g, sistg, diastg FROM dat_trans_grafico ORDER BY id_trans_graf DESC LIMIT 5";
    $result = $conexion->query($sql);
    
    if ($result && $result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Fecha</th><th>Sistólica</th><th>Diastólica</th><th>Acción</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id_trans_graf'] . "</td>";
            echo "<td>" . $row['fecha_g'] . "</td>";
            echo "<td>" . $row['sistg'] . "</td>";
            echo "<td>" . $row['diastg'] . "</td>";
            echo "<td><button class='btn-eliminar' data-id='" . $row['id_trans_graf'] . "'>Eliminar</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay registros</p>";
    }
    ?>
    
    <script>
    $(document).ready(function() {
        console.log('jQuery cargado, versión:', $.fn.jquery);
        
        $('.btn-eliminar').on('click', function() {
            const id = $(this).data('id');
            console.log('Eliminando ID:', id);
            
            if (confirm('¿Eliminar registro ' + id + '?')) {
                $.ajax({
                    url: 'eliminar_signos_vitales.php',
                    method: 'POST',
                    data: {
                        id_registro: id,
                        action: 'eliminar'
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Respuesta:', response);
                        if (response.success) {
                            alert('Eliminado correctamente');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', error);
                        console.error('Response:', xhr.responseText);
                        alert('Error de conexión: ' + error);
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
