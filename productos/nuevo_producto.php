<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1);
        
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

    <div class="container my-5">
        <h1 class="display-4 text-primary mb-4 custom-header">Nuevo producto</h1>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $tmp_nombre = depurar($_POST["nombre"]);
            $tmp_precio = depurar($_POST["precio"]);
            if (isset($_POST["categoria"])) {
                $tmp_categoria = depurar($_POST["categoria"]);
            } else {
                $tmp_categoria = "";
            }
            $tmp_stock = depurar($_POST["stock"]);
            $tmp_descripcion = depurar($_POST["descripcion"]);


            /* Validación imagenes */
            $nombre_img = $_FILES["imagen"]["name"];
            $ubi_tmp_img = $_FILES["imagen"]["tmp_name"];
            $type_img = $_FILES["imagen"]["type"];
            
            if (strlen($nombre_img) > 60) {
                $err_imagen = "El nombre de la imagen no puede superar los 60 catacteres.";
            } else {
                $lista_extensiones = ["image/png", "image/jpg", "image/jpeg", "image/webp"];
                if (!in_array($type_img, $lista_extensiones)) {
                    $err_imagen = "La extensión de imagen no es admitida.";
                } else {
                    $ubi_final_img = "../imagenes/$nombre_img";
                    move_uploaded_file($ubi_tmp_img, $ubi_final_img);
                }
            }

            /* Validación nombre */
            if ($tmp_nombre == "") {
                $err_nombre = "El nombre es obligatorio.";
            } else {
                if (strlen($tmp_nombre) < 2 || strlen($tmp_nombre) > 50) {
                    $err_nombre = "El nombre tiene que tener entre 2 y 50 caracteres.";
                } else {
                    $patron_nombre = "/^[a-zA-Z0-9 ]+/";
                    if (!preg_match($patron_nombre, $tmp_nombre)) {
                        $err_nombre = "El nombre solo puede tener letras, números y espacios en blanco.";
                    } else {
                        $nombre = $tmp_nombre;
                    }
                }
            }


            /* Validación precio */
            if ($tmp_precio == "") {
                $err_precio = "El precio es obligatorio.";
            } else {
                if (!is_numeric($tmp_precio)) {
                    $err_precio = "El precio debe ser numérico";
                } else {
                    if ($tmp_precio < 0 || $tmp_precio > 2147483647) {
                        $err_precio = "El precio debe ser mayor a 0 y menor a 2.147.483.647.";
                    } else {
                        $patron_precio = "/^[0-9]{1,4}(\.[0-9]{1,2})?$/";
                        if (!preg_match($patron_precio, $tmp_precio)) {
                            $err_precio = "El rango de precio es de 0 hasta 9999.99";
                        } else {
                            $precio = $tmp_precio;
                        }
                    }   
                }
            }


            /* Validación categoria */
            if ($tmp_categoria == "") {
                $err_categoria = "La categoría es ogligatoria.";
            } else {
                if (strlen($tmp_categoria) > 30) {
                    $err_categoria = "La categoría debe tener un máximo del 30 caracteres.";
                } else {
                    $sql = "SELECT * FROM categorias";
                    $resultado_categoria = $_conexion -> query($sql);
                    $lista_categorias = [];

                    while ($fila = $resultado_categoria -> fetch_assoc()) {
                        $lista_categorias[] = $fila['categoria'];
                    }

                    if (!in_array($tmp_categoria, $lista_categorias)) {
                        $err_categoria = "La categoría no es válida";
                    } else {
                        $categoria = $tmp_categoria;
                    }
                }
            }


            /* Validación stock */
            if (!is_numeric($tmp_stock)) {
                $err_stock = "El stock debe ser numérico";
            } else {
                if ($tmp_stock < 0 || $tmp_stock > 2147483647) {
                    $err_stock = "El stock debe ser mayor a 0 y menor a 2.147.483.647.";
                } else {
                    $stock = $tmp_stock;
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
        
        $sql = "SELECT * FROM categorias ORDER BY categoria";
        $resultado = $_conexion -> query($sql);
        $categorias = [];

        while ($fila = $resultado -> fetch_assoc()) {
            array_push($categorias, $fila["categoria"]);
        }

        ?>
        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <!-- Nombre -->
            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="nombre" style="font-size: 14px;">
                <label for="nombre" style="margin-top: -6px;">Nombre</label>
                <?php 
                    if(isset($err_nombre)){
                        echo "<span class='error'>$err_nombre</span>";
                    }
                ?>
            </div>

            <!-- Precio -->
            <div class="form-floating mb-3">
                <input id="precio" class="form-control" type="text" name="precio" style="font-size: 14px;">
                <label for="precio" style="margin-top: -6px;">Precio</label>
                <?php 
                    if(isset($err_precio)){
                        echo "<span class='error'>$err_precio</span>";
                    }
                ?>
            </div>

            <!-- Categorías -->
            <div class="form-floating mb-3">
                <select id="categoria" class="form-select" name="categoria" style="font-size: 14px;">
                    <option disabled selected hidden>--- Seleccione una categoría ---</option>
                    <?php
                        foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria ?>">
                                <?php echo $categoria ?>
                            </option>
                        <?php } ?>
                </select>
                <label for="categoria" style="margin-top: -6px;">Categoría</label>
                <?php 
                    if(isset($err_categoria)){
                        echo "<span class='error'>$err_categoria</span>";
                    }
                ?>
            </div>

            <!-- Stock -->
            <div class="form-floating mb-3">
                <input id="stock" class="form-control" type="text" name="stock" style="font-size: 14px;">
                <label for="stock" style="margin-top: -6px;">Stock</label>
                <?php 
                    if(isset($err_stock)){
                        echo "<span class='error'>$err_stock</span>";
                    }
                ?>
            </div>

            <!-- Imagen -->
            <div class="form-floating mb-3">
                <input class="form-control" type="file" name="imagen" id="imagen" style="font-size: 14px;">
                <label for="imagen" style="margin-top: -6px;">Imagen</label>
                <?php 
                    if(isset($err_imagen)){
                        echo "<span class='error'>$err_imagen</span>";
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
                <a class="btn btn-secondary" href="index.php">Volver</a>
            </div>

        </form>
    </div>

    <?php
        /* Enviar a la BBDD */
        if (isset($nombre) && isset($precio) && isset($categoria) && isset($ubi_final_img) && isset($descripcion)) {
            $enviar = "INSERT INTO productos (nombre, precio, categoria, stock, imagen, descripcion) 
                VALUES ('$nombre', '$precio', '$categoria', '$stock', '$ubi_final_img', '$descripcion')";
            $_conexion -> query($enviar);
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>