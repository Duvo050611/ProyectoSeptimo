<?php
session_start();
include "../../conexionbd.php";
include("../header_medico.php");
$resultado = $conexion->query("select paciente.*, dat_ingreso.especialidad, dat_ingreso.area, dat_ingreso.motivo_atn, dat_ingreso.fecha, dat_ingreso.id_atencion
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp WHERE dat_ingreso.area='EGRESO DE URGENCIAS'") or die($conexion->error);
?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />

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
  <title>Menu Gestión Médica </title>
  <link rel="shortcut icon" href="logp.png">
</head>
<div class="container">
</div>

<body>

  <?php
  function calculaedad($fechanacimiento)
  {
    list($ano, $mes, $dia) = explode("-", $fechanacimiento);
    $ano_diferencia  = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia   = date("d") - $dia;
    if ($dia_diferencia < 0 || $mes_diferencia < 0)
      $ano_diferencia--;
    return $ano_diferencia;
  }

  $id_atencion = $_SESSION['hospital'];


  $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac, di.fecha, di.area, di.alta_med, p.sexo, p.tip_san  FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

  $result_pac = $conexion->query($sql_pac);

  while ($row_pac = $result_pac->fetch_assoc()) {
    $pac_nom =  $row_pac['papell'] . ' ' . $row_pac['sapell'] . ' ' . $row_pac['nom_pac'];
    $pac_fecnac = $row_pac['fecnac'];
    $pac_fecnac1 = '"' . $row_pac['fecnac'] . '"';
    $pac_fecing = $row_pac['fecha'];
    $area = $row_pac['area'];
    $alta_med = $row_pac['alta_med'];
    $exp = $row_pac['Id_exp'];
    $sexo = $row_pac['sexo'];
    $tipo_sang = $row_pac['tip_san'];
  }

  $date = date_create($pac_fecnac);
  $edad = calculaedad($pac_fecnac);

  $usuario = $_SESSION['login'];
  $usuario2 = $usuario['id_usua'];
  if ($sexo == "MUJER") {
  ?>


    <section class="content container-fluid">
      <center>
        <div class="container">
       <div class="thead" style="background-color: #0c675e; color: white; font-size: 24px;">
  <center><strong>PARTOGRAMA</strong></center><p>
</div>
</div>
      </center>
      <div class="container box"><br>
        <div class="container">
      <div class="row">
        <div class="col-sm-6">
          NO.EXPEDIENTE : <td><strong><?php echo $exp ?></strong></td><br>
          PACIENTE : <td><strong><?php echo $pac_nom ?></strong></td><br>
          SEXO : <td><strong><?php echo $sexo ?></strong></td><br>

        </div>

        <div class="col-sm-6">
          TIPO DE SANGRE : <td><strong><?php echo $tipo_sang ?></strong></td><br>
          FECHA DE NACIMIENTO : <td><strong><?php echo date_format($date, "d/m/Y"); ?></strong></td><br>
          EDAD : <td><strong><?php echo $edad ?></strong></td><br>
        </div>
      </div>
</div>
     <hr>
           <?php 
$id_partograma=$_GET['id_partograma'];
$alta="SELECT * FROM partograma where id_partograma=$id_partograma";
$result=$conexion->query($alta);
while ($row=$result->fetch_assoc()) {
  $id_usua=$row['id_usua'];
 ?>
        <div class="content">
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col-sm">
                <label for="hc_ges">GESTA</label><br>
                <input type="number" min="0" value="<?php echo $row['gestas'] ?>" step="1" placeholder="Gestaciones" name="gestaciones" id="gestaciones" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
              </div>
              <div class="col-sm">
                <label for="hc_par">PARTOS</label><br>
                <input type="number" value="<?php echo $row['partos'] ?>" min="0" step="1" name="partos" placeholder="Partos" id="partos" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
              </div>
              <div class="col-sm">
                <label for="hc_ces">CESÁREAS</label><br>
                <input type="number" min="0" value="<?php echo $row['cesareas'] ?>" step="1" placeholder="Cesáreas" name="cesareas" id="cesareas" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
              </div>
              <div class="col-sm">
                <label for="hc_abo">ABORTOS</label><br>
                <input type="number" min="0" value="<?php echo $row['abortos'] ?>" step="1" placeholder="Abortos" name="abortos" id="abortos" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
              </div>
              <div class="col-sm">
                <label for="hc_fechafur">FECHA ÚLTIMA REGLA</label><br>
                <input type="date" min="0" value="<?php echo $row['fur'] ?>" step="1" name="fur" placeholder="Fecha de última regla" id="fur" style="text-transform:uppercase;" required class="form-control">
              </div>
            </div>
            <p>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="no_hijos">NO. DE HIJOS VIVOS: </label><br>
                  <input type="number" min="0" value="<?php echo $row['no_hijos'] ?>" step="1" name="no_hijos" placeholder="Número de hijos" id="no_hijos" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="malformaciones">Malformados: </label><br>
                  <input type="number" min="0" value="<?php echo $row['malformaciones'] ?>" step="1" name="malformaciones" placeholder="Malformacioness" id="malformaciones" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="f_ucesarea">FECHA DE ÚLTIMO PARTO CESÁREA: </label><br>
                  <input type="date" value="<?php echo $row['f_ucesarea'] ?>" name="f_ucesarea" placeholder="Fecha de último parto cesárea" id="f_ucesarea" style="text-transform:uppercase;" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="fpp">FECHA PROBABLE DE PARTO: </label><br>
                  <input type="date" min="0" step="1" value="<?php echo $row['fpp'] ?>" placeholder="FPP" name="fpp" id="fpp" style="text-transform:uppercase;" required class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="sem_gestacion">EMBARAZO ACTUAL-SEMANAS GESTACIÓN: </label><br>
                  <input type="number" min="0" step="0.1" value="<?php echo $row['sem_gestacion'] ?>" max="50" name="sem_gestacion" placeholder="Embarazo actual-semanas gestación" id="sem_gestacion" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
                </div>
              </div>
            </div>
            

            <?php
            if ($tipo_sang == "NO ESPECIF") {
            ?>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">TIPO DE SANGRE:</label>
                    <select id="tip_san" class="form-control" name="tip_san" required>
                      <option value="<?php echo $row['tip_san'] ?>"><?php echo $row['tip_san'] ?></option>
                      <option></option>
                      <option value="" disabled="">Seleccionar tipo de sangre</option>
                      <option value="O Rh(-)">O Rh(-)</option>
                      <option value="O Rh(+)">O Rh(+)</option>
                      <option value="A Rh(-)">A Rh(-)</option>
                      <option value="A Rh(+)">A Rh(+)</option>
                      <option value="B Rh(-)">B Rh(-)</option>
                      <option value="B Rh(+)">B Rh(+)</option>
                      <option value="AB Rh(-)">AB Rh(-)</option>
                      <option value="AB Rh(+)">AB Rh(+)</option>
                      <option value="NO ESPECIFICADO">NO ESPECIFICADO</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="no_consultas">NO. DE CONSULTAS: </label><br>
                    <input type="number" min="0" step="1" value="<?php echo $row['no_consultas'] ?>" max="50" name="no_consultas" placeholder="No. de consultas" id="no_consultas" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
                  </div>
                </div>
              </div>
            <?php
            } else {
            ?>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="no_consultas">NO. DE CONSULTAS: </label><br>
                  <input type="number" min="0" step="1" value="<?php echo $row['no_consultas'] ?>" max="50" name="no_consultas" placeholder="No. de consultas" id="no_consultas" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
                </div>
              </div>
            </div>
            <?php
            }
            ?>
<div class="container">
            <div class="row">
              <?php 
              if ($row['c_perinatal']== "NO") {?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="c_perinatal">CONTROL PERINATAL: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="c_perinatal" id="c_perinatal1" value="NO" checked required>
                    <label class="form-check-label" for="exampleRadios1">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="c_perinatal" id="c_perinatal2" value="SI">
                    <label class="form-check-label" for="exampleRadios2">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php }elseif ($row['c_perinatal']== "SI") {?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="c_perinatal">CONTROL PERINATAL: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="c_perinatal" id="c_perinatal1" value="NO"  required>
                    <label class="form-check-label" for="exampleRadios1">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="c_perinatal" id="c_perinatal2" checked value="SI">
                    <label class="form-check-label" for="exampleRadios2">
                      SI
                    </label>
                  </div>
                </div>
              </div>
              <?php } ?>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="unidad">UNIDAD</label><br>
                  <input type="text" min="0" step="1" max="50" value="<?php echo $row['unidad'] ?>" name="unidad" placeholder="Unidad" id="unidad" style="text-transform:uppercase;" onkeypress="return SoloLetras(event);" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
            </div>
</div>
<div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="lab_prev">LAB. PREV. REC.: </label><br>
                <textarea name="lab_prev" class="form-control" rows="5"><?php echo $row['lab_prev_rec'] ?></textarea>
              </div>
            </div>
        
            <div class="col-md-12">
              <label for="complicaciones_actual">COMPLICACIONES DEL EMBARAZO ACTUAL:</label></strong>
              <textarea name="complicaciones_actual" class="form-control" rows="5"><?php echo $row['comp_emb_act'] ?></textarea>
            </div>
            <div class="col-md-12">
              <label for="tratamiento">TRATAMIENTO:</label></strong>
              <textarea name="tratamiento" class="form-control" rows="5"><?php echo $row['tratamiento'] ?></textarea>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="c_uterina">CONTRACTILIDAD UTERINA: </label><br>
                <input type="text" name="c_uterina" value="<?php echo $row['c_uterina'] ?>" placeholder="Contractilidad Uterina" id="c_uterina" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                <label for="c_uterina">Inicia en 10 min.</label><br>
              </div>
            </div>
          </div>
            <div class="row">
              <?php if ($row['sang_tv']== "NO") {?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="sangrado_tv">SANGRADO TRASVERSAL: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="sangrado_tv" id="sangrado_tv1" value="NO" checked>
                    <label class="form-check-label" for="sangrado_tv">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="sangrado_tv" id="sangrado_tv2" value="SI">
                    <label class="form-check-label" for="sangrado_tv">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php }elseif($row['sang_tv']== "SI"){?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="sangrado_tv">SANGRADO TRASVERSAL: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="sangrado_tv" id="sangrado_tv1" value="NO" >
                    <label class="form-check-label" for="sangrado_tv">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="sangrado_tv" id="sangrado_tv2" value="SI" checked>
                    <label class="form-check-label" for="sangrado_tv">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php } ?>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="fecha_inicio">INICIO FECHA</label><br>
                      <input type="date" name="fecha_inicio" value="<?php echo $row['inicio_fecha'] ?>" id="fecha_inicio" style="text-transform:uppercase;" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="hora_inicio"> HORA</label><br>
                      <input type="time" name="hora_inicio" value="<?php echo $row['inicio_hora'] ?>" id="hora_inicio" style="text-transform:uppercase;" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <?php if ($row['rpm']== "NO") {?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="r_p_m">RUPTURA PREMATURA DE MEMBRANAS: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="r_p_m" id="r_p_m1" value="NO" checked>
                    <label class="form-check-label" for="r_p_m1">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="r_p_m" id="r_p_m2" value="SI">
                    <label class="form-check-label" for="r_p_m2">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php }elseif ($row['rpm']== "SI") {?>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="r_p_m">RUPTURA PREMATURA DE MEMBRANAS: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="r_p_m" id="r_p_m1" value="NO" >
                    <label class="form-check-label" for="r_p_m1">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="r_p_m" id="r_p_m2" value="SI" checked>
                    <label class="form-check-label" for="r_p_m2">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php } ?>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="fecha_rpm">FECHA</label><br>
                      <input type="date" name="fecha_rpm" id="fecha_rpm" value="<?php echo $row['fecha_rpm'] ?>" style="text-transform:uppercase;" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="hora_rpm">HORA</label><br>
                      <input type="time" name="hora_rpm" id="hora_rpm" value="<?php echo $row['hora_rpm'] ?>" style="text-transform:uppercase;" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="no_consultas_urg">NO. DE CONSULTA DE URGENCIAS: </label><br>
                  <input type="number" min="0" step="1" max="50" value="<?php echo $row['no_consul_urg'] ?>" name="no_consultas_urg" placeholder="No. de consultas" id="no_consultas_urg" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
                </div>
              </div>
              <?php if ($row['mot_fetal']== "NO") {?>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="motilidad">MOTILIDAD FETAL: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="motilidad" id="motilidad1" value="NO" checked>
                    <label class="form-check-label" for="motilidad">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="motilidad" id="motilidad2" value="SI">
                    <label class="form-check-label" for="motilidad">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php }elseif ($row['mot_fetal']== "SI") {?>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="motilidad">MOTILIDAD FETAL: </label><br>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="motilidad" id="motilidad1" value="NO" >
                    <label class="form-check-label" for="motilidad">
                      NO
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="motilidad" id="motilidad2" value="SI" checked>
                    <label class="form-check-label" for="motilidad">
                      SI
                    </label>
                  </div>
                </div>
              </div>
            <?php } ?>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="dism">DISM: </label><br>
                  <input type="text" name="dism" placeholder="DISM" value="<?php echo $row['dism'] ?>" id="dism" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="nl">NL: </label><br>
                  <input type="text" name="nl" placeholder="NL" value="<?php echo $row['nl'] ?>" id="nl" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="p_sistolica">TENSIÓN ARTERIAL: </label>
                  <input type="number" name="p_sistolica" min="0" placeholder="mmHg" id="p_sistolica" class="form-control-sm" style="text-transform:uppercase;" value="<?php echo $row['p_sistolica'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required> /
                  <label for="p_sistolica"><input type="number" min="0" name="p_diastolica" placeholder="mmHg" id="p_diastolica" class="form-control-sm" style="text-transform:uppercase;" value="<?php echo $row['p_diastolica'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                    <label for="p_diastolica">MMHG</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="temp">TEMP: </label><br>
                  <input type="number" min="30" step="0.1" value="<?php echo $row['temp'] ?>" max="50" name="temp" placeholder="Temperatura" id="temp" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="f_card">FRECUENCIA CARDIACA:</label><br>
                  <input type="number" name="f_card" min="0" step="1" value="<?php echo $row['fc'] ?>" placeholder="Frecuencia cardiaca" id="f_card" class="form-control-sm" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                </div>
                <div class="form-group">
                  <label for="f_resp">FRECUENCIA RESPIRATORIA:</label><br>
                  <input type="number" name="f_resp" min="0" step="1" value="<?php echo $row['fr'] ?>" placeholder="Frecuencia respiratoria" id="f_resp" class="form-control-sm" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="edema">EDEMA: </label><br>
                  <input type="text" name="edema" placeholder="EDEMA" id="edema" value="<?php echo $row['edema'] ?>" style="text-transform:uppercase;"  onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <center>
                  <label>ALTURA DE UTERO. : </label><br>
                  <select class="form-control" name="a_utero" required>
                    <option value="<?php echo $row['alt_utero'] ?>"><?php echo $row['alt_utero'] ?></option>
                    <option></option>
                    <option value="16">16</option>
                    <option value="18">18</option>
                    <option value="20">20</option>
                    <option value="22">22</option>
                    <option value="24">24</option>
                    <option value="26">26</option>
                    <option value="28">28</option>
                  </select>
                  <img src="../../img/altura_utero.jpeg" width="200px" />
                </center>
              </div>

              <div class="col-md-4">
                <center>
                  <label>DILATACIÓN Y POSICIÓN : </label><br>
                  <img src="../../img/dilatacion_pocision.jpeg" width="200px" />
                </center>
              </div>

              <div class="col-md-4">
                <center>
                  <label for="altura_p">ALTURA DE LA PRESENTACIÓN: </label><br>
                  <img src="../../img/altura_presentacion.jpg" width="200px" />
                </center>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="fcf">FRECUENCIA CARDIACA FETAL: </label><br>
                  <input type="number" min="0" step="1" name="fcf" value="<?php echo $row['fcf'] ?>" placeholder="FCF" id="fcf" Fstyle="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" required class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="ritmo">RÍTMO: </label><br>
                  <input type="text" name="ritmo" placeholder="Rítmo" value="<?php echo $row['ritmo'] ?>" id="fritmocf" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="tono_u">TONO UTERINO: </label><br>
                  <input type="text" name="tono_u" placeholder="Tono uterino" value="<?php echo $row['t_uterino'] ?>" id="tono_u" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="membranas">MEMBRANAS INTEGRAS: </label><br>
                  <input type="text" name="membranas" value="<?php echo $row['memb_int'] ?>" placeholder="Membranas Integras" id="membranas" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">

                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="rotas">ROTAS: </label><br>
                  <input type="text" name="rotas" placeholder="Rotas" value="<?php echo $row['rotas'] ?>" id="rotas" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="aspecto_la">ASPECTO DEL LIQUIDO AMNIOTICO L.A: </label><br>
                  <input type="text" name="aspecto_la" value="<?php echo $row['asp_la'] ?>" placeholder="Aspecto de la L.A" id="aspecto_la" style="text-transform:uppercase;"  onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="cervix">CERVIX:BORRAMIENTO: </label><br>
                  <input type="text" name="cervix" placeholder="CERVIX:Borramiento" value="<?php echo $row['cervix'] ?>" id="cervix" style="text-transform:uppercase;"  onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="dilatacion">DILATACIÓN: </label><br>
                  <select class="form-control" name="dilatacion_p" required>
                    <option value="<?php echo $row['dilatacion'] ?>"><?php echo $row['dilatacion'] ?></option>
                    <option></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="presentacion">PRESENTACIÓN: </label><br>
                  <select class="form-control" name="altura_p" required>
                    <option value="<?php echo $row['presentacion'] ?>"><?php echo $row['presentacion'] ?></option>
                    <option></option>
                    <option value="I">I</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="pelvis">PELVIS</label><br>
                  <input type="text" name="pelvis" value="<?php echo $row['pelvis'] ?>" placeholder="Pelvis" id="pelvis" style="text-transform:uppercase;"  onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="util">ÚTIL: </label><br>
                  <input type="text" name="util" value="<?php echo $row['util'] ?>" placeholder="Útil" id="util" style="text-transform:uppercase;"  onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="no_util">NO ÚTIL: </label><br>
                  <input type="text" name="no_util" placeholder="No Útil" value="<?php echo $row['n_util'] ?>" id="no_util" style="text-transform:uppercase;"  onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control">
                </div>
              </div>
            </div>
<div class="row">
            <div class="col-md-12">
              <label for="impresion_diag">IMPRESIÓN DIAGNÓSTICA:</label></strong>
              <textarea name="impresion_diag" class="form-control" rows="5"><?php echo $row['imp_diag'] ?></textarea>
            </div>

            <div class="col-md-12">
              <label for="plan_t">PLAN DE TRATAMIENTO:</label></strong>
              <textarea name="plan_t" class="form-control" rows="5"><?php echo $row['p_trat'] ?></textarea>
            </div>
          </div>
              <?php 
$date=date_create($row['fecha']);
?>
      <div class="row">
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>FECHA REGISTRO : </label></strong><br>
                 <input type="date" name="fecha" value="<?php echo date_format($date,"Y-m-d") ?>" class="form-control">
             </div>
           </div>
           <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>HORA REGISTRO :</label></strong><br>
                 <input type="time" name="hora" value="<?php echo date_format($date,"H:i:s") ?>" class="form-control">
             </div>
           </div>
       <?php 
   }
$select="SELECT * FROM reg_usuarios where id_usua=$id_usua";
$resultado=$conexion->query($select);
while ($row_doc=$resultado->fetch_assoc()) {
    $doctor=$row_doc['nombre'].' '.$row_doc['papell'].' '.$row_doc['sapell'];
    $id_doc=$row_doc['id_usua'];
}

        ?>
          <div class="col-sm-4">
              <div class="form-group">
                 <strong> <label>MÉDICO QUE REGISTRÓ: </label></strong><br>
                 <select name="medico" class="form-control" >
                     <option value="<?php echo $id_doc ?>"><?php echo $doctor ?></option>
                     <option></option>
                     <option value=" ">SELECCIONAR MEDICO :</option>
                     <?php 
                      $select="SELECT * FROM reg_usuarios where id_rol=2 || id_rol=12";
                      $resultado=$conexion->query($select);
                      foreach ($resultado as $row ) {
                      ?>
                      <option value="<?php echo $row['id_usua'] ?>"><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']; ?></option>
                  <?php } ?>
                 </select>
             </div>
           </div>
       </div>
            <br>
            <center>
              <div class="col-sm-4">
                <input type="submit" name="btnpartograma" class="btn btn-block btn-success" value="Fírmar">
              </div>
            </center>
          </form>
        
        </div>
      </div>
      <?php
      if (isset($_POST['btnpartograma'])) {
        $gestaciones = mysqli_real_escape_string($conexion, (strip_tags($_POST["gestaciones"], ENT_QUOTES))); //Escanpando caracteres
        $partos = mysqli_real_escape_string($conexion, (strip_tags($_POST["partos"], ENT_QUOTES))); //Escanpando caracteres
        $cesareas = mysqli_real_escape_string($conexion, (strip_tags($_POST["cesareas"], ENT_QUOTES))); //Escanpando caracteres
        $abortos = mysqli_real_escape_string($conexion, (strip_tags($_POST["abortos"], ENT_QUOTES))); //Escanpando caracteres
        $fur = mysqli_real_escape_string($conexion, (strip_tags($_POST["fur"], ENT_QUOTES))); //Escanpando caracteres
        $no_hijos = mysqli_real_escape_string($conexion, (strip_tags($_POST["no_hijos"], ENT_QUOTES))); //Escanpando caracteres
        $malformaciones = mysqli_real_escape_string($conexion, (strip_tags($_POST["malformaciones"], ENT_QUOTES))); //Escanpando caracteres
        $f_ucesarea = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_ucesarea"], ENT_QUOTES))); //Escanpando caracteres
        $fpp = mysqli_real_escape_string($conexion, (strip_tags($_POST["fpp"], ENT_QUOTES))); //Escanpando caracteres
        $sem_gestacion = mysqli_real_escape_string($conexion, (strip_tags($_POST["sem_gestacion"], ENT_QUOTES))); //Escanpando caracteres
        $no_consultas = mysqli_real_escape_string($conexion, (strip_tags($_POST["no_consultas"], ENT_QUOTES))); //Escanpando caracteres
        $c_perinatal = mysqli_real_escape_string($conexion, (strip_tags($_POST["c_perinatal"], ENT_QUOTES))); //Escanpando caracteres
        $lab_prev = mysqli_real_escape_string($conexion, (strip_tags($_POST["lab_prev"], ENT_QUOTES))); //Escanpando caracteres
        $complicaciones_actual = mysqli_real_escape_string($conexion, (strip_tags($_POST["complicaciones_actual"], ENT_QUOTES))); //Escanpando caracteres
        $tratamiento = mysqli_real_escape_string($conexion, (strip_tags($_POST["tratamiento"], ENT_QUOTES))); //Escanpando caracteres
        $c_uterina = mysqli_real_escape_string($conexion, (strip_tags($_POST["c_uterina"], ENT_QUOTES))); //Escanpando caracteres
        $sangrado_tv = mysqli_real_escape_string($conexion, (strip_tags($_POST["sangrado_tv"], ENT_QUOTES))); //Escanpando caracteres
        $r_p_m = mysqli_real_escape_string($conexion, (strip_tags($_POST["r_p_m"], ENT_QUOTES))); //Escanpando caracteres
        $no_consultas_urg = mysqli_real_escape_string($conexion, (strip_tags($_POST["no_consultas_urg"], ENT_QUOTES))); //Escanpando caracteres
        $motilidad = mysqli_real_escape_string($conexion, (strip_tags($_POST["motilidad"], ENT_QUOTES))); //Escanpando caracteres
        $dism = mysqli_real_escape_string($conexion, (strip_tags($_POST["dism"], ENT_QUOTES))); //Escanpando caracteres
        $nl = mysqli_real_escape_string($conexion, (strip_tags($_POST["nl"], ENT_QUOTES))); //Escanpando caracteres
        $p_sistolica = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_sistolica"], ENT_QUOTES))); //Escanpando caracteres
        $p_diastolica = mysqli_real_escape_string($conexion, (strip_tags($_POST["p_diastolica"], ENT_QUOTES))); //Escanpando caracteres
        $temp = mysqli_real_escape_string($conexion, (strip_tags($_POST["temp"], ENT_QUOTES))); //Escanpando caracteres
        $f_card = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_card"], ENT_QUOTES))); //Escanpando caracteres
        $f_resp = mysqli_real_escape_string($conexion, (strip_tags($_POST["f_resp"], ENT_QUOTES))); //Escanpando caracteres
        $edema = mysqli_real_escape_string($conexion, (strip_tags($_POST["edema"], ENT_QUOTES))); //Escanpando caracteres
        $a_utero = mysqli_real_escape_string($conexion, (strip_tags($_POST["a_utero"], ENT_QUOTES))); //Escanpando caracteres
        $fcf = mysqli_real_escape_string($conexion, (strip_tags($_POST["fcf"], ENT_QUOTES))); //Escanpando caracteres
        $ritmo = mysqli_real_escape_string($conexion, (strip_tags($_POST["ritmo"], ENT_QUOTES))); //Escanpando caracteres
        $tono_u = mysqli_real_escape_string($conexion, (strip_tags($_POST["tono_u"], ENT_QUOTES))); //Escanpando caracteres
        $membranas = mysqli_real_escape_string($conexion, (strip_tags($_POST["membranas"], ENT_QUOTES))); //Escanpando caracteres
        $rotas = mysqli_real_escape_string($conexion, (strip_tags($_POST["rotas"], ENT_QUOTES))); //Escanpando caracteres
        $aspecto_la = mysqli_real_escape_string($conexion, (strip_tags($_POST["aspecto_la"], ENT_QUOTES))); //Escanpando caracteres
        $cervix = mysqli_real_escape_string($conexion, (strip_tags($_POST["cervix"], ENT_QUOTES))); //Escanpando caracteres
        $dilatacion_p = mysqli_real_escape_string($conexion, (strip_tags($_POST["dilatacion_p"], ENT_QUOTES))); //Escanpando caracteres
        $altura_p = mysqli_real_escape_string($conexion, (strip_tags($_POST["altura_p"], ENT_QUOTES))); //Escanpando caracteres
        $pelvis = mysqli_real_escape_string($conexion, (strip_tags($_POST["pelvis"], ENT_QUOTES))); //Escanpando caracteres
        $util = mysqli_real_escape_string($conexion, (strip_tags($_POST["util"], ENT_QUOTES))); //Escanpando caracteres
        $no_util = mysqli_real_escape_string($conexion, (strip_tags($_POST["no_util"], ENT_QUOTES))); //Escanpando caracteres
        $impresion_diag = mysqli_real_escape_string($conexion, (strip_tags($_POST["impresion_diag"], ENT_QUOTES))); //Escanpando caracteres
        $plan_t = mysqli_real_escape_string($conexion, (strip_tags($_POST["plan_t"], ENT_QUOTES))); //Escanpando caracteres
        $t_sang = mysqli_real_escape_string($conexion, (strip_tags($_POST["tip_san"], ENT_QUOTES))); //Escanpando caracteres
        $fecha= mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha"], ENT_QUOTES)));
        $hora= mysqli_real_escape_string($conexion,(strip_tags($_POST["hora"], ENT_QUOTES)));
        $medico= mysqli_real_escape_string($conexion,(strip_tags($_POST["medico"], ENT_QUOTES)));

        $merge = $fecha.' '.$hora;
        

        
          $unidad = mysqli_real_escape_string($conexion, (strip_tags($_POST["unidad"], ENT_QUOTES))); //Escanpando caracteres
       

        if ($sangrado_tv == 'NO') {
          $fecha_inicio = "";
          $hora_inicio = "";
        } else {
          $fecha_inicio = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_inicio"], ENT_QUOTES))); //Escanpando caracteres
          $hora_inicio = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_inicio"], ENT_QUOTES))); //Escanpando caracteres
        }

        if ($sangrado_tv == 'NO') {
          $fecha_rpm = "";
          $hora_rpm = "";
        } else {
          $fecha_rpm = mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_rpm"], ENT_QUOTES))); //Escanpando caracteres
          $hora_rpm = mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_rpm"], ENT_QUOTES))); //Escanpando caracteres
        }

date_default_timezone_set('America/Guatemala');
                $fecha_actual = date("Y-m-d H:i:s");

/*        $sql_partograma = 'INSERT INTO partograma(id_atencion,fecha,gestas,f_ucesarea,partos,cesareas,abortos,fur, no_hijos, malformaciones, fpp,sem_gestacion,no_consultas,c_perinatal,unidad,lab_prev_rec,comp_emb_act,tratamiento,c_uterina,sang_tv,inicio_fecha,inicio_hora,rpm,fecha_rpm,hora_rpm,no_consul_urg,mot_fetal,dism,nl,p_sistolica,p_diastolica, temp, fc, fr, edema, alt_utero, fcf, ritmo,t_uterino,memb_int, rotas,asp_la,cervix,dilatacion,presentacion,pelvis,util,n_util,imp_diag,p_trat, id_usua)VALUES(' . $id_atencion . ',"'.$fecha_actual.'",' . $gestaciones . ',"' . $f_ucesarea . '",' . $partos . ',' . $cesareas . ',' . $abortos . ',"' . $fur . '",' . $no_hijos . ',' . $malformaciones . ',"' . $fpp . '",' . $sem_gestacion . ',' . $no_consultas . ',"' . $c_perinatal . '","' . $unidad . '","' . $lab_prev . '","' . $complicaciones_actual . '","' . $tratamiento . '","' . $c_uterina . '","' . $sangrado_tv . '","' . $fecha_inicio . '","' . $hora_inicio . '","' . $r_p_m . '","' . $fecha_rpm . '","' . $hora_rpm . '",' . $no_consultas_urg . ',"' . $motilidad . '","' . $dism . '","' . $nl . '",' . $p_sistolica . ',' . $p_diastolica . ',' . $temp . ',' . $f_card . ',' . $f_resp . ',"' . $edema . '",' . $a_utero . ',"' . $fcf . '","' . $ritmo . '","' . $tono_u . '","' . $membranas . '","' . $rotas . '","' . $aspecto_la . '","' . $cervix . '","' . $dilatacion_p . '","' . $altura_p . '","' . $pelvis . '","' . $util . '","' . $no_util . '","' . $impresion_diag . '","' . $plan_t . '",' . $usuario2 . ');';

        //  echo $sql_partograma;
        $result_pac = $conexion->query($sql_partograma);*/

        $sql2 = "UPDATE partograma SET id_usua='$medico',fecha='$merge',gestas='$gestaciones', f_ucesarea='$f_ucesarea', partos ='$partos', cesareas='$cesareas', abortos='$abortos', fur='$fur', no_hijos='$no_hijos', malformaciones='$malformaciones', fpp='$fpp', sem_gestacion='$sem_gestacion', no_consultas='$no_consultas', c_perinatal='$c_perinatal', unidad='$unidad', lab_prev_rec='$lab_prev', comp_emb_act='$complicaciones_actual', tratamiento='$tratamiento', c_uterina='$c_uterina', sang_tv='$sangrado_tv', inicio_fecha='$fecha_inicio', inicio_hora='$hora_inicio', rpm='$r_p_m', fecha_rpm='$fecha_rpm', hora_rpm='$hora_rpm', no_consul_urg='$no_consultas_urg', mot_fetal='$motilidad', dism='$dism', nl='$nl', p_sistolica='$p_sistolica', p_diastolica='$p_diastolica', temp='$temp', fc='$f_card', fr='$f_resp', edema='$edema', alt_utero='$a_utero', fcf='$fcf', ritmo='$ritmo', t_uterino='$tono_u', memb_int='$membranas', rotas='$rotas', asp_la='$aspecto_la', cervix='$cervix', dilatacion='$dilatacion_p', presentacion='$altura_p', pelvis='$pelvis', util='$util', n_util='$no_util', imp_diag='$impresion_diag', p_trat='$plan_t'
         WHERE id_partograma= $id_partograma";
        $result = $conexion->query($sql2);

        if (isset($t_sang)) {
          $sql_sangre = 'UPDATE paciente set tip_san="' . $t_sang . '" WHERE Id_exp =' . $exp . ';';
          $result_sang = $conexion->query($sql_sangre);
        }

       echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> DATO EDITADO CORRECTAMENTE...</p>";
        echo '<script type="text/javascript">window.location ="../cartas_consentimientos/consent_medico.php"</script>';
      }

      ?>
    </section>
    </div>

    <footer class="main-footer">
      <?php
      include("../../template/footer.php");
      ?>
    </footer>



  <?php
  } else {

    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
    echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
    echo '<script>
                    $(document).ready(function() {
                        swal({
                            title: "SOLO SE PERMITE SELECCIONAR MUJERES", 
                            type: "error",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.location.href = "../../template/menu_medico.php";
                            }
                        });
                    });
                </script>';
  }
  ?>


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