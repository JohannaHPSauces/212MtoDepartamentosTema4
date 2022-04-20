<?php 
        /*
             * @author: Johanna Herrero Pozuelo
             * Created on: 03/04/2022
             * Inicial Mantenimiento Departamentos Tema4
             */
       
        //Importamos la libreria de validacion
        require_once '../core/210322ValidacionFormularios.php'; 
        //Importamos la configuracion a la base de datos
        require_once '../config/confDBPDO.php';                   
        
        if(isset($_REQUEST['volver'])){
            header('Location: ../../212ProyectoTema4/index.php');
            exit;
        }
        if(isset($_REQUEST['exportar'])){
            header('Location: ../../212ProyectoTema4/codigoPhp/Ejercicio08PDOJson.php');
            exit;
        }
        if(isset($_REQUEST['importar'])){
            header('Location: ../../212ProyectoTema4/codigoPhp/Ejercicio07PDOJson.php');
            exit;
        }
        if(isset($_REQUEST['crear'])){
            header('Location: crear.php');
            exit;
        }
        
        $entradaOK=true;
         //Array respuestas
        $aErrores = ['DescDepartamento' => null];
        
            if (isset($_REQUEST['buscar'])){
                $aErrores['DescDepartamento']= validacionFormularios::comprobarAlfabetico($_REQUEST['DescDepartamento'], 100, 1, 1);

                $descDepartamento= $_REQUEST['DescDepartamento'];
                
                    foreach($aErrores as $campo =>$error){//Recorro el array de errores buscando si hay
                        if($error ==null){// Si hay algun error 
                            $entradaOk=false; //Ponemos la entrada a false
                            $_REQUEST[$campo]="";//Vacia los campos
                        }
                    }
            }else{
                //$entradaOK=false;
                $descDepartamento=null;
            }
                if(!is_null($descDepartamento)){
                    $consulta = "select * from T02_Departamento where T02_DescDepartamento like '%".$descDepartamento."%'";
                }else{
                        //Mostrado de todas la filas
                    $consulta = "select * from T02_Departamento";
                }
    
            //Si ha dado al boton de buscar
            if($entradaOK){
                try {
                    //Establecer una conexión con la base de datos 
                     $miDB = new PDO(HOST,USER,PASSWORD);                            
                     $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     
                    //Preparamos la consulta
                    $resultadoConsulta = $miDB->prepare($consulta); 
                    //Ejecutamos la consulta
                    $resultadoConsulta->execute();
                    
                    //Si no ha buscado ningun departamento mostramos todos
                    if($resultadoConsulta->rowCount()>0){
                        echo "<table>";
                        echo "<tr>";
                            echo "<th> CODIGO DEPARTAMENTO</th>";
                            echo "<th> DESCRIPCION DEPARTAMENTO</th>";
                            echo "<th> VOLUMEN DEPARTAMENTO </th>";
                        echo "</tr>";

                        $oDepartamento = $resultadoConsulta->fetchObject();  //obtiene la siguiente fila y la devuelve como objeto. 
                        while ($oDepartamento){  
                            $codDepartamento= $oDepartamento->T02_CodDepartamento;
                            echo "<tr>";
                            echo "<td><p>$codDepartamento </td>";           
                            echo "<td> $oDepartamento->T02_DescDepartamento </td>";
                            echo "<td> $oDepartamento->T02_VolumenNegocio </td></p>";
                        ?>
                        <td><a href="editar.php?codDepartamentoEnCurso=<?php echo urlencode($codDepartamento); ?>"><img src="../webroot/lapiz.png" class="ver" width="40" height="40"></td>
                        <td><a href="borrar.php?codDepartamentoEnCurso=<?php echo urlencode($codDepartamento); ?>"><img src="../webroot/basura.png" class="ver" width="40" height="40"></td>
                        <td><a href="ver.php?codDepartamentoEnCurso=<?php echo urlencode($codDepartamento); ?>"><img src="../webroot/ojo.png" class="ver" width="40" height="40"></td>
                         <?php        
                    
                            echo "</tr>";
                            $oDepartamento = $resultadoConsulta->fetchObject();
                        } 
                    }else{
                        //Si el departamento no existe le decimos que no existe
                       echo "<p style='background-color: red;'>NO EXISTE ESE DEPARTAMENTO!!</p><br>";
                    }
                
                }catch (PDOException $excepcion){
                    $codigoError=$excepcion->getCode();//Obtenemos y guardamos el codigo del error
                    $mensajeError=$excepcion->getMessage();//Obtenemos y guardamos el mensaje de error

                    echo "<p style='background-color:red;'>Codigo de error: $codigoError</p>";   
                    echo "<br>";
                    echo "<p style='background-color:red;'>Mensaje de error: $mensajeError </p>";
                } finally {
                    //Cerramos conexion
                    unset($miDB);
                }
            }
?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div id="cajaTitulo">Mantenimiento Departamentos</div><br>
            <input id="volver" type="submit" name="volver" value="Volver">
            <br>
            <br>
                <fieldset>
                    <label>Buscar departamento por descripción:</label>
                    <input type = "text" name = "DescDepartamento" value="<?php echo(isset($_REQUEST['DescDepartamento']) ? $_REQUEST['DescDepartamento'] : null); ?>"><?php echo($aErrores['DescDepartamento']!=null ? "<span style='color:red'>".$aErrores['DescDepartamento']."</span>" : null); ?>
                    <input type="submit" name="buscar" value="Buscar">
                </fieldset>
            <br>
            <input type="submit" name="crear" value="Añadir">
            <input type="submit" name="exportar" value="Exportar">
            <input type="submit" name="importar" value="Importar">
            </form>
           

    </body>
</html>
<!Doctype HTML>
<html>
    <head>
        <title>Ejercicio mtoDepartamentos</title>
        <meta charset="UTF-8">
        <style>
            div{
                width: 100%;height: 50px;
                background: grey;
                font-size: 40px;
                font-weight: bold;
                color:white;
                text-align: center;
            }
             h3{
                color: darkslateblue;
            }
            table{
                border: 4px white solid;
                background: lightgray;
                width: 98%;height: 10%;
            }
            td{
                border: 2px white solid;
                text-align: center;
            }
            th{
                background: grey;
            }
            fieldset{
                width: 95%;height: 50px;
                border: 3px solid grey;
                color: black;
                position: relative;
            }
            
            input:nth-of-type(2), input:nth-of-type(3), input:nth-of-type(4) {
                font-size: 20px;
                border-radius:5px;
            }
            #volver{
                width: 100px;height:50px;
                font-size: 20px;
                border-radius:5px;
            }
            button{
                background: none;
            }
        </style>
    </head>
    <body>
