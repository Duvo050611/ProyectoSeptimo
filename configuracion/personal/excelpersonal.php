<?php 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= lista_personal.xls");
 ?>
<head>
	<meta charset="UTF-8"/>
</head>
<table class="table table-bordered table-striped" id="mytable">

                    <thead class="thead" style="background-color: #0c675e">
                    <tr>
                        <th>ID</th>
                        <th>CURP</th>
                        <th>NOMBRE</th>
                        <th>FECHA NAC</th>
                        <th>CÉDULA</th>
                        <th>FUNCIÓN</th>
                        <th>TELÉFONO</th>
                        <th>E-MAIL</th>        
                        <th>PREFIJO</th>
                        <th>USUARIO</th>
                        <th>CLAVE</th>
                        <th>ROL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    include '../../conexionbd.php';
                    $resultado2 = $conexion->query("SELECT * FROM reg_usuarios u, rol r where u.id_rol = r.id_rol") or die($conexion->error);
                    $no = 1;
                    while ($row = $resultado2->fetch_assoc()) {
                         $date=date_create($row['fecnac']);
                         $fechanac = date_format($date,"d-m-Y");
                         echo '<tr>'
                            . '<td>' . $row['id_usua'] . '</td>'
                            . '<td>' . $row['curp_u'] . '</td>'
                            . '<td>' . $row['papell'] . '</td>'
                            . '<td>' . $row['fecnac'] . '</td>'
                           
                            . '<td>' . $row['cedp'] . '</td>'
                            . '<td>' . $row['cargp'] . '</td>'
                            . '<td>' . $row['tel'] . '</td>'
                            . '<td>' . $row['email'] . '</td>'
                            . '<td>' . $row['pre'] . '</td>'
                            . '<td>' . $row['nombre'] . '</td>'
                            . '<td>' . $row['pass'] . '</td>'
                            . '<td>' . $row['rol'] . '</td>'
                            .'</tr>';
                        $no++;
                    }
                    ?>
                    </tbody>
                </table>