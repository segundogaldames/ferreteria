<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_GET['empleado'])) {
        $id = (int) $_GET['empleado'];

        $res = $mbd->prepare("SELECT e.id, e.rut, e.nombre, e.fecha_nac, e.email, e.direccion, c.nombre as comuna, r.nombre as rol, e.comuna_id, e.rol_id FROM empleados e INNER JOIN comunas c ON e.comuna_id = c.id INNER JOIN roles r ON r.id = e.rol_id WHERE e.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $empleado = $res->fetch();

        $res = $mbd->prepare("SELECT id FROM usuarios WHERE empleado_id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $usuario = $res->fetch();

        #lista de comunas
        $res = $mbd->query("SELECT id, nombre FROM comunas ORDER BY nombre");
        $comunas = $res->fetchall();

        #lista de roles
        $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
        $roles = $res->fetchall();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $fecha_nac = trim(strip_tags($_POST['fecha_nac']));
            $direccion = trim(strip_tags($_POST['direccion']));
            $comuna = filter_var($_POST['comuna'], FILTER_VALIDATE_INT);

            if (!$fecha_nac) {
                $msg = 'Ingrese la fecha de nacimiento del empleado';
            }elseif (strlen($direccion) < 6) {
                $msg = 'Ingrese la dirección del empleado';
            }elseif (!$comuna) {
                $msg = 'Seleccione una comuna';
            }else {
               #modificar el empleado
                $res = $mbd->prepare("UPDATE empleados SET fecha_nac = ?, direccion = ?, comuna_id = ? WHERE id = ?");
                $res->bindParam(1, $fecha_nac);
                $res->bindParam(2, $direccion);
                $res->bindParam(3, $comuna);
                $res->bindParam(4, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El perfil se ha modificado correctamente';
                    header('Location: ' . SHOW_EMPLEADO . $id);
                }
            }
        }
    }


    #truco para listar de manera previa los datos de una consulta
    /* echo '<pre>';
    print_r($regiones);exit;
    echo '</pre>'; */
?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador' || $_SESSION['usuario_id'] == $usuario['id']): ?>
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
        <div class="col-md-6 offset-md-3 mb-4">

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <h4 class="text-success">Editar Perfil</h4>

            <?php if($empleado): ?>
                <p class="text-danger">Campos obligatorios *</p>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nac" value="<?php echo $empleado['fecha_nac']; ?>" class="form-control" id="fecha_nac" aria-describedby="fecha_nac">
                    <div id="fecha_nac" class="form-text text-danger">Ingresa la fecha de nacimiento...</div>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección<span class="text-danger">*</span></label>
                    <input type="text" name="direccion" value="<?php echo $empleado['direccion']; ?>" class="form-control" id="direccion" aria-describedby="direccion">
                    <div id="direccion" class="form-text text-danger">Ingresa la dirección particular (calle, numero, sector, etc)...</div>
                </div>
                <div class="mb-3">
                    <label for="comuna" class="form-label">Comuna</label>
                    <select name="comuna" class="form-control">
                        <option value="<?php echo $empleado['comuna_id']; ?>">
                            <?php echo $empleado['comuna']; ?>
                        </option>

                        <option value="">Seleccione...</option>

                        <?php foreach($comunas as $comuna): ?>
                            <option value="<?php echo $comuna['id']; ?>">
                                <?php echo $comuna['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="comuna" class="form-text">Selecciona la comuna...</div>
                </div>

                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-success">Editar</button>
                <a href="<?php echo BASE_URL ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
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