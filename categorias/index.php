<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <?php
        error_reporting( E_ALL );
        ini_set("display_errors", 1);
        
        require("../util/conexion.php");
    ?>
    <style>
        img {
            width: 100px;
            height: 150px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Tabla de categorías</h1>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre_categoria = $_POST["categoria"];
            $sql = "DELETE FROM categorias WHERE categoria = '$nombre_categoria'";
            $_conexion -> query($sql);
        }

        $sql = "SELECT * FROM categorias";
        $resultado = $_conexion -> query($sql);
    ?>
    <br>
    <a class="btn btn-info" href="nueva_categoria.php">Agregar una categoría</a>
    <a class="btn btn-info" href="../productos/index.php">Ver productos</a>
    <br><br>
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