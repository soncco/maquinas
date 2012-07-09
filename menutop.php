<?php
	$menutop = array(
		"/recibos.php" => "Recibos",
		"/informes.php" => "Informes",
		"/rubros.php" => "Rubros",
		"/pagadores.php" => "Pagadores",
		"/maquinas.php" => "M&aacute;quinas",
		"/opciones.php" => "Opciones",
		"/usuarios.php" => "Usuarios"
	);
	
?>
<div id="menutop">
  <ul>
    <li><a href="alquiler.php" class="<?php if($self=='/alquiler.php') print "active"; ?>">Alquiler</a></li>
    <!--li><a href="informes.php" class="<?php if($self=='/informes.php') print "active"; ?>">Informes</a></li-->
    <li><a href="combustible.php" class="<?php if($self=='/combustible.php') print "active"; ?>">Combustible</a></li>
    <li><a href="operadores.php" class="<?php if($self=='/operadores.php') print "active"; ?>">Operadores</a></li>
    <li><a href="clientes.php" class="<?php if($self=='/clientes.php') print "active"; ?>">Clientes</a></li>
    <li><a href="maquinas.php" class="<?php if($self=='/maquinas.php') print "active"; ?>">M&aacute;quinas</a></li>
    <li><a href="opciones.php" class="<?php if($self=='/opciones.php') print "active"; ?>">Opciones</a></li>
    <li><a href="usuarios.php" class="<?php if($self=='/usuarios.php') print "active"; ?>">Usuarios</a></li>
  </ul>
</div>