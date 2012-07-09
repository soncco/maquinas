<?php
/**
 * Gestión de administradores.
 */ 
	require_once('home.php');
	require_once('redirect.php');

	$id = ! empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
		if ( validate_required(array(
			'Nombres' => $_POST['nombres'], 
			'Usuario' => $_POST['usuario']))) {

			$error = false;
			if($_POST['password']!=$_POST['pwd2']) {
				$error = true;
				$msg = "Las contrase&ntilde;as no coinciden.";
			}else{
				$pwd = trim($_POST['password']);
				if(empty($pwd)&&(!$id)) {
					$error = true;
					$msg = "La contrase&ntilde;a es un campo requerido.";
				}
			}

			if(!$error) :
				$user_values = array(
					'nombres' => $_POST['nombres'],
					'usuario' => $_POST['usuario'],
				);

				if($id&&(!empty($_POST['password']))) {
					$user_values['password'] = md5(trim($_POST['password']));
				}

				if($id===0) {
					$user_values['password'] = md5(trim($_POST['password']));
				}

				$user_values = array_map('strip_tags', $user_values);
				$id = save_user($id, $user_values);
				if($id) $id = 0;
				$msg = "Los datos se guardaron satisfactoriamente.";
			endif;
		} else 
			$msg = "Ya existe el usuario '$user_values[usuario]'.";
	}

	$users = get_items($bcdb->admin);
	$user = array();
	if($id) {
		$user = get_item($id, $bcdb->admin);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/text.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/960.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/layout.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/thickbox.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<script type="text/javascript" src="/scripts/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/scripts/jquery.validate.js"></script>
<script type="text/javascript" src="/scripts/jquery.collapsible.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#frmusers").validate();		
		/**
		 * Varios
		 */	
		$('#nombres').focus();
	});
</script>
<title>Usuarios | Alquiler de máquinas</title>
</head>

<body>
<div class="container_16">
  <div id="header">
    <h1 id="logo"> <a href="/"><span>Alquiler de máquinas</span></a> </h1>
    <?php include "menutop.php"; ?>
    <?php if(isset($_SESSION['loginuser'])) : ?>
    <div id="logout">Sesión: <?php print $_SESSION['loginuser']['nombres']; ?> <a href="logout.php">Salir</a></div>
    <?php endif; ?>
  </div>
  <div class="clear"></div>
  <div id="icon" class="grid_3">
    <p class="align-center"><img src="images/usuarios.png" alt="Usuarios" /></p>
  </div>
  <div id="content" class="grid_13">
    <h1>Usuarios</h1>
    <?php if (isset($msg)): ?>
    <p class="<?php echo ($error) ? "error" : "msg" ?>"><?php print $msg; ?></p>
    <?php endif; ?>
    <form name="frmusers" id="frmusers" method="post" action="usuarios.php">
      <fieldset class="collapsible">
        <legend>Datos del usuario</legend>
        <p>
          <label for="nombres">Nombres:</label>
          <input type="text" name="nombres" id="nombres" maxlength="255" size="60" value="<?php print ($user) ? $user['nombres'] : ""; ?>" />
        </p>
        <p>
          <label for="usuario" title="Nombre de Usuario">Usuario:</label>
          <input type="text" name="usuario" id="usuario" maxlength="100" size="40" value="<?php print ($user) ? $user['usuario'] : ""; ?>" class="required" title="Ingresa el nombre de usuario" <?php print (isset($user['usuario'])) ? 'readonly="readonly"' : ""; ?> />
        </p>
        <p>
          <label for="password">Contraseña:</label>
          <input type="password" name="password" id="pwd" maxlength="100" title="Ingresa la contraseña" />
        </p>
        <p>
          <label for="pwd2">Otra vez:</label>
          <input type="password" name="pwd2" id="pwd2" maxlength="100" title="Ingresa la contraseña" />
          <br />
          <span class="nota">Si no va a cambiar la contraseña, deje los campos en blanco.</span> </p>
        <p class="align-center">
          <button type="submit" name="submit" id="submit">Guardar</button>
          <input type="hidden" name="id" id="id" value="<?php print $id; ?>" />
        </p>
      </fieldset>
    </form>
    <fieldset class="collapsibleClosed">
      <legend>Usuario existentes</legend>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombres</th>
            <th>Usuario</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($users): ?>
          <?php $alt = "even"; ?>
          <?php foreach($users as $k=> $usuario): ?>
          <tr class="<?php print $alt ?>">
            <th><?php print $usuario['id']; ?>
              </td>
            <th><?php print $usuario['nombres']; ?>
              </td>
            <td><?php print $usuario['usuario']; ?></td>
            <td><a href="usuarios.php?id=<?php print $usuario['id']; ?>">Editar</a></td>
            <?php $alt = ($alt == "even") ? "odd" : "even"; ?>
          </tr>
          <?php endforeach; ?>
          <?php else: ?>
          <tr class="<?php print $alt; ?>">
            <td colspan="4">No existen datos
              </th>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php include "pager.php"; ?>
    </fieldset>
  </div>
  <div class="clear"></div>
  <?php include "footer.php"; ?>
</div>
</body>
</html>