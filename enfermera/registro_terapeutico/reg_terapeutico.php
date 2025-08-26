<?php
session_start();
//include "../../conexionbd.php";
include "../header_enfermera.php";
$resultado = $conexion->query("select * from reg_usuarios") or die($conexion->error);
$usuario = $_SESSION['login'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv=”Content-Type” content=”text/html; charset=ISO-8859-1″/>
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
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

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
    <title>DETALLE DE LA CUENTA</title>
    <style>
        hr.new4 {
            border: 1px solid red;
        }
        #im{
margin-left: 178px;

}

#nuev1{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left:370px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;

}


#nuev2{
position: absolute;
top:222px; left:360px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
 
  border: 0;
   outline: none;
border:1px solid steelblue;

}


#nuev3{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 215px; left:312px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev4{
position: absolute;
top:222px; left:260px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;

}

#nuev5{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 185px; left:312px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev6{
position: absolute;
top:190px; left:260px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;

}

#nuev7{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 185px; left:342px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev8{
position: absolute;
top:195px; left:310px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;

}

#nuev9{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 185px; left:370px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev10{
position: absolute;
top:195px; left:360px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;
}

#nuev11{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 155px; left:370px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev12{
position: absolute;
top:147px; left:380px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;
}

#nuev13{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 155px; left:342px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev14{
position: absolute;
top:165px; left:320px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;
}

#nuev15{
background-color: transparent;
border: 1px solid transparent;
position: absolute;
top: 155px; left:312px;  
padding: 0px 0px;
font-size: 9px;
cursor: pointer;
color: black;
font-weight: bold;
}

#nuev16{
position: absolute;
top:165px; left:270px;  
 font-size: 10px;
   font-weight: bold;
   background-color: transparent;
  border: 0;
   outline: none;
border:1px solid steelblue;
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
top:425px; left:220px;  
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

        <div class="content">
          
            <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 25px;">
                 <tr><strong><center>REGISTRO DE TERAPÍA INTENSIVA - ENFERMERÍA</center></strong>
        </div>
         <hr>
 <font size="2">
         <div class="container">
  <div class="row">
    <div class="col-sm-6">

      Expediente: <strong><?php echo $folio?> </strong>
    
     Paciente: <strong><?php echo $pac_papell . ' ' . $pac_sapell . ' ' . $pac_nom_pac ?></strong>
    </div>
    <div class="col-sm">
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
    Aseguradora: <strong><?php $sql_aseg = "SELECT aseg from dat_financieros where id_atencion =$id_atencion ORDER BY fecha DESC limit 1";
            $result_aseg = $conexion->query($sql_aseg);
                while ($row_aseg = $result_aseg->fetch_assoc()) {
                echo $row_aseg['aseg'];
            }
                 ?></strong>
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
<center><strong>TURNO:</strong></center>
<nav>

  <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-diurno" role="tab" aria-controls="nav-contact" aria-selected="false"><h6>SIGNOS VITALES</h6></a>
    <a class="nav-item nav-link" id="nav-ing-tab" data-toggle="tab" href="#nav-ing" role="tab" aria-controls="nav-ing" aria-selected="false"><h6>INGRESOS</h6></a>
    <a class="nav-item nav-link" id="nav-eg-tab" data-toggle="tab" href="#nav-eg" role="tab" aria-controls="nav-eg" aria-selected="false"><h6>EGRESOS</h6></a>
    <a class="nav-item nav-link" id="nav-cate-tab" data-toggle="tab" href="#cate" role="tab" aria-controls="cate" aria-selected="false"><h6>CONTROL <br> CATÉTERES</h6></a>
    <a class="nav-item nav-link" id="nav-val-tab" data-toggle="tab" href="#val" role="tab" aria-controls="val" aria-selected="false"><h6>VALORACIÓN PARA <br>CUIDADOS ESPECIFICOS DE <br>SEGURIDAD Y PROTECCIÓN</h6></a> 
    <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-matutino" role="tab" aria-controls="nav-home" aria-selected="false"><h6>VALORACIONES Y<br>ESCALAS</h6></a>
    <a class="nav-item nav-link" id="nav-not-tab" data-toggle="tab" href="#not" role="tab" aria-controls="nav-not" aria-selected="false"><h6>NOTAS DE <br> ENFERMERIA</h6></a>
    
    
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    
    <div class="tab-pane fade" id="not" role="tabpanel" aria-labelledby="nav-not-tab">
        <form action="" method="POST">
<p></p>
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>

      <strong>Hora:</strong>
      <select class="form-control col-3" aria-label="Default select example" name="hora" required="">
  <option value="">Seleccionar hora</option>
  <option value="8">8:00 A.M.</option>
  <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 P.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>

</select>
    <p>


<th><h5><strong>Nota de enfermería</strong></h5></th>
<div class="botones">
<button type="button" class="btn btn-danger btn-sm" id="ferg"><i class="fas fa-microphone"></button></i>
<button type="button" class="btn btn-primary btn-sm" id="stoprial"><i class="fas fa-microphone-slash"></button></i>
<button type="button" class="btn btn-success btn-sm" id="playnoc"><i class="fas fa-play"></button></i>
</div>
<textarea rows="3" class="form-control" name="notaenf" id="txtuee"></textarea>
<script type="text/javascript">
const ferg = document.getElementById('ferg');
const stoprial = document.getElementById('stoprial');
const txtuee = document.getElementById('txtuee');

const btnPlaynotno = document.getElementById('playnoc');
btnPlaynotno.addEventListener('click', () => {
        leerTexto(txtuee.value);
});

function leerTexto(txtuee){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtuee;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}

     let notar = new webkitSpeechRecognition();
      notar.lang = "es-ES";
      notar.continuous = true;
      notar.interimResults = false;

      notar.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length -1][0].transcript;
        txtuee.value += frase;
      }

      ferg.addEventListener('click', () => {
        notar.start();
      });

      stoprial.addEventListener('click', () => {
        notar.abort();
      });
</script>
<br>
  <div class="form-group col-12">
<center><button type="submit" name="btnnotaenf" class="btn btn-primary">Firmar</button>
<button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button></center>
</div>
</form>

<hr>

<?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultadon = $conexion->query("SELECT * from nota_enf_ter WHERE id_atencion=$id_atencion ORDER BY id_nota_ter DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>
<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="8"><center><h5><strong>Notas de enfermería</strong></h5></center></th>
         </tr>

    <tr class="table-success">
      <th scope="col"><center>Fecha de registro</center></th>
      <th scope="col"><center>Fecha reporte</center></th>
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Editar</center></th>
      <th scope="col"><center>Eliminar</center></th>

    </tr>


  </thead>
  <tbody>

      <?php
                while($f = mysqli_fetch_array($resultadon)){
                    $registro = $f['id_usua'];
                    
                    ?>
    <tr>
      <td><center><strong> <?php $fech=date_create($f['fecha_registro']); echo date_format($fech,"d-m-Y H:i a");?></center></strong></td>
<td><center><strong> <?php $fechr=date_create($f['fecha']); echo date_format($fechr,"d-m-Y");?></center></strong></td>
      <td><center><strong> <?php echo $f['hora'];?> </strong></center></td>
      <td><center><strong>
        <?php echo $f['nota'];?></strong></center>
       </td>
    

 <td><center><a href="editar_notaenf.php?id=<?php echo $f['id_nota_ter'];?>" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td></center>

 <td><center><a href="el_notanef.php?id=<?php echo $f['id_nota_ter'];?>" title="Eliminar dato" class="btn btn-danger btn-sm "><span class="fa fa-trash" aria-hidden="true"></span></a></td>

    </tr>


       <?php
                }
                ?>
  </tbody>
</table>

 <?php

          if (isset($_POST['btnnotaenf'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];

$hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
$notaenf =  mysqli_real_escape_string($conexion, (strip_tags($_POST["notaenf"], ENT_QUOTES)));
$fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));



if($hora=='8' || $hora=='9' || $hora=='10' || $hora=='11'|| $hora=='12'|| $hora=='13' || $hora=='14'){
$turno="MATUTINO";
} else if ($hora=='15' || $hora=='16' || $hora=='17'|| $hora=='18'|| $hora=='19' || $hora=='20' || $hora=='21') {
  $turno="VESPERTINO";
}else if ($hora=='22' || $hora=='23' || $hora=='24'|| $hora=='1'|| $hora=='2' || $hora=='3' || $hora=='4' || $hora=='5' || $hora=='6' || $hora=='7') {
    $turno="NOCTURNO";
}

$fecha_actual = date("Y-m-d H:i:s");

if ($hora == '1' || $hora == '2' || $hora == '3' || $hora == '4' || $hora == '5' || $hora == '6' || $hora == '7') {
   // Restamos un día a la fecha actual
   //$yesterday = date('Y-m-d H:i', strtotime('-1 day')) ; 
   $yesterday = date("Y-m-d H:i"); 
} else { 
   $yesterday = date("Y-m-d H:i"); 
}


$ingresarnot = mysqli_query($conexion, 'INSERT INTO nota_enf_ter (
  id_atencion,fecha,hora,turno,nota,id_usua,fecha_registro) values (' . $id_atencion . ' , "' . $fecha . '" , "' . $hora . '" , "' . $turno . '" , "' . $notaenf . '" ,' . $id_usua . ', "' . $yesterday . '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


           echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
          }
          ?>


        </div>

<div class="tab-pane fade" id="val" role="tabpanel" aria-labelledby="nav-val-tab">
     <form action="" method="POST">
<div class="table-responsive">
<table class="table table-bordered">
  <thead>
     <tr class="table-warning">
      <th colspan="18"><center><h5><strong>CUIDADOS ESPECIFICOS DE SEGURIDAD Y PROTECCIÓN / PARAMETROS VENTILATORIOS</strong></h5></center></th>
    </tr>
    <tr class="table-active">
      <th scope="col" colspan="1"><center>Hora</center></th>
      <th scope="col" colspan="1"><center>Tipo</center></th>
      <th scope="col" colspan="1" >Cantidad</th>
      <th scope="col" colspan="1">Observaciones</th>
    </tr>

  </thead>
  <tbody>
    <tr>
      <td><select class="form-control" name="hora" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
  

        </select></td>
      <th scope="row" colspan="1">  <select name="tipo" class="form-control" required>
        <option value="">Seleccionar</option>
        <option value="Glasgow">Glasgow</option>
        <option value="Neurologico">Neurológico</option>
        <option value="Glicemia capilar">Glicemia capilar</option>
        <option value="Presion intracraneal">Presión intracraneal</option>
        <option value="Presion perfusion cerebral">Presión perfusión cerebral</option>
        <option value="Presion intraabdominal">Presión intraabdominal</option>
        <option value="Perimetro abdominal">Perímetro abdominal</option>
        <option value="Presion perfusion abdominal">Presión perfusión abdominal</option>
        <option value="Presion venosa central">Presión venosa central</option>
        <option value="Insulina">Insulina</option>
        <option value="Dextrosa">Dextrosa al 50%</option>
        <option value="Oxigenoterapia">Oxigenoterapía</option>
        <option value="Micronebulizaciones">Micronebulizaciones</option>
        <option value="Enema">Enema</option>
   
        <option value="Modo ventilatorio">Modo ventilatorio</option>
        <option value="Volumen corriente">Volumen corriente</option>    
        <option value="Frecuencia respiratoria">Frecuencia respiratoria</option>
        <option value="Fraccion inspirada de oxigeno">Fracción inspirada de oxígeno</option>
        <option value="PEEP">PEEP</option>
        <option value="Presion inspiratoria">Presión inspiratoria</option>
        <option value="Presion pico">Presión pico</option>
  
      </select>
    </th>
<td class="col-2"><input type="text" name="cantidad" class="form-control"></td>
<td colspan="1"><textarea name="obs" class="form-control">
    </textarea></td></tr>
</tbody>

</table>

</div>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>
</div><p></p>
<center><input type="submit" name="btnmon" class="btn btn-block btn-success col-3" value="Agregar"></center>

</form>
<?php
if(isset($_POST['btnmon'])){
     include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];
$hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
$tipo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
$cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));
$obs =  mysqli_real_escape_string($conexion, (strip_tags($_POST["obs"], ENT_QUOTES)));
$fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));


$fr = date("Y-m-d H:i");


$fech_a = date("Y-m-d");
   $ingresar4 = mysqli_query($conexion, 'INSERT INTO monitoreo_sust (id_atencion,id_usua,hora,tipo,cantidad,obs,fecha_registro,fecha,ter) values ('.$id_atencion.',' . $id_usua . ',"' . $hora . '","' . $tipo . '","'.$cantidad.'","'.$obs.'","'.$fr.'","'.$fecha.'","Si") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
    }
    ?>

 <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="search" placeholder="BUSCAR...">
            </div>
 <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from monitoreo_sust WHERE id_atencion=$id_atencion and ter='Si' ORDER BY id_mon_s DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="10"><center><h5><strong>Monitoreo</strong></h5></center></th>
         </tr>
    <tr class="table-success">
            <th scope="col"><center>Fecha de registro</center></th>
       <th scope="col"><center>Fecha reporte</center></th>
     <th scope="col" >Hora</th>
      <th scope="col" >Tipo</th>
      <th scope="col">Cantidad</th>
      <th scope="col">Observaciones</th>
       <th scope="col"><center>Editar</center></th>
       <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>
      <?php
                while($f = mysqli_fetch_array($resultado)){
                    
                    ?>
<tr>
    <td><center><strong> <?php $fech_r=date_create($f['fecha_registro']); echo date_format($fech_r,"d-m-Y H:i a");?> </strong></center></td>
<td><center><strong> <?php $fech_rr=date_create($f['fecha']); echo date_format($fech_rr,"d-m-Y");?> </strong></center></td>
<td><center><strong> <?php echo $f['hora'];?> </strong></center></td>
<td><center><strong> <?php echo $f['tipo'];?> </strong></center></td>

<td><center><strong> <?php echo $f['cantidad'];?> </strong></center></td>
<td><center><strong> <?php echo $f['obs'];?> </strong></center></td>



<td><a href="edit_mon.php?id_mon_s=<?php echo $f['id_mon_s'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>

<td><a href="el_mon.php?id_mon_s=<?php echo $f['id_mon_s'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>
 
    </tr>
       <?php
                }
                 
                ?>
  </tbody>
</table>

<!--OJOS OJOS OJOS OJOS--><!--OJOS OJOS OJOS OJOS--><!--OJOS OJOS OJOS OJOS--><!--OJOS OJOS OJOS OJOS-->
<hr>
 <form action="" method="POST">
<div class="table-responsive">
<table class="table table-bordered">
  <thead>
     <tr class="table-warning">
      <th colspan="18"><center><h5><strong>DIÁMETRO PUPILAR</strong></h5></center></th>
    </tr>
    <tr class="table-active">
      <th scope="col" colspan="1"><center>Hora</center></th>
      <th scope="col" colspan="1"><center>Ojo</center></th>
      <th scope="col" colspan="1" >Tamaño</th>
  
    </tr>

  </thead>
  <tbody>
    <tr>
      <td><select class="form-control" name="hora" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
  

        </select></td>
      <th scope="row" colspan="1">  <select name="lado" class="form-control" required>
        <option value="">Seleccionar lado de ojo</option>
         <option value="Ojo izquierdo">Ojo izquierdo</option>
          <option value="Ojo Derecho">Ojo Derecho</option>
          
               
      </select>
    </th>
<td class="col-5"><input type="text" name="tamano" class="form-control"></td>
</tr>
</tbody>

</table>

</div>
<img src="../../imagenes/val_pupilar.jpg" class="img-fluid"><p></p>
<div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>
</div><p></p>
<center><input type="submit" name="btnojo" class="btn btn-block btn-success col-3" value="Agregar"></center>

</form>
<?php
if(isset($_POST['btnojo'])){
     include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];
$hora =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora"], ENT_QUOTES)));
$lado =  mysqli_real_escape_string($conexion, (strip_tags($_POST["lado"], ENT_QUOTES)));
$tamano =  mysqli_real_escape_string($conexion, (strip_tags($_POST["tamano"], ENT_QUOTES)));
$fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
$fecha_registro = date("Y-m-d H:i");

   $ingresar4 = mysqli_query($conexion, 'INSERT INTO d_pupilar (id_atencion,id_usua,hora,lado,tamano,fecha_registro,fecha_reporte,ter) values ('.$id_atencion.',' . $id_usua . ',"' . $hora . '","' . $lado . '","'.$tamano.'","'.$fecha_registro.'","'.$fecha.'","Si") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
    }
    ?>

 <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="search" placeholder="BUSCAR...">
            </div>
 <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from d_pupilar WHERE id_atencion=$id_atencion and ter='Si' ORDER BY id_pupilar DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="10"><center><h5><strong>DIÁMETRO PUPILAR</strong></h5></center></th>
         </tr>
    <tr class="table-success">
            <th scope="col"><center>Fecha de registro</center></th>
       <th scope="col"><center>Fecha reporte</center></th>
     <th scope="col" >Hora</th>
      <th scope="col" >Lado</th>
      <th scope="col">Tamaño</th>
       <th scope="col"><center>Editar</center></th>
       <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>
      <?php
                while($f = mysqli_fetch_array($resultado)){
                    
                    ?>
<tr>
    <td><center><strong> <?php $fech_r=date_create($f['fecha_registro']); echo date_format($fech_r,"d-m-Y H:i a");?> </strong></center></td>
<td><center><strong> <?php $fech_rr=date_create($f['fecha']); echo date_format($fech_rr,"d-m-Y");?> </strong></center></td>
<td><center><strong> <?php echo $f['hora'];?> </strong></center></td>
<td><center><strong> <?php echo $f['lado'];?> </strong></center></td>

<td><center><strong> <?php echo $f['tamano'];?> </strong></center></td>




<td><a href="edit_pupilar.php?id_mon_s=<?php echo $f['id_pupilar'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>

<td><a href="el_pupilar.php?id_mon_s=<?php echo $f['id_pupilar'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>
 
    </tr>
       <?php
                }
                 
                ?>
  </tbody>
</table>
    </div>

<!--cateteres -->

<div class="tab-pane fade" id="cate" role="tabpanel" aria-labelledby="nav-cate-tab">
  <form action="" method="POST">
<div class="table-responsive">
<table class="table table-bordered">
  <thead>
     <tr class="table-warning">
      <th colspan="18"><center><h5><strong>CONTROL DE CATÉTERES</strong></h5></center></th>
    </tr>
    <tr class="table-active">
      <th scope="col" colspan="1"><center>Dispositivos</center></th>
      <th scope="col" colspan="1" >Tipo</th>
      <th scope="col" colspan="9">Fecha de instalación</th>
       <th scope="col" colspan="2">Instaló</th>
      <th scope="col" colspan="1">Dias instalado</th>
      <th scope="col" colspan="1">Fecha de cambio</th>
      <th scope="col" colspan="1">Observaciones</th>
    </tr>

  </thead>
  <tbody>
    <tr>
      <th scope="row" colspan="1">  <select name="dispositivos" class="form-control" >
        <option value="">Seleccionar dispositivo</option>
         <option value="CATETER CENTRAL">CATÉTER CENTRAL</option>
          <option value="CATETER PERIFERICO">CATÉTER PERIFERICO</option>
           <option value="SONDA VESICAL">SONDA VESICAL</option>
            <option value="SONDA NASOGASTRICA">SONDA NASOGÁSTRICA</option>
             <option value="OTROS">OTROS</option>
      </select>
    </th>

<td colspan="1"><input type="text" name="tipo" class="form-control"></td>
      
<td colspan="2"><input type="date" name="fecha_inst" class="form-control"></td>

<td colspan="9"><input type="text" name="instalo" class="form-control"></td>
<td colspan="1"><input type="text" name="dias_inst" class="form-control"></td>

<td colspan="1"><input type="date" name="fecha_cambio" class="form-control"></td>

<td colspan="1"><input type="text" name="cultivo" class="form-control"></td></tr>
 
</tbody>
</table>
</div>

<center><input type="submit" name="btncate" class="btn btn-block btn-success col-3" value="Agregar"></center>
</form >
<?php
if(isset($_POST['btncate'])){
     include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$id_usua= $usuario['id_usua'];
$id_atencion = $_SESSION['pac'];
$dispositivos =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dispositivos"], ENT_QUOTES)));
$tipo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
$fecha_inst =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_inst"], ENT_QUOTES)));
$instalo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["instalo"], ENT_QUOTES)));
$dias_inst =  mysqli_real_escape_string($conexion, (strip_tags($_POST["dias_inst"], ENT_QUOTES)));
$fecha_cambio =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha_cambio"], ENT_QUOTES)));
$cultivo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cultivo"], ENT_QUOTES)));


date_default_timezone_set('America/Guatemala');
$fr = date("Y-m-d H:i");
   $ingresar4 = mysqli_query($conexion, 'INSERT INTO cate_enf_ter (id_atencion,id_usua,dispositivos,tipo,fecha_inst,instalo,dias_inst,fecha_cambio,cultivo,fecha_registro,tip) values ('.$id_atencion.',' . $id_usua . ',"' . $dispositivos .'","' . $tipo . '","'.$fecha_inst.'","'.$instalo.'","'.$dias_inst.'","'.$fecha_cambio.'","'.$cultivo.'","'.$fr.'","Terapia intensiva") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
    }
    ?>

 <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="search" placeholder="BUSCAR...">
            </div>
 <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from cate_enf_ter WHERE id_atencion=$id_atencion and tip='Terapia intensiva'ORDER BY id DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="10"><center><h5><strong>CONTROL DE CATÉTERES</strong></h5></center></th>
         </tr>
    <tr class="table-success">
      <th scope="col"><center>Dispositivos</center></th>
      <th scope="col"><center>Tipo</center></th>
      <th scope="col"><center>Fecha de instalación</center></th>
      <th scope="col"><center>Instaló</center></th>
      <th scope="col"><center>Dias instalado</center></th>
      <th scope="col"><center>Fecha de cambio</center></th>
      <th scope="col"><center>Observaciones</center></th>
       <th scope="col"><center>Fecha de registro</center></th>
 <th scope="col"><center>Editar</center></th>
  <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>
      <?php
                while($f = mysqli_fetch_array($resultado)){
                    
                    ?>
<tr>
<td><center><strong> <?php echo $f['dispositivos'];?> </strong></center></td>
<td><center><strong> <?php echo $f['tipo'];?> </strong></center></td>
<td><center><strong> <?php $fech_i=date_create($f['fecha_inst']); echo date_format($fech_i,"d-m-Y");?></strong></center></td>
<td><center><strong> <?php echo $f['instalo'];?> </strong></center></td>
<td><center><strong> <?php echo $f['dias_inst'];?> </strong></center></td>
<td><center><strong> <?php $fech_c=date_create($f['fecha_cambio']); echo date_format($fech_c,"d-m-Y");?></strong></center></td>
<td><center><strong> <?php echo $f['cultivo'];?> </strong></center></td>
<td><center><strong> <?php $fech_r=date_create($f['fecha_registro']); echo date_format($fech_r,"d-m-Y H:i");?> </strong></center></td>

<td><a href="edit_cate_ter.php?id_cate_ter=<?php echo $f['id'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-warning"><i class="fas fa-edit"></i></button></td>

<td><a href="el_cate_ter.php?id_cate_ter=<?php echo $f['id'];?>&id_atencion=<?php echo $f['id_atencion'];?>"><button type="button" class="btn btn-danger">X </button></td>
 
    </tr>
       <?php
                }
                 
                ?>
  </tbody>
</table>

</div>
<!-- fin cateteres-->


 <div class="tab-pane fade" id="nav-ing" role="tabpanel" aria-labelledby="nav-ing-tab">  
<form action="" method="POST">
 <div class="container">
    <div class="row">
           <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="5"><center><h5><strong>INGRESOS</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td> <select class="form-control" name="hora_med" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
    </select></td>
      <td><select class="form-control" name="desc" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar descripción</option>
        <option value="HEMODERIVADOS">HEMODERIVADOS</option>
        <option value="SOLUCION BASE">SOLUCIÓN BASE</option>
        <option value="MEDICAMENTOS">MEDICAMENTOS</option>
        <option value="VIA ORAL">VIA ORAL</option>
        <option value="AMINAS">AMINAS</option>
        <option value="INFUSIONES 1">INFUSIONES 1</option>
        <option value="INFUSIONES 2">INFUSIONES 2</option>
        <option value="INFUSIONES 3">INFUSIONES 3</option>
        <option value="INFUSIONES 4">INFUSIONES 4</option>
        <option value="NUTRICION ENTERAL">NUTRICIÓN ENTERAL</option>
        <option value="NUTRICION PARENTERAL">NUTRICIÓN PARENTERAL</option>
        <option value="CARGAS">CARGAS</option>
    </select></td>
      <td><input type="cm-number" name="cantidad" class="form-control"></td>
      
      <td><input type="submit" name="btning" class="btn btn-block btn-success" value="Agregar"></td>
    </tr>
    
  </tbody>
</table>

     </div>
 </div> 
 <div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>
</div>
</form>
<?php
if(isset($_POST['btning'])){
     include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $hora_med =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_med"], ENT_QUOTES)));
            $desc =  mysqli_real_escape_string($conexion, (strip_tags($_POST["desc"], ENT_QUOTES)));
            $cantidad =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cantidad"], ENT_QUOTES)));
            $fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));
            
if ($hora_med == '1' || $hora_med == '2' || $hora_med == '3' || $hora_med == '4' || $hora_med == '5' || $hora_med == '6' || $hora_med == '7') {
   // Restamos un día a la fecha actual
   //$yesterday = date('Y-m-d H:i', strtotime('-1 day')) ; 
   $yesterday = date("Y-m-d H:i"); 
} else { 
   $yesterday = date("Y-m-d H:i"); 
}
            

$fe = date("Y-m-d");
   $ingresar2 = mysqli_query($conexion, 'INSERT INTO ing_enf_ter (id_atencion,id_usua,hora,des,cantidad,fecha,fecha_registro) values ('.$id_atencion.',' . $id_usua . ',"' . $hora_med .'","' . $desc . '","'.$cantidad.'","'.$fecha.'","'.$yesterday.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
    }
    ?>
     <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="search" placeholder="Buscar...">
            </div>
            
             <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from ing_enf_ter WHERE id_atencion=$id_atencion ORDER BY id_ing_ter DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable3">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="8"><center><h5><strong>INGRESOS</strong></h5></center></th>
         </tr>

    <tr class="table-success">
      <th scope="col"><center>Fecha de registro</center></th>
      <th scope="col"><center>Fecha reporte</center></th>
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad Ml</center></th>
      <th scope="col"><center>Registró</center></th>
      <th scope="col"><center>Editar</center></th>
      <th scope="col"><center>Eliminar</center></th>

    </tr>


  </thead>
  <tbody>

      <?php
                while($f = mysqli_fetch_array($resultado)){
                    $registro = $f['id_usua'];
                    
                    ?>
    <tr>
      <td><center><strong> <?php $fech=date_create($f['fecha_registro']); echo date_format($fech,"d-m-Y H:i a");?></center></strong></td>
<td><center><strong> <?php $fechr=date_create($f['fecha']); echo date_format($fechr,"d-m-Y");?></center></strong></td>
      <td><center><strong> <?php echo $f['hora'];?> </strong></center></td>
      <td><center><strong>
        <?php echo $f['des'];?></strong></center>
       </td>
      <td><center><strong><?php echo $f['cantidad'];?></strong></center></td>
      <?php $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$registro") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)) {$registro = $row[papell]; }?>
        
        <td><center><strong><?php echo $registro;?></strong></center></td>

 <td><center><a href="editar_ingresos.php?id=<?php echo $f['id_ing_ter'];?>" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td></center>

 <td><center><a href="el_ingresos.php?id=<?php echo $f['id_ing_ter'];?>" title="Eliminar dato" class="btn btn-danger btn-sm "><span class="fa fa-trash" aria-hidden="true"></span></a></td>

    </tr>


       <?php
                }
                ?>
  </tbody>
</table>

 </div>

 <div class="tab-pane fade" id="nav-eg" role="tabpanel" aria-labelledby="nav-eg-tab">  
<form action="" method="POST">
 <div class="container">
    <div class="row">
           <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="5"><center><h5><strong>EGRESOS</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad</center></th>
      <th scope="col"><center>Características</center></th>
      <th scope="col"><center></center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td> <select class="form-control" name="hora_eg" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
  

  </select></td>
  <td><select class="form-control" name="des_eg" style="width : 100%; heigth : 100%" required="">
  <option value="">Seleccionar descripción</option>
  <option value="ORINA">ORINA</option>
  <option value="VOMITO">VOMITO</option>
  <option value="SANGRADO">SANGRADO</option>
  <option value="SONDA NASOGASTRICA">SONDA NASOGASTRICA</option>
  <option value="SONDA T">SONDA T</option>
  <option value="EVACUACIONES">EVACUACIONES</option>
  <option value="COLOSTOMIA">COLOSTOMIA</option>
  <option value="BIOVAC IZQ">BIOVAC IZQ</option>
  <option value="BIOVAC DER">BIOVAC DER</option>
  <option value="DRENOVAC">DRENOVAC</option>
  <option value="PENROSE IZQ">PENROSE IZQ</option>
  <option value="PENROSE DER">PENROSE DER</option>
  <option value="SARATOGA">SARATOGA</option>
  <option value="ILEOSTOMIAS">ILEOSTOMIAS</option>
  <option value="PLEUROVAC">PLEUROVAC</option>
  <!--<option value="OTROS">OTROS</option>-->


        </select></td>
      <td><input type="cm-number" name="cant_eg" class="form-control"></td>
       <td><textarea name="carac" class="form-control" rows="1"></textarea></td>
      <td><input type="submit" name="btneg" class="btn btn-block btn-success" value="Agregar"></td>
    </tr>
  </tbody>
</table>
     </div>
 </div> 
 <div class="container">
  <div class="row">
    <div class="col-sm-2">
     <strong>Fecha de reporte:</strong>
    </div>
    <div class="col-sm-3">
      <input type="date" value="<?php echo $fecha_actual = date("Y-m-d") ?>" name="fecha" class="form-control" required>
    </div>
  </div>
</div>
</form><hr>
<?php
if(isset($_POST['btneg'])){
     include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
            $hora_eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_eg"], ENT_QUOTES)));
            $des_eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["des_eg"], ENT_QUOTES)));
            $cant_eg =  mysqli_real_escape_string($conexion, (strip_tags($_POST["cant_eg"], ENT_QUOTES)));
            $carac =  mysqli_real_escape_string($conexion, (strip_tags($_POST["carac"], ENT_QUOTES)));
            $fecha =  mysqli_real_escape_string($conexion, (strip_tags($_POST["fecha"], ENT_QUOTES)));

$feceg = date("Y-m-d");

if ($hora_eg == '1' || $hora_eg == '2' || $hora_eg == '3' || $hora_eg == '4' || $hora_eg == '5' || $hora_eg == '6' || $hora_eg == '7') {
   // Restamos un día a la fecha actual
   //$yesterday = date('Y-m-d H:i', strtotime('-1 day')) ; 
   $yesterday = date("Y-m-d H:i"); 
} else { 
   $yesterday = date("Y-m-d H:i"); 
}
   $ingresar2 = mysqli_query($conexion, 'INSERT INTO eg_enf_ter (id_atencion,id_usua,hora_eg,des_eg,cant_eg,carac,fecha_eg,fecha_registro) values ('.$id_atencion.',' . $id_usua . ',"' . $hora_eg .'","' . $des_eg . '","'.$cant_eg.'","'.$carac.'","'.$fecha.'","'.$yesterday.'") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));

      echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
    }
    ?>
    <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:19%" id="searche" placeholder="Buscar...">
            </div>
 <?php

include "../../conexionbd.php";
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT * from eg_enf_ter WHERE id_atencion=$id_atencion ORDER BY id_eg_ter DESC") or die($conexion->error);
$usuario = $_SESSION['login'];
?>

<table class="table table-bordered table-striped" id="mytable4">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="9"><center><h5><strong>EGRESOS</strong></h5></center></th>
         </tr>
    <tr class="table-success">
      <th scope="col"><center>Fecha de registro</center></th>
       <th scope="col"><center>Fecha reporte</center></th>
      <th scope="col"><center>Hora</center></th>
      <th scope="col"><center>Descripción</center></th>
      <th scope="col"><center>Cantidad Ml</center></th>
      <th scope="col"><center>Características</center></th>
      <th scope="col"><center>Registró</center></th>
      <th scope="col"><center>Editar</center></th>
      <th scope="col"><center>Eliminar</center></th>
    </tr>
  </thead>
  <tbody>
      <?
                while($f = mysqli_fetch_array($resultado)){
                    $registro=$f['id_usua'];
                    
                    ?>
    <tr>
      <td><center><strong> <?php $fech_e=date_create($f['fecha_registro']); echo date_format($fech_e,"d-m-Y H:i");?></center></strong></td>
<td><center><strong> <?php $fech_er=date_create($f['fecha_eg']); echo date_format($fech_er,"d-m-Y");?></center></strong></td>
      <td><center><strong> <?php echo $f['hora_eg'];?> </strong></center></td>
      <td><center><strong>
        <?php echo $f['des_eg'];?></strong></center>
       </td>
      <td><center><strong><?php echo $f['cant_eg'];?></strong></center></td>
  <td><center><strong><?php echo $f['carac'];?></strong></center></td>
  
   <?php $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$registro") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)) {$registro = $row[papell]; }?>
        
        <td><center><strong><?php echo $registro;?></strong></center></td>
        
      <td><center><a href="editar_egresos.php?id=<?php echo $f['id_eg_ter'];?>" title="Editar datos" class="btn btn-warning btn-sm "><span class="fa fa-edit" aria-hidden="true"></span></a></td></center>

      <td><center><a href="el_egresos.php?id=<?php echo $f['id_eg_ter'];?>" title="Eliminar dato" class="btn btn-danger btn-sm "><span class="fa fa-trash" aria-hidden="true"></span></a></td>
    </tr>
       <?php
                }
                 
                ?>
  </tbody>
</table>
 </div>


  <!--INICIO MATUTINO-->
  <div class="tab-pane fade" id="nav-matutino" role="tabpanel" aria-labelledby="nav-home-tab">  
<form action="insertar_reg_ter.php" method="POST">
<br>
    <div class="row">
                    <div class="col-sm-4">
                        <label class="control-label"><strong>Fecha de reporte:</strong> </label>
                        <input type="date" class="form-control" name="fecha_m" required value="<?php echo $fecha_actual = date("Y-m-d"); ?>">
                    </div>
                    <div class="col-sm-3">
                        <label class="control-label"><strong>Hora:</strong></label>
                       <select class="form-control" name="hora_m" required>
                       <center>
                        <option value="">Seleccionar hora</option>
                        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
                       </center>                           
                       </select>
                    </div>
   
  </div>
                    <hr>
<!--
 <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Peso: </label>
                            <input type="text" class="form-control" name="peso_m" step="0.01" placeholder="Kg." id="peso" minlength="0.0"  onkeypress="return SoloNumeros(event);"
                                   class="form-control-sm" style="text-transform:uppercase;" value=""
                                   onkeyup="javascript:this.value=this.value.toUpperCase();" >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Talla: </label>
                            <input type="text" class="form-control" onkeypress="return SoloNumeros(event);" name="talla_m" step="1" placeholder="CM." id="talla"
                                   class="form-control-sm" style="text-transform:uppercase;" value=""
                                   onkeyup="javascript:this.value=this.value.toUpperCase();" >
                        </div>
                    </div>
                </div>-->
                <div class="row">
                    <div class="col-md-3">
                        <legend class="col-form-label col-sm-12 pt-0">Nivel RCP:</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" class="form-control" type="radio" name="medlegal_m" id="medlegal"
                                       value="SI" >
                                <label class="form-check-label" class="form-control" for="medlegalsi1">
                                    Si
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" class="form-control" type="radio" name="medlegal_m" id="medlegal"
                                       value="NO" checked>
                                <label class="form-check-label" class="form-control" for="medlegalno2">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <legend class="col-form-label col-sm-12 pt-0">Apache:</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" class="form-control" type="radio" name="codigomater_m" id="codigomater"
                                       value="SI">
                                <label class="form-check-label" class="form-control" for="codigomatersi1"  name="codigomater_m" value="NO">
                                    Si
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" class="form-control" type="radio" name="codigomater_m" id="codigomater"
                                       value="NO" checked>
                                <label class="form-check-label" class="form-control" for="codigomaterno2" >
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                   
                    
                </div>
            

     <div class="row">
         <div class="col-sm-8"><hr>
            <table class="table table-bordered table-striped" id="mytable">
     <thead class="thead">
    <tr class="table-warning">
      <th colspan="5"><center><h5><strong>ESCALA DE AGITACIÓN SEDACIÓN RASS</strong></h5></center></th>
    </tr>
    <tr class="table-active">
                            <th>Puntos</th>
                            <th>Categorías</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>+4</td>
                                <td>Combativo</td>
                            </tr>
                            <tr>
                                <td>+3</td>
                                <td>Muy agitado</td>
                            </tr>
                            <tr>
                                <td>+2</td>
                                <td>Agitado</td>
                            </tr>
                             <tr>
                                <td>+1</td>
                                <td>Inquieto</td>
                            </tr>
                            <tr>
                                <td>0</td>
                                <td>Alerta y tranquilo</td>
                            </tr>
                            <tr>
                                <td>-1</td>
                                <td>Somnoliento</td>
                            </tr>
                            <tr>
                                <td>-2</td>
                                <td>Sedación ligera</td>
                            </tr>
                           <tr>
                                <td>-3</td>
                                <td>Sedación moderada</td>
                            </tr>
                            
                            <tr>
                                <td>-4</td>
                                <td>Sedación profunda</td>
                            </tr>
                            
                            <tr>
                                <td>-5</td>
                                <td>No estimulable</td>
                            </tr>
                           
                        </table><br>

                    </div>
                    <div class="col-sm-4"><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    <label><button type="button" class="btn btn-success btn-sm" id="playeasr"><i class="fas fa-play"></button></i>
Escala de Agitación - Sedación RASS</label>
                    <input type="number" name="agit_m" class="form-control" id="txteas">
                </div>
                </div>
<script type="text/javascript">
const txteas = document.getElementById('txteas');
const btneass = document.getElementById('playeasr');
btneass.addEventListener('click', () => {
        leerTexto(txteas.value);
});

function leerTexto(txteas){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txteas;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
<hr>
  <div class="thead" style="background-color: #2b2d7f; color: white; font-size: 20px;">
                 <tr><strong><center>VALORACIÓN DE LA PIEL</center></strong>
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

 <a class="btn btn-primary" data-bs-toggle="collapse" href="#nuev" role="button" aria-expanded="false" aria-controls="nuev" id="nuev1" >
  X
  </a>

<div class="collapse collapse-horizontal" id="nuev">
    <input type="text" name="nuevo" class="form-control form-control-sm col-sm-1" id="nuev2">
</div>


<a class="btn btn-primary" data-bs-toggle="collapse" href="#nn" role="button" aria-expanded="false" aria-controls="nn" id="nuev3" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nn">
    <input type="text" name="nuevo1" class="form-control form-control-sm col-sm-1" id="nuev4">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nnn" role="button" aria-expanded="false" aria-controls="nnn" id="nuev5" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nnn">
    <input type="text" name="nuevo2" class="form-control form-control-sm col-sm-1" id="nuev6">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nnnn" role="button" aria-expanded="false" aria-controls="nnnn" id="nuev7" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nnnn">
    <input type="text" name="nuevo3" class="form-control form-control-sm col-sm-1" id="nuev8">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nnnnn" role="button" aria-expanded="false" aria-controls="nnnnn" id="nuev9" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nnnnn">
    <input type="text" name="nuevo4" class="form-control form-control-sm col-sm-1" id="nuev10">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nnnnn1" role="button" aria-expanded="false" aria-controls="nnnnn1" id="nuev11" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nnnnn1">
    <input type="text" name="nuevo5" class="form-control form-control-sm col-sm-1" id="nuev12">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nnnnn12" role="button" aria-expanded="false" aria-controls="nnnnn12" id="nuev13" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nnnnn12">
    <input type="text" name="nuevo6" class="form-control form-control-sm col-sm-1" id="nuev14">
</div>

<a class="btn btn-primary" data-bs-toggle="collapse" href="#nnnnn14" role="button" aria-expanded="false" aria-controls="nnnnn14" id="nuev15" >
  X
  </a>
<div class="collapse collapse-horizontal" id="nnnnn14">
    <input type="text" name="nuevo7" class="form-control form-control-sm col-sm-1" id="nuev16">
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

<div class="row">
    <table class="table table-bordered table-striped" id="mytable">
     <thead class="thead">
    <tr class="table-warning">
      <th colspan="5"><center><h5><strong>ESCALAS DE ÚLCERAS POR PRESIÓN NORTON</strong></h5></center></th>
    </tr>
    <tr class="table-active">
      <th scope="col"><center>Escala</center></th>
      <th scope="col"><center>Parámetro</center></th>
      <th scope="col"><center>Calificación</center></th>
      <th scope="col"><center>Valor</center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row" class="col-sm-3"><center>Estado físico general</center></th>
      <td>Bueno: relleno capilar rápido<br>Mediano: relleno capilar lento <br> Regular: Ligero edema<br>Muy malo: edema generalizado</td>
      <td><center>4<br>3<br>2<br>1</center></td>

      <td class="col-sm-1"><div class="losInput"><input type="text" name="estfis_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row"><center><br><br><br>Estado mental</center></th>
      <td>Alerta<br>Apático<br>Confuso<br>Estuporoso y comatoso</td>
      <td><center>4<br>3<br>2<br>1</center></td>
     <td><br><br><br><div class="losInput"><input type="text" name="estmen_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row"><center><br>Actividad</center></th>
      <td>Ambulante<br>Camina con ayuda<br>Sentado<br>Encamado</td>
      <td><center>4<br>3<br>2<br>1</center></td>
        <td><br><div class="losInput"><input type="text" name="act_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row"><center>Movilidad</center></th>
      <td>Total<br>Disminuida<br>Muy limitada<br>Inmóvil</td>
      <td><center>4<br>3<br>2<br>1</center></td>
        <td><div class="losInput"><input type="text" name="mov_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
    <tr>
      <th scope="row"><center><br>Incontinencia</center></th>
      <td>Ninguna<br>Ocasional<br>Urinaria o fecal<br>Urinaria y fecal</td>
      <td><center>4<br>3<br>2<br>1</center></td>
        <td><br><div class="losInput"><input type="text" name="inc_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 49 && event.charCode <= 52'></div></td>
    </tr>
     <tr>
      <th colspan="2"></th>
      <th colspan="1"><center><h5><strong><button type="button" class="btn btn-success btn-sm" id="playtotnorton"><i class="fas fa-play"></button></i>
 Total:</strong></h5></center></th>
      <th colspan="1"><center> <div class="inputTotal"><input type="text" class="form-control" id="txtnortontot" disabled></div></center></th>
    </tr>
<script type="text/javascript">
const txtnortontot = document.getElementById('txtnortontot');
const btn1tno = document.getElementById('playtotnorton');
btn1tno.addEventListener('click', () => {
        leerTexto(txtnortontot.value);
});

function leerTexto(txtnortontot){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtnortontot;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>

     <tr>
      <th colspan="2"><center><button type="button" class="btn btn-success btn-sm" id="playneqvm"><i class="fas fa-play"></button></i> Nombre de enfermera (o) que valora</center></th>
      <th colspan="3"><center><input type="text" name="nomenf_m" id="txtenfmaval" class="form-control"></center></th>
    </tr>
<script type="text/javascript">
const txtenfmaval = document.getElementById('txtenfmaval');
const btn1valoraenf = document.getElementById('playneqvm');
btn1valoraenf.addEventListener('click', () => {
        leerTexto(txtenfmaval.value);
});

function leerTexto(txtenfmaval){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtenfmaval;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
 <tr class="table-danger">
      <th colspan="5"><center><font size="2">Interpretación: &nbsp; &nbsp; &nbsp; 5-11 puntos: muy alto riesgo <strong> &nbsp; &nbsp; &nbsp; 12-14 puntos: riesgo evidente </strong>&nbsp; &nbsp; &nbsp;más de 14 puntos: riesgo mínimo </font></center></th>
    </tr>
  </tbody>
</table>
</div>

<hr>
<br>
<div class="row">
    <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
    <tr class="table-warning">
      <th colspan="5"><center><h5><strong>VALORACIÓN DE RIESGO DE CAIDAS ESCALA DE DOWNTON</strong></h5></center></th>
    </tr>
    <tr class="table-active">
      <th scope="col"><center>Variable</center></th>
      <th scope="col"><center>Observación</center></th>
      <th scope="col"><center>Calificación</center></th>
      <th scope="col"><center>Valor</center></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row" class="col-sm-3"><center>Caidas previas</center></th>
      <td>No<br>Si</td>
      <td><center>0<br>1</center></td>

      <td class="col-sm-1"><div class="losInput2"><input type="text" name="caidas_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 48 && event.charCode <= 49' required></div></td>
    </tr>
    <tr>
      <th scope="row"><center><br><br><br>Medicamentos</center></th>
      <td>Ninguno<br>Tranquilizantes-sedante<br>Diuréticos<br>Hipotensores(no diuréticos)<br>Antiparksonianos<br>Antidepresivos<br>Otros medicamentos</td>
      <td><center>0<br>1<br>1<br>1<br>1<br>1<br>1</center></td>
     <td><br><br><br><div class="losInput2"><input type="text" name="medi_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 48 && event.charCode <= 54' required></div></td>
    </tr>
    <tr>
      <th scope="row"><center><br>Déficits sensoriales</center></th>
      <td>Ninguno<br>Alteraciones visuales<br>Alteraciones auditivas<br>Extremidades (Ictus..)</td>
      <td><center>0<br>1<br>1<br>1</center></td>
        <td><br><div class="losInput2"><input type="text" name="defic_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 48 && event.charCode <= 51' required></div></td>
    </tr>
    <tr>
      <th scope="row"><center>Estado mental</center></th>
      <td>Orientado<br>Confuso</td>
      <td><center>0<br>1</center></td>
        <td><div class="losInput2"><input type="text" name="estement_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 48 && event.charCode <= 49' required></div></td>
    </tr>
    <tr>
      <th scope="row"><center><br>Deambulación</center></th>
      <td>Normal<br>Segura con ayuda<br>Insegura con ayuda / sin ayuda<br>Imposible</td>
      <td><center>0<br>1<br>1<br>1</center></td>
        <td><br><div class="losInput2"><input type="text" name="deamb_m" class="form-control" maxlength="1"onkeypress='return event.charCode >= 48 && event.charCode <= 51' required></div></td>
    </tr>
     <tr>
      <th colspan="2"></th>
      <th colspan="1"><center><h5><strong><button type="button" class="btn btn-success btn-sm" id="playtotdow"><i class="fas fa-play"></button></i> 
 Total:</strong></h5></center></th>
      <th colspan="1"><center> <div class="inputTotal2"><input type="text" id="txtdowt" name="total_m" class="form-control" disabled></div></center></th>
    </tr>

<script type="text/javascript">
const txtdowt = document.getElementById('txtdowt');
const btn1totdow = document.getElementById('playtotdow');
btn1totdow.addEventListener('click', () => {
        leerTexto(txtdowt.value);
});

function leerTexto(txtdowt){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtdowt;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>

     <tr>
      <th colspan="2"><center><button type="button" class="btn btn-success btn-sm" id="playenfdom"><i class="fas fa-play"></button></i> Nombre de enfermera (o) que valora</center></th>
      <th colspan="3"><center><input type="text" name="nom_enf_m" id="txtvalenfmadow" class="form-control"></center></th>
    </tr>
<script type="text/javascript">
const txtvalenfmadow = document.getElementById('txtvalenfmadow');
const btn1valoradowenf = document.getElementById('playenfdom');
btn1valoradowenf.addEventListener('click', () => {
        leerTexto(txtvalenfmadow.value);
});

function leerTexto(txtvalenfmadow){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtvalenfmadow;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
     <tr>
    <th colspan="2"><center><button type="button" class="btn btn-success btn-sm" id="playirppmat"><i class="fas fa-play"></button></i> Intervenciones / recomendaciones para prevención de riesgo de caída</center></th>
    <th colspan="3"><center><input type="text" name="interv_m" id="txtintmatat" class="form-control"></center></th>
    </tr>
<script type="text/javascript">
const txtintmatat = document.getElementById('txtintmatat');
const btn1intprevma = document.getElementById('playirppmat');
btn1intprevma.addEventListener('click', () => {
        leerTexto(txtintmatat.value);
});

function leerTexto(txtintmatat){
    const speech = new SpeechSynthesisUtterance();
    speech.text= txtintmatat;
    speech.volume=1;
    speech.rate=1;
    speech.pitch=0;
    window.speechSynthesis.speak(speech);
}
</script>
 <tr class="table-danger">
      <th colspan="5"><center><font size="2">Interpretación: Todos los pacientes con <strong>3 o más </strong>puntos en esta calificación se consideran de <strong>Alto riesgo para caída</strong></font></center></th>
    </tr>
  </tbody>
</table>
</div>
<br><br>

  <div class="form-group col-12">
<center><button type="submit" class="btn btn-primary">Firmar</button>
<button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button></center>
</div>
</form>
  </div>
<!--TERMINO MATUTINO-->




<!--INICIO DIURNO (SIGNOS VITALES)-->
  <div class="tab-pane fade" id="nav-diurno" role="tabpanel" aria-labelledby="nav-home-tab">  

<form action="" method="POST">
 <div class="container-fluid">
<div class="container">
  
      <div class="row">
        <table class="table table-bordered table-striped" id="mytable">
       <thead class="thead">
         <tr class="table-primary">
            <th colspan="9"><center><h5><strong>REGISTRAR SIGNOS VITALES</strong></h5></center></th>
         </tr>
    <tr class="table-active">
      <!--<th scope="col" class="col-sm-1"><center>Tipo</center></th>-->
      <th scope="col" class="col-sm-1"><center>Hora</center></th>
      <th scope="col" class="col-sm-2"><center>Presón arterial</center></th>
      <th scope="col" class="col-sm-1"><center>Frecuencia cardiaca</center></th>
      <th scope="col" class="col-sm-1"><center>Frecuencia respiratoria</center></th>
     <th scope="col" class="col-sm-1"><center>Temperatura</center></th>
     <th scope="col" class="col-sm-1"><center>Saturación oxígeno</center></th>
       <th scope="col" class="col-sm-1"><center><img src="../../imagenes/caras.png" width="250"> Nivel de dolor</center></th>
        <!--<th scope="col" class="col-sm-1"><center>Nivel de dolor</center></th>-->
     
    </tr>
  </thead>
  <tbody>
    <tr>


      <td>
        <select class="form-control" name="hora_med" style="width : 100%; heigth : 100%" required="">
        <option value="">Seleccionar hora</option>
        <option value="8">8:00 A.M.</option>
         <option value="9">9:00 A.M.</option>
  <option value="10">10:00 A.M.</option>
  <option value="11">11:00 A.M.</option>
  <option value="12">12:00 A.M.</option>
  <option value="13">13:00 P.M.</option>
  <option value="14">14:00 P.M.</option>
  <option value="15">15:00 P.M.</option>
  <option value="16">16:00 P.M.</option>
  <option value="17">17:00 P.M.</option>
  <option value="18">18:00 P.M.</option>
  <option value="19">19:00 P.M.</option>
  <option value="20">20:00 P.M.</option>
  <option value="21">21:00 P.M.</option>
  <option value="22">22:00 P.M.</option>
  <option value="23">23:00 P.M.</option>
  <option value="24">24:00 A.M.</option>
  <option value="1">1:00 A.M.</option>
  <option value="2">2:00 A.M.</option>
  <option value="3">3:00 A.M.</option>
  <option value="4">4:00 A.M.</option>
  <option value="5">5:00 A.M.</option>
  <option value="6">6:00 A.M.</option>
  <option value="7">7:00 A.M.</option>
  

        </select>
      </td>
      <td>
     <div class="row">
  <div class="col losInputTAM"><input type="number" class="form-control" id="sist" name="sist_mat" required=""></div> /
  <div class="col losInputTAM"><input type="number" class="form-control" id="diast" name="diast_mat" required=""></div>
 
</div></td>
      <td><input type="number" class="form-control" name="freccard_mat" required="">
    </div></td>
      <td><input type="number" class="form-control" name="frecresp_mat" required="">
    </div></td>
<td><input type="cm-number" class="form-control" name="temper_mat" required="">
    </div></td>
<td><input type="number"  class="form-control col-sm-12" name="satoxi_mat" required="">
    </div></td>
 <td>
        <select class="form-control col-sm-12" name="niv_dolor">
            <option value="">Seleccionar nivel de dolor</option>
             <option value="10">10</option>
             <option value="9">9</option>
             <option value="8">8</option>
             <option value="7">7</option>
             <option value="6">6</option>
             <option value="5">5</option>
             <option value="4">4</option>
             <option value="3">3</option>
             <option value="2">2</option>
             <option value="1" >1</option>
             <option value="0">0</option>
             
        </select>
   
        
    </div></td>
    </div></td>
     
    </tr>
  </tbody>
</table>
     </div>
     <center>
     <input type="submit" name="btnagregar" class="btn btn-block btn-success col-3" value="Agregar">
    </center>
    </form>
</div>
    <?php

          if (isset($_POST['btnagregar'])) {
            include "../../conexionbd.php";
            $usuario = $_SESSION['login'];
            $id_usua= $usuario['id_usua'];
            $id_atencion = $_SESSION['pac'];
$diast_mat=0;
$sist_mat=0;
$tam_m=0;
//$tipo =  mysqli_real_escape_string($conexion, (strip_tags($_POST["tipo"], ENT_QUOTES)));
$hora_med =  mysqli_real_escape_string($conexion, (strip_tags($_POST["hora_med"], ENT_QUOTES)));
$sist_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["sist_mat"], ENT_QUOTES)));
$diast_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["diast_mat"], ENT_QUOTES)));
$freccard_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["freccard_mat"], ENT_QUOTES)));
$frecresp_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["frecresp_mat"], ENT_QUOTES)));
$temper_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["temper_mat"], ENT_QUOTES)));
$satoxi_mat =  mysqli_real_escape_string($conexion, (strip_tags($_POST["satoxi_mat"], ENT_QUOTES)));
$niv_dolor =  mysqli_real_escape_string($conexion, (strip_tags($_POST["niv_dolor"], ENT_QUOTES)));

if($hora_med=='8' ||$hora_med=='9' || $hora_med=='10' || $hora_med=='11'|| $hora_med=='12'|| $hora_med=='13' || $hora_med=='14'){
$turno="MATUTINO";
} else if ($hora_med=='15' || $hora_med=='16' || $hora_med=='17'|| $hora_med=='18'|| $hora_med=='19' || $hora_med=='20' || $hora_med=='21') {
  $turno="VESPERTINO";
}else if ($hora_med=='22' || $hora_med=='23' || $hora_med=='24'|| $hora_med=='1'|| $hora_med=='2' || $hora_med=='3' || $hora_med=='4' || $hora_med=='5' || $hora_med=='6' || $hora_med=='7') {
    $turno="NOCTURNO";
}

$fecha_actual = date("Y-m-d H:i:s");

if ($hora_med == '24' || $hora_med == '1' || $hora_med == '2' || $hora_med == '3' || $hora_med == '4' || $hora_med == '5' || $hora_med == '6' || $hora_med == '7') {
   // Restamos un día a la fecha actual
   //$yesterday = date('Y-m-d', strtotime('-1 day')) ; 
   $yesterday = date("Y-m-d H:i"); 
} else { 
   $yesterday = date("Y-m-d"); 
}

$tam_m=($diast_mat+$sist_mat)/2;

$ingresarsignos = mysqli_query($conexion, 'INSERT INTO signos_vitales (
  id_atencion,id_usua,fecha,p_sistol,p_diastol,fcard,fresp,temper,satoxi,niv_dolor,tam,hora,tipo,fecha_registro) values (' . $id_atencion . ' , ' . $id_usua . ' ,"' . $yesterday. '", "' . $sist_mat . '" , "' . $diast_mat . '" , "' . $freccard_mat . '" , "' . $frecresp_mat . '" , "' . $temper_mat . '", "' . $satoxi_mat . '","' . $niv_dolor . '","' . $tam_m . '",' . $hora_med . ',"TERAPIA INTENSIVA","' . $fecha_actual. '") ') or die('<p>Error al registrar</p><br>' . mysqli_error($conexion));


           echo '<script type="text/javascript">window.location.href = "reg_terapeutico.php";</script>';
          }
          ?>    
          <div class="col col-12">
          
            <div class="form-group">
                <input type="text" class="form-control pull-right" style="width:20%" id="search" placeholder="BUSCAR...">
            </div>
               <?php


?>
            <div class="table-responsive">

            <table class="table table-bordered table-striped" id="mytable">
                <thead class="thead bg-navy">
              
                    <th scope="col">Pdf</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Fecha</th>
                    
                    <th scope="col">Tipo</th>
                    
                </tr>
                </thead>
                <tbody>

<?php
include "../../conexionbd.php";
$usuario = $_SESSION['login'];
$usua=$usuario['id_usua'];
$id_atencion=$_SESSION['pac'];
$resultado = $conexion->query("SELECT fecha,id_atencion,id_usua,hora,id_sig,p_sistol,p_diastol,fcard,fresp,temper,satoxi,niv_dolor, tipo from signos_vitales s WHERE s.id_atencion=$id_atencion AND s.tipo='TERAPIA INTENSIVA' group by fecha  ORDER BY id_sig DESC") or die($conexion->error);
while($f = mysqli_fetch_array($resultado)){
       $id_usua=$f['id_usua'];
      $resultado_usua = $conexion->query("SELECT * FROM reg_usuarios WHERE id_usua=$id_usua") or die($conexion->error);
        while($row = mysqli_fetch_array($resultado_usua)){

$sql_pac = "SELECT p.sapell, p.papell, p.nom_pac, p.dir, p.id_edo, p.id_mun, p.Id_exp, p.tel, p.fecnac,p.tip_san, di.fecha, di.area, di.alta_med, p.sexo, di.alergias,p.folio FROM paciente p, dat_ingreso di WHERE p.Id_exp=di.Id_exp and di.id_atencion =$id_atencion";

      $result_pac = $conexion->query($sql_pac);

      while ($row_pac = $result_pac->fetch_assoc()) {
        
      


?>          
                    <tr>
<td class="fondo"><a href="../signos_vitales/signos_vitales_ter.php?id_ord=<?php echo $f['id_sig'];?>&id_atencion=<?php echo $f['id_atencion'];?>&id_usua=<?php echo $usua?>&fecha=<?php echo $f['fecha']?>&idexp=<?php echo $row_pac['Id_exp']?>"><button type="button" class="btn btn-danger"> <i class="fa fa-file-pdf-o" style="font-size:28px"  aria-hidden="true"></i> </button></td>
                      
                   <td class="fondo"><strong><?php echo $f['hora'];?></strong></td>    
<td class="fondo"><strong><?php $date=date_create($f['fecha']); echo date_format($date,"d-m-Y");?></strong></td>

<td class="fondo"><strong><?php echo $f['tipo'];?></strong></td>
<!--<td class="fondo"><strong><?php echo $row['nombre'].' '.$row['papell'].' '.$row['sapell']?></strong></td>-->
                    </tr>
                    <?php
}
    }
        }
                ?>
                
                </tbody>
              
            </table>
            </div>
 <!--TERMINO SIGNOS VITALES-->  
 
                

            </form>

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



<script src="../../template/plugins/jQuery/jQuery-2.1.3.min.js"></script>
<!-- FastClick -->
<script src='../../template/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="../../template/dist/js/app.min.js" type="text/javascript"></script>

<script type="text/javascript">
        function mostrar3(value)
        {
            if(value=="AGREGAR3" || value==true)
            {
                // habilitamos
                document.getElementById('contenido3').style.display = 'block';
            }else if(value=="DISMINUIR3" || value==false){
                // deshabilitamos
                document.getElementById('contenido3').style.display = 'none';
            }
        }

        $('.losInput7 input').on('change', function(){
  var total = 0;
  $('.losInput7 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal7 input').val(total.toFixed());
});


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


                $('.losInput9 input').on('change', function(){
  var total = 0;
  $('.losInput9 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal9 input').val(total.toFixed());
});


                        $('.losInput10 input').on('change', function(){
  var total = 0;
  $('.losInput10 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal10 input').val(total.toFixed());
});

   $('.losInput11 input').on('change', function(){
  var total = 0;
  $('.losInput11 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal11 input').val(total.toFixed());
});

        $('.losInput12 input').on('change', function(){
  var total = 0;
  $('.losInput12 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal12 input').val(total.toFixed());
});

        $('.losInput input').on('change', function(){
  var total = 0;
  $('.losInput input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal input').val(total.toFixed());
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

         $('.losInput3 input').on('change', function(){
  var total = 0;
  $('.losInput3 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal3 input').val(total.toFixed());
});

          $('.losInput4 input').on('change', function(){
  var total = 0;
  $('.losInput4 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal4 input').val(total.toFixed());
});

 $('.losInput5 input').on('change', function(){
  var total = 0;
  $('.losInput5 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal5 input').val(total.toFixed());
});

  $('.losInput6 input').on('change', function(){
  var total = 0;
  $('.losInput6 input').each(function() {
    if($( this ).val() != "")
    {
      total = total + parseFloat($( this ).val());
    }
  });
  $('.inputTotal6 input').val(total.toFixed());
});
    </script>

      <script type="text/javascript">
  

$('.losInputTAM input').on('change', function(){
  var total = 0;
  var sist, diast, tam;
  var string = "mmHG";
   sist=document.getElementById("sist").value;
   diast=document.getElementById("diast").value;
   $('.losInputTAM input').each(function() {
    if($( this ).val() != "")
    {
   //parseInt(total= (sist+diast)); 
   total = total + parseInt($( this ).val()) /2;
    }
  });
  $('.inputTotalTAM input').val(total.toFixed(0)+ " " +string );

});

$('.losInputTAM2 input').on('change', function(){
  var total = 0;
  var sist, diast, tam;
  var string = "mmHG";
   sist=document.getElementById("sist").value;
   diast=document.getElementById("diast").value;
   $('.losInputTAM2 input').each(function() {
    if($( this ).val() != "")
    {
   //parseInt(total= (sist+diast)); 
   total = total + parseInt($( this ).val()) /2;
    }
  });
  $('.inputTotalTAM2 input').val(total.toFixed(0)+ " " +string );

});


$('.losInputTAM3 input').on('change', function(){
  var total = 0;
  var sist, diast, tam;
  var string = "mmHG";
   sist=document.getElementById("sist").value;
   diast=document.getElementById("diast").value;
   $('.losInputTAM3 input').each(function() {
    if($( this ).val() != "")
    {
   //parseInt(total= (sist+diast)); 
   total = total + parseInt($( this ).val()) /2;
    }
  });
  $('.inputTotalTAM3 input').val(total.toFixed(0)+ " " +string );

});

</script>

</body>

</html>