<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['rol'])) {
        $rol_id = (int) $_GET['rol'];

        $res = $mbd->prepare("SELECT id, nombre FROM roles WHERE id = ?");
        $res->bindParam(1, $rol_id);
        $res->execute();
        $rol = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $nombre = trim(strip_tags($_POST['nombre']));

            if (!$nombre) {
                $msg = 'Ingrese el nombre del rol';
            }else {
                #actualzamos el nombre del rol
                $res = $mbd->prepare("UPDATE roles SET nombre = ? WHERE id = ?");
                $res->bindParam(1, $nombre);
                $res->bindParam(2, $rol_id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El rol se ha modificado correctamente';
                    header('Location: ' . SHOW_ROL . $rol_id);
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

            <h4 class="text-success">Editar Rol</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($rol): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="region" class="form-label">Rol</label>
                        <input type="text" name="nombre" value="<?php echo $rol['nombre']; ?>" class="form-control" id="region" aria-describedby="region">
                        <div id="region" class="form-text">Ingresa el rol...</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-success">Editar</button>
                    <a href="<?php echo SHOW_ROL . $rol_id; ?>" class="btn btn-outline-secondary">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">No se puede editar este rol</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>