<?php
#usar libreria PDO para conectar la base de datos
$usuario = 'root';
$clave = 1234;

#para los usuarios de windows la clave es vacia
// $clave = '';


try {
    $mbd = new PDO('mysql:host=localhost;dbname=ferreteria', $usuario, $clave);
    /* foreach($mbd->query('SELECT * from FOO') as $fila) {
        print_r($fila);
    } */
    //$mbd = null;
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}