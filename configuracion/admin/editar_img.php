<?php
session_start();
include "../../conexionbd.php";
include "../../configuracion/header_configuracion.php";

if( isset($_GET['id_simg'])) {
  $resultado = $conexion ->query("SELECT * FROM img_sistema WHERE id_simg=".$_GET['id_simg'])or die($conexion->error);
  if(mysqli_num_rows($resultado) > 0 ){
    $f=mysqli_fetch_row($resultado);
  }else{
    header("location: alta_usuarios.php");
  }
}else{
  header("location: alta_usuarios.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Imágenes - Sistema</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.magnific-popup.min.js"></script>
    <script src="../../js/aos.js"></script>
    <script src="../../js/main.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%) !important;
            font-family: 'Roboto', sans-serif !important;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(64, 224, 255, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(64, 224, 255, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container-fluid {
            position: relative;
            z-index: 1;
        }

        .thead {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            font-size: 20px !important;
            padding: 20px !important;
            border-radius: 15px 15px 0 0 !important;
            border: 2px solid #40E0FF !important;
            border-bottom: none !important;
            box-shadow: 0 5px 20px rgba(64, 224, 255, 0.3);
            text-shadow: 0 0 15px rgba(64, 224, 255, 0.5);
            letter-spacing: 2px;
            margin-top: 30px;
        }

        .modern-card {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #40E0FF !important;
            border-top: none !important;
            border-radius: 0 0 20px 20px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5),
                        0 0 30px rgba(64, 224, 255, 0.2) !important;
            padding: 40px 30px !important;
            margin-top: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(64, 224, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            pointer-events: none;
        }

        .row {
            position: relative;
            z-index: 1;
        }

        .col-sm-4 {
            margin-bottom: 25px;
        }

        label {
            color: #40E0FF !important;
            font-weight: 600 !important;
            margin-bottom: 10px !important;
            display: block;
            font-size: 1rem !important;
            letter-spacing: 0.5px;
            text-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
        }

        label i {
            margin-right: 8px;
            color: #40E0FF !important;
        }

        .form-control-file {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border: 2px solid #40E0FF !important;
            border-radius: 15px !important;
            padding: 15px 20px !important;
            font-size: 1rem !important;
            transition: all 0.3s ease !important;
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(64, 224, 255, 0.2) !important;
            width: 100%;
            cursor: pointer;
        }

        .form-control-file:hover {
            border-color: #00D9FF !important;
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            box-shadow: 0 5px 20px rgba(64, 224, 255, 0.3) !important;
        }

        .form-control-file:focus {
            border-color: #00D9FF !important;
            box-shadow: 0 6px 25px rgba(64, 224, 255, 0.4) !important;
            outline: none !important;
        }

        hr {
            border-top: 2px solid #40E0FF !important;
            margin: 30px 0 !important;
            box-shadow: 0 0 10px rgba(64, 224, 255, 0.3);
        }

        .button-container {
            text-align: center !important;
            margin-top: 35px !important;
            padding: 25px !important;
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-radius: 15px !important;
            border: 2px solid #40E0FF !important;
            box-shadow: 0 5px 20px rgba(64, 224, 255, 0.2);
        }

        .btn {
            padding: 14px 40px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 1.5px !important;
            transition: all 0.3s ease !important;
            border: 2px solid !important;
            margin: 0 10px !important;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3) !important;
            font-size: 1rem !important;
        }

        .btn-success {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border-color: #28a745 !important;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #28a745 !important;
            border-color: #20c997 !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 30px rgba(40, 167, 69, 0.5) !important;
        }

        .btn-danger {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            color: #ffffff !important;
            border-color: #dc3545 !important;
            text-decoration: none !important;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            color: #dc3545 !important;
            border-color: #e74c3c !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 30px rgba(220, 53, 69, 0.5) !important;
            text-decoration: none !important;
        }

        .alert-success {
            background: linear-gradient(135deg, #16213e 0%, #0f3460 100%) !important;
            border: 2px solid #28a745 !important;
            color: #ffffff !important;
            border-radius: 15px !important;
            padding: 20px !important;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3) !important;
            margin: 20px 0 !important;
        }

        .alert-success i {
            color: #28a745 !important;
            margin-right: 10px;
        }

        .main-footer {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%) !important;
            border-top: 2px solid #40E0FF !important;
            color: #ffffff !important;
            box-shadow: 0 -4px 20px rgba(64, 224, 255, 0.2);
            margin-top: 50px;
        }

        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
            border-left: 1px solid #40E0FF;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #40E0FF 0%, #0f3460 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #00D9FF 0%, #40E0FF 100%);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-card {
            animation: fadeInUp 0.6s ease-out;
        }

        @media screen and (max-width: 768px) {
            .thead {
                font-size: 16px !important;
                padding: 15px !important;
            }

            .modern-card {
                padding: 20px 15px !important;
            }

            .btn {
                padding: 12px 25px !important;
                font-size: 0.9rem !important;
                margin: 5px !important;
            }

            .button-container {
                padding: 15px !important;
            }
        }

        @media screen and (max-width: 576px) {
            .btn {
                display: block !important;
                width: 100% !important;
                margin: 10px 0 !important;
            }
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="thead">
                <tr><strong><center>EDITAR IMAGENES</center></strong></tr>
            </div>

            <div class="modern-card">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="img_base"><i class="fas fa-image"></i> Imagen del perfil</label>
                            <input type="file" class="form-control-file" id="img_base" name="img_base">
                        </div>

                        <div class="col-sm-4">
                            <label for="img_ipdf"><i class="fas fa-image"></i> Imagen izquierda de pdf</label>
                            <input type="file" class="form-control-file" id="img_ipdf" name="img_ipdf">
                        </div>

                        <div class="col-sm-4">
                            <label for="img_cpdf"><i class="fas fa-image"></i> Imagen central de pdf</label>
                            <input type="file" class="form-control-file" id="img_cpdf" name="img_cpdf">
                        </div>

                        <div class="col-sm-4">
                            <label for="img_dpdf"><i class="fas fa-image"></i> Imagen derecha de pdf</label>
                            <input type="file" class="form-control-file" id="img_dpdf" name="img_dpdf">
                        </div>

                        <div class="col-sm-4">
                            <label for="img_cuerpo"><i class="fas fa-image"></i> Imagen del body (cuerpo)</label>
                            <input type="file" class="form-control-file" id="img_cuerpo" name="img_cuerpo">
                        </div>
                    </div>

                    <hr>

                    <div class="button-container">
                        <button type="submit" name="editar" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="imgsistema.php" class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

<?php
if (isset($_POST['editar'])) {
    //imagen1 EDITAR
    if($_FILES['img_base']['name']!=''){
        $nombr = $_FILES['img_base']['name'];
        $carpeta="img/";
        $temp=explode('.' ,$nombr);
        $extension= end($temp);
        $img=time().'.'.$extension;

        if($extension=='jpg' || $extension=='png' || $extension=='jpeg'|| $extension=='PNG' || $extension=='JPEG' || $extension=='JPG'){
            if(move_uploaded_file($_FILES['img_base']['tmp_name'], $carpeta.$img)){
                $fila= $conexion->query('select img_base from img_sistema where id_simg='.$_GET['id_simg']);
                $id=mysqli_fetch_row($fila);
                if(file_exists('img/'.$id[0])){
                    unlink('img/'.$id[0]);
                }
                $conexion->query("update img_sistema set img_base='".$img."' where id_simg=".$_GET['id_simg']);
            }
        }
    }

    //img2 EDITAR
    if($_FILES['img_ipdf']['name']!=''){
        $nombrf = $_FILES['img_ipdf']['name'];
        $carpetaf="img2/";
        $tempf=explode('.' ,$nombrf);
        $extensionf= end($tempf);
        $imgf=time().'.'.$extensionf;

        if($extensionf=='jpg' || $extensionf=='png' || $extensionf=='jpeg' || $extensionf=='JPG' || $extensionf=='PNG' || $extensionf=='JPEG'){
            if(move_uploaded_file($_FILES['img_ipdf']['tmp_name'], $carpetaf.$imgf)){
                $filaf= $conexion->query('select img_ipdf from img_sistema where id_simg='.$_GET['id_simg']);
                $id=mysqli_fetch_row($filaf);
                if(file_exists('img2/'.$id[0])){
                    unlink('img2/'.$id[0]);
                }
                $conexion->query("update img_sistema set img_ipdf='".$imgf."' where id_simg=".$_GET['id_simg']);
            }
        }
    }

    //imagen3
    if($_FILES['img_cpdf']['name']!=''){
        $nombrff = $_FILES['img_cpdf']['name'];
        $carpetaff="img3/";
        $tempff=explode('.' ,$nombrff);
        $extensionfc= end($tempff);
        $imgfc=time().'.'.$extensionfc;

        if($extensionfc=='jpg' || $extensionfc=='png' || $extensionfc=='jpeg' || $extensionfc=='JPG' || $extensionfc=='PNG' || $extensionfc=='JPEG'){
            if(move_uploaded_file($_FILES['img_cpdf']['tmp_name'], $carpetaff.$imgfc)){
                $filafa= $conexion->query('select img_cpdf from img_sistema where id_simg='.$_GET['id_simg']);
                $ida=mysqli_fetch_row($filafa);
                if(file_exists('img3/'.$ida[0])){
                    unlink('img3/'.$ida[0]);
                }
                $conexion->query("update img_sistema set img_cpdf='".$imgfc."' where id_simg=".$_GET['id_simg']);
            }
        }
    }

    //imagen4derecha
    if($_FILES['img_dpdf']['name']!=''){
        $nombrff = $_FILES['img_dpdf']['name'];
        $carpetaff="img4/";
        $tempff=explode('.' ,$nombrff);
        $extensionfc= end($tempff);
        $imgfc=time().'.'.$extensionfc;

        if($extensionfc=='jpg' || $extensionfc=='png' || $extensionfc=='jpeg' || $extensionfc=='JPG' || $extensionfc=='PNG' || $extensionfc=='JPEG'){
            if(move_uploaded_file($_FILES['img_dpdf']['tmp_name'], $carpetaff.$imgfc)){
                $filafa= $conexion->query('select img_dpdf from img_sistema where id_simg='.$_GET['id_simg']);
                $ida=mysqli_fetch_row($filafa);
                if(file_exists('img4/'.$ida[0])){
                    unlink('img4/'.$ida[0]);
                }
                $conexion->query("update img_sistema set img_dpdf='".$imgfc."' where id_simg=".$_GET['id_simg']);
            }
        }
    }

    //imagen5body
    if($_FILES['img_cuerpo']['name']!=''){
        $nombrff = $_FILES['img_cuerpo']['name'];
        $carpetaff="img5/";
        $tempff=explode('.' ,$nombrff);
        $extensionfc= end($tempff);
        $imgfc=time().'.'.$extensionfc;

        if($extensionfc=='jpg' || $extensionfc=='png' || $extensionfc=='jpeg' || $extensionfc=='JPG' || $extensionfc=='PNG' || $extensionfc=='JPEG'){
            if(move_uploaded_file($_FILES['img_cuerpo']['tmp_name'], $carpetaff.$imgfc)){
                $filafa= $conexion->query('select img_cuerpo from img_sistema where id_simg='.$_GET['id_simg']);
                $ida=mysqli_fetch_row($filafa);
                if(file_exists('img5/'.$ida[0])){
                    unlink('img5/'.$ida[0]);
                }
                $conexion->query("update img_sistema set img_cuerpo='".$imgfc."' where id_simg=".$_GET['id_simg']);
            }
        }
    }

    echo "<p class='alert alert-success' id='mensaje'> <i class=' fa fa-check'></i> Dato insertado correctamente...</p>";
    echo '<script type="text/javascript">window.location ="imgsistema.php"</script>';
}
?>

<footer class="main-footer">
    <?php include("../../template/footer.php"); ?>
</footer>

<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>
</body>
</html>
