<?php
#declaracion de constantes para determinar las principales rutas de la app
define('BASE_URL','http://localhost:8080/ferreteria/');
define('REGIONES', BASE_URL . 'regiones/');
define('COMUNAS', BASE_URL . 'comunas/');

define('PARAM', false);

#rutas de roles
define('ROLES', BASE_URL . 'roles/');
define('ADD_ROL', ROLES . 'add.php');
define('SHOW_ROL', ROLES . 'show.php?rol=' . PARAM);
define('EDIT_ROL', ROLES . 'edit.php?rol=' . PARAM);

#rutas empleados
define('EMPLEADOS', BASE_URL . 'empleados/');
define('ADD_EMPLEADO', EMPLEADOS . 'add.php');
define('SHOW_EMPLEADO', EMPLEADOS . 'show.php?empleado=' . PARAM);
define('EDIT_EMPLEADO', EMPLEADOS . 'edit.php?empleado=' . PARAM);

#rutas usuarios
define('USUARIO', BASE_URL . 'usuarios/');
define('ADD_USUARIO', USUARIO . 'add.php?empleado=' . PARAM);
//define('SHOW_USUARIO', USUARIO . 'show.php?usuario=' . PARAM);
define('EDIT_USUARIO', USUARIO . 'edit.php?usuario=' . PARAM);
define('EDIT_PASSWORD', USUARIO . 'editPassword.php?usuario=' . PARAM);
define('LOGIN', USUARIO . 'login.php');
define('LOGOUT', USUARIO . 'logout.php');
