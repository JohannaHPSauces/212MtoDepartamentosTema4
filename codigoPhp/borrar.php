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
        $aRespuestas= [ 'codDepartamento' => $_REQUEST['codDepartamentoEnCurso'],
                        'descDepartamento' => '',
                        'fechaBaja' => '',
                        'volumenNegocio' => ''
                        ];
        
        if(isset($_REQUEST['Aceptar'])){//Si el usuario pulsa el boton de eleiminar
            try{
                $miDB= new PDO(HOST,USER,PASSWORD);//Creo un objeto PDO
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Establezco los atributos para la conexion
               
                $consulta="DELETE FROM T02_Departamento WHERE T02_CodDepartamento='{$aRespuestas['codDepartamento']}'";//Hago la consulta
                $resultadoConsulta=$miDB->prepare($consulta);//Preparo la consulta
                $resultadoConsulta->execute();//Ejecuta la consulta
               
                header('Location: mtoDepartamentos.php');
                exit;
             }catch(PDOException $excepcion){ //Pero se no se ha podido ejecutar saltara la excepcion
               $codigoError= $exception->getCode();//Guardo en una variable el codigo del error
               $mensajeError =$excepcion->getMessage();//Guardo en una variable el mensaje de error

               echo "<p style='background-color:red'> Codigo de error: ".$codigoError;
               echo "<p style=background-color:red'> Mensaje de error: ".$mensajeError;
            }finally{
                unset($miDB);//Cierro la conexion
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
                width: 350px;height: 100px;
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
            input:nth-of-type(4), input:nth-of-type(5){
                height: 30px; width: 80px;
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
                background: red;
            }
            input{
                font-size: 16px;
                border-radius:5px;
            }
            input:nth-of-type(2), input:nth-of-type(3){
                font-size: 20px;
                border-radius:5px;
            }
        </style>
    </head>
    <body>
         <form action="<?php echo $_SERVER['PHP_SELF'] . "?codDepartamentoEnCurso=" . $aRespuestas['codDepartamento'] ?>" method="post">
            <h3>Quieres eliminar este departamento? </h3>
             <fieldset>
                <input type="text" id="codDepartamento" name="codDepartamento"value="<?php echo $aRespuestas['codDepartamento'] ?>" disabled>
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
