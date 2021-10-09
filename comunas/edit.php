<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        $res = $mbd->prepare("SELECT c.id, c.nombre, c.region_id, r.nombre as region FROM comunas c INNER JOIN regiones r ON c.region_id = r.id WHERE c.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comuna = $res->fetch();

        #listado de regiones
        $res = $mbd->query("SELECT id, nombre FROM regiones ORDER BY nombre");
        $regiones = $res->fetchall();
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
            <h4 class="text-success">Editar Comuna</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($comuna): ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="comuna" class="form-label">Comuna</label>
                        <input type="text" name="nombre" value="<?php echo $comuna['nombre']; ?>" class="form-control" id="comuna" aria-describedby="comuna">
                        <div id="comuna" class="form-text">Ingresa la comuna...</div>
                    </div>
                    <div class="mb-3">
                        <label for="comuna" class="form-label">Región</label>
                        <select name="region" class="form-control">
                            <option value="<?php echo $comuna['region_id']; ?>">
                                <?php echo $comuna['region']; ?>
                            </option>

                            <option value="">Seleccione...</option>

                            <?php foreach($regiones as $region): ?>
                                <option value="<?php echo $region['id']; ?>">
                                    <?php echo $region['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div id="region" class="form-text">Ingresa la región...</div>
                    </div>
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-outline-success">Editar</button>
                    <a href="<?php echo REGIONES . 'show.php?id=' . $id; ?>" class="btn btn-outline-secondary">Volver</a>
                </form>
            <?php else: ?>
                <p class="text-info">No se puede editar esta región</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>