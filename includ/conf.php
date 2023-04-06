<?php 
    session_start();
    if (empty($_SESSION["NOME"]))
    {
        header("location:../");
        die();
    }
    date_default_timezone_set('America/Sao_Paulo');