<?php
    require("./conexion.php");
    require("./funciones.php");
    session_start();

    if(isset($_SESSION['Editar'])){
        $Editar = $_SESSION['Editar'];
        $Cod_Cliente = $_SESSION['Cod_Cliente'];
    }else{
        $Editar=0;
        $Cod_Cliente =0;
    }
    if($Editar){
        $Cargar = "SELECT TRIM(Nombre) AS Nombre,
            TRIM(Apellidos) AS Apellidos,
            TRIM(Contraseña) AS Contraseña,
            TRIM(Correo) AS Correo
        FROM Cliente WHERE Cod_Cliente = '$Cod_Cliente';";
        $Busqueda = mysqli_query($Conexion, $Cargar);
        $row = mysqli_fetch_assoc($Busqueda);
    }
    if(isset($_POST['Registrar'])){
        $Nombre = $_POST['Nombre'];
        $Apellidos = $_POST['Apellidos'];
        $Contraseña = $_POST['Contraseña'];
        $Email = $_POST['Email'];
        if (ComprobarCorreo($Email)) {
            $Registrar = "INSERT INTO Cliente(Nombre, Apellidos, Contraseña, Correo)
                VALUES ('$Nombre',
                '$Apellidos',
                '$Contraseña',
                '$Email'
                );"
            ;
            mysqli_query($Conexion, $Registrar);
            header('location:../index.php');
        } else {
            echo '<script language="javascript">alert("Este correo ya tiene una cuenta de usuario");</script>'; 
        };
    }
    if(isset($_POST['Editado'])){
        $Nombre = $_POST['Nombre'];
        $Apellidos = $_POST['Apellidos'];
        $Contraseña = $_POST['Contraseña'];
        $Email = $_POST['Email'];
        $Update = "UPDATE Cliente 
            SET Nombre='$Nombre',
            Apellidos='$Apellidos', 
            Contraseña='$Contraseña', 
            Correo='$Email'
            WHERE Cod_Cliente='$Cod_Cliente'
            ;"
        ;
        mysqli_query($Conexion, $Update);
        header("location:./cliente.php");
    }
    if(isset($_POST['Eliminar'])){
        $SELECT = "SELECT Cod_Factura FROM Facturas WHERE Cod_Cliente = '$Cod_Cliente';";
        $Cod = mysqli_query($Conexion, $SELECT);
        
        while($Cod_Factura = mysqli_fetch_assoc($Cod)){
            $DELETE = "DELETE FROM Participan WHERE Cod_Factura = '".$Cod_Factura['Cod_Factura']."';";
            mysqli_query($Conexion, $DELETE);
        }
        $DELETE2 = "DELETE FROM Facturas WHERE Cod_Cliente = '$Cod_Cliente';";
        mysqli_query($Conexion, $DELETE2);
        $DELETE3 = "DELETE FROM Cliente WHERE Cod_Cliente = '$Cod_Cliente';";
        mysqli_query($Conexion, $DELETE3);
        session_destroy();
        header('Refresh:0; url=../index.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registro</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/index.css">
    </head>
    <body>
        <div class="register-box">
            <h1>Registrar Cliente</h1>
            <form method="POST" action="">
            <div class="input" id="Nombre">
                <b>Nombre</b>
                <input type="text" name="Nombre"
                    <?php
                        if($Editar){
                            echo "value=".$row['Nombre'];
                        } else {
                            echo "placeholder='Nombre'";
                        }
                        ?>
                >
            </div>
            <div class="input" id="Apellidos">
                <b>Apellidos</b>
                <input type="text" name="Apellidos"
                    <?php
                        if($Editar){
                            echo "value=".$row['Apellidos'];
                        } else {
                            echo "placeholder='Apellidos'";
                        }
                        ?>
                >
            </div>
            <div class="input" id="Contraseña">
                <b>Contraseña</b>
                <input name="Contraseña"
                    <?php
                        if($Editar){
                            echo "value=".$row['Contraseña'];
                        } else {
                            echo "placeholder='Contraseña'";
                            echo "type='password'";
                        }
                        ?>
                >
            </div>
            <div class="input" id="Correo">
                <b>Email</b>
                <input type="email" name="Email"
                    <?php
                        if($Editar){
                            echo "value=".$row['Correo'];
                        } else {
                            echo "placeholder='Correo'";
                        }
                        ?>
                >
            </div>
            <div class="input" id="botonera">
                <?php
                        if($Editar){
                            ?>
                            <input type="submit" name="Editado" value="Editar">
                            <input type="submit" name="Eliminar" value="Eliminar">
                            </form>
                            <form method="POST" action="./Cliente.php">
                                <input type="submit" name="Cancelar" value="Cancelar">
                            </form>
                            <?php
                        } else {
                            ?>
                            <input type="submit" name="Registrar" value="Registrar">
                            </form>
                            <form method="POST" action="../index.php">
                                <input type="submit" name="Cancelar" value="Cancelar">
                            </form>
                            <?php
                        }
                        ?>
            </div>
        </div>
    </body>
</html>