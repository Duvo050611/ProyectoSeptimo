<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: ../index.php");
}

include("../header_medico.php");
?>

<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <!--  Bootstrap  -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <script src="../../js/jquery-3.3.1.min.js"></script>
  <script src="../../js/jquery-ui.js"></script>
  <script src="../../js/popper.min.js"></script>
  <script src="../../js/bootstrap.min.js"></script>
  <script src="../../js/jquery.magnific-popup.min.js"></script>
  <script src="../../js/aos.js"></script>
  <script src="../../js/main.js"></script>

  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search").keyup(function() {
        _this = this;
        // Show only matching TR, hide rest of them
        $.each($("#mytable tbody tr"), function() {
          if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
            $(this).hide();
          else
            $(this).show();
        });
      });
    });
  </script>
  <style>
    td.fondo {
      background-color: #2b2d7f !important;
    }
    td.cuenta{
      background-color: red; !important;
    }
  </style>
  <style>
    td.fondo2 {
      background-color: green !important;
    }
  </style>
  <style>
    td.fondo3 {
      background-color: orange !important;
    }
  </style>


</head>

<body>
  <section class="content container-fluid">

    <!--------------------------
    | Your Page Content Here |
    -------------------------->
<div class="container box">
  <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                     <tr><center><strong>CENSO DE PACIENTES</strong></center><p>

  </div>
  
    <div class="row">
       <div class="col-sm-5">
            <a href="../../gestion_administrativa/censo/pdf_censo_comp.php" class="btn btn-md btn-md btn-block btn-success" target="_blank">IMPRIMIR CENSO </a>
       <br>
       </div>
    </div>
</div>

    <div class="container box">
      <div class="container-fluid">
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                     <tr><center><strong>CONSULTA EXTERNA</strong></center><p>
        </div>

        <div class="row">
          <br />

          <div class="col-md-4">
            <input type="text" class="form-control pull-right" id="search" placeholder="BUSCAR">
          </div>

         <!-- <div class="col-md-4">
            <a href="../../gestion_administrativa/censo/pdf_censo_hosp.php" class="btn btn-md btn-md btn-block btn-success" target="_blank">IMPRIMIR CENSO HOSPITALIZACIÓN</a>
          </div>-->

          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="mytable">
              <thead class="thead">
                <tr>
                  <center>
                    <th scope="col">Espacio</th>
                  </center>
                  <th scope="col">
                    <center>Fecha de ingreso</center>
                  </th>
                  <th scope="col">
                    <center>Paciente</center>
                  </th>
                  <th scope="col">
                    <center>Edad</center>
                  </th>
                  <th scope="col">
                    <center>Diagnóstico de ingreso</center>
                  </th>
                  <th scope="col">
                    <center>Expediente</center>
                  </th>
                  <th scope="col">
                    <center>Médico tratante</center>
                  </th>
                  <th scope="col">
                    <center>Alta médica</center>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                  $sql = "SELECT * from cat_camas where TIPO ='CONSULTA' ORDER BY num_cama ASC ";
                  $result = $conexion->query($sql);
                  while ($row = $result->fetch_assoc()) {
                    $id_at_cam = $row['id_atencion'];
                    $estatus = $row['estatus'];
                    $usuario = $_SESSION['login'];
                    $id_usua= $usuario['id_usua'];
                    $rol= $usuario['id_rol'];
                    $sql_tabla = "SELECT p.fecnac,p.Id_exp, p.folio, p.papell, p.sapell,p.nom_pac, df.aseg, di.fecha, di.motivo_atn, di.alta_med,ru.pre, ru.papell as nom_doc from dat_ingreso di, dat_financieros df, paciente p, reg_usuarios ru WHERE p.Id_exp = di.Id_exp and di.id_atencion=df.id_atencion and di.id_usua = ru.id_usua and di.id_atencion = $id_at_cam LIMIT 1";
                    $result_tabla = $conexion->query($sql_tabla);
                    $rowcount = mysqli_num_rows($result_tabla);
                    if ($rowcount != 0) {
                      while ($row_tabla = $result_tabla->fetch_assoc()) {
                        $alta=$row_tabla['alta_med'];
                        $date1 = date_create($row_tabla['fecha']);
                        $fecingr = date_format($date1,"d/m/Y H:i");
                        if($alta=='SI'){
                         echo '<td class="fondo2"> <font color="white" size="2">' . $row['num_cama'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $fecingr . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . calculaedad($row_tabla['fecnac']) .'</font></font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['motivo_atn'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['folio'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['alta_med'] . '</font></td>';
                        echo '<tr>';
                        }else{
                         echo '<td class="fondo"><font color="white" size="2">' . $row['num_cama'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $fecingr . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . calculaedad($row_tabla['fecnac']) . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['motivo_atn'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['folio'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['alta_med'] . '</font></td>';
                        echo '<tr>';
                        }
                      }
                    }  elseif($estatus=="MANTENIMIENTO"){
                       echo '<td class="cuenta">' . $row['num_cama'] . '</td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta">HABITACIÓN</td>';
                      echo '<td class="cuenta">NO</td>';
                      echo '<td class="cuenta">DISPONIBLE</td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta"></td></tr>';
                    }elseif($estatus=="EN PROCESO DE LIBERA"){
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3">' . $row['num_cama'] . '</td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3">POR </td>';
                      echo '<td class="fondo3">LIBERAR</td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3"></td></tr>';
                    }
                    else {
                      echo '<td>' . $row['num_cama'] . '</td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '</tr>';
                    }
                  }
                  ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="container box">
      <div class="container-fluid">
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                     <tr><center><strong>PREPARACIÓN</strong></center><p>
        </div>

        <div class="row">
          <br />

          <div class="col-md-4">
            <input type="text" class="form-control pull-right" id="search" placeholder="BUSCAR">
          </div>

         <!-- <div class="col-md-4">
            <a href="../../gestion_administrativa/censo/pdf_censo_hosp.php" class="btn btn-md btn-md btn-block btn-success" target="_blank">IMPRIMIR CENSO HOSPITALIZACIÓN</a>
          </div>-->

          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="mytable">
              <thead class="thead">
                <tr>
                  <center>
                    <th scope="col">Habitación</th>
                  </center>
                  <th scope="col">
                    <center>Fecha de ingreso</center>
                  </th>
                  <th scope="col">
                    <center>Paciente</center>
                  </th>
                  <th scope="col">
                    <center>Edad</center>
                  </th>
                  <th scope="col">
                    <center>Diagnóstico de ingreso</center>
                  </th>
                  <th scope="col">
                    <center>Expediente</center>
                  </th>
                  <th scope="col">
                    <center>Médico tratante</center>
                  </th>
                  <th scope="col">
                    <center>Alta médica</center>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                  $sql = "SELECT * from cat_camas where TIPO ='HOSPITALIZACIÓN' ORDER BY num_cama ASC ";
                  $result = $conexion->query($sql);
                  while ($row = $result->fetch_assoc()) {
                    $id_at_cam = $row['id_atencion'];
                    $estatus = $row['estatus'];
                    $usuario = $_SESSION['login'];
                    $id_usua= $usuario['id_usua'];
                    $rol= $usuario['id_rol'];
                    $sql_tabla = "SELECT p.fecnac,p.Id_exp, p.folio, p.papell, p.sapell,p.nom_pac, df.aseg, di.fecha, di.motivo_atn, di.alta_med,ru.pre, ru.papell as nom_doc from dat_ingreso di, dat_financieros df, paciente p, reg_usuarios ru WHERE p.Id_exp = di.Id_exp and di.id_atencion=df.id_atencion and di.id_usua = ru.id_usua and di.id_atencion = $id_at_cam LIMIT 1";
                    $result_tabla = $conexion->query($sql_tabla);
                    $rowcount = mysqli_num_rows($result_tabla);
                    if ($rowcount != 0) {
                      while ($row_tabla = $result_tabla->fetch_assoc()) {
                        $alta=$row_tabla['alta_med'];
                        $date1 = date_create($row_tabla['fecha']);
                        $fecingr = date_format($date1,"d/m/Y H:i");
                        if($alta=='SI'){
                         echo '<td class="fondo2"> <font color="white" size="2">' . $row['num_cama'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $fecingr . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . calculaedad($row_tabla['fecnac']) .'</font></font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['motivo_atn'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['folio'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['alta_med'] . '</font></td>';
                        echo '<tr>';
                        }else{
                         echo '<td class="fondo"><font color="white" size="2">' . $row['num_cama'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $fecingr . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . calculaedad($row_tabla['fecnac']) . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['motivo_atn'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['folio'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['alta_med'] . '</font></td>';
                        echo '<tr>';
                        }
                      }
                    }  elseif($estatus=="MANTENIMIENTO"){
                       echo '<td class="cuenta">' . $row['num_cama'] . '</td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta">HABITACIÓN</td>';
                      echo '<td class="cuenta">NO</td>';
                      echo '<td class="cuenta">DISPONIBLE</td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta"></td></tr>';
                    }elseif($estatus=="EN PROCESO DE LIBERA"){
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3">' . $row['num_cama'] . '</td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3">POR </td>';
                      echo '<td class="fondo3">LIBERAR</td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3"></td></tr>';
                    }
                    else {
                      echo '<td>' . $row['num_cama'] . '</td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '</tr>';
                    }
                  }
                  ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
        <div class="container box">
      <div class="container-fluid">
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
                     <tr><center><strong>RECUPERACIÓN</strong></center><p>
        </div>

        <div class="row">
          <br />

          <div class="col-md-4">
            <input type="text" class="form-control pull-right" id="search" placeholder="BUSCAR">
          </div>

         <!-- <div class="col-md-4">
            <a href="../../gestion_administrativa/censo/pdf_censo_hosp.php" class="btn btn-md btn-md btn-block btn-success" target="_blank">IMPRIMIR CENSO HOSPITALIZACIÓN</a>
          </div>-->

          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="mytable">
              <thead class="thead">
                <tr>
                  <center>
                    <th scope="col">Habitación</th>
                  </center>
                  <th scope="col">
                    <center>Fecha de ingreso</center>
                  </th>
                  <th scope="col">
                    <center>Paciente</center>
                  </th>
                  <th scope="col">
                    <center>Edad</center>
                  </th>
                  <th scope="col">
                    <center>Diagnóstico de ingreso</center>
                  </th>
                  <th scope="col">
                    <center>Expediente</center>
                  </th>
                  <th scope="col">
                    <center>Médico tratante</center>
                  </th>
                  <th scope="col">
                    <center>Alta médica</center>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <?php
                  $sql = "SELECT * from cat_camas where TIPO ='OBSERVACIÓN' ORDER BY num_cama ASC ";
                  $result = $conexion->query($sql);
                  while ($row = $result->fetch_assoc()) {
                    $id_at_cam = $row['id_atencion'];
                    $estatus = $row['estatus'];
                    $usuario = $_SESSION['login'];
                    $id_usua= $usuario['id_usua'];
                    $rol= $usuario['id_rol'];
                    $sql_tabla = "SELECT p.fecnac,p.Id_exp, p.folio, p.papell, p.sapell,p.nom_pac, df.aseg, di.fecha, di.motivo_atn, di.alta_med,ru.pre, ru.papell as nom_doc from dat_ingreso di, dat_financieros df, paciente p, reg_usuarios ru WHERE p.Id_exp = di.Id_exp and di.id_atencion=df.id_atencion and di.id_usua = ru.id_usua and di.id_atencion = $id_at_cam LIMIT 1";
                    $result_tabla = $conexion->query($sql_tabla);
                    $rowcount = mysqli_num_rows($result_tabla);
                    if ($rowcount != 0) {
                      while ($row_tabla = $result_tabla->fetch_assoc()) {
                        $alta=$row_tabla['alta_med'];
                        $date1 = date_create($row_tabla['fecha']);
                        $fecingr = date_format($date1,"d/m/Y H:i");
                        if($alta=='SI'){
                         echo '<td class="fondo2"> <font color="white" size="2">' . $row['num_cama'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $fecingr . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . calculaedad($row_tabla['fecnac']) .'</font></font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['motivo_atn'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['folio'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</font></td>';
                        echo '<td class="fondo2"><font color="white" size="2">' . $row_tabla['alta_med'] . '</font></td>';
                        echo '<tr>';
                        }else{
                         echo '<td class="fondo"><font color="white" size="2">' . $row['num_cama'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $fecingr . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['papell'] . ' ' . $row_tabla['sapell'] . ' ' . $row_tabla['nom_pac'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . calculaedad($row_tabla['fecnac']) . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['motivo_atn'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['folio'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['pre'] . ' ' . $row_tabla['nom_doc'] . '</font></td>';
                        echo '<td class="fondo"><font color="white" size="2">' . $row_tabla['alta_med'] . '</font></td>';
                        echo '<tr>';
                        }
                      }
                    }  elseif($estatus=="MANTENIMIENTO"){
                       echo '<td class="cuenta">' . $row['num_cama'] . '</td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta">HABITACIÓN</td>';
                      echo '<td class="cuenta">NO</td>';
                      echo '<td class="cuenta">DISPONIBLE</td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta"></td>';
                      echo '<td class="cuenta"></td></tr>';
                    }elseif($estatus=="EN PROCESO DE LIBERA"){
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3">' . $row['num_cama'] . '</td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3">POR </td>';
                      echo '<td class="fondo3">LIBERAR</td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3"></td>';
                      echo '<td class="fondo3"></td></tr>';
                    }
                    else {
                      echo '<td>' . $row['num_cama'] . '</td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '<td></td>';
                      echo '</tr>';
                    }
                  }
                  ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    
  </section>
  </div>
  <footer class="main-footer">
    <?php
    function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
        $bisiesto=true;
        return $bisiesto;
    }

    function calculaedad($fecnac)
    {
      
     


$fecha_actual = date("Y-m-d");
$fecha_nac=$fecnac;
$fecha_de_nacimiento =strval($fecha_nac);

// separamos en partes las fechas
$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
$array_actual = explode ( "-", $fecha_actual );

$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

//ajuste de posible negativo en $días
if ($dias < 0)
{
    --$meses;

    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
    switch ($array_actual[1]) {
           case 1:     $dias_mes_anterior=31; break;
           case 2:     $dias_mes_anterior=31; break;
           case 3:     
               if (bisiesto($array_actual[0]))
                {
                    $dias_mes_anterior=29; break;
                } else {
                    $dias_mes_anterior=28; break;
                }
               
           case 4:     $dias_mes_anterior=31; break;
           case 5:     $dias_mes_anterior=30; break;
           case 6:     $dias_mes_anterior=31; break;
           case 7:     $dias_mes_anterior=30; break;
           case 8:     $dias_mes_anterior=31; break;
           case 9:     $dias_mes_anterior=31; break;
           case 10:    $dias_mes_anterior=30; break;
           case 11:    $dias_mes_anterior=31; break;
           case 12:    $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

 if($anos > "0" ){
   $edad = $anos." años";
}elseif($anos <="0" && $meses>"0"){
   $edad = $meses." meses";
    
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    $edad = $dias." días";
}

 return $edad;
}


    include("../../template/footer.php");
    ?>
  </footer>

  <script>
    document.oncontextmenu = function() {
      return false;
    }
  </script>
  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

</body>

</html>