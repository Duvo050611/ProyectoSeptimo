<?php
  $lifetime=36000;
  session_set_cookie_params($lifetime);
session_start();
//include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];


?>
<!DOCTYPE html>
<html>

<head>
    
  <!--buscador select-->
      <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
 
    <!---
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>





    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
    <!--multiselect-->

    <script src="librerias/alertifyjs/alertify.js"></script>

    <script>
        // Write on keyup event of keyword input element
        $(document).ready(function () {
            $("#search").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevo").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#example tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoS").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleS tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoI").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleI tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoME").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleME tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoMEDC").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleMEDC tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoMAT").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleMAT tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoMATC").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleMATC tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_nuevoE").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#exampleE tbody tr"), function () {
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
        $(document).ready(function () {
            $("#search_dep").keyup(function () {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function () {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
    </script>
    <title>REGISTRO CLINICO QUIRÚRGICO</title>
    <style>
        hr.new4 {
            border: 1px solid red;
        }
        #im{
margin-left: 178px;

}

html,body{
  overflow-x: scroll;
   height:10px;
  width:102%;

}

#uno{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 268px; left: 342px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

 #contenido1{
position: absolute;
top: 264px; left: 349px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
    outline: none;
border:1px solid steelblue;
}


#dos{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 245px; left: 353px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

 #contenido2{
position: absolute;
top: 240px; left: 360px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#tres{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 138px; left: 352px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#contenido3{
position: absolute;
top: 136px; left: 357px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#cuatro{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 88px; left: 359px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#contenido4{
position: absolute;
top: 83px; left: 366px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#cin{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 48px; left: 343px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#contenido5{
position: absolute;
top: 43px; left: 350px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#se{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 37px; left: 330px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#contenido6{
position: absolute;
top: 33px; left: 244px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#sie{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 217px; left: 244px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#contenido7{
position: absolute;
top: 210px; left: 156px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


/*ESPALDA*/
#oc{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 190px; left: 795px;  
padding: 1px 1px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#contenido8{
position: absolute;
top: 186px; left: 805px;  
 font-size: 10px;
background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#cero{
position: absolute;
top: -197px; left: 145px;  
 font-size: 20px;
}

#doso{
position: absolute;
top: -197px; left: 275px;  
 font-size: 20px;
}

#cuatroc{
position: absolute;
top: -197px; left: 412px;  
 font-size: 20px;
}

#seis{
position: absolute;
top: -197px; left: 543px;  
 font-size: 20px;
}

#ocho{
position: absolute;
top: -197px; left: 680px;  
 font-size: 20px;
}
#diez{
position: absolute;
top: -197px; left: 802px;  
 font-size: 20px;
}

#pi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 504px; left: 304px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipi{
position: absolute;
top: 500px; left: 216px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#pid{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 504px; left: 380px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipid{
position: absolute;
top: 501px; left: 390px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#toi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 482px; left: 312px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#tobi{
position: absolute;
top: 475px; left: 223px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#to{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 482px; left: 372px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#tobd{
position: absolute;
top: 476px; left: 380px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#roi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 370px; left: 308px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iroi{
position: absolute;
top: 366px; left: 223px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#brod{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 370px; left: 376px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#irod{
position: absolute;
top: 366px; left: 384px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#mui{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 314px; left: 306px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imi{
position: absolute;
top: 309px; left: 224px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#mud{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 310px; left: 377px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imd{
position: absolute;
top: 305px; left: 381px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#ingi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 245px; left: 331px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ingli{
position: absolute;
top: 240px; left: 248px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#domen{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left: 342px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#idomen{
position: absolute;
top: 209px; left: 346px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#ddoi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 254px; left: 202px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddi{
position: absolute;
top: 250px; left: 118px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#ddoid{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 278px; left: 210px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddid{
position: absolute;
top: 272px; left: 125px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#ditr{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 295px; left: 214px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iditr{
position: absolute;
top:298px; left:133px;  
 font-size: 10px;
 background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#dic{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 293px; left: 223px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#idic{
position: absolute;
top:295px; left:227px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#dicin{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 282px; left: 231px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#idicin{
position: absolute;
top:277px; left:236px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bddu{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 254px; left: 482px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddu{
position: absolute;
top:248px; left:485px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bpmai{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 254px; left: 227px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipmai{
position: absolute;
top:249px; left:230px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#bmuñi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 235px; left: 236px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imuñi{
position: absolute;
top:230px; left:152px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bbri{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 172px; left: 262px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ibri{
position: absolute;
top:168px; left:180px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bbric{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 140px; left: 276px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ibric{
position: absolute;
top:135px; left:192px;  
 font-size: 10px;
    background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bhomi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 110px; left: 276px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ihomi{
position: absolute;
top:105px; left:192px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bcpi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 138px; left: 333px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icpi{
position: absolute;
top:150px; left:279px;  
 font-size: 10px;
  background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}


#bcpei{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 125px; left: 315px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icpei{
position: absolute;
top:120px; left:235px;  
 font-size: 10px;
   background-color: transparent;
  border: 0;
   font-weight: bold;
   outline: none;
border:1px solid steelblue;
}

#bcped{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 125px; left: 367px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icped{
position: absolute;
top:120px; left:370px;  
 font-size: 10px;
   font-weight: bold;
  background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcvi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 88px; left: 322px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icvi{
position: absolute;
top:82.5px; left:240px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#bddos{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 281px; left: 478px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddos{
position: absolute;
top:276px; left:483px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#bddt{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 293px; left: 471px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddt{
position: absolute;
top:295px; left:476px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bddc{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 290px; left: 462px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddc{
position: absolute;
top:285px; left:377px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bddcinco{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 275px; left: 453px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iddcinco{
position: absolute;
top:272px; left:368px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bpalmad{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 254px; left: 457px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipalmad{
position: absolute;
top:249px; left:374px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmund{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 236px; left: 452px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imund{
position: absolute;
top:231px; left:456px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#bdbr{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left: 440px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#idbr{
position: absolute;
top:210px; left:448px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#babd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 170px; left: 420px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ianbd{
position: absolute;
top:165px; left:430px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcder{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 133px; left: 413px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icder{
position: absolute;
top:128px; left:420px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bhder{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 108px; left: 410px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ihder{
position: absolute;
top:103px; left:414px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmand{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 58px; left: 359px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imand{
position: absolute;
top:53px; left:365px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#bmanc{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 67px; left: 343px;  
padding: 0px 0px;
font-size: 8.5px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imanc{
position: absolute;
top:66px; left:348px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmaniz{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 58px; left: 328px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imaniz{
position: absolute;
top:54px; left:242px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmejd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 37px; left: 357px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imejd{
position: absolute;
top:32px; left:363px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bnariz{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 25px; left: 343px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inariz{
position: absolute;
top:17px; left:349px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bfrentei{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 10px; left: 335px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ifrentei{
position: absolute;
top:6px; left:248px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bfrented{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 10px; left: 351px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ifrented{
position: absolute;
top:5px; left:355px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#nuevo01{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 214px; left: 372px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo1{
position: absolute;
top:225px; left:370px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#nuevo02{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left: 312px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo2{
position: absolute;
top:225px; left:255px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#nuevo03{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 188px; left: 310px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo3{
position: absolute;
top:197px; left:255px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#nuevo04{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 188px; left: 342px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo4{
position: absolute;
top:197px; left:310px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#nuevo05{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 188px; left: 373px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo5{
position: absolute;
top:197px; left:370px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#nuevo06{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 162px; left: 373px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo6{
position: absolute;
top:168px; left:350px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#nuevo07{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 162px; left: 343px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo7{
position: absolute;
top:168px; left:310px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#nuevo08{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 162px; left: 310px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#inuevo8{
position: absolute;
top:168px; left:267px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}



#bppi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 510px; left: 758px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ippi{
position: absolute;
top:505px; left:673px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bppd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 510px; left: 830px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ippd{
position: absolute;
top:505px; left:839px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#btia{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 480px; left: 765px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#itia{
position: absolute;
top:475px; left:680px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#btda{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 480px; left: 825px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#itda{
position: absolute;
top:475px; left:830px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bpani{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 427px; left: 762px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipani{
position: absolute;
top:422px; left:680px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bpand{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 427px; left: 827px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipand{
position: absolute;
top:422px; left:835px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bpchi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 375px; left: 760px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipchi{
position: absolute;
top:370px; left:675px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bpchd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 375px; left: 830px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ipchd{
position: absolute;
top:370px; left:840px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmusai{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 325px; left: 765px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imusai{
position: absolute;
top:320px; left:680px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmusad{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 325px; left: 823px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imusad{
position: absolute;
top:320px; left:830px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bglui{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 255px; left: 770px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iglui{
position: absolute;
top:250px; left:685px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bglud{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 255px; left: 817px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iglud{
position: absolute;
top:250px; left:825px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcini{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left: 774px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icini{
position: absolute;
top:210px; left:692px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcind{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left: 813px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icind{
position: absolute;
top:210px; left:819px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcosi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 160px; left: 775px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icosi{
position: absolute;
top:155px; left:690px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#bcosd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 160px; left: 815px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icosd{
position: absolute;
top:155px; left:820px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#besai{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 119px; left: 770px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iesai{
position: absolute;
top:115px; left:685px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#besad{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 119px; left: 818px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iesad{
position: absolute;
top:115px; left:825px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#besalt{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 85px; left: 795px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iesalt{
position: absolute;
top:80px; left:800px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#bdorsali{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 257px; left: 682px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#idorsali{
position: absolute;
top:252px; left:598px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bdorsald{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 257px; left: 910px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#idorsald{
position: absolute;
top:252px; left:916px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmuneati{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 238px; left: 685px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imuneati{
position: absolute;
top:233px; left:600px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bmuneatd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 238px; left: 902px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#imuneatd{
position: absolute;
top:233px; left:910px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#banbei{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 220px; left: 698px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ianbei{
position: absolute;
top:215px; left:610px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#banbed{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 220px; left: 892px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ianbed{
position: absolute;
top:215px; left:900px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bccodoi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 185px; left:715px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iccodoi{
position: absolute;
top:180px; left:629px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bccodod{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 185px; left:875px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#iccodod{
position: absolute;
top:180px; left:880px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bbaiz{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 154px; left:716px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ibaiz{
position: absolute;
top:150px; left:630px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bbader{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 154px; left:874px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#ibader{
position: absolute;
top:150px; left:882px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcbajo{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 60px; left:795px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icbajo{
position: absolute;
top:55px; left:805px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcmedio{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 45px; left:795px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icmedio{
position: absolute;
top:40px; left:805px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcabezamedio{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 30px; left:795px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icabezamedio{
position: absolute;
top:25px; left:805px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcabaiz{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 18px; left:780px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icabaiz{
position: absolute;
top:14px; left:695px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#bcabader{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 18px; left:810px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#icabader{
position: absolute;
top:13px; left:816px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


#espi{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 430px; left:310px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#cssespi{
position: absolute;
top:425px; left:210px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#espd{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 430px; left:375px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#cssespd{
position: absolute;
top:425px; left:390px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}

#coxis{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 240px; left:795px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}

#csscoxis{
position: absolute;
top:235px; left:710px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
  outline: none;
border:1px solid steelblue;
}


    </style>
    
    
    <!--ESTILO Y SCRIPT DE PRUEBA -->
     <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />
  <title>CONTROL DE CATÉTERES</title>
  <style type="text/css">
    .btnAdd {
      text-align: right;
      width: 83%;
      margin-bottom: 20px;
    }
   
    .modal-backdrop{
 position: relative;
  width:101%;
   height:101%;
}
}
  </style>
</head>

<body>
<section class="content container-fluid">

    <?php

    include "../../conexionbd.php";

    if (isset($_SESSION['pac'])) {
      $id_atencion = $_SESSION['pac'];

      $sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias, p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

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
        $folio = $row_pac['folio'];
      }

      $sql_pac = "SELECT * FROM  dat_ingreso WHERE id_atencion =$id_atencion";

          $result_pac = $conexion->query($sql_pac);

          while ($row_pac = $result_pac->fetch_assoc()) {
            $fingreso = $row_pac['fecha'];
             $fegreso = $row_pac['fec_egreso'];
             $alta_med = $row_pac['alta_med'];
             $alta_adm = $row_pac['alta_adm'];
             $activo = $row_pac['activo'];
             $valida = $row_pac['valida'];
          }

if($alta_med=='SI' && $alta_adm=='SI' && $activo=='NO' && $valida=='SI'){
    
    $sql_est = "SELECT DATEDIFF('$fegreso', '$fingreso') as estancia FROM dat_ingreso where id_atencion = $id_atencion";

      $result_est = $conexion->query($sql_est);

      while ($row_est = $result_est->fetch_assoc()) {
           $estancia = $row_est['estancia'];
       
      }
}else{
    
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
}

      ///inicio bisiesto
function bisiesto($anio_actual){
    $bisiesto=false;
    //probamos si el mes de febrero del año actual tiene 29 días
      if (checkdate(2,29,$anio_actual))
      {
        $bisiesto=true;
    }
    return $bisiesto;
}

//date_default_timezone_set('America/Guatemala');
$fecha_actual = date("Y-m-d");
$fecha_nac=$pac_fecnac;
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
           case 10:     $dias_mes_anterior=30; break;
           case 11:     $dias_mes_anterior=31; break;
           case 12:     $dias_mes_anterior=30; break;
    }

    $dias=$dias + $dias_mes_anterior;
}

//ajuste de posible negativo en $meses
if ($meses < 0)
{
    --$anos;
    $meses=$meses + 12;
}

//echo "<br>Tu edad es: $anos años con $meses meses y $dias días";

    ?>
      <div class="container">
        <div class="content">
         <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO QUIRÚRGICO - ENFERMERÍA</center></strong>
        </div>
         <hr>
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm-6">

      Expediente: <strong><?php echo $folio?> </strong>
    
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm" id="h">
      Área: <strong><?php echo $area ?></strong>
    </div>
   <?php $date = date_create($pac_fecing);
   ?>
      <div class="col-sm">
      Fecha de ingreso: <strong><?php echo date_format($date, "d-m-Y") ?></strong>
    </div>
  </div>
</div></font>

 <font size="2">
     <div class="container">
  <div class="row">
    <div class="col-sm">
       <?php $date1 = date_create($pac_fecnac);
   ?>
      Fecha de nacimiento: <strong><?php echo date_format($date1, "d-m-Y") ?></strong>
    </div>
    <div class="col-sm">
      Tipo de sangre: <strong><?php echo $pac_tip_sang ?></strong>
    </div>
   
      <div class="col-sm">
      Habitación: <strong><?php $sql_hab = "SELECT num_cama from cat_camas where id_atencion =$id_atencion";
$result_hab = $conexion->query($sql_hab);                                                                                    while ($row_hab = $result_hab->fetch_assoc()) {
  echo $row_hab['num_cama'];
} ?></strong>
    </div>
    <div class="col-sm">
      Tiempo estancia: <strong><?php echo $estancia ?> Dias</strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
   <div class="col-sm-3">
      Edad: <strong><?php if($anos > "0" ){
   echo $anos." años";
}elseif($anos <="0" && $meses>"0"){
    echo $meses." meses";
}elseif($anos <="0" && $meses<="0" && $dias>"0"){
    echo $dias." dias";
}
?></strong>
    </div>
    <div class="col-sm-3">

      Peso: <strong><?php $sql_vit = "SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
      
$result_vit = $conexion->query($sql_vit);                                                                                    while ($row_vit = $result_vit->fetch_assoc()) {
    $peso=$row_vit['peso'];

} if (!isset($peso)){
    $peso=0;
   
}   echo $peso;?></strong>
    </div>
  
      <div class="col-sm">
      Talla: <strong><?php $sql_vitt =" SELECT * from dat_hclinica where Id_exp=$id_exp ORDER by id_hc DESC LIMIT 1";
$result_vitt = $conexion->query($sql_vitt);                                                                                    while ($row_vitt = $result_vitt->fetch_assoc()) {
    $talla=$row_vitt['talla'];
 
} if (!isset($talla)){
    
    $talla=0;
}   echo $talla;?></strong>
    </div>
<div class="col-sm">
      Género: <strong><?php echo $pac_sexo ?></strong>
    </div>
     
  </div>
</div>
</font>
 <font size="2">
  <div class="container">
  <div class="row">
     <div class="col-sm-3">
      Alergias: <strong><?php echo $alergias ?></strong>
    </div>
    <div class="col-sm-6">
      Estado de salud: <strong><?php $sql_edo = "SELECT edo_salud from dat_ingreso where id_atencion=$id_atencion ORDER by edo_salud ASC LIMIT 1";
$result_edo = $conexion->query($sql_edo);                                                                                    while ($row_edo = $result_edo->fetch_assoc()) {
  echo $row_edo['edo_salud'];
} ?></strong>
    </div>
      
     <div class="col-sm">
      <label class="control-label">Aseguradora: </label><strong>  &nbsp; 
            <?php   $sql_aseg = "SELECT aseg from dat_ingreso where id_atencion =$id_atencion";
                    $result_aseg = $conexion->query($sql_aseg);
                    while ($row_aseg = $result_aseg->fetch_assoc()) {
                          echo $row_aseg['aseg'];
                          $at=$row_aseg['aseg'];
                    }
                    
                    $resultadot = $conexion ->query("SELECT tip_precio FROM cat_aseg WHERE aseg='$at'")or die($conexion->error);
                    while($filat = mysqli_fetch_array($resultadot)){ 
                        $tr=$filat["tip_precio"];
                    echo ' '.$tr;
                    } ?></strong>
    </div>
  </div>
</div>
</font>
 <font size="2">
<div class="container">
  <div class="row">
   
   <div class="col-sm-4">
 <?php 
$d="";
      $sql_motd = "SELECT diagprob_i from dat_nevol where id_atencion=$id_atencion ORDER by diagprob_i ASC LIMIT 1";
    $result_motd = $conexion->query($sql_motd);
    while ($row_motd = $result_motd->fetch_assoc()) {
        $d=$row_motd['diagprob_i'];
    } ?>
    <?php $sql_mot = "SELECT motivo_atn from dat_ingreso where id_atencion=$id_atencion ORDER by motivo_atn ASC LIMIT 1";
    $result_mot = $conexion->query($sql_mot);
    while ($row_mot = $result_mot->fetch_assoc()) {
    $m=$row_mot['motivo_atn'];
    } ?>

    <?php if ($d!=null) {
       echo '<td> Diagnóstico: <strong>' . $d .'</strong></td>';
    } else{
          echo '<td"> Motivo de atención: <strong>' . $m .'</strong></td>';
    }?>
    </div>
  </div>
</div></font>
<hr>
<div class="container-fluid" id="conp">

<div class="container">
  <div class="row">



    <!--<div>
      
    <a class="btn btn-outline-primary btn-sm active" href="vista_enf_quirurgico.php">NOTA</a>
    </div>-->
    <!--<div>&nbsp;
    <a class="btn btn-outline-primary btn-sm" href="nav_signos.php">SIGNOS VITALES</a>
    </div>-->
      <nav>
         <div id="barra">
         <?php
$resultado = $conexion->query("SELECT * FROM enf_quirurgico WHERE id_atencion=$id_atencion")or die($conexion -> error); 
while($fila = mysqli_fetch_array($resultado)){
    $id_quir=$fila['id_quir'];
}

$resultado = $conexion->query("SELECT * FROM recu WHERE id_atencion=$id_atencion")or die($conexion -> error); 
while($fila = mysqli_fetch_array($resultado)){
    $id_recu=$fila['id_recu'];
}

$resultado = $conexion->query("SELECT * FROM enf_posto WHERE id_atencion=$id_atencion")or die($conexion -> error); 
while($fila = mysqli_fetch_array($resultado)){
    $id_enf_post=$fila['id_enf_post'];
}
         ?>
         
         <?php 
         if(isset($id_quir) and isset($id_recu)and isset($id_enf_post)){ 
             ?>
             
            <a href="vista_enf_quirurgico2.php"> <button type="button" class="btn btn-success">Abrir segunda cirugia</button> </a><p></p>
          <?php   
         }
         ?>
         
         
         
         
         <?php 
         if(!isset($id_quir) and !isset($id_recu)and !isset($id_enf_post)){
         ?>
         <div class="container">
             <div class="row">
                 <div class="col-3">
          <font color="steelblue">Progreso de Notas quirúrgicas 0/3</font><p></p>
          <font color="red">Falta nota preoperatoria, transoperatoria y postoperatoria</font>
          </div>
           <div class="col-4">
          <img src="0.png" class="img-fluid" width="340">
          </div>
          </div>
          </div>
         <?php } else if(isset($id_quir) and !isset($id_recu)and !isset($id_enf_post)){ ?>
         <div class="container">
             <div class="row">
                 <div class="col-3">
          <font color="steelblue">Progreso de Notas quirúrgicas 1/3</font><p></p>
          <font color="red">Falta nota transoperatoria y postoperatoria</font>
          </div>
           <div class="col-4">
          <img src="33.png" class="img-fluid" width="340" >
          </div>
          </div>
          </div>
         <?php }else if(isset($id_quir) and isset($id_recu)and !isset($id_enf_post)){ ?>
         
         <div class="container">
             <div class="row">
                 <div class="col-3">
          <font color="steelblue">Progreso de Notas quirúrgicas 2/3</font><p></p>
           <font color="red">Falta nota postoperatoria</font>
          </div>
           <div class="col-4">
          <img src="66.png" class="img-fluid" width="340">
          </div>
          </div>
          </div>
        <?php } else if(isset($id_quir) and isset($id_recu)and isset($id_enf_post)){ ?>
          
         <div class="container">
             <div class="row">
                 <div class="col-3">
          <font color="green">Progreso de Notas quirúrgicas 3/3</font>
          </div>
           <div class="col-4">
             
          <img src="100.png" class="img-fluid" width="345">
          </div>
          </div>
          </div>
          
          <?php } ?>
         <p></p>
         </div>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
      
     <button class="nav-item btn btn-outline-primary btn-sm" id="nav-cir-tab" data-toggle="tab" href="#nav-cir" role="tab" aria-controls="nav-cir" aria-selected="true">CIRUGÍA SEGURA</button>&nbsp;&nbsp;
     
    <button class="nav-item btn btn-outline-primary btn-sm" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="false">NOTA PREOPERATORIA</button>&nbsp;&nbsp;
 
<button class="nav-item btn btn-outline-primary btn-sm" id="nav-recu-tab" data-toggle="tab" href="#nav-recu" role="tab" aria-controls="nav-recu" aria-selected="false">NOTA TRANSOPERATORIA</button>
&nbsp;&nbsp;
<button class="nav-item btn btn-outline-primary btn-sm" id="nav-control-tab" data-toggle="tab" href="#nav-control" role="tab" aria-controls="nav-control" aria-selected="false">CONTROL TEXTILES</button>
&nbsp;&nbsp;
<button class="nav-item btn btn-outline-primary btn-sm" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">SIGNOS VITALES</button>&nbsp;&nbsp;
   <!-- <button class="nav-item btn btn-outline-primary btn-sm" id="nav-ingresos-tab" data-toggle="tab" href="#nav-ingresos" role="tab" aria-controls="nav-ingresos" aria-selected="false">SOLUCIONES</button>&nbsp;&nbsp;-->
      <button class="nav-item btn btn-outline-primary btn-sm" id="nav-ing-tab" data-toggle="tab" href="#nav-ing" role="tab" aria-controls="nav-ing" aria-selected="false">INGRESOS / EGRESOS</button>
    
     <!--<button class="nav-item btn btn-outline-primary btn-sm" id="nav-eg-tab" data-toggle="tab" href="#nav-eg" role="tab" aria-controls="nav-eg" aria-selected="false">EGRESOS</button>&nbsp;&nbsp;-->
    
    


      &nbsp;&nbsp;
<button class="nav-item btn btn-outline-primary btn-sm" id="nav-prueba-tab" data-toggle="tab" href="#nav-prueba" role="tab" aria-controls="nav-prueba" aria-selected="false">CATÉTERES</button>

&nbsp;&nbsp;
<button class="nav-item btn btn-outline-primary btn-sm" id="nav-post-tab" data-toggle="tab" href="#nav-post" role="tab" aria-controls="nav-post" aria-selected="false">NOTA POSTOPERATORIA</button>

&nbsp;&nbsp;
<button class="nav-item btn btn-outline-primary btn-sm" id="nav-cuid-tab" data-toggle="tab" href="#nav-cuid" role="tab" aria-controls="nav-cuid" aria-selected="false">CUIDADOS</button>
  
    &nbsp;&nbsp;
   <!-- <a href="nav_med.php" class="btn btn-outline-primary  btn-sm">MEDICAMENTOS/EQUIPOS</a>-->
    <button class="nav-item btn btn-outline-primary btn-sm" id="nav-medicam-tab" data-toggle="tab" href="#nav-medicam" role="tab" aria-controls="nav-medicam" aria-selected="false">FARMACIA</button>
  
  &nbsp;&nbsp;
   <!-- <a href="nav_med.php" class="btn btn-outline-primary  btn-sm">MATERIALES</a>-->
    <button class="nav-item btn btn-outline-primary btn-sm" id="nav-med-tab" data-toggle="tab" href="#nav-med" role="tab" aria-controls="nav-med" aria-selected="false">CEYE</button>
  
  &nbsp;&nbsp;
    
    <button class="nav-item btn btn-outline-primary btn-sm" id="nav-eq-tab" data-toggle="tab" href="#nav-eq" role="tab" aria-controls="nav-eq" aria-selected="false">EQUIPOS</button>
  
  &nbsp;&nbsp;

   <!--  <a href="../../sauxiliares/Ceye/programacion_quir.php" class="btn btn-outline-success  btn-sm">AGENDA QUIRÚRGICA</a>
       &nbsp;&nbsp;-->
       
       
     <button class="nav-item btn btn-outline-primary btn-sm" id="nav-piso-tab" data-toggle="tab" href="#nav-piso" role="tab" aria-controls="nav-piso" aria-selected="false">PACIENTE A:</button>
 
  </div>


</nav>
    <!--<div>
    
    <a class="btn btn-outline-primary btn-sm" href="nav_textiles.php">CONTROL TEXTILES</a>
    </div>
    <div>
        &nbsp;
    <a class="btn btn-outline-primary   btn-sm" href="nav_cate.php">CATÉTERES</a>
    </div>-->
    <div><p></p>
      
    
    </div>

     <!--<div>
        &nbsp;
      <a href="nav_rec.php" class="btn btn-outline-primary  btn-sm">NOTA RECUPERACIÓN</a>
    </div><p>-->
    <!--<div>
         &nbsp;
      <a href="../ordenes_medico/ordenes_quir.php" class="btn btn-outline-primary  btn-sm">CUIDADOS </a>
    </div>-->
   <!-- <div>&nbsp;
      <a href="nav_med_rojo.php" class="btn btn-outline-danger  btn-sm">CARRO ROJO</a>
    </div>-->
    <div> <p></p> 
     
    </div>
  </div>
</div>
</div>

<div class="tab-content" id="nav-tabContent">
    
      <!-- PRUEBA NUEVO-->
    <div class="tab-pane fade" id="nav-prueba" role="tabpanel" aria-labelledby="nav-prueba-tab">
   
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>CONTROL DE CATÉTERES</center></strong>
</div><br>
        <div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success">Agregar nuevo catéter</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevo" placeholder="Buscar...">
            </div>
            <table id="example" class="table">
              <thead>
                <th>Id</th>
                <th>Dispositivos</th>
                <th>Calibre</th>
                <th>Fecha de instalación</th>
                <th>Instalo</th>
                <th>Observaciones</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
    

  
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_data.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [5]
          },

        ]
      });
    });
    $(document).on('submit', '#addUser', function(e) {
      e.preventDefault();
	var dispositivos = $('#adddispositivosField').val();
	 var tipo = $('#addtipoField').val();
	 var fecha_inst = $('#addfecha_instField').val();
      var instalo = $('#addinstaloField').val();
      var cultivo = $('#addcultivoField').val();
      
      if (dispositivos != '' && cultivo != '' && tipo != '' && instalo != '' && fecha_inst != '') {
        $.ajax({
          url: "add_user.php",
          type: "post",
          data: {
		    dispositivos: dispositivos,
            tipo: tipo,
            fecha_inst: fecha_inst,
            instalo: instalo,
            cultivo:cultivo
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#example').DataTable();
              mytable.draw();
              document.getElementById("addUser").reset();
              $('#addUserModal').modal('hide');
             alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUser', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
var dispositivos = $('#dispositivosField').val();
var tipo = $('#tipoField').val();
var fecha_inst = $('#fecha_instField').val();
      var instalo = $('#instaloField').val();
      var cultivo = $('#cultivoField').val();
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (dispositivos != '' && fecha_inst != '' && tipo != '' && instalo != '' && cultivo != '') {
        $.ajax({
          url: "update_user.php",
          type: "post",
          data: {
            dispositivos: dispositivos,
            tipo: tipo,
            fecha_inst: fecha_inst,
            instalo: instalo,
            cultivo: cultivo,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#example').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtn">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id, dispositivos, tipo, fecha_inst, instalo,cultivo, button]);
              $('#exampleModal').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#example').on('click', '.editbtn ', function(event) {
      var table = $('#example').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModal').modal('show');

      $.ajax({
        url: "get_single_data.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#dispositivosField').val(json.dispositivos);
          $('#tipoField').val(json.tipo);
          $('#fecha_instField').val(json.fecha_inst);
          $('#instaloField').val(json.instalo);
          $('#cultivoField').val(json.cultivo);

          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtn', function(event) {
      var table = $('#example').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Estas seguro de eliminar este registro? ? ")) {
        $.ajax({
          url: "delete_user.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUser">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            <div class="mb-3 row">
              <label for="cityField" class="col-md-3 form-label">Dispositivos</label>
              <div class="col-md-9">
                  
                  <select name="dispositivos" id="dispositivosField" class="form-control" >
        <option value="">Seleccionar dispositivo</option>
         <option value="CATETER CENTRAL">CATÉTER CENTRAL</option>
          <option value="CATETER PERIFERICO">CATÉTER PERIFÉRICO</option>
           <option value="SONDA VESICAL">SONDA VESICAL</option>
            <option value="SONDA NASOGASTRICA">SONDA NASOGÁSTRICA</option>
             <option value="OTROS">OTROS</option>
      </select>
                  
                
              </div>
            </div>
            <div class="mb-3 row">
              <label for="tipoField" class="col-md-3 form-label">Calibre</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="tipoField" name="name">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fecha_instField" class="col-md-3 form-label">Fecha de instalacion</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="fecha_instField" name="fecha_inst">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="instaloField" class="col-md-3 form-label">Instalo</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="instaloField" name="instalo">
              </div>
            </div>
           

 <div class="mb-3 row">
              <label for="cultivoField" class="col-md-3 form-label">Observaciones</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="cultivoField" name="cultivo">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nuevo registro de cateter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUser" action="">
              <div class="mb-3 row">
              <label for="addCityField" class="col-md-3 form-label">Dispositivos</label>
              <div class="col-md-9">
                    <select name="dispositivos" id="adddispositivosField" class="form-control" >
        <option value="">Seleccionar dispositivo</option>
         <option value="CATETER CENTRAL">CATÉTER CENTRAL</option>
          <option value="CATETER PERIFERICO">CATÉTER PERIFÉRICO</option>
           <option value="SONDA VESICAL">SONDA VESICAL</option>
            <option value="SONDA NASOGASTRICA">SONDA NASOGÁSTRICA</option>
             <option value="OTROS">OTROS</option>
      </select>
            
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addtipoField" class="col-md-3 form-label">Calibre</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addtipoField" name="tipo">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addfecha_instField" class="col-md-3 form-label">Fecha de instalacion</label>
              <div class="col-md-9">
                  <?php $fr = date("Y-m-d H:i");?>
                <input type="date" class="form-control" id="addfecha_instField" name="fecha_inst" value="<?php echo $fecha_actual = date("Y-m-d");?>">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addinstaloField" class="col-md-3 form-label">Instaló</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addinstaloField" name="instalo">
              </div>
            </div>
            

<div class="mb-3 row">
              <label for="addcultivoField" class="col-md-3 form-label">Observaciones</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addcultivoField" name="cultivo">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
        </div>
    
    
     <!-- MEDICAMENTOS NUEVO-->
    <div class="tab-pane fade" id="nav-medicam" role="tabpanel" aria-labelledby="nav-medicam-tab">

<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>MEDICAMENTOS</center></strong>
</div><br>
<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalME" class="btn btn-success">Agregar nuevos Medicamentos</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoME" placeholder="Buscar...">
            </div>
            <h4>MEDICAMENTOS EN TRANSITO</h4>
            <table id="exampleME" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de reporte</th>
                <th>Hora</th>
                <th>Descripcion</th>
                <th>Dosis</th>
                <th>Via</th>
                <th>Otros</th>
                <th>Cantidad surtida</th>
                <th>Cantidad utilizada</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
          
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleME').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataME.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [5]
          },

        ]
      });
    });
    $(document).on('submit', '#addUserME', function(e) {
      e.preventDefault();
      
	var enf_fecha = $('#addenf_fechaField').val();
	var cart_hora = $('#addcart_horaField').val();
	var medicam_mat = $('#addmedicam_matField').val();
    var dosis_mat = $('#adddosis_matField').val();
    var via_mat = $('#addvia_matField').val();
    var unimed = $('#addunimedField').val();
    var otro = $('#addotroField').val();
    var cart_qty = $('#addcart_qtyField').val();
        
      if (enf_fecha != '' && cart_hora != '' && medicam_mat != '' && cart_qty != '') {
        $.ajax({
          url: "add_userME.php",
          type: "post",
          data: {
		    enf_fecha:enf_fecha,
            cart_hora:cart_hora,
            medicam_mat:medicam_mat,
            dosis_mat:dosis_mat,
            via_mat:via_mat,
            unimed:unimed,
            otro:otro,
            cart_qty:cart_qty
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleME').DataTable();
              mytable.draw();
              document.getElementById("addUserME").reset();
              $('#addUserModalME').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserME', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var enf_fecha = $('#enf_fechaField').val();
      var cart_hora = $('#cart_horaField').val();
      var medicam_mat = $('#medicam_matField').val();
      var dosis_mat = $('#dosis_matField').val();
      var via_mat = $('#via_matField').val();
      var unimed = $('#unimedField').val();
      var otro = $('#otroField').val();
      var cart_qty = $('#cart_qtyField').val();
      var cart_qtyS = $('#cart_qtySField').val();
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (enf_fecha != '' && cart_hora != '' && medicam_mat != '' && cart_qty != '') {
        $.ajax({
          url: "update_userME.php",
          type: "post",
          data: {
            enf_fecha:enf_fecha,
            cart_hora:cart_hora,
            medicam_mat:medicam_mat,
            dosis_mat:dosis_mat,
            via_mat:via_mat,
            unimed:unimed,
            otro:otro,
            cart_qty:cart_qty,
            cart_qtyS:cart_qtyS,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleME').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnME">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnME">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id, enf_fecha, cart_hora, medicam_mat, dosis_mat,via_mat,otro,cart_qtyS,cart_qty, button]);
              $('#exampleModalME').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleME').on('click', '.editbtnME ', function(event) {
      var table = $('#exampleME').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalME').modal('show');

      $.ajax({
        url: "get_single_dataME.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#cart_horaField').val(json.cart_hora);
          $('#enf_fechaField').val(json.enf_fecha);
          $('#medicam_matField').val(json.medicam_mat);
          $('#via_matField').val(json.via_mat);
          $('#unimedField').val(json.unimed);
          $('#dosis_matField').val(json.dosis_mat);
          $('#cart_qtyField').val(json.cart_qty);
          $('#cart_qtySField').val(json.cart_surtido);
           $('#otroField').val(json.otro);
        
        
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnME', function(event) {
      var table = $('#exampleME').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Estas seguro de eliminar este registro? ? ")) {
        $.ajax({
          url: "delete_userME.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalME" tabindex="-1" aria-labelledby="exampleModalLabelsignosME" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelsignosME">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUserME">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            <div class="mb-3 row">
              <label for="enf_fechaField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="enf_fechaField" name="enf_fecha">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="cart_horaField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                   <input type="time" name="cart_hora" id="cart_horaField" class="form-control">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="medicam_matField" class="col-md-3 form-label">Medicamento</label>
              <div class="col-md-9">
                     <input type="text" name="medicam_mat" id="medicam_matField" class="form-control" disabled>
                 
              </div>
            </div>
            <div class="mb-3 row">
              <label for="dosis_matField" class="col-md-3 form-label">Dosis</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="dosis_matField" name="dosis_mat">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="unimedField" class="col-md-3 form-label">Unidad de medida</label>
              <div class="col-md-9">
                  <select id="unimedField" name="unimed" class="form-control">
    <option value="">Seleccionar unidad de medida</option>
    <option value="Gota">Gota</option>
    <option value="Microgota">Microgota</option>
    <option value="Litro">Litro</option>
    <option value="Mililitro">Mililitro</option>
    <option value="Microlitro">Microlitro</option>
    <option value="Centimetro cubico">Centímetro cúbico</option>
    <option value="Dracma liquida">Dracma líquida</option>
    <option value="Onza liquida">Onza líquida</option>
    <option value="Kilogramo">Kilogramo</option>
    <option value="Gramo">Gramo</option>
    <option value="Miligramo">Miligramo</option>
    <option value="Microgramo">Microgramo</option>
    <option value="Microgramo de HA">Microgramo de HA</option>
    <option value="Nanogramo">Nanogramo</option>
    <option value="Libra">Libra</option>
    <option value="Onza">Onza</option>
    <option value="Masa molar">Masa molar</option>
    <option value="Milimol">Milimol</option>
    <option value="Miliequivalente">Miliequivalente</option>
    <option value="Unidad">Unidad</option>
    <option value="Miliunidad">Miliunidad</option>
    <option value="Unidad internacional">Unidad internacional</option>
    <option value="Unidad">Unidad</option>
</select>
              </div>
            </div>
             <div class="mb-3 row">
              <label for="via_matField" class="col-md-3 form-label">Via</label>
              <div class="col-md-9">
    <select class="form-control" id="via_matField" name="via_mat">
    <option value="">Seleccionar vía</option>
    <option value="INTRAVENOSA">INTRAVENOSA</option>
    <option value="INTRAMUSCULAR">INTRAMUSCULAR</option>
    <option value="INTRAOSEA">INTRAOSEA</option>
    <option value="INTRADERMICA">INTRADÉRMICA</option>
    <option value="NASAL">NASAL</option>
    <option value="TOPICA">ÓTICA</option>
    <option value="ORAL">ORAL</option>
    <option value="SUBLINGUAL">SUBLINGUAL</option>
    <option value="SUBTERMICA">SUBDÉRMICA</option>
    <option value="SUBCUTANEA">SUBCUTANEA</option>
    <option value="SONDA">SONDA</option>
    <option value="RECTAL">RECTAL</option>
    <option value="TOPICO">TÓPICO</option>
</select>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="otroField" class="col-md-3 form-label">Otros</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="otroField" name="otro">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="cart_qtyField" class="col-md-3 form-label">Cantidad</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="cart_qtyField" name="cart_qty">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="cart_qtySField" class="col-md-3 form-label">Cantidad Surtida</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="cart_qtySField" name="cart_qtyS">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalME" tabindex="-1" aria-labelledby="exampleModalLabelllME" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelllME">Nuevo registro de medicamentos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserME" action="">
              <div class="mb-3 row">
              <label for="addenf_fechaField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                  <?php $fr = date("Y-m-d H:i");?>
                <input type="date" class="form-control" id="addenf_fechaField" name="enf_fecha" value="<?php echo $fecha_actual = date("Y-m-d");?>">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addcart_horaField" class="col-sm-3 form-label">Hora</label>
              <div class="col-md-9">
                <input type="time" class="form-control" id="addcart_horaField" name="cart_hora">
              </div>
            </div>
              <div class="mb-3 row">
              <label for="addmedicam_matField" class="col-md-3 form-label">Medicamento</label>
              <div class="col-md-9">
                  
                  <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="medicam_mat" id="addmedicam_matField" style="width : 100%; heigth : 100%" required="">
         <?php
         $sql = "SELECT * FROM item, stock where item.controlado = 'NO' AND item.item_id = stock.item_id and stock.stock_qty != 0 ORDER BY item.item_name ASC";
              $result = $conexion->query($sql);
                echo"<option value=''>Seleccionar medicamento</option>";
               while ($row_datos = $result->fetch_assoc()) {
              
                 echo "<option value='" . $row_datos['item_id'] . "'>" . $row_datos['item_name'] . ', '.  $row_datos['item_grams'] . "</option>";
                }
          ?></select>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="adddosis_matField" class="col-sm-3 form-label">Dosis</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="adddosis_matField" name="dosis_mat">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addunimedField" class="col-md-3 form-label">Unidad de medida</label>
              <div class="col-md-9">
                  <select id="addunimedField" name="unimed" class="form-control">
    <option value="">Seleccionar unidad de medida</option>
    <option value="Gota">Gota</option>
    <option value="Microgota">Microgota</option>
    <option value="Litro">Litro</option>
    <option value="Mililitro">Mililitro</option>
    <option value="Microlitro">Microlitro</option>
    <option value="Centimetro cubico">Centímetro cúbico</option>
    <option value="Dracma liquida">Dracma líquida</option>
    <option value="Onza liquida">Onza líquida</option>
    <option value="Kilogramo">Kilogramo</option>
    <option value="Gramo">Gramo</option>
    <option value="Miligramo">Miligramo</option>
    <option value="Microgramo">Microgramo</option>
    <option value="Microgramo de HA">Microgramo de HA</option>
    <option value="Nanogramo">Nanogramo</option>
    <option value="Libra">Libra</option>
    <option value="Onza">Onza</option>
    <option value="Masa molar">Masa molar</option>
    <option value="Milimol">Milimol</option>
    <option value="Miliequivalente">Miliequivalente</option>
    <option value="Unidad">Unidad</option>
    <option value="Miliunidad">Miliunidad</option>
    <option value="Unidad internacional">Unidad internacional</option>
    <option value="Unidad">Unidad</option>
</select>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addvia_matField" class="col-md-3 form-label">Via</label>
              <div class="col-md-9">
                <select class="form-control" id="addvia_matField" name="via_mat">
    <option value="">Seleccionar vía</option>
    <option value="INTRAVENOSA">INTRAVENOSA</option>
    <option value="INTRAMUSCULAR">INTRAMUSCULAR</option>
    <option value="INTRAOSEA">INTRAOSEA</option>
    <option value="INTRADERMICA">INTRADÉRMICA</option>
    <option value="NASAL">NASAL</option>
    <option value="TOPICA">ÓTICA</option>
    <option value="ORAL">ORAL</option>
    <option value="SUBLINGUAL">SUBLINGUAL</option>
    <option value="SUBTERMICA">SUBDÉRMICA</option>
    <option value="SUBCUTANEA">SUBCUTANEA</option>
    <option value="SONDA">SONDA</option>
    <option value="RECTAL">RECTAL</option>
    <option value="TOPICO">TÓPICO</option>
</select>
              </div>
            </div>
             <div class="mb-3 row">
              <label for="addotroField" class="col-md-3 form-label">Otros</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addotroField" name="otro">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addcart_qtyField" class="col-md-3 form-label">Cantidad</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="addcart_qtyField" name="cart_qty" min="1" value="1">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div> 

      <center>
     <div class="col-md-4">
          
            <form action="" method="POST" name="formconfm<?php echo $id_atencion;?>" id="formconfm<?php echo $id_atencion;?>">
                <td>
                <input type="hidden" name="paciente" value="<?php echo $id_atencion; ?>">
                <button type="button" class="btn btn-block btn-success col-9" id="btnconf<?php echo $id_atencion;?>" name="btnconf<?php echo $id_atencion;?>">Confirmar </button>
                </td>
            </form>
     
        </div><br>
          </center>
   <script type="text/javascript">
    $(document).ready(function(){
        $('#btnconf<?php echo $id_atencion;?>').click(function(){
            var datos=$('#formconfm<?php echo $id_atencion;?>').serialize();
            $.ajax({
                type:"POST",
                url:"manipula_carnew.php",
                data:datos,
                success:function(r){
                    if(r=1){
                         mytable = $('#exampleME').DataTable();
                         mytable.draw();
                         mytable = $('#exampleMEDC').DataTable();
                         mytable.draw();
                        //$("#mytablemcnuevot").load("vista_enf_quirurgico.php #mytablemcnuevot");
                        alertify.success("Insumos con cantidad utilizada distinta de cero confirmados con éxito");
                        $("#mytablemcons").load("vista_enf_quirurgico.php #mytablemcons");
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }
            });
            return false;
        });
    });
    </script>
    <br><hr>
    
    <div class="container">

               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoMEDC" placeholder="Buscar...">
            </div>
            <h4>MEDICAMENTOS CONFIRMADOS</h4>
            <table id="exampleMEDC" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de reporte</th>
                <th>Hora</th>
                <th>Descripcion</th>
                <th>Dosis</th>
                <th>Via</th>
                <th>Otros</th>
                <th>Cantidad</th>
               
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
          
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleMEDC').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id_med_reg', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataMEDC.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [7]
          },

        ]
      });
    });
    </script>
    
</div>  <!-- MEDICAMENTOS NUEVO FIN-->    

        
<div class="tab-pane fade" id="nav-piso" role="tabpanel" aria-labelledby="nav-piso-tab">
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>ENVIAR PACIENTE A:</center></strong>
    </div><p></p>
    <form method="POST" id="fpiso" name="fpiso">
        <select name="area" class="form-control col-sm-4" required id="areapac">
          
            <option value="HOSPITALIZACIÓN">Hospitalización (Piso)</option>
            <option value="RECUPERACIÓN">Recuperación </option>
            <option value="TERAPIA INTENSIVA">Terapia intensiva</option>
            <option value="OBSERVACIÓN">Observación</option>
        </select>
        <center>
        <div class="form-group col-6">
            <button type="submit" id="btnpiso" name="btnpiso" class="btn btn-success">Enviar paciente</button>
        </div></center>
    </form>
    
    <!-- SCRIPT AJAX-->
    <script type="text/javascript">
        $(document).ready(function(){
        $('#btnpiso').click(function(){
            var datos=$('#fpiso').serialize();
            $.ajax({
                type:"POST",
                url:"enviarpac.php",
                data:datos,
                success:function(r){
                    if(r=1){
                        $("#nav-piso").load("vista_enf_quirurgico.php #fpiso");
                       alertify.success("Paciente enviado con éxito");
                       document.getElementById("fpiso").reset();
                             document.getElementById("#h").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
    </script>
</div>
    
<div class="tab-pane fade active" id="nav-cir" role="tabpanel" aria-labelledby="nav-cir-tab">
     <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>CIRUGÍA SEGURA</center></strong>
  </div>
    <form method="POST" id="formcir">

  <div class="row">
    <div class="col-sm">
     <!--INICIO DE CARD-->
<div class="card" style="width: 22rem;">
  <div class="card-body">
    <center><h6 class="card-title"><strong>Fase 1: Entrada <p>Antes de la inducción de la anestesia</strong></h6></center><hr>
    <h7 class="card-subtitle text-bold">El cirujano, el anestesiólogo y el personal de enfermería en presencia del paciente han confirmado:</h7><p>
     <div class="form-check">
  <input class="form-check-input" type="checkbox" id="URGENCIAS" value="Si" name="identidad">
  <label class="form-check-label" for="URGENCIAS">
  Su identidad.
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="sitquir" value="Si" name="sitquir">
  <label class="form-check-label" for="sitquir">
    
  El sitio quirúrgico.
  
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="procquir" value="Si" name="procquir">
  <label class="form-check-label" for="procquir">
    
  El procedimiento quirúrgico.
  
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="suconsen" value="Si" name="suconsen">
  <label class="form-check-label" for="suconsen">
    
  Su consentimiento.
  
  </label>
</div>
<hr>
<div class="container">
  <div class="row">
    <strong>¿El personal de enfermería ha confirmado con el cirujano que esté marcado el sitio quirúrgico?</strong>
      <div class="form-check col-4">
  <input class="form-check-input" type="radio" id="lugar" value="Si" name="lug_noproc">
  <label class="form-check-label" for="lugar">
   Si
  </label>
</div>
 
    
       <div class="form-check">
  <input class="form-check-input" type="radio" id="noprocede" value="No procede" name="lug_noproc">
  <label class="form-check-label" for="noprocede">
    No procede
  </label>
</div>
    
    </div>
  </div>
  <hr>
  <strong>El cirujano ha confirmado la realización de asepsia en el sitio quirúrgico:</strong>
   <div class="form-check">
  <input class="form-check-input" type="radio" id="verificado" value="Si" name="circonfase">
  <label class="form-check-label" for="verificado">
    Si
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="verificadono" value="No" name="circonfase">
  <label class="form-check-label" for="verificadono">
    No
  </label>
</div>
<hr>
<strong>El anestesiólogo ha completado el control de la seguridad de la anestesia al revisar: medicamentos, equipo (funcionalidad y condiciones óptimas) y el riesgo anestésico del paciente.</strong>
 <div class="form-check">
  <input class="form-check-input" type="radio" id="conseg" value="Si" name="conseg">
  <label class="form-check-label" for="conseg">
   Si
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="consegno" value="No" name="conseg">
  <label class="form-check-label" for="consegno">
   No
  </label>
</div>
<hr>

<strong>El anestesiólogo ha colocado y comprobado que funcione el oxímetro de pulso correctamente.</strong>
 <div class="form-check">
  <input class="form-check-input" type="radio" id="oximetro" value="Si" name="oximetro">
  <label class="form-check-label" for="oximetro">
   Si
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="oximetrono" value="No" name="oximetro">
  <label class="form-check-label" for="oximetrono">
   No
  </label>
</div>
   <hr>
    <strong>El anestesiólogo ha confirmado si el paciente tiene:</strong><br>
    <p>¿Alergias conocidas?</p>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="aler_conno" value="No" name="alerg_con">
  <label class="form-check-label" for="aler_conno">
 No
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="aler_consi" value="Si" name="alerg_con">
  <label class="form-check-label" for="aler_consi">
   Si
  </label>
</div>
    </div>
  </div>
  <hr>
   
    <p><strong>¿Vía aérea difícil y/o riesgo de aspiración?</strong></p>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="difviano" value="No" name="dif_via_aerea">
  <label class="form-check-label" for="difviano">
 No
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="difviasi" value="Si" name="dif_via_aerea">
  <label class="form-check-label" for="difviasi">
   Si, y se cuenta con material, equipo y ayuda disponible.
  </label>
</div>
    </div>
  </div>
<hr>
   <p><strong>¿Riesgo de hemorragia en adultos >500 ml. (niños >7 ml / kg)?</strong></p>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="hematiesno" value="No" name="reishemo">
  <label class="form-check-label" for="hematiesno">
 No
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="hematiessi" value="Si" name="reishemo">
  <label class="form-check-label" for="hematiessi">
   Si, y se ha previsto la disponibilidad de líquidos y dos vías centrales.
  </label>
</div>
    </div>
  </div>
  <hr>
   <p><strong>¿Posible necesidad de hemoderivados y soluciones disponibles?</strong></p>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="posned" value="No" name="nechemo">
  <label class="form-check-label" for="posned">
 No
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="cruce" value="Si" name="nechemo">
  <label class="form-check-label" for="cruce">
 Si, y ya se ha realizado el cruce de sangre
  </label>
</div>
    </div>
  </div>

  </div>
</div>
     <!--FIN DE CARD-->
    </div>
    <div class="col-sm">
      <!--INICIO DE SEGUNDA CARD-->
<div class="card" style="width: 22rem;">
  <div class="card-body">
    <h6 class="card-title"><strong><center>Fase 2: Pausa quirúrgica<p>Antes de la incisión cutánea</strong></center></h6><hr>
    <h7 class="card-subtitle mb-2 text-bold">El circulante ha identificado a cada uno de los miembros del quipo quirúrgico para se presenten por su nombre y función, sin omisiones.</h7><p>
   
      <div class="form-check">
  <input class="form-check-input" type="checkbox" id="confmp" value="Si" name="fcirujano">
  <label class="form-check-label" for="confmp">
   Cirujano
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="fayucir" value="Si" name="fayucir">
  <label class="form-check-label" for="fayucir">
   Ayudante de cirujano
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="fanest" value="Si" name="fanest">
  <label class="form-check-label" for="fanest">
  Antestesiólogo
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="Instrumentista" value="Si" name="instrumentista">
  <label class="form-check-label" for="Instrumentista">
   Instrumentista
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="fotros" value="Otros" name="fotros">
  <label class="form-check-label" for="fotros">
   Otros
  </label>
</div>

<hr>
 
    <strong>El cirujano, ha confirmado de manera verbal con el anestesiólogo y el personal de enfermería:</strong>
    
 <div class="form-check">
  <input class="form-check-input" type="checkbox" id="paccorr" value="Si" name="paccorr">
  <label class="form-check-label" for="paccorr">
  Paciente correcto
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" id="proccorr" value="Si" name="proccorr">
  <label class="form-check-label" for="proccorr">
    Procedimiento correcto
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" id="sitquird" value="Si" name="sitquird">
  <label class="form-check-label" for="sitquird">
    Sitio quirúrgico
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" id="encas" value="Si" name="encas">
  <label class="form-check-label" for="encas">
    En caso de órgano bilateral, ha marcado derecho o izquierdo, según corresponda
  </label>
</div>
  
  <div class="form-check">
  <input class="form-check-input" type="checkbox" id="casmul" value="Si" name="casmul">
  <label class="form-check-label" for="casmul">
    En caso de estructura múltiple, ha especificado el nivel a operar.
  </label>
</div>
  
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="poscpac" value="Si" name="poscpac">
  <label class="form-check-label" for="poscpac">
      Posición correcta del paciente.
  </label>
</div>
   
  
  
<hr>
<strong><p>¿El anestesiólogo y el personal de enfermería han verificado que se haya aplicado la profilaxis antibiótica conforme a las indicaciones médicas?</p></strong>
  <div class="form-check">
  <input class="form-check-input" type="radio" id="cirresi" value="Si" name="anverpro">
  <label class="form-check-label" for="cirresi">
  Si
  </label>
</div>

     <div class="form-check">
  <input class="form-check-input" type="radio" id="anesresi" value="No" name="anverpro">
  <label class="form-check-label" for="anesresi">
  No
  </label>
</div>

 <div class="form-check">
  <input class="form-check-input" type="radio" id="enfresi" value="No procede" name="anverpro">
  <label class="form-check-label" for="enfresi">
  No procede
  </label>
</div>
<hr>

<p><strong>El cirujano y el personal de enfermería han verificado que cuenta con los estudios de imagen que requiere?</strong></p>
  <div class="row">
    <div class="col-sm">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="diag_esesi" value="Si" name="img_diag">
  <label class="form-check-label" for="diag_esesi">
 Si
  </label>
</div>
    </div>
    <div class="col-sm-9">
      <div class="form-check">
  <input class="form-check-input" type="radio" id="diag_eseno" value="No procede" name="img_diag">
  <label class="form-check-label" for="diag_eseno">
  No procede
  </label>
</div>
    </div>
  </div>
<hr>
  <div class="form-group">
    <label for="texto"></i><strong> Previsión de Eventos Críticos:</strong></label>
  <p><strong>El cirujano ha informado:</strong>

<div class="form-check">
  <input class="form-check-input" type="checkbox" id="pasocri" value="Si" name="pasocri">
  <label class="form-check-label" for="pasocri">
  Los pasos críticos o no sistematizados.
</label>
    </div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="durope" value="Si" name="durope">
  <label class="form-check-label" for="durope">
  La duración de la operación.
</label>
    </div>
    <div class="form-check">
  <input class="form-check-input" type="checkbox" id="persangre" value="Si" name="persangre">
  <label class="form-check-label" for="persangre">
  La pérdida de sangre prevista.
</label>
    </div>

<strong>El anestesiólogo ha informado:</strong>
 <div class="form-check">
  <input class="form-check-input" type="checkbox" id="exriesen" value="Si" name="exriesen">
  <label class="form-check-label" for="exriesen">
  La existencia de algún riesgo o enfermedad en el paciente que pueda complicar la cirugía.
</label>
    </div>

<strong>El personal de enfermería ha informado:</strong>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="fechm" value="Si" name="fechm">
  <label class="form-check-label" for="fechm">
 La fecha y método de esterilización del equipo y el instrumental.
    </div>
 
     <div class="form-check">
  <input class="form-check-input" type="checkbox" id="exproble" value="Si" name="exproble">
  <label class="form-check-label" for="exproble">
La existencia de algún problema con el instrumental, los equipos y el conteo del mismo.
    </div> 
  
</div>
</div>
</div>

      <!--TERMINO DE SEGUNDA CAR-->

    </div>
    <div class="col-sm">
     <!--INICIO DE TERCER CAR-->
<div class="card" style="width: 22rem;">
  <div class="card-body">
    <h6 class="card-title"><strong><center>Fase 3: Salida</center><p>Antes de que el paciente salga de quirófano</strong></h6><hr>
    <h7 class="card-subtitle mb-2 text-bold">El cirujano responsable de la atención del paciente, en presencia del anestesiólogo y el personal de enfermería, ha aplicado la Lista de Verificación de la Seguridad de la Cirugía y ha confirmado verbalmente:</h7><p>
   <div class="form-check">
  <input class="form-check-input" type="checkbox" id="nomprocre" value="Si" name="nomprocre">
  <label class="form-check-label" for="nomprocre">
  El nombre del procedimiento realizado:
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="recuento" value="Si" name="recuento">
  <label class="form-check-label" for="recuento">
El recuento completo del instrumental, gasas y agujas.
  </label>
</div>
<div class="container">
  <div class="row">
   <div class="form-check">
  <input class="form-check-input" type="checkbox" id="etqmu" value="Si" name="etqmu">
  <label class="form-check-label" for="etqmu">
El etiquetado de las muestras (nombre completo del paciente, fecha de nacimiento, fecha de cirugía y descripción general).
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" id="proineq" value="Si" name="proineq">
  <label class="form-check-label" for="proineq">
Los problemas con el instrumental y los equipos que deben ser notificados y resueltos.
  </label>
</div> 
   
  </div>
</div>
<hr>
<p><strong>El cirujano y el anestesiólogo han comentado al personal de enfermería circulante:</strong></p>
  <div class="row">
    
   <div class="form-check">
  <input class="form-check-input" type="checkbox" id="prinrecpost" value="Si" name="prinrecpost">
  <label class="form-check-label" for="prinrecpost">
Los principales aspectos de la recuperación postoperatoria.
  </label>
</div> 
    
     <div class="form-check">
  <input class="form-check-input" type="checkbox" id="plantrat" value="Si" name="plantrat">
  <label class="form-check-label" for="plantrat">
El plan de tratamiento.
  </label>
</div> 
    <div class="form-check">
  <input class="form-check-input" type="checkbox" id="riesgpaci" value="Si" name="riesgpaci">
  <label class="form-check-label" for="riesgpaci">
Los riesgos del paciente.
  </label>
</div> 
  </div>

<hr>
<strong>¿Ocurrieron eventos adversos?</strong>
<div class="form-check">
  <input class="form-check-input" type="radio" id="eventosad" value="Si" name="eventosad">
  <label class="form-check-label" for="eventosad">
Si
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="eventosadno" value="No" name="eventosad">
  <label class="form-check-label" for="eventosadno">
No
  </label>
</div> 
<hr>

<strong>¿Se registro el evento adverso?</strong>
<div class="form-check">
  <input class="form-check-input" type="radio" id="eventosada" value="Si" name="reieventad">
  <label class="form-check-label" for="eventosada">
Si
  </label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" id="eventosadnoa" value="No" name="reieventad">
  <label class="form-check-label" for="eventosadnoa">
No
  </label><p>
  ¿Dónde?<input type="text" name="donde" class="form-control">
</div> 

<hr>
<div class="form-group">
    <label for="exampleFormControlTextarea1"><strong>Listado del personal responsable que participó en la aplicación y llenado de esta lista de verificación.</strong></label>


  </div>
    <div class="form-group">
    <label for="cirujano"><button type="button" class="btn btn-success btn-sm" id="playcir"><i class="fas fa-play"></button></i>
 Cirujano(s)</label>
    <input type="text" class="form-control" id="cirujano" value="" name="fir_cir">
  </div>
<script type="text/javascript">
const cirujano = document.getElementById('cirujano');
const btnPlayTextjano = document.getElementById('playcir');

btnPlayTextjano.addEventListener('click', () => {
        leerTexto(cirujano.value);
});

function leerTexto(cirujano){
    const speech = new SpeechSynthesisUtterance();
    speech.text= cirujano;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    <div class="form-group">
    <label for="anes"><button type="button" class="btn btn-success btn-sm" id="playtes"><i class="fas fa-play"></button></i> Antestesiólogo(s)</label>
    <input type="text" class="form-control" id="anes" value="" name="fir_anest">
  </div>
<script type="text/javascript">
const anes = document.getElementById('anes');
const btnPlayTextlogo = document.getElementById('playtes');

btnPlayTextlogo.addEventListener('click', () => {
        leerTexto(anes.value);
});

function leerTexto(anes){
    const speech = new SpeechSynthesisUtterance();
    speech.text= anes;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    <div class="form-group">
    <label for="enfermera"><button type="button" class="btn btn-success btn-sm" id="playfer"><i class="fas fa-play"></button></i>Personal de Enfermería</label>
    <input type="text" class="form-control" id="enfermera" value="" name="fir_enf">
  </div>
    <script type="text/javascript">
const enfermera = document.getElementById('enfermera');
const btnPlayTxte = document.getElementById('playfer');

btnPlayTxte.addEventListener('click', () => {
        leerTexto(enfermera.value);
});

function leerTexto(enfermera){
    const speech = new SpeechSynthesisUtterance();
    speech.text= enfermera;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
  </div>
</div>
     <!--TERMINO DE CAR-->
    </div>
  </div>


 <hr>

                        <center><div class="form-group col-6">
                            <button type="submit" id="btncir" name="btncir" class="btn btn-primary">FIRMAR Y GUARDAR</button>
                          
                        </div></center>

                    
                        </form>
                        <!-- SCRIPT AJAX-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btncir').click(function(){
            var datos=$('#formcir').serialize();

            $.ajax({
                type:"POST",
                url:"insertar_cir_seg.php",
                data:datos,
                success:function(r){
                    if(r=1){
                        $("#tabs").load("vista_enf_quirurgico.php #tabs");
                       alertify.success("Formato de cirugía segura agregado con éxito");
                       document.getElementById("formcir").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>
                       
    </div>


<!--PESTAÑA cuidados-->
<div class="tab-pane fade" id="nav-cuid" role="tabpanel" aria-labelledby="nav-cuid-tab">
    <br>
    <div class="container">
        <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 18px;">
  <center><strong>PRESCRIPCIÓN Y ÓRDENES: PREPARACIÓN DE CASOS QUIRÚRGICOS, DIETAS, ETC.</strong></center>
</div>
<form method="POST" id="cuida">
<div class="col-sm-4">
                    <div class="form-group">
                        <br>
<font color="#2b2d7f"><strong>Dieta</strong> </font>
                        <select class="form-control" name="dieta" required id="dieta">
                                 <option value="">Seleccionar dieta</option>
                            
                            <?php
                            $sql_d = "SELECT DISTINCT id_dieta,dieta FROM cat_dietas WHERE dieta_activo='SI' order by dieta ASC";
                            $result_d = $conexion->query($sql_d);
                            while ($row_d = $result_d->fetch_assoc()) {
                                echo "<option value='" . $row_d['dieta'] . "'>" . $row_d['dieta'] . "</option>";
                            }
                            ?>
                    </select>
                 </div>
               </div>
<div class="container">
                <div class="row">
                    
                    <div class="col-sm-2">
                        <strong><font color="#2b2d7f">Drenaje<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecord"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecord"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pla4"><i class="fas fa-play"></button></i>
</div></font></strong>
                    </div>
                  
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="gca" name="gca" value="SI">
                            <label class="form-check-label" for="son">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="gca" name="gca" value="NO" checked>
                            <label class="form-check-label" for="son">
                                NO
                            </label>
                        </div>
                    </div>
              
                    <div class="col-sm-7">
     <input type="text" name="gcat" class="form-control" id="gcat">
    </div>
    <script type="text/javascript">
const btnStartRecord = document.getElementById('btnStartRecord');
const btnStopRecord = document.getElementById('btnStopRecord');
const gcat = document.getElementById('gcat');

const btnPlayTex4 = document.getElementById('pla4');
btnPlayTex4.addEventListener('click', () => {
        leerTexto(gcat.value);
});

function leerTexto(gcat){
    const speech = new SpeechSynthesisUtterance();
    speech.text= gcat;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 

     let recognition = new webkitSpeechRecognition();
      recognition.lang = "es-ES";
      recognition.continuous = true;
      recognition.interimResults = false;

      recognition.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        gcat.value += frase;
      }

      btnStartRecord.addEventListener('click', () => {
        recognition.start();
      });

      btnStopRecord.addEventListener('click', () => {
        recognition.abort();
      });
</script>
                </div>
                <p>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <strong><font color="#2b2d7f">Sonda<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="medg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="toss"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pla5"><i class="fas fa-play"></button></i>
</div></font></strong>
                    </div>
                  
                      <div class="col-sm-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="son" name="son" value="SI">
                            <label class="form-check-label" for="son">
                                SI
                            </label>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="son" name="son" value="NO" checked>
                            <label class="form-check-label" for="son">
                                NO
                            </label>
                        </div>
                    </div>
              
                    <div class="col-sm-7">
     <input type="text" name="sont" class="form-control" id="sont">
    </div>
    <script type="text/javascript">
const medg = document.getElementById('medg');
const toss = document.getElementById('toss');
const sont = document.getElementById('sont');

const btnPlayTex5 = document.getElementById('pla5');
btnPlayTex5.addEventListener('click', () => {
        leerTexto(sont.value);
});

function leerTexto(sont){
    const speech = new SpeechSynthesisUtterance();
    speech.text= sont;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
} 


     let rem = new webkitSpeechRecognition();
      rem.lang = "es-ES";
      rem.continuous = true;
      rem.interimResults = false;

      rem.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        sont.value += frase;
      }

      medg.addEventListener('click', () => {
        rem.start();
      });

      toss.addEventListener('click', () => {
        rem.abort();
      });
</script>
                </div>
            </div>
     <P>
<div class="container">
                <div class="row">
                    <div class="col-sm-3">
                        <strong><label for="exampleFormControlTextarea1"><font size="3" color="#2b2d7f">
                                    Observaciones(Otros): <button type="button" class="btn btn-success btn-sm" id="plobo"><i class="fas fa-play"></button></i></font></label></strong>
                    </div>
             
                    <div class="col-sm-9">
      <input type="text" name="observ_be" class="form-control"  id="observ_be">
    </div>
                </div>
            </div>
              <script type="text/javascript">

const observ_be = document.getElementById('observ_be');
const btnPlayTextm = document.getElementById('plobo');

btnPlayTextm.addEventListener('click', () => {
        leerTexto(observ_be.value);
});

function leerTexto(observ_be){
    const sd = new SpeechSynthesisUtterance();
    sd.text= observ_be;
    sd.volume=1;
    sd.rate=1;
    sd.pitch=0;
    window.speechSynthesis.speak(sd);
}
</script>

<!--ORDENES LAB-->
  <<!--div class="row">
    <div class="col-sm-3">
        <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">Estudios laboratorio: <button type="button" class="btn btn-success btn-sm" id="playlab"><i class="fas fa-play"></button></i></font></label></strong>
    </div>
    <div class="col-sm-4">
     <div class="form-group"><br>
        <select id="l1" name="l1[]" multiple="multiple" class="form-control" required="" >
          <option value="NINGUNO" selected="NINGUNO" >NINGUNO</option>
            <?php
            $sql_serv = "SELECT * FROM cat_servicios c where tipo = 1 and serv_activo = 'SI'";
            $result_serv = $conexion->query($sql_serv);
            while ($row_serv = $result_serv->fetch_assoc()) {
              echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
             
              }
              ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#l1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 350,
                                    maxHeight: 200,
                                    enableFiltering: true,
                                    dropUp: false
                                });
                            });
                        </script>
                    </div>
                  
  <script type="text/javascript">
    const l1 = document.getElementById('l1');
    const btnPlayTextloba = document.getElementById('playlab');
    btnPlayTextloba.addEventListener('click', () => {
      leerTexto(l1.value);
    });

    function leerTexto(l1){
      const ss = new SpeechSynthesisUtterance();
      ss.text= l1;
      ss.volume=1;
      ss.rate=1;
      ss.pitch=0;
      window.speechSynthesis.speak(ss);
    }
   </script>
 </div>

  <div class=" col-sm-5">
    <div class="form-group">
       Detalle estudios laboratorio:
      <button type="button" class="btn btn-danger btn-sm" id="detel"><i class="fas fa-microphone">
      </button></i>
      <button type="button" class="btn btn-primary btn-sm" id="finel"><i class="fas fa-microphone-slash">
      </button></i>
      <button type="button" class="btn btn-success btn-sm" id="playdetallelab"><i class="fas fa-play">
      </button></i>
      <textarea id="txtdetallelab" name="detalle_lab" class="form-control"></textarea>                 
    </div>
  </div>
</div>-->

<script type="text/javascript">
const detel = document.getElementById('detel');
const finel = document.getElementById('finel');
const txtdetallelab = document.getElementById('txtdetallelab');

const btnPlayTextdetestlab = document.getElementById('playdetallelab');
btnPlayTextdetestlab.addEventListener('click', () => {
        leerTexto(txtdetallelab.value);
});

function leerTexto(txtdetallelab){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdetallelab;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rsdetlab = new webkitSpeechRecognition();
      rsdetlab.lang = "es-ES";
      rsdetlab.continuous = true;
      rsdetlab.interimResults = false;

      rsdetlab.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdetallelab.value += frase;
      }

      detel.addEventListener('click', () => {
        rsdetlab.start();
      });

      finel.addEventListener('click', () => {
        rsdetlab.abort();
      });
</script>      
            <!--<div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">Estudios Imagenología: <button type="button" class="btn btn-success btn-sm" id="playima"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <br>
                        
                        <select id="a1" name="a1[]" multiple="multiple" class="form-control" required="">
                            <option value="NINGUNO" selected="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios where tipo =2 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#a1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 350,
                                    maxHeight: 200,
                                    enableFiltering: true,
                                    dropUp: false
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="col-sm-5">Detalle estudios Imagenología<br>
<input type="text" class="form-control" name="det_imagen"  >
 </div>
<script type="text/javascript">
const a1 = document.getElementById('a1');
const btnPlayTextimaa = document.getElementById('playima');
btnPlayTextimaa.addEventListener('click', () => {
        leerTexto(a1.value);
});

function leerTexto(a1){
    const ss = new SpeechSynthesisUtterance();
    ss.text= a1;
    ss.volume=1;
    ss.rate=1;
    ss.pitch=0;
    window.speechSynthesis.speak(ss);
}

</script>
            </div>-->

  <!--<div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">Estudios patología:</font></label></strong>
                </div>
                <div class="col-sm-4">

                    <div class="form-group">
                        <br>
                    
                       <select id="p1" name="p1[]" multiple="multiple" class="form-control col-9" required="">
                            <option value="NINGUNO" selected="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios where tipo =6 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#p1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 250,
                                    maxHeight: 200,
                                    enableFiltering: true,
                                    dropUp: false,
                                    enableCaseInsensitiveFiltering: true
                                });
                            });
                        </script>
                    </div>
                </div>
 <div class="col-sm-5">Detalle estudios patología<br>
<input type="text" class="form-control" name="det_pato"  >
 </div>
            </div>-->

           <!-- <div class="row">
                <div class="col-sm-3">
                    <strong><label for="exampleFormControlTextarea1"><br><font size="3" color="#2b2d7f">Solicitud Banco de Sangre:<button type="button" class="btn btn-success btn-sm" id="playsangre"><i class="fas fa-play"></button></i></font></label></strong>
                </div>
                <div class="col-sm-4">

                    <div class="form-group">
                       <br>
                        <select id="s1" name="s1[]" multiple="multiple" class="form-control" required="">
                            <option value="NINGUNO" selected="NINGUNO">NINGUNO</option>
                            <?php
                            $sql_serv = "SELECT * FROM cat_servicios where tipo =5 and serv_activo = 'SI'";
                            $result_serv = $conexion->query($sql_serv);
                            while ($row_serv = $result_serv->fetch_assoc()) {
                                echo "<option value='" . $row_serv['serv_desc'] . "'>" . $row_serv['serv_desc'] . "</option>";
                            }
                            ?>
                        </select>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#s1').multiselect({
                                    nonSelectedText: 'Selecciona servicio(s)',
                                    includeSelectAllOption: false,
                                    buttonWidth: 350,
                                    maxHeight: 200,
                                    enableFiltering: true,
                                    dropUp: false
                                });
                            });
                        </script>
                    </div>
                </div>
<script type="text/javascript">
const s1 = document.getElementById('s1');
const btnPlayTextsan = document.getElementById('playsangre');
btnPlayTextsan.addEventListener('click', () => {
        leerTexto(s1.value);
});

function leerTexto(s1){
    const ss = new SpeechSynthesisUtterance();
    ss.text= s1;
    ss.volume=1;
    ss.rate=1;
    ss.pitch=0;
    window.speechSynthesis.speak(ss);
}

</script>
                <div class="col-sm-5">Detalle Solicitud banco de Sangre: 
<button type="button" class="btn btn-danger btn-sm" id="detga"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="bancos"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playdetsangre"><i class="fas fa-play"></button></i>

<textarea type="text" class="form-control" name="det_sang" id="txtdtbds"> </textarea>
<script type="text/javascript">
const detga = document.getElementById('detga');
const bancos = document.getElementById('bancos');
const txtdtbds = document.getElementById('txtdtbds');

const btnPlayTextdetsangre = document.getElementById('playdetsangre');
btnPlayTextdetsangre.addEventListener('click', () => {
        leerTexto(txtdtbds.value);
});

function leerTexto(txtdtbds){
    const ss = new SpeechSynthesisUtterance();
    ss.text= txtdtbds;
    ss.volume=1;
    ss.rate=1;
    ss.pitch=0;
    window.speechSynthesis.speak(ss);
}

     let rds = new webkitSpeechRecognition();
      rds.lang = "es-ES";
      rds.continuous = true;
      rds.interimResults = false;

      rds.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdtbds.value += frase;
      }

      detga.addEventListener('click', () => {
        rds.start();
      });

      bancos.addEventListener('click', () => {
        rds.abort();
      });
</script>
                            
                       
                </div>
            </div>-->
          <!-- TERMINO ORDENES LAB-->  
            <hr>
            <div class="container">
                <div class="row">
                    <div class="col align-self-start">
                    </div>
                    <button type="submit" class="btn btn-primary" name="guar" id="guar">Firmar</button> &nbsp;
                    <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                    <div class="col align-self-end">
                    </div>
                </div>
            </div>


            <br>
            <br>
        </form>
    </div>

<!-- SCRIPT AJAX-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#guar').click(function(){
            var datos=$('#cuida').serialize();
            $.ajax({
                type:"POST",
                url:"insertarcuida.php",

                data:datos,
                success:function(r){
                    if(r=1){
                        //$("#mytable3").load("vista_enf_quirurgico.php #mytable3");
                       alertify.success("Nota de cuidados guardada");
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>

</div>

<!--PESTAÑA recuperacion-->
<div class="tab-pane fade" id="nav-recu" role="tabpanel" aria-labelledby="nav-recu-tab">
<br>
  
     
      <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>NOTA TRANSOPERATORIA</center></strong>
            <p>
    </div>
    
    <?php 
$repreop = $conexion->query("SELECT * FROM recu P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.id_atencion=$id_atencion") or die($conexion->error);
              while ($row_p = $repreop->fetch_assoc()) {
                $id_recu=$row_p['id_recu'];
                $id_atencion_r=$row_p['id_atencion'];
              } 
          
?>

<?php if(isset($id_recu) and isset($id_atencion_r)){
   
  
$recu = $conexion->query("SELECT * FROM recu P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.id_atencion=$id_atencion order by id_recu ASC limit 1") or die($conexion->error);
              while ($row_rec = $recu->fetch_assoc()) {
                  $ferecu=date_create($row_rec['text_fecha']);
               ?>
     <form method="POST" id="recupeact">
         
    <div class="container">
  <div class="row">
       <input type="hidden" name="id_recu" value="<?php echo $row_rec['id_recu'];?>">
<div class="col-sm-2">
                        <label class="control-label"><strong>Fecha reporte:</strong> </label>
                        
                         <input type="text" name="" id="" class="form-control" value="<?php echo date_format($ferecu,'d-m-Y')?>" disabled >
                    </div>
      <div class="col-sm-2">
        Sala
   <input type="text" class="form-control" name="sala" value="<?php echo $row_rec['sala']?>" disabled>
    </div>
    <div class="col-sm-4">
        Hora de inicio de cirugia
     <input type="time" class="form-control" name="inicio_cir" value="<?php echo $row_rec['inicio_cir']?>">
    </div>
   <div class="col-sm-3">
     
    <label>Hora de término de cirugía:</label>
     <input type="time" name="ter_cir" class="form-control" value="<?php echo $row_rec['ter_cir']?>">
  
      </div>
   
  </div>
</div>
<p></p>

<div class="container">
  <div class="row">
    <div class="col-sm-6">
        Imagenología
     <textarea name="imagen" class="form-control" rows="1" disabled><?php echo $row_rec['imagen']?></textarea>
     <textarea name="imagen2" class="form-control"></textarea>
    </div>
    <div class="col-sm-6">
        Incidentes
     <textarea name="incidentes" rows="1" class="form-control" disabled><?php echo $row_rec['incidentes']?></textarea>
      <textarea name="incidentes2" class="form-control"></textarea>
    </div>
    
   
  </div>
</div>
<p></p>
 
 <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>Ayudantes</center></strong>
            <p>
    </div>

<p></p>
<script type="text/javascript">
const txtsuto = document.getElementById('txtsuto');
const btnPlaymasto = document.getElementById('pegresopt');

btnPlaymasto.addEventListener('click', () => {
        leerTexto(txtsuto.value);
});

function leerTexto(txtsuto){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtsuto;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
<hr>
  <div class="row">
   <div class="col">
<button type="button" class="btn btn-success btn-sm" id="pnom1"><i class="fas fa-play"></button></i> Cirujano:<input type="text"  name="cirujano" class="form-control" id="nom_enf_preop" value="<?php echo $row_rec['cirujano']?>" disabled>
            <input type="text"  name="cirujano2" class="form-control" id="nom_enf_preop">
         </div>
 <br>
<script type="text/javascript">
const nom_enf_preop = document.getElementById('nom_enf_preop');

const btnPlaye1 = document.getElementById('pnom1');

btnPlaye1.addEventListener('click', () => {
        leerTexto(nom_enf_preop.value);
});

function leerTexto(nom_enf_preop){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_preop;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    
  <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pmeranombre"><i class="fas fa-play"></button></i>
Anestesiologo:<input type="text" name="anestesiologo" class="form-control" id="nom_enf_post" value="<?php echo $row_rec['anestesiologo']?>" disabled>
<input type="text" name="anestesiologo2" class="form-control" id="nom_enf_post">
         </div> 
     <br>
<script type="text/javascript">
const nom_enf_post = document.getElementById('nom_enf_post');
const btnPlaymbrerre = document.getElementById('pmeranombre');

btnPlaymbrerre.addEventListener('click', () => {
        leerTexto(nom_enf_post.value);
});

function leerTexto(nom_enf_post){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_post;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Instrumentista:<input type="text"  name="instrumentista" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['instrumentista']?>" disabled>
 <input type="text"  name="instrumentista2" class="form-control" id="nom_enf_tipan">
         </div>
     <br>
<script type="text/javascript">
const nom_enf_tipan = document.getElementById('nom_enf_tipan');

const btnPlaye2 = document.getElementById('pnomeq');

btnPlaye2.addEventListener('click', () => {
        leerTexto(nom_enf_tipan.value);
});

function leerTexto(nom_enf_tipan){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_tipan;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
  </div>

<p></p>

  <div class="row">
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Traumatólogo:<input type="text"  name="trauma" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['trauma']?>" disabled>
 <input type="text"  name="trauma2" class="form-control" id="nom_enf_tipan">
         </div>
    <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Neurocirujano:<input type="text"  name="neuro" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['neuro']?>"disabled>
  <input type="text"  name="neuro2" class="form-control" id="nom_enf_tipan">
         </div>
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Maxilofacial:<input type="text"  name="maxi" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['maxi']?>" disabled>
   <input type="text"  name="maxi2" class="form-control" id="nom_enf_tipan">
 
         </div>
  </div>

 <div class="row">
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Gastroenterólogo:<input type="text"  name="gastro" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['gastro']?>" disabled>
   <input type="text"  name="gastro2" class="form-control" id="nom_enf_tipan">
         </div>
    <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Oncólogo:<input type="text"  name="onco" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['onco']?>" disabled>
   <input type="text"  name="onco2" class="form-control" id="nom_enf_tipan">
         </div>
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Ginecólogo:<input type="text"  name="gine" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['gine']?>" disabled>
   <input type="text"  name="gine2" class="form-control" id="nom_enf_tipan">
         </div>
  </div>
  
 <div class="row">
       <div class="col-sm-4">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Bariatra:<input type="text"  name="bari" class="form-control" id="nom_enf_tipan" value="<?php echo $row_rec['bari']?>" disabled>
   <input type="text"  name="bari2" class="form-control" id="nom_enf_tipan">
         </div>
 </div>
  <p></p>

<div class="row">
  <div class="col-4">
            <button type="button" class="btn btn-success btn-sm" id="peralen"><i class="fas fa-play"></button></i>
 Circulante:
 
 <select name="circulante" class="form-control" data-live-search="true" id="nom_enf_trans" style="width : 100%; heigth : 100%">
     <?php
      
                $sql_diag="SELECT * FROM reg_usuarios where id_usua='".$row_rec['circulante']."' order by papell ASC";
                $result_diag=$conexion->query($sql_diag);
                while($row2=$result_diag->fetch_assoc()){
                    $ncir=$row2['papell'];
                    
                }?>
     
      <option value="<?php echo $row_rec['circulante']?>"><?php echo $ncir?></option>
             <option value="" disabled>Seleccionar enfermera</option>
                <?php
      
                $sql_diag="SELECT * FROM reg_usuarios where id_rol=3 order by papell ASC";
                $result_diag=$conexion->query($sql_diag);
                while($row=$result_diag->fetch_assoc()){

                echo "<option value='" . $row['id_usua'] . "'>".$row['papell']."</option>"; 
                } ?>
            </select>
 
</div>
         </div> 
     <p></p>
 
 
<script type="text/javascript">
const nom_enf_trans = document.getElementById('nom_enf_trans');
const btnPlayultenf = document.getElementById('peralen');

btnPlayultenf.addEventListener('click', () => {
        leerTexto(nom_enf_trans.value);
});

function leerTexto(nom_enf_trans){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_trans;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>



<p></p>
  <div class="row">
    <div class="col-sm">
   Primer ayudante:<input type="text" name="p_a" class="form-control" id="p_a" value="<?php echo $row_rec['p_a']?>" disabled>
   <input type="text" name="p_a2" class="form-control" id="p_a">
    </div>
    <div class="col-sm">
    Segundo ayudante:<input type="text" name="s_a" class="form-control" id="s_a" value="<?php echo $row_rec['s_a']?>" disabled>
    <input type="text" name="s_a2" class="form-control" id="p_a">
    </div>
    <div class="col-sm">
    Tercer ayudante:<input type="text" name="t_a" class="form-control" id="t_a" value="<?php echo $row_rec['t_a']?>" disabled>
    <input type="text" name="t_a2" class="form-control" id="p_a">
    </div>
    </div><hr>
<div class="container">
    <strong>Nota Transoperatoria </strong>
  <div class="row">
  <div class="col">
    <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecordt"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecordt"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pnotaderrt"><i class="fas fa-play"></button></i>
</div>
   <textarea name="not_recu" class="form-control" rows="2" id="not_recut" disabled><?php echo $row_rec['not_recu']?></textarea>
      <textarea name="not_recu2" class="form-control" rows="4" id="not_recut"></textarea>
    <script type="text/javascript">
const btnStartRecordt = document.getElementById('btnStartRecordt');
const btnStopRecordt = document.getElementById('btnStopRecordt');
const not_recut = document.getElementById('not_recut');

const btnPlaynotrect = document.getElementById('pnotaderrt');

btnPlaynotrect.addEventListener('click', () => {
        leerTexto(not_recut.value);
});

function leerTexto(not_recut){
    const speech = new SpeechSynthesisUtterance();
    speech.text= not_recut;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let recognitiont = new webkitSpeechRecognition();
      recognitiont.lang = "es-ES";
      recognitiont.continuous = true;
      recognitiont.interimResults = false;

      recognitiont.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        not_recut.value += frase;
      }

      btnStartRecordt.addEventListener('click', () => {
        recognitiont.start();
      });

      btnStopRecordt.addEventListener('click', () => {
        recognitiont.abort();
      });
</script>
    </div>  
     </div><p>
     <center>
     <button type="submit" name="btnaacturecu" id="btnaacturecu" class="btn btn-primary">Firmar y guardar</button>
 </center>
    </form>
    </div>
    <!-- SCRIPT AJAX ACTUALIZAR RECU-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnaacturecu').click(function(){
            var datos=$('#recupeact').serialize();
            $.ajax({
                type:"POST",
                url:"actualizarrecu.php",

                data:datos,
                success:function(r){
                    if(r=1){
                       $("#nav-recu").load("vista_enf_quirurgico.php #recupeact");
                       alertify.success("Nota de recuperación actualizada");
                       document.getElementById("recupeact").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>
    <?php } }else{ ?>
    
    
    <form method="POST" id="recupe">
    <div class="container">
  <div class="row">
      <div class="col-sm-2">
          <?php $fr = date("Y-m-d H:i");?>

                        <label class="control-label"><strong>Fecha reporte:</strong> </label>
                         <input type="date" name="fecha_reporte" id="fecha_reporte" class="form-control" value="<?php echo $fecha_actual = date("Y-m-d");?>" required>
                    </div>
       <div class="col-sm-2">
        Sala
    <select name="sala" class="form-control">
        <option value="">Seleccionar sala </option>
        <option value="Sala 1">Sala 1</option>
        <option value="Sala 2">Sala 2</option>
        <option value="Sala 3">Sala 3</option>
        <option value="Sala 4">Sala 4 </option>
        <option value="Sala de parto">Sala de parto </option>
    </select>
    </div>
    <div class="col-sm-4">
        Hora de inicio de cirugia
     <input type="time" class="form-control" name="inicio_cir">
    </div>
   <div class="col-sm-3">
     
    <label>Hora de término de cirugía:</label>
     <input type="time" name="ter_cir" class="form-control">
  
      </div>
   
  </div>
</div>
<p></p>

<div class="container">
  <div class="row">
    <div class="col-sm-6">
        Imagenología
     <textarea name="imagen" class="form-control"></textarea>
    </div>
    <div class="col-sm-6">
        Incidentes
     <textarea name="incidentes" class="form-control"></textarea>
    </div>
    
   
  </div>
</div>
<p></p>
 
 <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>Ayudantes</center></strong>
            <p>
    </div>

<p></p>
<script type="text/javascript">
const txtsuto = document.getElementById('txtsuto');
const btnPlaymasto = document.getElementById('pegresopt');

btnPlaymasto.addEventListener('click', () => {
        leerTexto(txtsuto.value);
});

function leerTexto(txtsuto){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtsuto;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
<hr>
  <div class="row">
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnom1"><i class="fas fa-play"></button></i> Cirujano:<input type="text"  name="cirujano" class="form-control" id="nom_enf_preop">
         </div>
 <br>
<script type="text/javascript">
const nom_enf_preop = document.getElementById('nom_enf_preop');

const btnPlaye1 = document.getElementById('pnom1');

btnPlaye1.addEventListener('click', () => {
        leerTexto(nom_enf_preop.value);
});

function leerTexto(nom_enf_preop){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_preop;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    
  <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pmeranombre"><i class="fas fa-play"></button></i>
Anestesiologo:<input type="text" name="anestesiologo" class="form-control" id="nom_enf_post">
         </div> 
     <br>
<script type="text/javascript">
const nom_enf_post = document.getElementById('nom_enf_post');
const btnPlaymbrerre = document.getElementById('pmeranombre');

btnPlaymbrerre.addEventListener('click', () => {
        leerTexto(nom_enf_post.value);
});

function leerTexto(nom_enf_post){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_post;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Instrumentista:<input type="text"  name="instrumentista" class="form-control" id="nom_enf_tipan">
         </div>
     <br>
<script type="text/javascript">
const nom_enf_tipan = document.getElementById('nom_enf_tipan');

const btnPlaye2 = document.getElementById('pnomeq');

btnPlaye2.addEventListener('click', () => {
        leerTexto(nom_enf_tipan.value);
});

function leerTexto(nom_enf_tipan){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_tipan;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
  </div>

<p></p>

  <div class="row">
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Traumatólogo:<input type="text"  name="trauma" class="form-control" id="nom_enf_tipan">
         </div>
    <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Neurocirujano:<input type="text"  name="neuro" class="form-control" id="nom_enf_tipan">
         </div>
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Maxilofacial:<input type="text"  name="maxi" class="form-control" id="nom_enf_tipan">
         </div>
  </div>

 <div class="row">
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Gastroenterólogo:<input type="text"  name="gastro" class="form-control" id="nom_enf_tipan">
         </div>
    <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Oncólogo:<input type="text"  name="onco" class="form-control" id="nom_enf_tipan">
         </div>
   <div class="col">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Ginecólogo:<input type="text"  name="gine" class="form-control" id="nom_enf_tipan">
         </div>
  </div>
  
 <div class="row">
       <div class="col-sm-4">
            <button type="button" class="btn btn-success btn-sm" id="pnomeq"><i class="fas fa-play"></button></i>
 Bariatra:<input type="text"  name="bari" class="form-control" id="nom_enf_tipan">
         </div>
 </div>
  <p></p>

<div class="row">
  <div class="col-4">
      <div class="alert alert-danger" role="alert">
 Recuerda completar el campo de CIRCULANTE*
</div>
            <button type="button" class="btn btn-success btn-sm" id="peralen"><i class="fas fa-play"></button></i>
 Circulante<font color="red">*</font>:
 
 <select name="circulante" class="form-control" data-live-search="true" id="nom_enf_trans" style="width : 100%; heigth : 100%">
             <option value="">Seleccionar enfermera</option>
                <?php
      
                $sql_diag="SELECT * FROM reg_usuarios where id_rol=3 order by papell ASC";
                $result_diag=$conexion->query($sql_diag);
                while($row=$result_diag->fetch_assoc()){

                echo "<option value='" . $row['id_usua'] . "'>".$row['papell']."</option>"; 
                } ?>
            </select>
 
</div>

<div class="col-4"><br><br><br><br>
     Circulante 2:
 <select name="circulante2" class="form-control" data-live-search="true" id="nom_enf_trans" style="width : 100%; heigth : 100%">
             <option value="">Seleccionar enfermera</option>
                <?php
      
                $sql_diag="SELECT * FROM reg_usuarios where id_rol=3 order by papell ASC";
                $result_diag=$conexion->query($sql_diag);
                while($row=$result_diag->fetch_assoc()){

                echo "<option value='" . $row['id_usua'] . "'>".$row['papell']."</option>"; 
                } ?>
            </select>
 
</div>
<div class="col-4"><br><br><br><br>
     Circulante 3:
 <select name="circulante3" class="form-control" data-live-search="true" id="nom_enf_trans" style="width : 100%; heigth : 100%">
             <option value="">Seleccionar enfermera</option>
                <?php
      
                $sql_diag="SELECT * FROM reg_usuarios where id_rol=3 order by papell ASC";
                $result_diag=$conexion->query($sql_diag);
                while($row=$result_diag->fetch_assoc()){

                echo "<option value='" . $row['id_usua'] . "'>".$row['papell']."</option>"; 
                } ?>
            </select>
 
</div>
         </div> 
     <p></p>
 
 
<script type="text/javascript">
const nom_enf_trans = document.getElementById('nom_enf_trans');
const btnPlayultenf = document.getElementById('peralen');

btnPlayultenf.addEventListener('click', () => {
        leerTexto(nom_enf_trans.value);
});

function leerTexto(nom_enf_trans){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_trans;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>



<p></p>
  <div class="row">
    <div class="col-sm">
   Primer ayudante:<input type="text" name="p_a" class="form-control" id="p_a">
    </div>
    <div class="col-sm">
    Segundo ayudante:<input type="text" name="s_a" class="form-control" id="s_a">
    </div>
    <div class="col-sm">
    Tercer ayudante:<input type="text" name="t_a" class="form-control" id="t_a">
    </div>
    </div><hr>
<div class="container">
    <strong>Nota Transoperatoria </strong>
  <div class="row">
  <div class="col">
    <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecordt"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecordt"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pnotaderrt"><i class="fas fa-play"></button></i>
</div>
   <textarea name="not_recu" class="form-control" rows="4" id="not_recut"></textarea>
    <script type="text/javascript">
const btnStartRecordt = document.getElementById('btnStartRecordt');
const btnStopRecordt = document.getElementById('btnStopRecordt');
const not_recut = document.getElementById('not_recut');

const btnPlaynotrect = document.getElementById('pnotaderrt');

btnPlaynotrect.addEventListener('click', () => {
        leerTexto(not_recut.value);
});

function leerTexto(not_recut){
    const speech = new SpeechSynthesisUtterance();
    speech.text= not_recut;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let recognitiont = new webkitSpeechRecognition();
      recognitiont.lang = "es-ES";
      recognitiont.continuous = true;
      recognitiont.interimResults = false;

      recognitiont.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        not_recut.value += frase;
      }

      btnStartRecordt.addEventListener('click', () => {
        recognitiont.start();
      });

      btnStopRecordt.addEventListener('click', () => {
        recognitiont.abort();
      });
</script>
    </div>  
     </div><p>
     <center>
     <button type="submit" name="btnagregarrecu" id="btnagregarrecu" class="btn btn-primary">Firmar y guardar</button>
 </center>
    </form>
</div>
<?php }?>
<!-- SCRIPT AJAX-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnagregarrecu').click(function(){
            var datos=$('#recupe').serialize();
            $.ajax({
                type:"POST",
                url:"insertarrecu.php",

                data:datos,
                success:function(r){
                    if(r=1){
                        $("#barra").load(" #barra");
                        //$("#nav-recu").load("vista_enf_quirurgico.php #recupe");
                       alertify.success("Nota de recuperación guardada");
                       document.getElementById("recupe").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>

</div>



<!--NOTA POSTOPERATORIA-->

<div class="tab-pane fade" id="nav-post" role="tabpanel" aria-labelledby="nav-post-tab">
    
      <?php 
$repos = $conexion->query("SELECT * FROM enf_posto P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.id_atencion=$id_atencion") or die($conexion->error);
              while ($row_po = $repos->fetch_assoc()) {
                $id_enf_post =$row_po['id_enf_post'];
                $id_atencion_p=$row_po['id_atencion'];
              } 
          
?>

<?php if(isset($id_enf_post) and isset($id_atencion_p)){
   

$re_po = $conexion->query("SELECT * FROM enf_posto P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.id_atencion=$id_atencion order by id_enf_post ASC limit 1") or die($conexion->error);
              while ($row_pos = $re_po->fetch_assoc()) {
              $fecpa=date_create($row_pos['fecha']);
              $fefp=date_format($fecpa,'d-m-Y');
               ?>
    
    <form method="POST" id="posto2" name="posto2">
<hr>
<div class="container">
<div class="row">
    
   <!--<div class="col-sm-2">
                        <label class="control-label"><strong>Fecha reporte:</strong> </label>
                        
                         <input type="text" name="" id="" class="form-control" value="<?php echo $fefp?>" disabled >
                    </div>-->
       <input type="hidden" name="id_enf_post" value="<?php echo $row_rec['id_enf_post'];?>">
    <div class="col-sm-3">
      <div class="form-group">
    <label>Hora de término de anestesia:</label>
     <input type="time" name="ter_anes" class="form-control" value="<?php echo $row_pos['ter_anes'] ?>">
  </div>
      </div>
      
</div>
<label class="control-label"><strong>Cirugía realizada:</strong> </label>
<input type="text" name="cir_real" class="form-control" value="<?php echo $row_pos['cir_real'] ?>">
                     
<p></p>

 <div class="row">
    <div class="col">
     <center>Tipo de cirugía <br></center> 
     
     <?php 
     if($row_pos['tip_cir']=='LIMPIA'){
    ?>
Limpia &nbsp;<input type="radio" value="LIMPIA" name="tip_cir" checked>&nbsp;&nbsp;&nbsp;&nbsp;
      Contaminada &nbsp;<input type="radio" value="CONTAMINADA" name="tip_cir">&nbsp;&nbsp;&nbsp;&nbsp;
      Limpia-contaminada &nbsp;<input type="radio" value="LIMPIA-CONTAMINADA" name="tip_cir">&nbsp;&nbsp;&nbsp;
      Sucia &nbsp;<input type="radio" value="SUCIA" name="tip_cir">
     <?php }else  if($row_pos['tip_cir']=='CONTAMINADA'){
     ?>
     Limpia &nbsp;<input type="radio" value="LIMPIA" name="tip_cir">&nbsp;&nbsp;&nbsp;&nbsp;
      Contaminada &nbsp;<input type="radio" value="CONTAMINADA" name="tip_cir" checked>&nbsp;&nbsp;&nbsp;&nbsp;
      Limpia-contaminada &nbsp;<input type="radio" value="LIMPIA-CONTAMINADA" name="tip_cir">&nbsp;&nbsp;&nbsp;
      Sucia &nbsp;<input type="radio" value="SUCIA" name="tip_cir">
     <?php } else  if($row_pos['tip_cir']=='LIMPIA-CONTAMINADA'){ ?>
         Limpia &nbsp;<input type="radio" value="LIMPIA" name="tip_cir">&nbsp;&nbsp;&nbsp;&nbsp;
      Contaminada &nbsp;<input type="radio" value="CONTAMINADA" name="tip_cir" >&nbsp;&nbsp;&nbsp;&nbsp;
      Limpia-contaminada &nbsp;<input type="radio" value="LIMPIA-CONTAMINADA" name="tip_cir" checked>&nbsp;&nbsp;&nbsp;
      Sucia &nbsp;<input type="radio" value="SUCIA" name="tip_cir">
     <?php }else  if($row_pos['tip_cir']=='SUCIA'){ ?>
    Limpia &nbsp;<input type="radio" value="LIMPIA" name="tip_cir">&nbsp;&nbsp;&nbsp;&nbsp;
      Contaminada &nbsp;<input type="radio" value="CONTAMINADA" name="tip_cir" >&nbsp;&nbsp;&nbsp;&nbsp;
      Limpia-contaminada &nbsp;<input type="radio" value="LIMPIA-CONTAMINADA" name="tip_cir" >&nbsp;&nbsp;&nbsp;
      Sucia &nbsp;<input type="radio" value="SUCIA" name="tip_cir" checked>
     <?php } ?>
    
    </div>
    <div class="col">
     <center>Pieza patológica <br></center> 
     <?php if($row_pos['p_medico']==null){ 
     ?>
     
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SI &nbsp;<input type="radio" value="SI" name="pipat" class="pago">&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;NO &nbsp;<input type="radio" value="NO" name="pipat" class="pago" checked>&nbsp;&nbsp;&nbsp;&nbsp;
     <?php }else{?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SI &nbsp;<input type="radio" value="SI" name="pipat" class="pago" checked>&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;NO &nbsp;<input type="radio" value="NO" name="pipat" class="pago">&nbsp;&nbsp;&nbsp;&nbsp;
     <?php } ?>

  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
        $(".pago").click(function(evento){
          
            var valor = $(this).val();
          
            if(valor == 'SI'){
                $("#div1").css("display", "block");
                $("#div2").css("display", "none");
            }else{
                $("#div1").css("display", "none");
                $("#div2").css("display", "block");
            }
    });
});

</script>

<div class="collapse" id="div1" style="display:;">
<div class="card card-body">
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
<tr><strong><center>SOLICITUD DE ESTUDIO Y DISPOSICIÓN DE PIEZAS ANATOMOPATOLÓGICAS</center></strong>
</div><p>
    <div class="container">
  <div class="row">
    <div class="col-sm-4">
     <center><button type="button" class="btn btn-success btn-sm" id="playtratm"><i class="fas fa-play"></button></i> Médico tratante:</center>
    </div>
    <div class="col-sm">
    <input type="text" name="p_medico" class="form-control" id="txtmmtt" value="<?php echo $row_pos['p_medico'] ?>" disabled>
        <input type="text" name="p_medico2" class="form-control" id="txtmmtt">
    <script type="text/javascript">
const txtmmtt = document.getElementById('txtmmtt');
const btnPlayTexttt = document.getElementById('playtratm');

btnPlayTexttt.addEventListener('click', () => {
        leerTexto(txtmmtt.value);
});

function leerTexto(txtmmtt){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtmmtt;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    </div>
  </div>
</div>
<p>
    
   <?php 
     if($row_pos['dispo_p']=='estudio patologico'){
    ?>
    
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico" checked>
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I.">
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante">
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente">
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar">
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>
<?php }else if($row_pos['dispo_p']=='R.P.B.I.'){?>

<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico" >
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I." checked>
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante">
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente">
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar">
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>

<?php } else if($row_pos['dispo_p']=='medico tratante'){?>

<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico" >
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I.">
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante"checked>
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente">
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar">
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>

<?php }else if($row_pos['dispo_p']=='paciente'){?>

<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico" >
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I.">
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante">
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente" checked>
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar">
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>

<?php } else if($row_pos['dispo_p']=='familiar'){?>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico" >
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I.">
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante">
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente" >
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar" checked>
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>
<?php }?>






<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Diagnósticos y/o datos clínicos:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="yog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="oycs"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playdidc"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="diagyodc" class="form-control" id="txtdd" disabled><?php echo $row_pos['diagyodc']?></textarea>
<textarea name="diagyodc2" class="form-control" id="txtdd"></textarea>
<script type="text/javascript">
const yog = document.getElementById('yog');
const oycs = document.getElementById('oycs');
const txtdd = document.getElementById('txtdd');

const btnPlayTextclid = document.getElementById('playdidc');

btnPlayTextclid.addEventListener('click', () => {
        leerTexto(txtdd.value);
});

function leerTexto(txtdd){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdd;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rgno = new webkitSpeechRecognition();
      rgno.lang = "es-ES";
      rgno.continuous = true;
      rgno.interimResults = false;

      rgno.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdd.value += frase;
      }

      yog.addEventListener('click', () => {
        rgno.start();
      });

      oycs.addEventListener('click', () => {
        rgno.abort();
      });
</script>
</div>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center><button type="button" class="btn btn-success btn-sm" id="playpian"><i class="fas fa-play"></button></i> Pieza anatómica:</center>
</div>
<div class="col-sm">
<input type="text" name="p_anato" class="form-control" id="txtanatpiz" value="<?php echo $row_pos['p_anato']?>" disabled>
<input type="text" name="p_anato2" class="form-control" id="txtanatpiz">
</div>
<script type="text/javascript">
const txtanatpiz = document.getElementById('txtanatpiz');
const btnPlayTextpza = document.getElementById('playpian');

btnPlayTextpza.addEventListener('click', () => {
        leerTexto(txtanatpiz.value);
});

function leerTexto(txtanatpiz){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtanatpiz;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

</script>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Tipo de intervención:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="tdig"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopit"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playtpoint"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="tipo_de_i" class="form-control" id="txtdipo" disabled><?php echo $row_pos['tipo_de_i']?></textarea>
<textarea name="tipo_de_i2" class="form-control" id="txtdipo"></textarea>
<script type="text/javascript">
const tdig = document.getElementById('tdig');
const stopit = document.getElementById('stopit');
const txtdipo = document.getElementById('txtdipo');

const btnPlayTextdetcion = document.getElementById('playtpoint');

btnPlayTextdetcion.addEventListener('click', () => {
        leerTexto(txtdipo.value);
});

function leerTexto(txtdipo){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdipo;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rien = new webkitSpeechRecognition();
      rien.lang = "es-ES";
      rien.continuous = true;
      rien.interimResults = false;

      rien.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdipo.value += frase;
      }

      tdig.addEventListener('click', () => {
        rien.start();
      });

      stopit.addEventListener('click', () => {
        rien.abort();
      });
</script>
</div>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center><button type="button" class="btn btn-success btn-sm" id="playsitioobtenc"><i class="fas fa-play"></button></i>
 Sitio de obtención:</center>
</div>
<div class="col-sm">
<input type="text" name="sitio_ob" class="form-control" id="txtobtes" value="<?php echo $row_pos['sitio_ob']?>" disabled>
<input type="text" name="sitio_ob2" class="form-control" id="txtobtes">
</div>
</div>
</div>
<script type="text/javascript">
const txtobtes = document.getElementById('txtobtes');
const btnPlayTs = document.getElementById('playsitioobtenc');

btnPlayTs.addEventListener('click', () => {
        leerTexto(txtobtes.value);
});

function leerTexto(txtobtes){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtobtes;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

</script>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Observaciones:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="estudiosog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopestciine"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playobsc"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="estudios_obser" class="form-control" id="txtcioest" disabled><?php echo $row_pos['estudios_obser']?></textarea>
<textarea name="estudios_obser2" class="form-control" id="txtcioest"></textarea>
<script type="text/javascript">
const estudiosog = document.getElementById('estudiosog');
const stopestciine = document.getElementById('stopestciine');
const txtcioest = document.getElementById('txtcioest');

const btnPlayTsob = document.getElementById('playobsc');

btnPlayTsob.addEventListener('click', () => {
        leerTexto(txtcioest.value);
});

function leerTexto(txtcioest){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcioest;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let estudiosrob = new webkitSpeechRecognition();
      estudiosrob.lang = "es-ES";
      estudiosrob.continuous = true;
      estudiosrob.interimResults = false;

      estudiosrob.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtcioest.value += frase;
      }

      estudiosog.addEventListener('click', () => {
        estudiosrob.start();
      });

      stopestciine.addEventListener('click', () => {
        estudiosrob.abort();
      });
</script>
</div>
</div>
</div>


</div>


</div>  
      
<div id="div2" style="display:none;">
</div>
<p></p>

<P></P>

<tr>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>MEDIDAS PARA LA PREVENCIÓN DE RIESGO DE CAÍDAS</center></strong>
    </div>
    <center><i>Recuerda que un paciente en quirófano y recuperación siempre tendrá un riesgo ALTO</i></center>
    <td><p>
      <div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="oxi">
 ¿Barandales de cama o camilla elevados?
  </label>
    </div>
    
    <?php 
    if($row_pos['oxi']=='SI'){
    ?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="oxi" id="oxi" checked>
    </div>
    <?php } else{
    ?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="oxi" id="oxi">
    </div>
    <?php }?>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="con">
  ¿Vigilado por el personal de área?
  </label>
    </div>
    
     <?php 
    if($row_pos['con']=='SI'){
    ?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="con" id="con" checked>
    </div>
    <?php }else{?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="con" id="con" >
    </div>
    <?php }?>
    
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="muc">
 ¿Se colocaron sujetadores?
  </label>
    </div>
   <?php 
    if($row_pos['muc']=='SI'){
    ?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="muc" id="muc" checked>
    </div>
    <?php }else{?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="muc" id="muc" >
    </div>
    <?php }?>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="vent">
 ¿Se aseguró al paciente antes de cambio de cama o camilla?
  </label>
    </div>
    <?php 
    if($row_pos['vent']=='SI'){
    ?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="vent" id="vent" checked>
    </div>
    <?php }else{?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="vent" id="vent" >
    </div>
    <?php }?>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="est">
  ¿Se aseguró al paciente antes de realizar movimiento de cambio de posición del paciente o de la mesa quirúrgica?
  </label>
    </div>
  <?php 
    if($row_pos['est']=='SI'){
    ?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="est" id="est" checked>
    </div>
    <?php }else{?>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="est" id="est" >
    </div>
    <?php }?>
  </div>
</div> 
</td>
<hr>


    <p></p>
     <div class="row">
    <div class="col-sm">
        <strong>Nota de Recuperación</strong>
        <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecordposto"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecordofofof"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playadefp"><i class="fas fa-play"></button></i>
</div>
        <textarea class="form-control" name="notapost" id="textopp" disabled><?php echo $row_pos['notapost']?></textarea>
             <textarea class="form-control" name="notapost2" id="textopp"></textarea>
         <script type="text/javascript">
const btnStartRecordposto = document.getElementById('btnStartRecordposto');
const btnStopRecordofofof = document.getElementById('btnStopRecordofofof');
const textopp = document.getElementById('textopp');

const btnPlayTextdea = document.getElementById('playadefp');

  btnPlayTextdea.addEventListener('click', () => {
          leerTexto(textopp.value);
  });

  function leerTexto(textopp){
      const speech = new SpeechSynthesisUtterance();
      speech.text= textopp;
      speech.volume=1;
      speech.rate=1;
      speech.pitch=0;
      window.speechSynthesis.speak(speech);
  }

     let recognitionnotaposto = new webkitSpeechRecognition();
      recognitionnotaposto.lang = "es-ES";
      recognitionnotaposto.continuous = true;
      recognitionnotaposto.interimResults = false;

      recognitionnotaposto.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        textopp.value += frase;
      }

      btnStartRecordposto.addEventListener('click', () => {
        recognitionnotaposto.start();
      });

      btnStopRecordofofof.addEventListener('click', () => {
        recognitionnotaposto.abort();
      });
</script>
        </div>
        </div>
    <p></p>
 
</div> <!--fin del div contanier-->
<hr>
<center>
     <button type="submit" name="btnposact" id="btnposact" class="btn btn-primary">Firmar y guardar</button>
 </center>
    </form>
    
    <!-- SCRIPT AJAX  ACTALIZAR POSTO-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnposact').click(function(){
            var datos=$('#posto2').serialize();
            $.ajax({
                type:"POST",
                url:"actualizarpostop.php",

                data:datos,
                success:function(r){
                    if(r=1){
                       $("#nav-post").load("vista_enf_quirurgico.php #posto2");
                       alertify.success("Nota postoperatoria actualizada");
                       document.getElementById("posto2").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>
    <?php } }else{ ?>
    
    <form method="POST" id="posto" name="posto">
<hr>
<div class="container">
<div class="row">
  <div class="col-sm-2">
          <?php $fr = date("Y-m-d H:i");?>

                        <label class="control-label"><strong>Fecha reporte:</strong> </label>
                         <input type="date" name="fecha_reporte" id="fecha_reporte" class="form-control" value="<?php echo $fecha_actual = date("Y-m-d");?>" required>
                    </div>
    <div class="col-sm-3">
      <div class="form-group">
    <label>Hora de término de anestesia:</label>
     <input type="time" name="ter_anes" class="form-control">
  </div>
      </div>
      
</div>

                        <label class="control-label"><strong>Cirugía realizada:</strong> </label>
                        <input type="text" name="cir_real" class="form-control">
                      
                   
<p></p>

 <div class="row">
    <div class="col">
     <center>Tipo de cirugía <br></center> 
      Limpia &nbsp;<input type="radio" value="LIMPIA" name="tip_cir" checked="">&nbsp;&nbsp;&nbsp;&nbsp;
      Contaminada &nbsp;<input type="radio" value="CONTAMINADA" name="tip_cir">&nbsp;&nbsp;&nbsp;&nbsp;
      Limpia-contaminada &nbsp;<input type="radio" value="LIMPIA-CONTAMINADA" name="tip_cir">&nbsp;&nbsp;&nbsp;
      Sucia &nbsp;<input type="radio" value="SUCIA" name="tip_cir">
    </div>
    <div class="col">
     <center>Pieza patológica <br></center> 
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SI &nbsp;<input type="radio" value="SI" name="pipat" class="pago">&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;NO &nbsp;<input type="radio" value="NO" name="pipat" checked="" class="pago">&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
        $(".pago").click(function(evento){
          
            var valor = $(this).val();
          
            if(valor == 'SI'){
                $("#div1").css("display", "block");
                $("#div2").css("display", "none");
            }else{
                $("#div1").css("display", "none");
                $("#div2").css("display", "block");
            }
    });
});

</script>

<div class="collapse" id="div1" style="display:;">
<div class="card card-body">
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
<tr><strong><center>SOLICITUD DE ESTUDIO Y DISPOSICIÓN DE PIEZAS ANATOMOPATOLÓGICAS</center></strong>
</div><p>
    <div class="container">
  <div class="row">
    <div class="col-sm-4">
     <center><button type="button" class="btn btn-success btn-sm" id="playtratm"><i class="fas fa-play"></button></i> Médico tratante:</center>
    </div>
    <div class="col-sm">
    <input type="text" name="p_medico" class="form-control" id="txtmmtt">
    <script type="text/javascript">
const txtmmtt = document.getElementById('txtmmtt');
const btnPlayTexttt = document.getElementById('playtratm');

btnPlayTexttt.addEventListener('click', () => {
        leerTexto(txtmmtt.value);
});

function leerTexto(txtmmtt){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtmmtt;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    </div>
  </div>
</div>
<p>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico">
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I.">
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante">
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente">
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar">
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Diagnósticos y/o datos clínicos:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="yog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="oycs"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playdidc"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="diagyodc" class="form-control" id="txtdd"></textarea>
<script type="text/javascript">
const yog = document.getElementById('yog');
const oycs = document.getElementById('oycs');
const txtdd = document.getElementById('txtdd');

const btnPlayTextclid = document.getElementById('playdidc');

btnPlayTextclid.addEventListener('click', () => {
        leerTexto(txtdd.value);
});

function leerTexto(txtdd){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdd;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rgno = new webkitSpeechRecognition();
      rgno.lang = "es-ES";
      rgno.continuous = true;
      rgno.interimResults = false;

      rgno.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdd.value += frase;
      }

      yog.addEventListener('click', () => {
        rgno.start();
      });

      oycs.addEventListener('click', () => {
        rgno.abort();
      });
</script>
</div>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center><button type="button" class="btn btn-success btn-sm" id="playpian"><i class="fas fa-play"></button></i> Pieza anatómica:</center>
</div>
<div class="col-sm">
<input type="text" name="p_anato" class="form-control" id="txtanatpiz">
</div>
<script type="text/javascript">
const txtanatpiz = document.getElementById('txtanatpiz');
const btnPlayTextpza = document.getElementById('playpian');

btnPlayTextpza.addEventListener('click', () => {
        leerTexto(txtanatpiz.value);
});

function leerTexto(txtanatpiz){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtanatpiz;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

</script>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Tipo de intervención:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="tdig"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopit"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playtpoint"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="tipo_de_i" class="form-control" id="txtdipo"></textarea>
<script type="text/javascript">
const tdig = document.getElementById('tdig');
const stopit = document.getElementById('stopit');
const txtdipo = document.getElementById('txtdipo');

const btnPlayTextdetcion = document.getElementById('playtpoint');

btnPlayTextdetcion.addEventListener('click', () => {
        leerTexto(txtdipo.value);
});

function leerTexto(txtdipo){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdipo;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rien = new webkitSpeechRecognition();
      rien.lang = "es-ES";
      rien.continuous = true;
      rien.interimResults = false;

      rien.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdipo.value += frase;
      }

      tdig.addEventListener('click', () => {
        rien.start();
      });

      stopit.addEventListener('click', () => {
        rien.abort();
      });
</script>
</div>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center><button type="button" class="btn btn-success btn-sm" id="playsitioobtenc"><i class="fas fa-play"></button></i>
 Sitio de obtención:</center>
</div>
<div class="col-sm">
<input type="text" name="sitio_ob" class="form-control" id="txtobtes">
</div>
</div>
</div>
<script type="text/javascript">
const txtobtes = document.getElementById('txtobtes');
const btnPlayTs = document.getElementById('playsitioobtenc');

btnPlayTs.addEventListener('click', () => {
        leerTexto(txtobtes.value);
});

function leerTexto(txtobtes){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtobtes;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

</script>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Observaciones:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="estudiosog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopestciine"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playobsc"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="estudios_obser" class="form-control" id="txtcioest"></textarea>
<script type="text/javascript">
const estudiosog = document.getElementById('estudiosog');
const stopestciine = document.getElementById('stopestciine');
const txtcioest = document.getElementById('txtcioest');

const btnPlayTsob = document.getElementById('playobsc');

btnPlayTsob.addEventListener('click', () => {
        leerTexto(txtcioest.value);
});

function leerTexto(txtcioest){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcioest;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let estudiosrob = new webkitSpeechRecognition();
      estudiosrob.lang = "es-ES";
      estudiosrob.continuous = true;
      estudiosrob.interimResults = false;

      estudiosrob.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtcioest.value += frase;
      }

      estudiosog.addEventListener('click', () => {
        estudiosrob.start();
      });

      stopestciine.addEventListener('click', () => {
        estudiosrob.abort();
      });
</script>
</div>
</div>
</div>


</div>
</div>  
      
<div id="div2" style="display:none;">
</div>
<p></p>

<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>VALORACIÓN FINAL DE LA PIEL</center></strong>
</div>
<p>
 <div class="container">
  <div class="row">
    <div class="col-sm">

<!-- BOT 1-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="uno" >
  X
  </a>
<div class="collapse collapse-horizontal" id="collapseExample">
    <input type="text" name="mara" class="form-control form-control-sm col-sm-1" id="contenido1">
</div>
<!-- TER BOT 1-->

<!-- BOT 2-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cdos" role="button" aria-expanded="false" aria-controls="cdos" id="dos" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cdos">
    <input type="text" name="marb" class="form-control form-control-sm col-sm-1"  id="contenido2">
</div>
<!-- TER BOT 2-->

<!-- BOT 3-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ctres" role="button" aria-expanded="false" aria-controls="ctres" id="tres" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ctres">
    <input type="text" name="marc" class="form-control form-control-sm col-sm-1" id="contenido3">
</div>
<!-- TER BOT 3-->

<!-- BOT 4-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ccua" role="button" aria-expanded="false" aria-controls="ccua" id="cuatro" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ccua">
    <input type="text" name="mard" class="form-control form-control-sm col-sm-1" id="contenido4">
</div>
<!-- TER BOT 4-->


<!-- BOT 5-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cco" role="button" aria-expanded="false" aria-controls="cco" id="cin" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cco">
    <input type="text" name="mare" class="form-control form-control-sm col-sm-1" id="contenido5">
</div>
<!-- TER BOT 5-->

<!-- BOT 6-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#sei" role="button" aria-expanded="false" aria-controls="sei" id="se" >
  X
  </a>
<div class="collapse collapse-horizontal" id="sei">
    <input type="text" name="marf" class="form-control form-control-sm col-sm-1" id="contenido6">
</div>
<!-- TER BOT 6-->

<!-- BOT 7-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#csie" role="button" aria-expanded="false" aria-controls="csie" id="sie" >
  X
  </a>
<div class="collapse collapse-horizontal" id="csie">
    <input type="text" name="marg" class="form-control form-control-sm col-sm-1" id="contenido7">
</div>
<!-- TER BOT 7-->

<!-- espalda BOT 8-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#coch" role="button" aria-expanded="false" aria-controls="coch" id="oc" >
  X
  </a>
<div class="collapse collapse-horizontal" id="coch">
    <input type="text" name="marh" class="form-control form-control-sm col-sm-1" id="contenido8">
</div>
<!-- TER BOT 8-->


<!-- pie-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#pie" role="button" aria-expanded="false" aria-controls="pie" id="pi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="pie">
    <input type="text" name="pie" class="form-control form-control-sm col-sm-1" id="ipi">
</div>
<!-- pie-->


<!-- pie d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#pied" role="button" aria-expanded="false" aria-controls="pied" id="pid" >
  X
  </a>
<div class="collapse collapse-horizontal" id="pied">
    <input type="text" name="pied" class="form-control form-control-sm col-sm-1" id="ipid">
</div>
<!-- pie d-->

<!-- tob I-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tobiz" role="button" aria-expanded="false" aria-controls="tobiz" id="toi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tobiz">
    <input type="text" name="tod" class="form-control form-control-sm col-sm-1" id="tobi">
</div>
<!-- tob I-->

<!-- tob d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tob" role="button" aria-expanded="false" aria-controls="tob" id="to" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tob">
    <input type="text" name="toi" class="form-control form-control-sm col-sm-1" id="tobd">
</div>
<!-- tob d-->

<!-- rodilla i-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#rod" role="button" aria-expanded="false" aria-controls="rod" id="roi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="rod">
    <input type="text" name="rodi" class="form-control form-control-sm col-sm-1" id="iroi">
</div>
<!-- rodilla i-->

<!-- rodilla d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#rd" role="button" aria-expanded="false" aria-controls="rd" id="brod" >
  X
  </a>
<div class="collapse collapse-horizontal" id="rd">
    <input type="text" name="rodd" class="form-control form-control-sm col-sm-1" id="irod">
</div>
<!-- rodilla d-->

<!-- muslo i-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#muslo" role="button" aria-expanded="false" aria-controls="muslo" id="mui" >
  X
  </a>
<div class="collapse collapse-horizontal" id="muslo">
    <input type="text" name="musloi" class="form-control form-control-sm col-sm-1" id="imi">
</div>
<!-- muslo i-->

<!-- muslo d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#muslod" role="button" aria-expanded="false" aria-controls="muslod" id="mud" >
  X
  </a>
<div class="collapse collapse-horizontal" id="muslod">
    <input type="text" name="muslod" class="form-control form-control-sm col-sm-1" id="imd">
</div>
<!-- muslo d-->

<!-- ingle i-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#inglec" role="button" aria-expanded="false" aria-controls="inglec" id="ingi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="inglec">
    <input type="text" name="inglei" class="form-control form-control-sm col-sm-1" id="ingli">
</div>
<!-- ingle i-->


<!-- ombligo-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ombligo" role="button" aria-expanded="false" aria-controls="ombligo" id="domen" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ombligo">
    <input type="text" name="iabdomen" class="form-control form-control-sm col-sm-1" id="idomen">
</div>
<!-- ombligo-->


<!-- dedoi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoi" role="button" aria-expanded="false" aria-controls="dedoi" id="ddoi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoi">
    <input type="text" name="ddi" class="form-control form-control-sm col-sm-1" id="iddi">
</div>
<!-- dedoi-->

<!-- dedoi2-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoidos" role="button" aria-expanded="false" aria-controls="dedoidos" id="ddoid" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoidos">
    <input type="text" name="ddidos" class="form-control form-control-sm col-sm-1" id="iddid">
</div>
<!-- dedoi2-->


<!-- dedoi3-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoitres" role="button" aria-expanded="false" aria-controls="dedoitres" id="ditr" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoitres">
    <input type="text" name="dditres" class="form-control form-control-sm col-sm-1" id="iditr">
</div>
<!-- dedoi3-->

<!-- dedoi4-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoic" role="button" aria-expanded="false" aria-controls="dedoic" id="dic" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoic">
    <input type="text" name="ddicuatro" class="form-control form-control-sm col-sm-1" id="idic">
</div>
<!-- dedoi4-->

<!-- dedoi5-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoicin" role="button" aria-expanded="false" aria-controls="dedoicin" id="dicin" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoicin">
    <input type="text" name="ddicinco" class="form-control form-control-sm col-sm-1" id="idicin">
</div>
<!-- dedoi5-->

<!-- dedod1-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddu" role="button" aria-expanded="false" aria-controls="ddu" id="bddu" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddu">
    <input type="text" name="ddoderu" class="form-control form-control-sm col-sm-1" id="iddu">
</div>
<!-- dedod1-->

<!-- palmai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#pma" role="button" aria-expanded="false" aria-controls="pma" id="bpmai" >
  X
  </a>
<div class="collapse collapse-horizontal" id="pma">
    <input type="text" name="palmai" class="form-control form-control-sm col-sm-1" id="ipmai">
</div>
<!-- palmai-->


<!-- mulecai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#m" role="button" aria-expanded="false" aria-controls="m" id="bmuñi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="m">
    <input type="text" name="munei" class="form-control form-control-sm col-sm-1" id="imuñi">
</div>
<!-- muñecai-->

<!-- brazoicodo-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#br" role="button" aria-expanded="false" aria-controls="br" id="bbri" >
  X
  </a>
<div class="collapse collapse-horizontal" id="br">
    <input type="text" name="brazi" class="form-control form-control-sm col-sm-1" id="ibri">
</div>
<!-- brazoicodo-->

<!-- brazoci-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cjo" role="button" aria-expanded="false" aria-controls="cjo" id="bbric" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cjo">
    <input type="text" name="brazci" class="form-control form-control-sm col-sm-1" id="ibric">
</div>
<!-- brazoci-->

<!-- hombroi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#hbroi" role="button" aria-expanded="false" aria-controls="hbroi" id="bhomi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="hbroi">
    <input type="text" name="homi" class="form-control form-control-sm col-sm-1" id="ihomi">
</div>
<!-- hombroi-->

<!-- peci-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cpi" role="button" aria-expanded="false" aria-controls="cpi" id="bcpi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cpi">
    <input type="text" name="peci" class="form-control form-control-sm col-sm-1" id="icpi">
</div>
<!-- peci-->

<!-- pecti-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cpei" role="button" aria-expanded="false" aria-controls="cpei" id="bcpei" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cpei">
    <input type="text" name="pecti" class="form-control form-control-sm col-sm-1" id="icpei">
</div>
<!-- pecti-->

<!-- pectd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cped" role="button" aria-expanded="false" aria-controls="cped" id="bcped" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cped">
    <input type="text" name="pectd" class="form-control form-control-sm col-sm-1" id="icped">
</div>
<!-- pectd-->

<!-- clavi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cvi" role="button" aria-expanded="false" aria-controls="cvi" id="bcvi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cvi">
    <input type="text" name="cvi" class="form-control form-control-sm col-sm-1" id="icvi">
</div>
<!-- clavi-->

<!-- dedod2-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddos" role="button" aria-expanded="false" aria-controls="ddos" id="bddos" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddos">
    <input type="text" name="dedodos" class="form-control form-control-sm col-sm-1" id="iddos">
</div>
<!-- dedod2-->

<!-- dedod3-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddtres" role="button" aria-expanded="false" aria-controls="ddtres" id="bddt" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddtres">
    <input type="text" name="dedotres" class="form-control form-control-sm col-sm-1" id="iddt">
</div>
<!-- dedod3-->

<!-- dedod4-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddcuatro" role="button" aria-expanded="false" aria-controls="ddcuatro" id="bddc" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddcuatro">
    <input type="text" name="dedocuatro" class="form-control form-control-sm col-sm-1" id="iddc">
</div>
<!-- dedod4-->

<!-- dedod5-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddcinco" role="button" aria-expanded="false" aria-controls="ddcinco" id="bddcinco" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddcinco">
    <input type="text" name="dedocincoo" class="form-control form-control-sm col-sm-1" id="iddcinco">
</div>
<!-- dedod5-->

<!-- palmaderecha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#palmad" role="button" aria-expanded="false" aria-controls="palmad" id="bpalmad" >
  X
  </a>
<div class="collapse collapse-horizontal" id="palmad">
    <input type="text" name="palmad" class="form-control form-control-sm col-sm-1" id="ipalmad">
</div>
<!-- palmaderecha-->

<!-- muñecaderecha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#munecader" role="button" aria-expanded="false" aria-controls="munecader" id="bmund" >
  X
  </a>
<div class="collapse collapse-horizontal" id="munecader">
    <input type="text" name="munecad" class="form-control form-control-sm col-sm-1" id="imund">
</div>
<!-- muñecaderecha-->

<!-- brazoderecho-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dbrazo" role="button" aria-expanded="false" aria-controls="dbrazo" id="bdbr" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dbrazo">
    <input type="text" name="derbraz" class="form-control form-control-sm col-sm-1" id="idbr">
</div>
<!-- brazoderecho-->


<!-- antebrazoder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#anteder" role="button" aria-expanded="false" aria-controls="anteder" id="babd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="anteder">
    <input type="text" name="annbraz" class="form-control form-control-sm col-sm-1" id="ianbd">
</div>
<!-- antbrazoder-->

<!-- conder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#conejod" role="button" aria-expanded="false" aria-controls="conejod" id="bcder" >
  X
  </a>
<div class="collapse collapse-horizontal" id="conejod">
    <input type="text" name="cconder" class="form-control form-control-sm col-sm-1" id="icder">
</div>
<!-- conder-->

<!-- hombroderecho-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#hombroderecho" role="button" aria-expanded="false" aria-controls="hombroderecho" id="bhder" >
  X
  </a>
<div class="collapse collapse-horizontal" id="hombroderecho">
    <input type="text" name="hombrod" class="form-control form-control-sm col-sm-1" id="ihder">
</div>
<!-- hombroderecho-->


<!-- mandibulader-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#mander" role="button" aria-expanded="false" aria-controls="mander" id="bmand" >
  X
  </a>
<div class="collapse collapse-horizontal" id="mander">
    <input type="text" name="mandiderr" class="form-control form-control-sm col-sm-1" id="imand">
</div>
<!-- mandibulader-->


<!-- mandibulacentro-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#mancentro" role="button" aria-expanded="false" aria-controls="mancentro" id="bmanc" >
  X
  </a>
<div class="collapse collapse-horizontal" id="mancentro">
    <input type="text" name="mandicentroo" class="form-control form-control-sm col-sm-1" id="imanc">
</div>
<!-- mandibulacentro-->

<!-- mandibulaizquierda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#manizquierda" role="button" aria-expanded="false" aria-controls="manizquierda" id="bmaniz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="manizquierda">
    <input type="text" name="mandiizqui" class="form-control form-control-sm col-sm-1" id="imaniz">
</div>
<!-- mandibulaizquierda-->

<!-- mejilla derecha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#mejillad" role="button" aria-expanded="false" aria-controls="mejillad" id="bmejd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="mejillad">
    <input type="text" name="mejderecha" class="form-control form-control-sm col-sm-1" id="imejd">
</div>
<!-- mejilla derecha-->

<!--nariz-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#conariz" role="button" aria-expanded="false" aria-controls="conariz" id="bnariz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="conariz">
    <input type="text" name="narizc" class="form-control form-control-sm col-sm-1" id="inariz">
</div>
<!--nariz-->

<!--frenteizq-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#frentei" role="button" aria-expanded="false" aria-controls="frentei" id="bfrentei" >
  X
  </a>
<div class="collapse collapse-horizontal" id="frentei">
    <input type="text" name="frenteizquierda" class="form-control form-control-sm col-sm-1" id="ifrentei">
</div>
<!--frenteizq-->

<!--frenteder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#frented" role="button" aria-expanded="false" aria-controls="frented" id="bfrented" >
  X
  </a>
<div class="collapse collapse-horizontal" id="frented">
    <input type="text" name="frentederecha" class="form-control form-control-sm col-sm-1" id="ifrented">
</div>
<!--frenteder-->


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo1" role="button" aria-expanded="false" aria-controls="nuevo1" id="nuevo01" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo1">
    <input type="text" name="nuevo1" class="form-control form-control-sm col-sm-1" id="inuevo1">
</div>


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo2" role="button" aria-expanded="false" aria-controls="nuevo2" id="nuevo02" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo2">
    <input type="text" name="nuevo2" class="form-control form-control-sm col-sm-1" id="inuevo2">
</div>


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo3" role="button" aria-expanded="false" aria-controls="nuevo3" id="nuevo03" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo3">
    <input type="text" name="nuevo3" class="form-control form-control-sm col-sm-1" id="inuevo3">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo4" role="button" aria-expanded="false" aria-controls="nuevo4" id="nuevo04" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo4">
    <input type="text" name="nuevo4" class="form-control form-control-sm col-sm-1" id="inuevo4">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo5" role="button" aria-expanded="false" aria-controls="nuevo5" id="nuevo05" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo5">
    <input type="text" name="nuevo5" class="form-control form-control-sm col-sm-1" id="inuevo5">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo6" role="button" aria-expanded="false" aria-controls="nuevo6" id="nuevo06" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo6">
    <input type="text" name="nuevo6" class="form-control form-control-sm col-sm-1" id="inuevo6">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo7" role="button" aria-expanded="false" aria-controls="nuevo7" id="nuevo07" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo7">
    <input type="text" name="nuevo7" class="form-control form-control-sm col-sm-1" id="inuevo7">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo8" role="button" aria-expanded="false" aria-controls="nuevo8" id="nuevo08" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo8">
    <input type="text" name="nuevo8" class="form-control form-control-sm col-sm-1" id="inuevo8">
</div>






<!---------- ESPALDA------------>

<!--plantapie-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#plantapiei" role="button" aria-expanded="false" aria-controls="plantapiei" id="bppi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="plantapiei">
    <input type="text" name="plantapiea" class="form-control form-control-sm col-sm-1" id="ippi">
</div>
<!--plantapie-->

<!--plantapieder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#plantapieder" role="button" aria-expanded="false" aria-controls="plantapieder" id="bppd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="plantapieder">
    <input type="text" name="plantapieader" class="form-control form-control-sm col-sm-1" id="ippd">
</div>
<!--plantapieder-->


<!--tobati-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tobilloiz" role="button" aria-expanded="false" aria-controls="tobilloiz" id="btia" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tobilloiz">
    <input type="text" name="tobilloati" class="form-control form-control-sm col-sm-1" id="itia">
</div>
<!--tobati-->

<!--tobatd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tobilloder" role="button" aria-expanded="false" aria-controls="tobilloder" id="btda" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tobilloder">
    <input type="text" name="tobilloatd" class="form-control form-control-sm col-sm-1" id="itda">
</div>
<!--tobatd-->

<!--pti-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#chami" role="button" aria-expanded="false" aria-controls="chami" id="bpani" >
  X
  </a>
<div class="collapse collapse-horizontal" id="chami">
    <input type="text" name="ptiatras" class="form-control form-control-sm col-sm-1" id="ipani">
</div>
<!--pti-->

<!--ptd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#chamd" role="button" aria-expanded="false" aria-controls="chamd" id="bpand" >
  X
  </a>
<div class="collapse collapse-horizontal" id="chamd">
    <input type="text" name="ptdatras" class="form-control form-control-sm col-sm-1" id="ipand">
</div>
<!--ptd-->


<!--piernaiespalda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#piernaiespalda" role="button" aria-expanded="false" aria-controls="piernaiespalda" id="bpchi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="piernaiespalda">
    <input type="text" name="pierespaldai" class="form-control form-control-sm col-sm-1" id="ipchi">
</div>
<!--piernaiespalda-->

<!--piernadespalda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#piernadespalda" role="button" aria-expanded="false" aria-controls="piernadespalda" id="bpchd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="piernadespalda">
    <input type="text" name="pierespaldad" class="form-control form-control-sm col-sm-1" id="ipchd">
</div>
<!--piernadespalda-->

<!--musloatrasi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#musloatrasi" role="button" aria-expanded="false" aria-controls="musloatrasi" id="bmusai" >
  X
  </a>
<div class="collapse collapse-horizontal" id="musloatrasi">
    <input type="text" name="musloatrasiz" class="form-control form-control-sm col-sm-1" id="imusai">
</div>
<!--musloatrasi-->

<!--musloatrasd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#musloatrasd" role="button" aria-expanded="false" aria-controls="musloatrasd" id="bmusad" >
  X
  </a>
<div class="collapse collapse-horizontal" id="musloatrasd">
    <input type="text" name="musloatrasder" class="form-control form-control-sm col-sm-1" id="imusad">
</div>
<!--musloatrasd-->

<!--gluteosi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#gluteosi" role="button" aria-expanded="false" aria-controls="gluteosi" id="bglui" >
  X
  </a>
<div class="collapse collapse-horizontal" id="gluteosi">
    <input type="text" name="glutiz" class="form-control form-control-sm col-sm-1" id="iglui">
</div>
<!--gluteosi-->

<!--gluteosd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#gluteosd" role="button" aria-expanded="false" aria-controls="gluteosd" id="bglud" >
  X
  </a>
<div class="collapse collapse-horizontal" id="gluteosd">
    <input type="text" name="glutder" class="form-control form-control-sm col-sm-1" id="iglud">
</div>
<!--gluteosd-->


<!--cinturai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cinturai" role="button" aria-expanded="false" aria-controls="cinturai" id="bcini" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cinturai">
    <input type="text" name="cinturaiz" class="form-control form-control-sm col-sm-1" id="icini">
</div>
<!--cinturai-->

<!--cinturad-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cinturad" role="button" aria-expanded="false" aria-controls="cinturad" id="bcind" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cinturad">
    <input type="text" name="cinturader" class="form-control form-control-sm col-sm-1" id="icind">
</div>
<!--cinturad-->

<!--costillasai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#costillasai" role="button" aria-expanded="false" aria-controls="costillasai" id="bcosi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="costillasai">
    <input type="text" name="costilliz" class="form-control form-control-sm col-sm-1" id="icosi">
</div>
<!--costillasai-->

<!--costillasad-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#costillasad" role="button" aria-expanded="false" aria-controls="costillasad" id="bcosd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="costillasad">
    <input type="text" name="costillder" class="form-control form-control-sm col-sm-1" id="icosd">
</div>
<!--costillasad-->

<!--espaldarribai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espaldarribai" role="button" aria-expanded="false" aria-controls="espaldarribai" id="besai" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espaldarribai">
    <input type="text" name="espaldarribaiz" class="form-control form-control-sm col-sm-1" id="iesai">
</div>
<!--espaldarribai-->

<!--espaldarribad-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espaldarribad" role="button" aria-expanded="false" aria-controls="espaldarribad" id="besad" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espaldarribad">
    <input type="text" name="espaldaarribader" class="form-control form-control-sm col-sm-1" id="iesad">
</div>
<!--espaldarribad-->

<!--espaldalta-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espaldalta" role="button" aria-expanded="false" aria-controls="espaldalta" id="besalt" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espaldalta">
    <input type="text" name="espaldaalta" class="form-control form-control-sm col-sm-1" id="iesalt">
</div>
<!--espaldalta-->

<!--dorsaliz mano-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dorsaliz" role="button" aria-expanded="false" aria-controls="dorsaliz" id="bdorsali" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dorsaliz">
    <input type="text" name="dorsaliz" class="form-control form-control-sm col-sm-1" id="idorsali">
</div>
<!--dorsaliz-->

<!--dorsalder mano-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dorsalder" role="button" aria-expanded="false" aria-controls="dorsalder" id="bdorsald" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dorsalder">
    <input type="text" name="dorsalder" class="form-control form-control-sm col-sm-1" id="idorsald">
</div>
<!--dorsalder-->

<!--munecaatrasi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#munecaatrasi" role="button" aria-expanded="false" aria-controls="munecaatrasi" id="bmuneati" >
  X
  </a>
<div class="collapse collapse-horizontal" id="munecaatrasi">
    <input type="text" name="munecaatrasiz" class="form-control form-control-sm col-sm-1" id="imuneati">
</div>
<!--munecaatrasi-->

<!--munecaatrasd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#munecaatrasd" role="button" aria-expanded="false" aria-controls="munecaatrasd" id="bmuneatd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="munecaatrasd">
    <input type="text" name="munecaatrasder" class="form-control form-control-sm col-sm-1" id="imuneatd">
</div>
<!--munecaatrasd-->

<!--antebiesp-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#antebiesp" role="button" aria-expanded="false" aria-controls="antebiesp" id="banbei" >
  X
  </a>
<div class="collapse collapse-horizontal" id="antebiesp">
    <input type="text" name="antebiesp" class="form-control form-control-sm col-sm-1" id="ianbei">
</div>
<!--antebiesp-->

<!--antebdesp-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#antebdesp" role="button" aria-expanded="false" aria-controls="antebdesp" id="banbed" >
  X
  </a>
<div class="collapse collapse-horizontal" id="antebdesp">
    <input type="text" name="antebdesp" class="form-control form-control-sm col-sm-1" id="ianbed">
</div>
<!--antebdesp-->

<!--casicodoi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#casicodoi" role="button" aria-expanded="false" aria-controls="casicodoi" id="bccodoi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="casicodoi">
    <input type="text" name="casicodoi" class="form-control form-control-sm col-sm-1" id="iccodoi">
</div>
<!--casicodoi-->

<!--casicododer-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#casicododer" role="button" aria-expanded="false" aria-controls="casicododer" id="bccodod" >
  X
  </a>
<div class="collapse collapse-horizontal" id="casicododer">
    <input type="text" name="casicododer" class="form-control form-control-sm col-sm-1" id="iccodod">
</div>
<!--casicododer-->

<!--brazalti-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#brazalti" role="button" aria-expanded="false" aria-controls="brazalti" id="bbaiz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="brazalti">
    <input type="text" name="brazalti" class="form-control form-control-sm col-sm-1" id="ibaiz">
</div>
<!--brazalti-->

<!--brazaltder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#brazaltder" role="button" aria-expanded="false" aria-controls="brazaltder" id="bbader" >
  X
  </a>
<div class="collapse collapse-horizontal" id="brazaltder">
    <input type="text" name="brazaltder" class="form-control form-control-sm col-sm-1" id="ibader">
</div>
<!--brazaltder-->

<!--cuelloatrasbajo-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cuelloatrasbajo" role="button" aria-expanded="false" aria-controls="cuelloatrasbajo" id="bcbajo" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cuelloatrasbajo">
    <input type="text" name="cuellatrasb" class="form-control form-control-sm col-sm-1" id="icbajo">
</div>
<!--cuelloatrasbajo-->

<!--cuelloatrasmedio-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cuelloatrasmedio" role="button" aria-expanded="false" aria-controls="cuelloatrasmedio" id="bcmedio" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cuelloatrasmedio">
    <input type="text" name="cuellatrasmedio" class="form-control form-control-sm col-sm-1" id="icmedio">
</div>
<!--cuelloatrasmedio-->

<!--cabezadorsalmedia-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cabezadorsalmedia" role="button" aria-expanded="false" aria-controls="cabezadorsalmedia" id="bcabezamedio" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cabezadorsalmedia">
    <input type="text" name="cabedorsalm" class="form-control form-control-sm col-sm-1" id="icabezamedio">
</div>
<!--cabezadorsalmedia-->

<!--cabezaaltaizquierda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cabezaaltaizquierda" role="button" aria-expanded="false" aria-controls="cabezaaltaizquierda" id="bcabaiz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cabezaaltaizquierda">
    <input type="text" name="cabealtaizqu" class="form-control form-control-sm col-sm-1" id="icabaiz">
</div>
<!--cabezaaltaizquierda-->

<!--cabezaaltadercha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cabezaaltadercha" role="button" aria-expanded="false" aria-controls="cabezaaltadercha" id="bcabader" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cabezaaltadercha">
    <input type="text" name="cabezaaltader" class="form-control form-control-sm col-sm-1" id="icabader">
</div>
<!--cabezaaltadercha-->

<!--espinillaizquierda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espinillaizquierda" role="button" aria-expanded="false" aria-controls="espinillaizquierda" id="espi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espinillaizquierda">
    <input type="text" name="espizq" class="form-control form-control-sm col-sm-1" id="cssespi">
</div>
<!--espinillaizquierda-->


<!--espinillader-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espinillader" role="button" aria-expanded="false" aria-controls="espinillader" id="espd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espinillader">
    <input type="text" name="espder" class="form-control form-control-sm col-sm-1" id="cssespd">
</div>
<!--espinillader-->

<!--coxix-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#coxix" role="button" aria-expanded="false" aria-controls="coxix" id="coxis" >
  X
  </a>
<div class="collapse collapse-horizontal" id="coxix">
    <input type="text" name="coxis" class="form-control form-control-sm col-sm-1" id="csscoxis">
</div>
<!--coxix-->

 <center><img src="../../img/marcaje_qx.jpg" height="550" id="im"></center>
     
    </div>
     
  </div>
</div>
<P></P>

<tr>
    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>MEDIDAS PARA LA PREVENCIÓN DE RIESGO DE CAÍDAS</center></strong>
    </div>
    <center><i>Recuerda que un paciente en quirófano y recuperación siempre tendrá un riesgo ALTO</i></center>
    <td><p>
      <div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="oxi">
 ¿Barandales de cama o camilla elevados?
  </label>
    </div>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="oxi" id="oxi">
    </div>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="con">
  ¿Vigilado por el personal de área?
  </label>
    </div>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="con" id="con">
    </div>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="muc">
 ¿Se colocaron sujetadores?
  </label>
    </div>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="muc" id="muc">
    </div>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="vent">
 ¿Se aseguró al paciente antes de cambio de cama o camilla?
  </label>
    </div>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="vent" id="vent">
    </div>
  </div>
</div> 
<div class="container">
  <div class="row">
    <div class="col-sm-10">
    <label class="form-check-label" for="est">
  ¿Se aseguró al paciente antes de realizar movimiento de cambio de posición del paciente o de la mesa quirúrgica?
  </label>
    </div>
    <div class="col-sm">
       <input class="form-check-input" type="checkbox" value="SI" name="est" id="est">
    </div>
  </div>
</div> 
</td>
<hr>


    <p></p>
     <div class="row">
    <div class="col-sm">
        <strong>Nota de Recuperación</strong>
        <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="btnStartRecordposto"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="btnStopRecordofofof"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playadefp"><i class="fas fa-play"></button></i>
</div>
        <textarea class="form-control" name="notapost" id="textopp"></textarea>
        
         <script type="text/javascript">
const btnStartRecordposto = document.getElementById('btnStartRecordposto');
const btnStopRecordofofof = document.getElementById('btnStopRecordofofof');
const textopp = document.getElementById('textopp');

const btnPlayTextdea = document.getElementById('playadefp');

  btnPlayTextdea.addEventListener('click', () => {
          leerTexto(textopp.value);
  });

  function leerTexto(textopp){
      const speech = new SpeechSynthesisUtterance();
      speech.text= textopp;
      speech.volume=1;
      speech.rate=1;
      speech.pitch=0;
      window.speechSynthesis.speak(speech);
  }

     let recognitionnotaposto = new webkitSpeechRecognition();
      recognitionnotaposto.lang = "es-ES";
      recognitionnotaposto.continuous = true;
      recognitionnotaposto.interimResults = false;

      recognitionnotaposto.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        textopp.value += frase;
      }

      btnStartRecordposto.addEventListener('click', () => {
        recognitionnotaposto.start();
      });

      btnStopRecordofofof.addEventListener('click', () => {
        recognitionnotaposto.abort();
      });
</script>
        </div>
        </div>
    <p></p>
 
</div> <!--fin del div contanier-->
<hr>
<center>
     <button type="submit" name="btnpos" id="btnpos" class="btn btn-primary">Firmar y guardar</button>
 </center>
    </form>
    <?php }?>
</div>

<!--FIN NOTA POSOPERATORIA--

<!-- SCRIPT AJAX-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnpos').click(function(){
            var datos=$('#posto').serialize();
            $.ajax({
                type:"POST",
                url:"insertarpostop.php",

                data:datos,
                success:function(r){
                    if(r=1){
                        $("#barra").load(" #barra");
                       //$("#tabs").load("vista_enf_quirurgico.php #tabs");
                       alertify.success("Nota postoperatoria guardada");
                       document.getElementById("posto").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>


<!--PESTAÑA CONTROL TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES TEXTILES-->
<br>
<div class="tab-pane fade" id="nav-control" role="tabpanel" aria-labelledby="nav-control-tab">
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>CONTROL DE TEXTILES</center></strong>
</div><br>

<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalT" class="btn btn-success">Agregar nuevos textiles</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoT" placeholder="Buscar...">
            </div>
            <table id="exampleT" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de registro</th>
                <th>Fecha de reporte</th>
                <th>Material</th>
                <th>Inicio</th>
                <th>Dentro</th>
                <th>Fuera</th>
                <th>Total</th>
                <th>Registró</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
            
            <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleT').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataT.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [7]
          },

        ]
      });
    });
    $(document).on('submit', '#addUserT', function(e) {
      e.preventDefault();
      

	var fechat = $('#addfechatField').val();
	var mat = $('#addmatField').val();
    var inicio = $('#addinicioField').val();
    var dentro = $('#adddentroField').val();
    var fuera = $('#addfueraField').val();
            
      if (fechat != '' && mat != '') {
        $.ajax({
          url: "add_userT.php",
          type: "post",
          data: {
		    fechat: fechat,
            mat: mat,
            inicio: inicio,
            dentro: dentro,
            fuera: fuera
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleT').DataTable();
              mytable.draw();
              document.getElementById("addUserT").reset();
              $('#addUserModalT').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserT', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var fechat = $('#fechatField').val();
      var mat = $('#matField').val();
      var inicio = $('#inicioField').val();
      var dentro = $('#dentroField').val();
      var fuera = $('#fueraField').val();
      var fecha_registrot = $('#fecha_registrotField').val();
      var id_usuat = $('#id_usuatField').val();
  var id_usua2t = $('#id_usua2tField').val();
  var total = $('#totalField').val();
  
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (fechat != '' && mat != '') {
        $.ajax({
          url: "update_userT.php",
          type: "post",
          data: {
            fechat: fechat,
            mat: mat,
            inicio: inicio,
            dentro: dentro,
            fuera:fuera,
            fecha_registrot:fecha_registrot,
            id_usuat:id_usuat,
              id_usua2t:id_usua2t,
              total:total,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleT').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnT">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnT">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id,fecha_registrot,fechat,mat,inicio,dentro,fuera,inicio,id_usuat+id_usua2t,button]);
              $('#exampleModalT').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleT').on('click', '.editbtnT ', function(event) {
      var table = $('#exampleT').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalT').modal('show');

      $.ajax({
        url: "get_single_dataT.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#fechatField').val(json.fechare);
          $('#matField').val(json.mat);
          $('#inicioField').val(json.inicio);
          $('#dentroField').val(json.dentro);
          $('#fueraField').val(json.fuera);
          $('#id_usuatField').val(json.id_usua);
           $('#id_usua2tField').val(json.id_usua2);
          $('#fecha_registrotField').val(json.text_fecha);
          $('#totalField').val(json.total);
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnT', function(event) {
      var table = $('#exampleT').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("¿Estas seguro de eliminar este registro? ")) {
        $.ajax({
          url: "delete_userT.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalT" tabindex="-1" aria-labelledby="exampleModalLabeliT" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabeliT">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUserT">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            
             <input type="hidden" class="form-control" id="fecha_registrotField" name="fecha_registrot">
              <input type="hidden" class="form-control" id="id_usuatField" name="id_usuat">
            <input type="hidden" class="form-control" id="id_usua2tField" name="id_usua2t">
                <input type="hidden" class="form-control" id="totalField" name="total">
            <div class="mb-3 row">
              <label for="fechatField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="fechatField" name="fechat">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="matField" class="col-md-3 form-label">Materiales</label>
              <div class="col-md-9">
                   <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="mat" id="matField" style="width : 100%; heigth : 100%">
        <option value="">Seleccionar materiales</option>
        <option value="Gasas">Gasas</option>
        <option value="Compresas">Compresas</option>
        <option value="Push">Push</option>
        <option value="Cotonoides">Cotonoides</option>
        <option value="Instrumental">Instrumental</option>
        <option value="Agujas">Agujas</option>
       <option value="Otros">Otros</option>
        </select>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="inicioField" class="col-sm-3 form-label">Inicio</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="inicioField" name="inicio">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="dentroField" class="col-md-3 form-label">Dentro</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="dentroField" name="dentro">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fueraField" class="col-md-3 form-label">Fuera</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="fueraField" name="fuera">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalT" tabindex="-1" aria-labelledby="exampleModalLabelllT" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelllT">Nuevo registro de Textiles</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserT" action="">
              <div class="mb-3 row">
              <label for="addfechatField" class="col-sm-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                   <?php $fr = date("Y-m-d H:i");?>
                <input type="date" class="form-control" id="addfechatField" name="fechat"  value="<?php echo $fecha_actual = date("Y-m-d");?>">
              </div>
            </div>
              <div class="mb-3 row">
              <label for="addmatField" class="col-md-3 form-label">Materiales</label>
              <div class="col-md-9">
                  <select data-live-search="true" name="mat" id="addmatField" class="form-control" style="width : 100%; heigth : 100%">
        <option value="">Seleccionar materiales</option>
        <option value="Gasas">Gasas</option>
        <option value="Compresas">Compresas</option>
        <option value="Push">Push</option>
        <option value="Cotonoides">Cotonoides</option>
        <option value="Instrumental">Instrumental</option>
        <option value="Agujas">Agujas</option>
       <option value="Otros">Otros</option>
        </select>
              
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addinicioField" class="col-md-3 form-label">Inicio</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="addinicioField" name="inicio" >
              </div>
            </div>
            <div class="mb-3 row">
              <label for="adddentroField" class="col-md-3 form-label">Dentro</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="adddentroField" name="dentro"  >
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addfueraField" class="col-md-3 form-label">Fuera</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="addfueraField" name="fuera" >
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>   



</div>



<!-- INICIO PESAÑA NOTA-->
  <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

<?php 
$repreop = $conexion->query("SELECT * FROM enf_quirurgico P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.id_atencion=$id_atencion") or die($conexion->error);
              while ($row_p = $repreop->fetch_assoc()) {
                $id_quir=$row_p['id_quir'];
                $id_atencion_p=$row_p['id_atencion'];
              } 
          
?>

<?php if(isset($id_quir) and isset($id_atencion_p)){
   
 
$repreop = $conexion->query("SELECT * FROM enf_quirurgico P, dat_ingreso DI WHERE DI.id_atencion=$id_atencion and P.id_atencion=$id_atencion order by id_quir ASC limit 1") or die($conexion->error);
              while ($row_p = $repreop->fetch_assoc()) {
                     $frpar=date_create($row_p['fecha']); 
               ?>
<!--Si ya existela nota se saca para que la editen-->   
<form  method="POST" id="notaj2" name="notaj2">
      <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>PREOPERATORIO</center></strong>
  </div>
<div class="container">
<!--<div class="col-sm-2">
                        <label class="control-label"><strong>Fecha reporte:</strong> </label>
                        
                         <input type="text" name="" id="" class="form-control" value="<?php echo date_format($frpar,'d-m-Y') ?>" disabled>
                    </div>-->
                         <input type="hidden" name="id_quir2" value="<?php echo $row_p['id_quir'];?>">
<div class="col-sm-3">
                        <label class="control-label"><strong>Hora:</strong> </label>
                         <input type="time" name="hora" id="hora" class="form-control" value="<?php echo $row_p['hora'];?>">
</div>

  
  <p></p>
   <div class="col-sm-7">
      <label><strong>Cirugía programada:</strong></label>
      <input type="text" name="cir_prog" class="form-control" value="<?php echo $row_p['cir_prog'];?>">
     
    </div>
    </div>
    <div class="container">
                    <hr>
                    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>NOTAS DE ENFERMERIA</center></strong>
</div> 
     <div class="row">
            <div class="col-sm-3">
                        <label class="control-label">Hora:</label>
                         <input type="time" name="horac" id="horac" class="form-control" value="<?php echo $row_p['horac'];?>">
                    </div>
         <div class="col">
             Condición inicial: <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="cig"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="enis"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pcind"><i class="fas fa-play"></button></i>
<textarea name="not_preop" class="form-control" id="not_preop" disabled rows="1"><?php echo $row_p['not_preop'];?></textarea>
</div><textarea name="not_preop2" class="form-control" id="not_preop"></textarea><p></p>
<script type="text/javascript">
const cig = document.getElementById('cig');
const enis = document.getElementById('enis');
const not_preop = document.getElementById('not_preop');

const btnPlayincon = document.getElementById('pcind');

btnPlayincon.addEventListener('click', () => {
        leerTexto(not_preop.value);
});

function leerTexto(not_preop){
    const speech = new SpeechSynthesisUtterance();
    speech.text= not_preop;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rci = new webkitSpeechRecognition();
      rci.lang = "es-ES";
      rci.continuous = true;
      rci.interimResults = false;

      rci.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        not_preop.value += frase;
      }

      cig.addEventListener('click', () => {
        rci.start();
      });

      enis.addEventListener('click', () => {
        rci.abort();
      });
</script>
         </div>
        
     </div>


 
 <div class="row">
      <div class="col-sm-3">
      
      <div class="form-group">
    <label>Hora de inicio de anestesia:</label>
<input type="time" name="in_isq" class="form-control" value="<?php echo $row_p['in_isq'];?>">
      </div>
      </div>
         <div class="col">
             
             Tipo anestesia:<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="tipog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="aness"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pnom2"><i class="fas fa-play"></button></i>
<textarea name="tipan" class="form-control" id="tipan" disabled rows="1"><?php echo $row_p['tipan'];?></textarea>
</div><textarea name="tipan2" class="form-control" id="tipan" ></textarea>
<script type="text/javascript">
const tipog = document.getElementById('tipog');
const aness = document.getElementById('aness');
const tipan = document.getElementById('tipan');

const btnPlaytipoaa = document.getElementById('pnom2');

btnPlaytipoaa.addEventListener('click', () => {
        leerTexto(tipan.value);
});

function leerTexto(tipan){
    const speech = new SpeechSynthesisUtterance();
    speech.text= tipan;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rta = new webkitSpeechRecognition();
      rta.lang = "es-ES";
      rta.continuous = true;
      rta.interimResults = false;

      rta.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        tipan.value += frase;
      }

      tipog.addEventListener('click', () => {
        rta.start();
      });

      aness.addEventListener('click', () => {
        rta.abort();
      });
</script>
         </div>
       
     </div>

<p></p>
  
  <div class="row">
   
      <div class="col-sm-3">
                        <label class="control-label">Hora: </label>
                         <input type="time" name="horaas" id="horaas" class="form-control" required value="<?php echo $row_p['horaas'];?>">
                    </div>
   <div class="col-sm-4">
      <label>Asepsia:</label>
     <select name="asepsia" class="form-control" id="not_post">
          <option value="<?php echo $row_p['asepsia'];?>"><?php echo $row_p['asepsia'];?></option>
    <option value="" disabled>Seleccionar asepsia</option>
     <option value="Isodine espuma">Isodine espuma</option>
      <option value="Isodine solucion">Isodine solución</option>
<option value="Jabon">Jabón</option>
      <option value="Merthiolate">Merthiolate</option>
      <option value="Benzal">Benzal</option>
        <option value="Duraprep">Duraprep</option>
          <option value="Cloraprep">Cloraprep</option>
</select>
    </div>
      
      <div class="col-sm-5">
           <label>Otros:</label>
          <input type="text" name="otros_asep" id="otros_asep" class="form-control" disabled value="<?php echo $row_p['otros_asep'];?>">
          <input type="text" name="otros_asep2" id="otros_asep" class="form-control">
          </div>
          </div>
          </div>
          
          <center><br>
<button type="submit" name="btnno2" id="btnno2" class="btn btn-primary">Firmar</button>
<button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button></center>
          </form>
          <!-- SCRIPT AJAX EDITAR-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnno2').click(function(){
            var datos=$('#notaj2').serialize();
          
            $.ajax({
                type:"POST",
                url:"actualizar_regquir.php",
                data:datos,
                success:function(r){
                    if(r=1){
                        $("#nav-home").load("vista_enf_quirurgico.php #notaj2");
                       alertify.success("Nota actualizada con éxito");
                       document.getElementById("notaj2").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>
</div>
          <br>
<?php    
}

}else{
    
?>
<form  method="POST" id="notaj" name="notaj">
      <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>PREOPERATORIO</center></strong>
  </div>
<div class="container">
  <div class="row">
      
      <div class="col-sm-2">
          <?php $fr = date("Y-m-d H:i");?>

                        <label class="control-label"><strong>Fecha reporte:</strong> </label>
                         <input type="date" name="fecha_reporte" id="fecha_reporte" class="form-control" value="<?php echo $fecha_actual = date("Y-m-d");?>" required>
                    </div>
                    
                    <div class="col-sm-2">
                        <label class="control-label"><strong>Hora:</strong> </label>
                         <input type="time" name="hora" id="hora" class="form-control" required>
                    </div>
                   
                
<p></p>
   <div class="col-sm-8">
                        <label class="control-label"><strong>Cirugía programada:</strong> </label>
                        <input type="text" name="cir_prog" class="form-control">
                  
                    </div>
                    <p></p>
                      </div>
                    <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>NOTAS DE ENFERMERIA</center></strong>
</div> 
     <div class="row">
            <div class="col-sm-3">
                        <label class="control-label">Hora:</label>
                         <input type="time" name="horac" id="horac" class="form-control" required>
                    </div>
         <div class="col">
             Condición inicial: <div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="cig"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="enis"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pcind"><i class="fas fa-play"></button></i>
</div><textarea name="not_preop" class="form-control" id="not_preop"></textarea><p></p>
<script type="text/javascript">
const cig = document.getElementById('cig');
const enis = document.getElementById('enis');
const not_preop = document.getElementById('not_preop');

const btnPlayincon = document.getElementById('pcind');

btnPlayincon.addEventListener('click', () => {
        leerTexto(not_preop.value);
});

function leerTexto(not_preop){
    const speech = new SpeechSynthesisUtterance();
    speech.text= not_preop;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rci = new webkitSpeechRecognition();
      rci.lang = "es-ES";
      rci.continuous = true;
      rci.interimResults = false;

      rci.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        not_preop.value += frase;
      }

      cig.addEventListener('click', () => {
        rci.start();
      });

      enis.addEventListener('click', () => {
        rci.abort();
      });
</script>
         </div>
        
     </div>


 
 <div class="row">
      <div class="col-sm-3">
      
      <div class="form-group">
    <label>Hora de inicio de anestesia:</label>
<input type="time" name="in_isq" class="form-control">
      </div>
      </div>
         <div class="col">
             
             Tipo anestesia:<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="tipog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="aness"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="pnom2"><i class="fas fa-play"></button></i>
</div><textarea name="tipan" class="form-control" id="tipan" ></textarea>
<script type="text/javascript">
const tipog = document.getElementById('tipog');
const aness = document.getElementById('aness');
const tipan = document.getElementById('tipan');

const btnPlaytipoaa = document.getElementById('pnom2');

btnPlaytipoaa.addEventListener('click', () => {
        leerTexto(tipan.value);
});

function leerTexto(tipan){
    const speech = new SpeechSynthesisUtterance();
    speech.text= tipan;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rta = new webkitSpeechRecognition();
      rta.lang = "es-ES";
      rta.continuous = true;
      rta.interimResults = false;

      rta.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        tipan.value += frase;
      }

      tipog.addEventListener('click', () => {
        rta.start();
      });

      aness.addEventListener('click', () => {
        rta.abort();
      });
</script>
         </div>
       
     </div>

<p></p>
  
  <div class="row">
   
      <div class="col-sm-3">
                        <label class="control-label">Hora: </label>
                         <input type="time" name="horaas" id="horaas" class="form-control" required>
                    </div>
   <div class="col-sm-4">
      <label>Asepsia:</label>
     <select name="asepsia" class="form-control" id="not_post">
    <option value="">Seleccionar asepsia</option>
     <option value="Isodine espuma">Isodine espuma</option>
      <option value="Isodine solucion">Isodine solución</option>
<option value="Jabon">Jabón</option>
      <option value="Merthiolate">Merthiolate</option>
      <option value="Benzal">Benzal</option>
        <option value="Duraprep">Duraprep</option>
          <option value="Cloraprep">Cloraprep</option>
</select>
    </div>
      
      <div class="col-sm-5">
           <label>Otros:</label>
          <input type="text" name="otros_asep" id="otros_asep" class="form-control" required>
          </div>
     
   <br>

<p>
<p>
</p>

<div class="collapse" id="div1" style="display:;">
<div class="card card-body">
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
<tr><strong><center>SOLICITUD DE ESTUDIO Y DISPOSICIÓN DE PIEZAS ANATOMOPATOLÓGICAS</center></strong>
</div><p>
    <div class="container">
  <div class="row">
    <div class="col-sm-4">
     <center><button type="button" class="btn btn-success btn-sm" id="playtratm"><i class="fas fa-play"></button></i> Médico tratante:</center>
    </div>
    <div class="col-sm">
    <input type="text" name="p_medico" class="form-control" id="txtmmtt">
    <script type="text/javascript">
const txtmmtt = document.getElementById('txtmmtt');
const btnPlayTexttt = document.getElementById('playtratm');

btnPlayTexttt.addEventListener('click', () => {
        leerTexto(txtmmtt.value);
});

function leerTexto(txtmmtt){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtmmtt;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
    </div>
  </div>
</div>
<p>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
    <center>Disposición final de la pieza:</center>
    </div>
    <div class="col-sm-4">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="d" value="estudio patologico">
  <label class="form-check-label" for="d">
    Estudio patológico
  </label>
</div>
    </div>
    <div class="col-sm-1">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="rpbi" value="R.P.B.I.">
  <label class="form-check-label" for="rpbi">
    R.P.B.I.
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="medtratante" value="medico tratante">
  <label class="form-check-label" for="medtratante">
Médico tratante:  </label>
</div>
    </div>
     <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="pacI" value="paciente">
  <label class="form-check-label" for="pacI">
    Paciente
  </label>
</div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
     <div class="col-sm-4">
    </div>
    <div class="col-sm">
    <div class="form-check">
  <input class="form-check-input" type="radio" name="dispo_p" id="familiar" value="familiar">
  <label class="form-check-label" for="familiar">
Familiar (de acuerdo a la legalidad)  </label>
</div>
    </div>
  </div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Diagnósticos y/o datos clínicos:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="yog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="oycs"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playdidc"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="diagyodc" class="form-control" id="txtdd"></textarea>
<script type="text/javascript">
const yog = document.getElementById('yog');
const oycs = document.getElementById('oycs');
const txtdd = document.getElementById('txtdd');

const btnPlayTextclid = document.getElementById('playdidc');

btnPlayTextclid.addEventListener('click', () => {
        leerTexto(txtdd.value);
});

function leerTexto(txtdd){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdd;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rgno = new webkitSpeechRecognition();
      rgno.lang = "es-ES";
      rgno.continuous = true;
      rgno.interimResults = false;

      rgno.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdd.value += frase;
      }

      yog.addEventListener('click', () => {
        rgno.start();
      });

      oycs.addEventListener('click', () => {
        rgno.abort();
      });
</script>
</div>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center><button type="button" class="btn btn-success btn-sm" id="playpian"><i class="fas fa-play"></button></i> Pieza anatómica:</center>
</div>
<div class="col-sm">
<input type="text" name="p_anato" class="form-control" id="txtanatpiz">
</div>
<script type="text/javascript">
const txtanatpiz = document.getElementById('txtanatpiz');
const btnPlayTextpza = document.getElementById('playpian');

btnPlayTextpza.addEventListener('click', () => {
        leerTexto(txtanatpiz.value);
});

function leerTexto(txtanatpiz){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtanatpiz;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

</script>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Tipo de intervención:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="tdig"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopit"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playtpoint"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="tipo_de_i" class="form-control" id="txtdipo"></textarea>
<script type="text/javascript">
const tdig = document.getElementById('tdig');
const stopit = document.getElementById('stopit');
const txtdipo = document.getElementById('txtdipo');

const btnPlayTextdetcion = document.getElementById('playtpoint');

btnPlayTextdetcion.addEventListener('click', () => {
        leerTexto(txtdipo.value);
});

function leerTexto(txtdipo){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdipo;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let rien = new webkitSpeechRecognition();
      rien.lang = "es-ES";
      rien.continuous = true;
      rien.interimResults = false;

      rien.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtdipo.value += frase;
      }

      tdig.addEventListener('click', () => {
        rien.start();
      });

      stopit.addEventListener('click', () => {
        rien.abort();
      });
</script>
</div>
</div>
</div>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center><button type="button" class="btn btn-success btn-sm" id="playsitioobtenc"><i class="fas fa-play"></button></i>
 Sitio de obtención:</center>
</div>
<div class="col-sm">
<input type="text" name="sitio_ob" class="form-control" id="txtobtes">
</div>
</div>
</div>
<script type="text/javascript">
const txtobtes = document.getElementById('txtobtes');
const btnPlayTs = document.getElementById('playsitioobtenc');

btnPlayTs.addEventListener('click', () => {
        leerTexto(txtobtes.value);
});

function leerTexto(txtobtes){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtobtes;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

</script>
<hr>
<div class="container">
<div class="row">
<div class="col-sm-4">
<center>Observaciones:
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="estudiosog"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stopestciine"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playobsc"><i class="fas fa-play"></button></i>
</div></center>
</div>
<div class="col-sm">
<textarea name="estudios_obser" class="form-control" id="txtcioest"></textarea>
<script type="text/javascript">
const estudiosog = document.getElementById('estudiosog');
const stopestciine = document.getElementById('stopestciine');
const txtcioest = document.getElementById('txtcioest');

const btnPlayTsob = document.getElementById('playobsc');

btnPlayTsob.addEventListener('click', () => {
        leerTexto(txtcioest.value);
});

function leerTexto(txtcioest){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtcioest;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let estudiosrob = new webkitSpeechRecognition();
      estudiosrob.lang = "es-ES";
      estudiosrob.continuous = true;
      estudiosrob.interimResults = false;

      estudiosrob.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtcioest.value += frase;
      }

      estudiosog.addEventListener('click', () => {
        estudiosrob.start();
      });

      stopestciine.addEventListener('click', () => {
        estudiosrob.abort();
      });
</script>
</div>
</div>
</div>


</div>
</div>  
      
<div id="div2" style="display:none;">
</div>

<hr>
 <div class="container">
     
     
     

         </tr><hr>

<p>
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>VALORACIÓN DE LA PIEL INICIAL</center></strong>
</div>
<p>
 <div class="container">
  <div class="row">
    <div class="col-sm">

<!-- BOT 1-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="uno" >
  X
  </a>
<div class="collapse collapse-horizontal" id="collapseExample">
    <input type="text" name="mara" class="form-control form-control-sm col-sm-1" id="contenido1">
</div>
<!-- TER BOT 1-->

<!-- BOT 2-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cdos" role="button" aria-expanded="false" aria-controls="cdos" id="dos" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cdos">
    <input type="text" name="marb" class="form-control form-control-sm col-sm-1"  id="contenido2">
</div>
<!-- TER BOT 2-->

<!-- BOT 3-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ctres" role="button" aria-expanded="false" aria-controls="ctres" id="tres" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ctres">
    <input type="text" name="marc" class="form-control form-control-sm col-sm-1" id="contenido3">
</div>
<!-- TER BOT 3-->

<!-- BOT 4-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ccua" role="button" aria-expanded="false" aria-controls="ccua" id="cuatro" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ccua">
    <input type="text" name="mard" class="form-control form-control-sm col-sm-1" id="contenido4">
</div>
<!-- TER BOT 4-->


<!-- BOT 5-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cco" role="button" aria-expanded="false" aria-controls="cco" id="cin" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cco">
    <input type="text" name="mare" class="form-control form-control-sm col-sm-1" id="contenido5">
</div>
<!-- TER BOT 5-->

<!-- BOT 6-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#sei" role="button" aria-expanded="false" aria-controls="sei" id="se" >
  X
  </a>
<div class="collapse collapse-horizontal" id="sei">
    <input type="text" name="marf" class="form-control form-control-sm col-sm-1" id="contenido6">
</div>
<!-- TER BOT 6-->

<!-- BOT 7-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#csie" role="button" aria-expanded="false" aria-controls="csie" id="sie" >
  X
  </a>
<div class="collapse collapse-horizontal" id="csie">
    <input type="text" name="marg" class="form-control form-control-sm col-sm-1" id="contenido7">
</div>
<!-- TER BOT 7-->

<!-- espalda BOT 8-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#coch" role="button" aria-expanded="false" aria-controls="coch" id="oc" >
  X
  </a>
<div class="collapse collapse-horizontal" id="coch">
    <input type="text" name="marh" class="form-control form-control-sm col-sm-1" id="contenido8">
</div>
<!-- TER BOT 8-->


<!-- pie-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#pie" role="button" aria-expanded="false" aria-controls="pie" id="pi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="pie">
    <input type="text" name="pie" class="form-control form-control-sm col-sm-1" id="ipi">
</div>
<!-- pie-->


<!-- pie d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#pied" role="button" aria-expanded="false" aria-controls="pied" id="pid" >
  X
  </a>
<div class="collapse collapse-horizontal" id="pied">
    <input type="text" name="pied" class="form-control form-control-sm col-sm-1" id="ipid">
</div>
<!-- pie d-->

<!-- tob I-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tobiz" role="button" aria-expanded="false" aria-controls="tobiz" id="toi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tobiz">
    <input type="text" name="tod" class="form-control form-control-sm col-sm-1" id="tobi">
</div>
<!-- tob I-->

<!-- tob d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tob" role="button" aria-expanded="false" aria-controls="tob" id="to" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tob">
    <input type="text" name="toi" class="form-control form-control-sm col-sm-1" id="tobd">
</div>
<!-- tob d-->

<!-- rodilla i-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#rod" role="button" aria-expanded="false" aria-controls="rod" id="roi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="rod">
    <input type="text" name="rodi" class="form-control form-control-sm col-sm-1" id="iroi">
</div>
<!-- rodilla i-->

<!-- rodilla d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#rd" role="button" aria-expanded="false" aria-controls="rd" id="brod" >
  X
  </a>
<div class="collapse collapse-horizontal" id="rd">
    <input type="text" name="rodd" class="form-control form-control-sm col-sm-1" id="irod">
</div>
<!-- rodilla d-->

<!-- muslo i-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#muslo" role="button" aria-expanded="false" aria-controls="muslo" id="mui" >
  X
  </a>
<div class="collapse collapse-horizontal" id="muslo">
    <input type="text" name="musloi" class="form-control form-control-sm col-sm-1" id="imi">
</div>
<!-- muslo i-->

<!-- muslo d-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#muslod" role="button" aria-expanded="false" aria-controls="muslod" id="mud" >
  X
  </a>
<div class="collapse collapse-horizontal" id="muslod">
    <input type="text" name="muslod" class="form-control form-control-sm col-sm-1" id="imd">
</div>
<!-- muslo d-->

<!-- ingle i-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#inglec" role="button" aria-expanded="false" aria-controls="inglec" id="ingi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="inglec">
    <input type="text" name="inglei" class="form-control form-control-sm col-sm-1" id="ingli">
</div>
<!-- ingle i-->


<!-- ombligo-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ombligo" role="button" aria-expanded="false" aria-controls="ombligo" id="domen" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ombligo">
    <input type="text" name="iabdomen" class="form-control form-control-sm col-sm-1" id="idomen">
</div>
<!-- ombligo-->


<!-- dedoi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoi" role="button" aria-expanded="false" aria-controls="dedoi" id="ddoi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoi">
    <input type="text" name="ddi" class="form-control form-control-sm col-sm-1" id="iddi">
</div>
<!-- dedoi-->

<!-- dedoi2-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoidos" role="button" aria-expanded="false" aria-controls="dedoidos" id="ddoid" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoidos">
    <input type="text" name="ddidos" class="form-control form-control-sm col-sm-1" id="iddid">
</div>
<!-- dedoi2-->


<!-- dedoi3-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoitres" role="button" aria-expanded="false" aria-controls="dedoitres" id="ditr" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoitres">
    <input type="text" name="dditres" class="form-control form-control-sm col-sm-1" id="iditr">
</div>
<!-- dedoi3-->

<!-- dedoi4-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoic" role="button" aria-expanded="false" aria-controls="dedoic" id="dic" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoic">
    <input type="text" name="ddicuatro" class="form-control form-control-sm col-sm-1" id="idic">
</div>
<!-- dedoi4-->

<!-- dedoi5-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dedoicin" role="button" aria-expanded="false" aria-controls="dedoicin" id="dicin" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dedoicin">
    <input type="text" name="ddicinco" class="form-control form-control-sm col-sm-1" id="idicin">
</div>
<!-- dedoi5-->

<!-- dedod1-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddu" role="button" aria-expanded="false" aria-controls="ddu" id="bddu" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddu">
    <input type="text" name="ddoderu" class="form-control form-control-sm col-sm-1" id="iddu">
</div>
<!-- dedod1-->

<!-- palmai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#pma" role="button" aria-expanded="false" aria-controls="pma" id="bpmai" >
  X
  </a>
<div class="collapse collapse-horizontal" id="pma">
    <input type="text" name="palmai" class="form-control form-control-sm col-sm-1" id="ipmai">
</div>
<!-- palmai-->


<!-- mulecai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#m" role="button" aria-expanded="false" aria-controls="m" id="bmuñi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="m">
    <input type="text" name="munei" class="form-control form-control-sm col-sm-1" id="imuñi">
</div>
<!-- muñecai-->

<!-- brazoicodo-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#br" role="button" aria-expanded="false" aria-controls="br" id="bbri" >
  X
  </a>
<div class="collapse collapse-horizontal" id="br">
    <input type="text" name="brazi" class="form-control form-control-sm col-sm-1" id="ibri">
</div>
<!-- brazoicodo-->

<!-- brazoci-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cjo" role="button" aria-expanded="false" aria-controls="cjo" id="bbric" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cjo">
    <input type="text" name="brazci" class="form-control form-control-sm col-sm-1" id="ibric">
</div>
<!-- brazoci-->

<!-- hombroi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#hbroi" role="button" aria-expanded="false" aria-controls="hbroi" id="bhomi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="hbroi">
    <input type="text" name="homi" class="form-control form-control-sm col-sm-1" id="ihomi">
</div>
<!-- hombroi-->

<!-- peci-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cpi" role="button" aria-expanded="false" aria-controls="cpi" id="bcpi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cpi">
    <input type="text" name="peci" class="form-control form-control-sm col-sm-1" id="icpi">
</div>
<!-- peci-->

<!-- pecti-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cpei" role="button" aria-expanded="false" aria-controls="cpei" id="bcpei" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cpei">
    <input type="text" name="pecti" class="form-control form-control-sm col-sm-1" id="icpei">
</div>
<!-- pecti-->

<!-- pectd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cped" role="button" aria-expanded="false" aria-controls="cped" id="bcped" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cped">
    <input type="text" name="pectd" class="form-control form-control-sm col-sm-1" id="icped">
</div>
<!-- pectd-->

<!-- clavi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cvi" role="button" aria-expanded="false" aria-controls="cvi" id="bcvi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cvi">
    <input type="text" name="cvi" class="form-control form-control-sm col-sm-1" id="icvi">
</div>
<!-- clavi-->

<!-- dedod2-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddos" role="button" aria-expanded="false" aria-controls="ddos" id="bddos" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddos">
    <input type="text" name="dedodos" class="form-control form-control-sm col-sm-1" id="iddos">
</div>
<!-- dedod2-->

<!-- dedod3-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddtres" role="button" aria-expanded="false" aria-controls="ddtres" id="bddt" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddtres">
    <input type="text" name="dedotres" class="form-control form-control-sm col-sm-1" id="iddt">
</div>
<!-- dedod3-->

<!-- dedod4-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddcuatro" role="button" aria-expanded="false" aria-controls="ddcuatro" id="bddc" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddcuatro">
    <input type="text" name="dedocuatro" class="form-control form-control-sm col-sm-1" id="iddc">
</div>
<!-- dedod4-->

<!-- dedod5-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#ddcinco" role="button" aria-expanded="false" aria-controls="ddcinco" id="bddcinco" >
  X
  </a>
<div class="collapse collapse-horizontal" id="ddcinco">
    <input type="text" name="dedocincoo" class="form-control form-control-sm col-sm-1" id="iddcinco">
</div>
<!-- dedod5-->

<!-- palmaderecha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#palmad" role="button" aria-expanded="false" aria-controls="palmad" id="bpalmad" >
  X
  </a>
<div class="collapse collapse-horizontal" id="palmad">
    <input type="text" name="palmad" class="form-control form-control-sm col-sm-1" id="ipalmad">
</div>
<!-- palmaderecha-->

<!-- muñecaderecha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#munecader" role="button" aria-expanded="false" aria-controls="munecader" id="bmund" >
  X
  </a>
<div class="collapse collapse-horizontal" id="munecader">
    <input type="text" name="munecad" class="form-control form-control-sm col-sm-1" id="imund">
</div>
<!-- muñecaderecha-->

<!-- brazoderecho-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dbrazo" role="button" aria-expanded="false" aria-controls="dbrazo" id="bdbr" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dbrazo">
    <input type="text" name="derbraz" class="form-control form-control-sm col-sm-1" id="idbr">
</div>
<!-- brazoderecho-->


<!-- antebrazoder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#anteder" role="button" aria-expanded="false" aria-controls="anteder" id="babd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="anteder">
    <input type="text" name="annbraz" class="form-control form-control-sm col-sm-1" id="ianbd">
</div>
<!-- antbrazoder-->

<!-- conder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#conejod" role="button" aria-expanded="false" aria-controls="conejod" id="bcder" >
  X
  </a>
<div class="collapse collapse-horizontal" id="conejod">
    <input type="text" name="cconder" class="form-control form-control-sm col-sm-1" id="icder">
</div>
<!-- conder-->

<!-- hombroderecho-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#hombroderecho" role="button" aria-expanded="false" aria-controls="hombroderecho" id="bhder" >
  X
  </a>
<div class="collapse collapse-horizontal" id="hombroderecho">
    <input type="text" name="hombrod" class="form-control form-control-sm col-sm-1" id="ihder">
</div>
<!-- hombroderecho-->


<!-- mandibulader-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#mander" role="button" aria-expanded="false" aria-controls="mander" id="bmand" >
  X
  </a>
<div class="collapse collapse-horizontal" id="mander">
    <input type="text" name="mandiderr" class="form-control form-control-sm col-sm-1" id="imand">
</div>
<!-- mandibulader-->


<!-- mandibulacentro-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#mancentro" role="button" aria-expanded="false" aria-controls="mancentro" id="bmanc" >
  X
  </a>
<div class="collapse collapse-horizontal" id="mancentro">
    <input type="text" name="mandicentroo" class="form-control form-control-sm col-sm-1" id="imanc">
</div>
<!-- mandibulacentro-->

<!-- mandibulaizquierda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#manizquierda" role="button" aria-expanded="false" aria-controls="manizquierda" id="bmaniz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="manizquierda">
    <input type="text" name="mandiizqui" class="form-control form-control-sm col-sm-1" id="imaniz">
</div>
<!-- mandibulaizquierda-->

<!-- mejilla derecha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#mejillad" role="button" aria-expanded="false" aria-controls="mejillad" id="bmejd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="mejillad">
    <input type="text" name="mejderecha" class="form-control form-control-sm col-sm-1" id="imejd">
</div>
<!-- mejilla derecha-->

<!--nariz-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#conariz" role="button" aria-expanded="false" aria-controls="conariz" id="bnariz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="conariz">
    <input type="text" name="narizc" class="form-control form-control-sm col-sm-1" id="inariz">
</div>
<!--nariz-->

<!--frenteizq-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#frentei" role="button" aria-expanded="false" aria-controls="frentei" id="bfrentei" >
  X
  </a>
<div class="collapse collapse-horizontal" id="frentei">
    <input type="text" name="frenteizquierda" class="form-control form-control-sm col-sm-1" id="ifrentei">
</div>
<!--frenteizq-->

<!--frenteder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#frented" role="button" aria-expanded="false" aria-controls="frented" id="bfrented" >
  X
  </a>
<div class="collapse collapse-horizontal" id="frented">
    <input type="text" name="frentederecha" class="form-control form-control-sm col-sm-1" id="ifrented">
</div>
<!--frenteder-->


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo1" role="button" aria-expanded="false" aria-controls="nuevo1" id="nuevo01" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo1">
    <input type="text" name="nuevo1" class="form-control form-control-sm col-sm-1" id="inuevo1">
</div>


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo2" role="button" aria-expanded="false" aria-controls="nuevo2" id="nuevo02" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo2">
    <input type="text" name="nuevo2" class="form-control form-control-sm col-sm-1" id="inuevo2">
</div>


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo3" role="button" aria-expanded="false" aria-controls="nuevo3" id="nuevo03" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo3">
    <input type="text" name="nuevo3" class="form-control form-control-sm col-sm-1" id="inuevo3">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo4" role="button" aria-expanded="false" aria-controls="nuevo4" id="nuevo04" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo4">
    <input type="text" name="nuevo4" class="form-control form-control-sm col-sm-1" id="inuevo4">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo5" role="button" aria-expanded="false" aria-controls="nuevo5" id="nuevo05" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo5">
    <input type="text" name="nuevo5" class="form-control form-control-sm col-sm-1" id="inuevo5">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo6" role="button" aria-expanded="false" aria-controls="nuevo6" id="nuevo06" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo6">
    <input type="text" name="nuevo6" class="form-control form-control-sm col-sm-1" id="inuevo6">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo7" role="button" aria-expanded="false" aria-controls="nuevo7" id="nuevo07" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo7">
    <input type="text" name="nuevo7" class="form-control form-control-sm col-sm-1" id="inuevo7">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nuevo8" role="button" aria-expanded="false" aria-controls="nuevo8" id="nuevo08" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nuevo8">
    <input type="text" name="nuevo8" class="form-control form-control-sm col-sm-1" id="inuevo8">
</div>






<!---------- ESPALDA------------>

<!--plantapie-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#plantapiei" role="button" aria-expanded="false" aria-controls="plantapiei" id="bppi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="plantapiei">
    <input type="text" name="plantapiea" class="form-control form-control-sm col-sm-1" id="ippi">
</div>
<!--plantapie-->

<!--plantapieder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#plantapieder" role="button" aria-expanded="false" aria-controls="plantapieder" id="bppd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="plantapieder">
    <input type="text" name="plantapieader" class="form-control form-control-sm col-sm-1" id="ippd">
</div>
<!--plantapieder-->


<!--tobati-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tobilloiz" role="button" aria-expanded="false" aria-controls="tobilloiz" id="btia" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tobilloiz">
    <input type="text" name="tobilloati" class="form-control form-control-sm col-sm-1" id="itia">
</div>
<!--tobati-->

<!--tobatd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#tobilloder" role="button" aria-expanded="false" aria-controls="tobilloder" id="btda" >
  X
  </a>
<div class="collapse collapse-horizontal" id="tobilloder">
    <input type="text" name="tobilloatd" class="form-control form-control-sm col-sm-1" id="itda">
</div>
<!--tobatd-->

<!--pti-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#chami" role="button" aria-expanded="false" aria-controls="chami" id="bpani" >
  X
  </a>
<div class="collapse collapse-horizontal" id="chami">
    <input type="text" name="ptiatras" class="form-control form-control-sm col-sm-1" id="ipani">
</div>
<!--pti-->

<!--ptd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#chamd" role="button" aria-expanded="false" aria-controls="chamd" id="bpand" >
  X
  </a>
<div class="collapse collapse-horizontal" id="chamd">
    <input type="text" name="ptdatras" class="form-control form-control-sm col-sm-1" id="ipand">
</div>
<!--ptd-->


<!--piernaiespalda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#piernaiespalda" role="button" aria-expanded="false" aria-controls="piernaiespalda" id="bpchi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="piernaiespalda">
    <input type="text" name="pierespaldai" class="form-control form-control-sm col-sm-1" id="ipchi">
</div>
<!--piernaiespalda-->

<!--piernadespalda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#piernadespalda" role="button" aria-expanded="false" aria-controls="piernadespalda" id="bpchd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="piernadespalda">
    <input type="text" name="pierespaldad" class="form-control form-control-sm col-sm-1" id="ipchd">
</div>
<!--piernadespalda-->

<!--musloatrasi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#musloatrasi" role="button" aria-expanded="false" aria-controls="musloatrasi" id="bmusai" >
  X
  </a>
<div class="collapse collapse-horizontal" id="musloatrasi">
    <input type="text" name="musloatrasiz" class="form-control form-control-sm col-sm-1" id="imusai">
</div>
<!--musloatrasi-->

<!--musloatrasd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#musloatrasd" role="button" aria-expanded="false" aria-controls="musloatrasd" id="bmusad" >
  X
  </a>
<div class="collapse collapse-horizontal" id="musloatrasd">
    <input type="text" name="musloatrasder" class="form-control form-control-sm col-sm-1" id="imusad">
</div>
<!--musloatrasd-->

<!--gluteosi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#gluteosi" role="button" aria-expanded="false" aria-controls="gluteosi" id="bglui" >
  X
  </a>
<div class="collapse collapse-horizontal" id="gluteosi">
    <input type="text" name="glutiz" class="form-control form-control-sm col-sm-1" id="iglui">
</div>
<!--gluteosi-->

<!--gluteosd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#gluteosd" role="button" aria-expanded="false" aria-controls="gluteosd" id="bglud" >
  X
  </a>
<div class="collapse collapse-horizontal" id="gluteosd">
    <input type="text" name="glutder" class="form-control form-control-sm col-sm-1" id="iglud">
</div>
<!--gluteosd-->


<!--cinturai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cinturai" role="button" aria-expanded="false" aria-controls="cinturai" id="bcini" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cinturai">
    <input type="text" name="cinturaiz" class="form-control form-control-sm col-sm-1" id="icini">
</div>
<!--cinturai-->

<!--cinturad-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cinturad" role="button" aria-expanded="false" aria-controls="cinturad" id="bcind" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cinturad">
    <input type="text" name="cinturader" class="form-control form-control-sm col-sm-1" id="icind">
</div>
<!--cinturad-->

<!--costillasai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#costillasai" role="button" aria-expanded="false" aria-controls="costillasai" id="bcosi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="costillasai">
    <input type="text" name="costilliz" class="form-control form-control-sm col-sm-1" id="icosi">
</div>
<!--costillasai-->

<!--costillasad-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#costillasad" role="button" aria-expanded="false" aria-controls="costillasad" id="bcosd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="costillasad">
    <input type="text" name="costillder" class="form-control form-control-sm col-sm-1" id="icosd">
</div>
<!--costillasad-->

<!--espaldarribai-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espaldarribai" role="button" aria-expanded="false" aria-controls="espaldarribai" id="besai" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espaldarribai">
    <input type="text" name="espaldarribaiz" class="form-control form-control-sm col-sm-1" id="iesai">
</div>
<!--espaldarribai-->

<!--espaldarribad-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espaldarribad" role="button" aria-expanded="false" aria-controls="espaldarribad" id="besad" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espaldarribad">
    <input type="text" name="espaldaarribader" class="form-control form-control-sm col-sm-1" id="iesad">
</div>
<!--espaldarribad-->

<!--espaldalta-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espaldalta" role="button" aria-expanded="false" aria-controls="espaldalta" id="besalt" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espaldalta">
    <input type="text" name="espaldaalta" class="form-control form-control-sm col-sm-1" id="iesalt">
</div>
<!--espaldalta-->

<!--dorsaliz mano-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dorsaliz" role="button" aria-expanded="false" aria-controls="dorsaliz" id="bdorsali" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dorsaliz">
    <input type="text" name="dorsaliz" class="form-control form-control-sm col-sm-1" id="idorsali">
</div>
<!--dorsaliz-->

<!--dorsalder mano-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#dorsalder" role="button" aria-expanded="false" aria-controls="dorsalder" id="bdorsald" >
  X
  </a>
<div class="collapse collapse-horizontal" id="dorsalder">
    <input type="text" name="dorsalder" class="form-control form-control-sm col-sm-1" id="idorsald">
</div>
<!--dorsalder-->

<!--munecaatrasi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#munecaatrasi" role="button" aria-expanded="false" aria-controls="munecaatrasi" id="bmuneati" >
  X
  </a>
<div class="collapse collapse-horizontal" id="munecaatrasi">
    <input type="text" name="munecaatrasiz" class="form-control form-control-sm col-sm-1" id="imuneati">
</div>
<!--munecaatrasi-->

<!--munecaatrasd-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#munecaatrasd" role="button" aria-expanded="false" aria-controls="munecaatrasd" id="bmuneatd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="munecaatrasd">
    <input type="text" name="munecaatrasder" class="form-control form-control-sm col-sm-1" id="imuneatd">
</div>
<!--munecaatrasd-->

<!--antebiesp-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#antebiesp" role="button" aria-expanded="false" aria-controls="antebiesp" id="banbei" >
  X
  </a>
<div class="collapse collapse-horizontal" id="antebiesp">
    <input type="text" name="antebiesp" class="form-control form-control-sm col-sm-1" id="ianbei">
</div>
<!--antebiesp-->

<!--antebdesp-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#antebdesp" role="button" aria-expanded="false" aria-controls="antebdesp" id="banbed" >
  X
  </a>
<div class="collapse collapse-horizontal" id="antebdesp">
    <input type="text" name="antebdesp" class="form-control form-control-sm col-sm-1" id="ianbed">
</div>
<!--antebdesp-->

<!--casicodoi-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#casicodoi" role="button" aria-expanded="false" aria-controls="casicodoi" id="bccodoi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="casicodoi">
    <input type="text" name="casicodoi" class="form-control form-control-sm col-sm-1" id="iccodoi">
</div>
<!--casicodoi-->

<!--casicododer-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#casicododer" role="button" aria-expanded="false" aria-controls="casicododer" id="bccodod" >
  X
  </a>
<div class="collapse collapse-horizontal" id="casicododer">
    <input type="text" name="casicododer" class="form-control form-control-sm col-sm-1" id="iccodod">
</div>
<!--casicododer-->

<!--brazalti-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#brazalti" role="button" aria-expanded="false" aria-controls="brazalti" id="bbaiz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="brazalti">
    <input type="text" name="brazalti" class="form-control form-control-sm col-sm-1" id="ibaiz">
</div>
<!--brazalti-->

<!--brazaltder-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#brazaltder" role="button" aria-expanded="false" aria-controls="brazaltder" id="bbader" >
  X
  </a>
<div class="collapse collapse-horizontal" id="brazaltder">
    <input type="text" name="brazaltder" class="form-control form-control-sm col-sm-1" id="ibader">
</div>
<!--brazaltder-->

<!--cuelloatrasbajo-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cuelloatrasbajo" role="button" aria-expanded="false" aria-controls="cuelloatrasbajo" id="bcbajo" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cuelloatrasbajo">
    <input type="text" name="cuellatrasb" class="form-control form-control-sm col-sm-1" id="icbajo">
</div>
<!--cuelloatrasbajo-->

<!--cuelloatrasmedio-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cuelloatrasmedio" role="button" aria-expanded="false" aria-controls="cuelloatrasmedio" id="bcmedio" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cuelloatrasmedio">
    <input type="text" name="cuellatrasmedio" class="form-control form-control-sm col-sm-1" id="icmedio">
</div>
<!--cuelloatrasmedio-->

<!--cabezadorsalmedia-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cabezadorsalmedia" role="button" aria-expanded="false" aria-controls="cabezadorsalmedia" id="bcabezamedio" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cabezadorsalmedia">
    <input type="text" name="cabedorsalm" class="form-control form-control-sm col-sm-1" id="icabezamedio">
</div>
<!--cabezadorsalmedia-->

<!--cabezaaltaizquierda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cabezaaltaizquierda" role="button" aria-expanded="false" aria-controls="cabezaaltaizquierda" id="bcabaiz" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cabezaaltaizquierda">
    <input type="text" name="cabealtaizqu" class="form-control form-control-sm col-sm-1" id="icabaiz">
</div>
<!--cabezaaltaizquierda-->

<!--cabezaaltadercha-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#cabezaaltadercha" role="button" aria-expanded="false" aria-controls="cabezaaltadercha" id="bcabader" >
  X
  </a>
<div class="collapse collapse-horizontal" id="cabezaaltadercha">
    <input type="text" name="cabezaaltader" class="form-control form-control-sm col-sm-1" id="icabader">
</div>
<!--cabezaaltadercha-->

<!--espinillaizquierda-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espinillaizquierda" role="button" aria-expanded="false" aria-controls="espinillaizquierda" id="espi" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espinillaizquierda">
    <input type="text" name="espizq" class="form-control form-control-sm col-sm-1" id="cssespi">
</div>
<!--espinillaizquierda-->


<!--espinillader-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#espinillader" role="button" aria-expanded="false" aria-controls="espinillader" id="espd" >
  X
  </a>
<div class="collapse collapse-horizontal" id="espinillader">
    <input type="text" name="espder" class="form-control form-control-sm col-sm-1" id="cssespd">
</div>
<!--espinillader-->

<!--coxix-->
  <a class="btn btn-primary" data-bs-toggle="collapse" href="#coxix" role="button" aria-expanded="false" aria-controls="coxix" id="coxis" >
  X
  </a>
<div class="collapse collapse-horizontal" id="coxix">
    <input type="text" name="coxis" class="form-control form-control-sm col-sm-1" id="csscoxis">
</div>
<!--coxix-->

 <center><img src="../../img/marcaje_qx.jpg" height="550" id="im"></center>
     
    </div>
     
  </div>
</div>

<hr>
 <tr>
    
  
  
 
 

<br>
<script type="text/javascript">
const nom_enf_preop = document.getElementById('nom_enf_preop');

const btnPlaye1 = document.getElementById('pnom1');

btnPlaye1.addEventListener('click', () => {
        leerTexto(nom_enf_preop.value);
});

function leerTexto(nom_enf_preop){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_preop;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
     

      

     
 </div>
<script type="text/javascript">
const nom_enf_trans = document.getElementById('nom_enf_trans');
const btnPlayultenf = document.getElementById('peralen');

btnPlayultenf.addEventListener('click', () => {
        leerTexto(nom_enf_trans.value);
});

function leerTexto(nom_enf_trans){
    const speech = new SpeechSynthesisUtterance();
    speech.text= nom_enf_trans;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>




<div class="form-group col-12">
  <center><br>
<button type="submit" name="btnno" id="btnno" class="btn btn-primary">Firmar</button>
<button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button></center>
</div><hr>
      
</div>

</form>
</div>
  </div>
<!-- SCRIPT AJAX-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnno').click(function(){
            var datos=$('#notaj').serialize();
          
            $.ajax({
                type:"POST",
                url:"insertar_regquir.php",
                data:datos,
                success:function(r){
                    if(r=1){
                        $("#barra").load(" #barra");
                       // $("#tabs").load("vista_enf_quirurgico.php #tabs");
                       alertify.success("Nota guardada con éxito");
                       document.getElementById("notaj").reset();
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>
<?php }?>


<!--INICIO PESTAÑA ingresos y egresos-->

 <div class="tab-pane fade" id="nav-ing" role="tabpanel" aria-labelledby="nav-ing-tab">

 <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>INGRESOS</center></strong>
</div><br>

<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalI" class="btn btn-success">Agregar nuevos ingresos</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoI" placeholder="Buscar...">
            </div>
            <table id="exampleI" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de registro</th>
                <th>Fecha de reporte</th>
                <th>Hora</th>
                <th>Soluciones</th>
                <th>Volumen</th>
                <th>Registró</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
            
            <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleI').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataI.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [7]
          },

        ]
      });
    });
    $(document).on('submit', '#addUserI', function(e) {
      e.preventDefault();
      
	var horai = $('#addhoraiField').val();
	var fechai = $('#addfechaiField').val();
	var soluciones = $('#addsolucionesField').val();
    var volumen = $('#addvolumenField').val();
      if (horai != '' && fechai != '' && soluciones != '' && volumen != '') {
        $.ajax({
          url: "add_userI.php",
          type: "post",
          data: {
		    horai: horai,
            fechai: fechai,
            soluciones: soluciones,
            volumen: volumen,
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleI').DataTable();
              mytable.draw();
              document.getElementById("addUserI").reset();
              $('#addUserModalI').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserI', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var horai = $('#horaiField').val();
      var fechai = $('#fechaiField').val();
      var soluciones = $('#solucionesField').val();
      var volumen = $('#volumenField').val();
      
            var fecha_registro = $('#fecha_registroField').val();
      var id_usua = $('#id_usuaField').val();
  
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (horai != '' && fechai != '' && soluciones != '' && volumen != '') {
        $.ajax({
          url: "update_userI.php",
          type: "post",
          data: {
            horai: horai,
            soluciones: soluciones,
            fechai: fechai,
            volumen: volumen,
            fecha_registro:fecha_registro,
            id_usua:id_usua,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleI').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnI">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnI">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id,fecha_registro,fechai,horai,soluciones,volumen,id_usua,button]);
              $('#exampleModalI').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleI').on('click', '.editbtnI ', function(event) {
      var table = $('#exampleI').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalI').modal('show');

      $.ajax({
        url: "get_single_dataI.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#horaiField').val(json.hora);
          $('#fechaiField').val(json.fecha);
          $('#solucionesField').val(json.soluciones);
          $('#volumenField').val(json.volumen);
          $('#id_usuaField').val(json.id_usua);
          $('#fecha_registroField').val(json.fecha_registro);
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnI', function(event) {
      var table = $('#exampleI').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("¿Estas seguro de eliminar este registro? ")) {
        $.ajax({
          url: "delete_userI.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalI" tabindex="-1" aria-labelledby="exampleModalLabeli" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabeli">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUserI">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            
             <input type="hidden" class="form-control" id="fecha_registroField" name="fecha_registro">
              <input type="hidden" class="form-control" id="id_usuaField" name="id_usua">
            
            
            <div class="mb-3 row">
              <label for="fechaiField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="fechaiField" name="fechai">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="horaiField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                   <input type="time" name="horai" id="horaiField" class="form-control">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="solucionesField" class="col-sm-3 form-label">Describir ingresos</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="solucionesField" name="soluciones">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="volumenField" class="col-md-3 form-label">Volumen</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="volumenField" name="volumen">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalI" tabindex="-1" aria-labelledby="exampleModalLabelll" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelll">Nuevo registro de Ingresos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserI" action="">
              <div class="mb-3 row">
              <label for="addfechaiField" class="col-sm-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                   <?php $fr = date("Y-m-d H:i");?>
                <input type="date" class="form-control" id="addfechaiField" name="fechai"  value="<?php echo $fecha_actual = date("Y-m-d");?>">
              </div>
            </div>
              <div class="mb-3 row">
              <label for="addhoraiField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                  <input type="time" name="horai" id="addhoraiField" class="form-control">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addsolucionesField" class="col-md-3 form-label">Describir ingresos</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addsolucionesField" name="soluciones">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addvolumenField" class="col-md-3 form-label">Volumen</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addvolumenField" name="volumen">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>        
         
         <!--EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESO EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESOS EGRESO-->
         <br>
          <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>EGRESOS</center></strong>
</div><br>
            
<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalE" class="btn btn-success">Agregar nuevos egresos</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoE" placeholder="Buscar...">
            </div>
            <table id="exampleE" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de registro</th>
                <th>Fecha de reporte</th>
                <th>Hora</th>
                <th>Soluciones</th>
                <th>Volumen</th>
                <th>Registró</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
            
            <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleE').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataE.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [7]
          },

        ]
      });
    });
    $(document).on('submit', '#addUserE', function(e) {
      e.preventDefault();
      
	var horae = $('#addhoraeField').val();
	var fechae = $('#addfechaeField').val();
	var solucionese = $('#addsolucioneseField').val();
    var volumene = $('#addvolumeneField').val();
      if (horae != '' && fechae != '' && solucionese != '' && volumene != '') {
        $.ajax({
          url: "add_userE.php",
          type: "post",
          data: {
		    horae: horae,
            fechae: fechae,
            solucionese: solucionese,
            volumene: volumene,
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleE').DataTable();
              mytable.draw();
              document.getElementById("addUserE").reset();
              $('#addUserModalE').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserE', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var horae = $('#horaeField').val();
      var fechae = $('#fechaeField').val();
      var solucionese = $('#solucioneseField').val();
      var volumene = $('#volumeneField').val();
      
            var fecha_registroe = $('#fecha_registroeField').val();
      var id_usuae = $('#id_usuaeField').val();
  
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (horae != '' && fechae != '' && solucionese != '' && volumene != '') {
        $.ajax({
          url: "update_userE.php",
          type: "post",
          data: {
            horae: horae,
            solucionese: solucionese,
            fechae: fechae,
            volumene: volumene,
            fecha_registroe:fecha_registroe,
            id_usuae:id_usuae,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleE').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnE">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnE">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id,fecha_registroe,fechae,horae,solucionese,volumene,id_usuae,button]);
              $('#exampleModalE').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleE').on('click', '.editbtnE ', function(event) {
      var table = $('#exampleE').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalE').modal('show');

      $.ajax({
        url: "get_single_dataE.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#horaeField').val(json.hora);
          $('#fechaeField').val(json.fecha);
          $('#solucioneseField').val(json.soluciones);
          $('#volumeneField').val(json.volumen);
          $('#id_usuaeField').val(json.id_usua);
          $('#fecha_registroeField').val(json.fecha_registro);
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnE', function(event) {
      var table = $('#exampleE').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("¿Estas seguro de eliminar este registro? ")) {
        $.ajax({
          url: "delete_userE.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalE" tabindex="-1" aria-labelledby="exampleModalLabelie" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelie">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUserE">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            
             <input type="hidden" class="form-control" id="fecha_registroeField" name="fecha_registro">
              <input type="hidden" class="form-control" id="id_usuaeField" name="id_usua">
            
            
            <div class="mb-3 row">
              <label for="fechaeField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="fechaeField" name="fechae">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="horaeField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                   <input type="time" name="horae" id="horaeField" class="form-control">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="solucioneseField" class="col-sm-3 form-label">Describir ingresos</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="solucioneseField" name="solucionese">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="volumeneField" class="col-md-3 form-label">Volumen</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="volumeneField" name="volumene">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalE" tabindex="-1" aria-labelledby="exampleModalLabelllE" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelllE">Nuevo registro de Egresos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserE" action="">
              <div class="mb-3 row">
              <label for="addfechaeField" class="col-sm-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                   <?php $fr = date("Y-m-d H:i");?>
                <input type="date" class="form-control" id="addfechaeField" name="fechae"  value="<?php echo $fecha_actual = date("Y-m-d");?>">
              </div>
            </div>
              <div class="mb-3 row">
              <label for="addhoraeField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                  <input type="time" name="horae" id="addhoraeField" class="form-control">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addsolucioneseField" class="col-md-3 form-label">Describir ingresos</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addsolucioneseField" name="solucionese">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addvolumeneField" class="col-md-3 form-label">Volumen</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addvolumeneField" name="volumene">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>              
            
</div> <!--termino de ingresos y egresos -->


<!--INICIO PESTAÑA soluciones-->

 <div class="tab-pane fade" id="nav-ingresos" role="tabpanel" aria-labelledby="nav-ingresos-tab">
     <div class="container">
  <form method="POST" id="formsol" name="formsol">
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="9"><center><h5><strong>REGISTRAR SOLUCIONES ADMINISTRADAS AL PACIENTE</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      
      <th scope="col-4"><center>Inicio</center></th>
       <th scope="col-4"><center>ml/hrs</center></th>
      <th scope="col-4"><center>Término</center></th>
      <th scope="col-4"><center>Soluciones</center></th>
      <th scope="col-1"><center>Tipo catéter</center></th>
     
      <th scope="col"><center>Volumen total</center></th>
    
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>
          <input type="time" name="hora_i" class="form-control">
    
      </td>
       <td>
       <input type="text" name="pvc" class="form-control">
   
      </td>
       <td>
       <input type="time" name="hora_t" class="form-control">
   
      </td>
      <td> 
    <input type="text" class="form-control" name="sol" required="">
      </td>
      <td><input type="text" name="tcate" class="form-control" ></td>
      <!--<td><input type="text" name="sitio" class="form-control" ></td>-->
       <td><input type="text" name="vol" class="form-control" ></td>
      <td><input type="submit" name="btnsol" id="btnsol" class="btn btn-block btn-success btn-sm" value="AGREGAR"></td>
    </tr>
  </tbody>
</table>
<?php $fecha_actual = date("Y-m-d"); ?>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
      <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" name="sol_fecha" value="<?php echo $fecha_actual ?>" class="form-control">
    </div>
    
  </div>
</div>
     </div>
     
    </form>

<!-- termino seccion de control-->
</div>


<!-- SCRIPT AJAX-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnsol').click(function(){
            var datos=$('#formsol').serialize();

            $.ajax({
                type:"POST",
                url:"insertarsoluciones.php",
                data:datos,
                success:function(r){
                    if(r=1){
                        $("#mytables").load("vista_enf_quirurgico.php #mytables");
                       alertify.success("Solución agregada con éxito");
                        $("#formsoleli").load("vista_enf_quirurgico.php #formsoleli");
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }

            });

            return false;
        });
    });
</script>

<div class="container-fluid">
          <div class="col col-12">

            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
            </div>
            
               <?php


?>
            <div class="table-responsive">

            <table class="table table-bordered table-striped" id="mytables">
                <thead class="thead bg-navy">
              
                    <th scope="col">Pdf</th>
                      <th scope="col">Fecha de registro</th>
                    <th scope="col">Fecha de reporte</th>
                      <th scope="col">Hora inicio</th> 
                               <th scope="col">PVC</th> 
                       <th scope="col">Hora término</th> 
                    <th scope="col">Soluciones</th>
                    <th scope="col">Tipo catéter</th>
                   
                     <th scope="col">Volumen total</th>
                    
                    <th scope="col">Tipo</th>
                    <th scope="col">Registró</th>
                    <th scope="col">Editar</th>
                    <th scope="col">Eliminar</th>
                </tr>
                </thead>
                <tbody>

<?php
include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from sol_enf m , reg_usuarios r WHERE m.id_atencion=$id_atencion and m.id_usua=r.id_usua and tipo='QUIROFANO' ORDER BY id_sol_enf DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
while($f = mysqli_fetch_array($resultado)){   

    $resultado2 = $conexion->query("SELECT * FROM paciente P, dat_ingreso DI WHERE P.Id_exp=DI.Id_exp and DI.id_atencion=$id_atencion") or die($conexion->error);

            
              while ($row = $resultado2->fetch_assoc()) { 
                
    ?>
                    <tr>
                        <td class="fondo"><a href="../soluciones/pdf_soluciones.php?id_ord=<?php echo $f['id_sol_enf'];?>&id_atencion=<?php echo $f['id_atencion'];?>&id_usua=<?php echo $usuario['id_usua']?>&fecha=<?php echo $f['sol_fecha'];?>&id_exp=<?php echo $row['Id_exp'];?>" target="_blank"><button type="button" class="btn btn-danger"> <i class="fa fa-file-pdf-o" style="font-size:28px"  aria-hidden="true"></i> </button></a></td>
                          <td class="fondo"><strong><?php $dater=date_create($f['fecha_registro']); echo date_format($dater,"d/m/Y H:i a");?></strong></td>
                        <td class="fondo"><strong><?php $date=date_create($f['sol_fecha']); echo date_format($date,"d/m/Y");?></strong></td>
                        <td class="fondo"><strong><?php echo $f['hora_i'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['pvc'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['hora_t'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['sol'];?></strong></td>
                         <td class="fondo"><strong><?php echo $f['tcate'];?></strong></td>
                        
                         <td class="fondo"><strong><?php echo $f['vol'];?></strong></td>
                       
                        <td class="fondo"><strong><?php echo $f['tipo'];?></strong></td>
                        <td class="fondo"><strong><?php echo $f['papell'].' '.$f['sapell']?></strong></td>

<td><a href="edit_sol.php?id_sol_enf=<?php echo $f['id_sol_enf'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>
<td>
    <a href="eliminarsoluciones.php?id_sol_enf=<?php echo $f['id_sol_enf'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X</button>

</td>
                    </tr>
                   
                    <?php
                }
                 }
                ?>
                
                </tbody>
              
            </table>
            </div>

        </div>
    </div>
     </div>

<!--PESTAÑA EQUIPOS--> <!--PESTAÑA EQUIPOS--> <!--PESTAÑA EQUIPOS--><!--PESTAÑA EQUIPOS--><!--PESTAÑA EQUIPOS--><!--PESTAÑA EQUIPOS-->
<div class="tab-pane fade" id="nav-eq" role="tabpanel" aria-labelledby="nav-eq-tab">
    
<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>EQUIPOS</center></strong>
</div><br>

<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalEQ" class="btn btn-success">Agregar nuevos Equipos</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoEQ" placeholder="Buscar...">
            </div>
            EQUIPOS EN TRANSITO
            <table id="exampleEQ" class="table">
              <thead>
                <th>Id</th>
                <th>Nombre del material</th>
                <th>Cantidad</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
          <div class="col-md-2"></div>
        </div>
          
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleEQ').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataEQ.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false
            
          },

        ]
      });
    });
    $(document).on('submit', '#addUserEQ', function(e) {
      e.preventDefault();
      
	var qty_serv = $('#addqty_servField').val();
	var serv = $('#addservField').val();

      
      if (qty_serv != '' && serv != '') {
        $.ajax({
          url: "add_userEQ.php",
          type: "post",
          data: {
		    qty_serv: qty_serv,
            serv: serv
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleEQ').DataTable();
              mytable.draw();
              document.getElementById("addUserEQ").reset();
              $('#addUserModalEQ').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserEQ', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var hora = $('#horaField').val();
      var sistg = $('#sistgField').val();
      var diastg = $('#diastgField').val();
      var fcardg = $('#fcardgField').val();
      var satg = $('#satgField').val();
      var glic = $('#glicField').val();
      var fechare = $('#fechareField').val();
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (hora != '' && sistg != '' && diastg != '' && fcardg != '' && satg != '' && glic != '' && fechare != '') {
        $.ajax({
          url: "update_userEQ.php",
          type: "post",
          data: {
            hora: hora,
            sistg: sistg,
            diastg: diastg,
            fcardg: fcardg,
            satg: satg,
            glic:glic,
            fechare:fechare,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleEQ').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnEQ">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnEQ">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id, hora, sistg, button]);
              $('#exampleModalEQ').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleEQ').on('click', '.editbtnEQ ', function(event) {
      var table = $('#exampleEQ').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalEQ').modal('show');

      $.ajax({
        url: "get_single_dataEQ.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#horaField').val(json.hora);
          $('#sistgField').val(json.sistg);
          $('#diastgField').val(json.diastg);
          $('#fcardgField').val(json.fcardg);
          $('#satgField').val(json.satg);
          $('#glicField').val(json.glic);
          $('#fechareField').val(json.fechare);
        
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnEQ', function(event) {
      var table = $('#exampleEQ').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Estas seguro de eliminar este registro? ? ")) {
        $.ajax({
          url: "delete_userEQ.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalEQ" tabindex="-1" aria-labelledby="exampleModalLabelllEQ" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelllEQ">Nuevo registro de Equipos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserEQ" action="">
              <div class="mb-3 row">
              <label for="addservField" class="col-md-3 form-label">Equipo</label>
              <div class="col-md-9">
                <select class="selectpicker" data-show-subtext="true" data-live-search="true"  name="serv" id="addservField" style="width : 100%; heigth : 100%" >
         <option value="">Seleccionar equipo</option>
         <?php
          $sql_serv = "SELECT * FROM cat_servicios where tipo =4 and serv_activo = 'SI' ORDER BY serv_desc ASC";
          $result_serv = $conexion->query($sql_serv);
          while ($row_serv = $result_serv->fetch_assoc()) {
            echo "<option value='" . $row_serv['id_serv'] . "'>" . $row_serv['serv_desc'] . "</option>";
           }
         ?>
                                </select>
                 
              </div>
            </div>
           
             <div class="mb-3 row">
              <label for="addqty_servField" class="col-md-3 form-label">Cantidad</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="addqty_servField" name="qty_serv">
              </div>
            </div>
           
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>       

<script type="text/javascript">
    $(document).ready(function(){
        $('#btnconfEQ<?php echo $id_atencion;?>').click(function(){
            var datos=$('#formconfmEQ<?php echo $id_atencion;?>').serialize();
            $.ajax({
                type:"POST",
                url:"manipula_carnewEQ.php",
                data:datos,
                success:function(r){
                    if(r=1){
                         mytable = $('#exampleEQ').DataTable();
                         mytable.draw();
                         mytable = $('#exampleEQC').DataTable();
                         mytable.draw();
                        alertify.success("Medicamentos confirmados con éxito");
                        //$("#exampleEQ").load("vista_enf_quirurgico.php #exampleEQ");
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }
            });
            return false;
        });
    });
    </script><center>
     <div class="col-md-4">
            
            <form action="" method="POST" name="formconfmEQ<?php echo $id_atencion;?>" id="formconfmEQ<?php echo $id_atencion;?>">
                <td>
                <input type="hidden" name="id_atencion" value="<?php echo $id_atencion; ?>">
                <button type="button" class="btn btn-block btn-success col-9" id="btnconfEQ<?php echo $id_atencion;?>" name="btnconfEQ<?php echo $id_atencion;?>">Confirmar </button>
                </td>
            </form>
        </div></center>
        
 <div class="container">

               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoEQC" placeholder="Buscar...">
            </div>
            <h4>EQUIPOS CONFIRMADOS</h4>
            <table id="exampleEQC" class="table">
              <thead>
                <th>Id</th>
                <th>Nombre del material</th>
                <th>Cantidad</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
          
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleEQC').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id_equipceye', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataEQC.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": true,
            "aTargets": [2]
          },

        ]
      });
    });
    </script>        
        
</div>




<!--PESTAÑA MATERIALES--> <!--PESTAÑA MATERIALES--> <!--PESTAÑA MATERIALES--><!--PESTAÑA MATERIALES--><!--PESTAÑA MATERIALES--><!--PESTAÑA MATERIALES-->
<div class="tab-pane fade" id="nav-med" role="tabpanel" aria-labelledby="nav-med-tab">

<div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <strong><center>MATERIALES</center></strong>
</div><br>

<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalMAT" class="btn btn-success">Agregar nuevos Materiales</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoMAT" placeholder="Buscar...">
            </div>
            <h4>MATERIALES EN TRANSITO</h4>
            <table id="exampleMAT" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de registro</th>
                <th>Descripcion</th>
                <th>Cantidad surtida</th>
                <th>Cantidad</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>

<!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleMAT').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataMAT.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [4]
          },

        ]
      });
    });
    $(document).on('submit', '#addUserMAT', function(e) {
      e.preventDefault();
      
	var medicam_matm = $('#addmedicam_matmField').val();
    var cart_qtym = $('#addcart_qtymField').val();
        
      if (medicam_matm != '' && cart_qtym != '') {
        $.ajax({
          url: "add_userMAT.php",
          type: "post",
          data: {
            medicam_matm:medicam_matm,
            cart_qtym:cart_qtym
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleMAT').DataTable();
              mytable.draw();
              document.getElementById("addUserMAT").reset();
              $('#addUserModalMAT').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserMAT', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
         var cart_fecham = $('#cart_fechamField').val();
      var medicam_matm = $('#medicam_matmField').val();
       var cart_qtyM = $('#cart_qtyMField').val();
      var cart_qtym = $('#cart_qtymField').val();
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (cart_qtym != '' && medicam_matm != '') {
        $.ajax({
          url: "update_userMAT.php",
          type: "post",
          data: {
              cart_fecham:cart_fecham,
            medicam_matm:medicam_matm,
            cart_qtym:cart_qtym,
            cart_qtyM:cart_qtyM,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleMAT').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnMAT">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnMAT">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id,cart_fecham, medicam_matm,cart_qtyM,cart_qtym, button]);
              $('#exampleModalMAT').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleMAT').on('click', '.editbtnMAT ', function(event) {
      var table = $('#exampleMAT').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalMAT').modal('show');

      $.ajax({
        url: "get_single_dataMAT.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
         
          $('#cart_fechamField').val(json.cart_fecha);
          $('#medicam_matmField').val(json.medicam_mat);
          $('#cart_qtymField').val(json.cart_qty);
          $('#cart_qtyMField').val(json.cart_surtido);
          
        
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnMAT', function(event) {
      var table = $('#exampleMAT').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Estas seguro de eliminar este registro? ? ")) {
        $.ajax({
          url: "delete_userMAT.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalMAT" tabindex="-1" aria-labelledby="exampleModalLabelsignosMAT" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelsignosMAT">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUserMAT">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
             <input type="hidden" name="cart_fecham" id="cart_fechamField" value="">
            <div class="mb-3 row">
              <label for="medicam_matmField" class="col-md-3 form-label">Material</label>
              <div class="col-md-9">
                     <input type="text" name="medicam_matm" id="medicam_matmField" class="form-control" disabled>
                 
              </div>
            </div>
          
            <div class="mb-3 row">
              <label for="cart_qtymField" class="col-md-3 form-label">Cantidad</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="cart_qtymField" name="cart_qtym">
              </div>
            </div>
            
             <div class="mb-3 row">
              <label for="cart_qtyMField" class="col-md-3 form-label">Cantidad Surtida</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="cart_qtyMField" name="cart_qtyM">
              </div>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalMAT" tabindex="-1" aria-labelledby="exampleModalLabelllMAT" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelllMAT">Nuevo registro de Materiales</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserMAT" action="">
              <div class="mb-3 row">
              <label for="addmedicam_matmField" class="col-md-3 form-label">Material</label>
              <div class="col-md-9">
                  
                  <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="medicam_matm" id="addmedicam_matmField" style="width : 100%; heigth : 100%">
                    <option value="">Seleccionar material</option>
                    <?php
                    $sql = "SELECT * FROM material_ceye, stock_ceye where material_ceye.material_controlado = 'NO' AND material_ceye.material_id = stock_ceye.item_id and stock_ceye.stock_qty > 0 ORDER BY material_ceye.material_nombre ASC";
                    $result = $conexion->query($sql);
                    while ($row_datos = $result->fetch_assoc()) {
                        echo "<option value='" . $row_datos['material_id'] . "'>" . $row_datos['material_nombre'] .', '.$row_datos['material_contenido'] ."</option>";
                    }?>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addcart_qtymField" class="col-md-3 form-label">Cantidad</label>
              <div class="col-md-9">
                <input type="number" class="form-control" id="addcart_qtymField" name="cart_qtym">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div> 


            <center>
        <div class="col-md-3">
            <center>
            <form action="" method="POST" name="formconfi<?php echo $id_atencion;?>" id="formconfi<?php echo $id_atencion;?>">
                <td>
                <input type="hidden" name="paciente" value="<?php echo $id_atencion; ?>">
                <button type="button" class="btn btn-block btn-success col-9" id="btnconfi<?php echo $id_atencion;?>" name="btnconfi<?php echo $id_atencion;?>">Confirmar </button>
                </td>
            </form>
            </center>
        </div>
    </center>
    <p></p>
    
 <script type="text/javascript">
    $(document).ready(function(){
        $('#btnconfi<?php echo $id_atencion;?>').click(function(){
            var datos=$('#formconfi<?php echo $id_atencion;?>').serialize();
            $.ajax({
                type:"POST",
                url:"manipula_carromat.php",
                data:datos,
                success:function(r){
                    if(r=1){
                        mytable = $('#exampleMAT').DataTable();
                         mytable.draw();
                         mytable = $('#exampleMATC').DataTable();
                         mytable.draw();
                        alertify.success("Materiales confirmados con éxito");
                       // $("#mytablemcons").load("vista_enf_quirurgico.php #mytablemcons");
                    }else{
                        alertify.error("Fallo el servidor");
                    }
                }
            });
            return false;
        });
    });
    </script>


    <div class="container">

               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoMATC" placeholder="Buscar...">
            </div>
            <h4>MATERIALES CONFIRMADOS</h4>
            <table id="exampleMATC" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de registro</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
          
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleMATC').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id_med_reg', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataMATC.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [3]
          },

        ]
      });
    });
    </script>


</div><!--termino pestaña MATERIALES-->

<!--INICIO PESTAÑA SIGNOS-->
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
      <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
         <tr><strong><center>SIGNOS VITALES</center></strong>
</div><br>
<div class="container">
            <div class="btnAdd">
         <center> <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModalS" class="btn btn-success">Agregar nuevos signos vitales</a></center>
        </div>
               <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:25%" id="search_nuevoS" placeholder="Buscar...">
            </div>
            <table id="exampleS" class="table">
              <thead>
                <th>Id</th>
                <th>Fecha de registro</th>
                <th>Fecha de reporte</th>
                <th>Hora</th>
                <th>Presión arterial</th>
                <th>Frecuencia cardiaca</th>
                <th>Saturación de Oxígeno</th>
                <th>Glicemia capilar</th>
                <th>Opciones</th>
              </thead>
              <tbody>
              </tbody>
            </table>
      
          <div class="col-md-2"></div>
        </div>
          
  <!-- Optional JavaScript; choose one of the two! -->
  <script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
  
  <script type="text/javascript">
    $(document).ready(function() {
      $('#exampleS').DataTable({
          "language": {
                 "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
      "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior",
        }
    },
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
          $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        searching: false,
        'order': [],
        'ajax': {
          'url': 'fetch_dataS.php',
          'type': 'post',
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [5]
          },

        ]
      });
    });
    $(document).on('submit', '#addUserS', function(e) {
      e.preventDefault();
      
	var hora = $('#addhoraField').val();
	var sistg = $('#addsistgField').val();
	var diastg = $('#adddiastgField').val();
    var fcardg = $('#addfcardgField').val();
    var satg = $('#addsatgField').val();
    var glic = $('#addglicField').val();
    var fechare = $('#addfechareField').val();
      
      if (hora != '' && sistg != '' && diastg != '' && fcardg != '' && satg != '' && glic != '' && fechare != '') {
        $.ajax({
          url: "add_userS.php",
          type: "post",
          data: {
		    hora: hora,
            sistg: sistg,
            diastg: diastg,
            fcardg: fcardg,
            satg:satg,
            glic:glic,
            fechare:fechare
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              mytable = $('#exampleS').DataTable();
              mytable.draw();
              document.getElementById("addUserS").reset();
              $('#addUserModalS').modal('hide');
                alertify.success("Registro agregado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $(document).on('submit', '#updateUserS', function(e) {
      e.preventDefault();
      //var tr = $(this).closest('tr');
      var hora = $('#horaField').val();
      var sistg = $('#sistgField').val();
      var diastg = $('#diastgField').val();
      var fcardg = $('#fcardgField').val();
      var satg = $('#satgField').val();
      var glic = $('#glicField').val();
      var fechare = $('#fechareField').val();
      var trid = $('#trid').val();
      var id = $('#id').val();
      if (hora != '' && sistg != '' && diastg != '' && fcardg != '' && satg != '' && glic != '' && fechare != '') {
        $.ajax({
          url: "update_userS.php",
          type: "post",
          data: {
            hora: hora,
            sistg: sistg,
            diastg: diastg,
            fcardg: fcardg,
            satg: satg,
            glic:glic,
            fechare:fechare,
            id: id
          },
          success: function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if (status == 'true') {
              table = $('#exampleS').DataTable();
              // table.cell(parseInt(trid) - 1,0).data(id);
              // table.cell(parseInt(trid) - 1,1).data(username);
              // table.cell(parseInt(trid) - 1,2).data(email);
              // table.cell(parseInt(trid) - 1,3).data(mobile);
              // table.cell(parseInt(trid) - 1,4).data(city);
 // table.cell(parseInt(trid) - 1,5).data(dispositivos);
              var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-warning btn-sm editbtnS">Editar</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtnS">Eliminar</a></td>';
              var row = table.row("[id='" + trid + "']");
              row.row("[id='" + trid + "']").data([id, hora, sistg, diastg, fcardg,satg,glic,fechare, button]);
              $('#exampleModalS').modal('hide');
               alertify.success("Registro editado correctamente");
            } else {
              alert('failed');
            }
          }
        });
      } else {
        alert('Completa todos los campos por favor!');
      }
    });
    $('#exampleS').on('click', '.editbtnS ', function(event) {
      var table = $('#exampleS').DataTable();
      var trid = $(this).closest('tr').attr('id');
      // console.log(selectedRow);
      var id = $(this).data('id');
      $('#exampleModalS').modal('show');
      $.ajax({
        url: "get_single_dataS.php",
        data: {
          id: id
        },
        type: 'post',
        success: function(data) {
          var json = JSON.parse(data);
          $('#horaField').val(json.hora);
          $('#sistgField').val(json.sistg);
          $('#diastgField').val(json.diastg);
          $('#fcardgField').val(json.fcardg);
          $('#satgField').val(json.satg);
          $('#glicField').val(json.glic);
          $('#fechareField').val(json.fechare);
        
          $('#id').val(id);
          $('#trid').val(trid);
        }
      })
    });

    $(document).on('click', '.deleteBtnS', function(event) {
      var table = $('#exampleS').DataTable();
      event.preventDefault();
      var id = $(this).data('id');
      if (confirm("Estas seguro de eliminar este registro? ? ")) {
        $.ajax({
          url: "delete_userS.php",
          data: {
            id: id
          },
          type: "post",
          success: function(data) {
            var json = JSON.parse(data);
            status = json.status;
            if (status == 'success') {
              //table.fnDeleteRow( table.$('#' + id)[0] );
              //$("#example tbody").find(id).remove();
              //table.row($(this).closest("tr")) .remove();
              $("#" + id).closest('tr').remove();
               alertify.success("Registro eliminado correctamente");
            } else {
              alert('Failed');
              return;
            }
          }
        });
      } else {
        return null;
      }



    })
  </script>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalS" tabindex="-1" aria-labelledby="exampleModalLabelsignos" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelsignos">Editar registro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateUserS">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trid" id="trid" value="">
            <div class="mb-3 row">
              <label for="horaField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                   <input type="time" name="hora" id="horaField" class="form-control">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="sistgField" class="col-sm-4 form-label">Presión arterial</label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="sistgField" name="sistg">
              </div>/
              <div class="col-md-3">
                <input type="text" class="form-control" id="diastgField" name="diastg">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fcardgField" class="col-md-3 form-label">Frecuencia cardiaca</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="fcardgField" name="fcardg">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="satgField" class="col-md-3 form-label">Saturación Oxigeno</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="satgField" name="satg">
              </div>
            </div>
             <div class="mb-3 row">
              <label for="glicField" class="col-md-3 form-label">Glicemia capilar</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="glicField" name="glic">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="fechareField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                <input type="date" class="form-control" id="fechareField" name="fechare">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Editar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add user Modal -->
  <div class="modal fade" id="addUserModalS" tabindex="-1" aria-labelledby="exampleModalLabelll" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabelll">Nuevo registro de signos vitales</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserS" action="">
              <div class="mb-3 row">
              <label for="addhoraField" class="col-md-3 form-label">Hora</label>
              <div class="col-md-9">
                  <input type="time" name="hora" id="addhoraField" class="form-control">
                 
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addsistgField" class="col-sm-4 form-label">Presión arterial</label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="addsistgField" name="sistg">
              </div>/
              <div class="col-md-3">
                <input type="text" class="form-control" id="adddiastgField" name="diastg">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addfcardgField" class="col-md-3 form-label">Frecuencia cardiaca</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addfcardgField" name="fcardg">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addsatgField" class="col-md-3 form-label">Saturación Oxigeno</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addsatgField" name="satg">
              </div>
            </div>
             <div class="mb-3 row">
              <label for="addglicField" class="col-md-3 form-label">Glicemia capilar</label>
              <div class="col-md-9">
                <input type="text" class="form-control" id="addglicField" name="glic">
              </div>
            </div>
            <div class="mb-3 row">
              <label for="addfechareField" class="col-md-3 form-label">Fecha de reporte</label>
              <div class="col-md-9">
                  <?php $fr = date("Y-m-d H:i");?>
                <input type="date" class="form-control" id="addfechareField" name="fechare" value="<?php echo $fecha_actual = date("Y-m-d");?>">
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>        
          
</div><!--TERMINO NOTA-->




<hr>





            <?php
            } else {
                echo '<script type="text/javascript"> window.location.href="../../template/select_pac_enf.php";</script>';
            }
            ?>
        </div>
    </div>
</section>
</div>

<footer class="main-footer">
    <?php
    include("../../template/footer.php");
    ?>
</footer>
<!-- scripts para buscador

-->

<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->

<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<!--script que no se usan-->
<script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/main.js"></script>


<script type="text/javascript">
    $('.losInput8 input').on('change', function(){
  var total = 0;
  $('.losInput8 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal8 input').val(total.toFixed());
});


    $('.losInput2 input').on('change', function(){
  var total = 0;
  $('.losInput2 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal2 input').val(total.toFixed());
});


  $('.losInputin input').on('change', function(){
  var total = 0;
  $('.losInputin input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotalin input').val(total.toFixed());
});
</script>

<script type="text/javascript">
   $(document).ready(function() {
    $('.js-example-basic-single').select2();
});
      $(document).ready(function () {
        $('#mibuscadorcir').select2();
    });
</script>
<script type="text/javascript">
$(document).ready(function() {
    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
});
</script>
</body>

</html>