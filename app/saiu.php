<?php 
   session_start();
   session_destroy();
   header("location:../");
   //Responsavel pela funcionalidade do botão sair