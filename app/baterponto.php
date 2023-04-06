<?php 
   require("../includ/banco.php");
   require("../includ/conf.php");
   require("funcoes.php");
  
   $id  = $_SESSION["ID"];
      
   baterPonto($id);

   header("location:./");


  
