<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['empleado'])) {
        $id = (int) $_GET['empleado'];

        $res = $mbd->prepare("SELECT e.id, e.rut, e.nombre, e.fecha_nac, e.email, e.direccion, c.nombre as comuna, r.nombre as rol FROM empleados e INNER JOIN comunas c ON e.comuna_id = c.id INNER JOIN roles r ON r.id = e.rol_id WHERE e.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $empleado = $res->fetch();

        $res = $mbd->prepare("SELECT id, activo FROM usuarios WHERE empleado_id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $usuario = $res->fetch();
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

            <h4 class="text-success">Empleado</h4>

            <?php if($empleado): ?>
                <table class="table table-hover">
                    <tr>
                        <th>RUT:</th>
                        <td><?php echo $empleado['rut']; ?></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><?php echo $empleado['nombre']; ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de nacimiento:</th>
                        <td>
                            <?php
                                #crear una instancia de la clase Datetime
                                $fecha = new DateTime($empleado['fecha_nac']);
                                echo $fecha->format('d-m-Y');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo $empleado['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Dirección:</th>
                        <td><?php echo $empleado['direccion']; ?></td>
                    </tr>
                    <tr>
                        <th>Comuna:</th>
                        <td><?php echo $empleado['comuna']; ?></td>
                    </tr>
                    <tr>
                        <th>Rol:</th>
                        <td><?php echo $empleado['rol']; ?></td>
                    </tr>

                    <?php if($usuario): ?>
                        <tr>
                            <th>Activo:</th>
                            <td>
                                <?php
                                    if ($usuario['activo'] == 1) {
                                        echo 'Si';
                                    }else {
                                        echo 'No';
                                    }
                                ?>
                            </td>

                        </tr>
                    <?php endif; ?>

                </table>
                <a href="<?php echo EDIT_EMPLEADO . $id; ?>" class="btn btn-outline-success">Editar</a>
                <?php if(!$usuario): ?>
                    <a href="<?php echo ADD_USUARIO . $id; ?>" class="btn btn-outline-primary">Crear Cuenta</a>
                <?php else: ?>
                    <a href="<?php echo EDIT_USUARIO . $usuario['id']; ?>" class="btn btn-outline-primary">Modificar Estado</a>
                <?php endif; ?>
                <a href="<?php echo EMPLEADOS; ?>" class="btn btn-outline-secondary">Volver</a>
            <?php else: ?>
                <p class="text-info">No hay datos</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>