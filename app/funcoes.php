<?php 
require("../includ/banco.php");
date_default_timezone_set('America/Sao_Paulo');

function  ultimosPontosRegistrados($id)
{
    global $pdo; 
    $sql = $pdo->prepare("SELECT HORA_ENTRADA1,HORA_SAIDA1, HORA_ENTRADA2,HORA_SAIDA2 FROM MADALOZZO_PONTO2 WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = CURDATE()");
    $sql->execute();
    $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
 
    if (!empty($sql))
    {
        $html = "<table class ='table'>";
        $html.= "<tr>";
        $html.= "<th>ENTRADA 1</th>";
        $html.= "<th>SAIDA 1</th>";
        $html.= "<th>ENTRADA 2</th>";
        $html.= "<th>SAIDA 2</th>";
        $html.= "</tr>";

        foreach ($sql as $key => $value)
        {
           $html.="<tr>";

           $html.="<td>".$value["HORA_ENTRADA1"]."</td>";
           $html.="<td>".$value["HORA_SAIDA1"]."</td>";
           $html.="<td>".$value["HORA_ENTRADA2"]."</td>";
           $html.="<td>".$value["HORA_SAIDA2"]."</td>";

           $html.="</tr>";
        }

        $html.="</table>";

      
    }
    else 
    {
        $html = "<h2>Ainda você não possui ponto registrado hoje";
    }    
    
    echo $html;
    
}

function baterPonto($id)
{
    global $pdo; 
    $data = date("Y-m-d");
    $dataAtual = date("Y/m/d");
    $mes = date("m");
    $hora = date("H:i:s");
    $anoAtual = date("Y");

    //Realiza a consulta de ponto batido da data atual
   $sql = $pdo->prepare("SELECT * FROM MADALOZZO_PONTO2 WHERE DATA_REGISTRO = CURDATE() && IDFUNCIONARIO =".$id."");
   $sql->execute();
   $consultaPontoHoje = $sql->fetchAll(PDO::FETCH_ASSOC);
//Se não existir ponto batidos na data atual é registrado o primeiro ponto do dia    
   if (empty($consultaPontoHoje))
   {

      $sql2  = $pdo->prepare("INSERT INTO MADALOZZO_PONTO2 (IDFUNCIONARIO,HORA_ENTRADA1,DATA_REGISTRO) VALUES (?,CURTIME(),'".$dataAtual."')");
        
      if ($sql2->execute(array($id)))
      {
      }

   }
   else{

   //Realiza a consulta do primerio ponto batido no dia
   $sql3 = $pdo->prepare("SELECT * FROM MADALOZZO_PONTO2 WHERE  IDFUNCIONARIO = '".$id."'  && DATA_REGISTRO ='".$dataAtual."' ORDER BY HORA_ENTRADA1 DESC LIMIT 1");
   $sql3->execute();
   $consulta = $sql3->fetchAll(PDO::FETCH_ASSOC);

   foreach ($consulta as $key => $value)
   {

     switch ($value) {
   //Se o campo HORA_SAIDA1 estiver vazio vai ser considerado que o primeiro ponto foi batido  e dispara o registro do ponto da primeria saida    
       case empty($value["HORA_SAIDA1"]) :

        $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HORA_SAIDA1 = CURTIME() WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = '".$dataAtual."'") ;
              
        if ($sql2->execute())
        {

          //Realiza a consulta para o calculo da HT
          $sql = $pdo->prepare("SELECT HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,JORNADA FROM MADALOZZO_PONTO2 INNER JOIN MADALOZZO_FUNCIONARIO ON IDFUNCIONARIO = ID_FUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && DATE(DATA_REGISTRO) = CURDATE() ");
          $sql->execute();
          $result = $sql->fetchAll(PDO::FETCH_ASSOC);
          foreach($result as $key => $value){
            
               // Faz o cálculo das horas
               $total = (strtotime($value["HORA_SAIDA1"]) - strtotime($value["HORA_ENTRADA1"])) + (strtotime($value["HORA_SAIDA2"]) - strtotime($value["HORA_ENTRADA2"]));

               // Encontra as horas trabalhadas
               $hours      = floor($total / 60 / 60);

               // Encontra os minutos trabalhados
               $minutes    = round(($total - ($hours * 60 * 60)) / 60);

               // Formata a hora e minuto para ficar no formato de 2 números, exemplo 00
               $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
               $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

               // Exibe no formato "hora:minuto"
               $horaT = $hours.':'.$minutes;

               $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HT = '".$horaT."' WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = '".$dataAtual."'") ;
         
              $sql2->execute();
              horas($id);

          }
                  

        }
      break;
       case   empty($value["HORA_ENTRADA2"]):
  
          $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HORA_ENTRADA2 = CURTIME() WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = '".$dataAtual."'") ;
                
          if ($sql2->execute())
          {
           
          }
           
       break; 
       case   empty($value["HORA_SAIDA2"]):
  
        $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HORA_SAIDA2 = CURTIME() WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = '".$dataAtual."'") ;
              
        if ($sql2->execute())
        {
          
          $sql = $pdo->prepare("SELECT  date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,JORNADA FROM MADALOZZO_PONTO2 INNER JOIN MADALOZZO_FUNCIONARIO ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && DATE(DATA_REGISTRO) = CURDATE()");
          $sql->execute();
          $result = $sql->fetchAll(PDO::FETCH_ASSOC);
          foreach($result as $key => $value)
          {
            // Faz o cálculo das horas
            $total = (strtotime($value["HORA_SAIDA1"]) - strtotime($value["HORA_ENTRADA1"])) + (strtotime($value["HORA_SAIDA2"]) - strtotime($value["HORA_ENTRADA2"]));

            // Encontra as horas trabalhadas
            $hours      = floor($total / 60 / 60);

            // Encontra os minutos trabalhados
            $minutes    = round(($total - ($hours * 60 * 60)) / 60);

            // Formata a hora e minuto para ficar no formato de 2 números, exemplo 00
            $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
            $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

            // Exibe no formato "hora:minuto"
            $horaT = $hours.':'.$minutes;

            $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HT = '".$horaT."' WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = '".$dataAtual."'") ;
      
            $sql2->execute();
            horas($id);
          }
        
         
        }
         
     break; 

     default: 
       //Se 4 pontos foram batidos durante o dia 
       echo "Limite de ponto batido excedido";
    break;  
       
   
     }

   }
  }
}

function horas($id)
{
   global $pdo;
   $mesAno = date("m/Y");
   $data = date("Y-m-d");


   //verifica  se existe um banco de horas para o mes caso não exista o mesmo é criado

   
   $sql = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE MES_ANO = '".$mesAno."' && IDFUNCIONARIO =".$_SESSION["ID"]."");
   $sql->execute();
   $consultaBandoHoraMes = $sql->fetchAll(PDO::FETCH_ASSOC);
    
   if (empty($consultaBandoHoraMes))
   {

      $sql2  = $pdo->prepare("INSERT INTO BANCO_HORA (IDFUNCIONARIO,MES_ANO) VALUES ('".$_SESSION["ID"]."','".$mesAno."')");
      $sql2->execute();
   } 
//Script que realiza a gravação da HE e HF 

   $SQL = $pdo->prepare("SELECT SUBTIME(ht,jornada) as HORA, HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,HE,HF,JORNADA FROM MADALOZZO_PONTO2  LEFT JOIN MADALOZZO_FUNCIONARIO ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
   $SQL->execute();
   $result = $SQL->fetchAll(PDO::FETCH_ASSOC);
   foreach($result as $key => $value)
   {
       

       if ($value["HORA"] < "00:00:00")
       {
           $hora = str_replace("-","",$value["HORA"]);
           $SQL  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HF = '".$hora."' WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
           $SQL->execute();

           $SQL  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HE = '00:00:00' WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
           $SQL->execute();
       }
       else
       {
           $SQL  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HE = '".$value["HORA"]."' WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
           $SQL->execute();

           $hora = str_replace("-","",$value["HORA"]);
           $SQL  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HF = '00:00:00'WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
           $SQL->execute();
           
       }                            
          
                   
}

    //Realiza consulta trazendo o resultado do saldo que é HE - HF esta query filtra da data atual

    $query = $pdo->prepare("SELECT SUBTIME(HE,HF) AS HORA  FROM MADALOZZO_PONTO2 where IDFUNCIONARIO = ".$id."&& DATE(DATA_REGISTRO) = CURDATE()");
    $query->execute();

    $horaBanco = $query->fetch(PDO::FETCH_ASSOC);
    
    //Se o resultado da busca não for vazio é disparado a query que atualiza o saldo do dia 

     if (!empty($horaBanco))
     {
         $sql = $pdo->prepare("UPDATE MADALOZZO_PONTO2 SET SALDO = '".$horaBanco["HORA"]."'WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) = CURDATE()");
         $sql->execute();
     }
            
    
     //Soma  o tatal de saldo durante o mes
     $sql2 = $pdo->prepare("SELECT time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( SALDO ) ) ),'%H:%i:%s') AS HORA FROM madalozzo_ponto2 where IDFUNCIONARIO = '".$id."' && date_format(DATA_REGISTRO,'%m/%Y')= '".$mesAno."'");

     $sql2->execute();

     $resultado = $sql2->fetch(PDO::FETCH_ASSOC);

     //grava o resultado no banco de dados na tabela banco de horas
     $sql3 = $pdo->prepare("UPDATE BANCO_HORA SET SALDO_BANCO = '".$resultado["HORA"]."'WHERE IDFUNCIONARIO = '".$id."' && MES_ANO= '".$mesAno."'");
     $sql3->execute();


}

function abono($id,$data,$justificativa)
{
   global $pdo;
   $mesAno = date("m/Y");

//Script que realiza a gravação da HE e HF

   $SQL = $pdo->prepare("SELECT HF,HT,JORNADA FROM MADALOZZO_PONTO2  LEFT JOIN MADALOZZO_FUNCIONARIO ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
   
   $SQL->execute();

   $result = $SQL->fetchAll(PDO::FETCH_ASSOC);
   foreach($result as $key => $value)
   {
       
        // Faz o cálculo das horas
        $total = (strtotime($value["HT"]) - strtotime($value["JORNADA"])) + (strtotime($value["HF"]) - strtotime($value["JORNADA"]));

         echo $total;

        // Encontra as horas trabalhadas
        $hours      = floor($total / 60 / 60);

        // Encontra os minutos trabalhados
        $minutes    = round(($total - ($hours * 60 * 60)) / 60);

        // Formata a hora e minuto para ficar no formato de 2 números, exemplo 00
        $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

        // Exibe no formato "hora:minuto"
        $horaT = $hours.':'.$minutes;

        $abono = $value["HF"];

        $horaT = str_replace("-","",$horaT);

        $SQL  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HT = '".$horaT."',HF = '00:00:00',ABONO = '".$abono."',JUSTIFICATIVA_ABONO = '".$justificativa."' WHERE IDFUNCIONARIO = '".$id."' && DATA_REGISTRO = '".$data."'") ;
        $SQL->execute();           
       }                            
          


    //Realiza consulta trazendo o resultado do saldo que é HE - HF esta query filtra da data atual

    $query = $pdo->prepare("SELECT SUBTIME(HE,HF) AS HORA  FROM MADALOZZO_PONTO2 where IDFUNCIONARIO = ".$id."&& DATE(DATA_REGISTRO) ='".$data."'");
    $query->execute();

    $horaBanco = $query->fetch(PDO::FETCH_ASSOC);
    
    //Se o resultado da busca não for vazio é disparado a query que atualiza o saldo do dia 

     if (!empty($horaBanco))
     {
         $sql = $pdo->prepare("UPDATE MADALOZZO_PONTO2 SET SALDO = '".$horaBanco["HORA"]."'WHERE IDFUNCIONARIO = '".$id."' && DATE(DATA_REGISTRO) ='".$data."'");
         $sql->execute();
     }
            
    
     //Soma  o tatal de saldo durante o mes
     $sql2 = $pdo->prepare("SELECT time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( SALDO ) ) ),'%H:%i:%s') AS HORA FROM madalozzo_ponto2 where IDFUNCIONARIO = '".$id."' && date_format(DATA_REGISTRO,'%m/%Y')= '".$mesAno."'");

     $sql2->execute();

     $resultado = $sql2->fetch(PDO::FETCH_ASSOC);

     //grava o resultado no banco de dados na tabela banco de horas
     $sql3 = $pdo->prepare("UPDATE BANCO_HORA SET SALDO_BANCO = '".$resultado["HORA"]."'WHERE IDFUNCIONARIO = '".$id."' && MES_ANO= '".$mesAno."'");
     $sql3->execute();    
}    

function encontraMesF($id)
{
    global $pdo;
    $sql = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO  = $id"); 
    $sql->execute();
    $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
    $select = "<select name = 'mesAno'>";
    foreach($sql as $key => $value)
    {
        $select.="<option value ='".$value["MES_ANO"]."'>".$value["MES_ANO"]."</option>";
    }
    $select.= "</select>";

    echo $select;
}

function responsavel($responsavel)
     {
         global $pdo;
         $sql = $pdo->prepare("SELECT * FROM RESPONSAVEL");
         $sql->execute();
         $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
         $html = "<label for = 'responsavel'>Responsavel: &nbsp &nbsp &nbsp </label>";
         $html.= "<select class  = 'form-control' id ='responsavel' name = 'responsavel'>";

         $sql2 = $pdo->prepare("SELECT NOME FROM RESPONSAVEL WHERE IDRESPONSAVEL = $responsavel");
         $sql2->execute();
         $sql2 = $sql2->fetch(PDO::FETCH_ASSOC);
         $html.= "<option  value =". $responsavel." hidden >".$sql2["NOME"]."</option>";
        
         foreach ($sql as $key => $value)
         {
          
           $html.= "<option value =".$value["IDRESPONSAVEL"].">".$value["NOME"]."</option>";
         }
         $html .="</select>"; 
         echo $html;
     }
         
     
     function responsavelC()
     {
         global $pdo;
         $sql = $pdo->prepare("SELECT * FROM RESPONSAVEL");
         $sql->execute();
         $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
         $html = "<label for = 'responsavel'>Responsavel: &nbsp &nbsp &nbsp </label>";
         $html.= "<select class  = 'form-control' id ='responsavel' name = 'responsavel'>";

        
         foreach ($sql as $key => $value)
         {
          
           $html.= "<option value =".$value["IDRESPONSAVEL"].">".$value["NOME"]."</option>";
         }
         $html .="</select>"; 
         echo $html;
     }










