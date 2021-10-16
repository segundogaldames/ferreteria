<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

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

        if (!$nombre) {
            $msg = 'Ingrese el nombre del rol';
        }else {
            // #verificar que el empleado recibido no este registrado
            // $res = $mbd->prepare("SELECT id FROM roles WHERE nombre = ?");
            // $res->bindParam(1, $nombre);
            // $res->execute();
            // $rol = $res->fetch();

            // if($rol){
            //     $msg = 'El rol ingresado ya existe... intente con otro';
            // }else {
            //     $res = $mbd->prepare("INSERT INTO roles(nombre) VALUES(?)");
            //     $res->bindParam(1, $nombre);
            //     $res->execute();

            //     $row = $res->rowCount();

            //     if ($row) {
            //         $_SESSION['success'] = 'El rol se ha registrado correctamente';
            //         header('Location: ' . ROLES);
            //     }
            // }
        }
    }
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
            <?php include('../partials/mensajes.php'); ?>

            <h4 class="text-success">Nuevo Empleado</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <p class="text-danger">Campos obligatorios *</p>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="rut" class="form-label">RUT<span class="text-danger">*</span></label>
                    <input type="text" name="rut" class="form-control" id="rut" aria-describedby="rut">
                    <div id="rut" class="form-text text-danger">Ingresa el RUT o Cédula de Identidad...</div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre<span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" id="nombre" aria-describedby="nombre">
                    <div id="nombre" class="form-text text-danger">Ingresa el nombre completo...</div>
                </div>
                <div class="mb-3">
                    <label for="fecha_nac" class="form-label">Fecha de nacimiento<span class="text-danger">*</span></label>
                    <input type="date" name="fecha_nac" class="form-control" id="fecha_nac" aria-describedby="fecha_nac">
                    <div id="fecha_nac" class="form-text text-danger">Ingresa la fecha de nacimiento...</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="email">
                    <div id="email" class="form-text text-danger">Ingresa el correo electrónico...</div>
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección<span class="text-danger">*</span></label>
                    <input type="text" name="direccion" class="form-control" id="direccion" aria-describedby="direccion">
                    <div id="direccion" class="form-text text-danger">Ingresa la dirección particular (calle, numero, sector, etc)...</div>
                </div>
                <div class="mb-3">
                    <label for="comuna" class="form-label">Comuna</label>
                    <select name="comuna" class="form-control">

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
                <button type="submit" class="btn btn-outline-success">Guardar</button>
                <a href="<?php echo EMPLEADOS; ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
        </div>

    </div>

</body>
</html>