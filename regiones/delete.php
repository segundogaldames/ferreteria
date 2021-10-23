<?php
require('../class/conexion.php');
require('../class/rutas.php');

session_start();

if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'){


    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $id = (int) $_POST['id'];

        #verificar que hay una region con el id enviado
        $res = $mbd->prepare("SELECT id FROM regiones WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $region = $res->fetch();

        if ($region) {
            #verificar que la region existente no tenga comunas asociadas
            $res = $mbd->prepare("SELECT id FROM comunas WHERE region_id = ?");
            $res->bindParam(1, $id);
            $res->execute();
            $comunas = $res->fetchall();

            if (!$comunas) {
                #eliminar la region
                $res = $mbd->prepare("DELETE FROM regiones WHERE id = ?");
                $res->bindParam(1, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'La region se ha eliminado correctamente';
                    header('Location: ' . REGIONES);
                }
            }else{
                $_SESSION['danger'] = 'La región tiene comunas asociadas... no se puede eliminar';
                header('Location: ' . REGIONES . 'show.php?id=' . $id);
            }
        }else {
            $_SESSION['danger'] = 'La región no se puede eliminar';
            header('Location: ' . REGIONES . 'show.php?id=' . $id);
        }
    }
}else{
    $_SESSION['danger'] = 'Operación no permitida';
    header('Location: ' . BASE_URL);
}