<?php
// Suprimir advertencias para evitar errores en la salida del PDF
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

require '../../fpdf/fpdf.php';
include '../../conexionbd.php';

$id_tratamiento = @$_GET['id_tratamiento'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

// Verificar que existe el tratamiento
$sql_trat = "SELECT * FROM dat_tratamientos_genericos WHERE id = $id_tratamiento AND id_atencion = $id_atencion";
$result_trat = $conexion->query($sql_trat);

if ($result_trat->num_rows == 0) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE REGISTRO DE TRATAMIENTO PARA ESTE PACIENTE", 
                type: "error",
                confirmButtonText: "ACEPTAR"
            }, function(isConfirm) { 
                if (isConfirm) {
                    window.close();
                }
            });
        });
    </script>';
    exit();
}

// Obtener datos del paciente (sin campos inexistentes)
$sql_pac = "SELECT p.papell, p.nom_pac, p.sapell, p.edad, p.sexo, p.Id_exp, p.folio, p.dir, p.tel, p.fecnac, p.ocup AS ocupacion 
            FROM paciente p WHERE p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);
$row_pac = $result_pac->fetch_assoc();

$nombre_completo = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
$folio = $row_pac['folio'];
$edad = $row_pac['edad'];
$sexo = $row_pac['sexo'];
$telefono = $row_pac['tel'];
$fecha_nacimiento = $row_pac['fecnac'];
$ocupacion = $row_pac['ocupacion'];
$domicilio = $row_pac['dir'];
$expediente = $row_pac['Id_exp'];

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
    function Header()
    {
        include '../../conexionbd.php';
        $id_exp = @$_GET['id_exp'];
        $id_atencion = @$_GET['id_atencion'];

        if ($this->PageNo() == 1) {
            $sql_pac = "SELECT p.papell, p.nom_pac, p.sapell, p.edad, p.sexo, p.Id_exp, p.folio, p.dir, p.tel, p.fecnac, p.ocup AS ocupacion 
                        FROM paciente p WHERE p.Id_exp = $id_exp";
            $result_pac = $conexion->query($sql_pac);
            $row_pac = $result_pac->fetch_assoc();

            $nombre_completo = $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
            $folio = $row_pac['folio'];
            $edad = $row_pac['edad'];
            $sexo = $row_pac['sexo'];
            $tel = $row_pac['tel'];
            $fecnac = $row_pac['fecnac'];
            $dir = $row_pac['dir'];
            $ocup = $row_pac['ocupacion'];

            $sql_ingreso = "SELECT tipo_a, fecha FROM dat_ingreso WHERE id_atencion = $id_atencion";
            $result_ingreso = $conexion->query($sql_ingreso);
            $row_ingreso = $result_ingreso->fetch_assoc();
            $servicio = $row_ingreso['tipo_a'] ?? '';
            $fecha_ing = $row_ingreso['fecha'] ?? '';
        }

        // Imágenes del sistema en todas las páginas
        $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC");
        while ($f = mysqli_fetch_array($resultado)) {
            $this->Image("../../configuracion/admin/img2/" . $f['img_ipdf'], 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);
        }

        // Título principal en todas las páginas
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('REGISTRO DE TRATAMIENTO'), 0, 1, 'C');
        
        // Solo mostrar datos del paciente en la primera página
        if ($this->PageNo() == 1) {
            $this->Ln(5);

            $this->SetFont('Arial', 'B', 9);
            $this->SetFillColor(230, 240, 255);
            $this->Cell(0, 6, 'Datos del Paciente:', 0, 1, 'L', true);
            $this->SetFont('Arial', '', 8);
            $this->SetFillColor(255, 255, 255);

            $this->Cell(35, 5, 'Servicio:', 0, 0, 'L');
            $this->Cell(55, 5, utf8_decode($servicio), 0, 0, 'L');
            $this->Cell(35, 5, 'Fecha de registro:', 0, 0, 'L');
            $this->Cell(0, 5, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');

            $this->Cell(35, 5, 'Paciente:', 0, 0, 'L');
            $this->Cell(55, 5, utf8_decode($folio . ' - ' . $nombre_completo), 0, 0, 'L');
            $this->Cell(35, 5, utf8_decode('Teléfono:'), 0, 0, 'L');
            $this->Cell(0, 5, utf8_decode($tel), 0, 1, 'L');

            $this->Cell(35, 5, utf8_decode('Fecha de nacimiento:'), 0, 0, 'L');
            $this->Cell(30, 5, date('d/m/Y', strtotime($fecnac)), 0, 0, 'L');
            $this->Cell(10, 5, utf8_decode('Edad:'), 0, 0, 'L');
            $this->Cell(15, 5, utf8_decode($edad . ' '), 0, 0, 'L');
            $this->Cell(15, 5, utf8_decode('Género:'), 0, 0, 'L');
            $this->Cell(20, 5, utf8_decode($sexo), 0, 0, 'L');
            
            $this->Ln(5);

            $this->Cell(20, 5, utf8_decode('Domicilio:'), 0, 0, 'L');
            $this->Cell(0, 5, utf8_decode($dir), 0, 1, 'L');

            $this->Cell(35, 5, 'Expediente:', 0, 0, 'L');
            $this->Cell(0, 5, $id_exp, 0, 1, 'L');

            $this->Ln(5);
        } else {
            // En páginas subsecuentes, solo agregar un poco de espacio después del título
            $this->Ln(8);
        }
    }

    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
    }
    
    function crearGraficaSignosVitales($horas, $sistolica, $diastolica, $frecuencia_cardiaca, $temperatura, $saturacion)
    {
        $x_inicio = 10;
        $y_inicio = $this->GetY() + 5;
        $ancho_grafica = 180;
        $alto_grafica = 90;
        $margen_interno = 15;
        
        // Marco principal con sombra
        $this->SetDrawColor(180, 180, 180);
        $this->SetLineWidth(0.3);
        $this->Rect($x_inicio + 1, $y_inicio + 1, $ancho_grafica, $alto_grafica); // Sombra
        
        $this->SetDrawColor(60, 60, 60);
        $this->SetLineWidth(0.8);
        $this->SetFillColor(250, 252, 255);
        $this->Rect($x_inicio, $y_inicio, $ancho_grafica, $alto_grafica, 'DF');
        
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(25, 25, 112);
        $this->SetXY($x_inicio, $y_inicio - 10);
        $this->Cell($ancho_grafica, 8, utf8_decode(' EVOLUCIÓN DE SIGNOS VITALES'), 0, 0, 'C');
        
        // Área de la gráfica
        $area_x = $x_inicio + $margen_interno;
        $area_y = $y_inicio + $margen_interno;
        $area_ancho = $ancho_grafica - ($margen_interno * 2) - 40; // Espacio para leyenda
        $area_alto = $alto_grafica - ($margen_interno * 2) - 15; // Espacio para etiquetas
        
        // Fondo del área de gráfica
        $this->SetFillColor(248, 250, 255);
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.3);
        $this->Rect($area_x, $area_y, $area_ancho, $area_alto, 'DF');
        
        // Líneas de cuadrícula
        $this->SetDrawColor(230, 230, 230);
        $this->SetLineWidth(0.2);
        
        // Líneas horizontales
        for ($i = 1; $i < 5; $i++) {
            $y_grid = $area_y + ($area_alto / 5) * $i;
            $this->Line($area_x, $y_grid, $area_x + $area_ancho, $y_grid);
        }
        
        // Líneas verticales
        if (count($horas) > 1) {
            for ($i = 1; $i < count($horas); $i++) {
                $x_grid = $area_x + ($area_ancho / (count($horas) - 1)) * $i;
                $this->Line($x_grid, $area_y, $x_grid, $area_y + $area_alto);
            }
        }
        
        // Información de debug mejorada
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY($area_x + 5, $area_y + 5);
        $this->Cell(0, 3, 'Registros: ' . count($horas), 0, 1, 'L');
        
        // Si hay datos, crear la gráfica
        if (count($horas) >= 1) {
            // Filtrar datos válidos
            $datos_validos = array();
            $horas_validas = array();
            
            for ($i = 0; $i < count($horas); $i++) {
                if ($sistolica[$i] > 0 || $diastolica[$i] > 0 || $frecuencia_cardiaca[$i] > 0 || $temperatura[$i] > 0 || $saturacion[$i] > 0) {
                    $horas_validas[] = $horas[$i] ?: 'T' . ($i + 1);
                    $datos_validos['sistolica'][] = $sistolica[$i] > 0 ? $sistolica[$i] : 0;
                    $datos_validos['diastolica'][] = $diastolica[$i] > 0 ? $diastolica[$i] : 0;
                    $datos_validos['frecuencia_cardiaca'][] = $frecuencia_cardiaca[$i] > 0 ? $frecuencia_cardiaca[$i] : 0;
                    $datos_validos['temperatura'][] = $temperatura[$i] > 0 ? $temperatura[$i] : 0;
                    $datos_validos['saturacion'][] = $saturacion[$i] > 0 ? $saturacion[$i] : 0;
                }
            }
            
            if (count($horas_validas) > 0) {
                $num_puntos = count($horas_validas);
                $espacio_x = $num_puntos > 1 ? $area_ancho / ($num_puntos - 1) : $area_ancho / 2;
                
                // Función mejorada para dibujar líneas con puntos
                function dibujarLineaConPuntos($pdf, $puntos, $color_r, $color_g, $color_b, $grosor = 2) {
                    if (count($puntos) == 0) return;
                    
                    // Configurar color y grosor
                    $pdf->SetDrawColor($color_r, $color_g, $color_b);
                    $pdf->SetFillColor($color_r, $color_g, $color_b);
                    $pdf->SetLineWidth($grosor);
                    
                    // Dibujar líneas entre puntos
                    for ($i = 0; $i < count($puntos) - 1; $i++) {
                        $pdf->Line($puntos[$i]['x'], $puntos[$i]['y'], $puntos[$i + 1]['x'], $puntos[$i + 1]['y']);
                    }
                    
                    // Dibujar puntos como círculos
                    foreach ($puntos as $punto) {
                        // Punto principal
                        $pdf->SetFillColor($color_r, $color_g, $color_b);
                        $pdf->Circle($punto['x'], $punto['y'], 2.5, 'F');
                        
                        // Borde blanco
                        $pdf->SetDrawColor(255, 255, 255);
                        $pdf->SetLineWidth(0.8);
                        $pdf->Circle($punto['x'], $punto['y'], 2.5, 'D');
                    }
                }
                
                // 1. Presión Sistólica (Rojo)
                $puntos_sistolica = array();
                for ($i = 0; $i < $num_puntos; $i++) {
                    if ($datos_validos['sistolica'][$i] > 0) {
                        $x = $area_x + ($num_puntos == 1 ? $area_ancho/2 : $i * $espacio_x);
                        $valor = $datos_validos['sistolica'][$i];
                        $proporcion = max(0, min(1, ($valor - 60) / (180 - 60)));
                        $y = $area_y + $area_alto - ($proporcion * $area_alto);
                        $puntos_sistolica[] = array('x' => $x, 'y' => $y, 'valor' => $valor);
                    }
                }
                dibujarLineaConPuntos($this, $puntos_sistolica, 220, 20, 60);
                
                // 2. Presión Diastólica (Azul)
                $puntos_diastolica = array();
                for ($i = 0; $i < $num_puntos; $i++) {
                    if ($datos_validos['diastolica'][$i] > 0) {
                        $x = $area_x + ($num_puntos == 1 ? $area_ancho/2 : $i * $espacio_x);
                        $valor = $datos_validos['diastolica'][$i];
                        $proporcion = max(0, min(1, ($valor - 40) / (120 - 40)));
                        $y = $area_y + $area_alto - ($proporcion * $area_alto);
                        $puntos_diastolica[] = array('x' => $x, 'y' => $y, 'valor' => $valor);
                    }
                }
                dibujarLineaConPuntos($this, $puntos_diastolica, 30, 144, 255);
                
                // 3. Frecuencia Cardíaca (Verde)
                $puntos_fc = array();
                for ($i = 0; $i < $num_puntos; $i++) {
                    if ($datos_validos['frecuencia_cardiaca'][$i] > 0) {
                        $x = $area_x + ($num_puntos == 1 ? $area_ancho/2 : $i * $espacio_x);
                        $valor = $datos_validos['frecuencia_cardiaca'][$i];
                        $proporcion = max(0, min(1, ($valor - 50) / (120 - 50)));
                        $y = $area_y + $area_alto - ($proporcion * $area_alto);
                        $puntos_fc[] = array('x' => $x, 'y' => $y, 'valor' => $valor);
                    }
                }
                dibujarLineaConPuntos($this, $puntos_fc, 34, 139, 34);
                
                // 4. Temperatura (Naranja)
                $puntos_temp = array();
                for ($i = 0; $i < $num_puntos; $i++) {
                    if ($datos_validos['temperatura'][$i] > 0) {
                        $x = $area_x + ($num_puntos == 1 ? $area_ancho/2 : $i * $espacio_x);
                        $valor = $datos_validos['temperatura'][$i];
                        $proporcion = max(0, min(1, ($valor - 35) / (42 - 35)));
                        $y = $area_y + $area_alto - ($proporcion * $area_alto);
                        $puntos_temp[] = array('x' => $x, 'y' => $y, 'valor' => $valor);
                    }
                }
                dibujarLineaConPuntos($this, $puntos_temp, 255, 140, 0);
                
                // 5. Saturación (Violeta)
                $puntos_sat = array();
                for ($i = 0; $i < $num_puntos; $i++) {
                    if ($datos_validos['saturacion'][$i] > 0) {
                        $x = $area_x + ($num_puntos == 1 ? $area_ancho/2 : $i * $espacio_x);
                        $valor = $datos_validos['saturacion'][$i];
                        $proporcion = max(0, min(1, ($valor - 85) / (100 - 85)));
                        $y = $area_y + $area_alto - ($proporcion * $area_alto);
                        $puntos_sat[] = array('x' => $x, 'y' => $y, 'valor' => $valor);
                    }
                }
                dibujarLineaConPuntos($this, $puntos_sat, 138, 43, 226);
                
                // Etiquetas de tiempo mejoradas
                $this->SetFont('Arial', 'B', 7);
                $this->SetTextColor(70, 70, 70);
                for ($i = 0; $i < count($horas_validas); $i++) {
                    $x = $area_x + ($num_puntos == 1 ? $area_ancho/2 : $i * $espacio_x);
                    $this->SetXY($x - 12, $area_y + $area_alto + 2);
                    $hora_mostrar = strlen($horas_validas[$i]) > 8 ? substr($horas_validas[$i], 0, 8) : $horas_validas[$i];
                    $this->Cell(24, 4, $hora_mostrar, 0, 0, 'C');
                }
                
                // Valores en los puntos (mostrar algunos valores clave)
                $this->SetFont('Arial', '', 6);
                $this->SetTextColor(60, 60, 60);
                
                // Mostrar valores de presión sistólica
                foreach ($puntos_sistolica as $punto) {
                    if ($punto['valor'] > 0) {
                        $this->SetXY($punto['x'] - 8, $punto['y'] - 8);
                        $this->Cell(16, 3, $punto['valor'], 0, 0, 'C');
                    }
                }
            }
        } else {
            // Mensaje cuando no hay datos
            $this->SetXY($area_x + 20, $area_y + $area_alto/2 - 5);
            $this->SetFont('Arial', 'I', 10);
            $this->SetTextColor(120, 120, 120);
            $this->Cell($area_ancho - 40, 8, utf8_decode('📈 Sin datos de signos vitales disponibles'), 0, 0, 'C');
        }
        
        // Leyenda mejorada con marco
        $leyenda_x = $area_x + $area_ancho + 8;
        $leyenda_y = $area_y + 5;
        
        // Marco de la leyenda
        $this->SetDrawColor(200, 200, 200);
        $this->SetFillColor(255, 255, 255);
        $this->SetLineWidth(0.3);
        $this->Rect($leyenda_x - 2, $leyenda_y - 2, 32, 64, 'DF');
        
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(50, 50, 50);
        $this->SetXY($leyenda_x, $leyenda_y);
        $this->Cell(30, 4, 'LEYENDA', 0, 1, 'C');
        
        // Items de leyenda con iconos
        $items_leyenda = array(
            array('color' => array(220, 20, 60), 'texto' => 'Sistolica', 'rango' => '60-180'),
            array('color' => array(30, 144, 255), 'texto' => 'Diastolica', 'rango' => '40-120'),
            array('color' => array(34, 139, 34), 'texto' => 'Frec. Card.', 'rango' => '50-120'),
            array('color' => array(255, 140, 0), 'texto' => 'Temperatura', 'rango' => '35-42'),
            array('color' => array(138, 43, 226), 'texto' => utf8_decode('Saturación'), 'rango' => '85-100')
        );
        
        $this->SetFont('Arial', '', 6);
        foreach ($items_leyenda as $index => $item) {
            $y_item = $leyenda_y + 8 + ($index * 9);
            
            // Círculo de color
            $this->SetFillColor($item['color'][0], $item['color'][1], $item['color'][2]);
            $this->Circle($leyenda_x + 2, $y_item + 1, 1.5, 'F');
            
            // Texto
            $this->SetTextColor(60, 60, 60);
            $this->SetXY($leyenda_x + 6, $y_item - 1);
            $this->Cell(20, 3, $item['texto'], 0, 1, 'L');
            
            // Rango
            $this->SetTextColor(100, 100, 100);
            $this->SetXY($leyenda_x + 6, $y_item + 2);
            $this->Cell(20, 2, $item['rango'], 0, 1, 'L');
        }
        
        // Posicionar cursor después de la gráfica
        $this->SetY($y_inicio + $alto_grafica + 20);
    }
    
    function Circle($x, $y, $r, $style = '')
    {
        // Crear un círculo más preciso usando múltiples líneas curvas
        if ($style == 'F') {
            // Círculo relleno - usar un polígono de muchos lados
            $num_segmentos = 16;
            $puntos = array();
            
            for ($i = 0; $i < $num_segmentos; $i++) {
                $angulo = ($i / $num_segmentos) * 2 * M_PI;
                $puntos[] = $x + $r * cos($angulo);
                $puntos[] = $y + $r * sin($angulo);
            }
            
            // Usar rectángulos pequeños para simular un círculo relleno
            for ($dx = -$r; $dx <= $r; $dx += 0.5) {
                for ($dy = -$r; $dy <= $r; $dy += 0.5) {
                    $distancia = sqrt($dx * $dx + $dy * $dy);
                    if ($distancia <= $r) {
                        $this->Rect($x + $dx - 0.25, $y + $dy - 0.25, 0.5, 0.5, 'F');
                    }
                }
            }
        } else {
            // Círculo solo borde - usar octágono
            $vertices = 8;
            $puntos = array();
            
            for ($i = 0; $i <= $vertices; $i++) {
                $angulo = ($i / $vertices) * 2 * M_PI;
                $x_punto = $x + $r * cos($angulo);
                $y_punto = $y + $r * sin($angulo);
                
                if ($i > 0) {
                    $this->Line($puntos[0], $puntos[1], $x_punto, $y_punto);
                }
                $puntos = array($x_punto, $y_punto);
            }
        }
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 30);

// Recuperar datos del tratamiento
$row_trat = $result_trat->fetch_assoc();
$tipo_tratamiento = $row_trat['tipo_tratamiento'];
$medico_tratante = $row_trat['medico_tratante'];
$anestesiologo = $row_trat['anestesiologo'];
$anestesia = $row_trat['anestesia'];
$nota_enfermeria = $row_trat['nota_enfermeria'];
$enfermera_responsable = $row_trat['enfermera_responsable'];
$fecha_registro = $row_trat['fecha_registro'];

$fecha_objeto = new DateTime($fecha_registro);
$fecha_formato = $fecha_objeto->format('d/m/Y');
$hora_formato = $fecha_objeto->format('H:i');

// Mostrar datos del tratamiento
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 230, 250);
$pdf->Cell(0, 8, utf8_decode('DETALLES DEL TRATAMIENTO'), 0, 1, 'C', true);
$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Información General'), 0, 1, 'L', true);

$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255, 255, 255);
$pdf->Cell(40, 5, 'Tipo de tratamiento:', 0, 0, 'L');
$pdf->Cell(55, 5, utf8_decode($tipo_tratamiento), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Fecha de registro:'), 0, 0, 'L');
$pdf->Cell(0, 5, $fecha_formato . ' - ' . $hora_formato, 0, 1, 'L');

if ($medico_tratante) {
    $pdf->Cell(40, 5, utf8_decode('Médico tratante:'), 0, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode($medico_tratante), 0, 1, 'L');
}

if ($anestesiologo) {
    $pdf->Cell(40, 5, utf8_decode('Anestesiólogo:'), 0, 0, 'L');
    $pdf->Cell(55, 5, utf8_decode($anestesiologo), 0, 0, 'L');
    if ($anestesia) {
        $pdf->Cell(35, 5, 'Tipo de anestesia:', 0, 0, 'L');
        $pdf->Cell(0, 5, utf8_decode($anestesia), 0, 1, 'L');
    } else {
        $pdf->Ln();
    }
} elseif ($anestesia) {
    $pdf->Cell(40, 5, 'Tipo de anestesia:', 0, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode($anestesia), 0, 1, 'L');
}

if ($enfermera_responsable) {
    $pdf->Cell(40, 5, 'Enfermera responsable:', 0, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode($enfermera_responsable), 0, 1, 'L');
}

$pdf->Ln(4);

// Nota de enfermería
if ($nota_enfermeria) {
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(245, 245, 245);
    $pdf->Cell(0, 6, utf8_decode('Notas de Enfermería'), 0, 1, 'L', true);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(0, 4, utf8_decode($nota_enfermeria), 1, 'J', false);
    $pdf->Ln(2);
}

// Signos vitales - Primero verificar qué datos hay disponibles
$sql_signos_debug = "SELECT COUNT(*) as total FROM dat_trans_grafico WHERE id_tratamiento = $id_tratamiento";
$result_debug = $conexion->query($sql_signos_debug);
$debug_row = $result_debug->fetch_assoc();

$sql_signos = "SELECT * FROM dat_trans_grafico WHERE id_tratamiento = $id_tratamiento ORDER BY hora, id_trans_graf";
$result_signos = $conexion->query($sql_signos);

// Siempre mostrar la sección de gráfica
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(245, 245, 245);
$pdf->Cell(0, 6, utf8_decode('Gráfica de Signos Vitales'), 0, 1, 'L', true);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(0, 4, 'Buscando registros para tratamiento ID: ' . $id_tratamiento . ' (Total encontrados: ' . $debug_row['total'] . ')', 0, 1, 'L');
$pdf->Ln(2);

if ($result_signos->num_rows > 0) {
    // Recopilar datos para la gráfica
    $horas = array();
    $sistolica = array();
    $diastolica = array();
    $frecuencia_cardiaca = array();
    $temperatura = array();
    $saturacion = array();
    
    while ($row_signos = $result_signos->fetch_assoc()) {
        $hora_actual = $row_signos['hora'] ?: 'S/H';
        $horas[] = $hora_actual;
        $sistolica[] = (float)($row_signos['sistg'] ?: 0);
        $diastolica[] = (float)($row_signos['diastg'] ?: 0);
        $frecuencia_cardiaca[] = (float)($row_signos['fcardg'] ?: 0);
        $temperatura[] = (float)($row_signos['tempg'] ?: 0);
        $saturacion[] = (float)($row_signos['satg'] ?: 0);
    }
    
    // Mostrar información de debug
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 4, 'Registros procesados: ' . count($horas), 0, 1, 'L');
    if (count($horas) > 0) {
        $pdf->Cell(0, 4, 'Ejemplo - Hora: ' . $horas[0] . ', Sist: ' . $sistolica[0] . ', Diast: ' . $diastolica[0], 0, 1, 'L');
    }
    $pdf->Ln(2);
    
    $pdf->crearGraficaSignosVitales($horas, $sistolica, $diastolica, $frecuencia_cardiaca, $temperatura, $saturacion);
    
    $pdf->Ln(5);
    
    $result_signos = $conexion->query($sql_signos); // Reiniciar consulta
    
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(245, 245, 245);
    $pdf->Cell(0, 6, 'Registro Detallado de Signos Vitales', 0, 1, 'L', true);
    $pdf->Ln(1);

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetFillColor(220, 230, 250);
    $pdf->Cell(25, 6, 'Hora', 1, 0, 'C', true);
    $pdf->Cell(20, 6, 'T.A. Sist.', 1, 0, 'C', true);
    $pdf->Cell(20, 6, 'T.A. Diast.', 1, 0, 'C', true);
    $pdf->Cell(20, 6, 'F. Card.', 1, 0, 'C', true);
    $pdf->Cell(20, 6, 'F. Resp.', 1, 0, 'C', true);
    $pdf->Cell(25, 6, 'Sat. O2', 1, 0, 'C', true);
    $pdf->Cell(40, 6, 'Temp.', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 7);
    while ($row_signos = $result_signos->fetch_assoc()) {
        $pdf->Cell(25, 5, $row_signos['hora'] ?: '-', 1, 0, 'C');
        $pdf->Cell(20, 5, $row_signos['sistg'] ?: '-', 1, 0, 'C');
        $pdf->Cell(20, 5, $row_signos['diastg'] ?: '-', 1, 0, 'C');
        $pdf->Cell(20, 5, $row_signos['fcardg'] ?: '-', 1, 0, 'C');
        $pdf->Cell(20, 5, $row_signos['frespg'] ?: '-', 1, 0, 'C');
        $pdf->Cell(25, 5, $row_signos['satg'] ?: '-', 1, 0, 'C');
        $pdf->Cell(40, 5, $row_signos['tempg'] ?: '-', 1, 1, 'C');
    }
} else {
    $pdf->crearGraficaSignosVitales(array(), array(), array(), array(), array(), array());
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 6, 'No se encontraron registros de signos vitales para este tratamiento.', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 4, 'Verificar que existan registros con id_tratamiento = ' . $id_tratamiento, 0, 1, 'C');
    $pdf->Ln(5);
}

$pdf->Output();
