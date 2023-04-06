<?php 
//Script PHP para realizar o cadatro de Funcionario
   require("../../includ/banco.php");
   if (!isset($_POST["nome"]))
   {
       header("location:../");
   }
   else
   {
      $id = $_POST["id"];
      $nome  = $_POST["nome"];
      $email = $_POST["email"];
      
      $sql = $pdo->prepare("UPDATE RESPONSAVEL SET NOME ='$nome', EMAIL ='$email' WHERE IDRESPONSAVEL ='$id' ");

    if ($sql->execute())
    {
        echo "Cadastro Realizado com sucesso!";
        header("location:../responsavel.php");
    }
    else
    {
        echo "Erro na tentativa de cadastro!";
        header("location:../responsavel.php");
    }



      


   }