<?php 
   require("funcoes.php");
   $id = $_POST["id"];
   $data = $_POST["data"];
   $justificativa = $_POST["justificativa"];
   abono($id,$data,$justificativa);

   header("location:detalhes.php?id=$id");