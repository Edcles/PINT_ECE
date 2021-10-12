<?php
    require("./php/conexion.php");
    $BD = "PFC_Eduardo";
    
    $C_BD = "CREATE DATABASE $BD;";

    if ($Conexion){
        mysqli_query($Conexion, $C_BD);
        if (mysqli_select_db($Conexion, $BD)){

            $C_T_Proveedor = "CREATE TABLE Proveedor (
                Cod_Proveedor INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Nombre VARCHAR(30)
            );";
            mysqli_query($Conexion, $C_T_Proveedor);

            $C_T_Pago = "CREATE TABLE Pago (
                Cod_Pago INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Nombre VARCHAR(30)
            );";
            mysqli_query($Conexion, $C_T_Pago);

            $C_T_Rebaja = "CREATE TABLE Rebaja (
                Cod_Rebaja INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Nombre VARCHAR(30)
            );";
            mysqli_query($Conexion, $C_T_Rebaja);

            $C_T_Categorias = "CREATE TABLE Categorias (
                Cod_Categoria INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Nombre VARCHAR(30)
            );";
            mysqli_query($Conexion, $C_T_Categorias);
            
            $C_T_Producto = "CREATE TABLE Producto (
                Cod_Producto INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Nombre VARCHAR(30),
                Precio DECIMAL(10,2),
                Fecha_de_caducidad DATE,
                Cod_Categoria INT,
                Stock INT,
                Cod_Proveedor INT,
                Cod_Rebaja INT,
                FOREIGN KEY Cod_Categoria(Cod_Categoria) REFERENCES Categorias(Cod_Categoria),
                FOREIGN KEY Cod_Proveedor(Cod_Proveedor) REFERENCES Proveedor(Cod_Proveedor),
                FOREIGN KEY Cod_Rebaja(Cod_Rebaja) REFERENCES Rebaja(Cod_Rebaja)
            );";
            mysqli_query($Conexion, $C_T_Producto);

            $C_T_Cliente = "CREATE TABLE Cliente (
                Cod_Cliente INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
                Nombre VARCHAR(20), 
                Apellidos VARCHAR(40),
                Contraseña VARCHAR(30),
                Correo VARCHAR(50),
                Direccion VARCHAR(100)
            );";
            mysqli_query($Conexion, $C_T_Cliente);

            $C_T_Trabajador = "CREATE TABLE Trabajador(
                Cod_Trabajador INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Nombre VARCHAR(20),
                Apellidos VARCHAR(40),
                Contraseña VARCHAR(30),
                Correo VARCHAR(50)
            );";
            mysqli_query($Conexion, $C_T_Trabajador);

            $C_T_Facturas = "CREATE TABLE Facturas(
                Cod_Factura INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                Cod_Cliente INT,
                Fecha DATETIME,
                Coste DECIMAL(10,2),
                Cod_Pago INT,
                FOREIGN KEY Cod_Cliente(Cod_Cliente) REFERENCES Cliente(Cod_Cliente),
                FOREIGN KEY Cod_Pago(Cod_Pago) REFERENCES Pago(Cod_Pago)
            );";
            mysqli_query($Conexion, $C_T_Facturas);

            $C_T_Participan = "CREATE TABLE Participan(
                Cod_Producto INT,
                Cod_Factura INT,
                Cantidad INT,
                FOREIGN KEY Cod_Producto(Cod_Producto) REFERENCES Producto(Cod_Producto),
                FOREIGN KEY Cod_Factura(Cod_Factura) REFERENCES Facturas(Cod_Factura)
            );";
            mysqli_query($Conexion, $C_T_Participan);

            $C_T_Inscribe = "CREATE TABLE Inscribe(
                Codigo_Trabajador INT,
                Codigo_Producto INT,
                FOREIGN KEY Codigo_Producto(Codigo_Producto) REFERENCES Producto(Cod_Producto),
                FOREIGN KEY Codigo_Trabajador(Codigo_Trabajador) REFERENCES Trabajador(Cod_Trabajador)
            );";
            mysqli_query($Conexion, $C_T_Inscribe);

            //DATOS

            $D_T_Proveedor = "INSERT INTO Proveedor(Nombre) VALUES ('COCACOLA'),
               ('PEPSI'),
               ('FRUTERIAS PACO'),
               ('PESCANOVA'),
               ('BARCELÓ'),
               ('OREO'),
               ('NESTLÉ'),
               ('FANTA'),
               ('BEEFEATER')
            ;";
            mysqli_query($Conexion, $D_T_Proveedor);

            $D_T_Pago = "INSERT INTO Pago(Nombre) VALUES ('Metalico'),
               ('Tarjeta'),
               ('Paypal')
            ;";
            mysqli_query($Conexion, $D_T_Pago);

            $D_T_Categorias = "INSERT INTO Categorias(Nombre) VALUES ('REFRESCOS'),
                ('BEBIDAS ALCOHÓLICAS'),
                ('PESCADO'),
                ('FRUTAS'),
                ('CHOCOLATE')
            ;";
            mysqli_query($Conexion, $D_T_Categorias);

            $D_T_Rebaja = "INSERT INTO Rebaja(Cod_Rebaja, Nombre)
            VALUES ('1', 'Nada'),
                ('2', '2x1'),
                ('3', '3x2'),
                ('4', '10%'),
                ('5', '30%'),
                ('6', '50%'),
                ('7', '70%')
            ;";
            mysqli_query($Conexion, $D_T_Rebaja);

            $D_T_Productos = "INSERT INTO Producto(Cod_Producto, Nombre, Precio, Fecha_de_caducidad, Cod_Categoria, Stock, Cod_Proveedor, Cod_Rebaja)
                VALUES ('1', 'Producto no Disponible', NULL, NULL, NULL, NULL, NULL, '1'),
                ('2', 'COCA-COLA', '1.25', '2021-12-01', '1', '20', '1', '2'),
                ('3', 'COCA-COLA 0', '1.50', '2021-12-01', '1', '10', '1', '3'),
                ('4', 'COCA-COLA LIGHT', '1.50', '2021-12-01', '1', '20', '1', '4'),
                ('5', 'PEPSI', '1.25', '2021-12-01', '1', '20', '2', '5'),
                ('6', 'PEPSI 0', '1.30', '2021-12-01', '1', '20', '2', '6'),
                ('7', 'MANZANAS', '2', '2021-12-01', '4', '20', '3', '7'),
                ('8', 'PLATANOS', '1.50', '2021-12-01', '4', '20', '3', '1'),
                ('9', 'SANDIA', '2.95', '2021-12-01', '4', '12', '3', '1'),
                ('11', 'ARANDANOS', '2.25', '2021-12-01', '3', '20', '4', '1'),
                ('12', 'FRAMBUESA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('13', 'FRESA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('14', 'GROSELLA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('15', 'ZARZAMORA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('16', 'LIMON', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('17', 'MANDARINA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('18', 'NARANJA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('19', 'POMELO', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('20', 'MELON', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('21', 'AGUACATE', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('22', 'CHIRIMOYA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('23', 'COCO', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('24', 'DATIL', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('25', 'KIWI', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('26', 'MANGO', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('27', 'PAPAYA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('28', 'PIÑA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('29', 'ALBARICOQUE', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('30', 'CEREZA', '2.25', '2021-12-01', '4', '20', '3', '1'),
                ('10', 'HIGO', '1.25', '2021-12-01', '4', '20', '3', '1')
            ;";
            mysqli_query($Conexion, $D_T_Productos);

            $D_T_Cliente = "INSERT INTO Cliente(Nombre, Apellidos, Contraseña, Correo)
            VALUES ('CLIENTE', 'CLIENTE', '1', 'cliente@cliente.es')
            ;";
            mysqli_query($Conexion, $D_T_Cliente);

            $D_T_Trabajador = "INSERT INTO Trabajador(Nombre, Apellidos, Contraseña, Correo)
            VALUES ('TRABAJADOR', 'TRABAJADOR', '1', 'trabajador@trabajador.es')
            ;";
            mysqli_query($Conexion, $D_T_Trabajador);

            echo "Base de datos y tablas creadas"; 
           
        } else {
            echo "No se puede seleccionar la base de datos";
        }
    } else {
        echo "No conectado con la base de datos";
    }
?>