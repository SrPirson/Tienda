<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1);
        
        require("./util/conexion.php");

        session_start();
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
        img {
            width: 100px;
            height: 100px;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <nav>
        <ul class="nav nav-tabs justify-content-end">
            <li class="nav-item">
                <?php
                    if (isset($_SESSION["usuario"])) {
                        echo "<p class='nav-link dropdown-toggle text-secondary' data-bs-toggle='dropdown' role='button'>Bienvenid@ " . $_SESSION["usuario"] . "</p>";
                    }
                ?>
                <ul class="dropdown-menu">
                    <li>
                    <?php
                        if (isset($_SESSION["usuario"])) {
                            echo "<a class='dropdown-item' href='./usuario/cambiar_credenciales.php'>Cambiar contraseña</a>";
                        }
                    ?>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                    <?php
                        if (isset($_SESSION["usuario"])) {
                            echo "<a class='dropdown-item' href='./usuario/cerrar_sesion.php'>Cerrar sesión</a>";
                        }
                    ?>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <?php
                    if (!isset($_SESSION["usuario"])) {
                        echo "<a class='nav-link' href='./usuario/iniciar_sesion.php'>Iniciar sesión</a>";
                    }
                ?>
            </li>
        </ul>

        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <?php
                    if (isset($_SESSION["usuario"])) {
                        echo "<a class='nav-link' aria-current='page' href='./categorias/index.php'>Categorías</a>";
                    } else {
                        echo "<a class='nav-link' aria-current='page' href='./categorias/index.php' hidden>Categorías</a>";
                    }
                ?>
            </li>
            <li class="nav-item">
                <?php
                    if (isset($_SESSION["usuario"])) {
                        echo "<a class='nav-link' aria-current='page' href='./productos/index.php'>Productos</a>";
                    } else {
                        echo "<a class='nav-link' aria-current='page' href='./productos/index.php' hidden>Productos</a>";
                    }
                ?>
            </li>
        </ul>
    </nav>

    <h1 class="display-4 text-primary mb-4 custom-header">Productos</h1>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_producto = $_POST["id_producto"];
            $sql = "DELETE FROM productos WHERE id_producto = $id_producto";
            $_conexion -> query($sql);
        }

        $sql = "SELECT * FROM productos";
        $resultado = $_conexion -> query($sql);
    ?>
    <br>
    <table class="table text-center table-bordered border-secundary table-hover table-light">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
                while ($fila = $resultado -> fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='table-success'>" . $fila["nombre"] . "</td>";
                    echo "<td class='table-danger'>" . $fila["precio"] . "</td>";
                    echo "<td class='table-warning'>" . $fila["categoria"] . "</td>";
                    echo "<td class='table-info'>" . $fila["stock"] . "</td>";
                    echo "<td class='table-primary'>" . $fila["descripcion"] . "</td>";
                    ?>
                    <td class='table-secondary'>
                        <img src="./imagenes/<?php echo $fila["imagen"] ?>" alt="">
                    </td>
                    <?php
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>