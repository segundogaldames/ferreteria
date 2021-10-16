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

        #lista de comunas
        $res = $mbd->query("SELECT id, nombre FROM comunas ORDER BY nombre");
        $comunas = $res->fetchall();

        #lista de roles
        $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
        $roles = $res->fetchall();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            $rut = trim(strip_tags($_POST['rut']));
            $nombre = trim(strip_tags($_POST['nombre']));
            $fecha_nac = trim(strip_tags($_POST['fecha_nac']));
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $direccion = trim(strip_tags($_POST['direccion']));
            $comuna = filter_var($_POST['comuna'], FILTER_VALIDATE_INT);
            $rol = filter_var($_POST['rol'], FILTER_VALIDATE_INT);

            if (strlen($rut) < 7 || strlen($rut) > 10) {
                $msg = 'Ingrese el RUT del empleado';
            }elseif (strlen($nombre) < 4) {
                $msg = 'Ingrese el nombre completo del empleado';
            }elseif (!$fecha_nac) {
                $msg = 'Ingrese la fecha de nacimiento del empleado';
            }elseif (!$email) {
                $msg = 'Ingrese el correo electrónico del empleado';
            }elseif (strlen($direccion) < 6) {
                $msg = 'Ingrese la dirección del empleado';
            }elseif (!$comuna) {
                $msg = 'Seleccione una comuna';
            }elseif (!$rol) {
                $msg = 'Seleccione un rol';
            }else {
               #modificar el empleado
                $res = $mbd->prepare("UPDATE empleados SET rut = ?, nombre = ?, fecha_nac = ?, direccion = ?, email = ?, comuna_id = ?, rol_id = ? WHERE id = ?");
                $res->bindParam(1, $rut);
                $res->bindParam(2, $nombre);
                $res->bindParam(3, $fecha_nac);
                $res->bindParam(4, $direccion);
                $res->bindParam(5, $email);
                $res->bindParam(6, $comuna);
                $res->bindParam(7, $rol);
                $res->bindParam(8, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El empleado se ha modificado correctamente';
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

            <h4 class="text-success">Editar Empleado</h4>

            <?php if($empleado): ?>
                <p class="text-danger">Campos obligatorios *</p>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="rut" class="form-label">RUT<span class="text-danger">*</span></label>
                    <input type="text" name="rut" value="<?php echo $empleado['rut']; ?>" class="form-control" id="rut" aria-describedby="rut">
                    <div id="rut" class="form-text text-danger">Ingresa el RUT o Cédula de Identidad (con guión y digito verificador, sin puntos)...</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre<span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="<?php echo $empleado['nombre']; ?>" class="form-control" id="nombre" aria-describedby="nombre">
                    <div id="nombre" class="form-text text-danger">Ingresa el nombre completo...</div>
                </div>
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nac" value="<?php echo $empleado['fecha_nac']; ?>" class="form-control" id="fecha_nac" aria-describedby="fecha_nac">
                    <div id="fecha_nac" class="form-text text-danger">Ingresa la fecha de nacimiento...</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                    <input type="email" name="email" value="<?php echo $empleado['email']; ?>" class="form-control" id="email" aria-describedby="email">
                    <div id="email" class="form-text text-danger">Ingresa el correo electrónico...</div>
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
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select name="rol" class="form-control">
                        <option value="<?php echo $empleado['rol_id']; ?>">
                            <?php echo $empleado['rol']; ?>
                        </option>

                        <option value="">Seleccione...</option>

                        <?php foreach($roles as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>">
                                <?php echo $rol['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="rol" class="form-text">Selecciona el rol...</div>
                </div>

                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-success">Editar</button>
                <a href="<?php echo SHOW_EMPLEADO . $id; ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
            <?php else: ?>
                <p class="text-info">No hay datos</p>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>