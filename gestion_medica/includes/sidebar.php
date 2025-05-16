<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Sidebar Funcional Fondo Blanco</title>
<!-- Font Awesome Icons -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  /* Reset and base */
  body {
    margin: 0;
    font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
    background-color: #f9f9f9;
  }
  /* Sidebar styles */
  .main-sidebar {
    width: 260px;
    background-color: #fff;
    height: 100vh;
    position: fixed;
    overflow-y: auto;
    box-shadow: 2px 0 8px rgba(0,0,0,0.1);
    border-right: 1px solid #ddd;
  }
  .sidebar {
    padding-top: 15px;
  }
  .user-panel {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .user-panel .image img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 1px solid #ccc;
  }
  .user-panel .info {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
  }
  .user-panel .info a {
    font-size: 12px;
    color: #28a745;
    text-decoration: none;
  }
  .user-panel .info a:hover {
    text-decoration: underline;
  }

  /* Sidebar menu */
  .sidebar-menu, 
  .treeview-menu {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .sidebar-menu > li.header {
    padding: 12px 25px 12px 15px;
    font-size: 14px;
    color: #777;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid #eee;
  }
  .sidebar-menu > li {
    position: relative;
  }
  .sidebar-menu > li > a {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: #444;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease;
    border-left: 4px solid transparent;
  }
  .sidebar-menu > li > a i.fa {
    width: 20px;
    font-size: 16px;
    margin-right: 10px;
    color: #777;
    flex-shrink: 0;
  }
  .sidebar-menu > li > a:hover {
    background-color: #e9f0f9;
    color: #007bff;
    border-left-color: #007bff;
  }
  /* Pull right container (arrow) */
  .pull-right-container {
    margin-left: auto;
  }
  .pull-right-container > .fa {
    font-size: 14px;
    color: #777;
    transition: transform 0.3s ease;
  }
  /* Treeview menu - hidden by default */
  .treeview-menu {
    display: none;
    padding-left: 30px;
    background-color: #f8faff;
    border-left: 1px solid #ddd;
  }
  /* Treeview submenu items */
  .treeview-menu li a {
    font-size: 13px;
    color: #555;
    padding: 8px 15px;
    display: block;
    position: relative;
    transition: background-color 0.3s ease;
  }
  .treeview-menu li a i.fa {
    width: 16px;
    font-size: 14px;
    margin-right: 8px;
    color: #999;
  }
  .treeview-menu li a:hover {
    background-color: #dde9f7;
    color: #007bff;
  }
  /* Open menu styles */
  .menu-open > a {
    background-color: #e9f0f9;
    color: #007bff;
    border-left-color: #007bff;
  }
  .menu-open > a .fa-angle-left {
    transform: rotate(-90deg);
    color: #007bff;
  }
</style>
</head>
<body>
<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="image">
        <img src="../imagenes/<?php echo $usuario['img_perfil']; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="info">
        <p><?php echo $usuario['papell']; ?></p>
        <a href="#"><i class="fa fa-circle"></i> ACTIVO</a>
      </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENÚ PRINCIPAL</li>
      
      <li class="treeview">
        <a href="#">
          <i class="fa fa-stethoscope" aria-hidden="true"></i>
          <span>Exploraciones - Órbita y Vías Lagrimales</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li><a href="../exploraciones/listar_exploraciones.php"><i class="fa fa-eye"></i> Ver exploraciones</a></li>
          <li><a href="../exploraciones/formulario_exploracion.php"><i class="fa fa-plus-circle"></i> Agregar exploración</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-eye"></i>
          <span>Segmento Anterior</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li><a href="../exploraciones/listar_segmento.php"><i class="fa fa-list"></i> Ver exploraciones</a></li>
          <li><a href="../exploraciones/formulario_segmento.php"><i class="fa fa-plus-circle"></i> Agregar exploración</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-eye"></i>
          <span>Segmento Posterior</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
          <li><a href="../exploraciones/formulario_segmento_posterior.php"><i class="fa fa-plus-circle"></i> Registrar Exploración</a></li>
          <li><a href="../exploraciones/listar_segmento_posterior.php"><i class="fa fa-list"></i> Ver Exploraciones</a></li>
        </ul>
      </li>

    </ul>
  </section>
</aside>

<script>
  $(function() {
    $('.sidebar-menu li.treeview > a').on('click', function(e) {
      e.preventDefault();
      var parentLi = $(this).parent();
      var submenu = parentLi.find('.treeview-menu').first();

      if (submenu.is(':visible')) {
        submenu.slideUp(200);
        parentLi.removeClass('menu-open');
        $(this).find('.fa-angle-left').removeClass('fa-angle-down').addClass('fa-angle-left');
      } else {
        // Close other open menus at the same level
        parentLi.siblings('.treeview').find('.treeview-menu:visible').slideUp(200);
        parentLi.siblings('.treeview').removeClass('menu-open');
        parentLi.siblings('.treeview').find('.fa-angle-left').removeClass('fa-angle-down').addClass('fa-angle-left');
        
        submenu.slideDown(200);
        parentLi.addClass('menu-open');
        $(this).find('.fa-angle-left').removeClass('fa-angle-left').addClass('fa-angle-down');
      }
    });
  });
</script>
</body>
</html>

