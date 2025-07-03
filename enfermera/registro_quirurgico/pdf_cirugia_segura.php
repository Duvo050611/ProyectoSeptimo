<?php
session_start();
include "../../conexionbd.php";
require "../../fpdf/fpdf.php";

$id_atencion = $_SESSION['hospital'];

// Obtener datos del checklist
$sql_check = "SELECT * FROM dat_cir_seg WHERE id_atencion = ? LIMIT 1";
$stmt = $conexion->prepare($sql_check);
$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$res_check = $stmt->get_result();
$data = $res_check->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Checklist quirúrgico no encontrado.");
}

// Obtener datos del paciente
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.Id_exp, p.resp, p.paren, p.edad, p.sexo, p.dir, p.tel, di.id_usua, p.fecnac
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = ? LIMIT 1";
$stmt = $conexion->prepare($sql_pac);
$stmt->bind_param("i", $id_atencion);
$stmt->execute();
$res_pac = $stmt->get_result();
$pac = $res_pac->fetch_assoc();
$stmt->close();

if (!$pac) {
    die("Paciente no encontrado.");
}

// Clase PDF
class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
        while ($f = mysqli_fetch_assoc($resultado)) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetTextColor(40, 40, 40);
        $this->Cell(0, 12, utf8_decode('LISTA DE VERIFICACIÓN DE CIRUGÍA SEGURA'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, utf8_decode('Fecha: ') . date('d/m/Y H:i'), 0, 1, 'R');
        $this->Ln(5);
    }

    function Footer() {
    $this->SetY(-25);
    $this->SetFont('Arial', '', 8);
    $this->MultiCell(0, 4, utf8_decode(
        "Av. Tecnológico 1020, Col. Bellavista, C.P. 52172, Metepec, Edo. de México,\n" .
        "Teléfonos. (722) 232.8086 / (722) 238.6901, inst.enfermedadesoculares@gmail.com."
    ), 0, 'C');
    $this->Ln(1);
    $this->SetFont('Arial', 'I', 8);
    $this->Cell(0, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
}
    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
function Row($data, $widths, $line_height = 5) {
    $nb = 0;
    for ($i = 0; $i < count($data); $i++) {
        $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
    }
    $h = $line_height * $nb;
    $this->CheckPageBreak($h);
    for ($i = 0; $i < count($data); $i++) {
        $w = $widths[$i];
        $x = $this->GetX();
        $y = $this->GetY();
        $this->Rect($x, $y, $w, $h);
        $this->MultiCell($w, $line_height, $data[$i], 0, 'L');
        $this->SetXY($x + $w, $y);
    }
    $this->Ln($h);
}

function CheckPageBreak($h) {
    if ($this->GetY() + $h > $this->PageBreakTrigger) {
        $this->AddPage($this->CurOrientation);
    }
}

}

function mostrar($estado) {
    return $estado ? '[X]' : '[ ]';
}



$pdf = new PDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

// Datos generales del paciente
$fechaNacimiento =$pac['fecnac']; // Agrégalo si tienes este campo
$diagnostico = ''; // Si lo tienes en la BD
$procedimientoQx = ''; // Si lo tienes en la BD
$fechaHoy = date("d/m/Y");

// Tabla de encabezado completa
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(0, 10, utf8_decode("LISTA DE VERIFICACIÓN DE CIRUGÍA SEGURA"), 1, 1, 'C', true);
$pdf->SetFont('Arial', '', 10);

$nombreCompleto = $pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac'];

$pdf->Cell(65, 8, utf8_decode("Nombre del paciente"), 1, 0, 'L');
$pdf->Cell(125, 8, utf8_decode($nombreCompleto), 1, 1, 'L');

$pdf->Cell(65, 8, utf8_decode("Fecha de nacimiento"), 1, 0, 'L');
$pdf->Cell(125, 8, utf8_decode($fechaNacimiento), 1, 1, 'L');

$pdf->Cell(65, 8, utf8_decode("Fecha de elaboración"), 1, 0, 'L');
$pdf->Cell(125, 8, utf8_decode($fechaHoy), 1, 1, 'L');

$pdf->Cell(65, 8, utf8_decode("Diagnóstico"), 1, 0, 'L');
$pdf->Cell(125, 8, utf8_decode($diagnostico), 1, 1, 'L');

$pdf->Cell(65, 8, utf8_decode("Procedimiento quirúrgico"), 1, 0, 'L');
$pdf->Cell(125, 8, utf8_decode($procedimientoQx), 1, 1, 'L');

// Secciones con espacio para hora
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);

$pdf->Cell(140, 8, utf8_decode("ENTRADA ANTES DE LA INDUCCIÓN DE LA ANESTESIA"), 1, 0, 'L', true);
$pdf->Cell(50, 8, utf8_decode("Hora: ___________"), 1, 1, 'L');

$pdf->Cell(140, 8, utf8_decode("PAUSA QUIRÚRGICA ANTES DE LA INCISIÓN"), 1, 0, 'L', true);
$pdf->Cell(50, 8, utf8_decode("Hora: ___________"), 1, 1, 'L');

$pdf->Cell(140, 8, utf8_decode("SALIDA ANTES DE QUE EL PACIENTE SALGA DEL QUIRÓFANO"), 1, 0, 'L', true);
$pdf->Cell(50, 8, utf8_decode("Hora: ___________"), 1, 1, 'L');

$pdf->Ln(5);

// Definir textos para cada sección
$entradas_textos = [
    "(Con el enfermero y el anestesista, como mínimo)",
    "¿Ha confirmado el paciente su identidad, el sitio quirúrgico, el procedimiento y su consentimiento?",
    "¿Se ha marcado el sitio quirúrgico?",
    "¿Se ha completado la comprobación de los aparatos de anestesia y la medicación anestésica?",
    "¿Se ha colocado el pulsioxímetro al paciente y funciona?",
    "¿Tiene el paciente alergias conocidas?",
    "¿Tiene el paciente vía aérea difícil / riesgo de aspiración?",
    "¿Sí, y hay materiales y equipos / ayuda disponible?",
    "¿Riesgo de hemorragia > 500 ml (7 ml/kg en niños)?"
];

$entradas_valores = [
    "",
    mostrar($data['confirmacion_identidad']),
    mostrar($data['sitio_marcado_si']),
    mostrar($data['verificacion_anestesia']),
    mostrar($data['pulsioximetro']),
    mostrar($data['alergias_si']),
    mostrar($data['via_aerea_si']),
    "", // este dato no está en BD o no definido
    mostrar($data['riesgo_hemo_si']),
];

$pausas_textos = [
    "(Con el enfermero, el anestesista y el cirujano)",
    "Confirmar que todos los miembros del equipo se hayan presentado por su nombre",
    "Confirmar la identidad del paciente, el sitio quirúrgico y el procedimiento",
    "¿Se ha administrado profilaxis antibiótica en los últimos 60 minutos?",
    "Previsión de eventos críticos",
    "Cirujano:",
    "¿Cuáles serán los pasos críticos o no sistematizados?",
    "¿Cuánto durará la operación?",
    "¿Cuál es la pérdida de sangre prevista?",
    "Anestesista:",
    "¿Presenta el paciente algún problema específico?",
    "Equipo de enfermería:",
    "¿Se ha confirmado la esterilidad (con resultados de los indicadores)?",
    "¿Hay dudas o problemas relacionados con el instrumental y los equipos?",
    "¿Pueden visualizarse las imágenes diagnósticas esenciales?"
];

$pausas_valores = [
    "",
    mostrar($data['miembros_presentados']),
    mostrar($data['confirmacion_identidad_equipo']),
    mostrar($data['profilaxis_antibiotica_si']),
    "", // texto general sin estado
    "",
    mostrar($data['pasos_criticos']),
    mostrar($data['duracion_operacion']),
    mostrar($data['perdida_sangre']),
    "",
    mostrar($data['problemas_paciente']),
    "",
    mostrar($data['esterilidad_confirmada']),
    mostrar($data['problemas_instrumental']),
    mostrar($data['imagenes_visibles_si']),
];

$salidas_textos = [
    "(Con el enfermero, el anestesista y el cirujano)",
    "El enfermero confirma verbalmente:",
    "El nombre del procedimiento",
    "El recuento de instrumentos, gasas y agujas",
    "El etiquetado de las muestras (lectura de la etiqueta en voz alta, incluido el nombre del paciente)",
    "Si hay problemas que resolver relacionados con el instrumental y los equipos",
    "Cirujano, anestesista y enfermero:",
    "¿Cuáles son los aspectos críticos de la recuperación y el tratamiento del paciente?"
];

$salidas_valores = [
    "",
    "",
    mostrar($data['nombre_procedimiento']),
    mostrar($data['recuento_instrumental']),
    mostrar($data['etiquetado_muestras']),
    mostrar($data['problemas_instrumental_final']),
    "",
    mostrar($data['aspectos_recuperacion']),
];

// Máximo de filas para imprimir
$max_filas = max(count($entradas_textos), count($pausas_textos), count($salidas_textos));

$pdf->SetFont('Arial', '', 9);

$w1 = 63;
$w2 = 63;
$w3 = 64;
$line_height = 5;

$widths = [$w1, $w2, $w3];

// Palabras/frases que deben ir en negrita y con fondo
function esResaltado($texto) {
    $frases_clave = [
        '(Con el enfermero y el anestesista, como mínimo)',
        '(Con el enfermero, el anestesista y el cirujano)',
        'Cirujano:',
        'Anestesista:',
        'Equipo de enfermería:',
        'Cirujano, anestesista y enfermero:'
    ];
    foreach ($frases_clave as $clave) {
        if (stripos($texto, $clave) !== false) return true;
    }
    return false;
}

for ($i = 0; $i < $max_filas; $i++) {
    $col1_raw = $entradas_textos[$i] ?? "";
    $col2_raw = $pausas_textos[$i] ?? "";
    $col3_raw = $salidas_textos[$i] ?? "";

    $col1_val = $entradas_valores[$i] ?? "";
    $col2_val = $pausas_valores[$i] ?? "";
    $col3_val = $salidas_valores[$i] ?? "";

    $col1 = utf8_decode($col1_raw . ($col1_val ? "  $col1_val" : ""));
    $col2 = utf8_decode($col2_raw . ($col2_val ? "  $col2_val" : ""));
    $col3 = utf8_decode($col3_raw . ($col3_val ? "  $col3_val" : ""));

    $bold1 = esResaltado($col1_raw);
    $bold2 = esResaltado($col2_raw);
    $bold3 = esResaltado($col3_raw);

    // Altura máxima de la fila
    $h1 = $pdf->NbLines($w1, $col1) * $line_height;
    $h2 = $pdf->NbLines($w2, $col2) * $line_height;
    $h3 = $pdf->NbLines($w3, $col3) * $line_height;
    $h = max($h1, $h2, $h3);

    $pdf->CheckPageBreak($h);
    $x = $pdf->GetX();
    $y = $pdf->GetY();

    // Dibujar bordes sin texto
    $pdf->Rect($x, $y, $w1, $h);
    $pdf->Rect($x + $w1, $y, $w2, $h);
    $pdf->Rect($x + $w1 + $w2, $y, $w3, $h);

    // Columna 1
    $pdf->SetFont('Arial', $bold1 ? 'B' : '', 9);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($w1, $line_height, $col1, 0, 'L');

    // Columna 2
    $pdf->SetFont('Arial', $bold2 ? 'B' : '', 9);
    $pdf->SetXY($x + $w1, $y);
    $pdf->MultiCell($w2, $line_height, $col2, 0, 'L');

    // Columna 3
    $pdf->SetFont('Arial', $bold3 ? 'B' : '', 9);
    $pdf->SetXY($x + $w1 + $w2, $y);
    $pdf->MultiCell($w3, $line_height, $col3, 0, 'L');

    // Salto a la siguiente fila
    $pdf->SetXY($x, $y + $h);
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("FIRMAS DEL EQUIPO QUIRÚRGICO"), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);

$firmas = [
    "NOMBRE Y FIRMA DE MÉDICO CIRUJANO",
    "NOMBRE Y FIRNA DE MÉDICO ANESTESIÓLOGO",
    "NOMBRE Y FIRMA DEL MÉDICO AYUDANTE",
    "NOMBRE Y FIRMA DE MÉDICO AYUDANTE 2",
    "NOMBRE Y FIRMA DE LA ENFERMERA CIRCULANTE",
    "NOMBRE Y FIRMA DEL INSTRUMENTISTA"
];

$ancho_celda = 65;
$alto_firma = 20;
$alto_linea = 5;
$padding = 2;

for ($row = 0; $row < 2; $row++) {
    // Línea para firmas
    for ($col = 0; $col < 3; $col++) {
        $index = $row * 3 + $col;
        if (isset($firmas[$index])) {
            $pdf->Cell($ancho_celda, $alto_firma, "__________________________", 1, 0, 'C');
        } else {
            $pdf->Cell($ancho_celda, $alto_firma, "", 1, 0);
        }
    }
    $pdf->Ln();

    // Altura uniforme por fila (opcional: calcular dinámicamente)
    $altura_celda_texto = 15;

    $y_inicio = $pdf->GetY();

    // Segunda fila: textos centrados y en paralelo
    for ($col = 0; $col < 3; $col++) {
        $index = $row * 3 + $col;
        $x = 10 + $col * $ancho_celda;
        $pdf->SetXY($x, $y_inicio);
        $pdf->Rect($x, $y_inicio, $ancho_celda, $altura_celda_texto); // Borde de la celda

        if (isset($firmas[$index])) {
            $pdf->SetXY($x + $padding, $y_inicio + 3); // pequeño margen superior
            $pdf->MultiCell($ancho_celda - 2 * $padding, 5, utf8_decode($firmas[$index]), 0, 'C');
        }

        // Restaurar Y y avanzar X
        $pdf->SetXY($x + $ancho_celda, $y_inicio);
    }

    $pdf->Ln($altura_celda_texto);
}

// Salida
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="checklist_quirurgico.pdf"');
$pdf->Output('I', 'checklist_quirurgico.pdf');
exit();
