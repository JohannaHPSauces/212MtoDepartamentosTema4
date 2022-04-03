<?php
         /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/01/2021
             * Aplicacion LogIn-LogOut Tema5
             */
        if(isset($_REQUEST['Cancelar'])){
            header('Location: mtoDepartamentos.php');
            exit;
        }
        
        require_once '../core/210322ValidacionFormularios.php';
        require_once '../config/confDBPDO.php';
        
        
       $entradaOk = true;                                     
        
        //Array para los errores
        $aErrores = [  'codDepartamento' => null,               
                       'descDepartamento' => null,
                       'volumenNegocio' => null];
        
         //Si se ha pulsado el boton de enviar 
        if (isset($_REQUEST['Aceptar'])) {
            //Guardamos en las variables las respuestas del formulario
            $CodDepartamento=strtoupper($_REQUEST['codDepartamento']);      
            $DescDepartamento=$_REQUEST['descDepartamento'];
            $VolumenNegocio=$_REQUEST['volumenNegocio'];
            
            //Validamos las respuuestas con ayuda de la libreria de validacion
            $aErrores['codDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['codDepartamento'], 3, 3, 1); 
            $aErrores['descDepartamento'] = validacionFormularios::comprobarAlfabetico($_REQUEST['descDepartamento'], 255, 1, 1); 
            $aErrores['volumenNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['volumenNegocio'], PHP_FLOAT_MAX, PHP_FLOAT_MIN, 1);
             
            //Comprobamos que el codigo de departamento no se encuentre en la base de datos
            try{
                    $miDB = new PDO(HOST,USER,PASSWORD);
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "SELECT T02_CodDepartamento from T02_Departamento where T02_CodDepartamento='$CodDepartamento'";
                    $consulta = $miDB->prepare($sql);//Preparamos la consulta
                    $consulta->execute();//Ejecutamos la consulta
                    
                    //Si el código de departamento ya existe
                    if($consulta->rowCount()>0){
                        $aErrores['codDepartamento'] = "Uyy!! ese codigo departamento ya existe";//Añadimos un mensaje de error al array de errores
                    }
            }catch(PDOException $excepcion){ 
                    $codigoError=$excepcion->getCode();//Obtenemos y guardamos el codigo del error
                    $mensajeError=$excepcion->getMessage();//Obtenemos y guardamos el mensaje de error

                    echo "<p style='background-color:red;'>Codigo de error: $codigoError</p>";   
                    echo "<br>";
                    echo "<p style='background-color:red;'>Mensaje de error: $mensajeError </p>";
            }
            
            //Recorremos el array de errores
            foreach($aErrores  as $campo => $error){
                    if ($error != null) { // Comprobamos que el campo no esté vacio
                        $entradaOk = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario      
                        $_REQUEST[$campo] = "";
                    }
            }
        }else{
             //Si el formulario no esta completo  
            $entradaOk = false;
        }
        //Si el formulario estaba completo recogemos las respuestas
        if($entradaOk){
            try {
                //Establecer una conexión con la base de datos 
                $miDB = new PDO(HOST,USER,PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //consulta para insertar el nuevo departamento
                $consulta = "INSERT INTO T02_Departamento (T02_CodDepartamento, T02_DescDepartamento, T02_VolumenNegocio) VALUES ('$CodDepartamento', '$DescDepartamento', '$VolumenNegocio')";
                $consulta = $miDB->prepare($consulta);//Preparamos la consulta
                $consulta->execute();//Pasamos los parámetros a la consulta
                
                header('Location: mtoDepartamentos.php');
                exit; 
                
            }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error

            } finally {
                unset($miDB);
            }
        }
              
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body{
                /*background: black;*/
            }
            fieldset{
                width: 270px;height: 340px;
                text-align: center;
                color: black;
                font-weight: bold;
                font-size: 20px;
                border: 4px solid black;
                margin: 3rem auto ;
               /* margin-left: 20rem ;*/
            }
            footer{
                background: blueviolet;
                border-radius: 5px 5px 5px 5px;
                font-weight: bold;
                position: fixed;
                bottom: -1px;
                width: 100%;
                height: 60px;
                color: black;
                text-align: center;
                padding: 2px;
                vertical-align: middle;
            }
            a img{
                    display: flex;
                    margin:auto;
                    width:35px;
                    height:35px;
            }
            strong{
		font-size: 20px;
            }
            strong a{
		color:black;
		text-decoration: none;
            }
            strong a:hover{
		color:blue;
            }
            h3{
                text-align: center;
                font-size: 30px;
                line-height: 50px;
                height: 50px;width: 100%;
                background: lightgray;
            }
            input{
                font-size: 16px;
                border-radius:5px;
            }
            input:nth-of-type(4), input:nth-of-type(5){
                font-size: 20px;
                border-radius:5px;
            }
        </style>
    </head>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h3>AÑADIR DEPARTAMENTO</h3>
            <fieldset>
                <label for="codDepartamento">Código Departamento: </label>
                <input type="text" id="codDepartamento" name="codDepartamento" value="<?php echo(isset($_REQUEST['codDepartamento']) ? $_REQUEST['codDepartamento'] : null); ?>"><br> <?php echo($aErrores['codDepartamento']!=null ? "<span style='color:red'>".$aErrores['codDepartamento']."</span>" : null); ?>
                <br>
                <br>
                <label for="descDepartamento">Descripción departamento: </label>
                <input type="text" id="descDepartamento" name="descDepartamento" value="<?php echo(isset($_REQUEST['descDepartamento']) ? $_REQUEST['descDepartamento'] : null); ?>"><br> <?php echo($aErrores['descDepartamento']!=null ? "<span style='color:red'>".$aErrores['descDepartamento']."</span>" : null); ?>
                <br>
                <br>
                <label for="volumenNegocio">Volumen Departamento: </label>
                <input type="text" id="volumenNegocio" name="volumenNegocio" value="<?php echo(isset($_REQUEST['volumenNegocio']) ? $_REQUEST['volumenNegocio'] : null); ?>"><br> <?php echo($aErrores['volumenNegocio']!=null ? "<span style='color:red'>".$aErrores['volumenNegocio']."</span>" : null); ?>
                <br>
                <br>
                <input type="submit" value="Aceptar" name="Aceptar">
                <input type="submit" value="Cancelar" name="Cancelar">
            </fieldset>
        </form>
    </body>
</html>
<?php
    //}
?>