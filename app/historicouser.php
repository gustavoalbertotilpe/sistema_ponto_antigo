<?php 
     require("../includ/conf.php");
     require("../includ/banco.php");
     require("funcoes.php");

     /**
      * pagina PHP responsavel pelo cadastro de novos funcionarios
      */

     $mesAno = date("m/Y");
    
     if (empty($_SESSION["FOTO"]))
     {
        $foto = "padrao.png";
     }
     else 
     {
        $foto = $_SESSION["FOTO"];
     }
            
?>     
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel = "stylesheet" href = "../css/estilo.css">

    <title>Madalozzo</title>
  </head>
  <body>    
    <header class = "topo container-fluid">

         <div class ="row">
            <div class = "col-md-6"></div>

            <div class = "col-md-6">
                 <div class = "saudacao">
                 <div class = "user-dados">
                    <table>
                        <tr>
                            <td><div style = "background-image: url('../img/<?php echo $foto;?>');"; class = "foto-funcionario"></div></td>
                            <td><h5><?php echo $_SESSION["NOME"]." ".$_SESSION["SOBRENOME"];?></h5><h6><?php echo $_SESSION["DEPARTAMENTO"];?></h6></td>
                        </tr>
        
                    </table>        
                </div>    

                 </div>
            </div>

         </div>


    </header>

    <?php 
//verifica o tipo de acesso para que esta pagina não seja acessadas pelos demais usuarios
 if ($_SESSION["TIPO_ACESSO"] == "FUNCIONARIO")
 {
  
?>


<div class = "container-fluid">
    <div class = "row">
        <div class = "col-md-12">
            <div class = "sair">
                <a class = "btn btn-danger" href = "saiu.php">SAIR</a>
            </div>
        </div>
    </div> 
    <div  class = "row">
         <div class = "col-md-7">
        <!--Div vazia!-->    
         </div>
         <div class = "col-md-5">
            
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link " href="./">HOME</a>
                </li>
            </ul>

       </div>
    </div>
<?php 

   $id = $_SESSION["ID"];
   $mesAno = $_GET["mes"];
   
   //Consulta dos dados do funcionario 


   $sqlF = $pdo->prepare("SELECT * FROM MADALOZZO_FUNCIONARIO WHERE ID_FUNCIONARIO = '".$id."'");
   $sqlF->execute();
   $sqlF = $sqlF->fetch(PDO::FETCH_ASSOC);
   

   //Consulta todos os meses no banco de hora
   $sql2 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."'");
   $sql2->execute();
   $sql2= $sql2->fetchAll(PDO::FETCH_ASSOC);



   foreach($sql2 as $key => $value)
   {

       if ($value["MES_ANO"] == $mesAno)
       {
           $saldoMes = $value["SALDO_BANCO"];
       }
   }

  



  
   $sql = $pdo->prepare("SELECT ID,HF,HE,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,ABONO,OBS,JUSTIFICATIVA_ABONO,DATA_REGISTRO AS DATA_ABONO,date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO FROM MADALOZZO_FUNCIONARIO INNER JOIN MADALOZZO_PONTO2 ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && date_format(DATA_REGISTRO,'%m/%Y') ='".$mesAno."' ORDER BY DATA_REGISTRO ASC");                     
    $sql->execute();

    $html = "
    <table class = 'table table-striped'>
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
    </tr>";



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
            </body>
            </html>
        ";

?>
   <div class = "row margin">
        <div class = "col-md-8"> 
            <div> 
                <table>
                   <tr>
                      <td rowspan=3><div class='foto-funcionario2' style = "background-image: url('../img/<?php echo $sqlF["FOTO"];?>');"></div></td>
                      <td>FUNCIONARIO: </td>
                      <td><?php echo $sqlF["NOME"]." ".$sqlF["SOBRENOME"];?></td>
                   </tr>
                   <tr>
                      <td>DEPARTAMENTO: </td>
                      <td><?php echo $sqlF["DEPARTAMENTO"];?></td>
                   </tr>
                   <tr>
                      <td>JORNADA: </td>
                      <td><?php echo $sqlF["JORNADA"];?></td>
                   </tr>
                </table>
            </div>
        </div>
        <div class = "col-md-4">
              <?php 
                 
                  if (!empty($saldoMes))
                  {
                    if ($saldoMes< "00:00:00")
                    {
                        $color = "red";
                    }
                    else 
                    {
                        $color = "green";
                    }
                      echo "<h2>Total de HF/HE do Mês: <span style = 'color:$color'>".$saldoMes."</span>";
                  }    
               ?>   
        </div>
   </div>
   <div class  = "row margin">
   
       <div class = "col-md-12">
           <h3>Historicos de Pontos Registrados do mês <?php echo $mesAno;?></h3>
          <?php echo $html;?>
       </div>
   
   </div>
</div>


    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src = "js/jquery-3.4.1.min.js"></script>
  </body>
</html>








<?php

 }
 else{
   

 //PAGINA DO ADM
?> 

<div class = "container-fluid">
    <div class = "row">
        <div class = "col-md-12">
            <div class = "sair">
                <a class = "btn btn-danger" href = "saiu.php">SAIR</a>
            </div>
        </div>
    </div> 
    <div  class = "row">
         <div class = "col-md-7">
        <!--Div vazia!-->    
         </div>
         <div class = "col-md-5">
            
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link " href="./">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="funcionario.php">FUNCIONARIOS</a>
                </li>
            </ul>

       </div>
    </div>
<?php 

   $id = $_GET['id'];
   $mesAno = $_GET["mes"];
   
   //Consulta dos dados do funcionario 


   $sqlF = $pdo->prepare("SELECT * FROM MADALOZZO_FUNCIONARIO WHERE ID_FUNCIONARIO = '".$id."'");
   $sqlF->execute();
   $sqlF = $sqlF->fetch(PDO::FETCH_ASSOC);
   

   //Consulta todos os meses no banco de hora
   $sql2 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."'");
   $sql2->execute();
   $sql2= $sql2->fetchAll(PDO::FETCH_ASSOC);



   foreach($sql2 as $key => $value)
   {

       if ($value["MES_ANO"] == $mesAno)
       {
           $saldoMes = $value["SALDO_BANCO"];
       }
   }

  



  
   $sql = $pdo->prepare("SELECT ID,HF,HE,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,ABONO,OBS,JUSTIFICATIVA_ABONO,DATA_REGISTRO AS DATA_ABONO,date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO FROM MADALOZZO_FUNCIONARIO INNER JOIN MADALOZZO_PONTO2 ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && DATE_FORMAT(DATA_REGISTRO,'%m/%Y') ='".$mesAno."'ORDER BY DATA_REGISTRO ASC");                     
    $sql->execute();

    $html = "
    <table class = 'table table-striped'>
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
    </tr>";



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
            </body>
            </html>
        ";

?>
   <div class = "row margin">
        <div class = "col-md-8"> 
            <div> 
                <table>
                   <tr>
                      <td rowspan=3><div class='foto-funcionario2' style = "background-image: url('../img/<?php echo $sqlF["FOTO"];?>');"></div></td>
                      <td>FUNCIONARIO: </td>
                      <td><?php echo $sqlF["NOME"]." ".$sqlF["SOBRENOME"];?></td>
                   </tr>
                   <tr>
                      <td>DEPARTAMENTO: </td>
                      <td><?php echo $sqlF["DEPARTAMENTO"];?></td>
                   </tr>
                   <tr>
                      <td>JORNADA: </td>
                      <td><?php echo $sqlF["JORNADA"];?></td>
                   </tr>
                </table>
            </div>
        </div>
        <div class = "col-md-4">
              <?php 
                 
                  if (!empty($saldoMes))
                  {
                    if ($saldoMes< "00:00:00")
                    {
                        $color = "red";
                    }
                    else 
                    {
                        $color = "green";
                    }
                      echo "<h2>Total de HF/HE do Mês: <span style = 'color:$color'>".$saldoMes."</span>";
                  }    
               ?>   
        </div>
   </div>
   <div class  = "row margin">
   
       <div class = "col-md-12">
           <h3>Historicos de Pontos Registrados do mês <?php echo $mesAno;?></h3>
          <?php echo $html;?>
       </div>
   
   </div>
</div>


    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src = "js/jquery-3.4.1.min.js"></script>
  </body>
</html>
<?php
}
?>



