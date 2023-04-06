<?php 
require("../includ/banco.php");
$inicio = $_POST["inicio"];
$fim = $_POST["fim"];
$id = $_POST["id"];

$textoF = "Periodo de Férias do dia ".$inicio." até ".$fim;
$sql = $pdo->prepare("INSERT INTO MADALOZZO_PONTO2 (IDFUNCIONARIO,DATA_REGISTRO, OBS) VALUES ($id,'$inicio','INICIO FERIAS')");
$sql->execute();
$sql = $pdo->prepare("INSERT INTO MADALOZZO_PONTO2 (IDFUNCIONARIO,DATA_REGISTRO,OBS) VALUES ($id,'$fim','FIM FERIAS')");
$sql->execute();

header("location:detalhes.php?id=$id");