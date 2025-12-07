<?php
session_start();
include "../../conexionbd.php";
$conexion = ConexionBD::getInstancia()->getConexion();
include "../header_enfermera.php";

// Datos de sesión
$usuario = $_SESSION['login'];
$id_atencion = $_SESSION['pac'];

// Traer datos del paciente
$queryPaciente = "
    SELECT P.Id_exp, P.nom_pac, P.papell, P.sapell, DI.id_atencion
    FROM paciente P
    INNER JOIN dat_ingreso DI ON P.Id_exp = DI.Id_exp
    WHERE DI.id_atencion = $id_atencion
";
$paciente = $conexion->query($queryPaciente)->fetch_assoc();

if (!$paciente) {
    echo "<div class='alert alert-danger'>No se encontró información del paciente.</div>";
    exit;
}

$rolPermitido = ($usuario['id_rol'] == 5 || $usuario['id_rol'] == 12);
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

    <style>
        * { box-sizing: border-box; }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
            color: #e0e0e0 !important;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                    radial-gradient(circle at 20% 50%, rgba(64,224,255,0.02) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(100,181,246,0.02) 0%, transparent 50%);
            z-index: 0;
            pointer-events: none;
        }

        .content-wrapper,
        section.content { background: transparent !important; }

        /* Sidebar mejorado */
        .main-sidebar {
            background: linear-gradient(180deg, #16213e, #0f3460) !important;
        }

        .sidebar-menu > li > a { color: #ffffff !important; }
        .sidebar-menu > li > a:hover {
            background: rgba(100,181,246,0.1) !important;
            color: #64b5f6 !important;
        }

        .treeview-menu {
            background: rgba(15, 52, 96, 0.5) !important;
            border-left: 2px solid #64b5f6;
        }

        .treeview-menu > li > a {
            font-size: 14px;
            padding: 10px 20px;
            color: #ffffff !important;
        }

        .treeview-menu > li > a:hover {
            background: rgba(100,181,246,0.1) !important;
            color: #64b5f6 !important;
        }

        /* Sección título */
        .titulo-seccion {
            background:#2b2d7f;
            color:white;
            padding:10px;
            font-size:22px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .btn-doc { font-size: 28px; }

        .content { padding: 0; }

        /* Contenedor documentos */
        .doc-card {
            background: linear-gradient(135deg, rgba(15,52,96,0.8), rgba(22,33,62,0.8));
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(100,181,246,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }

        .doc-card p {
            color: #e0e0e0 !important;
            font-size: 1rem;
        }

        .doc-card a.btn {
            transition: transform 0.2s ease;
        }

        .doc-card a.btn:hover {
            transform: scale(1.08);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: rgba(22,33,62,0.5); }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #64b5f6, #42a5f5);
            border-radius: 5px;
        }
    </style>

    <!-- Corrección apertura de treeview del sidebar -->
    <script>
        $(document).ready(function () {
            $('.treeview > a').on('click', function (e) {
                e.preventDefault();
                let parent = $(this).parent();
                let submenu = parent.find('.treeview-menu').first();

                parent.toggleClass('menu-open');
                submenu.slideToggle(200);
            });
        });
    </script>

</head>


<body>
<section class="content container-fluid">

    <div class="container mt-3">

        <center>
            <a href="../../template/menu_enfermera.php" class="btn btn-danger btn-sm">
                REGRESAR...
            </a>
        </center>

        <div class="titulo-seccion mt-3">
            <center><strong>IMPRESIÓN DE DOCUMENTOS (NOTAS DE ENFERMERÍA)</strong></center>
        </div>

        <p><strong>EXPEDIENTE:</strong> <?= $paciente['Id_exp']; ?></p>
        <p><strong>NOMBRE:</strong> <?= $paciente['nom_pac']." ".$paciente['papell']." ".$paciente['sapell']; ?></p>

        <!-- Caja de documentos -->
        <div class="doc-card mt-4">

            <h4><strong>15. Enfermería</strong></h4>

            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_regclin.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.00 Registro clínico
            </p>

            <p>
                <a class="btn btn-danger btn-sm"
                   href="../registro_quirurgico/pdf_cirugia_segura.php?id=<?= $paciente['id_atencion'] ?>&id_exp=<?= $paciente['Id_exp'] ?>"
                   target="_blank">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.01 Tratamiento
            </p>

            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_quir_area.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.02 Cirugía segura
            </p>

            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_pediatria.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.03 Quirófano
            </p>

            <p>
                <a class="btn btn-danger btn-sm" href="../vistas_doc/vista_transpdf.php">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.06 Pediátrico / Neonatal
            </p>

            <p>
                <a class="btn btn-danger btn-sm"
                   href="../registro_quirurgico/pdf_quirpiezas.php?id_atencion=<?= $paciente['id_atencion'] ?>&id_exp=<?= $paciente['Id_exp'] ?>"
                   target="_blank">
                    <span class="fa fa-file-pdf-o btn-doc"></span>
                </a>
                15.07 Transfusión sanguínea
            </p>

        </div>
    </div>

</section>
</body>
</html>
