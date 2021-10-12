<?php
    $host = "localhost";
    $user = "root";
    $pwd = "2asir21";
    $BD = "PFC_EDUARDO";
    $Conexion = mysqli_connect($host, $user, $pwd);
    mysqli_select_db($Conexion, $BD);
    global $BD;
?>