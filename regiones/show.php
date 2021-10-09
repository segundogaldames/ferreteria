<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $res = $mbd->prepare("SELECT id, nombre FROM regiones WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $region = $res->fetch();

        #lista de comunas por region
        $res = $mbd->prepare("SELECT id, nombre FROM comunas WHERE region_id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comunas = $res->fetchall();
    }

    #listado de regiones


    #truco para listar de manera previa los datos de una consulta
    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>
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
            <h4 class="text-success">Región</h4>
            <?php if($region): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Id:</th>
                        <td><?php echo $region['id']; ?></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo $region['nombre']; ?></td>
                    </tr>
                </table>
                <p>
                    <a href="<?php echo REGIONES . 'edit.php?id=' . $id; ?>" class="btn btn-outline-success">Editar</a>
                    <a href="" class="btn btn-outline-success">Agregar Comuna</a>
                    <a href="<?php echo REGIONES; ?>" class="btn btn-outline-secondary">Volver</a>
                    <form action="" method="post"></form>
                </p>
            <?php else: ?>
                <p class="text-info">No hay datos</p>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <hr>
                <h5 class="text-secondary">Comunas de la Región <?php echo $region['nombre']; ?> </h5>
                <?php if(count($comunas)): ?>
                    <div class="list-group">
                        <?php foreach($comunas as $comuna): ?>
                            <a href="<?php echo COMUNAS . 'edit.php?id=' . $comuna['id']; ?>" class="list-group-item list-group-item-action">
                                <?php echo $comuna['nombre']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-info">No hay comunas asociadas</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

</body>
</html>