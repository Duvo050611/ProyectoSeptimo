<?php
include "../conexionbd.php";

session_start();
//




if (!isset($_SESSION['login'])) {
  // remove all session variables
  session_unset();
  // destroy the session
  session_destroy();
  header('Location: ../index.php');
}
$usuario = $_SESSION['login'];
//$resultado = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua='" . $usuario . "'") or die($conexion->error);
if (!($usuario['id_rol'] == 2 || $usuario['id_rol'] == 5 || $usuario['id_rol'] == 12)) {
  session_unset();
  session_destroy();
  // echo "<script>window.Location='../index.php';</script>";
  header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
  <title>INEO Metepec</title>
  <link rel="icon" href="../imagenes/SIF.PNG">
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.2 -->
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Font Awesome Icons -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Ionicons -->
  <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
  <!-- Morris chart -->
  <link href="plugins/morris/morris.css" rel="stylesheet" type="text/css" />
  <!-- jvectormap -->
  <link href="plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <!-- Daterange picker -->
  <link href="plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
  <!-- AdminLTE Skins. Choose a skin from the css/skins
           folder instead of downloading all of them to reduce the load. -->
  <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />


  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

  <style>
  .headt{
      width:100%;
  }
  .nompac{
    font-size: 11.5px;
    position:absolute;
    }
     .ancholi{
    margin-top: 1px;
    margin-bottom: 10px;
    width:144px;
    height:100px;
    display: inline-block;
    }
    .ancholi2{
        
    width:139px;
    height:97px;
    display: inline-block;
    box-shadow:3px 5px 8px #2a6675;
    
    }
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
      max-width: 10000px;
      height: auto;
      display: flex;
      overflow-y: scroll;
      column-gap: 0.5em;
      column-rule: 10px solid white;
      column-width: 100px;
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

    .alert{
         padding-right: 40px;
         padding-left:6px;
     }
.nod{

        font-size: 10.3px;
     }
        @media screen and (max-width: 980px){
    
     .alert{
         padding-right: 38px;
         padding-left: 10px;
     }
     .nompac{
         margin-left:-3px;
        font-size: 10px;

     }
     .nod{
        font-size: 7px;
     }
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

        <a href="menu_medico.php" class="logo">
          
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="../imagenes/SI.PNG" height="30" width="120"></b></span>
        </a>
      <?php
      } else if ($usuario['id_rol'] == 5) {

      ?>
        <a href="menu_gerencia.php" class="logo">
        
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="../imagenes/SI.PNG" height="30" width="120"></b></span>
        </a>
        <?php }elseif($usuario['id_rol'] == 12) { ?>
         <a href="menu_residente.php" class="logo">
        
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img src="../imagenes/SI.PNG" height="30" width="120"></b></span>
        </a>

      <?php
      } else {
        //session_unset();
        session_destroy();
        echo "<script>window.Location='../index.php';</script>";
      }
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

            <!--<div class="col-2"><br>
    <div class="dropdown">
        <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   <?php //echo $usuario['nombre'];
    ?> <?php //echo $usuario['papell'];
        ?> <?php //echo $usuario['sapell'];
            ?>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <center><img src="imagenes/logo.jpg" height="45" class="rounded"></center><hr>
      <a class="dropdown-item">ID: <?php //echo $usuario['id_usua'];
                                    ?></a>
      <a class="dropdown-item">Matricula: <?php //echo $usuario['mat'];
                                          ?></a>
      <hr>
    <center><a class="btn btn-danger col-12" href="cerrar_sesion.php">Cerrar sesión</a></center>

  </div>
</div>
    </div>-->



            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"> <?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">

                  <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">

                  <p>
                    <?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?> <?php echo $usuario['sapell']; ?>

                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="../cerrar_sesion.php" class="btn btn-default btn-flat">CERRAR SESIÓN</a>
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


            <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $usuario['nombre']; ?> <?php echo $usuario['papell']; ?></p>

            <a href="#"><i class="fa fa-circle text-success"></i> ACTIVO</a>
          </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="treeview">
                        
              <?php
              if (isset($_SESSION['hospital'])) {
              ?>
                <li><a href="../gestion_medica/cartas_consentimientos/consent_lista.php"><i class="fa fa-print" ></i>IMPRIMIR DOCUMENTOS</a></li>
              <?php
              } else {
              ?>
                <li><a href="select_pac_hosp.php"><i class="fa fa-print" ></i>IMPRIMIR DOCUMENTOS</a></li>
              <?php
              }
              ?>
        
          </li>

          


           <?php
          if (isset($_SESSION['hospital'])) {
          ?>
           <li class="treeview">
                <a href="../historia_clinica/h_clinica.php">
                   <i class="fa fa-address-book" aria-hidden="true"></i> <span><font size ="2">HISTORIA CLÍNICA</font></span>
                </a>

            </li>

           <li class="treeview">
              <a href="#">
                <i class="fa fa-stethoscope"></i> <span>NOTAS MÉDICAS</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
               
               
                <li><a href="../gestion_medica/hospitalizacion/nota_ingreso.php">
                   <i class="fa fa-bed" aria-hidden="true"></i> NOTA DE INGRESO</a></li>
                <li><a href="../gestion_medica/hospitalizacion/vista_nota_evolucion.php">
                   <i class="fa fa-address-card" aria-hidden="true"></i> NOTA DE EVOLUCIÓN</a></li>
                
                <li><a href="../gestion_medica/hospitalizacion/nota_interconsulta.php">
                   <i class="fa fa-hospital-o" aria-hidden="true"></i> NOTA INTERCONSULTA</a></li>
                <li><a href="../gestion_medica/hospitalizacion/nota_translado.php">
                   <i class="fa fa-ambulance" aria-hidden="true"></i> NOTA REFERENCIA/TRASLADO</a></li>
                <li><a href="../gestion_medica/hospitalizacion/nota_neonatologica.php">
                   <i class="fas fa-baby"></i> NOTA NEONATOLÓGICA</a></li>
                <li><a href="../gestion_medica/hospitalizacion/partograma.php">
                   <i class="fa fa-female"></i> NOTA PARTOGRAMA</a></li>

                <li><a href="../gestion_medica/hospitalizacion/nota_posparto.php"> 
                   <i class="fa fa-female"></i> NOTA POST-PARTO</a></li>
               
                <li><a href="../gestion_medica/hospitalizacion/vista_de_transfuciones.php">
                   <i class="fa fa-angle-double-right" aria-hidden="true"></i> NOTA DE TRANSFUSIÓN </a></li>
                <li><a href="../gestion_medica/hospitalizacion/nota_egreso.php">
                   <i class="fa fa-check-square" aria-hidden="true"></i> NOTA DE EGRESO</a></li>


        <!-- NOTAS QUIRÚRGICAS-->
                <li><center><strong>QUIRÚRGICAS</strong></center></li>        
                <li><a href="../gestion_medica/quirurgico/nota_preoperatoria.php">
                   <i class="fa fa-bed" aria-hidden="true"></i> PRE-OPERATORIA </a></li>
                <li><a href="../gestion_medica/quirurgico/nota_cirugia_segura.php">
                   <i class="fa fa-medkit" aria-hidden="true"></i> CIRUGÍA SEGURA</a></li>
                <li><a href="../gestion_medica/quirurgico/nota_postoperatoria.php">
                   <i class="fa fa-plus-square" aria-hidden="true"></i> POST-OPERATORIA</a></li>
                <li><a href="../gestion_medica/quirurgico/nota_intervencion_quirurgica.php">
                   <i class="fa fa-user-md" aria-hidden="true"></i> DESCRIPCIÓN INTERVENCIÓN <br> QUIRÚRGICA</a></li>
                
        <!-- NOTAS ANESTÉSICAS-->
                 <li class="treeview">
                    <a href="#">
                       <i class="fa fa-stethoscope"></i> <span>NOTAS ANESTÉSICAS</span>
                       <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">
                       <li><a href="../gestion_medica/nota_anestesica/nota_anestesica.php"><i class="fa fa-circle"></i> PRE-ANESTÉSICA</a></li>
                       <li><a href="../gestion_medica/nota_anestesica/nota_2da_evaluacion.php"><i class="fa fa-circle"></i>EVALUACIÓN <br> PRE-ANESTÉSICA</a></li>
                       <li><a href="../gestion_medica/nota_anestesica/nota_registro_descriptivo.php">
                        <i class="fa fa-circle"></i> REGISTRO DESCRIPTIVO <br> TRANS-ANESTÉSICO</a></li>
                       <li><a href="../gestion_medica/nota_anestesica/nota_unidad_cuidados.php"><i class="fa fa-circle"></i> CUIDADOS <br>POST ANESTÉSICOS<br>(NOTA DE RECUPERACIÓN)</a></li>
                       <li><a href="../gestion_medica/nota_anestesica/nota_post_anestesica.php"><i class="fa fa-circle"></i>  POST-ANESTÉSICA</a></li>
                    </ul>
                 </li>
               </ul>
            </li>

            <li class="treeview">
             
              <?php
              if (isset($_SESSION['hospital'])) {
              ?>
                <li><a href="../gestion_medica/hospitalizacion/recetario_medico.php"><i class="fa fa-files-o"></i>RECETA HOSPITALIZACIÓN</a></li>
              <?php
              } else {
              ?>
                <li><a href="select_pac_hosp.php"><i class="fa fa-files-o"></i>RECETA HOSPITALIZACIÓN</a></li>
              <?php
              }
              ?>

          </li> 

            <li class="treeview">
                <a href="../gestion_medica/hospitalizacion/signos_vitales.php">
                  <i class="fa fa-heartbeat" aria-hidden="true"></i> <span>VISUALIZAR SIGNOS VITALES</span>
                </a>

            </li>

             <li class="treeview">
                <a href="../gestion_medica/hospitalizacion/ordenes_medico_form.php">
                  <i class="fa fa-files-o" aria-hidden="true"></i> <span>INDICACIONES MÉDICAS</span>
                </a>

            </li>


            <li class="treeview">
                <a href="../gestion_medica/estudios/estudios.php">
                   <i class="fa fa-flask" aria-hidden="true"></i> <span>RESULTADO DE ESTUDIOS</span>
                </a>
            </li>

            <li class="treeview">
                <a href="../gestion_medica/hospitalizacion/hoja_alta_medica.php">
                   <i class="fa fa-street-view" aria-hidden="true"></i> <span>AVISO DE ALTA MÉDICA</span>
                </a>
            </li>


          <li class="treeview">
              <a href="../gestion_medica/selectpac_sincama/select_pac.php">
                <i class="fa fa-print" aria-hidden="true"></i> <span>SELECCIONAR <br>OTROS PACIENTES</span>
              </a>        
          </li>


      <?php

}else {
 ?>
    
           <li class="treeview">
                <a href="select_pac_hosp.php">
                   <i class="fa fa-folder" aria-hidden="true"></i> <span>HISTORIA CLÍNICA</span>
                </a>

            </li>


            <li class="treeview">
              <a href="#">
               <i class="fa fa-stethoscope"></i> <span>NOTAS MÉDICAS</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
               
                 <li><a href="select_pac_hosp.php">
                   <i class="fa fa-bed" aria-hidden="true"></i> NOTA DE INGRESO</a></li>
                <li><a href="select_pac_hosp.php">
                   <i class="fa fa-address-card" aria-hidden="true"></i> NOTA DE EVOLUCIÓN</a></li>
                <li><a href="select_pac_hosp.php">
                     <i class="fa fa-female"></i> NOTA POST-PARTO</a></li>
                <li><a href="select_pac_hosp.php">
                     <i class="fa fa-hospital-o" aria-hidden="true"></i> NOTA INTERCONSULTA</a></li>
                <li><a href="select_pac_hosp.php">
                     <i class="fa fa-ambulance" aria-hidden="true"></i> NOTA REFERENCIA/TRASLADO</a></li>
                <li><a href="select_pac_hosp.php">
                      <i class="fas fa-baby"></i> NOTA NEONATOLÓGICA</a></li>
                <li><a href="select_pac_hosp.php">
                     <i class="fa fa-female"></i> NOTA PARTOGRAMA</a></li>
                <li><a href="select_pac_hosp.php">
                     <i class="fa fa-angle-double-right" aria-hidden="true"></i> NOTA DE TRANSFUSIÓN </a></li>
                <li><a href="select_pac_hosp.php">
                     <i class="fa fa-check-square" aria-hidden="true"></i> NOTA DE EGRESO</a></li>
          <!-- NOTAS QUIRÚRGICAS-->
                <li><center><strong>QUIRÚRGICAS</strong></center></li>   
                <li><a href="select_pac_hosp.php">
                   <i class="fa fa-bed" aria-hidden="true"></i> PRE-OPERATORIA </a></li>
                <li><a href="select_pac_hosp.php">
                   <i class="fa fa-medkit" aria-hidden="true"></i> CIRUGÍA SEGURA</a></li>
                <li><a href="select_pac_hosp.php">
                   <i class="fa fa-plus-square" aria-hidden="true"></i> POST-OPERATORIA</a></li>
               <li><a href="select_pac_hosp.php">
                   <i class="fa fa-user-md" aria-hidden="true"></i> DESCRIPCIÓN INTERVENCIÓN <br> QUIRÚRGICA</a></li>
                
        <!-- NOTAS ANESTÉSICAS-->
                 <li class="treeview">
                    <a href="#">
                       <i class="fa fa-stethoscope"></i> <span>NOTAS ANESTÉSICAS</span>
                       <i class="fa fa-angle-left pull-right"></i>
                    </a>

                    <ul class="treeview-menu">
                       <li><a href="select_pac_hosp.php"><i class="fa fa-circle"></i> PRE-ANESTÉSICA</a></li>
                       <li><a href="select_pac_hosp.php"><i class="fa fa-circle"></i> EVALUACIÓN <br> PRE-ANESTÉSICA</a></li>
                       <li><a href="select_pac_hosp.php"><i class="fa fa-circle"></i> REGISTRO DESCRIPTIVO <br> TRANS-ANESTÉSICO</a></li>
                       <li><a href="select_pac_hosp.php"><i class="fa fa-circle"></i> CUIDADOS <br>POST ANESTÉSICOS<br>(NOTA DE RECUPERACIÓN)</a></li>
                       <li><a href="select_pac_hosp.php"><i class="fa fa-circle"></i> POST-ANESTÉSICA</a></li>
                   </ul>
               </li>
              </ul>

            </li>

            <li class="treeview">
                <a href="select_pac_hosp.php">
                  <i class="fa fa-files-o"></i>  <span> RECETA HOSPITALIZACIÓN</span>
                </a>
            </li>

            <li class="treeview">
                <a href="select_pac_hosp.php">
                   <i class="fa fa-heartbeat" aria-hidden="true"></i> <span>VISUALIZAR SIGNOS VITALES</span>
                </a>
            </li>
            
            <li class="treeview">
                <a href="select_pac_hosp.php">
                   <i class="fa fa-files-o" aria-hidden="true"></i> <span>INDICACIONES MÉDICAS</span>
                </a>
            </li>

            <li class="treeview">
                <a href="select_pac_hosp.php">
                  <i class="fa fa-flask" aria-hidden="true"></i> <span>RESULTADO DE ESTUDIOS</span>
                </a>
            </li>
            
             <li class="treeview">
                <a href="select_pac_hosp.php">
                   <i class="fa fa-street-view" aria-hidden="true"></i> <span>AVISO DE ALTA MÉDICA</span>
                </a>
            </li>


          <li class="treeview">
              <a href="select_pac_hosp.php">
                <i class="fa fa-print" aria-hidden="true"></i> <span>SELECCIONAR <br>OTROS PACIENTES</span>
              </a>        
          </li>


             
            
          <?php
          }
          ?>


       

        </ul>
      </section>
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

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <script>
                    $(document).ready(function() {
                        swal({
                            title: "Favor de seleccionar un paciente", 
                            type: "error",
                            confirmButtonText: "ACEPTAR"
                        }, function(isConfirm) { 
                            if (isConfirm) {
                                window.location.href = "menu_medico.php";
                            }
                        });
                    });
                </script>

      
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
  <center><strong>CONSULTA EXTERNA</strong></center><p>
</div> <p></p>

<div class="container box">
        <div class= "row">
        

        <?php
        $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=1 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          if ($estaus == "LIBRE") {
        ?>
            <div class="ancholi">
             <div class="alert alert-success ancholi2" role="alert">
                <div><a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-bed"></i></a>
                <h7><font size="2"><?php echo $num_cama ?> </font></h7></div>
                <div><h7><font size="2"><?php echo $estaus ?></font></h7></div><br><br>
             </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
             <div class="ancholi">
              <div class="alert alert-danger ancholi2" role="alert">
               <!-- <a href="../enfermera/censo/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer">--><i style="font-size:18px;" class="fa fa-bed"></i></a>
                 <h7><font size="2"><?php echo $num_cama ?> <br> <h7><font size="2" class="nod"><?php echo $esta ?></font></h7></font></h7>
                <br><br><br>
              </div>
            </div>
           <?php
          } elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="ancholi">
              <div class="alert alert-warning ancholi2">
                <a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-bed"></i></a>
                <h7><font size="2"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
                 <br><br>
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php  
$sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp  order by di.fecha DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                   $id_exp = $row_cam['Id_exp'];
                   $id_usua1 = $row_cam['id_usua'];

                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];



                }
          ?>
          <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp?>&usuareg=<?php echo $usuario['id_usua']?>&usuapac=<?php echo $id_usua1?>&usuareg2=<?php echo $id_usua2?>&usuareg3=<?php echo $id_usua3?>&usuareg4=<?php echo $id_usua4?>&usuareg5=<?php echo $id_usua5?>" class="small-box-footer">
            <div class="ancholi">
              <div class="alert alert ancholi2" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:18px;" class="fa fa-bed"></i>
                
                <h7><font size="2"><?php echo $num_cama ?></font></h7>
              <!--  <h7>Estatus: OCUPADA</h7>-->
              
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.fecha DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac = $row_cam['nom_pac'] ;
                  $papell = $row_cam['papell'] ;
                   $id_exp = $row_cam['Id_exp'];
                   $id_usua1 = $row_cam['id_usua'];

                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
                ?>
                <br>
                <h7 class="nompac"><?php echo $papell ?></h7><br>
                <h7 class="nompac"><?php echo $nombre_pac ?></h7>
          <br><br>
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>      

        
        <?php
        $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=1 and seccion=2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          if ($estaus == "LIBRE") {
        ?>
         <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $esta ?></font></h7>
                <br>
 
              </div>
            </div>
            <?php
          } else {
          ?>
<?php  
$sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5  from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp and alta_adm='NO' order by di.id_atencion DESC";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                   $id_exp = $row_cam['Id_exp'];
                   $id_usua1 = $row_cam['id_usua'];
                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
          ?>
            <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&id_exp=<?php echo $id_exp?>&usuareg=<?php echo $usuario['id_usua']?>&usuapac=<?php echo $id_usua1?>&usuareg2=<?php echo $id_usua2?>&usuareg3=<?php echo $id_usua3?>&usuareg4=<?php echo $id_usua4?>&usuareg5=<?php echo $id_usua5?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                  $id_usua1 = $row_cam['id_usua'];
$id_exp = $row_cam['Id_exp'];
                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
                ?>
                <font size="2"><?php echo $nombre_pac?></font> 
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
</div>
</div>
       
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
  <center><strong>PREPARACIÓN</strong></center><p>
</div> 

<div class="container box col-12">
  <div class= "row">

        <?php
        $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=2 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          if ($estaus == "LIBRE") {
        ?>
             <div class="ancholi">
             <div class="alert alert-success ancholi2" role="alert">
                <div><a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-bed"></i></a>
                <h7><font size="2"><?php echo $num_cama ?> </font></h7></div>
                <div><h7><font size="2"><?php echo $estaus ?></font></h7></div><br><br>
             </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
           <div class="ancholi">
              <div class="alert alert-danger ancholi2" role="alert">
               <!-- <a href="../enfermera/censo/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer">--><i style="font-size:18px;" class="fa fa-bed"></i></a>
                 <h7><font size="2"><?php echo $num_cama ?> <br> <h7><font size="2" class="nod"><?php echo $esta ?></font></h7></font></h7>
                <br><br><br>
              </div>
            </div>
           <?php
          } elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="ancholi">
              <div class="alert alert-warning ancholi2" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-bed"></i></a>
                <h7><font size="2"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
                <br><br>
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                 $id_usua1 = $row_cam['id_usua'];

                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
                ?>
            <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&usuareg=<?php echo $usuario['id_usua']?>&usuapac=<?php echo $id_usua1?>&usuareg2=<?php echo $id_usua2?>&usuareg3=<?php echo $id_usua3?>&usuareg4=<?php echo $id_usua4?>&usuareg5=<?php echo $id_usua5?>" class="small-box-footer">
            <div class="ancholi">
              <div class="alert alert ancholi2" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:18px;" class="fa fa-bed"></i>
                
                <h7><font size="2"><?php echo $num_cama ?></font></h7>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac = $row_cam['nom_pac'] ;
                  $papell = $row_cam['papell'] ;
                  $id_exp = $row_cam['Id_exp'];
                  $id_usua1 = $row_cam['id_usua'];
                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
                ?>
                <br>
                <h7 class="nompac"><?php echo $papell ?></h7><br>
                <h7 class="nompac"><?php echo $nombre_pac ?></h7>
          <br><br>
              </div>
            </div></a>
        <?php
          }
        }
        ?>      

        <?php
        $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=2 and seccion=2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          if ($estaus == "LIBRE") {
        ?>
             <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
          <?php
          } else {
          ?>
            <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&usuareg=<?php echo $usuario['id_usua']?>&usuapac=<?php echo $id_usua1?>&usuareg2=<?php echo $id_usua2?>&usuareg3=<?php echo $id_usua3?>&usuareg4=<?php echo $id_usua4?>&usuareg5=<?php echo $id_usua5?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                 
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
</div>
</div>

<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
  <center><strong>RECUPERACIÓN</strong></center><p>
</div> 

<div class="container box col-12">
  <div class= "row">

        <?php
        $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=3 and seccion=1 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          if ($estaus == "LIBRE") {
        ?>
             <div class="ancholi">
             <div class="alert alert-success ancholi2" role="alert">
                <div><a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-bed"></i></a>
                <h7><font size="2"><?php echo $num_cama ?> </font></h7></div>
                <div><h7><font size="2"><?php echo $estaus ?></font></h7></div><br><br>
             </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
           <div class="ancholi">
              <div class="alert alert-danger ancholi2" role="alert">
               <!-- <a href="../enfermera/censo/edita_cama.php?id=<?php echo $row['id']?>" class="small-box-footer">--><i style="font-size:18px;" class="fa fa-bed"></i></a>
                 <h7><font size="2"><?php echo $num_cama ?> <br> <h7><font size="2" class="nod"><?php echo $esta ?></font></h7></font></h7>
                <br><br><br>
              </div>
            </div>
           <?php
          } elseif ($estaus == "EN PROCESO DE LIBERACION" or $estaus == "EN PROCESO DE LIBERA"){
$pr="POR LIBERAR";
?>
<div class="ancholi">
              <div class="alert alert-warning ancholi2" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:18px;" class="fa fa-bed"></i></a>
                <h7><font size="2"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="1"><?php echo $pr ?></font></h7>
                <br><br>
                <br>
              </div>
            </div>
<?php
          }else{
          ?>
          <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                 $id_usua1 = $row_cam['id_usua'];

                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
                ?>
            <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&usuareg=<?php echo $usuario['id_usua']?>&usuapac=<?php echo $id_usua1?>&usuareg2=<?php echo $id_usua2?>&usuareg3=<?php echo $id_usua3?>&usuareg4=<?php echo $id_usua4?>&usuareg5=<?php echo $id_usua5?>" class="small-box-footer">
            <div class="ancholi">
              <div class="alert alert ancholi2" role="alert" style="background-color: #2b2d7f; color:white;">

                <i style="font-size:18px;" class="fa fa-bed"></i>
                
                <h7><font size="2"><?php echo $num_cama ?></font></h7>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp,di.id_usua,di.id_usua2,di.id_usua3,di.id_usua4,di.id_usua5 from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                   $nombre_pac = $row_cam['nom_pac'] ;
                  $papell = $row_cam['papell'] ;
                  $id_exp = $row_cam['Id_exp'];
                  $id_usua1 = $row_cam['id_usua'];
                   $id_usua2 = $row_cam['id_usua2'];
                  $id_usua3 = $row_cam['id_usua3'];
                  $id_usua4 = $row_cam['id_usua4'];
                  $id_usua5 = $row_cam['id_usua5'];
                }
                ?>
                <br>
                <h7 class="nompac"><?php echo $papell ?></h7><br>
                <h7 class="nompac"><?php echo $nombre_pac ?></h7>
          <br><br>
              </div>
            </div></a>
        <?php
          }
        }
        ?>      

        <?php
        $sql = 'SELECT id,estatus,tipo,num_cama, id_atencion from cat_camas where piso=2 and seccion=2 ORDER BY num_cama ASC';
        $result = $conexion->query($sql);
        //  $id_atencion = $_GET['id_atencion'];
        while ($row = $result->fetch_assoc()) {
          $num_cama = $row['num_cama'];
          $id_atencion = $row['id_atencion'];
          $estaus = $row['estatus'];
          if ($estaus == "LIBRE") {
        ?>
             <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-success" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:25px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2"><?php echo $estaus ?></font></h7>
                <br>
                <br>
              </div>
            </div>
          <?php
          } elseif ($estaus == "MANTENIMIENTO") {
            $esta = "NO DISPONIBLE";
          ?>
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-warning" role="alert">
                <a href="#" class="small-box-footer"><i style="font-size:20px;" class="fa fa-bed"></i></a>
                <h7><font size="3"><?php echo $num_cama ?></font></h7>
                <br>
                <h7><font size="2" class="nod"><?php echo $esta ?></font></h7>
                <br>

              </div>
            </div>
          <?php
          } else {
          ?>
            <a href="../gestion_medica/hospitalizacion/select_pac.php?id_atencion=<?php echo $id_atencion ?>&usuareg=<?php echo $usuario['id_usua']?>&usuapac=<?php echo $id_usua1?>&usuareg2=<?php echo $id_usua2?>&usuareg3=<?php echo $id_usua3?>&usuareg4=<?php echo $id_usua4?>&usuareg5=<?php echo $id_usua5?>" class="small-box-footer">
            <div class="col-lg-1.5 col-xs-1">
              <div class="alert alert-danger" role="alert">

                <i style="font-size:25px;" class="fa fa-bed"></i>
                
                <h7><font size="3"><?php echo $num_cama ?></font></h7><br>
              <!--  <h7>Estatus: OCUPADA</h7>-->
                <?php
                $sql_pac = "SELECT p.nom_pac, p.papell,p.sapell, di.Id_exp from dat_ingreso di, paciente p, cat_camas cc where $id_atencion = di.id_atencion and di.Id_exp = p.Id_exp";
                $result_pac = $conexion->query($sql_pac);
                while ($row_cam = $result_pac->fetch_assoc()) {
                  $nombre_pac = $row_cam['papell'] . ' ' .$row_cam['nom_pac'] ;
                 
                }
                ?>
                <font size="2" class="nompac"><?php echo $nombre_pac ?></font>
                <br />
                
              </div>
            </div></a>
        <?php
          }
        }
        ?>
</div>
</div>

</section>
     

     <!-- </section> --><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <footer class="main-footer">
      <?php
      include("footer.php");
      ?>
    </footer>

  </div><!-- ./wrapper -->

  <!-- jQuery 2.1.3 -->
  <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
  <!-- Bootstrap 3.3.2 JS -->
  <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
  <!-- FastClick -->
  <script src='plugins/fastclick/fastclick.min.js'></script>
  <!-- AdminLTE App -->
  <script src="dist/js/app.min.js" type="text/javascript"></script>
  <!-- Sparkline -->
  <script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
  <!-- jvectormap -->
  <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
  <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
  <!-- daterangepicker -->
  <script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
  <!-- datepicker -->
  <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
  <!-- SlimScroll 1.3.0 -->
  <script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <!-- ChartJS 1.0.1 -->
  <script src="plugins/chartjs/Chart.min.js" type="text/javascript"></script>

  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard2.js" type="text/javascript"></script>

  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js" type="text/javascript"></script>




</body>

</html>