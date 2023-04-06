<?php 
     require("../includ/conf.php");
     require("../includ/banco.php");
     require("funcoes.php");

     /**
      * pagina PHP responsavel pelo cadastro de novos funcionarios
      */

     $mesAno = date("m/Y");
    
     if (empty($_SESSION["FOTO"]))
     {
        $foto = "padrao.png";
     }
     else 
     {
        $foto = $_SESSION["FOTO"];
     }
            
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
//verifica o tipo de acesso para que esta pagina não seja acessadas pelos demais usuarios
 if ($_SESSION["TIPO_ACESSO"] == "FUNCIONARIO")
 {
   echo "Voce não tem perimssao de acesso";
 }
 else{
   

 //PAGINA DO ADM
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
         <div class = "col-md-5">
            
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link " href="./">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="responsavel.php">RESPONSAVEL</a>
                </li>
            </ul>

       </div>
    </div>
<?php 
   $sql = $pdo->prepare("SELECT * FROM RESPONSAVEL WHERE IDRESPONSAVEL = '".$_GET['id']."'");
   $sql->execute();
   $sql = $sql->fetch(PDO::FETCH_ASSOC);

?>
   <div class = "row">
        <div class = "col-md-12"> 
            <!--Container com o formulario de cadastro!-->
             <div class = "formulario-cadastro-funcionario">
                <form action = "includ/editares.php" method = "POST">
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "nome" value = "<?php echo $sql["NOME"];?>"  autocomplete="off">
                     </div>
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "email" value = "<?php echo $sql["EMAIL"];?>" autocomplete="off">
                     </div>
                      <input type = "hidden" name = 'id' value ="<?php echo $_GET["id"];?>">
                     <button class = "btn btn-success" type = "submit">ALTERAR</button>
                </form>
            </div>
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
  </body>
</html>
<?php
}
?>


