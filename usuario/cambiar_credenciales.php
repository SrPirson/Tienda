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

        $usuario = $_SESSION["usuario"];
        
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $contrasena = $_POST["contrasena"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $_conexion -> query($sql);

            while ($fila = $resultado -> fetch_assoc()) {
                $contrasena_act = $fila["contrasena"];
            }
            echo "Actual: $contrasena_act";
            echo "Nueva: $contrasena";
        

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
                <input class="form-control" type="password" name="contraena_actual" style="font-size: 14px;">
                <label for="contraena_actual" style="margin-top: -6px;">Contraseña actual</label>
                <?php
                echo "<p>$usuario</p>";
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
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>