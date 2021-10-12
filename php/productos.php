<?php
    require('./conexion.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registro</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/trabajador.css">
    </head>
    <body>
        <h1>Productos Insertados</h1>
        <table>
            <tr>
                <th>Nombre del Producto</th>
            </tr>
            <?php
                $Select = "SELECT p.Nombre as NombreProd FROM trabajador t, inscribe i, producto p WHERE t.Cod_Trabajador = i.Codigo_Trabajador AND p.Cod_Producto = i.Codigo_Producto";
                $array = mysqli_query($Conexion, $Select);
                foreach($array as $valor){
                    echo "<tr>";
                    echo "<td>".$valor['NombreProd']."</td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <form method="POST" action="./registrartrabajador.php">
            <input type="submit" name="Volver" value="Volver">
        </form>
    </body>
</html>