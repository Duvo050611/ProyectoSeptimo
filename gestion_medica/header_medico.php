<?php
include "../../conexionbd.php";
    $lifetime=100000;
    session_set_cookie_params($lifetime);
    session_start();
//


if (!isset($_SESSION['login'])) {
  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
  //  header('Location: ../index.php');
}else{
      $lifetime=100000;
  session_set_cookie_params($lifetime);
}
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);

if (!($usuario['id_rol'] == 2 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12)) {
  session_unset();
  session_destroy();
  // echo "<script>window.Location='../../index.php';</script>";
  header('Location: ../../index.php');
}


?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
  <title>Médica del Ángel Custodio</title>
  <link rel="icon" href="../imagenes/SIF.PNG">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../../template/dist/js/pages/dashboard2.js" type="text/javascript"></script>

  <!-- AdminLTE for demo purposes -->
  <script src="../../template/dist/js/demo.js" type="text/javascript"></script>

  <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <!-- Font Awesome Icons -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Ionicons -->
  <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <!-- Morris chart -->
  <link href="../../template/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
  <!-- jvectormap -->
  <link href="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <!-- Daterange picker -->
  <link href="../../template/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="../../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

  <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
  <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

  <!-- Ionicons -->
  <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <!-- Morris chart -->
  <link href="../../template/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
  <!-- jvectormap -->
  <link href="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <!-- Daterange picker -->
  <link href="../../template/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="../../template/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
  <link href="../../template/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
  <!-- jQuery 2.1.3 -->
  <script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>


  <!-- FastClick -->
  <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
  <!-- Sparkline -->
  <script src="../../template/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
  <!-- jvectormap -->
  <script src="../../template/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
  <script src="../../template/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
  <!-- daterangepicker -->
  <script src="../../template/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
  <!-- datepicker -->
  <script src="../../template/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
  <!-- iCheck -->
  <script src="../../template/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
  <!-- SlimScroll 1.3.0 -->
  <script src="../../template/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <!-- ChartJS 1.0.1 -->
  <l src="../../template/plugins/chartjs/Chart.min.js" type="text/javascript"></l>


  <style>
    .dropdwn {
      float: left;
      overflow: hidden;
    }

    .dropdwn .dropbtn {
      cursor: pointer;
      font-size: 16px;
      border: none;
      outline: none;
      color: white;
      padding: 14px 16px;
      background-color: inherit;
      font-family: inherit;
      margin: 0;
    }

    .navbar a:hover,
    .dropdwn:hover .dropbtn,
    .dropbtn:focus {
      background-color: #367fa9;
    }

    .dropdwn-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdwn-content a {
      float: none;
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }

    .dropdwn-content a:hover {
      background-color: #ddd;
    }

    .show {
      display: block;
    }

    * {
      box-sizing: border-box;
    }

    .todo-container {
      max-width: 15000px;
      height: auto;
      display: flex;
      overflow-y: scroll;
      column-gap: 0.5em;
      column-rule: 1px solid white;
      column-width: 140px;
      column-count: 7;
    }

    .status {
      width: 25%;
      background-color: #ecf0f5;
      position: relative;
      padding: 60px 1rem 0.5rem;
      height: 100%;

    }

    .status h4 {
      position: absolute;
      top: 0;
      left: 0;
      background-color: #0b3e6f;
      color: white;
      margin: 0;
      width: 100%;

      padding: 0.5rem 1rem;
    }
  </style>
</head>

<body class=" hold-transition skin-blue sidebar-mini">

  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <!-- <img src="dist/img/logo.jpg" alt="logo">-->

      <?php
      if ($usuario['id_rol'] == 2) {
      ?>

        <a href="../../template/menu_medico.php" class="logo">
         
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b></span>
        </a>
      <?php
      } else if ($usuario['id_rol'] == 5) {

      ?>
        <a href="../../template/menu_gerencia.php" class="logo">
         
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b></span>
        </a>
        <?php }elseif($usuario['id_rol'] == 12) { ?>
         <a href="../../template/menu_residente.php" class="logo">
         
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="../../imagenes/SI.PNG" height="30" width="120"></b></span>
        </a>

      <?php
      } else
        //session_unset();
        session_destroy();
      echo "<script>window.Location='../index.php';</script>";
      ?>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>


        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image" />
                <span class="hidden-xs">  <?php echo $usuario['papell']; ?> </span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
                  <p>
                   <?php echo $usuario['papell']; ?> 
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                   <a href="../editar_perfil/editar_perfil.php?id_usua=<?php echo $usuario['id_usua'];?>" class="btn btn-default btn-flat">MIS DATOS </a>
                  </div>
                  <div class="pull-right">
                    <a href="../../cerrar_sesion.php" class="btn btn-default btn-flat">CERRAR SESIÓN</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="../../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image" />
          </div>
          <div class="pull-left info">
            <p> <?php echo $usuario['papell']; ?></p>

            <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
          </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
<li class="treeview">
  <a href="#">
    <i class="fa fa-stethoscope" aria-hidden="true"></i>
    <span>Exploraciones</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li>
      <a href="../gestion_medica/exploraciones/listar_exploraciones.php">
        <i class="fa fa-eye" aria-hidden="true"></i> Ver exploraciones
      </a>
    </li>
    <li>
      <a href="exploraciones/formulario_exploracion.php">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar exploración
      </a>
    </li>
  </ul>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-eye" aria-hidden="true"></i>
    <span>Segmento Anterior</span>
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu">
    <li>
      <a href="../gestion_medica/exploraciones/listar_segmento.php">
        <i class="fa fa-list" aria-hidden="true"></i> Ver exploraciones
      </a>
    </li>
    <li>
      <a href="../gestion_medica/exploraciones/formulario_segmento.php">
        <i class="fa fa-plus-circle" aria-hidden="true"></i> Agregar exploración
      </a>
    </li>
  </ul>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-eye" aria-hidden="true"></i> <span>Segmento Posterior</span>
    <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li><a href="../gestion_medica/exploraciones/formulario_segmento_posterior.php"><i class="fa fa-plus-circle"></i> Registrar Exploración</a></li>
    <li><a href="../gestion_medica/exploraciones/listar_segmento_posterior.php"><i class="fa fa-list"></i> Ver Exploraciones</a></li>
  </ul>
</li>

          
          <li class="treeview">                                  
              <?php
              if (isset($_SESSION['hospital'])) {
              ?>
                <li><a href="../cartas_consentimientos/consent_lista.php"><i class="fa fa-print" ></i> <font size ="2">IMPRIMIR DOCUMENTOS</font></a></li>
              <?php
              } else {
              ?>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-print" ></i> <font size ="2">IMPRIMIR DOCUMENTOS</font></a>
                </li>
              <?php
              }
              ?> 
          </li>
          
         <!-- <li class="treeview">
             <li><a href="../../gestion_medica/ambulatorio/receta_ambulatoria.php"><i class="fa fa-user-md"></i>
              <font size ="2"> CONSULTA</font></a></li>            
          </li>   -->

         

         
          <?php
          if (isset($_SESSION['hospital'])) {
          ?>
            <li class="treeview">
                <a href="../historia_clinica/h_clinica.php">
                   <i class="fa fa-folder" aria-hidden="true"></i> <span><font size ="2">HISTORIA CLÍNICA</font></span>
                </a>
            </li>
            
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-stethoscope"></i> <font size ="2"><span>NOTAS MÉDICAS</span></font>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">               
                
                <li><a href="../hospitalizacion/nota_ingreso.php"> <i class="fa fa-bed" aria-hidden="true"></i>
                    <font size ="2" color="black" > NOTA DE INGRESO </font></a></li>
                
                <li><a href="../hospitalizacion/nota_de_evolucion.php"><i class="fa fa-stethoscope" aria-hidden="true"></i>
                    <font size ="2" color="black" > NOTA DE EVOLUCIÓN </font></a></li>
                
                <li><a href="../hospitalizacion/nota_interconsulta.php"><i class="fa fa-hospital-o" aria-hidden="true"></i>
                    <font size ="2" color="black" > NOTA DE INTERCONSULTA</font></a>
                
                <li><a href="../hospitalizacion/nota_translado.php"><i class="fa fa-ambulance" aria-hidden="true"></i>
                    <font size ="2" color="black" > NOTA REFERENCIA/TRASLADO </font></a></li>
                
                <li><a href="../hospitalizacion/nota_neonatologica.php"><i class="fa fa-child"></i>
                    <font size ="2" color="black" > NOTA NEONATOLÓGICA </font></a></li>
                
                <li><a href="../hospitalizacion/partograma.php"><i class="fa fa-female"></i>
                    <font size ="2" color="black" > PARTOGRAMA</font></a></li>
                
              <!-- <li><a href="../hospitalizacion/nota_posparto.php"><i class="fa fa-female"></i> 
                    <font size ="2" color="black" > NOTA POST-PARTO </font></a></li>-->
                
                <!--<li><a href="../hospitalizacion/vista_de_transfuciones.php"><i class="fa fa-circle"></i>
                    <font size ="2" color="black" > NOTA DE TRANSFUSIÓN</font></a></li>-->
                <li><a href="../hospitalizacion/nota_egreso.php">
                    <i class="fa fa-street-view" aria-hidden="true"></i>
                    <font size ="2" color="black" > NOTA DE EGRESO </font></a></li>
                     
                <li><a href="../hospitalizacion/nota_defuncion.php"><i class="fa fa-plus-square"></i>
                    <font size ="2" color="black" > NOTA DE DEFUNCIÓN </font></a></li>
                <li><a href="../hospitalizacion/resumen_clinico.php"><i class="fa fa-files-o"></i>
                    <font size ="2" color="black" > RESUMEN CLÍNICO </font></a></li>
                
                <!-- NOTAS QUIRÚRGICAS-->
                <li><center><strong><font size ="2"> QUIRÚRGICAS</font></strong></center></li> 
                 <!--<li><a href="../quirurgico/hoja_progquir.php">
                   
                   <i class="fa fa-bed" aria-hidden="true"></i> 
                   <font size ="2" color="black" >  HOJA DE PROGRAMACIÓN<br> QUIRÚRGICA  </font></a></li>-->
                
               
                
                <li><a href="../quirurgico/nota_preoperatoria.php">
                   <i class="fa fa-bed" aria-hidden="true"></i> 
                   <font size ="2" color="black" > PROGRAMACIÓN QUIRÚRGICA </font></a></li>
                   
                <!--<li><a href="../quirurgico/nota_cirugia_segura.php">
                   <i class="fa fa-medkit" aria-hidden="true"></i> 
                  <font size ="2" color="black" > CIRUGÍA SEGURA </font></a></li>-->
                <li><a href="../quirurgico/nota_intervencion_quirurgica.php">
                   <i class="fa fa-user-md" aria-hidden="true"></i> 
                   <font size ="2" color="black" > DESCRIPCIÓN INTERVENCIÓN <br> QUIRÚRGICA </font></a></li>
              
               <!-- NOTAS ANESTÉSICAS-->
              
                        
                        <li><a href="../nota_anestesica/nota_unidad_cuidados.php"><i class="fa fa-bed"></i>
                        <font size ="2" color="black" >HOJA ANESTESICA <br> COMPLETA</font></a></li>
                        

               </li>

                
              </ul>
            </li>

             <li class="treeview">
             
              <?php
              if (isset($_SESSION['hospital'])) {
              ?>
                <li><a href="../hospitalizacion/recetario_medico.php"><i class="fa fa-files-o"></i> <span>
                      <font size ="2"> RECETA HOSPITALIZACIÓN</font></a></li>
              <?php
              } else {
              ?>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-files-o"></i> <span>
                      <font size ="2"> RECETA HOSPITALIZACIÓN</font></a></li>
              <?php
              }
              ?>

          </li> 


            <li class="treeview">
                <a href="../hospitalizacion/signos_vitales.php">
                  <i class="fa fa-heartbeat" aria-hidden="true"></i> <font size ="2"><span>VISUALIZAR SIGNOS VITALES</font></span>
                </a>

            </li>

            <li class="treeview">
                <a href="../hospitalizacion/ordenes_vista.php">
                 <i class="fa fa-files-o" aria-hidden="true"></i> <font size ="2"><span>INDICACIONES MÉDICAS</span></font>
                </a>

            </li>

             <li class="treeview">
                <a href="../estudios/estudios.php">
                   <i class="fa fa-flask" aria-hidden="true"></i> <font size ="2"><span>RESULTADOS DE ESTUDIOS</span></font>
                </a>
            </li>
            <?php if($usuario['id_rol'] == 12 || $usuario['id_rol'] == 5){ ?>
            <li class="treeview">
                <a href="../hospitalizacion/hoja_alta_medica.php">
                   <i class="fa fa-street-view" aria-hidden="true"></i> <font size ="2"><span>AVISO DE ALTA</span></font>
                </a>
            </li>
            <?php } ?>
    <!--        <li class="treeview">
              <a href="#">
                <i class="fa fa-user-md"></i> <font size ="2"><span>QUIRÓFANO</span></font>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li class="treeview">
                  <a href="../medicamentos/confirmar.php">
                    <i class="fa fa-medkit" aria-hidden="true"></i> <font size ="2"><span>CONFIRMAR MEDICAMENTOS <br>CEYE</span></font>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  
                </li>
              </ul>
            </li> -->
 
          <!--<li class="treeview">
              <a href="../cartas_consentimientos/consent_medico.php">
                <i class="fa fa-print" aria-hidden="true"></i> <font size ="2"><span>IMPRIMIR DOCUMENTOS</span></font>
                <i class="fa fa-angle-left pull-right"></i>
              </a>        
          </li>-->
          
          <?php if($usuario['id_rol'] == 2 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 5){ ?>
          <li class="treeview">
              <a href="../selectpac_sincama/select_pac.php">
                <i class="fa fa-print" aria-hidden="true"></i><font size ="2"><span>SELECCIONAR <br>OTROS PACIENTES</span></font>
              </a>        
          </li>
        <?php } ?>


          <?php
          } else {
          ?>
             
            <li class="treeview">
                <a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-address-book" aria-hidden="true"></i> <span><font size ="2">HISTORIA CLÍNICA</font></span>
                </a>
            </li>
             
            <li class="treeview">
              <a href="#">
                <i class="fa fa-stethoscope"></i> <span><font size ="2">NOTAS MÉDICAS</font></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              
              <ul class="treeview-menu">
                
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA DE URGENCIAS</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA DE INGRESO</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA DE EVOLUCIÓN</font></a></li>
                <!--<li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA DE POST-PARTO</font></a></li>-->
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA DE INTERCONSULTA</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA REFERENCIA/TRASLADO</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA NEONATOLÓGICA</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA PARTOGRAMA</font></a></li>
               <!-- <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2"> NOTA DE TRANSFUSIÓN</font></a></li>-->
                <li><a href="../../template/select_pac_hosp.php">
                    <i class="fa fa-street-view" aria-hidden="true"></i>
                    <font size ="2"> NOTA DE EGRESO</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2">NOTA DE DEFUNCIÓN</font></a></li>
                <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                    <font size ="2">RESUMEN CLÍNICO</font></a></li>
        <!-- NOTAS QUIRÚRGICAS-->
                <li><center><strong>QUIRÚRGICAS</strong></center></li>   
                <li><a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-bed" aria-hidden="true"></i> 
                   <font size ="2">PRE-OPERATORIA </font></a></li>
                <li><a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-medkit" aria-hidden="true"></i>
                   <font size ="2"> CIRUGÍA SEGURA</font></a></li>
                <li><a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-user-md" aria-hidden="true"></i>
                   <font size ="2"> DESCRIPCIÓN INTERVENCIÓN <br> QUIRÚRGICA</font></a></li>
                
        <!-- NOTAS ANESTÉSICAS-->
                 <li class="treeview">
                    <a href="#">
                       <i class="fa fa-stethoscope"></i> <span>NOTAS ANESTÉSICAS</span>
                       <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                       <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                       <font size ="2"> PRE-ANESTÉSICA</font></a></li>
                       <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                       <font size ="2"> EVALUACIÓN <br> PRE-ANESTÉSICA </font></a></li>
                       <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                       <font size ="2"> REGISTRO DESCRIPTIVO <br> TRANS-ANESTÉSICO</font></a></li>
                       <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                       <font size ="2"> CUIDADOS <br>POST ANESTÉSICOS<br>(NOTA DE RECUPERACIÓN)</font></a></li>
                       <li><a href="../../template/select_pac_hosp.php"><i class="fa fa-circle"></i>
                       <font size ="2"> POST-ANESTÉSICA</font></a></li>
                   </ul>
                </li>
              </ul>
            </li>


             <li class="treeview">
                <a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-id-card"></i> <span>
                      <font size ="2">RECETA HOSPITALIZACIÓN</font></span>
                </a>
            </li>

            <li class="treeview">
                <a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-heartbeat" aria-hidden="true"></i> <span>
                      <font size ="2">VISUALIZAR SIGNOS VTALES</font></span>
                </a>
            </li>
            <li class="treeview">
                <a href="../../template/select_pac_hosp.php">
                  <i class="fa fa-files-o" aria-hidden="true"></i> <span>
                     <font size ="2">INDICACIONES MÉDICAS</font></span>
                </a>
            </li>

            <li class="treeview">
                <a href="../../template/select_pac_hosp.php">
                  <i class="fa fa-flask" aria-hidden="true"></i> <span>
                    <font size ="2">RESULTADO DE ESTUDIOS</font></span>
                </a>
            </li>
              <?php if($usuario['id_rol'] == 12 || $usuario['id_rol'] == 5){ ?>
             <li class="treeview">
                <a href="../../template/select_pac_hosp.php">
                   <i class="fa fa-street-view" aria-hidden="true"></i> <span>
                      <font size ="2">AVISO DE ALTA MÉDICA</font></span>
                </a>
            </li>
             <?php } ?>
 <!--           <li class="treeview">
              <a href="#">
                <i class="fa fa-user-md"></i> <span><font size ="2">QUIRÓFANO</font></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

                  <li class="treeview">
                  <a href="../../template/select_pac_hosp.php">
                    <i class="fa fa-medkit" aria-hidden="true"></i> 
                      <span><font size ="2">CONFIRMAR INSUMOS<br>QUIRÓFANO (CEYE)</font></span>
                    <i class="fa fa-angle-left pull-right"></i>
                  </a>
                </li>
              </ul>
            </li>  

           <li class="treeview">
              <a a href="../../template/select_pac_hosp.php">
                <i class="fa fa-print" aria-hidden="true"></i> 
                  <span><font size ="2">IMPRIMIR DOCUMENTOS</font></span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>        
            </li>-->
            <?php if($usuario['id_rol'] == 2 || $usuario['id_rol'] == 12 || $usuario['id_rol'] == 5){ ?>
          <li class="treeview">
              <a href="../selectpac_sincama/select_pac.php">
                <i class="fa fa-print" aria-hidden="true"></i><font size ="2"><span>SELECCIONAR <br>OTROS PACIENTES</span></font>
              </a>        
          </li>
        <?php } ?>

          <?php
          }
          ?>


         <!-- <li class="treeview">
            <a href="#">
              <i class="fa fa-user-md"></i> <span><font size ="2">CIRUGÍA AMBULATORIA</font></span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              sub-menu notas medicas 

               sub-menu notas quirurgicas 
              <li class="treeview">
                <a href="#">
                 <i class="fa fa-stethoscope"></i> <span><font size ="2">NOTAS QUIRÚRGICAS</font></span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="../cirugia_ambulatoria/vista_preoperatoria.php"><i class="fa fa-circle"></i> 
                      <font size ="2">PRE-OPERATORIA</font></a></li>
                  <li><a href="../cirugia_ambulatoria/vista_intervencion.php"><i class="fa fa-circle"></i>
                      <font size ="2"> INTERVENCIÓN Qx</font></a></li>
                  <li><a href="../cirugia_ambulatoria/vista_cirugia.php"><i class="fa fa-circle"></i> 
                      <font size ="2"> CIRUGÍA SEGURA</font></a></li>
                  </ul>
              </li>
               sub-menu notas medicas 
              <li class="treeview">
                <a href="#">
                 <i class="fa fa-stethoscope"></i> <span><font size ="2">NOTAS ANESTÉSICAS</font></span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="../anestesica_ambulatorio/vista_anestesica.php"><i class="fa fa-circle"></i>
                      <font size ="2"> PRE-ANESTÉSICA</font></a></li>
                  <li><a href="../anestesica_ambulatorio/vista_2da_evaluacion.php"><i class="fa fa-circle"></i> 
                      <font size ="2"> EVALUACIÓN<br> PRE-ANESTÉSICA</font></a></li>
                  <li><a href="../anestesica_ambulatorio/vista_registro_descriptivo.php"><i class="fa fa-circle"></i>
                      <font size ="2"> REGISTRO DESCRIPTIVO<br> TRANS-ANESTÉSICO</font></a></li>
                  <li><a href="../anestesica_ambulatorio/vista_unidad.php"><i class="fa fa-circle"></i> 
                      <font size ="2"> CUIDADOS <br> POST-ANESTÉSICOS (UCPA) <br> (NOTA DE RECUPERACIÓN)</font></a></li>
                  <li><a href="../anestesica_ambulatorio/vista_nota_postanestesica.php"><i class="fa fa-circle"></i>
                      <font size ="2"> POST-ANESTÉSICA</font></a></li>
                </ul>
              </li>
            
              <li class="treeview">
                <a href="../medicamentos_ambulatorio/vista_pacientes.php">
                  <i class="fa fa-user-md"></i> <span><font size ="2">CONFIRMAR INSUMOS<br> Cx AMBULATORIA (CEYE)</font></span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
              </li>
              <li class="treeview">
                <a href="../documentos_ambulatorio/select_paciente.php">
                  <i class="fa fa-user-md"></i> <span><font size ="2">IMPRIMIR DOCUMENTOS<br> CIRUGÍA AMBULATORIA</font></span>
                  <i class="fa fa-angle-left pull-right"></i>
                </a>
              </li>
            </ul>
          </li>-->

    <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <!--AQUI VA QUE PUESTO TIENE-->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><STRONG>
              <h4>MÉDICO</h4>
            </STRONG></li>
        </ol>
      </nav>
      <script>
        function SoloLetras(e) {
          key = e.keyCode || e.which;
          tecla = String.fromCharCode(key).toString();
          letras = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚabcdefghijklmnñopqrstuvwxyzáéíóú ";

          especiales = [8, 13];
          tecla_especial = false
          for (var i in especiales) {
            if (key == especiales[i]) {
              tecla_especial = true;
              break;
            }
          }

          if (letras.indexOf(tecla) == -1 && !tecla_especial) {
            alert("Ingresar solo letras");
            return false;
          }
        }
      </script>

      <script>
        function SoloNumeros(evt) {
          if (window.event) {
            keynum = evt.keyCode;
          } else {
            keynum = evt.which;
          }

          if ((keynum > 47 && keynum < 58) || keynum == 8 || keynum == 13 || keynum == 46 || keynum == 44) {
            return true;
          } else {
            alert("Ingresar solo numeros");
            return false;
          }
        }
      </script>

      <script>
        function recetaamb(evt) {
          if (window.event) {
            keynum = evt.keyCode;
          } else {
            keynum = evt.which;
          }

          if ((keynum > 47 && keynum < 58) || keynum == 8) {
            return true;
          } else {
            alert("Ingresar solo numeros");
            return false;
          }
        }
      </script>