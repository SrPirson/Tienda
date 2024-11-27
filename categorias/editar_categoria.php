<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar categoría</title>
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

    <nav>
        <ul class="nav nav-tabs justify-content-center"><p></p></ul>
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="../index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./index.php">Categorías</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../productos/index.php">Productos</a>
            </li>
        </ul>
    </nav>

    <div class="container">

        <h1 class="display-4 text-primary mb-4 custom-header">Editar categoría</h1>
        <?php

        $categoria = $_GET["categoria"];
        $sql = "SELECT * FROM categorias WHERE categoria = '$categoria'";
        $resultado = $_conexion -> query($sql);

        while ($fila = $resultado -> fetch_assoc()) {
            $categoria = $fila["categoria"];
            $descripcion = $fila["descripcion"];
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_descripcion = depurar($_POST["descripcion"]);

            /* Validación descripción */
            if ($tmp_descripcion == "") {
                $err_descripcion = "La descripción es obligatoría";
            } else {
                if (strlen($tmp_descripcion) > 255) {
                    $err_descripcion = "La descripción debe tener un máximo del 255 caracteres.";
                } else {
                    $descripcion = $tmp_descripcion;
                }
            }
            
        }

        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <!-- Categorías -->
            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="categoria" style="font-size: 14px;" value="<?php echo $categoria ?>" disabled>
                <label for="categoria" style="margin-top: -6px;">Categoría</label>
            </div>

            <!-- Descripción -->
            <div class="form-floating mb-3">
                <textarea class="form-control" name="descripcion" id="descripcion" style="height: 100px; font-size: 14px;"><?php echo $descripcion ?></textarea>
                <label for="descripcion" style="margin-top: -6px;">Descripción</label>
                <?php 
                    if(isset($err_descripcion)){
                        echo "<span class='error'>$err_descripcion</span>";
                    }
                ?>
            </div>

            <div class="mb-3 btn-group">
                <input type="hidden" name="categoria" value="<?php echo $categoria ?>">
                <input class="btn btn-info" type="submit" value="Editar">
                <a class="btn btn-info" href="index.php">Volver</a>
            </div>

        </form>
    </div>
    <?php
        if (isset($descripcion)) {
            $update = "UPDATE categorias SET
                descripcion = '$descripcion'
                WHERE categoria = '$categoria'
            ";
            $_conexion -> query($update);
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>