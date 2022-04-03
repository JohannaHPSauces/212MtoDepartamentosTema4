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
        
        
        $entradaOk=true;
        $aRespuestas= ['codDepartamento' => $_REQUEST['codDepartamentoEnCurso'],
                       'descDepartamento' =>'',
                       'fechaBaja' => '',
                       'volumenNegocio' =>'' ];
        
        $aErrores=['descDepartamento' =>null,
                   'volumenNegocio' =>null ];
        
        if(isset($_REQUEST['Aceptar'])){
            $aErrores['descDepartamento']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['descDepartamento'], 200, 4, 1);//Hacemos la validacion de la descripcion
            $aErrores['volumenNegocio']= validacionFormularios::comprobarAlfaNumerico($_REQUEST['volumenNegocio'], 2, 1, 2, 1); //Hacemos la validacion de la contraseña
           
            foreach ($aErrores as $campo => $error) { // reocrro el array de errores
                if ($error != null) { // compruebo si hay algun elemento distinto de null
                    $entradaOk = false; // le doy el valor false a $entradaOK
                }
            }
        }else { // si el usuario no le ha dado al boton de enviar
            $entradaOk = false; // le doy el valor false a $entradaOK           
        }
        
        if($entradaOk){
            $descDepartamentoSeleccionado=$aRespuestas['desDepartamento']= $_REQUEST['descDepartamento']; //Guardo el la descripcion del usuario introducido
            $volNegocioSeleccionado=$aRespuestas['volumenNegocio']= $_REQUEST['volumenNegocio']; //Guardo la contraseña introducida
            $codDepartamentoSeleccionado= $aRespuestas['codDepartamento'];
            
            try{
                $miDB= new PDO(HOST, USER, PASSWORD); //Objeto para establecer la conexion
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
                
                
                $consulta ="UPDATE T02_Departamento SET T02_DescDepartamento='$descDepartamentoSeleccionado', T02_VolumenNegocio='$volNegocioSeleccionado' WHERE T02_CodDepartamento='$codDepartamentoSeleccionado'"; //consulta
                $resultadoConsulta=$miDB->prepare($consulta); //Preparar la consulta
                $resultadoConsulta->execute();//Ejecutal la consulta
                
                $oConsulta= $resultadoConsulta->fetchObject();//Guardo el resultado de la consulta en un objeto
                
               header('Location: mtoDepartamentos.php');
                exit;
            }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error

            }finally{//Para finalizar cerramos la conexion a la base de datos
                     unset($miDB);
            }
                foreach($aErrores as $campo =>$error){//Recorro el array de errores buscando si hay
                    if($error !=null){// Si hay algun error 
                        $entradaOk=false;
                        $_REQUEST['campo']="";//Vacia los campos
                    }
                }
        }else{
            $entradaOk= false; //Si el usuario no le ha dado ha enviar los datos muestra el formulario hasta que ponga algo
        }
                
       try {
                $consulta = <<<QUERY
                    SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$_REQUEST['codDepartamentoEnCurso']}';
                QUERY;

                // Conexión con la base de datos.
                $miDB= new PDO(HOST, USER, PASSWORD); //Objeto para establecer la conexion
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
                
                // Ejecución de la consulta.
                $oConsulta = $miDB->prepare($consulta);
                $oConsulta->execute();
                $oResultadoConsulta = $oConsulta->fetch();
                              
            }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
                $codigoError = $excepcion->getCode(); //Guardamos en una variable el codigo del error
                $mensajeError = $excepcion->getMessage(); //guardamos en una variable el mensaje del error 

                echo "<p style='background-color:red'> Codigo de error: ".$codigoError;     //Mostramos el error
                echo "<p style='background-color: red;'> Mensaje de error:". $mensajeError; //Mostramos el mensaje de error

            } finally {
                unset($miDB);
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
         <form action="<?php echo $_SERVER['PHP_SELF'] . "?codDepartamentoEnCurso=" . $aRespuestas['codDepartamento'] ?>" method="post">
            <h3>EDITAR DEPARTAMENTO</h3>
             <fieldset>
                <label for="codDepartamento">Código Departamento: </label>
                <input type="text" id="codDepartamento" name="codDepartamento"value="<?php echo $aRespuestas['codDepartamento'] ?>" disabled>
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
        <!--<footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados. <strong>Johanna Herrero Pozuelo </strong>
            <a  href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../../proyectoDWES/webroot/images/git.png" class="git"></a>
	</footer>-->
        
    </body>
</html>
<?php
   // }
?>