<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $res = $mbd->prepare("SELECT c.id, c.nombre, c.region_id, r.nombre as region FROM comunas c INNER JOIN regiones r ON c.region_id = r.id WHERE c.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comuna = $res->fetch();


    }

    #truco para listar de manera previa los datos de una consulta
    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunas</title>
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
            <?php include('../partials/mensajes.php'); ?>

            <h4 class="text-success">Comuna</h4>
            <?php if($comuna): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Comuna:</th>
                        <td><?php echo $comuna['nombre']; ?></td>
                    </tr>
                    <tr>
                        <th>Región:</th>
                        <td><?php echo $comuna['region']; ?></td>
                    </tr>
                </table>
                <p>
                    <a href="<?php echo COMUNAS . 'edit.php?id=' . $id; ?>" class="btn btn-outline-success">Editar</a>
                    <a href="<?php echo REGIONES . 'show.php?id=' . $comuna['region_id'] ; ?>" class="btn btn-outline-secondary">Volver</a>
                </p>
            <?php else: ?>
                <p class="text-info">No hay datos</p>
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