<?php 
     require("../includ/conf.php");
     require("../includ/banco.php");
     require("funcoes.php");

     /**
      * pagina PHP responsavel pelo cadastro de novos funcionarios
      */

     $mesAno = date("m/Y");
    


     $foto = $_SESSION["FOTO"];
  
            
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
    echo "Acesso não permitido";
?>

<!--

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
         </div>
         <div class = "col-md-5">
            
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link " href="./">HOME</a>
                </li>
            </ul>

       </div>
    </div>
  !-->  
<?php 
/*
   $id = $_SESSION["ID"];
   
   $mesAno = date("m/Y");
   
   //Consulta dos dados do funcionario 


   $sqlF = $pdo->prepare("SELECT * FROM MADALOZZO_FUNCIONARIO WHERE ID_FUNCIONARIO = '".$id."'");
   $sqlF->execute();
   $sqlF = $sqlF->fetch(PDO::FETCH_ASSOC);
   
   $JORNADA = $sqlF["JORNADA"];

   //Consulta todos os meses no banco de hora
   $sql2 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."'");
   $sql2->execute();
   $sql2= $sql2->fetchAll(PDO::FETCH_ASSOC);

   $selectMes = "<select name = 'mes' class = 'form-control'>";

   foreach($sql2 as $key => $value)
   {
       $selectMes.="<option value = '".$value["MES_ANO"]."'>".$value["MES_ANO"]."</option>";

       if ($value["MES_ANO"] == $mesAno)
       {
           $saldoMes = $value["SALDO_BANCO"];
       }
   }

 
   $selectMes .= "</select>";




//realiza uma consulta com as informações do historico 

   if (isset($_POST["mes"]) && empty($_POST["mes"]) == FALSE)
   {
        $sql3 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."' && MES_ANO = '".$_POST["mes"]."'");
        $sql3->execute(); 
   }
   else 
   {
        $sql3 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."' ");
        $sql3->execute(); 
   }

   $sql3= $sql3->fetchAll(PDO::FETCH_ASSOC);


   $htmlH = "<table class = 'table table-striped'>";
   $htmlH .= "<tr>";
   $htmlH.="<th>Mês/Ano</th>";
   $htmlH.= "<th>Saldo</th>";
   $htmlH.="<th>Ação</th>";
   $htmlH.="</tr>";

   
   foreach($sql3 as $key => $value)
   {
       if ($value["SALDO_BANCO"] < "00:00:00")
       {
          $color  ="red";
       }
       else
       {
           $color = "green";
       }
       $htmlH.= "<tr>";
       $htmlH.= "<td>".$value["MES_ANO"]."</td>";
       $htmlH.="<td style = 'color:$color'>".$value["SALDO_BANCO"]."</td>";
       $htmlH.="<td><a href ='historicouser.php?mes=".$value["MES_ANO"]."'>Abrir</td>";
       $htmlH.="</tr>";
   } 
   $htmlH.="</table>";





  
   $sql = $pdo->prepare("SELECT ID,HF,HE,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,ABONO,OBS,JUSTIFICATIVA_ABONO,DATA_REGISTRO AS DATA_ABONO,date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO FROM MADALOZZO_FUNCIONARIO INNER JOIN MADALOZZO_PONTO2 ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && DATE_FORMAT(DATA_REGISTRO,'%m/%Y') ='$mesAno' ORDER BY DATA_REGISTRO ASC");                     
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
            $html.="<td> ".$row["JUSTIFICATIVA_ABONO"]."</td>";
        }

        if (!empty($row["OBS"]))
        {
            $html.="<td></td>";
            $html.="<td> ".$row["OBS"]."</td>";
        }
       
    }
    $html.="
            </table>
            </div>
            </body>
            </html>
        ";



  
*/

?>
<!--
   <div class = "row margin">
        <div class = "col-md-8"> 
            <div> 
                <table>
                   <tr>
                      <td rowspan=3><div class='foto-funcionario2' style = "background-image: url('../img/<?php echo $sqlF["FOTO"];?>');"></div></td>
                      <td>FUNCIONARIO: </td>
                      <td><?php //echo $sqlF["NOME"]." ".$sqlF["SOBRENOME"];?></td>
                   </tr>
                   <tr>
                      <td>DEPARTAMENTO: </td>
                      <td><?php //echo $sqlF["DEPARTAMENTO"];?></td>
                   </tr>
                   <tr>
                      <td>JORNADA: </td>
                      <td><?php //echo $sqlF["JORNADA"];?></td>
                   
                   <td>DAS <?php// echo $sqlF["H1"]." A ".$sqlF["H2"];?></td>
                      <?php 
                         /*if ($sqlF["H3"] != "00:00:00")
                         {
                            echo "<td> E DAS ".$sqlF["H3"]." A ".$sqlF["H4"]."</td>";
                         }
                         */
                      ?>   
                      </tr>
                </table>
            </div>
        </div>
        <div class = "col-md-4">
              <?php 
               /*
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
                  */ 
               ?>   
              
        </div>
   </div>
   <div class  = "row margin">
   
       <div class = "col-md-12">
          <h3>PONTOS REGISTRADO DO MÊS ATUAL</h3>
          <?php //echo $html;?>
       </div>
   
   </div>
</div>
 <div class = "row margin">
    <div class = "col-md-12">
        <div style = "width:33.3%">
                    
            <label>Filtrar por Mês/Ano</label>
            <form action = "" method = "POST">
                <div class="input-group mb-3">
                    <?php //echo $selectMes?>
                    <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </div>
        
        </div>
    </div>
    <div class = "col-md-12">
       <h3>Historicos de Pontos Registrados</h3>

       <?php //echo $htmlH;?>
    </div>
 </div>

</div>
-->
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
   
   $mesAno = date("m/Y");
   
   //Consulta dos dados do funcionario 


   $sqlF = $pdo->prepare("SELECT MADALOZZO_FUNCIONARIO.NOME,SOBRENOME,MADALOZZO_FUNCIONARIO.EMAIL,JORNADA,DATA_REGISTRO_FUNCIONARIO,FOTO,TIPO_ACESSO,STATUS_USUARIO,DEPARTAMENTO,USUARIO,DATA_ADMISSAO,CPF,DATA_NASCIMENTO,H1,H2,H3,H4,RESPONSAVEL.NOME AS NOME_RESPONSAVEL FROM MADALOZZO_FUNCIONARIO LEFT JOIN RESPONSAVEL ON RESPONSAVEL = IDRESPONSAVEL WHERE ID_FUNCIONARIO = '".$id."'");
   $sqlF->execute();
   $sqlF = $sqlF->fetch(PDO::FETCH_ASSOC);
   
   $JORNADA = $sqlF["JORNADA"];

   //Consulta todos os meses no banco de hora
   $sql2 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."'");
   $sql2->execute();
   $sql2= $sql2->fetchAll(PDO::FETCH_ASSOC);

   $selectMes = "<select name = 'mes' class = 'form-control'>";

   foreach($sql2 as $key => $value)
   {
       $selectMes.="<option value = '".$value["MES_ANO"]."'>".$value["MES_ANO"]."</option>";

       if ($value["MES_ANO"] == $mesAno)
       {
           $saldoMes = $value["SALDO_BANCO"];
       }
   }

 
   $selectMes .= "</select>";
?>
   <div class = "row">
   <div class = "col-md-2">
           <ul class="nav nav-pills">
               <li class="nav-item">
                   <a class = "btn btn-primary" href ='editar.php?id=<?php echo $id;?>'>Editar</a> 
               </li>
           </ul>
   </div>
   <div class = "col-md-2">
    <form action = 'includ/relatorio.php' method = 'POST'>
        <div class = 'form-group'>
                <h4>Gerar Relatorio</h4>
                <?php echo $selectMes;?>
            </div>
        <input class = 'form-control' type = 'hidden' value = '<?php echo $id;?>' name = 'id'>
        <button type='submit' class='btn btn-primary'>Gerar</button>
    </form>
   </div>
</div>

<?php
//realiza uma consulta com as informações do historico 

   if (isset($_POST["mes"]) && empty($_POST["mes"]) == FALSE)
   {
        $sql3 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."' && MES_ANO = '".$_POST["mes"]."'");
        $sql3->execute(); 
   }
   else 
   {
        $sql3 = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE IDFUNCIONARIO = '".$id."' ");
        $sql3->execute(); 
   }

   $sql3= $sql3->fetchAll(PDO::FETCH_ASSOC);


   $htmlH = "<table class = 'table table-striped'>";
   $htmlH .= "<tr>";
   $htmlH.="<th>Mês/Ano</th>";
   $htmlH.= "<th>Saldo</th>";
   $htmlH.="<th>Ação</th>";
   $htmlH.="</tr>";

   
   foreach($sql3 as $key => $value)
   {
       if ($value["SALDO_BANCO"] < "00:00:00")
       {
          $color  ="red";
       }
       else
       {
           $color = "green";
       }
       $htmlH.= "<tr>";
       $htmlH.= "<td>".$value["MES_ANO"]."</td>";
       $htmlH.="<td style = 'color:$color'>".$value["SALDO_BANCO"]."</td>";
       $htmlH.="<td><a href ='historicouser.php?mes=".$value["MES_ANO"]."&id=".$id."'>Abrir</td>";
       $htmlH.="</tr>";
   } 
   $htmlH.="</table>";





  
   $sql = $pdo->prepare("SELECT ID,HF,HE,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,ABONO,OBS,JUSTIFICATIVA_ABONO,DATA_REGISTRO AS DATA_ABONO,date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO FROM MADALOZZO_FUNCIONARIO INNER JOIN MADALOZZO_PONTO2 ON ID_FUNCIONARIO = IDFUNCIONARIO WHERE IDFUNCIONARIO = ".$id." && DATE_FORMAT(DATA_REGISTRO,'%m/%Y') ='$mesAno' ORDER BY DATA_REGISTRO ASC");                     
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
        if ($row["HF"] != "00:00:00")
        {
            $html.= "<td> <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#id".$row["ID"]."'>ABONAR</button></td>
            </tr>     
            
            <div class='modal fade' id='id".$row["ID"]."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>ABONAR</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action = 'abono.php' method = 'POST'>
                       <div class = 'form-group'>
                           <label>Horas faltando que sera abonada</label>
                           <input class = 'form-control' type = 'time' value = '".$row["HF"]."' disabled>
                       </div>
                       <div class = 'form-group'>
                           <label>Data Abono</label>
                           <input class = 'form-control' type = 'date' value = '".$row["DATA_ABONO"]."' name = 'data' >
                       </div>
                       
                       <div class = 'form-group'>
                           <label>Justificativa</label>
                           <textarea class = 'form-control' name = 'justificativa'></textarea>
                       </div>
                       <input class = 'form-control' type = 'hidden' value = '".$id."' name = 'id'>
                       <button type='submit' class='btn btn-primary'>Salvar mudanças</button>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                    
                </div>
                </div>
            </div>
            </div>
            ";
        }
        if (!empty($row["ABONO"]))
        {
            $html.="<td>".$row["ABONO"]."</td>";
            $html.="<td> ".$row["JUSTIFICATIVA_ABONO"]."</td>";
        }

        if (!empty($row["OBS"]))
        {
            $html.="<td></td>";
            $html.="<td> ".$row["OBS"]."</td>";
        }
       
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
                      <td>RESPONSAVEL: </td>
                      <td><?php echo $sqlF["NOME_RESPONSAVEL"];?></td>
                   </tr>
                   <tr>
                      <td>JORNADA: </td>
                      <td><?php echo $sqlF["JORNADA"];?></td>
                   
                      <td>DAS <?php echo $sqlF["H1"]." A ".$sqlF["H2"];?></td>
                      <?php 
                         if ($sqlF["H3"] != "00:00:00")
                         {
                            echo "<td> E DAS ".$sqlF["H3"]." A ".$sqlF["H4"]."</td>";
                         }
                      ?>   
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
            
        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal2'>AGENDAMENTO FOLGA</button></td>
        <div class='modal fade bd-example-modal-lg ' id='modal2' tabindex='-1' role='dialog' >
        <div class='modal-dialog modal-lg' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLabel'>FOLGA</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <form action = 'folga.php' method = 'POST'>
                   <div class = 'form-group'>
                       <label>DATA FALGA</label>
                       <input class = 'form-control' type = 'date' name = 'data'>
                   </div>
                   <div class = 'form-group'>
                           <h4>DESCONTAR DAS HORAS EXTRAS?</h4>
                           <label>NÃO
                           <input type = 'radio' name = 'tipoDesconto' value = 'nao'  checked>
                           </label>
                           <label>SIM
                           <input type = 'radio' name = 'tipoDesconto' value = 'sim'>
                           </label>
                       </div>
                    <div class ="form-group">
                       <h4>Em caso de desconto na hora extra selecione o mês a ser descontado</h4>
                       <?php encontraMesF($id);?>
                    </div>
                   <div class = 'form-group'>
                       <label>Justificativa</label>
                       <textarea class = 'form-control' name = 'justificativa'></textarea>
                   </div>
                   <input class = 'form-control' type = 'hidden' value = '<?php echo $id;?>' name = 'id'>
                   <input type = 'hidden' value = '<?php  echo $JORNADA;?>' name = 'jornada'>
                   <button type='submit' class='btn btn-primary'>AGENDAR</button>
                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                
            </div>
            </div>
        </div>
        </div>


        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal3'>AGENDAMENTO FERIAS </button></td>
            
        
        <div class='modal fade bd-example-modal-lg ' id='modal3' tabindex='-1' role='dialog' >
        <div class='modal-dialog modal-lg' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLabel'>FERIAS</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <form action = 'ferias.php' method = 'POST'>
                   <div class = 'form-group'>
                           <h4>Qual é o periodo de ferias?</h4>
                           <input type = 'date' name = 'inicio'>
                            ATÉ
                           <input type = 'date' name = 'fim'>
                     </div>
                   <input class = 'form-control' type = 'hidden' value = '<?php echo $id;?>' name = 'id'>
                   <input type = 'hidden' value = '<?php  echo $JORNADA;?>' name = 'jornada'>
                   <button type='submit' class='btn btn-primary'>AGENDAR</button>
                </form>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                
            </div>
            </div>
        </div>
        </div>


        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal4'>AFASTAMENTO </button></td>
            
        
            <div class='modal fade bd-example-modal-lg ' id='modal4' tabindex='-1' role='dialog' >
            <div class='modal-dialog modal-lg' role='document'>
                <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>AFASTAMENTO</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>
                    <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action = 'afastamento.php' method = 'POST'>
                       <div class = 'form-group'>
                               <h4>Qual é o periodo do Afastamento?</h4>
                               <input type = 'date' name = 'inicio'>
                                ATÉ
                               <input type = 'date' name = 'fim'>
                         </div>
                         <div class = 'form-group'>
                               <h4>MOTIVO</h4>
                               <textarea class = "form-control" name = 'justificativa'></textarea>
                         </div>
                       <input class = 'form-control' type = 'hidden' value = '<?php echo $id;?>' name = 'id'>
                       <input type = 'hidden' value = '<?php  echo $JORNADA;?>' name = 'jornada'>
                       <button type='submit' class='btn btn-primary'>AGENDAR</button>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                    
                </div>
                </div>
            </div>
            </div>






        </div>
   </div>
   <div class  = "row margin">
   
       <div class = "col-md-12">
          <h3>PONTOS REGISTRADO DO MÊS ATUAL</h3>
          <?php echo $html;?>
       </div>
   
   </div>
</div>
 <div class = "row margin">
    <div class = "col-md-12">
        <div style = "width:33.3%">
                    
            <label>Filtrar por Mês/Ano</label>
            <form action = "" method = "POST">
                <div class="input-group mb-3">
                    <?php echo $selectMes?>
                    <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </div>
            </div>
        
        </div>
    </div>
    <div class = "col-md-12">
       <h3>Historicos de Pontos Registrados</h3>

       <?php echo $htmlH;?>
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



