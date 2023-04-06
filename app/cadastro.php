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
                    <a class="nav-link" href="funcionario.php">FUNCIONARIOS</a>
                </li>
            </ul>

       </div>
    </div>
   
   <div class = "row">
        <div class = "col-md-12"> 
            <!--Container com o formulario de cadastro!-->
             <div class = "formulario-cadastro-funcionario">
                <form action = "includ/cadastrar.php" method = "POST">
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "nome" placeholder = "NOME" required autocomplete="off">
                     </div>
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "sobrenome" placeholder = "SOBRENOME" autocomplete="off">
                     </div>
                     <div class = "form-group">
                         <label>Data Nascimento
                         <input class = "form-control" type = "date" name = "dataNascimento" autocomplete="off">
                         </label>
                     </div>
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "cpf" placeholder = "CPF" autocomplete="off">
                     </div>
                     <div class = "form-group">
                          <label>Data de Inicio
                         <input class = "form-control" type = "date" name = "dataAdmissao" placeholder = "DATA ADMISSÃO" autocomplete="off">
                         </label>
                     </div>
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "departamento" placeholder = "DEPARTAMENTO" autocomplete="off">
                     </div>
                     <div class = "form-group">
                         <?php responsavelC();?>
                     </div>
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "email" placeholder = "EMAIL" autocomplete="off">
                     </div>
                    <div class = "form-group">
                         <input class = "form-control" type = "text" name = "usuario" placeholder = "USUARIO" autocomplete="off">
                     </div>
                     <div class = "form-group">
                         <input class = "form-control" type = "text" name = "senha" placeholder = "SENHA" autocomplete="off">
                     </div>
                     <div class = "form-group">
                          <label> Carga Horaria
                           <input class = "form-control" type = "time" name = "jornada" placeholder = "JORNADA">
                         </label>
                     </div>
                     <div class = "form-group">
                          <label> Primerio Entrada
                           <input class = "form-control" type = "time" name = "h1">
                         </label>
                     </div>
                     <div class = "form-group">
                          <label> Primeira Saida
                           <input class = "form-control" type = "time" name = "h2">
                         </label>
                     </div>
                     <div class = "form-group">
                          <label> Segunda  Entrada
                           <input class = "form-control" type = "time" name = "h3" placeholder = "JORNADA">
                         </label>
                     </div>
                     <div class = "form-group">
                          <label> Segunda Saida
                           <input class = "form-control" type = "time" name = "h4" placeholder = "JORNADA">
                         </label>
                     </div>
                     <div class = "form-group">
                         <label for = "acessotipo">Tipo Acesso</label>
                         <select id = "acessotipo" name = 'tipoacesso' class = "form-control">
                             <option value = "FUNCIONARIO">FUNCIONARIO</option>
                             <option value = "ADM">ADM</option>
                        </select>    
                    </div>
                     <button class = "btn btn-success" type = "submit">CADASTRAR</button>
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



