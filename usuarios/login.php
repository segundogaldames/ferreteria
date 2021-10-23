<?php
    #llamada al archivo de rutas
    require('../class/rutas.php');
    require('../class/conexion.php');

    session_start();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $clave = trim(strip_tags($_POST['clave']));

        if (!$email) {
            $msg = 'Ingresa tu correo electrónico';
        }elseif (!$clave) {
            $msg = 'Ingresa tu password';
        }else {
            $clave = sha1($clave);
            #verificar que el usuario, la clave y el estado estan registrados en la tabla usuarios
            $res = $mbd->prepare("SELECT u.id, u.empleado_id, e.nombre, e.email, r.nombre as rol FROM usuarios u INNER JOIN empleados e ON e.id = u.empleado_id INNER JOIN roles r ON r.id = e.rol_id WHERE e.email = ? AND u.clave = ? AND u.activo = 1");
            $res->bindParam(1, $email);
            $res->bindParam(2, $clave);
            $res->execute();
            $usuario = $res->fetch();

            if (!$usuario) {
                $msg = 'El usuario o la password no están registrados';
            }else {
                #crear las variables de session para el usuario
                $_SESSION['autenticado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                $_SESSION['usuario_empleado'] = $usuario['empleado_id'];

                header('Location: ' . BASE_URL);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
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


            <h4 class="text-success">Iniciar Sesión</h4>

            <?php if(isset($msg)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>


            <form action="" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="email">
                    <div id="email" class="form-text text-danger">Ingresa tu email...</div>
                </div>
                <div class="mb-3">
                    <label for="clave" class="form-label">Password</label>
                    <input type="password" name="clave" class="form-control" id="clave" aria-describedby="clave">
                    <div id="clave" class="form-text text-danger">Ingresa tu password...</div>
                </div>
                <input type="hidden" name="confirm" value="1">
                <button type="submit" class="btn btn-outline-success">Ingresar</button>
                <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary">Volver</a>
            </form>
        </div>

    </div>

</body>
</html>