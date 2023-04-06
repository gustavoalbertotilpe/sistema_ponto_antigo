<?php 
    session_start();
    require("includ/banco.php");
//Verifica os dados
    if (isset($_POST["user"]) && empty($_POST["user"]) == FALSE )
    {
        $sql = $pdo->prepare("SELECT * FROM MADALOZZO_FUNCIONARIO WHERE USUARIO = ? && SENHA = ?");
        $sql->execute(array($_POST["user"],md5($_POST["senha"])));
        $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        if (empty($resultado))
        {
            $aviso =  "Login ou senha Invaido";
        }

        else 
        {

            foreach($resultado as $key => $value)
            {
                
               if ($value["STATUS_USUARIO"] == "DESATIVADO")
               {
                   $aviso = "Usuario desativado!";

                   die();
               }

               else 

               {
                  
                  $_SESSION["NOME"] =  $value["NOME"];
                  $_SESSION["SOBRENOME"] =  $value["SOBRENOME"];
                  $_SESSION["EMAIL"] =  $value["EMAIL"];
                  $_SESSION["FOTO"] =  $value["FOTO"];
                  $_SESSION["ID"] =  $value["ID_FUNCIONARIO"];
                  $_SESSION["DEPARTAMENTO"] =  $value["DEPARTAMENTO"];
                  $_SESSION["JORNADA"] =  $value["JORNADA"];
                  $_SESSION["TIPO_ACESSO"] =  $value["TIPO_ACESSO"];
                  header("location:app/");

                  die();

               }

            }
            

        }
        
    

    }
?>



<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "css/estiloLogin.css">

    <title>Madalozzo</title>
  </head>
  <body>
    
    <header class = "topo"></header>

    <div class = "container-fluid">
    
        <div class = "row">
            <div class = "col-md-12">
               <div class = "formulario">
                    <form action = "" method = "POST">
                
                        <div class = "form-group" >
                            <input class = "form-control" type = "text" name = "user" placeholder ="USUARIO">
                        </div>    

                        <div class = "form-group">
                            <input class = "form-control" type = "password" name = "senha" placeholder ="SENHA">
                        </div>

                        <div class = "form-group"> 
                            <button class = "btn btn-success btn-block btn-lg">Entrar</button>
                        </div>
                
                    </form>
    <?php
        if (isset($aviso))
        {
            echo $aviso;
        } 
    ?>
                </div>    
            
            </div>
        </div>
    
    </div>

    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>