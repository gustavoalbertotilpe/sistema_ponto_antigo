<?php 
   require("../includ/banco.php");
   $data = $_POST["data"];
   $justificativa = $_POST["justificativa"];
   $tipoDeDesconto = $_POST["tipoDesconto"];
   $jornada = $_POST["jornada"];
   $mesAno = $_POST["mesAno"];
   $id = $_POST["id"];

   if ($tipoDeDesconto == "nao")
   {
       $sql = $pdo->prepare("INSERT INTO MADALOZZO_PONTO2 (DATA_REGISTRO,HT,ABONO,JUSTIFICATIVA_ABONO,IDFUNCIONARIO) VALUE ('$data','$jornada','$jornada','$justificativa',$id)");
       $sql->execute();
       header("location:detalhes.php?id=$id");
   }
   else 
   {
        $sql = $pdo->prepare("SELECT SUBTIME(SALDO_BANCO,'".$jornada."') AS DESCONTO FROM BANCO_HORA WHERE IDFUNCIONARIO = $id && MES_ANO = '".$mesAno."'");
        $sql->execute();
        $value = $sql->fetch(PDO::FETCH_ASSOC);
        
        $sql = $pdo->prepare("UPDATE BANCO_HORA SET SALDO_BANCO = '".$value["DESCONTO"]."' WHERE IDFUNCIONARIO = $id && MES_ANO = '".$mesAno."' ");

        $sql->execute();

        $sql = $pdo->prepare("INSERT INTO MADALOZZO_PONTO2 (DATA_REGISTRO,HT,ABONO,JUSTIFICATIVA_ABONO,IDFUNCIONARIO) VALUE ('$data','$jornada','$jornada','$justificativa',$id)");
        $sql->execute();
        header("location:detalhes.php?id=$id");

   }

