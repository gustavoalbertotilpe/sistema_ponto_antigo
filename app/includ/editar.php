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
      $sobrenome = $_POST["sobrenome"];
      $departamento = $_POST["departamento"];
      $usuario =  $_POST["usuario"];
      $email = $_POST["email"];
      $jornada = $_POST["jornada"];
      $tipoAcesso  = $_POST["tipoacesso"];
      $status  = $_POST["status"];
      $cpf = $_POST['cpf'];
      $dataAdmissao = $_POST['dataAdmissao'];
      $dataNascimento = $_POST["dataNascimento"];
      $primariaEntrada = $_POST["h1"];
      $primeriaSaida = $_POST["h2"];
      $segundaEntrada = $_POST["h3"];
      $segundaSaida = $_POST["h4"];
      $responsavel = $_POST["responsavel"];
      
      if (empty($_POST["senha"]))
      {
            $sql = $pdo->prepare("UPDATE MADALOZZO_FUNCIONARIO SET NOME ='$nome', SOBRENOME ='$sobrenome', DEPARTAMENTO ='$departamento',USUARIO = '$usuario',EMAIL = '$email',JORNADA ='$jornada',TIPO_ACESSO='$tipoAcesso',STATUS_USUARIO='$status',CPF = '$cpf',DATA_ADMISSAO = '$dataAdmissao',DATA_NASCIMENTO = '$dataNascimento',H1='$primariaEntrada',H2='$primeriaSaida',H3='$segundaEntrada',H4='$segundaSaida',RESPONSAVEL = '$responsavel' WHERE ID_FUNCIONARIO ='$id' ");

            if ($sql->execute())
            {
                echo "Cadastro Realizado com sucesso!";
                header("location:../funcionario.php");
            }
            else
            {
                echo "Erro na tentativa de cadastro!";
                header("location:../funcionario.php");
            }


      }
      else
      {
            $senha = md5($_POST["senha"]);

            $sql = $pdo->prepare("UPDATE MADALOZZO_FUNCIONARIO SET NOME ='$nome', SOBRENOME ='$sobrenome', DEPARTAMENTO ='$departamento',USUARIO = '$usuario',SENHA = '$senha',EMAIL = '$email',JORNADA ='$jornada',TIPO_ACESSO='$tipoAcesso',STATUS_USUARIO='$status' WHERE ID_FUNCIONARIO ='$id' ");

            if ($sql->execute())
            {
                echo "Cadastro Realizado com sucesso!";
                header("location:../funcionario.php");
            }
            else
            {
                echo "Erro na tentativa de cadastro!";
                header("location:../funcionario.php");
            }
      }     


   }