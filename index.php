<?php
    require("./php/conexion.php");
    session_start();

    if (isset($_POST['Registrar'])){
        header('location:./php/registrar.php');
    }
    if (isset($_POST['Entrar'])){
        mysqli_select_db($Conexion, $BD);
        $User = $_POST['usuario'];
        $Password = $_POST['password'];

        $Buscarcliente = "SELECT *
            FROM Cliente 
            WHERE (Nombre LIKE '$User' 
                OR Correo LIKE '$User')
                AND Contraseña = '$Password'
            ;"
        ;$Buscartrabajador = "SELECT * 
            FROM Trabajador 
            WHERE (Nombre LIKE '$User' 
                OR Correo LIKE '$User')
                AND Contraseña = '$Password'
            ;"
        ;
        $Respuesta = mysqli_query($Conexion, $Buscarcliente);
        foreach($Respuesta as $Valor){
            $_SESSION['Cod_Cliente'] = $Valor['Cod_Cliente'];
        } 
        $Respuesta2 = mysqli_query($Conexion, $Buscartrabajador);
            foreach($Respuesta2 as $Valor){
                $_SESSION['Cod_Trabajador'] = $Valor['Cod_Trabajador'];
            } 
        if(mysqli_num_rows($Respuesta)>0){
            header('location:./php/cliente.php');
        }elseif(mysqli_num_rows($Respuesta2)>0){
            header('location:./php/trabajador.php');
        }else{
            echo '<script language="javascript">alert("Cliente no encontrado");</script>'; 
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Eduardo Claros</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="./css/index.css">
    </head>
    <body>
        <div class="login-box">
            <form method="POST" action="">
                <h1>Merca-Morón</h1>
                <div class="input" id="Nombre">
                    <b>Email</b><br>
                    <input type="text" name="usuario" placeholder="Nombre/Email">
                </div>
                <div class="input" id="Contraseña">
                    <b>Contraseña</b><br>
                    <input type="password" name="password" placeholder="Contraseña">
                </div>
                <div class="input" id="Botonera">
                    <input type="submit" name="Entrar" value="Entrar">
                    <br>
                    <input type="submit" name="Registrar" value="Registrar">
                </div>
            </form>
        </div>
    </body>
</html>