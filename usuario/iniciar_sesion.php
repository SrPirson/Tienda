<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $_POST["usuario"];
            $contrasena = $_POST["contrasena"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = $_conexion -> query($sql);

            if ($resultado -> num_rows == 0) {
                echo "<h2>El usuario $usuario no existe.</h2>";
            } else {
                $datos_usuario = $resultado -> fetch_assoc();
                $acceso_concedido = password_verify($contrasena,$datos_usuario["contrasena"]);
                if ($acceso_concedido) {
                    session_start();
                    $_SESSION["usuario"] = "$usuario";
                    header("location: ../index.php");
                    exit;
                } else {
                    echo "<h2>La contraseña es incorrecta</h2>";
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
        <h1 class="display-4 text-primary mb-4 custom-header">Iniciar Sesión</h1>

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
                <input class="btn btn-primary" type="submit" value="Iniciar Sesión">
                <a class="btn btn-secondary" href="./registro.php">Registrarse</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>