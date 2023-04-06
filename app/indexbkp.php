<?php 
     require("../includ/conf.php");
     require("../includ/banco.php");
     require("funcoes.php");

     

     $mesAno = date("m/Y");
    
     if (empty($_SESSION["FOTO"]))
     {
        $foto = "padrao.png";
     }
     else 
     {
        $foto = $_SESSION["FOTO"];
     }

     /**
      * Pagina index, nesta pagina é para os dois tipos de usuario tanto adm quanto funcionario
      */
            
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
//Verifica o tipo de usuario se for Funcionario sera renderizado a pagina do usuario comun
 if ($_SESSION["TIPO_ACESSO"] == "FUNCIONARIO")
 {
?>
<?php
    //Cria um banco de horas de acordo com cada mês  
    $sql = $pdo->prepare("SELECT * FROM BANCO_HORA WHERE MES_ANO = '".$mesAno."' && IDFUNCIONARIO =".$_SESSION["ID"]."");
    $sql->execute();
    $consultaBandoHoraMes = $sql->fetchAll(PDO::FETCH_ASSOC);
     
    if (empty($consultaBandoHoraMes))
    {
 
       $sql2  = $pdo->prepare("INSERT INTO BANCO_HORA (IDFUNCIONARIO,MES_ANO) VALUES ('".$_SESSION["ID"]."','".$mesAno."')");
       $sql2->execute();
      
    }

    //Resgata o valor que esta gravado no banco de horas

?>


    <div class = "container-fluid">
        <div class = "row">
            <div class = "col-md-6">
                <h4>Jornada de trabalho de <?php echo $_SESSION["JORNADA"];?> Horas</h4>
            </div>
            <div class = "col-md-6">
                <div class = "sair">
                    <a class = "btn btn-danger" href = "saiu.php">SAIR</a>
                </div>
            </div>  
        </div>

        <div class = "row">
            <div class = "col-md-12"> 
               
            </div>
        </div>
    
        <div class = "row">

        <div class = "col-md-12">
              <h4>Saldo de Hora do mês <?php echo $mesAno;?></h4>
           
               <?php 

                    $sql = $pdo->prepare("SELECT  HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2 FROM MADALOZZO_PONTO2  WHERE IDFUNCIONARIO = ".$_SESSION["ID"]." && DATE(DATA_REGISTRO) = CURDATE() ORDER BY  DATA_REGISTRO DESC");
                    $sql->execute();
                    $result = $sql->fetch(PDO::FETCH_ASSOC);


                    if (!empty($result["HORA_SAIDA1"]))
                    {
                        //Realiza consulta trazendo o resultado do saldo que é HE - HF esta query filtra da data atual

                        $query = $pdo->prepare("SELECT SUBTIME(HE,HF) AS HORA  FROM MADALOZZO_PONTO2 where IDFUNCIONARIO = ".$_SESSION["ID"]."&& DATE(DATA_REGISTRO) = CURDATE()");
                        $query->execute();

                        $horaBanco = $query->fetch(PDO::FETCH_ASSOC);
                        
                        //Se o resultado da busca não for vazio é disparado a query que atualiza o saldo do dia 

                            if (!empty($horaBanco))
                            {
                                $sql = $pdo->prepare("UPDATE MADALOZZO_PONTO2 SET SALDO = '".$horaBanco["HORA"]."'WHERE IDFUNCIONARIO = '".$_SESSION["ID"]."' && DATE(DATA_REGISTRO) = CURDATE()");
                                $sql->execute();
                            }
                                

                            //Soma  o tatal de saldo durante o mes
                            $sql2 = $pdo->prepare("SELECT time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( SALDO ) ) ),'%H:%i:%s') AS HORA FROM madalozzo_ponto2 where IDFUNCIONARIO = '".$_SESSION["ID"]."' && date_format(DATA_REGISTRO,'%m/%Y')= '".$mesAno."'");

                            $sql2->execute();

                            $resultado = $sql2->fetch(PDO::FETCH_ASSOC);

                            //grava o resultado no banco de dados na tabela banco de horas
                            $sql3 = $pdo->prepare("UPDATE BANCO_HORA SET SALDO_BANCO = '".$resultado["HORA"]."'WHERE IDFUNCIONARIO = '".$_SESSION["ID"]."' && MES_ANO= '".$mesAno."'");
                            $sql3->execute();

                            if ($resultado["HORA"]< "00:00:00")
                            {
                                $color = "#eb372a";   
                            }
                            else 
                            {
                                $color = "#2aeb84";
                            }
                            
                            echo "<div style = 'background-color:".$color.";width:50%; max-width:400px'>";
                            echo $resultado["HORA"];
                            echo "</div>";

                    }

                   


                  
                
                   /*foreach($resultadoHoraE as $key => $value)
                   {
                       if ($value["TOTAL_HORAS"] < "00:00:00")
                       {
                           $color = "#eb372a";   
                       }
                       else 
                       {
                           $color = "#2aeb84";
                       }
                       $totalHora = $value["TOTAL_HORAS"];

                       echo "<div style = 'background-color:".$color.";width:50%; max-width:400px'>";
                       echo $totalHora ;
                       echo "</div>";
                   }

                   ;*/

               ?>
           
           </div>
          
            <div class = "col-md-12">
              
              <div class = "form-ponto">
                 <!--Formulario com botão responsavel pela batida do ponto!-->
                  <form action = 'baterponto.php' method = 'POST'>
                     <input type = 'hidden' value = '<?php echo $_SESSION["ID"];?>' name = 'id_funcionario'>
                         <div class = "form-group">
                             <button class ="btn btn-primary btn-block btn-lg " type = 'submit'>Registrar Ponto</button>
                         </div>    
                  </form>
              
              </div>


            </div>
        </div>

        <div class = "row">

            <div class = "col-md-6 borda">
            
                <?php 
                //Escript que gera o relatorio de ponto batido da data atual
                //Query realiza a consulta de horas extra/horas faltando

                      $sql = $pdo->prepare("SELECT SUBTIME(jornada,ht) as HORA, HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,HE,HF,JORNADA FROM MADALOZZO_PONTO2  LEFT JOIN MADALOZZO_FUNCIONARIO ON ID_FUNCIONARIO = IDFUNCIONARIO  WHERE IDFUNCIONARIO = ".$_SESSION["ID"]." && DATE(DATA_REGISTRO) = CURDATE() ORDER BY  DATA_REGISTRO DESC");
                      $sql->execute();
                      $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                      if (empty($result))
                      {
                           echo "Nenhum ponto registrado hoje";
                      }
                      else 
                      {
                      echo "<h2>Pontos registrado  hoje</h2>";
                      echo "<table class = 'table table-striped' >";
                      echo "<tr>";
                      echo "<td>ENTRADA 1</td>";
                      echo "<td>SAIDA 1</td>";
                      echo "<td>ENTRADA 2</td>";
                      echo "<td>SAIDA 2</td>";
                      echo "<td>HORA TRABALHADA</td>";
                      foreach($result as $key => $value)
                      {
                           //Script que realiza a gravação da HE e HF 

                           if (!empty($value["HORA_SAIDA1"]) || $value["HORA_SAIDA2"])
                           {

                                if ($value["HORA"] < "00:00:00")
                                {
                                    $hora = str_replace("-","",$value["HORA"]);
                                    $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HF = '".$hora."' WHERE IDFUNCIONARIO = '".$_SESSION["ID"]."' && DATE(DATA_REGISTRO) = CURDATE()");
                                    $sql2->execute();
                                }
                                else
                                {
                                    $sql2  = $pdo->prepare("UPDATE  MADALOZZO_PONTO2 SET HE = '".$value["HORA"]."' WHERE IDFUNCIONARIO = '".$_SESSION["ID"]."'&& DATE(DATA_REGISTRO) = CURDATE()");
                                    $sql2->execute();
                                
                                }       
                            }                          
                            
                            echo "<tr>
                            <td>".$value["HORA_ENTRADA1"]."</td>
                            <td>".$value["HORA_SAIDA1"]."</td>
                            <td>".$value["HORA_ENTRADA2"]."</td>
                            <td>".$value["HORA_SAIDA2"]."</td>
                            <td>".$value["HT"]."</td>
                            ";                          
                                        
                    }
                    echo "</table>";
                    }
                    $dataAtual = date("d/m/Y");


                ?>
            
            
            </div>
        
        </div>
        <div class = "row margin ">
           <h2>Historico Pontos registrado</h2>
           <div class = "col-md-12 history">
                 <?php
                    // Escript que gera relatorio do historico de ponto

                        $sql = $pdo->prepare("SELECT  date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO,HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,SALDO,HF,HE FROM MADALOZZO_PONTO2 WHERE IDFUNCIONARIO = '".$_SESSION["ID"]. "'ORDER BY  DATA_REGISTRO DESC");
                        $sql->execute();
                        while ($row = $sql->fetch(PDO::FETCH_ASSOC))
                        {            

                            echo "<table class = 'table table-striped' >";
                            echo "<tr><td colspan =3>DATA: ".$row["DATA_REGISTRO"]."<td></tr>";
                            echo "<tr>";
                            echo "<td>ENTRADA 1</td>";
                            echo "<td>SAIDA 1</td>";
                            echo "<td>ENTRADA 2</td>";
                            echo "<td>SAIDA 2</td>";
                            echo "<td>HT</td>";
                            echo "<td>HF</td>";
                            echo "<td>HE</td>";
                            echo "<td>SALDO DO DIA</td>";

                            echo "<tr>
                            <td>".$row["HORA_ENTRADA1"]."</td>
                            <td>".$row["HORA_SAIDA1"]."
                            <td>".$row["HORA_ENTRADA2"]."</td>
                            <td>".$row["HORA_SAIDA2"]."
                            <td>".$row["HT"]."</td>
                            <td>".$row["HF"]."</td>
                            <td>".$row["HE"]."</td>
                            <td>".$row["SALDO"]."</td>
                            ";  

                
                        }
                
                 
                 ?>


           </div>
        </div>
    
    </div>
    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>

<?php 
 }
 else{
   

 //Renderização da pagina do ADM
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
            
         </div>
         <div class = "col-md-5">
            
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link " href="cadastro.php">CADASTRAR NOVO FUNCIONARIO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="funcionario.php">FUNCIONARIOS</a>
                </li>
            </ul>

       </div>
    </div>
   
   <div class = "row">
   
        <div class = "col-md-12"> 
           
           <h3> Ultimos pontos Registrados HOJE</h3>
           <!--Div responsavel por exibir a tabela com os relatorios de pontos dos funcinarios batidos na data atual!-->
           <div id = "ultimosPontosBatidosHj" class = "relatorio-pontos-batidos">
           
           </div>

        
        </div>

   </div>



</div>



    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src = "js/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function(){  

            //Função responsavel por fazer requisão ajax da pagina consultaPonto.php que e responsavel por gerar o relatorio de ponto batido
                function atualizaPontoBatidoHj(){
                        $.ajax({
                            url:'includ/consultaPonto.php',
                            success: function(data){
                                $('#ultimosPontosBatidosHj').html(data);
                            }
                        });
                }
            //chama a função a cada 2 segundos
            atualizaPontoBatidoHj();
            window.setInterval(atualizaPontoBatidoHj,2000);
                   
        })            

    
    
    </script>
  </body>
</html>


<?php
}
?>



