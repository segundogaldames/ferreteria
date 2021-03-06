<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    #listado de regiones
    $res = $mbd->query("SELECT id, nombre FROM regiones ORDER BY nombre");
    $regiones = $res->fetchall();

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
    <title>Regiones</title>
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

            <h4 class="text-success">Lista de Regiones | <a href="<?php echo REGIONES . 'add.php'; ?>" class="btn btn-link">Nueva Región</a></h4>

            <?php if(count($regiones)): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                    </tr>
                    <?php foreach($regiones as $region): ?>
                        <tr>
                            <td><?php echo $region['id']; ?></td>
                            <td>
                                <a href="<?php echo REGIONES . 'show.php?id=' .  $region['id']; ?>">
                                    <?php echo $region['nombre']; ?>
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="text-info">No hay regiones registradas</p>
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