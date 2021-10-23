<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['usuario'])) {
        //print_r($_GET);
        $id_usuario = (int) $_GET['usuario'];

        #verificar que hay un empleado registrado con el id recibido
        $res = $mbd->prepare("SELECT u.id, e.nombre FROM empleados e INNER JOIN usuarios u ON u.empleado_id = e.id WHERE u.id = ?");
        $res->bindParam(1, $id_usuario);
        $res->execute();
        $usuario = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $clave = trim(strip_tags($_POST['clave']));
            $reclave = trim(strip_tags($_POST['reclave']));

            if (strlen($clave) < 8) {
                $msg = 'Ingresa una password de al menos 8 caracteres';
            }elseif ($reclave != $clave) {
                $msg = 'Los passwords ingresados no coinciden';
            }else {
                $clave = sha1($clave);

                $res = $mbd->prepare("UPDATE usuarios SET clave = ? WHERE id = ?");
                $res->bindParam(1, $clave);
                $res->bindParam(2, $id_usuario);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'Tu password se ha modificado correctamente';
                    header('Location: ' . BASE_URL);
                }else {
                    $msg = 'El password no se ha modificado';
                }

            }
        }
    }


?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador' || ($_SESSION['usuario_id']) == $id_usuario): ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</head>
<body>
    <!-- este es un comentario -->
    <header>
        <!-- llamada al archivo menu.php -->
        <?php include('../partials/menu.php'); ?>
    </header>
    <div class="container-fluid">
        <div class="col-md-6 offset-md-3">


            <h4 class="text-success">Modificar Password</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($usuario): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="clave" class="form-label">Funcionario: <?php echo $usuario['nombre']; ?></label>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Password</label>
                        <input type="password" name="clave" class="form-control" id="clave" aria-describedby="clave">
                        <div id="clave" class="form-text text-danger">Ingresa el password...</div>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Confirmar Clave</label>
                        <input type="password" name="reclave" class="form-control" id="clave" aria-describedby="clave">
                        <div id="clave" class="form-text text-danger">Confirma el password...</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-success">Editar</button>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">La password no se ha podido modificar</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>
<?php else: ?>
    <?php
        $_SESSION['danger'] = 'Operación no permitida';
        header('Location: ' . BASE_URL);
    ?>
<?php endif; ?>