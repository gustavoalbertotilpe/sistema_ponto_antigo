<?php
 session_start();
 if(empty($_SESSION["NOME"])){
     header("location:../");
 }
    include("../_bibliotecas/mpdf/mpdf.php");
    include('../../includ/banco.php');
?>
<?php 
date_default_timezone_set('America/Sao_Paulo');
$data = date("d/m/Y  H:i:s");
$ano = date("Y");
$mes_ano = $_POST["mes"];
$id = $_POST["id"];

$sqlF = $pdo->prepare("SELECT NOME,SOBRENOME,DEPARTAMENTO,JORNADA,SALDO_BANCO,CPF,date_format(DATA_ADMISSAO,'%d/%m/%Y') AS DATA_ADMISSAO,H1,H2,H3,H4 FROM MADALOZZO_FUNCIONARIO  INNER JOIN BANCO_HORA ON IDFUNCIONARIO = ID_FUNCIONARIO WHERE ID_FUNCIONARIO = '".$id."' && MES_ANO = '".$mes_ano."'");
$sqlF->execute();
$sqlF = $sqlF->fetch(PDO::FETCH_ASSOC);
$H  = $sqlF["SALDO_BANCO"];
$td = "<td>DAS ".$sqlF["H1"]." A ".$sqlF["H2"]."</td>";

   if ($sqlF["H3"] != "00:00:00")
   {
    $td.= "<td> E DAS ".$sqlF["H3"]." A ".$sqlF["H4"]."</td>";
   }
$html.="
<!DOCTYPE html>
<html lang='pt-br'>
  <head>
    <meta charset='utf-8'>
    <title>Madalozzo</title>
    <style>
       body
       {
           margin:0;
           padding:0;
           font-family:arial;
       }    
    </style>
  </head>
  <body>    
      
       <table>
          <tr>
             <td>NOME </td>
             <td>".$sqlF["NOME"]." ".$sqlF["SOBRENOME"]."</td>
           </tr>
           <tr>
             <td>CPF</td>
             <td>".$sqlF["CPF"]."</td>
           </tr>
           <tr>
             <td>DATA INICIO</td>
             <td>".$sqlF["DATA_ADMISSAO"]."</td>
           </tr>
           <tr>
             <td>DEPARTAMENTO</td>
             <td>".$sqlF["DEPARTAMENTO"]."</td>
           </tr>
           <tr>
             <td>JORNADA</td>
             <td>".$sqlF["JORNADA"]." Horas diaria</td>
             ".$td."
           </tr>
        </table>  
        <br>
        <br>
        <div>
           <table border ='1'>
              <tr>
                 <th>DATA</th>
                 <th>ENTRADA 1</th>
                 <th>SAIDA 1</th>
                 <th>ENTRADA 2</th>
                 <th>SAIDA 2</th>
                 <th>HT</th>
                 <th>HF</th>
                 <th>HE</th>  
                 <th>ABONADO</th>
                 <th>JUSTIFICATIVA</th>
              </tr>
";

$sql = $pdo->prepare("SELECT ID,HF,HE,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,ABONO,OBS,JUSTIFICATIVA_ABONO,DATA_REGISTRO AS DATA_ABONO,date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO FROM MADALOZZO_FUNCIONARIO INNER JOIN MADALOZZO_PONTO2 ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && date_format(DATA_REGISTRO,'%m/%Y') = '".$mes_ano."';");                     
$sql->execute();
while($row = $sql->fetch(PDO::FETCH_ASSOC)){
    $html.="
    <tr>
    <td>".$row["DATA_REGISTRO"]."</td>
    <td>".$row["HORA_ENTRADA1"]."</td>
    <td>".$row["HORA_SAIDA1"]."</td>
    <td>".$row["HORA_ENTRADA2"]."</td>
    <td>".$row["HORA_SAIDA2"]."</td>
    <td>".$row["HT"]."</td>
    <td>".$row["HF"]."</td>
    <td> ".$row["HE"]."</td>
    
    ";
    if (!empty($row["ABONO"]))
        {
            $html.="<td>".$row["ABONO"]."</td>";
            $html.="<td>".$row["JUSTIFICATIVA_ABONO"]."</td>";
        }

        if (!empty($row["OBS"]))
        {
            $html.="<td></td>";
            $html.="<td> ".$row["OBS"]."</td>";
        }
        $html.="</tr>";
}
$html.="
        </table>
        
        </div>
        <h3>Total de HF/HE do mês: ".$H."</h3>
        <h3>Assinatura:</h3>
        <hr>
        </body>
        </html>
     ";

    $mpdf=new mPDF(); 
    $mpdf->AddPage('L');
    $mpdf->WriteHTML($html);
    $mpdf->Output();

?>
 