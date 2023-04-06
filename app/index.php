<?php 
     require("../includ/conf.php");
     require("../includ/banco.php");
     require("funcoes.php");

     

     $mesAno = date("m/Y");
    
     if (empty($_SESSION["FOTO"]))
     {
        $foto = "padrao.png";
     }
     else 
     {
        $foto = $_SESSION["FOTO"];
     }

     /**
      * Pagina index, nesta pagina é para os dois tipos de usuario tanto adm quanto funcionario
      */
            
?>     
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "../css/estilo.css">

    <title>Madalozzo</title>
  </head>
  <body>    
    <header class = "topo container-fluid">
    
         <div class ="row">
            <div class = "col-md-6"></div>

            <div class = "col-md-6">
                 <div class = "saudacao">
                 <div class = "user-dados">
                    <table>
                        <tr>
                            <td><div style = "background-image: url('../img/<?php echo $foto;?>');"; class = "foto-funcionario"></div></td>
                            <td><h5><?php echo $_SESSION["NOME"]." ".$_SESSION["SOBRENOME"];?></h5><h6><?php echo $_SESSION["DEPARTAMENTO"];?></h6></td>
                        </tr>
        
                    </table>        
                </div>    

                 </div>
            </div>

         </div>


    </header>

<?php 
//Verifica o tipo de usuario se for Funcionario sera renderizado a pagina do usuario comun
 if ($_SESSION["TIPO_ACESSO"] == "FUNCIONARIO")
 {
    $id  = $_SESSION["ID"];
?>
    
    <div class = "container-fluid">
        <div class = "row">
                <div class = "col-md-12">
                    <div class = "sair">
                        <a class = "btn btn-danger" href = "saiu.php">SAIR</a>
                    </div>
                </div>
       </div>        
        <div  class = "row">
            <div class = "col-md-7">
        <!--Div vazia!-->    
            </div>
                
    </div>
       <div class  = "row margin">
           <div class = "col-md-6">
               <h2>Ultimos pontos Registrados Hoje</h2>
               <?php 
                   echo ultimosPontosRegistrados($id);
               ?>    
           </div>
           <div class = "col-md-6">
               <form action = "baterponto.php" method = "POST" style = "float:Right">
                   <input class = "btn btn-primary" name = "baterPonto" type = "submit" value = "Registar Ponto">
               </form>
           </div>
       </div>
       <div class = "clear"></div>
    
    </div>

 <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
<?php 
 }
 else
 {
?> 
<div class = "container-fluid">
<div class = "row">
            <div class = "col-md-12">
                <div class = "sair">
                    <a class = "btn btn-danger" href = "saiu.php">SAIR</a>
                </div>
            </div>
    </div>  
    
    <div  class = "row">
         <div class = "col-md-8">
            
         </div>
         <div class = "col-md-4">
            
            <ul class="nav nav-pills">
               
                <li class="nav-item">
                    <a class="nav-link " href="responsavel.php">RESPONSAVEL</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="funcionario.php">FUNCIONARIOS</a>
                </li>
            </ul>

       </div>
    </div>
   
   <div class = "row">
   
        <div class = "col-md-12"> 
           
           <h3>Pontos Registrados Hoje</h3>
           <!--Div responsavel por exibir a tabela com os relatorios de pontos dos funcinarios batidos na data atual!-->
           <div id = "ultimosPontosBatidosHj" class = "relatorio-pontos-batidos">
           
           </div>

        
        </div>

   </div>



</div>



    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src = "js/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function(){  

            //Função responsavel por fazer requisão ajax da pagina consultaPonto.php que e responsavel por gerar o relatorio de ponto batido
                function atualizaPontoBatidoHj(){
                        $.ajax({
                            url:'includ/consultaPonto.php',
                            success: function(data){
                                $('#ultimosPontosBatidosHj').html(data);
                            }
                        });
                }
            //chama a função a cada 2 segundos
            atualizaPontoBatidoHj();
            window.setInterval(atualizaPontoBatidoHj,2000);
                   
        })            

    
    
    </script>
  </body>
</html>

<?php
 }
?>



