<?php

use PDF as GlobalPDF;

require '../../fpdf/fpdf.php';
require '../../conexionbd.php';
$conexion = ConexionBD::getInstancia()->getConexion();

$id = @$_GET['id'];
$id_exp = @$_GET['id_exp'];
$id_atencion = @$_GET['id_atencion'];

// Obtener datos del paciente
$sql_pac = "SELECT p.papell, p.nom_pac, p.sapell, p.edad, p.sexo, p.Id_exp, p.folio, p.dir, p.id_edo, p.id_mun, p.tel, p.ocup, p.resp, p.paren, p.tel_resp, p.fecnac 
            FROM paciente p WHERE p.Id_exp = $id_exp";
$result_pac = $conexion->query($sql_pac);
$row_pac = $result_pac->fetch_assoc();

$papell = $row_pac['papell'];
$nom_pac = $row_pac['nom_pac'];
$sapell = $row_pac['sapell'];
$edad = $row_pac['edad'];
$sexo = $row_pac['sexo'];
$Id_exp = $row_pac['Id_exp'];
$folio = $row_pac['folio'];
$dir = $row_pac['dir'];
$tel = $row_pac['tel'];
$fecnac = $row_pac['fecnac'];

// Obtener datos del ingreso
$sql_preop = "SELECT * FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_preop = $conexion->query($sql_preop);
$row_preop = $result_preop->fetch_assoc();

$tipo_a = $row_preop['tipo_a'] ?? '';
$fecha_ing = $row_preop['fecha'] ?? '';
$id_usua = $row_preop['id_usua'] ?? '';

// Obtener datos del tratamiento genérico
$sql_tratamiento = "
    SELECT dt.*, ru.nombre_usuario
    FROM diagnostico_tratamiento dt
    JOIN reg_usuarios ru ON dt.id_usua = ru.id_usua
    WHERE dt.id_exp = '$id_exp'
      AND dt.id_atencion = '$id_atencion'
    LIMIT 1
";
$result_tratamiento = $conexion->query($sql_tratamiento);
$row_tratamiento = $result_tratamiento->fetch_assoc();

if (!$row_tratamiento) {
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
        $(document).ready(function() {
            swal({
                title: "NO EXISTE TRATAMIENTO GENÉRICO PARA ESTE PACIENTE", 
                type: "error",
                confirmButtonText: "ACEPTAR"
            }, function(isConfirm) { 
                if (isConfirm) {
                    window.close();
                }
            });
        });
    </script>';
    exit;
}

// Datos del tratamiento
$tipo_tratamiento = $row_tratamiento['tipo_tratamiento'] ?? '';
$medico_tratante = $row_tratamiento['medico_tratante'] ?? '';
$anestesiologo = $row_tratamiento['anestesiologo'] ?? '';
$anestesia = $row_tratamiento['anestesia'] ?? '';
$nota_enfermeria = $row_tratamiento['nota_enfermeria'] ?? '';
$enfermera_responsable = $row_tratamiento['enfermera_responsable'] ?? '';
$fecha_registro = $row_tratamiento['fecha_registro'] ?? date('Y-m-d H:i:s');

// Datos del usuario que registró
$nom_usuario = $row_tratamiento['nombre'] ?? '';
$app_usuario = $row_tratamiento['papell_usuario'] ?? '';
$apm_usuario = $row_tratamiento['sapell_usuario'] ?? '';
$pre_usuario = $row_tratamiento['pre'] ?? '';
$firma = $row_tratamiento['firma'] ?? '';
$ced_p = $row_tratamiento['cedp'] ?? '';
$cargp = $row_tratamiento['cargp'] ?? '';

// Obtener signos vitales del paciente
$sql_signos = "SELECT dtg.*, ru.nombre as nombre_reg, ru.papell as papell_reg, ru.sapell as sapell_reg 
               FROM dat_trans_grafico dtg 
               LEFT JOIN reg_usuarios ru ON dtg.id_usua = ru.id_usua 
               WHERE dtg.id_atencion = $id_atencion 
               ORDER BY dtg.fecha_g DESC, dtg.hora DESC 
               LIMIT 20";
$result_signos = $conexion->query($sql_signos);

$GLOBALS['fecha_registro'] = $fecha_registro;

class PDF extends FPDF
{
    function Header()
    {
        include '../../conexionbd.php';
        $resultado = $conexion->query("SELECT * from img_sistema ORDER BY id_simg DESC") or die($conexion->error);
        while ($f = mysqli_fetch_array($resultado)) {
            $this->Image("../../configuracion/admin/img2/" . $f['img_ipdf'], 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);
        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('NOTA DE TRATAMIENTO GENÉRICO'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i', strtotime($GLOBALS['fecha_registro'])), 0, 1, 'R');
        $this->Ln(5);
    }
    
    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('INEO-000'), 0, 1, 'R');
    }
    
    // Método para dibujar círculos pequeños
    function Circle($x, $y, $r, $style = 'D')
    {
        $this->Ellipse($x, $y, $r, $r, $style);
    }
    
    // Método para dibujar elipses
    function Ellipse($x, $y, $rx, $ry, $style = 'D')
    {
        if($style == 'F')
            $op = 'f';
        elseif($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $lx = 4/3 * (M_SQRT2 - 1) * $rx;
        $ly = 4/3 * (M_SQRT2 - 1) * $ry;
        $k = $this->k;
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k, ($h - $y) * $k,
            ($x + $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x + $lx) * $k, ($h - ($y - $ry)) * $k,
            $x * $k, ($h - ($y - $ry)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $lx) * $k, ($h - ($y - $ry)) * $k,
            ($x - $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x - $rx) * $k, ($h - $y) * $k,
            ($x - $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x - $lx) * $k, ($h - ($y + $ry)) * $k,
            $x * $k, ($h - ($y + $ry)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x + $lx) * $k, ($h - ($y + $ry)) * $k,
            ($x + $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x + $rx) * $k, ($h - $y) * $k,
            ($x + $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x + $lx) * $k, ($h - ($y - $ry)) * $k,
            $x * $k, ($h - ($y - $ry)) * $k,
            $op));
    }
}

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(12,12,12); // Márgenes más pequeños
$pdf->SetAutoPageBreak(true,25); // Menos espacio en el pie 

// Datos del Paciente - Compactado
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'DATOS DEL PACIENTE', 0, 1, 'L', true);
$pdf->Ln(1);

$pdf->SetFont('Arial', '', 7);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);

// Línea 1 - Información básica
$pdf->Cell(30, 4, 'Servicio:', 0, 0, 'L');
$pdf->Cell(45, 4, utf8_decode($tipo_a), 0, 0, 'L');
$pdf->Cell(35, 4, 'Fecha de registro:', 0, 0, 'L');
$pdf->Cell(0, 4, date('d/m/Y H:i', strtotime($fecha_ing)), 0, 1, 'L');

// Línea 2 - Paciente
$pdf->Cell(30, 4, 'Paciente:', 0, 0, 'L');
$pdf->Cell(85, 4, utf8_decode($folio . ' - ' . $papell . ' ' . $sapell . ' ' . $nom_pac), 0, 0, 'L');
$pdf->Cell(25, 4, utf8_decode('Tel:'), 0, 0, 'L');
$pdf->Cell(0, 4, utf8_decode($tel), 0, 1, 'L');

// Línea 3 - Datos personales compactados
$pdf->Cell(30, 4, utf8_decode('F. Nacimiento:'), 0, 0, 'L');
$pdf->Cell(25, 4, date('d/m/Y', strtotime($fecnac)), 0, 0, 'L');
$pdf->Cell(15, 4, utf8_decode('Edad:'), 0, 0, 'L');
$pdf->Cell(15, 4, utf8_decode($edad), 0, 0, 'L');
$pdf->Cell(20, 4, utf8_decode('Género:'), 0, 0, 'L');
$pdf->Cell(0, 4, utf8_decode($sexo), 0, 1, 'L');

// Línea 4 - Domicilio
$pdf->Cell(30, 4, utf8_decode('Domicilio:'), 0, 0, 'L');
$pdf->Cell(0, 4, utf8_decode($dir), 0, 1, 'L');

$pdf->Ln(2);

// Información del Tratamiento - Compactada
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 240, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 6, utf8_decode('INFORMACIÓN DEL TRATAMIENTO GENÉRICO'), 0, 1, 'C', true);
$pdf->Ln(1);

$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0, 0, 0);

// Tabla compacta
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(50, 6, utf8_decode('Campo'), 1, 0, 'C', true);
$pdf->Cell(135, 6, utf8_decode('Información'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(255,255,255);

$pdf->Cell(50, 6, utf8_decode('Tipo de Tratamiento'), 1, 0, 'L');
$pdf->Cell(135, 6, utf8_decode($tipo_tratamiento), 1, 1, 'L');

$pdf->Cell(50, 6, utf8_decode('Médico Tratante'), 1, 0, 'L');
$pdf->Cell(135, 6, utf8_decode($medico_tratante), 1, 1, 'L');

$pdf->Cell(50, 6, utf8_decode('Anestesiólogo'), 1, 0, 'L');
$pdf->Cell(135, 6, utf8_decode($anestesiologo), 1, 1, 'L');

$pdf->Cell(50, 6, utf8_decode('Tipo de Anestesia'), 1, 0, 'L');
$pdf->Cell(135, 6, utf8_decode($anestesia), 1, 1, 'L');

$pdf->Cell(50, 6, utf8_decode('Enfermera Responsable'), 1, 0, 'L');
$pdf->Cell(135, 6, utf8_decode($enfermera_responsable), 1, 1, 'L');

$pdf->Ln(2);

// Notas de Enfermería - Optimizada
if (!empty($nota_enfermeria)) {
    // Verificar espacio disponible - más estricto
    if ($pdf->GetY() > 230) {
        $pdf->AddPage();
    }
    
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(230, 240, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 6, utf8_decode('NOTAS DE ENFERMERÍA'), 1, 1, 'C', true);
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetFillColor(255,255,255);
    $pdf->SetTextColor(0, 0, 0);
    
    // Altura super optimizada
    $texto_nota = utf8_decode($nota_enfermeria);
    $ancho_texto = 185;
    $altura_por_linea = 3.5;
    
    $pdf->MultiCell($ancho_texto, $altura_por_linea, $texto_nota, 1, 'J', false);
    $pdf->Ln(1);
}

// Signos Vitales - Gráfica como la imagen de referencia
if ($result_signos && $result_signos->num_rows > 0) {
    // Control de espacio más estricto
    if ($pdf->GetY() > 180) {
        $pdf->AddPage();
    }
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(230, 240, 255);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 8, utf8_decode('EVOLUCIÓN DE SIGNOS VITALES'), 0, 1, 'C', true);
    $pdf->Ln(3);
    
    // Recopilar datos optimizados (6 registros para mejor visualización)
    $signos_data = array();
    $result_signos->data_seek(0);
    $contador = 0;
    while(($signo = $result_signos->fetch_assoc()) && $contador < 6) {
        if ($signo && isset($signo['fecha_g'])) {
            $signos_data[] = array(
                'fecha' => $signo['fecha_g'],
                'sistolica' => floatval($signo['sistg'] ?? 0),
                'diastolica' => floatval($signo['diastg'] ?? 0),
                'frecuencia' => floatval($signo['fcardg'] ?? 0),
                'temperatura' => floatval($signo['tempg'] ?? 0),
                'respiracion' => floatval($signo['frespg'] ?? 0),
                'saturacion' => floatval($signo['satg'] ?? 0)
            );
            $contador++;
        }
    }
    
    if (!empty($signos_data) && count($signos_data) > 1) {
        // Invertir para orden cronológico
        $signos_data = array_reverse($signos_data);
        
        // Configuración de gráfica estilo imagen
        $grafica_x = 15;
        $grafica_y = $pdf->GetY();
        $grafica_width = 140; // Más angosta para dejar espacio a la leyenda
        $grafica_height = 75;
        
        // Fondo blanco con borde negro
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect($grafica_x, $grafica_y, $grafica_width, $grafica_height, 'F');
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.5);
        $pdf->Rect($grafica_x, $grafica_y, $grafica_width, $grafica_height);
        
        // Cuadrícula ligera
        $pdf->SetDrawColor(220, 220, 220);
        $pdf->SetLineWidth(0.2);
        
        // Líneas horizontales
        for ($i = 1; $i <= 4; $i++) {
            $y_grid = $grafica_y + ($grafica_height / 5) * $i;
            $pdf->Line($grafica_x, $y_grid, $grafica_x + $grafica_width, $y_grid);
        }
        
        // Líneas verticales
        $num_puntos = count($signos_data);
        $step_x = $grafica_width / ($num_puntos - 1);
        for ($i = 1; $i < $num_puntos - 1; $i++) {
            $x_grid = $grafica_x + $step_x * $i;
            $pdf->Line($x_grid, $grafica_y, $x_grid, $grafica_y + $grafica_height);
        }
        
        // Etiquetas de fechas en el eje X
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 7);
        for ($i = 0; $i < $num_puntos; $i++) {
            $x_label = $grafica_x + $step_x * $i;
            $fecha_format = date('d/m', strtotime($signos_data[$i]['fecha']));
            $pdf->SetXY($x_label - 8, $grafica_y + $grafica_height + 2);
            $pdf->Cell(16, 3, $fecha_format, 0, 0, 'C');
        }
        
        // Escala Y izquierda
        $pdf->SetXY($grafica_x - 10, $grafica_y - 2);
        $pdf->Cell(8, 3, '180', 0, 0, 'R');
        $pdf->SetXY($grafica_x - 10, $grafica_y + 15);
        $pdf->Cell(8, 3, '140', 0, 0, 'R');
        $pdf->SetXY($grafica_x - 10, $grafica_y + 35);
        $pdf->Cell(8, 3, '100', 0, 0, 'R');
        $pdf->SetXY($grafica_x - 10, $grafica_y + 55);
        $pdf->Cell(8, 3, '60', 0, 0, 'R');
        $pdf->SetXY($grafica_x - 10, $grafica_y + $grafica_height - 2);
        $pdf->Cell(8, 3, '35', 0, 0, 'R');
        
        // 1. PRESIÓN SISTÓLICA (Rojo vibrante)
        $pdf->SetDrawColor(220, 38, 38);
        $pdf->SetLineWidth(2.5);
        for ($i = 0; $i < $num_puntos - 1; $i++) {
            if ($signos_data[$i]['sistolica'] > 0 && $signos_data[$i + 1]['sistolica'] > 0) {
                $x1 = $grafica_x + $step_x * $i;
                $x2 = $grafica_x + $step_x * ($i + 1);
                
                $y1 = $grafica_y + $grafica_height - (($signos_data[$i]['sistolica'] - 35) / 145 * $grafica_height);
                $y2 = $grafica_y + $grafica_height - (($signos_data[$i + 1]['sistolica'] - 35) / 145 * $grafica_height);
                
                $y1 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y1));
                $y2 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y2));
                
                $pdf->Line($x1, $y1, $x2, $y2);
                
                // Puntos circulares
                $pdf->SetFillColor(220, 38, 38);
                $pdf->Circle($x1, $y1, 2.5, 'F');
                if ($i == $num_puntos - 2) {
                    $pdf->Circle($x2, $y2, 2.5, 'F');
                }
            }
        }
        
        // 2. PRESIÓN DIASTÓLICA (Azul intenso)
        $pdf->SetDrawColor(13, 110, 253);
        $pdf->SetLineWidth(2.5);
        for ($i = 0; $i < $num_puntos - 1; $i++) {
            if ($signos_data[$i]['diastolica'] > 0 && $signos_data[$i + 1]['diastolica'] > 0) {
                $x1 = $grafica_x + $step_x * $i;
                $x2 = $grafica_x + $step_x * ($i + 1);
                
                $y1 = $grafica_y + $grafica_height - (($signos_data[$i]['diastolica'] - 35) / 145 * $grafica_height);
                $y2 = $grafica_y + $grafica_height - (($signos_data[$i + 1]['diastolica'] - 35) / 145 * $grafica_height);
                
                $y1 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y1));
                $y2 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y2));
                
                $pdf->Line($x1, $y1, $x2, $y2);
                
                $pdf->SetFillColor(13, 110, 253);
                $pdf->Circle($x1, $y1, 2.5, 'F');
                if ($i == $num_puntos - 2) {
                    $pdf->Circle($x2, $y2, 2.5, 'F');
                }
            }
        }
        
        // 3. FRECUENCIA CARDÍACA (Verde esmeralda)
        $pdf->SetDrawColor(40, 167, 69);
        $pdf->SetLineWidth(2.5);
        for ($i = 0; $i < $num_puntos - 1; $i++) {
            if ($signos_data[$i]['frecuencia'] > 0 && $signos_data[$i + 1]['frecuencia'] > 0) {
                $x1 = $grafica_x + $step_x * $i;
                $x2 = $grafica_x + $step_x * ($i + 1);
                
                $y1 = $grafica_y + $grafica_height - (($signos_data[$i]['frecuencia'] - 35) / 145 * $grafica_height);
                $y2 = $grafica_y + $grafica_height - (($signos_data[$i + 1]['frecuencia'] - 35) / 145 * $grafica_height);
                
                $y1 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y1));
                $y2 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y2));
                
                $pdf->Line($x1, $y1, $x2, $y2);
                
                $pdf->SetFillColor(40, 167, 69);
                $pdf->Circle($x1, $y1, 2.5, 'F');
                if ($i == $num_puntos - 2) {
                    $pdf->Circle($x2, $y2, 2.5, 'F');
                }
            }
        }
        
        // 4. TEMPERATURA (Naranja vibrante) - Escalada para visibilidad
        $pdf->SetDrawColor(255, 135, 0);
        $pdf->SetLineWidth(2.5);
        for ($i = 0; $i < $num_puntos - 1; $i++) {
            if ($signos_data[$i]['temperatura'] > 0 && $signos_data[$i + 1]['temperatura'] > 0) {
                $x1 = $grafica_x + $step_x * $i;
                $x2 = $grafica_x + $step_x * ($i + 1);
                
                // Escalar temperatura para que sea visible (multiplicar por 20)
                $temp_escalada_1 = ($signos_data[$i]['temperatura'] - 35) * 20 + 35;
                $temp_escalada_2 = ($signos_data[$i + 1]['temperatura'] - 35) * 20 + 35;
                
                $y1 = $grafica_y + $grafica_height - (($temp_escalada_1 - 35) / 145 * $grafica_height);
                $y2 = $grafica_y + $grafica_height - (($temp_escalada_2 - 35) / 145 * $grafica_height);
                
                $y1 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y1));
                $y2 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y2));
                
                $pdf->Line($x1, $y1, $x2, $y2);
                
                $pdf->SetFillColor(255, 135, 0);
                $pdf->Circle($x1, $y1, 2.5, 'F');
                if ($i == $num_puntos - 2) {
                    $pdf->Circle($x2, $y2, 2.5, 'F');
                }
            }
        }
        
        // 5. SATURACIÓN (Púrpura moderno)
        $pdf->SetDrawColor(156, 39, 176);
        $pdf->SetLineWidth(2.5);
        for ($i = 0; $i < $num_puntos - 1; $i++) {
            if ($signos_data[$i]['saturacion'] > 0 && $signos_data[$i + 1]['saturacion'] > 0) {
                $x1 = $grafica_x + $step_x * $i;
                $x2 = $grafica_x + $step_x * ($i + 1);
                
                $y1 = $grafica_y + $grafica_height - (($signos_data[$i]['saturacion'] - 35) / 145 * $grafica_height);
                $y2 = $grafica_y + $grafica_height - (($signos_data[$i + 1]['saturacion'] - 35) / 145 * $grafica_height);
                
                $y1 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y1));
                $y2 = max($grafica_y + 2, min($grafica_y + $grafica_height - 2, $y2));
                
                $pdf->Line($x1, $y1, $x2, $y2);
                
                $pdf->SetFillColor(156, 39, 176);
                $pdf->Circle($x1, $y1, 2.5, 'F');
                if ($i == $num_puntos - 2) {
                    $pdf->Circle($x2, $y2, 2.5, 'F');
                }
            }
        }
        
        // LEYENDA en el lado derecho como en la imagen
        $leyenda_x = $grafica_x + $grafica_width + 10;
        $leyenda_y = $grafica_y + 10;
        
        // Marco de la leyenda
        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.3);
        $pdf->Rect($leyenda_x, $leyenda_y, 45, 55, 'FD');
        
        // Titulo de leyenda
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY($leyenda_x + 2, $leyenda_y + 2);
        $pdf->Cell(41, 5, 'LEYENDA', 0, 1, 'C');
        
        // Items de leyenda con circulos de colores
        $pdf->SetFont('Arial', '', 7);
        $items_y = $leyenda_y + 8;
        
        // Sistolica - Rojo vibrante
        $pdf->SetFillColor(220, 38, 38);
        $pdf->Circle($leyenda_x + 5, $items_y + 2, 2, 'F');
        $pdf->SetXY($leyenda_x + 10, $items_y);
        $pdf->Cell(30, 4, utf8_decode('Sistólica'), 0, 0, 'L');
        $pdf->SetXY($leyenda_x + 32, $items_y);
        $pdf->Cell(10, 4, 'mmHg', 0, 1, 'L');
        
        // Diastolica - Azul intenso
        $items_y += 6;
        $pdf->SetFillColor(13, 110, 253);
        $pdf->Circle($leyenda_x + 5, $items_y + 2, 2, 'F');
        $pdf->SetXY($leyenda_x + 10, $items_y);
        $pdf->Cell(30, 4, utf8_decode('Diastólica'), 0, 0, 'L');
        $pdf->SetXY($leyenda_x + 32, $items_y);
        $pdf->Cell(10, 4, 'mmHg', 0, 1, 'L');
        
        // Frec. Card. - Verde esmeralda
        $items_y += 6;
        $pdf->SetFillColor(40, 167, 69);
        $pdf->Circle($leyenda_x + 5, $items_y + 2, 2, 'F');
        $pdf->SetXY($leyenda_x + 10, $items_y);
        $pdf->Cell(30, 4, 'Frec. Card.', 0, 0, 'L');
        $pdf->SetXY($leyenda_x + 32, $items_y);
        $pdf->Cell(10, 4, 'x min', 0, 1, 'L');
        
        // Temperatura - Naranja vibrante
        $items_y += 6;
        $pdf->SetFillColor(255, 135, 0);
        $pdf->Circle($leyenda_x + 5, $items_y + 2, 2, 'F');
        $pdf->SetXY($leyenda_x + 10, $items_y);
        $pdf->Cell(30, 4, 'Temperatura', 0, 0, 'L');
        $pdf->SetXY($leyenda_x + 32, $items_y);
        $pdf->Cell(10, 4, utf8_decode('°C'), 0, 1, 'L');
        
        // Saturacion - Púrpura moderno
        $items_y += 6;
        $pdf->SetFillColor(156, 39, 176);
        $pdf->Circle($leyenda_x + 5, $items_y + 2, 2, 'F');
        $pdf->SetXY($leyenda_x + 10, $items_y);
        $pdf->Cell(30, 4, utf8_decode('Saturación'), 0, 0, 'L');
        $pdf->SetXY($leyenda_x + 32, $items_y);
        $pdf->Cell(10, 4, '%', 0, 1, 'L');
        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(10);
    } else {
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(255, 243, 205);
        $pdf->SetTextColor(138, 109, 59);
        $pdf->Cell(0, 6, utf8_decode('⚠ DATOS INSUFICIENTES: Se requieren al menos 2 registros para generar tendencias'), 1, 1, 'C', true);
        $pdf->Ln(2);
    }
} else {
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(248, 215, 218);
    $pdf->SetTextColor(132, 32, 41);
    $pdf->Cell(0, 6, utf8_decode('❌ SIN DATOS: No hay registros de signos vitales disponibles'), 1, 1, 'C', true);
    $pdf->Ln(2);
}

// Firma del profesional - Ultra optimizada
$pdf->SetY(-40); // Espacio mínimo para firma

if (!empty($firma) && file_exists('../../imgfirma/' . $firma)) {
    $imgWidth = 20; // Imagen muy compacta
    $imgX = ($pdf->GetPageWidth() - $imgWidth) / 2;
    $pdf->Image('../../imgfirma/' . $firma, $imgX, $pdf->GetY(), $imgWidth);
    $pdf->Ln(6); // Espacio mínimo
} else {
    $pdf->Ln(3); // Espacio ultra mínimo
}

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 2.5, utf8_decode(trim($pre_usuario . ' ' . $app_usuario . ' ' . $apm_usuario . ' ' . $nom_usuario)), 0, 1, 'C');
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 2.5, utf8_decode($cargp), 0, 1, 'C');
$pdf->Cell(0, 2.5, utf8_decode('Céd. Prof. ' . $ced_p), 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 6);
$pdf->Cell(0, 2.5, utf8_decode('Nombre y firma del profesional'), 0, 1, 'C');

$pdf->Output();
?>
