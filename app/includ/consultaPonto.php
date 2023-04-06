  <?php
    session_start();
    //Script PHP para consulta dos pontos batidos durate do decorrer da data atual funcionalidade da pagina ADM
    
    if ($_SESSION["TIPO_ACESSO"] == "FUNCIONARIO")
    {
      header("location:../");
    }
    else{
    require("../../includ/banco.php");    
    $data = date("Y-m-d");

    $sql = $pdo->prepare("SELECT HORA_ENTRADA1,HORA_SAIDA1,HORA_ENTRADA2,HORA_SAIDA2,HT,HF,HE,date_format(DATA_REGISTRO,'%d/%m/%Y') as DATA_REGISTRO,madalozzo_funcionario.NOME AS NOME,SOBRENOME,JORNADA,DEPARTAMENTO,H1,H2,H3,H4,RESPONSAVEL.NOME AS NOME_RESPONSAVEL FROM MADALOZZO_PONTO2 LEFT JOIN MADALOZZO_FUNCIONARIO ON IDFUNCIONARIO = ID_FUNCIONARIO INNER JOIN RESPONSAVEL ON RESPONSAVEL = IDRESPONSAVEL WHERE DATA_REGISTRO = '".$data."'ORDER BY NOME ASC");
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

    if (empty($resultado))
    {
        $html = "Nenhum ponto registrado hoje";
    }
    else
    {
      echo "<div class ='ponto-batido-adm'>";
      foreach($resultado as $key => $value)
      {
    ?>
      <div class ='ponto-batido-adm-2'>
           <ul>
              <li>Nome:  <?php echo $value["NOME"]." ".$value["SOBRENOME"];?></li>
              <li>Departamento: <?php echo $value["DEPARTAMENTO"];?></li>
              <li>Jornada: <?php echo $value["JORNADA"];?></li>
              <?php
                  if (empty($value["HORA_ENTRADA1"]) == FALSE)
                  {
                    echo "<li>Primeira Entrada: ".$value["HORA_ENTRADA1"]." Horas</li>";
                  }
                  if (empty($value["HORA_SAIDA1"]) == FALSE)
                  {
                    echo "<li>Primeira Saída: ".$value["HORA_SAIDA1"]." Horas</li>";
                  }
                  if (empty($value["HORA_ENTRADA2"]) == FALSE)
                  {
                    echo "<li>Segunda Entrada: ".$value["HORA_ENTRADA2"]." Horas</li>";
                  }
                  if (empty($value["HORA_SAIDA2"]) == FALSE)
                  {
                    echo "<li>Segunda Saída: ".$value["HORA_SAIDA2"]." Horas</li>";
                  }
              ?>
           </ul>
           
      </div>
    <?php
      }
      echo "</div>";
    }
   
}
?>