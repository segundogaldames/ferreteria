<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['usuario'])) {
        //print_r($_GET);
        $id = (int) $_GET['usuario'];

        #verificar que hay un empleado registrado con el id recibido
        $res = $mbd->prepare("SELECT u.id, u.activo, u.empleado_id, e.nombre FROM empleados e INNER JOIN usuarios u ON u.empleado_id = e.id WHERE u.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $usuario = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $activo = filter_var($_POST['activo'], FILTER_VALIDATE_INT);

            if (!$activo) {
                $msg = 'Selecciona un estado';
            }else {
                $res = $mbd->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
                $res->bindParam(1, $activo);
                $res->bindParam(2, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El estado se ha modificado correctamente';
                    header('Location: ' . SHOW_EMPLEADO . $usuario['empleado_id']);
                }
            }
        }
    }


?>
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


            <h4 class="text-success">Editar Usuario</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($usuario): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="clave" class="form-label">Empleado: <?php echo $usuario['nombre']; ?></label>
                    </div>

                    <div class="mb-3">
                        <label for="activo" class="form-label">Estado</label>
                        <select name="activo" class="form-control" id="">
                            <option value="<?php $usuario['activo'] ?>">
                                <?php
                                    if($usuario['activo'] == 1){
                                        echo 'Activo';
                                    }else {
                                        echo 'Inactivo';
                                    }
                                ?>
                            </option>

                            <option value="">Seleccione...</option>
                            <option value="1">Activar</option>
                            <option value="2">Desactivar</option>
                        </select>
                        <div id="clave" class="form-text text-danger">Selecciona un estado..</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-success">Editar</button>
                    <a href="<?php echo SHOW_EMPLEADO . $usuario['empleado_id']; ?>" class="btn btn-outline-secondary">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">La cuenta no se ha podido editar</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>