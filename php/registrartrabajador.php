<?php
    require("./conexion.php");
    require("./funciones.php");
    session_start();
    if(isset($_SESSION['EditarTrabajador'])){
        $Editar = $_SESSION['EditarTrabajador'];
        $Cod_Trabajador = $_SESSION['Cod_Trabajador'];
    }elseif(isset($_SESSION['AgregarTrabajador'])){
        $Editar=0;
        $Cod_Cliente=0;
        $_SESSION['EditarTrabajador']=0;
    }else{
        $Editar=0;
        $Cod_Cliente=0;
    }
    if($Editar){
        $Cargar = "SELECT TRIM(Nombre) AS Nombre,
            TRIM(Apellidos) AS Apellidos,
            TRIM(Contraseña) AS Contraseña,
            TRIM(Correo) AS Correo
        FROM Trabajador WHERE Cod_Trabajador = '$Cod_Trabajador';";
        $Busqueda = mysqli_query($Conexion, $Cargar);
        $row = mysqli_fetch_assoc($Busqueda);
    }
    if(isset($_POST['Registrar'])){
        $Nombre = $_POST['Nombre'];
        $Apellidos = $_POST['Apellidos'];
        $Contraseña = $_POST['Contraseña'];
        $Email = $_POST['Email'];

        if (ComprobarCorreo($Email)) {
            $Registrar = "INSERT INTO Trabajador(Nombre, Apellidos, Contraseña, Correo)
                VALUES ('$Nombre',
                '$Apellidos',
                '$Contraseña',
                '$Email'
                );"
            ;
            mysqli_query($Conexion, $Registrar);
            header('location:./trabajador.php');
        } else {
            echo '<script language="javascript">alert("Este correo ya tiene una cuenta de usuario");</script>'; 
        };
    }
    if(isset($_POST['Editado'])){
        $Nombre = $_POST['Nombre'];
        $Apellidos = $_POST['Apellidos'];
        $Contraseña = $_POST['Contraseña'];
        $Email = $_POST['Email'];
        $Update = "UPDATE Trabajador 
            SET Nombre='$Nombre',
            Apellidos='$Apellidos', 
            Contraseña='$Contraseña', 
            Correo='$Email'
            WHERE Cod_Trabajador='$Cod_Trabajador'
            ;"
        ;
        mysqli_query($Conexion, $Update);
        header("location:./trabajador.php");
    }
    if(isset($_POST['Eliminar'])){
        $DELETE = "DELETE FROM Inscribe WHERE Codigo_Trabajador = '$Cod_Trabajador';";
        mysqli_query($Conexion, $DELETE);
        $DELETE2 = "DELETE FROM Trabajador WHERE Cod_Trabajador = '$Cod_Trabajador';";
        mysqli_query($Conexion, $DELETE2);
        session_destroy();
        header('Refresh:0; url=../index.php');
    }
    if(isset($_POST['ProductosInsertados'])){
        header('location: ./productos.php');
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
            <h1 id="Titulo">Registrar Trabajador</h1>
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
                                echo "type='password' placeholder='Contraseña'";
                            }
                        ?>
                    >
                </div>
                <div class="input" id="Email">
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
                            <input type="submit" name="ProductosInsertados" value="Productos Insertados">                        
                        </form>
                            <form method="POST" action="./trabajador.php">
                            <input type="submit" name="Cancelar" value="Cancelar">
                        </form>
                    <?php
                        } else {
                    ?>
                                <input type="submit" name="Registrar" value="Registrar">
                            </form>
                            <form method="POST" action="./trabajador.php">
                                <input type="submit" name="Cancelar" value="Cancelar">
                            </form>
                    <?php
                        }
                    ?>
                </div>
            </form>
        </div>
    </body>
</html>