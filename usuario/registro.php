<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );
        
        require("../util/conexion.php");
        session_start();
        if (isset($_SESSION["usuario"])) {
            header("location: ../index.php");
            exit;
        }

    ?>
    <style>
        .custom-button-group{
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-bottom: 20px;
        }

        .custom-button-group .btn {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
        }

        .custom-button-group .btn-primary {
            background-color: #007bff;
            border-color: #0056b3;
        }

        .custom-button-group .btn-secondary{
            background-color: #6c757d;
            border-color: #545b62;
        }

        .custom-header {
            margin-left: 10px;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            letter-spacing: 1px;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <?php
        function depurar($entrada) {
            if ($entrada == null) {
                return "";
            }
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            $salida = stripslashes($salida);
            $salida = preg_replace('!\s+!', ' ', $salida);
            return $salida;
        }
    ?>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_usuario = depurar($_POST["usuario"]);
            $tmp_contrasena = $_POST["contrasena"];

            if ($tmp_usuario == "") {
                $err_usuario = "El usuario es obligatorio.";
            } else {
                $sql = "SELECT * FROM usuarios WHERE usuario = '$tmp_usuario'";
                $resultado = $_conexion -> query($sql);
                if ($resultado -> num_rows == 1) {
                    $err_usuario = "El usuario ya existe.";
                } else {
                    if (strlen($tmp_usuario) < 3 || strlen($tmp_usuario) > 15) {
                        $err_usuario = "El usuario tiene que tener entre 3 y 15 caracteres.";
                    } else {
                        $patron_usuario = "/^[a-zA-Z0-9ñÑ]+/";
                        if (!preg_match($patron_usuario, $tmp_usuario)) {
                            $err_usuario = "El usuario solo puede contener letras y números.";
                        } else {
                            $usuario = $tmp_usuario;
                        }
                    }
                }
            }

            if ($tmp_contrasena == "") {
                $err_contrasena = "La contraseña es obligatoria.";
            } else {
                if (strlen($tmp_contrasena) < 8 || strlen($tmp_contrasena) > 15) {
                    $err_contrasena = "La contraseña debe tener entre 8 y 15 caracteres.";
                } else {
                    $patron_contrasena = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                    if (!preg_match($patron_contrasena, $tmp_contrasena)) {
                        $err_contrasena = "La contraseña debe contener como minimo una mayuscula, número, letra y un caracter especial.";
                    } else {
                        $contrasena_cifrada = password_hash($tmp_contrasena,PASSWORD_DEFAULT);
                    }
                }  
            }
        }
    ?>

    <nav>
        <ul class="nav nav-tabs justify-content-center"><p></p></ul>
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="../index.php">Inicio</a>
            </li>
        </ul>
    </nav>
    <div class="container">
        <h1 class="display-4 text-primary mb-4 custom-header">Registro</h1>

        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="usuario" style="font-size: 14px;">
                <label for="usuario" style="margin-top: -6px;">Usuario</label>
                <?php 
                    if(isset($err_usuario)){
                        echo "<span class='error'>$err_usuario</span>";
                    }
                ?>
            </div>
            <div class="form-floating mb-3">
                <input class="form-control" type="password" name="contrasena" style="font-size: 14px;">
                <label for="contrasena" style="margin-top: -6px;">Contraseña</label>
                <?php 
                    if(isset($err_contrasena)){
                        echo "<span class='error'>$err_contrasena</span>";
                    }
                ?>
            </div>
            <div class="custom-button-group">
                <input class="btn btn-primary" type="submit" value="Registrarse">
                <a class="btn btn-secondary" href="./iniciar_sesion.php">Iniciar sesión</a>
            </div>
        </form>
    </div>
    <?php
        if (isset($usuario) && isset($contrasena_cifrada)) {
            $sql = "INSERT INTO usuarios VALUES ('$usuario','$contrasena_cifrada')";
            $_conexion -> query($sql);
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>