<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    //print_r($_SESSION);

    #listado de empleados
    $res = $mbd->query("SELECT e.id, e.nombre, c.nombre as comuna, r.nombre as rol FROM empleados e INNER JOIN comunas c ON e.comuna_id = c.id INNER JOIN roles r ON r.id = e.rol_id ORDER BY e.nombre");
    $empleados = $res->fetchall();

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
    <title>Empleados</title>
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

            <h4 class="text-success">Lista de Empleados | <a href="<?php echo ADD_EMPLEADO; ?>" class="btn btn-link">Nuevo Empleado</a></h4>

            <?php if(count($empleados)): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Nombre</th>
                        <th>Comuna</th>
                        <th>Rol</th>
                    </tr>
                    <?php foreach($empleados as $empleado): ?>
                        <tr>
                            <td>
                                <a href="<?php echo SHOW_EMPLEADO . $empleado['id']; ?>">
                                    <?php echo $empleado['nombre']; ?>
                                </a>
                            </td>
                            <td><?php echo $empleado['comuna']; ?></td>
                            <td><?php echo $empleado['rol']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p class="text-info">No hay empleados registrados</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>