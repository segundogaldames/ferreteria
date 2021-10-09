<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    #listado de regiones
    $res = $mbd->query("SELECT id, nombre FROM regiones ORDER BY nombre");
    $regiones = $res->fetchall();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
       $nombre = trim(strip_tags($_POST['nombre']));

       if (empty($nombre)) {
           $msg = 'Ingrese el nombre de la región';
       }else {
           #verificar que la region ingresada no existe
           $res = $mbd->prepare("SELECT id FROM regiones WHERE nombre = ?");
           $res->bindParam(1, $nombre);
           $res->execute();
           $region = $res->fetch();

           if ($region) {
              $msg = 'La región ingresada ya existe... intente con otra';
           }else {
               #ingresamos la region
                $res = $mbd->prepare("INSERT INTO regiones(nombre) VALUES(?)");
                $res->bindParam(1, $nombre);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                   header('Location: index.php');
                }
           }
       }

       #print_r($nombre);
    }

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
            <h4 class="text-success">Nueva Región</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="region" class="form-label">Región</label>
                    <input type="text" name="nombre" class="form-control" id="region" aria-describedby="region">
                    <div id="region" class="form-text">Ingresa la región...</div>
                </div>
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <a href="<?php echo REGIONES; ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
        </div>

    </div>

</body>
</html>