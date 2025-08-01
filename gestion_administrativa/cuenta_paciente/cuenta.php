<?php
ob_start(); // Start output buffering to prevent FPDF errors
require '../../fpdf/fpdf.php';
include '../../conexionbd.php';
$id_atencion = mysqli_real_escape_string($conexion, @$_GET['id_atencion']);
$id_usua_log = mysqli_real_escape_string($conexion, @$_GET['id_usua']);

mysqli_set_charset($conexion, "utf8");

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', '', 10);
        $resultado = $GLOBALS['conexion']->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($GLOBALS['conexion']->error);
        if ($f = $resultado->fetch_assoc()) {
            $bas = $f['img_ipdf'];
            $this->Image("../../configuracion/admin/img2/" . $bas, 7, 8, 50, 26);
            $this->Image("../../configuracion/admin/img3/" . $f['img_cpdf'], 58, 15, 109, 24);
            $this->Image("../../configuracion/admin/img4/" . $f['img_dpdf'], 168, 16, 38, 14);
        }
        $this->Ln(30);
    }

    function Footer()
    {
        $this->SetFont('Arial', 'B', 8);
        $this->SetY(-15);
        $this->Cell(0, 8, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
        $this->Cell(0, 10, utf8_decode('MAC-20'), 0, 1, 'R');
    }
}

$date = date("d/m/Y");

$pdf = new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();

$sql_reg_usu = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre, di.Id_exp, di.alta_adm 
                FROM dat_ingreso di 
                JOIN reg_usuarios ru ON ru.id_usua = di.id_usua 
                WHERE di.id_atencion = $id_atencion";
$result_reg_usu = $conexion->query($sql_reg_usu);
$row_reg_usu = $result_reg_usu->fetch_assoc();
$papell = $row_reg_usu['papell'] ?? '';
$sapell = $row_reg_usu['sapell'] ?? '';
$nombre = $row_reg_usu['nombre'] ?? '';
$alta_adm = $row_reg_usu['alta_adm'] ?? 'NO';

$sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac, di.fecha, di.area 
            FROM paciente p 
            JOIN dat_ingreso di ON p.Id_exp = di.Id_exp 
            WHERE di.id_atencion = $id_atencion";
$result_pac = $conexion->query($sql_pac);
$row_pac = $result_pac->fetch_assoc();
$pac_papell = $row_pac['papell'] ?? '';
$pac_sapell = $row_pac['sapell'] ?? '';
$pac_nom_pac = $row_pac['nom_pac'] ?? '';
$id_exp = $row_pac['Id_exp'] ?? '';

$sql_aseg = "SELECT aseg FROM dat_ingreso WHERE id_atencion = $id_atencion";
$result_aseg = $conexion->query($sql_aseg);
$row_aseg = $result_aseg->fetch_assoc();
$aseg = $row_aseg['aseg'] ?? 'NINGUNA';
$at = $aseg;

$resultadot = $conexion->query("SELECT tip_precio FROM cat_aseg WHERE aseg = '$at'") or die($conexion->error);
$tr = 1; // Default price type
if ($filat = $resultadot->fetch_assoc()) {
    $tr = $filat["tip_precio"];
}

if ($alta_adm == 'SI' && $aseg == 'NINGUNA') {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(202, 15, utf8_decode('ESTADO DE CUENTA'), 0, 0, 'C');
    $pdf->Ln(17);
    $pdf->SetFont('Arial', '', 6);

    $sql_cajero = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre 
                   FROM dat_ingreso di 
                   JOIN reg_usuarios ru ON ru.id_usua = di.cajero 
                   WHERE di.id_atencion = $id_atencion";
    $result_cajero = $conexion->query($sql_cajero);
    $row_cajero = $result_cajero->fetch_assoc();
    $papell_caj = $row_cajero['papell'] ?? '';

    $sql_medico = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre 
                   FROM dat_ingreso di 
                   JOIN reg_usuarios ru ON ru.id_usua = di.id_usua 
                   WHERE di.id_atencion = $id_atencion";
    $result_medico = $conexion->query($sql_medico);
    $row_medico = $result_medico->fetch_assoc();
    $papell_med = $row_medico['papell'] ?? '';
    $sapell_med = $row_medico['sapell'] ?? '';

    $sql_ingreso = "SELECT motivo_atn, cama_alta FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_ingreso = $conexion->query($sql_ingreso);
    $row_ingreso = $result_ingreso->fetch_assoc();
    $motivo_atn = $row_ingreso['motivo_atn'] ?? '';
    $cama = $row_ingreso['cama_alta'] ?? '';

    $pdf->SetDrawColor(43, 45, 127);
    $pdf->Cell(145, 5, utf8_decode('PACIENTE: ' . $id_exp . ' - ' . $pac_nom_pac . ' ' . $pac_papell . ' ' . $pac_sapell), 0, 0, 'L');
    $pdf->Cell(14, 5, utf8_decode('FECHA: '), 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode(date('d/m/Y h:i a', time())), 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->MultiCell(190, 5, utf8_decode('MÉDICO TRATANTE: ' . $papell_med . ' ' . $sapell_med), 0, 'L');
    $pdf->MultiCell(190, 5, utf8_decode('MOTIVO DE INGRESO: ' . $motivo_atn), 0, 'L');
    $pdf->Cell(88, 5, utf8_decode('PERSONAL: ' . $papell_caj), 0, 0, 'L');
    $pdf->Cell(57, 5, utf8_decode('ASEGURADORA: ' . $aseg), 0, 0, 'L');
    $pdf->Cell(48, 5, utf8_decode('HABITACIÓN: ' . $cama), 0, 0, 'L');
    $pdf->Ln(4);

} elseif ($alta_adm == 'SI' && $aseg != 'NINGUNA') {
    $sql_aseg = "SELECT aseg FROM dat_financieros WHERE id_atencion = $id_atencion ORDER BY fecha ASC LIMIT 1";
    $result_aseg = $conexion->query($sql_aseg);
    $row_aseg = $result_aseg->fetch_assoc();
    $aseg = $row_aseg['aseg'] ?? $aseg;

    $sql_cajero = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre 
                   FROM dat_ingreso di 
                   JOIN reg_usuarios ru ON ru.id_usua = di.cajero 
                   WHERE di.id_atencion = $id_atencion";
    $result_cajero = $conexion->query($sql_cajero);
    $row_cajero = $result_cajero->fetch_assoc();
    $papell_caj = $row_cajero['papell'] ?? '';
    $sapell_caj = $row_cajero['sapell'] ?? '';
    $nombre_caj = $row_cajero['nombre'] ?? '';

    $sql_medico = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre 
                   FROM dat_ingreso di 
                   JOIN reg_usuarios ru ON ru.id_usua = di.id_usua 
                   WHERE di.id_atencion = $id_atencion";
    $result_medico = $conexion->query($sql_medico);
    $row_medico = $result_medico->fetch_assoc();
    $papell_med = $row_medico['papell'] ?? '';
    $sapell_med = $row_medico['sapell'] ?? '';
    $nombre_med = $row_medico['nombre'] ?? '';

    $sql_ingreso = "SELECT motivo_atn, cama_alta FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_ingreso = $conexion->query($sql_ingreso);
    $row_ingreso = $result_ingreso->fetch_assoc();
    $motivo_atn = $row_ingreso['motivo_atn'] ?? '';
    $cama = $row_ingreso['cama_alta'] ?? '';

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(202, 15, utf8_decode('ESTADO DE CUENTA'), 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 7);

    $pdf->SetDrawColor(43, 45, 127);
    $pdf->Line(10, 50, 205, 50);
    $pdf->Line(10, 50, 10, 68);
    $pdf->Line(205, 50, 205, 68);
    $pdf->Line(10, 68, 205, 68);

    $pdf->Cell(145, 5, utf8_decode('PACIENTE: ' . $id_exp . ' - ' . $pac_nom_pac . ' ' . $pac_papell . ' ' . $pac_sapell), 0, 0, 'L');
    $pdf->Cell(14, 5, utf8_decode('FECHA: '), 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode(date('d/m/Y h:i a', time())), 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->MultiCell(190, 5, utf8_decode('MÉDICO TRATANTE: ' . $papell_med . ' ' . $sapell_med), 0, 'L');
    $pdf->MultiCell(190, 5, utf8_decode('MOTIVO DE INGRESO: ' . $motivo_atn), 0, 'L');
    $pdf->Cell(88, 5, utf8_decode('PERSONAL MSI: ' . $papell_caj), 0, 0, 'L');
    $pdf->Cell(57, 5, utf8_decode('ASEGURADORA: ' . $aseg), 0, 0, 'L');
    $pdf->Cell(48, 5, utf8_decode('HABITACIÓN: ' . $cama), 0, 0, 'L');
    $pdf->Ln(4);

} else {
    $sql_medico = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre 
                   FROM dat_ingreso di 
                   JOIN reg_usuarios ru ON ru.id_usua = di.id_usua 
                   WHERE di.id_atencion = $id_atencion";
    $result_medico = $conexion->query($sql_medico);
    $row_medico = $result_medico->fetch_assoc();
    $papell_med = $row_medico['papell'] ?? '';
    $sapell_med = $row_medico['sapell'] ?? '';
    $nombre_med = $row_medico['nombre'] ?? '';

    $sql_ingreso = "SELECT motivo_atn FROM dat_ingreso WHERE id_atencion = $id_atencion";
    $result_ingreso = $conexion->query($sql_ingreso);
    $row_ingreso = $result_ingreso->fetch_assoc();
    $motivo_atn = $row_ingreso['motivo_atn'] ?? '';

    $sql_cama = "SELECT num_cama FROM cat_camas WHERE id_atencion = $id_atencion";
    $result_cama = $conexion->query($sql_cama);
    $row_cama = $result_cama->fetch_assoc();
    $cama = $row_cama['num_cama'] ?? '';

    $sql_cajero = "SELECT ru.pre, ru.papell, ru.sapell, ru.nombre 
                   FROM reg_usuarios ru 
                   WHERE ru.id_usua = $id_usua_log";
    $result_cajero = $conexion->query($sql_cajero);
    $row_cajero = $result_cajero->fetch_assoc();
    $papell_caj = $row_cajero['papell'] ?? '';
    $sapell_caj = $row_cajero['sapell'] ?? '';
    $nombre_caj = $row_cajero['nombre'] ?? '';

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(202, 12, utf8_decode('ESTADO DE CUENTA'), 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 7);

    $pdf->SetDrawColor(43, 45, 127);
    $pdf->Line(10, 50, 205, 50);
    $pdf->Line(10, 50, 10, 68);
    $pdf->Line(205, 50, 205, 68);
    $pdf->Line(10, 68, 205, 68);

    $pdf->Cell(145, 5, utf8_decode('PACIENTE: ' . $id_exp . ' - ' . $pac_nom_pac . ' ' . $pac_papell . ' ' . $pac_sapell), 0, 0, 'L');
    $pdf->Cell(14, 5, utf8_decode('FECHA: '), 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode(date('d/m/Y h:i a', time())), 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->MultiCell(190, 5, utf8_decode('MÉDICO TRATANTE: ' . $papell_med . ' ' . $sapell_med), 0, 'L');
    $pdf->MultiCell(190, 5, utf8_decode('MOTIVO DE INGRESO: ' . $motivo_atn), 0, 'L');
    $pdf->Cell(88, 5, utf8_decode('PERSONAL: ' . $papell_caj), 0, 0, 'L');
    $pdf->Cell(57, 5, utf8_decode('ASEGURADORA: ' . $aseg), 0, 0, 'L');
    $pdf->Cell(48, 5, utf8_decode('HABITACIÓN: ' . $cama), 0, 0, 'L');
    $pdf->Ln(6);
}

/**********************************************/

$precio = 0;
$iva = 0;
$subtottal = 0;
$total = 0;
$totiva = 0;
$Stotiva = 0;
$totallab = 0;
$totalimg = 0;
$totalg12 = 0;
$totalhono = 0;
$total_dep = 0;
$total_desc = 0;

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5, 5, utf8_decode('No. '), 1, 0, 'C');
$pdf->Cell(17, 5, utf8_decode('FECHA'), 1, 0, 'C');
$pdf->Cell(80, 5, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('U. DE MEDIDA'), 1, 0, 'C');
$pdf->Cell(15, 5, utf8_decode('CANTIDAD'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('P. U.'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('IVA'), 1, 0, 'C');
$pdf->Cell(20, 5, utf8_decode('SUBTOTAL'), 1, 0, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 6);

$resultado3 = $conexion->query("SELECT dc.*, p.papell, p.sapell, p.nom_pac 
                               FROM dat_ctapac dc 
                               JOIN dat_ingreso di ON dc.id_atencion = di.id_atencion 
                               JOIN paciente p ON di.Id_exp = p.Id_exp 
                               WHERE dc.id_atencion = $id_atencion 
                               ORDER BY dc.cta_fec ASC") or die($conexion->error);

$no = 1;
while ($row3 = $resultado3->fetch_assoc()) {
    $flag = $row3['prod_serv'];
    $insumo = $row3['insumo'];
    $precioh = $row3['cta_tot'];
    $cant = $row3['cta_cant'];
    $precio = 0;
    $iva = 0;
    $descripcion = '';
    $umed = '';
    $tip_s = ''; // Initialize $tip_s

    if ($insumo == 0 && $flag != 'S' && $flag != 'P' && $flag != 'PC' && $flag != 'H') {
        $descripcion = $row3['prod_serv'];
        $umed = "OTROS";
        $precio = $precioh;
        $iva = $precio * 0.16;
    } elseif ($flag == 'H') {
        $resultado_servi = $conexion->query("SELECT serv_desc, serv_umed FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
        if ($row_servi = $resultado_servi->fetch_assoc()) {
            $descripcion = $row_servi['serv_desc'];
            $umed = $row_servi['serv_umed'];
            $precio = $precioh;
            $iva = 0;
        } else {
            $descripcion = "Servicio no encontrado";
            $umed = "N/A";
            $precio = $precioh;
            $iva = 0;
        }
    } elseif ($flag == 'S') {
        $resultado_serv = $conexion->query("SELECT serv_desc, serv_umed, serv_costo, serv_costo2, serv_costo3, serv_costo4, tipo 
                                           FROM cat_servicios WHERE id_serv = $insumo") or die($conexion->error);
        if ($row_serv = $resultado_serv->fetch_assoc()) {
            if ($tr == 1) $precio = $row_serv['serv_costo'];
            elseif ($tr == 2) $precio = $row_serv['serv_costo2'];
            elseif ($tr == 3) $precio = $row_serv['serv_costo3'];
            elseif ($tr == 4) $precio = $row_serv['serv_costo4'];
            else $precio = $precioh;
            $descripcion = $row_serv['serv_desc'];
            $umed = $row_serv['serv_umed'];
            $tip_s = $row_serv['tipo'];
            if ($tip_s == '1') $umed = 'LABORATORIO';
            elseif ($tip_s == '2') $umed = 'IMAGENOLOGIA';
            $iva = $precio * 0.16;
            if ($tip_s == '1') $totallab += $subtottal; // Move inside the block
            if ($tip_s == '2') $totalimg += $subtottal; // Move inside the block
        } else {
            $descripcion = "Servicio no encontrado";
            $umed = "N/A";
            $precio = $precioh;
            $iva = $precio * 0.16;
        }
    } elseif ($flag == 'P' || $flag == 'PC') {
        $resultado_prod = $conexion->query("SELECT i.item_name, it.item_type_desc 
                                           FROM item i 
                                           JOIN item_type it ON i.item_type_id = it.item_type_id 
                                           WHERE i.item_id = $insumo") or die($conexion->error);
        if ($row_prod = $resultado_prod->fetch_assoc()) {
            $descripcion = $row_prod['item_name'];
            $umed = ($flag == 'P') ? 'FARMACIA, ' . $row_prod['item_type_desc'] : 'QUIRÓFANO, ' . $row_prod['item_type_desc'];
            $precio = $precioh;
            $iva = $precio * 0.16;
        } else {
            $descripcion = "Producto no encontrado";
            $umed = "N/A";
            $precio = $precioh;
            $iva = $precio * 0.16;
        }
    }

    $precio = $precio + $iva;
    $subtottal = $precio * $cant;
    $totiva = $iva * $cant;
    $Stotiva += $totiva;
    $total += $subtottal;

    $date = date_create($row3['cta_fec']);
    $pdf->Cell(5, 5, utf8_decode($no), 1, 0, 'C');
    $pdf->Cell(17, 5, date_format($date, 'd/m/Y'), 1, 0, 'C');
    $pdf->Cell(80, 5, utf8_decode($descripcion), 1, 0, 'L');
    $pdf->Cell(18, 5, utf8_decode($umed), 1, 0, 'L');
    $pdf->Cell(15, 5, utf8_decode($cant), 1, 0, 'C');
    $pdf->Cell(20, 5, '$ ' . utf8_decode(number_format($precio, 2)), 1, 0, 'R');
    $pdf->Cell(20, 5, '$ ' . utf8_decode(number_format($totiva, 2)), 1, 0, 'R');
    $pdf->Cell(20, 5, '$ ' . utf8_decode(number_format($subtottal, 2)), 1, 0, 'R');
    $pdf->Ln(5);

    $no++;
}

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(5, 5, '', 0, 0, 'C');
$pdf->Cell(17, 5, '', 0, 0, 'C');
$pdf->Cell(80, 5, '', 0, 0, 'L');
$pdf->Cell(18, 5, '', 0, 0, 'L');
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(20, 5, 'Total: ', 1, 0, 'R');
$pdf->Cell(20, 5, '$ ' . number_format($Stotiva, 2), 1, 0, 'R');
$pdf->Cell(20, 5, '$ ' . number_format($total, 2), 1, 0, 'R');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(202, 10, utf8_decode('PAGOS O ABONOS'), 0, 0, 'C');
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 5, utf8_decode('FORMA DE PAGO'), 1, 0, 'C');
$pdf->Cell(60, 5, utf8_decode('DETALLE'), 1, 0, 'C');
$pdf->Cell(62, 5, utf8_decode('RECIBIÓ'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('CANTIDAD'), 1, 0, 'C');
$pdf->Cell(27, 5, utf8_decode('FECHA Y HORA'), 1, 0, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 6);
$resultado4 = $conexion->query("SELECT df.*, r.papell 
                               FROM dat_financieros df 
                               JOIN reg_usuarios r ON df.id_usua = r.id_usua 
                               WHERE df.banco != 'DESCUENTO' AND df.id_atencion = $id_atencion") or die($conexion->error);
$total_dep = 0;
while ($row4 = $resultado4->fetch_assoc()) {
    $fecha1 = date_create($row4['fecha']);
    $pdf->Cell(28, 5, utf8_decode($row4['banco']), 1, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode($row4['resp']), 1, 0, 'L');
    $pdf->Cell(62, 5, utf8_decode($row4['papell']), 1, 0, 'L');
    $pdf->Cell(18, 5, '$ ' . number_format($row4['deposito'], 2), 1, 0, 'R');
    $pdf->Cell(27, 5, date_format($fecha1, 'd/m/Y H:i A'), 1, 0, 'L');
    $pdf->Ln(5);
    $total_dep += $row4['deposito'];
}

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(148, 5, 'Total: ', 1, 0, 'R');
$pdf->Cell(18, 5, '$ ' . number_format($total_dep, 2), 1, 0, 'R');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(202, 10, utf8_decode('DESCUENTOS'), 0, 0, 'C');
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 5, utf8_decode('TIPO'), 1, 0, 'C');
$pdf->Cell(60, 5, utf8_decode('DETALLE'), 1, 0, 'C');
$pdf->Cell(62, 5, utf8_decode('REGISTRÓ'), 1, 0, 'C');
$pdf->Cell(18, 5, utf8_decode('CANTIDAD'), 1, 0, 'C');
$pdf->Cell(27, 5, utf8_decode('FECHA Y HORA'), 1, 0, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 6);
$resultado4 = $conexion->query("SELECT df.*, r.papell 
                               FROM dat_financieros df 
                               JOIN reg_usuarios r ON df.id_usua = r.id_usua 
                               WHERE df.banco = 'DESCUENTO' AND df.id_atencion = $id_atencion") or die($conexion->error);
$total_desc = 0;
while ($row4 = $resultado4->fetch_assoc()) {
    $fecha1 = date_create($row4['fecha']);
    $pdf->Cell(28, 5, utf8_decode($row4['banco']), 1, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode($row4['resp']), 1, 0, 'L');
    $pdf->Cell(62, 5, utf8_decode($row4['papell']), 1, 0, 'L');
    $pdf->Cell(18, 5, '$ ' . number_format($row4['deposito'], 2), 1, 0, 'R');
    $pdf->Cell(27, 5, date_format($fecha1, 'd/m/Y H:i A'), 1, 0, 'L');
    $pdf->Ln(5);
    $total_desc += $row4['deposito'];
}

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(148, 5, 'Total: ', 1, 0, 'R');
$pdf->Cell(18, 5, '$ ' . number_format($total_desc, 2), 1, 0, 'R');
$pdf->Ln(10);

$saldo = $total - $total_desc;
if ($total_dep > 0) {
    $total_cta = $saldo - $total_dep;
    if ($total_cta < 0) $total_cta = abs($total_cta);
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(145);
$pdf->Cell(30, 5, 'Subtotal: ', 0, 0, 'R');
$pdf->MultiCell(30, 5, '$ ' . number_format($total, 2), 0, 'R');

if ($total_desc > 0) {
    $pdf->SetX(145);
    $pdf->Cell(30, 5, 'Descuento: ', 0, 0, 'R');
    $pdf->MultiCell(30, 5, '$ ' . number_format($total_desc, 2), 0, 'R');
}

$pdf->SetX(175);
$pdf->MultiCell(30, 3, '_____________', 0, 'R');
$pdf->SetX(145);
$pdf->Cell(30, 5, 'Total: ', 0, 0, 'R');
$pdf->MultiCell(30, 5, '$ ' . number_format($saldo, 2), 0, 'R');

if ($total_dep > 0) {
    $pdf->SetX(145);
    $pdf->Cell(30, 5, 'Pagos/Abonos: ', 0, 0, 'R');
    $pdf->MultiCell(30, 5, '$ ' . number_format($total_dep, 2), 0, 'R');
    $pdf->SetX(145);
    $pdf->Cell(30, 5, 'Saldo actual: ', 0, 0, 'R');
    $pdf->MultiCell(30, 5, '$ ' . number_format($total_cta, 2), 0, 'R');
}

$pdf->Ln(5);
ob_end_clean(); // Clean output buffer before sending PDF
$pdf->Output();