<?php
    require("./conexion.php");
    require("./funciones.php");

    session_start();

    $Cod_Factura = $_SESSION['Codigo_Factura'];

    if(isset($_POST['Finalizar'])){
        $radio = $_POST['radio'];
        $UPDATE = "UPDATE Facturas SET Cod_Pago = '$radio' WHERE Cod_Factura = '$Cod_Factura'";
        mysqli_query($Conexion, $UPDATE);
        echo '<script language="javascript">alert("Gracias por su compra");</script>'; 
        header('location:./cliente.php');
    }

    $Cod_Cliente = $_SESSION['Cod_Cliente'];
    $SELECT = "SELECT DISTINCT cli.* FROM Cliente cli, Facturas fac 
        WHERE fac.Cod_Factura = '$Cod_Factura' 
        AND fac.Cod_Cliente = cli.Cod_Cliente 
        AND cli.Cod_Cliente = '$Cod_Cliente'
        ;"
    ;
    $Array = mysqli_query($Conexion, $SELECT);
    $SELECT2 = "SELECT DISTINCT pro.*, par.*, fac.Coste, fac.Cod_Factura FROM Facturas fac , Producto pro, Participan par
        WHERE fac.Cod_Factura = par.Cod_Factura
        AND par.Cod_Producto = pro.Cod_Producto
        AND fac.Cod_Factura = '$Cod_Factura'
        ;"
    ;
    $Array2 = mysqli_query($Conexion, $SELECT2);

    foreach($Array as $Valor){
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
                    <div id="Pagar">
                    <h1>Gracias por su compra</h1>
                        <div id="Nombre">
                            <p><b>Nombre </b><?php echo $Valor['Nombre']; ?></p>
                        </div>
                        <div id="Apellidos">
                            <p><b>Apellidos </b><?php echo $Valor['Apellidos']; ?></p>
                        </div>
                        <div id="Correo">
                            <p><b>Correo </b><?php echo $Valor['Correo']; ?></p>
                        </div>
                        <table>
                            <tr>
                                <td>Nombre</td>
                                <td>Precio</td>
                                <td>Cantidad</td>
                                <td>Rebaja</td>
                                <td>Precio Total</td>
                            </tr>
            <?php
    }
                foreach($Array2 as $Valor2){
            ?>  <tr>
                    <td><?php echo $Valor2['Nombre']?></td>
                    <td><?php echo $Valor2['Precio']." (U o Kg)"?></td>
                    <td><?php echo $Valor2['Cantidad']?></td>
                    <?php
                        $Rebaja = $Valor2['Cod_Rebaja'];
                        $SELECT = "SELECT * FROM Rebaja WHERE Cod_Rebaja = '$Rebaja'";
                        $array = mysqli_query($Conexion, $SELECT);
                        foreach($array as $Valor3){
                            echo "<td>".$Valor3['Nombre']."</td>";
                        }
                    ?>
                    <td>
                        <?php 
                            if($Valor2['Cod_Rebaja'] == '2'){
                                if($Valor2['Cantidad'] % 2 == 0){
                                    echo $Valor2['Cantidad']/2*$Valor2['Precio']." €";
                                }
                            }elseif($Valor2['Cod_Rebaja'] == '3'){
                                if($Valor2['Cantidad'] % 3 == 0){
                                    echo ($Valor2['Cantidad']/3)*2*$Valor2['Precio']." €";
                                }
                            }elseif($Valor2['Cod_Rebaja'] == '4'){
                                echo ($Valor2['Cantidad']*$Valor2['Precio'])+($Valor2['Precio']*0.1)." €";
                            }elseif($Valor2['Cod_Rebaja'] == '5'){
                                echo ($Valor2['Cantidad']*$Valor2['Precio'])+($Valor2['Precio']*0.3)." €";
                            }elseif($Valor2['Cod_Rebaja'] == '6'){
                                echo ($Valor2['Cantidad']*$Valor2['Precio'])+($Valor2['Precio']*0.5)." €";
                            }elseif($Valor2['Cod_Rebaja'] == '7'){
                                echo ($Valor2['Cantidad']*$Valor2['Precio'])+($Valor2['Precio']*0.7)." €";
                            }else{
                                echo $Valor2['Cantidad']*$Valor2['Precio']." €";
                            }
                        ?>
                    </td>
                </tr>
            <?php
    }
?>
                        </table>
                        <p><b>Coste Total: </b><?php echo $Valor2['Coste'];?></p>
                        <p><b>Forma de pago: </b></p>
                        <?php
                            $array = "SELECT * FROM pago";
                            $Option = mysqli_query($Conexion, $array);
                            foreach($Option as $valor){
                                echo "<input type='radio' name='radio' value='".$valor['Cod_Pago']."'>".$valor['Nombre'];
                            }
                        ?>
                    </div>
                    <div id="Boton">
                        <input type="submit" name="Finalizar" value="Finalizar compra">
                    </div>
                </form>
            </body>
        </html>