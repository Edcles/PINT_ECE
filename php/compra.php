<?php
    require("./conexion.php");
    require("./funciones.php");

    session_start();
    $Cod_Factura = $_SESSION['Codigo_Factura'];
    $Coste = 0;

    if(isset($_POST['Finalizar'])){
        $Productos = "SELECT prod.* FROM Producto prod, Participan par WHERE prod.Cod_Producto = par.Cod_Producto AND par.Cod_Factura = '$Cod_Factura';";
        $B_Productos = mysqli_query($Conexion, $Productos);
        $Array = $_POST['cantidad'];
        $Contador = 0;
        foreach($B_Productos as $Valor){
            $Cod_Producto = $Valor['Cod_Producto'];
            $Coste += $Array[$Contador]*$Valor['Precio'];
            $New_Stock = $Valor['Stock'] - $Array[$Contador];

            $Actualizar = "UPDATE Participan SET Cantidad = '$Array[$Contador]' WHERE Cod_Producto = '$Cod_Producto' AND Cod_Factura = '$Cod_Factura';";
            mysqli_query($Conexion, $Actualizar);
            $Actualizar = "UPDATE Facturas SET Coste = $Coste WHERE Cod_Factura = $Cod_Factura;";
            mysqli_query($Conexion, $Actualizar);
            $Actualizar = "UPDATE Producto SET Stock = '$New_Stock' WHERE Cod_Producto = $Cod_Producto;";
            mysqli_query($Conexion, $Actualizar);
            $Contador++;
            header('location: ./ticket.php');
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registro</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/cliente.css">
    </head>
    <body>
        <form method="POST" action="">
            <div id="Ticket">
                <h1>Elija cantidad</h1>
                <table>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Fecha de Caducidad</th>
                        <th>Stock</th>
                        <th>Rebaja</th>
                        <th>Cantidad</th>
                    </tr>
                    <tr>
                        <?php
                            $Productos = "SELECT prod.* FROM Producto prod, Participan par WHERE prod.Cod_Producto = par.Cod_Producto AND par.Cod_Factura = '$Cod_Factura';";
                            $B_Productos = mysqli_query($Conexion, $Productos);
                            foreach($B_Productos as $Valor){
                                $_SESSION['Codigodeproducto'] = $Valor['Cod_Producto'];
                                $_SESSION['Precio'] = $Valor['Precio'];
                            ?>
                                <td><?php echo $Valor['Nombre']?></td>
                                <td><?php echo $Valor['Precio']?></td>
                                <td><?php echo $Valor['Fecha_de_caducidad']?></td>
                                <td><?php echo $Valor['Stock']?></td>
                                <td>
                                    <?php 
                                        $Rebaja = $Valor['Cod_Rebaja'];
                                        $SELECT = "SELECT * FROM Rebaja WHERE Cod_Rebaja = '$Rebaja'";
                                        $array = mysqli_query($Conexion, $SELECT);
                                        foreach($array as $Nombre){
                                            echo $Nombre['Nombre'];
                                        }
                                    ?>
                                </td>
                                <td><input type="number" name="cantidad[]" placeholder="1" min="1" max="<?php echo $Valor['Stock']?>"></td>
                    </tr>
                            <?php
                            }
                        ?>
                </table>
            <div>
            <div id="Comprar">
                <input type="submit" name="Finalizar" value="Finalizar Compra">
            </div>
        </form>
    </body>
</html>