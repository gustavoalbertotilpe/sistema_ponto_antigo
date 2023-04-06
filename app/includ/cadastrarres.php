<?php 
//Script PHP para realizar o cadatro de Funcionario
   require("../../includ/banco.php");
   if (!isset($_POST["nome"]))
   {
       header("location:../");
   }
   else
   {
      $nome  = $_POST["nome"];
      $email = $_POST["email"];
     
 
      $sql = $pdo->prepare("INSERT INTO RESPONSAVEL (NOME,EMAIL) VALUES (?,?)");

      if ($sql->execute(array($nome,$email)))
      {
          echo "Cadastrado com sucesso!";
          header("location:../cadastror.php");
      }
      else
      {
          echo "Erro na tentativa de cadastro!";
       
      }

   }