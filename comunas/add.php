<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['region'])) {
        $region_id = (int) $_GET['region'];

        #recuperar la region segun la variable region que viene por GET
        $res = $mbd->prepare("SELECT id, nombre FROM regiones WHERE id = ?");
        $res->bindParam(1, $region_id);
        $res->execute();
        $region = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $nombre = trim(strip_tags($_POST['nombre']));

            if (!$nombre) {
                $msg = 'Ingrese el nombre de la comuna';
            }else{
                #verificar que la comuna no este registrada
                $res = $mbd->prepare("SELECT id FROM comunas WHERE nombre = ?");
                $res->bindParam(1, $nombre);
                $res->execute();
                $comuna = $res->fetch();

                if ($comuna) {
                    $msg = 'La comuna ingresada ya existe... intente con otra';
                }else {
                    #ingresamos la comuna
                    $res = $mbd->prepare("INSERT INTO comunas(nombre, region_id) VALUES(?, ?)");
                    $res->bindParam(1, $nombre);
                    $res->bindParam(2, $region_id);
                    $res->execute();

                    $row = $res->rowCount();

                    if ($row) {
                        $_SESSION['success'] = 'La comuna se ha registrado correctamente';
                        header('Location: ' . REGIONES . 'show.php?id=' . $region_id);
                    }
                }
            }
        }
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
            <h4 class="text-success">Nueva Comuna</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <?php if($region): ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="comuna" class="form-label">Comuna</label>
                    <input type="text" name="nombre" class="form-control" id="comuna" aria-describedby="comuna">
                    <div id="comuna" class="form-text">Ingresa la comuna...</div>
                </div>
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <a href="<?php echo REGIONES . 'show.php?id=' . $region_id; ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
            <?php else: ?>
                <p class="text-info">No se puede registrar la comuna en esta región</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>