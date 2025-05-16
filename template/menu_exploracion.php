<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>
</head>
<body>
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
      <a href="../gestion_medica/exploraciones/formulario_oftalmo.php">
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

</body>
</html>