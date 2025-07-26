<?php
session_start();
include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);

?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″ />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">



  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


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
  <script>
    // Write on keyup event of keyword input element
    $(document).ready(function() {
      $("#search_dep").keyup(function() {
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
  <title>SIGNOS VITALES</title>
  <style type="text/css">
    #contenido {
      display: none;
    }

    #contenido3 {
      display: none;
    }

    #contenido4 {
      display: none;
    }

    /* === ESTILOS CLÁSICOS MEJORADOS === */
    .vital-signs-section {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      margin: 20px 0;
      padding: 0;
    }

    .vital-header {
      background: #007bff;
      color: white;
      padding: 15px 20px;
      border-radius: 8px 8px 0 0;
      border-bottom: 1px solid #0056b3;
    }

    .vital-title {
      margin: 0;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .vital-content {
      padding: 20px;
      background: white;
    }

    .pressure-inputs-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .pressure-input {
      flex: 1;
    }

    .pressure-divider {
      font-size: 1.2rem;
      font-weight: bold;
      color: #dc3545;
      margin: 0 5px;
    }

    .label-with-icon {
      font-weight: 600;
      color: #495057;
      margin-bottom: 5px;
      display: flex;
      align-items: center;
      gap: 8px;
      min-height: 20px;
    }

    .label-with-icon i {
      width: 16px;
      text-align: center;
      flex-shrink: 0;
    }

    .buttons-section {
      background: #f8f9fa;
      padding: 20px;
      text-align: center;
      border-top: 1px solid #dee2e6;
      border-radius: 0 0 8px 8px;
    }

    .btn-improved {
      margin: 0 8px 10px 8px;
      padding: 12px 25px;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      border: 2px solid transparent;
    }

    .btn-improved:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      text-decoration: none;
    }

    .btn-improved.btn-success {
      background: #28a745;
      border-color: #28a745;
      color: white;
    }

    .btn-improved.btn-success:hover {
      background: #218838;
      border-color: #1e7e34;
      color: white;
    }

    .btn-improved.btn-warning {
      background: #ffc107;
      border-color: #ffc107;
      color: #212529;
    }

    .btn-improved.btn-warning:hover {
      background: #e0a800;
      border-color: #d39e00;
      color: #212529;
    }

    .btn-improved.btn-danger {
      background: #dc3545;
      border-color: #dc3545;
      color: white;
    }

    .btn-improved.btn-danger:hover {
      background: #c82333;
      border-color: #bd2130;
      color: white;
    }

    /* Estilos para paginación */
    .pagination-sm .page-link {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      border-radius: 0.25rem;
    }

    .pagination .page-item.active .page-link {
      background-color: #2b2d7f;
      border-color: #2b2d7f;
      color: white;
    }

    .pagination .page-link {
      color: #2b2d7f;
      border: 1px solid #dee2e6;
    }

    .pagination .page-link:hover {
      background-color: #e9ecef;
      border-color: #dee2e6;
      color: #2b2d7f;
    }

    .pagination .page-item.disabled .page-link {
      color: #6c757d;
      background-color: #fff;
      border-color: #dee2e6;
    }

    .text-muted {
      color: #6c757d !important;
    }

    /* Estilos para la barra de herramientas */
    .input-group .input-group-text {
      background-color: #f8f9fa;
      border-color: #ced4da;
      color: #495057;
    }

    .input-group .form-control {
      border-left: 0;
    }

    .input-group .form-control:focus {
      border-color: #2b2d7f;
      box-shadow: 0 0 0 0.2rem rgba(43, 45, 127, 0.25);
    }

    .text-primary {
      color: #2b2d7f !important;
    }

    /* Tabla mejorada */
    .table-bordered {
      border: 1px solid #dee2e6;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(43, 45, 127, 0.05);
    }

    .thead.bg-navy {
      background-color: #2b2d7f !important;
      color: white !important;
    }

    /* Estilos para botón de eliminar */
    .eliminar-signos {
      border: none;
      background: #dc3545;
      color: white;
      padding: 6px 10px;
      border-radius: 6px;
      transition: all 0.3s ease;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 38px;
      height: 32px;
    }

    .eliminar-signos:hover {
      background: #c82333;
      color: white;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .eliminar-signos:active {
      transform: translateY(0);
      box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
    }

    .eliminar-signos:disabled {
      background: #6c757d;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .eliminar-signos i {
      font-size: 14px;
    }

    /* Animación para filas que se van a eliminar */
    .fila-eliminando {
      background-color: #f8d7da !important;
      animation: fadeOutRow 0.4s ease-out forwards;
    }

    @keyframes fadeOutRow {
      0% {
        opacity: 1;
        transform: scale(1);
      }
      100% {
        opacity: 0;
        transform: scale(0.95);
      }
    }

    /* Estilos para alertas flotantes */
    .alert.position-fixed {
      max-width: 400px;
      word-wrap: break-word;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      border: none;
    }

    .alert.alert-success {
      background-color: #d4edda;
      color: #155724;
      border-left: 4px solid #28a745;
    }

    .alert.alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border-left: 4px solid #dc3545;
    }
  </style>

</head>

<body>
  <font size="2">
    <div class="container">
      <div class="row">
        <div class="col-12">

          <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
            <strong>
              <center><i class="fas fa-user-injured"></i> DATOS DEL PACIENTE</center>
            </strong>
          </div>
          <hr>
          <?php

          include "../../conexionbd.php";

          if (isset($_SESSION['pac'])) {
            $id_atencion = $_SESSION['pac'];

            $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

            $result_pac = $conexion->query($sql_pac);

            while ($row_pac = $result_pac->fetch_assoc()) {
              $pac_papell = $row_pac['papell'];
              $pac_sapell = $row_pac['sapell'];
              $pac_nom_pac = $row_pac['nom_pac'];
              $pac_dir = $row_pac['dir'];
              $pac_id_edo = $row_pac['id_edo'];
              $pac_id_mun = $row_pac['id_mun'];
              $pac_tel = $row_pac['tel'];
              $pac_fecnac = $row_pac['fecnac'];
              $pac_fecing = $row_pac['fecha'];
              $pac_tip_sang = $row_pac['tip_san'];
              $pac_sexo = $row_pac['sexo'];
              $area = $row_pac['area'];
              $alta_med = $row_pac['alta_med'];
              $id_exp = $row_pac['Id_exp'];
              $alergias = $row_pac['alergias'];
            }

            $sql_now = "SELECT DATE_ADD(NOW(), INTERVAL 12 HOUR) as dat_now FROM dat_ingreso WHERE id_atencion = $id_atencion";

            $result_now = $conexion->query($sql_now);

            while ($row_now = $result_now->fetch_assoc()) {
              $dat_now = $row_now['dat_now'];
            }

            $sql_est = "SELECT DATEDIFF( '$dat_now' , fecha) as estancia FROM dat_ingreso WHERE id_atencion = $id_atencion";

            $result_est = $conexion->query($sql_est);

            while ($row_est = $result_est->fetch_assoc()) {
              $estancia = $row_est['estancia'];
            }


          ?>
            <font size="2">

              <div class="row">
                <div class="col-sm-2">
                  Expediente: <strong><?php echo $id_exp ?> </strong>
                </div>
                <div class="col-sm-6">
                  Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
                </div>

                <?php $date = date_create($pac_fecing);
                ?>
                <div class="col-sm-4">
                  Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y H:i:s") ?></strong>
                </div>
              </div>
            </font>
            <font size="2">

              <div class="row">
                <div class="col-sm-4">
                  <?php $date1 = date_create($pac_fecnac);
                  ?>
                  <!-- INICIO DE FUNCION DE CALCULAR EDAD -->
                  <?php

                  $fecha_actual = date("Y-m-d");
                  $fecha_nac = $pac_fecnac;
                  $fecha_de_nacimiento = strval($fecha_nac);

                  // separamos en partes las fechas
                  $array_nacimiento = explode("-", $fecha_de_nacimiento);
                  $array_actual = explode("-", $fecha_actual);

                  $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
                  $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
                  $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días

                  //ajuste de posible negativo en $días
                  if ($dias < 0) {
                    --$meses;

                    //ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
                    switch ($array_actual[1]) {
                      case 1:
                        $dias_mes_anterior = 31;
                        break;
                      case 2:
                        $dias_mes_anterior = 31;
                        break;
                      case 3:

                        $dias_mes_anterior = 28;
                        break;

                      case 4:
                        $dias_mes_anterior = 31;
                        break;
                      case 5:
                        $dias_mes_anterior = 30;
                        break;
                      case 6:
                        $dias_mes_anterior = 31;
                        break;
                      case 7:
                        $dias_mes_anterior = 30;
                        break;
                      case 8:
                        $dias_mes_anterior = 31;
                        break;
                      case 9:
                        $dias_mes_anterior = 31;
                        break;
                      case 10:
                        $dias_mes_anterior = 30;
                        break;
                      case 11:
                        $dias_mes_anterior = 31;
                        break;
                      case 12:
                        $dias_mes_anterior = 30;
                        break;
                    }

                    $dias = $dias + $dias_mes_anterior;
                  }

                  //ajuste de posible negativo en $meses
                  if ($meses < 0) {
                    --$anos;
                    $meses = $meses + 12;
                  }

                  //echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

                  function bisiesto($anio_actual)
                  {
                    $bisiesto = false;
                    //probamos si el mes de febrero del año actual tiene 29 días
                    if (checkdate(2, 29, $anio_actual)) {
                      $bisiesto = true;
                    }
                    return $bisiesto;
                  }

                  ?>
                  <!-- TERMINO DE FUNCION DE CALCULAR EDAD -->
                  Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
                </div>
                <div class="col-sm-4">
                  Edad: <strong><?php if ($anos > "0") {
                                  echo $anos . " años";
                                } elseif ($anos <= "0" && $meses > "0") {
                                  echo $meses . " meses";
                                } elseif ($anos <= "0" && $meses <= "0" && $dias > "0") {
                                  echo $dias . " dias";
                                }
                                ?></strong>
                </div>


                <div class="col-sm-2">
                  Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
                                      $result_hab = $conexion->query($sql_hab);
                                      while ($row_hab = $result_hab->fetch_assoc()) {
                                        echo $row_hab['num_cama'];
                                      } ?></strong>
                </div>

              </div>

            </font>
            <font size="2">
              <div class="row">
                <?php
                $d = "";
                $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
                $result_motd = $conexion->query($sql_motd);
                while ($row_motd = $result_motd->fetch_assoc()) {
                  $d = $row_motd['diagprob_i'];
                } ?>
                <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
                $result_mot = $conexion->query($sql_mot);
                while ($row_mot = $result_mot->fetch_assoc()) {
                  $m = $row_mot['motivo_atn'];
                } ?>

                <?php if ($d != null) {
                  echo '<div class="col-sm-8"> Diagnóstico: <strong>' . $d . '</strong></div>';
                } else {
                  echo '<div class="col-sm-8"> Motivo de atención: <strong>' . $m . '</strong></div>';
                } ?>
                <div class="col-sm">
                  Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
                </div>
              </div>

            </font>
            <font size="2">
              <div class="row">
                <div class="col-sm-4">
                  Alergias: <strong><?php echo $alergias ?></strong>
                </div>
                <div class="col-sm-4">
                  Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
                                            $result_edo = $conexion->query($sql_edo);
                                            while ($row_edo = $result_edo->fetch_assoc()) {
                                              echo $row_edo['edo_salud'];
                                            } ?></strong>
                </div>
                <div class="col-sm-3">
                  Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
                </div>
              </div>
            </font>
            <?php $sql_edo = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
            $result_edo = $conexion->query($sql_edo);
            while ($row_edo = $result_edo->fetch_assoc()) {
              $peso = $row_edo['peso'];
              $talla = $row_edo['talla'];
            }
            if (!isset($peso)) {
              $peso = 0;
              $talla = 0;
            } ?>
            <font size="2">
              <div class="row">
                <div class="col-sm-4">
                  Peso: <strong><?php echo $peso ?></strong>
                </div>
                <div class="col-sm-3">
                  Talla: <strong><?php echo $talla ?></strong>
                </div>
              </div>
            </font>
            <hr>
        </div>
      <?php
          } else {
            echo '<script type="text/javascript"> window.location.href="../lista_pacientes/lista_pacientes.php";</script>';
          }
      ?>

      <body>
        <div class="container"> <!--INICIO DE CONSULTA DE URGENCIAS-->

          <?php
          // Incluir verificación de cirugía
          include "verificar_cirugia.php";
          
          // Verificar si la cirugía ha terminado
          $cirugiaTerminada = cirugiaTerminada($conexion, $id_atencion);
          
          if ($cirugiaTerminada) {
              echo "<div class='alert alert-warning text-center' style='background: linear-gradient(135deg, #ffeaa7, #fdcb6e); border: none; border-radius: 15px; padding: 25px; margin: 20px 0;'>";
              echo "<h4 style='color: #2d3436; margin-bottom: 15px;'><i class='fas fa-lock'></i> 🚫 Cirugía Terminada</h4>";
              echo "<p style='color: #2d3436; font-size: 16px; margin-bottom: 20px;'>Los signos vitales han sido <strong>bloqueados</strong> porque la cirugía ha sido marcada como terminada.</p>";
              echo "<p style='color: #636e72; font-size: 14px;'>Solo se pueden visualizar los registros existentes. No se pueden agregar nuevos signos vitales.</p>";
              echo "</div>";
          }
          ?>

          <!-- Botón Ver Gráfica siempre visible -->
          <div class="text-center mb-3">
            <a href="ver_grafica.php" class="btn btn-warning btn-improved">
              <i class="fas fa-chart-line"></i>
              <span>Ver Gráfica</span>
            </a>
          </div>

          <form action="insertar_trans_grafico.php" method="POST" <?php if ($cirugiaTerminada) echo 'style="display: none;"'; ?>>
            <div class="row">
              <div class="col-sm-12">
                <?php
                $id_trans_graf = 0;
                $resultado3 = $conexion->query("SELECT * from dat_trans_grafico WHERE id_atencion=$id_atencion ORDER BY id_trans_graf Desc") or die($conexion->error);

                while ($f3 = mysqli_fetch_array($resultado3)) {
                  $id_trans_graf = $f3['id_trans_graf'];
                }
                ?>
              </div>
            </div>

            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 24px;">
              <strong>
                <center><i class="fas fa-heartbeat"></i> SIGNOS VITALES</center>
              </strong>
            </div>

            <div class="vital-content">
              <div class="row">
                <!-- Presión Arterial -->
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                  <label class="label-with-icon">
                    <i class="fas fa-tachometer-alt text-primary"></i>
                    Presión Arterial
                  </label>
                  <div class="pressure-inputs-wrapper">
                    <input type="text" class="form-control pressure-input"
                      id="sist" name="sistg" required placeholder="SISTÓLICA">
                    <span class="pressure-divider">/</span>
                    <input type="text" class="form-control pressure-input"
                      id="diast" name="diastg" required placeholder="DIASTÓLICA">
                  </div>
                </div>

                <!-- Frecuencia Cardíaca -->
                <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                  <label class="label-with-icon">
                    <i class="fas fa-heart text-danger"></i>
                    Frecuencia Cardíaca
                  </label>
                  <input type="text" class="form-control"
                    name="fcardg" required placeholder="PULSO">
                </div>

                <!-- Frecuencia Respiratoria -->
                <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                  <label class="label-with-icon">
                    <i class="fas fa-lungs text-info"></i>
                    Frecuencia Respiratoria
                  </label>
                  <input type="text" class="form-control"
                    name="frespg" required placeholder="RESP">
                </div>

                <!-- Saturación de Oxígeno -->
                <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                  <label class="label-with-icon">
                    <i class="fas fa-percentage text-success"></i>
                    Saturación O₂
                  </label>
                  <input type="text" class="form-control"
                    name="satg" required placeholder="SAT O₂">
                </div>

                <!-- Temperatura -->
                <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                  <label class="label-with-icon">
                    <i class="fas fa-thermometer-half text-warning"></i>
                    Temperatura
                  </label>
                  <input type="text" class="form-control"
                    name="tempg" required placeholder="°C">
                </div>
              </div>
            </div>

            <!-- Botones de Acción Mejorados -->
            <div class="buttons-section">
              <!-- Campo oculto para id_tratamiento -->
              <input type="hidden" name="id_tratamiento" value="1">
              <!-- Campo oculto para hora_signos con valor automático -->
              <input type="hidden" name="hora_signos" id="hora_actual" value="<?php echo date('H:i'); ?>">
              
              <button type="submit" class="btn btn-success btn-improved" id="btn-agregar-signos">
                <i class="fas fa-plus-circle"></i>
                <span>Agregar</span>
              </button>
            </div>
        </div>

      </div>
      </center>
      <hr>
      </form>
    </div> <!--TERMINO DE NOTA MEDICA div container-->
    <div class="container">
      <div class="row">





        <!--<div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="Buscar...">
            </div>-->
        <!--<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="container" >
  <div class="row">
  
 <div class="col-sm-12">
     <div class="thead" style="background-color: #0c675e; color: white; font-size: 20px;">
         <tr><strong><center>GRÁFICO / SIGNOS VITALES</center></strong>
</div>
      <p>
    
    <canvas id="grafica"></canvas>
    <script src="script.js"></script>
    </div>
    
   
  
  </div>
</div>-->
        <?php

        include "../../conexionbd.php";
        $id_atencion = $_SESSION['pac'];
        
        // Configuración de paginación
        $registros_por_pagina = 5;
        $pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
        
        // Contar total de registros
        $query_total = "SELECT COUNT(*) as total FROM dat_trans_grafico WHERE id_atencion=$id_atencion";
        $resultado_total = $conexion->query($query_total);
        $row_total = $resultado_total->fetch_assoc();
        $total_registros = $row_total['total'];
        $total_paginas = ceil($total_registros / $registros_por_pagina);
        
        // Ajustar página actual si excede el total de páginas
        if ($pagina_actual > $total_paginas && $total_paginas > 0) {
            $pagina_actual = $total_paginas;
        }
        
        $offset = ($pagina_actual - 1) * $registros_por_pagina;
        
        // Consulta con LIMIT para paginación
        $resultado = $conexion->query("SELECT * from dat_trans_grafico WHERE id_atencion=$id_atencion ORDER BY id_trans_graf ASC LIMIT $offset, $registros_por_pagina") or die($conexion->error);
        $usuario = $_SESSION['login'];
        ?>
        
        <!-- Barra de herramientas -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="mb-0">
              <i class="fas fa-chart-line text-primary"></i> Registro Gráfico Trans-Anestésico
            </h5>
            <small class="text-muted">Total de registros: <?php echo $total_registros; ?></small>
          </div>
          <div class="input-group" style="width: 300px;">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control" id="search" placeholder="Buscar en esta página...">
          </div>
        </div>
        
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="mytable">
            <thead class="thead bg-navy">
              <tr>
                <th scope="col">No.</th>
                <th scope="col">Fecha de nota</th>
                <th scope="col">Hora</th>
                <th scope="col">Presión arterial</th>
                <th scope="col">Frecuencia cardiaca</th>
                <th scope="col">Frecuencia respiratoria</th>
                <th scope="col">Temperatura</th>
                <th scope="col">Sat. Oxígeno</th>
                <th scope="col">Acciones</th>

              </tr>
            </thead>
            <tbody>
              <?php
              $no = $offset + 1; // Comenzar numeración desde el offset correspondiente
              $hay_registros = false;
              while ($f = mysqli_fetch_array($resultado)) {
                $hay_registros = true;
              ?>

                <tr>
                  <td><strong><?php echo $no ?></strong></td>
                  <td><strong><?php $date = date_create($f['fecha_g']);
                              echo date_format($date, "d/m/Y H:i:s"); ?></strong></td>
                  <td><strong><?php echo $f['cuenta']; ?></strong></td>
                  <td><strong><?php echo $f['sistg']; ?>/<?php echo $f['diastg']; ?></strong></td>
                  <td><strong><?php echo $f['fcardg']; ?></strong></td>
                  <td><strong><?php echo $f['frespg']; ?></strong></td>
                  <td><strong><?php echo $f['tempg']; ?> °C</strong></td>
                  <td><strong><?php echo $f['satg']; ?> %</strong></td>
                  <td>
                    <button type="button" class="btn btn-danger btn-sm eliminar-signos" 
                            data-id="<?php echo $f['id_trans_graf']; ?>" 
                            data-fecha="<?php echo date_format($date, "d/m/Y H:i:s"); ?>"
                            title="Eliminar registro">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>

                  <?php $no++; ?>
                </tr>
              <?php
              }
              
              // Si no hay registros en esta página, mostrar mensaje
              if (!$hay_registros && $total_registros == 0) {
              ?>
                <tr>
                  <td colspan="9" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                    No hay registros gráficos disponibles para este paciente.
                  </td>
                </tr>
              <?php
              }
              ?>
            </tbody>

          </table>
        </div>
        
        <!-- Controles de paginación -->
        <?php if ($total_paginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div>
            <small class="text-muted">
              Mostrando <?php echo $offset + 1; ?> - <?php echo min($offset + $registros_por_pagina, $total_registros); ?> de <?php echo $total_registros; ?> registros
            </small>
          </div>
          <nav aria-label="Paginación de registros">
            <ul class="pagination pagination-sm mb-0">
              <!-- Botón Anterior -->
              <?php if ($pagina_actual > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <span class="page-link">&laquo;</span>
                </li>
              <?php endif; ?>
              
              <!-- Números de página -->
              <?php
              $inicio = max(1, $pagina_actual - 2);
              $fin = min($total_paginas, $pagina_actual + 2);
              
              for ($i = $inicio; $i <= $fin; $i++):
              ?>
                <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>
              
              <!-- Botón Siguiente -->
              <?php if ($pagina_actual < $total_paginas): ?>
                <li class="page-item">
                  <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <span class="page-link">&raquo;</span>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
        <?php endif; ?>

      </div>
    </div>

    </div>

    <footer class="main-footer">
      <?php
      include("../../template/footer.php");
      ?>
    </footer>

    <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- FastClick -->
    <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>





    <script>
      const $grafica = document.querySelector("#grafica");
      // Las etiquetas son las que van en el eje X. 
      const etiquetas = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48"]
      // Podemos tener varios conjuntos de datos
      const presion = {
        label: "PRESIÓN SISTOLICA",
        data: [<?php
                $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=1") or die($conexion->error);
                while ($resp_r = mysqli_fetch_array($resp)) {

                  echo $resp_r['sistg'];
                } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=2") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=3") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=4") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=5") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=6") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=7") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=8") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=9") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=10") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=11") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=12") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=13") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=14") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=15") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=16") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=17") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=18") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=19") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=20") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=21") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=22") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=23") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=24") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=25") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=26") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=27") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=28") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=29") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=30") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=31") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=32") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=33") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=34") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=35") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=36") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=37") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=38") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=39") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=40") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=41") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=42") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=43") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=44") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=45") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=46") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=47") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>, <?php
        $resp = $conexion->query("select sistg from dat_trans_grafico where id_atencion=$id_atencion and hora=48") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['sistg'];
        } ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de fondo
        borderColor: 'rgba(54, 162, 235, 1)', // Color del borde
        borderWidth: 1, // Ancho del borde
      };
      const frec = {
        label: "PRESIÓN DIASTOLICA",
        data: [<?php
                $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=1") or die($conexion->error);
                while ($resp_r = mysqli_fetch_array($resp)) {

                  echo $resp_r['diastg'];
                } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=2") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=3") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=4") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=5") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=6") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=7") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=8") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=9") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=10") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=11") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=12") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=13") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=14") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=15") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=16") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=17") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=18") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=19") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=20") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=21") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=22") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=23") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=24") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=25") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=26") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=27") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=28") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=29") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=30") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=31") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=32") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=33") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=34") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=35") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=36") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=37") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=38") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=39") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=40") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=41") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=42") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=43") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=44") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=45") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=46") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=45") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=46") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=47") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>, <?php
        $resp = $conexion->query("select diastg from dat_trans_grafico where id_atencion=$id_atencion and hora=48") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['diastg'];
        } ?>], 
        backgroundColor: 'rgba(255, 159, 64, 0.2)', 
        borderColor: 'rgba(255, 159, 64, 1)',
        borderWidth: 1,
      };
      const fresp = {
        label: "FRECUENCIA CARDIACA",
        data: [<?php
                $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=1") or die($conexion->error);
                while ($resp_r = mysqli_fetch_array($resp)) {

                  echo $resp_r['fcardg'];
                } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=2") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=3") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=6") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=7") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=8") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=9") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=10") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=11") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=12") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=13") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=14") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=15") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=16") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=17") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=18") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=19") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=20") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=21") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=22") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=23") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=24") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=25") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=26") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=27") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=28") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=29") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=30") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=31") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=32") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=33") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=34") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=35") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=36") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=37") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=38") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=39") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=40") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=41") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=42") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=43") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=44") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=45") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=46") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=47") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>, <?php
        $resp = $conexion->query("select fcardg from dat_trans_grafico where id_atencion=$id_atencion and hora=48") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['fcardg'];
        } ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
        backgroundColor: 'rgba(25, 159, 64, 0.2)', // Color de fondo
        borderColor: 'rgba(25, 159, 64, 1)', // Color del borde
        borderWidth: 1, // Ancho del borde
      };

      const temp = {
        label: "FRECUENCIA RESPIRATORIA",
        data: [<?php
                $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=1") or die($conexion->error);
                while ($resp_r = mysqli_fetch_array($resp)) {

                  echo $resp_r['frespg'];
                } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=2") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=3") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=4") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=5") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=6") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=7") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=8") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=9") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=10") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=11") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=12") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=13") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=14") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=15") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=16") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=17") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=18") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=19") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=20") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=21") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=22") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=23") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=24") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=25") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=26") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=27") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=28") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=29") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=30") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=31") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=32") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=33") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=34") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=35") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=36") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=37") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=38") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=39") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=40") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=41") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=42") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=43") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=44") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=45") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=46") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=47") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>, <?php
        $resp = $conexion->query("select frespg from dat_trans_grafico where id_atencion=$id_atencion and hora=48") or die($conexion->error);
        while ($resp_r = mysqli_fetch_array($resp)) {

          echo $resp_r['frespg'];
        } ?>], // La data es un arreglo que debe tener la misma cantidad de valores que la cantidad de etiquetas
        backgroundColor: 'rgba(155, 125, 224, 0.2)', // Color de fondo
        borderColor: 'rgba(155, 125, 224, 1)', // Color del borde
        borderWidth: 1, // Ancho del borde
      };

      new Chart($grafica, {
        type: 'line', // Tipo de gráfica
        data: {
          labels: etiquetas,
          datasets: [
            presion,
            frec,
            fresp,
            temp,
            sat,
            niv
            // Aquí más datos...
          ]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }],
          },
        }
      });
    </script>

    <script type="text/javascript">
      console.log('=== INICIANDO SCRIPT ELIMINAR ===');
      console.log('jQuery version:', typeof $ !== 'undefined' ? $.fn.jquery : 'No jQuery detected');
      
      // TEST INMEDIATO AL CARGAR
      setTimeout(function() {
        console.log('=== TEST DESPUÉS DE 2 SEGUNDOS ===');
        console.log('Botones .eliminar-signos encontrados:', $('.eliminar-signos').length);
        console.log('¿$ existe?', typeof $ !== 'undefined');
        console.log('¿ajax existe?', typeof $.ajax !== 'undefined');
        
        if ($('.eliminar-signos').length > 0) {
          console.log('✅ Botones detectados, agregando evento de test...');
          $('.eliminar-signos').first().off('click').on('click', function() {
            console.log('🎯 ¡TEST EXITOSO! El botón responde');
            alert('¡El botón funciona!');
          });
        }
      }, 2000);
      
      // Función para inicializar los botones
      function inicializarBotonesEliminar() {
        console.log('Inicializando botones eliminar...');
        
        // Verificar que existan botones
        const botones = $('.eliminar-signos');
        console.log('Botones encontrados:', botones.length);
        
        if (botones.length === 0) {
          console.warn('No se encontraron botones con clase .eliminar-signos');
          // Intentar de nuevo en 1 segundo
          setTimeout(inicializarBotonesEliminar, 1000);
          return;
        }
        
        // Mostrar información de cada botón
        botones.each(function(index) {
          const id = $(this).data('id');
          const fecha = $(this).data('fecha');
          console.log(`Botón ${index}: ID=${id}, Fecha=${fecha}`);
        });
        
        // Remover eventos anteriores para evitar duplicados
        $(document).off('click', '.eliminar-signos');
        
        // Agregar evento con delegación
        $(document).on('click', '.eliminar-signos', function(e) {
          e.preventDefault();
          e.stopPropagation();
          
          console.log('=== BOTÓN ELIMINAR CLICKEADO ===');
          
          const idRegistro = $(this).data('id');
          const fecha = $(this).data('fecha');
          
          console.log('ID del registro:', idRegistro);
          console.log('Fecha:', fecha);
          
          if (!idRegistro) {
            alert('Error: No se pudo obtener el ID del registro');
            return;
          }
          
          if (confirm(`¿Está seguro de eliminar el registro del ${fecha}?\n\nEsta acción no se puede deshacer.`)) {
            console.log('Usuario confirmó eliminación');
            eliminarRegistro(idRegistro, fecha, $(this));
          } else {
            console.log('Usuario canceló eliminación');
          }
        });
        
        console.log('Eventos de eliminación configurados correctamente');
      }
      
      // Función para eliminar registro
      function eliminarRegistro(idRegistro, fecha, botonElement) {
        console.log('Iniciando eliminación del registro:', idRegistro);
        
        // Deshabilitar botón
        botonElement.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
          url: 'eliminar_signos_vitales.php',
          method: 'POST',
          data: { 
            id_registro: idRegistro,
            action: 'eliminar',
            ajax: true
          },
          dataType: 'json',
          timeout: 10000, // 10 segundos de timeout
          success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            if (response && response.success) {
              alert('✅ Registro eliminado correctamente');
              console.log('Recargando página...');
              window.location.reload();
            } else {
              const mensaje = response ? response.message : 'Error desconocido';
              alert('❌ Error al eliminar: ' + mensaje);
              console.error('Error del servidor:', mensaje);
              
              // Restaurar botón
              botonElement.prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
          },
          error: function(xhr, status, error) {
            console.error('=== ERROR AJAX ===');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response text:', xhr.responseText);
            console.error('Status code:', xhr.status);
            
            let mensajeError = 'Error de conexión';
            if (xhr.status === 404) {
              mensajeError = 'Archivo eliminar_signos_vitales.php no encontrado';
            } else if (xhr.status === 500) {
              mensajeError = 'Error interno del servidor';
            } else if (status === 'timeout') {
              mensajeError = 'Tiempo de espera agotado';
            }
            
            alert('❌ ' + mensajeError + ': ' + error);
            
            // Restaurar botón
            botonElement.prop('disabled', false).html('<i class="fas fa-trash"></i>');
          }
        });
      }
      
      // Ejecutar cuando el documento esté listo
      $(document).ready(function() {
        console.log('Document ready ejecutado');
        inicializarBotonesEliminar();
        
        $('#mibuscador').select2();
        
        // Establecer hora actual automáticamente
        function setHoraActual() {
          const now = new Date();
          const horas = String(now.getHours()).padStart(2, '0');
          const minutos = String(now.getMinutes()).padStart(2, '0');
          const horaActual = horas + ':' + minutos;
          
          // Actualizar el campo de hora
          $('input[name="hora_signos"]').val(horaActual);
          $('#hora_actual').val(horaActual);
        }
        
        // Establecer hora al cargar la página
        setHoraActual();
        
        // Actualizar hora cada minuto
        setInterval(setHoraActual, 60000);
        
        // Validación del formulario
        $('form').on('submit', function(e) {
          // Asegurar que la hora esté actualizada
          setHoraActual();
          
          const sistg = $('input[name="sistg"]').val();
          const diastg = $('input[name="diastg"]').val();
          const fcardg = $('input[name="fcardg"]').val();
          const frespg = $('input[name="frespg"]').val();
          const satg = $('input[name="satg"]').val();
          const tempg = $('input[name="tempg"]').val();
          const hora = $('input[name="hora_signos"]').val();
          
          if (!sistg || !diastg || !fcardg || !frespg || !satg || !tempg || !hora) {
            alert('⚠️ Por favor complete todos los campos de signos vitales antes de continuar.');
            e.preventDefault();
            return false;
          }
          
          const submitBtn = $('button[type="submit"]');
          submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
          
          return true;
        });
      });
      
      $(window).on('load', function() {
        console.log('Window load ejecutado');
        setTimeout(function() {
          inicializarBotonesEliminar();
        }, 1000);
      });

    </script>

</body>

</html>