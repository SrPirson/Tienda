<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
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
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
        }

        .custom-button-group .btn-info {
            background-color: #17a2b8;
            border-color: #117a8b;
            color: white;
        }

        .custom-button-group .btn-info:hover {
            background-color: #138496;
            border-color: #0f6674;
            color: white;
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
                            echo "<a class='dropdown-item' href='../usuario/cambiar_credenciales.php'>Cambiar contraseña</a>";
                        }
                    ?>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                    <?php
                        if (isset($_SESSION["usuario"])) {
                            echo "<a class='dropdown-item' href='../usuario/cerrar_sesion.php'>Cerrar sesión</a>";
                        }
                    ?>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <?php
                    if (!isset($_SESSION["usuario"])) {
                        echo "<a class='nav-link' href='../usuario/iniciar_sesion.php'>Iniciar sesión</a>";
                    }
                ?>
            </li>
        </ul>
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

    <h1 class="display-4 text-primary mb-4 custom-header">Tabla de categorías</h1>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre_categoria = $_POST["categoria"];

            /*
            $comprobar = "SELECT * FROM productos WHERE categoria = '$nombre_categoria'";
            $resultado = $_conexion -> query($comprobar);
            */

            $sql = $_conexion -> prepare("SELECT * FROM productos WHERE categoria = ?");
            $sql -> bind_param("s", $nombre_categoria);
            $sql -> execute();
            $resultado = $sql -> get_result();

            if ($resultado -> num_rows >= 1) {
                echo "<script>
                        alert('No se puede borrar $nombre_categoria porque tiene productos asociados.');
                     </script>";
            } else {
                /*
                $sql = "DELETE FROM categorias WHERE categoria = '$nombre_categoria'";
                $_conexion -> query($sql);
                */

                $sql = $_conexion -> prepare("DELETE FROM categorias WHERE categoria = ?");
                $sql -> bind_param("s", $nombre_categoria);
                $sql -> execute();

            }
        }

        $sql = "SELECT * FROM categorias";
        $resultado = $_conexion -> query($sql);
    ?>
    <div class="custom-button-group">
        <a class="btn btn-info" href="nueva_categoria.php">Agregar una categoría</a>
    </div>
    <table class="table text-center table-bordered border-secundary table-hover table-light">
        <thead class="table-dark">
            <tr>
                <th>Categoría</th>
                <th>Descripción</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
                while ($fila = $resultado -> fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='table-success'>" . $fila["categoria"] . "</td>";
                    echo "<td class='table-danger'>" . $fila["descripcion"] . "</td>";
                    ?>
                    <td>
                        <a  class="btn btn-primary"
                            href="editar_categoria.php?categoria=<?php echo $fila["categoria"] ?>">
                            Editar
                        </a>
                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="categoria" value="<?php echo $fila["categoria"] ?>">
                            <input class="btn btn-danger" type="submit" value="Borrar">
                        </form>
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