<?php
         /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 20/01/2021
             * Aplicacion LogIn-LogOut Tema5
             */
        if(isset($_REQUEST['Volver'])){
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
        
       try {
                // Conexión con la base de datos.
                $miDB= new PDO(HOST, USER, PASSWORD); //Objeto para establecer la conexion
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
                
                $consulta = "SELECT * FROM T02_Departamento WHERE T02_CodDepartamento = '{$_REQUEST['codDepartamentoEnCurso']}'";

                // Ejecución de la consulta.
                $resultadoConsulta = $miDB->prepare($consulta);
                $resultadoConsulta->execute();
                $oConsulta = $resultadoConsulta->fetch();
                
                $descDepartamento= $aRespuestas['descDepartamento'] = $oConsulta['T02_DescDepartamento'];
                $VolumenNegocio=$aRespuestas['volumenNegocio'] = $oConsulta['T02_VolumenNegocio'];
                              
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
                width: 250px;height: 280px;
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
             <h3>VER DEPARTAMENTO </h3>
             <fieldset>
                <label for="codDepartamento">Codigo Departamento </label>
                <input type="text" id="codDepartamento" name="codDepartamento"value="<?php echo $aRespuestas['codDepartamento'] ?>" disabled>
                <br>
                <br>
                <label for="descDepartamento">Descripcion departamento: </label>
                <input type="text" id="descDepartamento" name="descDepartamento" value="<?php echo $descDepartamento ?>" disabled>
                <br>
                <br>
                <label for="volumenNegocio">Volumen Departamento: </label>
                <input type="text" id="volumenNegocio" name="volumenNegocio" value="<?php echo $VolumenNegocio ?>" disabled>
                <br>
                <br>
                <input type="submit" value="Volver" name="Volver">
            </fieldset>
        </form>
        <!--<footer>2021-22 I.E.S. Los sauces. ©Todos los derechos reservados. <strong>Johanna Herrero Pozuelo </strong>
            <a  href="https://github.com/JohannaHPSauces/LoginLogoutTema5/tree/developer"><img src="../../proyectoDWES/webroot/images/git.png" class="git"></a>
	</footer>-->
        
    </body>
</html>