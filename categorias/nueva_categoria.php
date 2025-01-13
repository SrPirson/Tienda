<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1);
        
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

    <div class="container my-5">
        <h1 class="display-4 text-primary mb-4 custom-header">Nueva categoría</h1>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_categoria = depurar($_POST["categoria"]);
            $tmp_descripcion = depurar($_POST["descripcion"]);


            /* Validación categoria */
            if ($tmp_categoria == "") {
                $err_categoria = "La categoría es ogligatoria.";
            } else {
                if (strlen($tmp_categoria) < 2 || strlen($tmp_categoria) > 30) {
                    $err_categoria = "La categoría tiene que tener entre 2 y 30 caracteres.";
                } else {
                    $patron_categoria = "/[a-zA-ZñÑ ]+/";
                    if (!preg_match($patron_categoria, $tmp_categoria)) {
                        $err_categoria = "La categoría solamente puede contener letras y espacios en blanco.";
                    } else {
                        /* $sql = "SELECT * FROM categorias WHERE categoria = '$tmp_categoria'";
                        $resultado = $_conexion -> query($sql); */

                        $sql = $_conexion -> prepare("SELECT * FROM categorias WHERE categoria = ?");
                        $sql -> bind_param("s", $tmp_categoria);
                        $sql -> execute();
                        $resultado = $sql -> get_result();

                        if ($resultado -> num_rows == 1) {
                            $err_categoria = "La categoría ya existe.";
                        } else {
                            $categoria = $tmp_categoria;
                        }
                    }
                }
            }


            /* Validación descripción */
            if ($tmp_descripcion == "") {
                $err_descripcion = "La descripción es obligarotía";
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
                <input class="form-control" type="text" name="categoria" style="font-size: 14px;">
                <label for="categoria" style="margin-top: -6px;">Categoría</label>
                <?php 
                    if(isset($err_categoria)){
                        echo "<span class='error'>$err_categoria</span>";
                    }
                ?>
            </div>

            <!-- Descripción -->
            <div class="form-floating mb-3">
                <textarea class="form-control" name="descripcion" id="descripcion" style="height: 100px; font-size: 14px;"></textarea>
                <label for="descripcion" style="margin-top: -6px;">Descripción</label>
                <?php 
                    if(isset($err_descripcion)){
                        echo "<span class='error'>$err_descripcion</span>";
                    }
                ?>
            </div>

            <!-- Botones -->
            <div class="custom-button-group">
                <input class="btn btn-primary" type="submit" value="Añadir">
                <a class="btn btn-secondary" href="./index.php">Volver</a>
            </div>

        </form>
    </div>

    <?php
        /* Enviar a la BBDD */
        if (isset($categoria) && isset($descripcion)) {
            /* $enviar = "INSERT INTO categorias (categoria, descripcion) 
                VALUES ('$categoria', '$descripcion')";
            $_conexion -> query($enviar); */

            $sql = $_conexion -> prepare("INSERT INTO categorias (categoria, descripcion) 
                VALUES (?, ?)");
            $sql -> bind_param("ss", $categoria, $descripcion);
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>