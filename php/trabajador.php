<?php
    require('./conexion.php');
    require('./funciones.php');
    session_start();
    $Cod_Trabajador = $_SESSION['Cod_Trabajador'];

    if(isset($_POST['EditarTrabajador'])){
        session_start();
        $_SESSION['AgregarTrabajador'] = 0;
        $_SESSION['EditarTrabajador'] = $_POST['EditarTrabajador'];
        header('location:./registrartrabajador.php');
    }
    if(isset($_POST['AgregarTrabajador'])){
        session_start();
        $_SESSION['EditarTrabajador'] = 0;
        $_SESSION['AgregarTrabajador'] = $_POST['AgregarTrabajador'];
        header('location:./registrartrabajador.php');
    }
    if(isset($_POST['Insertar'])){
        $Nombredelproducto = $_POST['Nombredelproducto'];
        $Codigodelproducto = CodigoProducto();
        $Categoria = $_POST['Categoria'];
        $Proveedor = $_POST['Proveedor'];
        $Precio = $_POST['Precio'];
        $Fecha_de_caducidad = $_POST['Fechadecaducidad'];
        $Stock = $_POST['Stock'];
        $Rebaja = $_POST['radio'];
        $Cod_Trabajador = $_SESSION['Cod_Trabajador'];

        if(!ComprobarProducto($Nombredelproducto, $Proveedor)){
            echo '<script language="javascript">alert("Este producto ya está en la base de datos");</script>'; 
        } else {
            InsertarProducto($Nombredelproducto, $Codigodelproducto, $Categoria, $Proveedor, $Precio, $Fecha_de_caducidad, $Stock, $Rebaja, $Cod_Trabajador);
        }
    }
    if(isset($_POST['Limpiar'])){
        header('Refresh:0');
    }
    if(isset($_POST['AgregarCategoria2'])){
        $NuevaCategoria = $_POST['NuevaCategoria'];
        NuevaCategoria($NuevaCategoria);
    }
    if(isset($_POST['AgregarProveedor2'])){
        $NuevoeProvedor = $_POST['NuevoProveedor'];
        NuevoProveedor($NuevoeProvedor);
    }
    if(isset($_POST['Buscar'])){
        $Nombredelproducto = $_POST['Nombredelproducto'];
        $Categoria = $_POST['Categoria'];
        $Proveedor = $_POST['Proveedor'];
        $Precio = $_POST['Precio'];
        $Fecha_de_caducidad = $_POST['Fechadecaducidad'];
        $Stock = $_POST['Stock'];
        $array = BuscarProducto($Nombredelproducto, $Categoria, $Proveedor, $Precio, $Fecha_de_caducidad, $Stock);
    }else{
        Listar();
        ProductosSinStock();
    }
    if(isset($_POST['Modificar'])){
        $Nombredelproducto = $_POST['Nombredelproducto'];
        $Codigodelproducto = $_POST['Codigodelproducto'];
        $Categoria = $_POST['Categoria'];
        $Proveedor = $_POST['Proveedor'];
        $Precio = $_POST['Precio'];
        $Fecha_de_caducidad = $_POST['Fechadecaducidad'];
        $Stock = $_POST['Stock'];
        $Rebaja = $_POST['radio'];
        ModificarProducto($Nombredelproducto, $Codigodelproducto, $Categoria, $Proveedor, $Precio, $Fecha_de_caducidad, $Stock, $Rebaja);
        print "<script>window.setTimeout(function() { window.location = './trabajador.php' }, 0);</script>";
    }
    if(isset($_POST['Eliminar'])){
        $Codigodelproducto = $_POST['Codigodelproducto'];
        
        EliminarProducto($Codigodelproducto);

    }
    if(isset($_POST['EliminarP'])){
        $Cod = $_POST['Cod'];
        
        EliminarProducto($Cod);

    }
    if(isset($_POST['Salir'])){
        session_destroy();
        print "<script>window.setTimeout(function() { window.location = '../index.php' }, 0);</script>";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registro</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/trabajador.css">
    </head>
    <body>
        <div class="Registro">
            <form name="form" method="POST" action="">
            <?php
            if(isset($_POST['Cargar'])){
                $Cod = $_POST['Cod'];
                $Cargar = "SELECT TRIM(Cod_Producto) AS Cod_Producto,
                    TRIM(Nombre) AS Nombre,
                    TRIM(Precio) AS Precio,
                    TRIM(Fecha_de_caducidad) AS Fecha_de_caducidad,
                    TRIM(Cod_Categoria) AS Cod_Categoria,
                    TRIM(Stock) AS Stock,
                    TRIM(Cod_Proveedor) AS Cod_Proveedor,
                    TRIM(Cod_Rebaja) AS Cod_Rebaja
                    FROM Producto WHERE Cod_Producto = '$Cod'
                    ;"
                ;
                $Busqueda = mysqli_query($Conexion, $Cargar);
                if(mysqli_num_rows($Busqueda) > 0){
                    $row = mysqli_fetch_assoc($Busqueda);
                } else {
                    echo '<script language="javascript">alert("No hay datos que correspondan con esa busqueda");</script>'; 
                }
            }
            ?>
                <h1>Insertar Productos:</h1>
                <div class="input" id="Nombre_del_producto">
                    <b>Nombre del Producto:</b>
                    <input type="text" name="Nombredelproducto" 
                        <?php 
                            if(isset($_POST['Cargar'])){
                                echo "value=".$row['Nombre'];
                            }else{
                                echo "placeholder='Nombre del Producto'";
                            }
                        ?>
                    >
                </div>
                <div class="input" id="Codigo_del_producto">
                    <b>Código del Producto:</b>
                    <input size="1px" type="text" name="Codigodelproducto" 
                        <?php 
                            if(isset($_POST['Cargar'])){
                                echo "value=".$row['Cod_Producto'];
                            } else {
                                echo "value=".CodigoProducto();
                            }
                        ?>
                    >
                </div>
                <div class="input" id="Categoría_">
                    <b>Categoría</b>
                    <select name="Categoria">
                        <option>
                        <?php 
                            if(isset($_POST['Cargar'])){
                                echo CambioCategoria($row['Cod_Categoria']);
                            } else {
                                echo "---";
                            }
                        ?>
                        </option>
                        <?php
                            $array = "SELECT * FROM categorias;";
                            $Option = mysqli_query($Conexion, $array);
                            foreach($Option as $Clave){
                                ?>
                                    <option value="<?php echo $Clave['Cod_Categoria'];?>">
                                        <?php echo $Clave['Nombre'];?>
                                    </option>
                                <?php 
                            }
                            if(isset($_POST['AgregarCategoria'])){
                                ?>
                                    <input type="text" name="NuevaCategoria" placeholder="Nueva Categoria">
                                    <input type="submit" name="AgregarCategoria2" value="Agregar Categoria">
                                <?php
                            } else {
                                ?>
                                    <input type="submit" name="AgregarCategoria" value="Agregar Categoria">
                                <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="input" id="Proveedor_">
                    <b>Proveedor</b>
                    <select name="Proveedor">
                        <option>
                        <?php 
                            if(isset($_POST['Cargar'])){
                                echo CambioProveedor($row['Cod_Proveedor']);
                            } else {
                                echo "---";
                            }
                        ?>
                        </option>
                        <?php
                            $array = "SELECT * FROM proveedor;";
                            $Option = mysqli_query($Conexion, $array);
                            foreach($Option as $Clave){
                                ?>
                                    <option value="<?php echo $Clave['Cod_Proveedor'];?>">
                                        <?php echo $Clave['Nombre'];?>
                                    </option>
                                <?php 
                            }
                        ?>
                        <?php
                            if(isset($_POST['AgregarProveedor'])){
                                ?>
                                    <input type="text" name="NuevoProveedor" placeholder="Nuevo Proveedor">
                                    <input type="submit" name="AgregarProveedor2" value="Agregar Proveedor">
                                <?php
                            } else {
                                ?>
                                    <input type="submit" name="AgregarProveedor" value="Agregar Proveedor">
                                <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="input" id="Precio_">
                    <b>Precio</b>
                    <input type="number" name="Precio" step="any" min="0"
                        <?php
                            if(isset($_POST['Cargar'])){
                                echo "value=".$row['Precio'];
                            } else {
                                echo "placeholder='0'";
                            }
                        ?>
                    >
                </div>
                <div class="input" id="Fecha_de_caducidad">
                    <b>Fecha de Caducidad</b>
                    <input type="date" name="Fechadecaducidad"
                        <?php
                            if(isset($_POST['Cargar'])){
                                echo "value=".$row['Fecha_de_caducidad'];
                            } else {
                                echo date("d, n, Y");
                            }
                        ?>
                    >
                </div>
                <div class="input" id="Stock_">
                    <b>Stock</b>
                    <input type="number" name="Stock" min="0" 
                        <?php
                            if(isset($_POST['Cargar'])){
                                echo "value=".$row['Stock'];
                            } else {
                                echo "value='1'";
                            }
                        ?>
                    >
                </div>
                <div class="input" id="Rebaja">
                    <b>Rebaja</b>
                    <?php
                        $array = "SELECT * FROM rebaja";
                        $Option = mysqli_query($Conexion, $array);
                        foreach($Option as $valor){
                            echo "<input type='radio' name='radio' value='".$valor['Cod_Rebaja']."'";
                            if(isset($_POST['Cargar'])){
                                    if($row['Cod_Rebaja'] == $valor['Cod_Rebaja']){
                                        echo "checked";
                                    }
                                }
                            echo ">".$valor['Nombre'];
                        }

                    ?>
                </div>
                <div class="input" id="botonera">
                    <input type="submit" name="Insertar" value="Insertar">
                    <?php
                        if(isset($_POST['Cargar'])){
                            echo "<input type='submit' name='Limpiar' value='Limpiar'>";
                        }else{
                            echo "<input type='submit' name='Buscar' value='Buscar'>";
                        }
                    ?>
                    <input type="submit" name="Modificar" value="Modificar">
                    <input type="submit" name="EditarTrabajador" value="Editar Trabajador">
                    <?php
                        if($_SESSION['Cod_Trabajador'] == 1){
                            echo "<input type='submit' name='AgregarTrabajador' value='Agregar Trabajador'>";
                        }
                    ?>
                    </form>
                    <form method="POST" action="">
                        <input type="submit" name="Salir" value="Salir">
                    </form>
                </div>
        </div>
    </body>
</html>