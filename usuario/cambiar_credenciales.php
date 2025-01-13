<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1 );
        
        require("../util/conexion.php");
        session_start();
        if (!isset($_SESSION["usuario"])) {
            header("location: ../usuario/iniciar_sesion.php");
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

        $usuario = $_SESSION["usuario"];
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_contrasena_act = $_POST["contraena_actual"];
            $tmp_contrasena_nueva = $_POST["contrasena_nueva"];
            $tmp_contrasena_conf = $_POST["contrasena_conf"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $_conexion -> query($sql);

            while ($fila = $resultado -> fetch_assoc()) {
                $contrasena_act = $fila["contrasena"];
            }

            $comparador = password_verify($tmp_contrasena_act, $contrasena_act);
            if (!$comparador) {
                $err_act_contrasena = "La contraseña no es correcta.";
            } else {
                if ($tmp_contrasena_nueva == "" || $tmp_contrasena_conf == "") {
                    $err_nueva_contrasena = "No puede haber campos vacíos.";
                } else {
                    if ($tmp_contrasena_nueva !== $tmp_contrasena_conf) {
                        $err_nueva_contrasena = "Las contraseñas no coinciden.";
                    } else {
                        if (strlen($tmp_contrasena_nueva) < 8 || strlen($tmp_contrasena_nueva) > 15) {
                            $err_nueva_contrasena = "La contraseña debe tener entre 8 y 15 caracteres.";
                        } else {
                            $patron_contrasena = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                            if (!preg_match($patron_contrasena, $tmp_contrasena_nueva)) {
                                $err_nueva_contrasena = "La contraseña debe contener como minimo una mayuscula, números y letras.";
                            } else {
                                if ($tmp_contrasena_act === $tmp_contrasena_conf) {
                                    $err_nueva_contrasena = "La contraseña no puede ser la misma.";
                                } else {
                                    $contrasena_cifrada = password_hash($tmp_contrasena_nueva,PASSWORD_DEFAULT);
                                }
                            }
                        }
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
        
        <h1 class="display-4 text-primary mb-4 custom-header">Cambiar contraseña</h1>

        <form class="col-6" action="" method="post" enctype="multipart/form-data">

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="usuario" style="font-size: 14px;" value="<?php echo "$usuario" ?>" disabled>
                <label for="usuario" style="margin-top: -6px;">Usuario</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="password" name="contraena_actual" style="font-size: 14px;">
                <label for="contraena_actual" style="margin-top: -6px;">Contraseña actual</label>
                <?php
                    if(isset($err_act_contrasena)){
                        echo "<span class='error'>$err_act_contrasena</span>";
                    }
                ?>
            </div>
            <br>

            <div class="form-floating mb-3">
                <input class="form-control" type="password" name="contrasena_nueva" style="font-size: 14px;">
                <label for="contrasena_nueva" style="margin-top: -6px;">Nueva contraseña</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="password" name="contrasena_conf" style="font-size: 14px;">
                <label for="contrasena_conf" style="margin-top: -6px;">Confirmar contraseña</label>
                <?php 
                    if(isset($err_nueva_contrasena)){
                        echo "<span class='error'>$err_nueva_contrasena</span>";
                    }
                ?>
            </div>

            <div class="custom-button-group">
                <input type="hidden" name="usuario" value="<?php echo $usuario ?>">
                <input class="btn btn-primary" type="submit" value="Guardar">
                <a class="btn btn-danger" href="../index.php">Cancelar</a>
            </div>
        </form>
    </div>
    <?php
        if (isset($contrasena_cifrada)) {
            /* $sql = "UPDATE usuarios SET contrasena = '$contrasena_cifrada' WHERE usuario = '$usuario'";
            $_conexion -> query($sql); */

            $sql = $_conexion -> prepare("UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
            $sql -> bind_param("ss", $contrasena_cifrada, $usuario);
            $sql -> execute();

        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>