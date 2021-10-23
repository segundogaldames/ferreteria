<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $nombre = trim(strip_tags($_POST['nombre']));

        if (!$nombre) {
            $msg = 'Ingrese el nombre del rol';
        }else {
            #verificar que el rol recibido no este registrado
            $res = $mbd->prepare("SELECT id FROM roles WHERE nombre = ?");
            $res->bindParam(1, $nombre);
            $res->execute();
            $rol = $res->fetch();

            if($rol){
                $msg = 'El rol ingresado ya existe... intente con otro';
            }else {
                $res = $mbd->prepare("INSERT INTO roles(nombre) VALUES(?)");
                $res->bindParam(1, $nombre);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El rol se ha registrado correctamente';
                    //print_r($_SESSION['success']);exit;
                    header('Location: ' . ROLES);
                }
            }
        }
    }
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
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


            <h4 class="text-success">Nuevo Rol</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <input type="text" name="nombre" class="form-control" id="rol" aria-describedby="rol">
                    <div id="rol" class="form-text">Ingresa el rol...</div>
                </div>
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <a href="<?php echo ROLES; ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
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