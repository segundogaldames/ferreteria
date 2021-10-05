<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $res = $mbd->prepare("SELECT id, nombre FROM regiones WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $region = $res->fetch();
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
            <?php else: ?>
                <p class="text-info">No hay datos</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>