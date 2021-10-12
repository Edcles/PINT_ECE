<?php

    //Esta función comprueba si el correo usado se encuentra en alguna de las 2 tablas

    function ComprobarCorreo($Correo) {
        require("./conexion.php");

        $bool = FALSE;
        $ComprobarCliente = "SELECT * FROM cliente WHERE Correo = '$Correo';";
        $ComprobarTrabajador = "SELECT * FROM trabajador WHERE Correo = '$Correo';";

        $BusquedaCliente = mysqli_query($Conexion, $ComprobarCliente);
        $BusquedaTrabajador = mysqli_query($Conexion, $ComprobarTrabajador);
        $array = mysqli_fetch_assoc($BusquedaTrabajador);

        if ((mysqli_num_rows($BusquedaCliente) == 0) && (mysqli_num_rows($BusquedaTrabajador) == 0) && ($array['Nombre'] <> 'Trabajador' || $array['Nombre'] <> 'TRABAJADOR')) {
            $bool = TRUE;
        }
        return $bool;
    }

    //Esta función comprueba el último codigo de producto para mostrarlo y asignarlo automaticamente

    function CodigoProducto(){
        require("./conexion.php");
        $Cod_Producto = "SELECT MAX(Cod_Producto) AS Cod_Producto FROM producto;";
        $N_Producto = 0;
        $B_C_P = mysqli_query($Conexion, $Cod_Producto);
        if (!$B_C_P){
            $N_Producto = 0;
        }else {
            foreach($B_C_P as $valor){
                $N_Producto = $valor['Cod_Producto'];
            }
        }
        $N_Producto+=1;
        return $N_Producto;
    }

    //Esta función inserta una categoria nueva despues de comprovar que dicha categoria
    //no se encuentra en la base de datos

    function NuevaCategoria($Nombre){
        require("./conexion.php");

        $BuscarCategoria = "SELECT * FROM categorias WHERE Nombre like UPPER('$Nombre');";
        $Busqueda = mysqli_query($Conexion, $BuscarCategoria);

        if(mysqli_num_rows($Busqueda) == 0){
            $Insertar = "INSERT INTO categorias(Nombre) VALUES (UPPER('$Nombre'));";
            mysqli_query($Conexion, $Insertar);
        } else {
            echo '<script language="javascript">alert("La categoria ya está en la base de datos");</script>'; 
        }
    }

    //Esta función inserta un proveedor nuevo despues de comprobar que dicho proveedor 
    //no se encuentra en la base de datos

    function NuevoProveedor($Nombre){
        require("./conexion.php");

        $BuscarProveedor = "SELECT * FROM proveedor WHERE Nombre LIKE UPPER('$Nombre');";
        $Busqueda = mysqli_query($Conexion, $BuscarProveedor);
        
        if(mysqli_num_rows($Busqueda) == 0){
            $Insertar = "INSERT INTO proveedor(Nombre) VALUES (UPPER('$Nombre'));";
            mysqli_query($Conexion, $Insertar);
        } else {
            echo '<script language="javascript">alert("El proveedor ya está en la base de datos");</script>'; 
        }
    }

    //Esta función comprueba si este producto ya existe

    function ComprobarProducto($Nombre, $Proveedor){
        require("./conexion.php");
        $bool = True;
        $Busqueda = "SELECT * 
            FROM producto 
            WHERE Nombre LIKE '$Nombre' 
            AND Cod_Proveedor = (
                SELECT Cod_Proveedor 
                    FROM proveedor 
                    WHERE Nombre LIKE '$Proveedor'
            );"
        ;
        $B_Producto = mysqli_query($Conexion, $Busqueda);

        if(mysqli_num_rows($B_Producto) != 0){
            $bool = False;
        }

        return $bool;
    }

    //Esta función inserta el producto en la base de datos

    function InsertarProducto($Nombredelproducto, $Codigodelproducto, $Categoria, $Proveedor, $Precio, $Fecha_de_caducidad, $Stock, $Rebaja, $Cod_Trabajador){
        require("./conexion.php");

        $Insertar = "INSERT INTO producto VALUES (
            '$Codigodelproducto',
            UPPER('$Nombredelproducto'),
            '$Precio',
            '$Fecha_de_caducidad',
            '$Categoria',
            '$Stock',
            '$Proveedor',
            '$Rebaja'
            );"
        ;
        $Insertar2 = "INSERT INTO Inscribe VALUES(
            '$Cod_Trabajador',
            '$Codigodelproducto'
            )"
        ;

        $Insercion = mysqli_query($Conexion, $Insertar);
        $Insercion2 = mysqli_query($Conexion, $Insertar2);

        if (!$Insercion && !$Insercion2){
            echo '<script language="javascript">alert("No se puede realizar la insercion");</script>'; 
        }
    }

    //Esta función busca los productos en la base de datos

    function BuscarProducto($Nombredelproducto, $Categoria, $Proveedor, $Precio, $Fecha_de_caducidad, $Stock){
        require("./conexion.php");
        $Buscar = "SELECT * 
            FROM producto 
            WHERE Nombre LIKE '$Nombredelproducto%' 
            OR Cod_Categoria = '$Categoria%'
            OR Cod_Proveedor = '$Proveedor%'
            OR Fecha_de_caducidad = '$Fecha_de_caducidad%'
            ;"
        ;
        if(!empty($Nombredelproducto)){
            $Buscar = "SELECT * FROM producto WHERE Nombre LIKE '$Nombredelproducto%';";
            $array = mysqli_query($Conexion, $Buscar);
        }elseif($Categoria <> "---"){
            $Buscar = "SELECT * FROM producto WHERE Cod_Categoria LIKE '$Categoria';";
            $array = mysqli_query($Conexion, $Buscar);
        }elseif($Proveedor <> "---"){
            $Buscar = "SELECT * FROM producto WHERE Cod_Proveedor LIKE '$Proveedor';";
            $array = mysqli_query($Conexion, $Buscar);
        }elseif(!empty($Fecha_de_caducidad)){
            $Buscar = "SELECT * FROM producto WHERE Fecha_de_caducidad LIKE '$Fecha_de_caducidad';";
            $array = mysqli_query($Conexion, $Buscar);
        }

        if(mysqli_num_rows($array) != 0){
            echo "<div class='productos'>";
            echo "<h2>Busqueda</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Codigo</th>";
            echo "<th>Nombre</th>";
            echo "<th>Categoria</th>";
            echo "<th>Proveedor</th>";
            echo "<th>Precio</th>";
            echo "<th>Fecha de caducidad</th>";
            echo "<th>Stock</th>";
            echo "</tr>";
            echo "<div class='Prod-box'>";
            foreach($array as $datos){
                echo "<form action='' method='POST'>";
                if($datos['Stock'] > 0){
                    echo "<tr>";
                        echo "<td>".$datos['Cod_Producto']."</td>";
                        echo "<td>".$datos['Nombre']."</td>";
                        echo "<td>".CambioCategoria($datos['Cod_Categoria'])."</td>";
                        echo "<td>".CambioProveedor($datos['Cod_Proveedor'])."</td>";
                        echo "<td>".$datos['Precio']."</td>";
                        echo "<td>".$datos['Fecha_de_caducidad']."</td>";
                        echo "<td>".$datos['Stock']."</td>";
                        echo "<td><input type='hidden' name='Cod' value='".$datos['Cod_Producto']."'>";
                        echo "<input type='submit' name='Cargar' value='Cargar'></td>";
                        echo "<td><input type='submit' name='EliminarP' value='Eliminar'></td>";
                    echo "</tr>";
                }
                echo "</form>";
            }
            echo "</div>";
            echo "</table>";
            echo "</div>";
        } else {
            echo '<script language="javascript">alert("No hay datos que correspondan con esa busqueda");</script>'; 
        }
    }

    //Esta función muestra los productos sin Stock

    function ProductosSinStock(){
        require("./conexion.php");

        $Busqueda = "SELECT * FROM Producto WHERE Stock = 0";
        $array = mysqli_query($Conexion, $Busqueda);
        if(mysqli_num_rows($array) != 0){
            echo "<div class='productos'>";
            echo "<h2>Sin Stock</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Codigo</th>";
            echo "<th>Nombre</th>";
            echo "<th>Categoria</th>";
            echo "<th>Proveedor</th>";
            echo "<th>Precio</th>";
            echo "<th>Fecha de caducidad</th>";
            echo "<th>Stock</th>";
            echo "</tr>";
            foreach($array as $datos){
                echo "<form action='' method='POST'>";
                echo "<tr>";
                    echo "<td>".$datos['Cod_Producto']."</td>";
                    echo "<td>".$datos['Nombre']."</td>";
                    echo "<td>".CambioCategoria($datos['Cod_Categoria'])."</td>";
                    echo "<td>".CambioProveedor($datos['Cod_Proveedor'])."</td>";
                    echo "<td>".$datos['Precio']."</td>";
                    echo "<td>".$datos['Fecha_de_caducidad']."</td>";
                    echo "<td>".$datos['Stock']."</td>";
                    echo "<td><input type='hidden' name='Cod' value='".$datos['Cod_Producto']."'>";
                    echo "<input type='submit' name='Cargar' value='Cargar'></td>";
                    echo "<td><input type='submit' name='EliminarP' value='Eliminar'></td>";
                echo "</tr>";
                echo "</form>";
            }
            echo "</table>";
            echo "</div>";
        }
    }

    //Esta función cambia el Cod_Categoria por el Nombre

    function CambioCategoria($Cod){
        require("./conexion.php");
        $cambio = "SELECT Nombre FROM Categorias WHERE Cod_Categoria = '$Cod';";
        $Cambio = mysqli_query($Conexion, $cambio);
        foreach($Cambio as $valor){
            $nombre = $valor['Nombre'];
        }
        return $nombre;
    }

    //Esta función cambia el Cod_Proveedor por el Nombre

    function CambioProveedor($Cod){
        require("./conexion.php");
        $cambio = "SELECT Nombre FROM Proveedor WHERE Cod_Proveedor = '$Cod';";
        $Cambio = mysqli_query($Conexion, $cambio);
        foreach($Cambio as $valor){
            $nombre = $valor['Nombre'];
        }
        return $nombre;
    }

    //Esta función cambia el Cod_Rebaja por el Nombre

    function CambioRebaja($Cod){
        require("./conexion.php");
        $cambio = "SELECT Nombre FROM Rebaja WHERE Cod_Rebaja = '$Cod';";
        $Cambio = mysqli_query($Conexion, $cambio);
        foreach($Cambio as $valor){
            $nombre = $valor['Nombre'];
        }
        return $nombre;
    }

    //Esta función modifica el producto

    function ModificarProducto($Nombredelproducto, $Codigodelproducto, $Categoria, $Proveedor, $Precio, $Fecha_de_caducidad, $Stock, $Rebaja){
        require("./conexion.php");
        if(!empty($Nombredelproducto)){
            $Update = "UPDATE producto SET Nombre = '$Nombredelproducto' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
        if(!empty($Categoria)){
            $Update = "UPDATE producto SET Cod_Categoria = '$Categoria' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
        if(!empty($Proveedor)){
            $Update = "UPDATE producto SET Cod_Proveedor = '$Proveedor' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
        if(!empty($Precio)){
            $Update = "UPDATE producto SET Precio = '$Precio' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
        if(!empty($Fecha_de_caducidad)){
            $Update = "UPDATE producto SET Fecha_de_caducidad = '$Fecha_de_caducidad' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
        if(!empty($Stock)){
            $Update = "UPDATE producto SET Stock = '$Stock' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
        if(!empty($Rebaja)){
            $Update = "UPDATE producto SET Cod_Rebaja = '$Rebaja' WHERE Cod_Producto = '$Codigodelproducto';";
            mysqli_query($Conexion, $Update);
        }
    }

    //Esta función elimina el producto

    function EliminarProducto($Codigodelproducto) {
        require("./conexion.php");
        
        $Eliminar = "DELETE FROM Inscribe WHERE Codigo_Producto = '$Codigodelproducto';";
        $Eliminar2 = "DELETE FROM Producto WHERE Cod_Producto = '$Codigodelproducto';";
        $Eliminar3 = "UPDATE Participan SET Cod_Producto = 1 WHERE Cod_Producto = '$Codigodelproducto'";

        mysqli_query($Conexion, $Eliminar3);
        mysqli_query($Conexion, $Eliminar);
        mysqli_query($Conexion, $Eliminar2);
    }

    //Esta función muestra todos los productos

    function TodosProductos(){
        require("./conexion.php");

        $Productos = "SELECT * FROM Producto WHERE Stock > 0;";
        $Array = mysqli_query($Conexion, $Productos);
        return $Array;
    }

    //Esta función filtra los productos

    function ProductosFiltrados($Proveedor, $Categoria, $Precio){
        require("./conexion.php");
        
        $Productos = "SELECT * FROM Producto WHERE Cod_Proveedor = '$Proveedor' OR Cod_Categoria = '$Categoria' OR precio BETWEEN 0 AND '$Precio';";
        $Array = mysqli_query($Conexion, $Productos);
        return $Array;
    }

    //Esta función lista todos los productos

    function Listar(){
        echo "<div class='productos'>";
        echo "<h2>Productos</h2>";
        echo "<table>";
            echo "<tr>";
                echo "<th>Codigo</th>";
                echo "<th>Nombre</th>";
                echo "<th>Categoria</th>";
                echo "<th>Proveedor</th>";
                echo "<th>Precio</th>";
                echo "<th>Fecha de caducidad</th>";
                echo "<th>Stock</th>";
                echo "<th>Rebaja</th>";
            echo "</tr>";
            echo "<div class='Prod-box'>";
        foreach(TodosProductos() as $datos){
            echo "<form action='' method='POST'>";
            if($datos['Stock'] == 0){
                echo "<tr>";
                    echo "<td><b><u>".$datos['Cod_Producto']."</u></b></td>";
                    echo "<td><b><u>".$datos['Nombre']."</u></b></td>";
                    echo "<td><b><u>".CambioCategoria($datos['Cod_Categoria'])."</u></b></td>";
                    echo "<td><b><u>".CambioProveedor($datos['Cod_Proveedor'])."</u></b></td>";
                    echo "<td><b><u>".$datos['Precio']."</u></b></td>";
                    echo "<td><b><u>".$datos['Fecha_de_caducidad']."</u></b></td>";
                    echo "<td><b><u>".$datos['Stock']."</u></b></td>";
                    echo "<td><b><u>".CambioRebaja($datos['Cod_Rebaja'])."</u></b></td>";
                    echo "<td><input type='hidden' name='Cod' value='".$datos['Cod_Producto']."'></td>";
                    echo "<td><input type='submit' name='Cargar' value='Cargar'></td>";
                echo "</tr>"; 
            } else {
                echo "<tr>";
                    echo "<td>".$datos['Cod_Producto']."</td>";
                    echo "<td>".$datos['Nombre']."</td>";
                    echo "<td>".CambioCategoria($datos['Cod_Categoria'])."</td>";
                    echo "<td>".CambioProveedor($datos['Cod_Proveedor'])."</td>";
                    echo "<td>".$datos['Precio']."</td>";
                    echo "<td>".$datos['Fecha_de_caducidad']."</td>";
                    echo "<td>".$datos['Stock']."</td>";
                    echo "<td>".CambioRebaja($datos['Cod_Rebaja'])."</td>";
                    echo "<td><input type='hidden' name='Cod' value='".$datos['Cod_Producto']."'>";
                    echo "<input type='submit' name='Cargar' value='Cargar'></td>";
                    echo "<td><input type='submit' name='EliminarP' value='Eliminar'></td>";
                echo "</tr>"; 
            }
            echo "</form>";
        }
        echo "</div>";
        echo "</table>";
        echo "</div>";
    }
?>