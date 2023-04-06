<?php 
    try 
    {
        $pdo = new PDO("mysql:host=localhost;dbname=madalozzoseguros",'root','',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e)
    {
        echo "Erro ao conectar no banco de dados";
    }