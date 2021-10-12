<?php
    require("./conexion.php");
    require("./funciones.php");
    session_start();

    $Cod_Cliente = $_SESSION['Cod_Cliente'];
    $SELECT_DATOS_CLIENTE = "SELECT * 
        FROM cliente WHERE Cod_Cliente = '$Cod_Cliente' 
        ;"
    ;
    $array = mysqli_query($Conexion, $SELECT_DATOS_CLIENTE);
    $valor = $array->fetch_assoc();

    $SELECT_DATOS_FACTURA = "SELECT * 
        FROM facturas WHERE Cod_Cliente = '$Cod_Cliente' 
        ;"
    ;

    $array2 = mysqli_query($Conexion, $SELECT_DATOS_FACTURA);
    $valor3 = $array2->fetch_assoc();

    if(isset($_POST['Salir'])){
        header('location:./cliente.php');
    }
    if(isset($_POST['Borrar'])){
        $Cod_Factura = $_POST['Cod_Factura'];
        $Borrar = "DELETE FROM Participan WHERE Cod_Factura = '$Cod_Factura'";
        $B_Participan = mysqli_query($Conexion, $Borrar);
        if($B_Participan){
            $Borrar2 = "DELETE FROM Facturas WHERE Cod_Factura = '$Cod_Factura'";
            $B_Factura = mysqli_query($Conexion, $Borrar2);
            if($B_Factura){
                header("Refresh:0");
                echo '<script language="javascript">alert("Factura borrada");</script>'; 
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Facturas</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/cliente.css">
    </head>
    <body>
        <div id="facturas">
            <form method="POST" action="">
                <?php
                if(mysqli_num_rows($array2) != 0){
                    echo "<h1>Facturas</h1>";
                    echo "<div id='facturas-box'>";
                    foreach($array2 as $valor2){
                        if($valor2['Coste']>0){
                        ?>
                        <table>
                            <tr>
                                <?php
                                     echo "<th colspan='2'>Fecha: ".$valor2['Fecha']."</th>";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                    echo "<td><b>Código: </b></td><td>".$valor2['Cod_Factura']."</td>";
                                ?>
                            </tr>
                                <?php
                                    echo "<td><b>Nombre: </b></td><td>".$valor['Nombre']."</td>";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                    echo "<td><b>Apellidos: </b></td><td>".$valor['Apellidos']."</td>";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                    echo "<td><b>Correo: </b></td><td>".$valor['Correo']."</td>";
                                ?>
                            </tr>
                            <tr>
                                <?php
                                    $Pago = $valor2['Cod_Pago'];
                                    $Select = "SELECT p.Nombre FROM Pago p, facturas f WHERE  p.Cod_Pago = f.Cod_Pago AND p.Cod_Pago = '$Pago';";
                                    $array = mysqli_query($Conexion, $Select);
                                    foreach($array as $Nombre){
                                        echo "<td><b>Pago: </b></td><td>".$Nombre['Nombre']."</td>";
                                    }
                                    
                                ?>
                            </tr>
                            <tr>
                                <?php
                                $Cod_Factura = $valor2['Cod_Factura'];
                            
                                $SELECT_DATOS_PRODUCTOS = "SELECT * 
                                    FROM participan p, producto pro
                                    WHERE p.Cod_Producto = pro.Cod_Producto
                                    AND p.Cod_Factura = '$Cod_Factura'
                                    ;"
                                ;
                            
                                $array3 = mysqli_query($Conexion, $SELECT_DATOS_PRODUCTOS);
                                echo "<td><b>Productos: </b></td><td>";
                                while ($valor3 = mysqli_fetch_assoc($array3)){
                                    echo $valor3['Nombre']." ";
                                    echo "<b>Cantidad: </b>".$valor3['Cantidad']." ";
                                    echo "<b>Precio: </b>".$valor3['Cantidad'] * $valor3['Precio']."<br>";
                                }
                                ?>
                            </tr>
                            <tr>    
                                <?php
                                    echo "<th><b>Total: </b></th><th>".$valor2['Coste']."</th>";
                                ?>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" name="Cod_Factura" value="<?php echo $valor2['Cod_Factura']?>">
                                    <input type="submit" id="borrar" name="Borrar" value="Borrar">
                                </td>
                            </tr>
                        </table>
                        <?php
                        }
                    }
                    echo "</div>";
                } else {
                    echo "<h1>No hay facturas para este cliente</h1>";
                }                
                    ?>
                </div>
                <div id="botonera">
                    <input type="submit" name="Salir" value="Salir">
                </div>
            </form>
    </body>
</html>