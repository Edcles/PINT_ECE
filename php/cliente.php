<?php
    require("./conexion.php");
    require("./funciones.php");

    session_start();

    if(isset($_POST['Productos'])){
        //Crea la factura
            $Cod_Cliente = $_SESSION['Cod_Cliente'];
            $I_Factura = "INSERT INTO Facturas(Cod_Cliente, Fecha) VALUES ('$Cod_Cliente', NOW())";
            $Cod_Factura = "SELECT MAX(Cod_Factura) AS Cod_Factura FROM Facturas;";
            mysqli_query($Conexion, $I_Factura);
            $Cod_Factura = mysqli_query($Conexion, $Cod_Factura);
            foreach($Cod_Factura as $Codigo){
                $Codigo_Factura = $Codigo['Cod_Factura'];
            }
            $_SESSION['Codigo_Factura'] = $Codigo_Factura;

        //Insertan los productos en Participan
            foreach($_POST['Productos'] as $Valor){
                $Insertar = "INSERT INTO Participan (Cod_Producto, Cod_Factura) VALUES ('$Valor', '$Codigo_Factura');";
                mysqli_query($Conexion, $Insertar);
            }
            header('location:./compra.php');
    }
    if(isset($_POST['Salir'])){
        session_destroy();
        header('location:../index.php');
    }
    if(isset($_POST['Facturas'])){
        header('location:./facturas.php');
    }
    if(isset($_POST['Editar'])){
        session_start();
        $_SESSION['Editar'] = $_POST['Editar'];
        header('location:./registrar.php');
    }
?>  
<!DOCTYPE html>
<html>
    <head>
        <title>Lista de la compra</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/cliente.css">
    </head>
    <body>
        <div class="productos-box">
            <form method="POST" action="">
                <h1>Comprar</h1>
                <div id="Productos">
                    <table>
                    <?php
                    $array = TodosProductos();
                    if(mysqli_num_rows($array) != 0){
                        foreach($array as $Clave => $Valor){
                            ?>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Rebaja</th>
                                    </tr>
                                    <tr>
                                        <td><input type='checkbox' value="<?php echo $Valor['Cod_Producto']?>" name='Productos[]'><?php echo $Valor['Nombre']?></td>
                                        <td><?php echo $Valor['Precio'];?></td>
                                        <td>
                                            <?php
                                                $Cod_Rebaja = $Valor['Cod_Rebaja'];
                                                $Select = "SELECT Nombre FROM Rebaja WHERE Cod_Rebaja = '$Cod_Rebaja';";
                                                $array = mysqli_query($Conexion, $Select);
                                                foreach($array as $valor){
                                                    echo $valor['Nombre'];
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                        }
                    } else {
                        echo "<h3>No hay productos</h3>";
                    }
                    ?>
                    </table>
                </div>
                <div id="botonera">
                    <input type="submit" name="Comprar" value="Comprar">
                    <input type="submit" name="Facturas" value="Facturas">
                    <input type="submit" name="Editar" value="Editar Cliente">
                    <input type="submit" name="Salir" value="Salir">
                </div>
            </form>
        </div>
    </body>
</html>