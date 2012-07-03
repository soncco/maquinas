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
    <li><a href="recibos.php" class="<?php if($self=='/recibos.php') print "active"; ?>">Recibos</a></li>
    <li><a href="informes.php" class="<?php if($self=='/informes.php') print "active"; ?>">Informes</a></li>
    <?php if(isset($loginuser)&&($loginuser['tipo'] == 1)) : ?>
    <li><a href="rubros.php" class="<?php if($self=='/rubros.php') print "active"; ?>">Rubros</a></li>
    <li><a href="pagadores.php" class="<?php if($self=='/pagadores.php') print "active"; ?>">Pagadores</a></li>
    <li><a href="maquinas.php" class="<?php if($self=='/maquinas.php') print "active"; ?>">M&aacute;quinas</a></li>
    <li><a href="opciones.php" class="<?php if($self=='/opciones.php') print "active"; ?>">Opciones</a></li>
    <li><a href="usuarios.php" class="<?php if($self=='/usuarios.php') print "active"; ?>">Usuarios</a></li>
    <?php endif; ?>
  </ul>
</div>