<?php
session_start();
include "../../conexionbd.php";
require '../../fpdf/fpdf.php';

if (!isset($_SESSION['login']) || !isset($_SESSION['hospital'])) {
    header("Location: ../login.php");
    exit();
}

$id_atencion = $_SESSION['hospital'];

// Obtener datos del paciente
$sql_pac = "SELECT p.papell, p.sapell, p.nom_pac, p.Id_exp, p.resp, p.paren, p.edad, p.sexo, p.dir, p.tel, di.id_usua 
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

// Obtener datos del médico tratante
$sql_doc = "SELECT pre, papell, sapell, nombre, firma FROM reg_usuarios WHERE id_usua = ? LIMIT 1";
$stmt = $conexion->prepare($sql_doc);
$stmt->bind_param("i", $pac['id_usua']);
$stmt->execute();
$res_doc = $stmt->get_result();
$med = $res_doc->fetch_assoc();
$stmt->close();

// Clase personalizada
class PDF extends FPDF {
    function Header() {
        include "../../conexionbd.php";
        $res = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1");
        while ($f = $res->fetch_assoc()) {
            $this->Image("../../configuracion/admin/img2/{$f['img_ipdf']}", 7, 11, 40, 25);
            $this->Image("../../configuracion/admin/img3/{$f['img_cpdf']}", 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/{$f['img_dpdf']}", 168, 16, 38, 14);
        }
        $this->SetY(45);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 10, utf8_decode('CARTA DE CONSENTIMIENTO INFORMADO PARA ANESTESIA'), 0, 1, 'C');
    }

    function Footer() {
        $this->SetY(-25);
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 4, utf8_decode(
            "Av. Tecnológico 1020, Col. Bellavista, C.P. 52172, Metepec, Edo. de México\n" .
            "Teléfonos: (722) 232.8086 / (722) 238.6901   \nEmail: inst.enfermedadesoculares@gmail.com"
        ), 0, 'C');
        $this->Ln(1);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 5, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Datos
$nombrePaciente = $pac['papell'] . ' ' . $pac['sapell'] . ' ' . $pac['nom_pac'];
$edadPaciente = $pac['edad'];
$sexoPaciente = $pac['sexo'];
$domicilioPaciente = $pac['dir'];
$telefonoPaciente = $pac['tel'];
$nombreResponsable = $pac['resp'];
$parentesco = $pac['paren'];
$nombreMedico = $med ? ($med['pre'] . ' ' . $med['papell'] . ' ' . $med['sapell'] . ' ' . $med['nombre']) : '________________________';

$pdf = new PDF('P', 'mm', 'Letter');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 25);
$pdf->SetFont('Arial', '', 10.5);

// CUADRO CON DATOS DEL PACIENTE Y REPRESENTANTE
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 9);
$datos = "Metepec, Mexico a ______ de __________ del ______\n" .
"NOMBRE DEL PACIENTE: " . $nombrePaciente . "   EDAD: " . $edadPaciente . "   SEXO: " . strtoupper($sexoPaciente) . "\n" .
"DOMICILIO: " . $domicilioPaciente . "   TEL: " . $telefonoPaciente . "\n" .
"NOMBRE DEL REPRESENTANTE LEGAL: " . $nombreResponsable . "   EDAD: __________\n" .
"RELACIÓN CON EL PACIENTE: " . $parentesco . "   DOMICILIO: " . $domicilioPaciente;
$pdf->MultiCell(0, 5, utf8_decode($datos), 1, 'L');
$pdf->Ln(3);

// CUERPO LEGAL
// Párrafo introductorio
$pdf->SetFont('Arial', '', 10.5);
$pdf->MultiCell(0, 5, utf8_decode("Yo____________________________________________________________, en pleno uso de mis facultades mentales y en mi calidad de paciente, o representante legal de este:"), 0, 'J');
$pdf->Ln(2);

// Declaración destacada
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 7, utf8_decode("DECLARO EN FORMA LIBRE Y VOLUNTARIA LO SIGUIENTE:"), 0, 1, 'C');
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 10);
$texto = <<<EOD

En base a mi derecho inalienable de elegir a mi médico, acepto al Dr.(a)____________________________________________________ como mi Médico Anestesiólogo,  quién está avalado por el Colegio de Anestesiólogos de___________, por la Federación Mexicana de Colegios de Anestesiología, A.C., y debidamente autorizado para ejercer la Anestesiología por la Oficina Estatal de Profesiones de Gobierno del Estado de______________

Entiendo que las complicaciones, aunque poco probables, son posibles, y pueden ser desde leves, tales como: pérdida o daño de una pieza dental, dolor de espalda, o en el sitio de punción, dolor de cabeza, alteraciones asociadas con la posición quirúrgica, dificultad transitoria para orinar, molestias oculares o de garganta, heridas en boca y tos; hasta severas tales como aspiración del contenido gástrico, descompensación de mis enfermedades crónicas, alteraciones cardiacas, renales, de la presión arterial, complicaciones pulmonares, reacciones medicamentosas, transfusionales, lesiones nerviosas o de médula espinal. Todas ellas pudieran causar secuelas permanentes e incluso llevar al fallecimiento. El beneficio que obtendré con la aplicación de la anestesia es que se pueda llevar a cabo el procedimiento diagnóstico y/o quirúrgico llamado _________________________________________ para intentar mejorar mi estado de salud.

Entiendo también que todo acto médico implica una serie de riesgos que pueden deberse a mi estado de salud, alteraciones congénitas o anatómicas que padezca, mis antecedentes de enfermedades, tratamientos actuales y previos, a la técnica anestésica o quirúrgica, al equipo médico utilizado y/o a la enfermedad que condiciona el procedimiento médico o quirúrgico al que he decidido someterme.

Estoy consciente de que puedo requerir de tratamientos complementarios que aumenten mi estancia hospitalaria con la participación de otros servicios o unidades médicas, con el incremento consecuente de los costos.

El Médico Anestesiólogo ha respondido mis dudas y me ha explicado en lenguaje claro y sencillo las alternativas anestésicas posibles y ACEPTO anestesia tipo __________________________________________________________________________, que es de carácter electivo___ urgente___ y he entendido los posibles riesgos y complicaciones de esta técnica anestésica.

Se me ha explicado que en mi atención pudieran intervenir médicos en entrenamiento de la especialidad de Anestesiología, pero siempre bajo la vigilancia y supervisión de mi Médico Anestesiólogo.

En mi presencia han sido llenados o cancelados todos los espacios en blanco que se presentan en este documento.

Se me ha informado que de no existir este documento en mi expediente, no se podrá llevar a cabo el procedimiento planeado.

En virtud de estar aclaradas todas mis dudas, DOY MI CONSENTIMIENTO para que mi persona o representado, pueda ser anestesiado con los riesgos inherentes al procedimiento y autorizo al anestesiólogo para que de acuerdo a su criterio, cambie la técnica anestésica intentando con ello resolver cualquier situación que se presente durante el acto anestésico-quirúrgico o de acuerdo a mis condiciones físicas y/o emocionales.
EOD;

$pdf->MultiCell(0, 4.5, utf8_decode($texto), 0, 'J');
$pdf->Ln(3);

// FIRMAS
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(90, 6, "__________________________________________", 0, 0, 'L');
$pdf->Cell(0, 6, "__________________________________________", 0, 1, 'R');
$pdf->Cell(90, 6, "NOMBRE Y FIRMA DEL MEDICO", 0, 0, 'L');
$pdf->Cell(0, 6, "NOMBRE Y FIRMA DEL PACIENTE O REPRESENTANTE LEGAL", 0, 1, 'R');
$pdf->Ln(2);
$pdf->Cell(90, 6, "__________________________________________", 0, 0, 'L');
$pdf->Cell(0, 6, "__________________________________________", 0, 1, 'R');
$pdf->Cell(90, 6, "NOMBRE Y FIRMA TESTIGO", 0, 0, 'L');
$pdf->Cell(0, 6, "NOMBRE Y FIRMA TESTIGO", 0, 1, 'R');

// NEGACIÓN
$pdf->Ln(4);
$pdf->SetFont('Arial', '', 8);
$textoNeg = "NEGACIÓN DEL CONSENTIMIENTO INFORMADO\nPor la presente, NIEGO el consentimiento para que sean practicados en mí o en mi representado el manejo de la técnica anestésica y lo que derive de ella, consciente de que he sido informado de las consecuencias que resulten de esta negativa.\n______________________________________________________\nNOMBRE Y FIRMA DEL PACIENTE O REPRESENTANTE LEGAL";
$pdf->MultiCell(0, 4, utf8_decode($textoNeg), 1, 'J');

// REVOCACIÓN
$pdf->Ln(2);
$textoRev = "REVOCACIÓN DEL CONSENTIMIENTO INFORMADO\nPor la presente, REVOCO el consentimiento otorgado en fecha ___________________________ y es mi deseo no proseguir el manejo anestésico que se indica en mí o en mi representado a partir de esta fecha ______________, relevando de toda responsabilidad al anestesiólogo, toda vez que he entendido los alcances que conlleva esta revocación.\n___________________________________\nNOMBRE Y FIRMA DEL PACIENTE O REPRESENTANTE LEGAL";
$pdf->MultiCell(0, 4, utf8_decode($textoRev), 1, 'J');


// Salida
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="carta_consentimiento_anestesia.pdf"');
$pdf->Output('I', 'carta_consentimiento_anestesia.pdf');
exit();
