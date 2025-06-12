<?php
session_start();
//include "../../conexionbd.php";
include "../header_medico.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="gb18030">
    <link rel="stylesheet" type="text/css" href="css/select2.css">
    <link href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" rel="stylesheet"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFMw5uZjQz4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/select2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldLv/Pr4nhuBviF5jGqQK/5i2Q5iZ64dxBl+zOZ" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>
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

    <title>DOCUMENTACIÓN </title>

</head>
<!--seccion urgencias-->

<body>

    <section class="container">

        <!--------------------------
    | Your Page Content Here |
    -------------------------->

        <a href="../../template/menu_medico.php" class="btn-sm btn-danger">Regresar...</a>

        <p>
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 22px;">
            <center><strong>IMPRESIÓN DE DOCUMENTOS</strong></center>
            <p>
        </div>
        <div class="container">

            <?php

        include "../../conexionbd.php";

        
        $resultado2 = $conexion->query("SELECT id_usua, curp_u, nombre, papell,sapell,fecnac,mat,cedp,cargp,email,u_activo FROM reg_usuarios") or die($conexion->error);
        ?>

            <!--Fin de los filtros-->


            <?php
              $id_atencion = $_SESSION['hospital'];
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'
                  . '<td>' . 'Expediente: '. '</td>' 
                  . '<td><strong>' . $row['folio'] . '</strong></td>'
                  . '<td>' . '  Paciente: '. '</td>' 
                  . '<td><strong>' . $row['papell'] . ' ' . $row['sapell'] . ' ' . $row['nom_pac'] . '</strong></td><br>'
                  . '</tr>';
                $no++;
              }

              ?>
            <hr>
        </div>
    </section>


    <section class="Container">


        <div class="container box">


            <?php

        include "../../conexionbd.php";

        // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios";

        //  $result = $conn->query($sql);

        $resultado2 = $conexion->query("SELECT id_usua, curp_u, nombre, papell,sapell,fecnac,mat,cedp,cargp,email,u_activo FROM reg_usuarios") or die($conexion->error);
        ?>

            <!--Fin de los filtros-->




            <div class="container" style="background-color: #5880B4; color:white;">
                <div class="row">
                    <div class="col">
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_administrativa/cartas_consentimientos/pdf_hoja_inicial.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                       style="font-size:20px"></span></a>
                  </strong></td>';
                
                


                $no++;
              }

              ?>

                        <strong>1. Hoja Inicial</strong>
                        <p>
                        <p>

                            <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_administrativa/cartas_consentimientos/pdf_hoja_frontal.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                       style="font-size:20px"></span></a>
                  </strong></td>';
                
                
                $no++;
              }

              ?>
                            <strong>2. Hoja Frontal </strong>
                        <p>
                        <p>
                            <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../historia_clinica/buscar_hc.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';

                $no++;
              }

              ?>
                            <strong>3. Historia clínica </strong>
                            <hr>

                            <strong>4. Observación</strong>
                        <p></p>

                        <strong>5. Notas médicas</strong>
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../vistas_pdf/vista_ingreso.php?Id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     ><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                
                


                $no++;
              }

              ?>
                        5.01 Exploracion Fisica
                        <p></p>

                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_evo_pdf.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target=""><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                
                


                $no++;
              }

              ?>

                        5.02 Refracciones antiguas
                        <p></p>

                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_inter_pdf.php?Id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target=""><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>


                        5.03 Autorrefractor/Queratometria
                        <p></p>



                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_traslado.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>


                        5.04 Refracción Actual
                        <p></p>

                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_neona_pdf.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>


                        5.05 Receta Anteojos
                        <p></p>



                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_parto_pdf.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>


                        5.06 Receta Lentes de Contacto
                        <p></p>


                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_egreso.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>

                        5.07 Pruebas
                        <p></p>



                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_nino_bebe.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.08 Niño/bebe
                        <p></p>


                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_mediciones_cornea.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>



                        5.09 Mediciones de la cornea
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_presion_intraocular.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.10 Presion intraocular
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_vias.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }

              ?>
                        5.11 Orbita, Parpados y Vias Lagrimales
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_seg_ant.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.12 Segmento Anterior
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_segmento_post.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.13 Segmento Posterior
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_estudios.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.14 Estudios
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_lente_intraocular.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.15 Lente Intraocular
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_diagnostico.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.16 Diagnostico
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_tratamiento.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.17 Tratamiento
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_tratamiento_laser.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.18 Tratamiento Laser
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_examenes_laboratorio.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.19 Examenes de laboratorio
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_examenes_gabinete.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.21 Examenes de Gabinete
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_recomendaciones.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        5.22 Recomendaciones
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                    href="../vistas_pdf/vista_resumen.php"><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>

                        5.21
                        <hr>

                    </div>

                    <div class="col">
                        <p></p>
                        <strong>10. Aviso de alta</strong>
                        <hr>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               
               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../pdf/vista_recetas_med.php"
                     target=""><span class="fa fa-file-pdf-o"
                                           style="font-size:20px"></span></a>
                  </strong></td>';
                $no++;
              }
              ?>
                        <strong>11. Recetas hospitalización </strong>
                        <hr>
                    </div>
                    <div class="col">
                        <p></p>

                        <strong>12. Consentimientos (ENFERMERIA)</strong>

                        <p></p>

                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_administrativa/cartas_consentimientos/pdf_consent_anest.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
              ?>
                        12.01 Procedimiento anestésico
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_administrativa/cartas_consentimientos/pdf_consent_BI.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
              ?>
                        12.02 Consentimiento informado
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="edit_pdf_consentBI.php"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
              ?>

                        12.03 Consentimiento informado abierto
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_administrativa/cartas_consentimientos/pdf_consent_trans.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
            ?>

                        12.04 Para transfusión sanguínea
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_administrativa/cartas_consentimientos/pdf_consent_BIPD.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
            ?>
                        12.05 Consentimiento alto riesgo
                        <p></p>


                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_medica/cartas_consentimientos/pdf_resp_alo.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"
                     target="_blank"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
            ?>
                        12.06 Responsiva alojamiento conjunto
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_medica/vistas_pdf/vista_mp_pdf.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
            ?>

                        12.07 Notificación a ministerio público
                        <p></p>
                        <?php
              // ../../gestion_medica/cartas_consentimientos/pdf_consent_BI_medico.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '
              $id_atencion = $_SESSION['hospital'];
              // $sql = "SELECT id_usua, curp_u, nombre, papell,sapell,fecha,mat,cedp,cargp,email,u_activo FROM reg_usuarios;";
              //  $result = $conn->query($sql);
              $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.Id_exp=DI.Id_exp ") or die($conexion->error);

              $no = 1;
              while ($row = $resultado2->fetch_assoc()) {
                 echo '<tr>'

               . '<td> <strong>
                  <a type="submit" class="btn btn-danger btn-sm"
                     href="../../gestion_medica/vistas_pdf/vista_inci.php?id_exp=' . $row['Id_exp'] . '&id_atencion=' . $row['id_atencion'] . '"><span class="fa fa-file-pdf-o"
                                           style="font-size:18px"></span></a>
                  </strong></td>';
                
                $no++;
              }
            ?>
                        12.08 Responsiva de incineracion
                        </script>
                        <!-- FastClick -->
                        <script src='../../template/plugins/fastclick/fastclick.min.js'></script>
                        <!-- AdminLTE App -->
                        <script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>

</html>