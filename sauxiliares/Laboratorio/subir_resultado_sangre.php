<?php
session_start();
include "../../conexionbd.php";


$resultado = $conexion->query("select paciente.*, dat_ingreso.id_atencion, triage.id_triage
from paciente 
inner join dat_ingreso on paciente.Id_exp=dat_ingreso.Id_exp
inner join triage on dat_ingreso.id_atencion=triage.id_atencion where id_triage=id_triage
") or die($conexion->error);

$usuario = $_SESSION['login'];


if ($usuario['id_rol'] == 10) {
    include "../header_labo.php";

} else if ($usuario['id_rol'] == 4 or 5) {
    include "../header_labo.php";
} else {
    //session_unset();
    // session_destroy();
    echo "<script>window.Location='../../index.php';</script>";

}


?>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    <!--  Bootstrap  -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

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

</head>

<div>
    <section class="content container-fluid">
        <div class="container box">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <h1>Información de Transfusión Sanguínea</h1>
                        <?php
                        $id = $_GET['not_id'];
                        $id_atencion = $_GET['id_atencion'];
                        $sql = "SELECT * FROM notificaciones_labo where realizado = 'NO'";
                        $result = $conexion->query($sql);

                        $sql_sangre = "SELECT * FROM paciente p, dat_ingreso d where d.id_atencion=$id_atencion and d.Id_exp=p.Id_exp";
                        $result_sangre = $conexion->query($sql_sangre);
                        while ($row=$result_sangre->fetch_assoc()) {
                            $id_exp=$row['Id_exp'];
                            $tipo_sangre=$row['tip_san'];
                        }
                        ?>
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Observaciones</label>
                                <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></button></i>
</div>
<textarea name="observacion" class="form-control" id="observacion" rows="3"></textarea>
<script type="text/javascript">
const btnStartRecord = document.getElementById('btnStartRecord');
const btnStopRecord = document.getElementById('btnStopRecord');
const observacion = document.getElementById('observacion');

     let recognition = new webkitSpeechRecognition();
      recognition.lang = "es-ES";
      recognition.continuous = true;
      recognition.interimResults = false;

      recognition.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        observacion.value += frase;
      }

      btnStartRecord.addEventListener('click', () => {
        recognition.start();
      });

      btnStopRecord.addEventListener('click', () => {
        recognition.abort();
      });
</script>
                            </div>
                       
                <div class="col-sm-4">
                    <label for="">TIPO DE SANGRE:</label>
                    <select id="sangre" class="form-control" name="sangre" required>
                         <option value="<?php echo $tipo_sangre ?>"><?php echo $tipo_sangre ?></option>
                         <option></option>
                         <option value="">SELECCIONAR</option>
                         <option value="O Rh(-)">O Rh(-)</option>
                         <option value="O Rh(+)">O Rh(+)</option>
                         <option value="A Rh(-)">A Rh(-)</option>
                         <option value="A Rh(+)">A Rh(+)</option>
                         <option value="B Rh(-)">B Rh(-)</option>
                         <option value="B Rh(+)">B Rh(+)</option>
                         <option value="AB Rh(-)">AB Rh(-)</option>
                         <option value="AB Rh(+)">AB Rh(+)</option>
                     </select>
                </div>

                    <br>
                    <hr>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">&nbsp;</label>
                        <div class="col-sm-12">
                            <a href="../../template/menu_laboratorio.php" class="btn btn-danger">Cancelar</a>
                            <input type="submit" name="edit" class="btn btn-success" value="Guardar Datos">
                        </div>
                    </div>

                    </form>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
        <?php
date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d H:i:s");
        if (isset($_POST['edit'])) {
           // $terminado = mysqli_real_escape_string($conexion, (strip_tags($_POST["realizado"], ENT_QUOTES))); //Escanpando caracteres
            $observacion = mysqli_real_escape_string($conexion, (strip_tags($_POST["observacion"], ENT_QUOTES))); //Escanpando caracteres
if (isset($_POST["sangre"])) {
    $sangre = mysqli_real_escape_string($conexion, (strip_tags($_POST["sangre"], ENT_QUOTES))); //Escanpando 
    $update_sangre="UPDATE paciente SET tip_san='$sangre' where Id_exp=$id_exp";
    $resultupdate=$conexion->query($update_sangre);
}else{
    $select_idexp="SELECT * FROM dat_ingreso d, paciente p where d.id_atencion=$id_atencion and d.Id_exp=p.Id_exp";
    $result_id=$conexion->query($select_idexp);
    while ($row_id=$result_id->fetch_assoc()) {
        $id_exp=$row_id['Id_exp'];
        $tip_san=$row_id['tip_san'];
    }
    $update_sangre="UPDATE paciente SET tip_san='$tip_san' where Id_exp=$id_exp";
    $resultupdate=$conexion->query($update_sangre);
}


  $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];

                $sql2 = "UPDATE notificaciones_sangre SET realizado = 'SI',resultado = '$observacion' ,fecha_resul = '$fecha_actual' , id_usua_resul ='$id_usua' WHERE not_id = $id";
                //  echo $sql2;
                //  return 'hbgk';
                $result = $conexion->query($sql2);
                echo '<script type="text/javascript">window.location ="resultados_sangre.php"</script>';



        }
        ?>
</div>
</section>
</div>
</div>


<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>

</body>

</html>