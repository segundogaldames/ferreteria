<?php

require('../class/rutas.php');
require('../class/conexion.php');

session_start();

if (isset($_SESSION['autenticado'])) {

    session_destroy();

    header('Location: ' . BASE_URL);
}