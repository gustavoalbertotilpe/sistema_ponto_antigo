<?php 
//Script PHP para realizar o cadatro de Funcionario
   require("../../includ/banco.php");
   if (!isset($_POST["nome"]))
   {
       header("location:../");
   }
   else
   {
      $nome  = $_POST["nome"];
      $sobrenome = $_POST["sobrenome"];
      $departamento = $_POST["departamento"];
      $usuario =  $_POST["usuario"];
      $senha = md5($_POST["senha"]);
      $email = $_POST["email"];
      $jornada = $_POST["jornada"];
      $tipoAcesso  = $_POST["tipoacesso"];
      $senha1 = $_POST["senha"];
      $dataAdmissao = $_POST["dataAdmissao"];
      $cpf = $_POST["cpf"];
      $dataNascimento = $_POST["dataNascimento"];
      $primariaEntrada = $_POST["h1"];
      $primeriaSaida = $_POST["h2"];
      $segundaEntrada = $_POST["h3"];
      $segundaSaida = $_POST["h4"];
      $responsavel = $_POST["responsavel"];
 
      $sql = $pdo->prepare("INSERT INTO MADALOZZO_FUNCIONARIO (NOME,SOBRENOME,DEPARTAMENTO,USUARIO,SENHA,EMAIL,JORNADA,TIPO_ACESSO,CPF,DATA_ADMISSAO,DATA_NASCIMENTO,H1,H2,H3,H4,RESPONSAVEL) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

      if ($sql->execute(array($nome,$sobrenome,$departamento,$usuario,$senha,$email,$jornada,$tipoAcesso,$cpf,$dataAdmissao,$dataAdmissao,$primariaEntrada,$primeriaSaida,$segundaEntrada,$segundaSaida,$responsavel)))
      {
          echo "Cadastro Realizado com sucesso!";

          $sql = $pdo->prepare("SELECT  * FROM RESPONSAVEL WHERE IDRESPONSAVEL = '$responsavel'");
          $sql->execute();
          $sql = $sql->fetch(PDO::FETCH_ASSOC);

          include "../_bibliotecas/phpmailer/PHPMailerAutoload.php"; 
        
           // Inicia a classe PHPMailer 
           $mail = new PHPMailer(); 
           
           // Método de envio 
           $mail->IsSMTP(); 
           
           // Enviar por SMTP 
           $mail->Host = "cmail.madalozzocorretora.com.br"; 
           
           // Você pode alterar este parametro para o endereço de SMTP do seu provedor 
           $mail->Port = 587; 
           
           
           // Usar autenticação SMTP (obrigatório) 
           $mail->SMTPAuth = true; 
           
           // Usuário do servidor SMTP (endereço de email) 
           // obs: Use a mesma senha da sua conta de email 
           $mail->Username = 'sgti@madalozzocorretora.com.br'; 
           $mail->Password = 'Brasil2019*'; 
           
           // Configurações de compatibilidade para autenticação em TLS 
           $mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) ); 
           
           // Você pode habilitar esta opção caso tenha problemas. Assim pode identificar mensagens de erro. 
           // $mail->SMTPDebug = 2; 
           
           // Define o remetente 
           // Seu e-mail 
           $mail->From = "sgti@madalozzocorretora.com.br";
           $mail->SMTPSecure = "tls"; // conexão segura com TLS 
           
           // Seu nome 
           $mail->FromName = "Sistema Ponto"; 
            
           
            // Define o(s) destinatário(s) 
            $mail->AddAddress($email, $nome." ".$sobrenome); 
            $mail->AddBCC('financeiro02@madalozzocorretora.com.br', 'Felipe'); 
            $mail->AddBCC($sql["EMAIL"],$sql["NOME"]); 

            $msg ="Ola, ".$nome." ".$sobrenome."<br><br>";
            $msg .="Este é o seu acesso ao Ponto
            Para realizar o acesso ao sistema copie a URL abaixo e salve em seus Favoritos:<br>";
            $msg.="URL: http://srv-101/ponto/  ou 192.168.200.11/ponto <br>";
            $msg .="Usuario: $usuario<br>";
            $msg .="Senha: $senha1<br>";
            $msg.="<h1>IMPORTANTE!</h1>

            * A marcação do ponto deverá ser registrada no início da jornada e no final. Caso o estagiário trabalhe com intervalo de almoço, a marcação deverá ocorrer nas 4 vezes (entrada, saída para almoço, volta do almoço e saída final).<br>
            
            * Lembramos que caso ao final do mês, o estagiário acumule horas extras, caberá ao Supervisor juntamente com a Diretoria validar o pagamento.<br>
            
            * O RH não terá acesso para realizar ajustes manuais de horas não batidas.<br>
            
            * Orientamos o uso do alarme no celular, como forma de alertar sobre a hora de registrar a marcação do ponto.<br>
            
            Qualquer dúvida, solicite orientação do RH.<br>";

            // Opcional: mais de um destinatário
            // $mail->AddAddress('fernando@email.com'); 

            // Opcionais: CC e BCC
            // $mail->AddCC('joana@provedor.com', 'Joana'); 
            // $mail->AddBCC('roberto@gmail.com', 'Roberto'); 

            // Definir se o e-mail é em formato HTML ou texto plano 
            // Formato HTML . Use "false" para enviar em formato texto simples ou "true" para HTML.
            $mail->IsHTML(true); 

            // Charset (opcional) 
            $mail->CharSet = 'UTF-8'; 

            // Assunto da mensagem 
            $mail->Subject = "ACESSO PONTO"; 

            // Corpo do email 
            $mail->Body = $msg; 

            // Opcional: Anexos 
            // $mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf"); 

            // Envia o e-mail 
            $enviado = $mail->Send(); 

            // Exibe uma mensagem de resultado 
            if ($enviado) 
            { 
               
               
            } else { 
                    $mensagem = "Codigo enviado no E-mail cadastrado!"; "Houve um erro enviando o email: ".$mail->ErrorInfo; 
            } 














































          echo"<script>alert('Solcilitação aberta com sucesso!')</script>";
          header("location:../cadastro.php");
      }
      else
      {
          echo "Erro na tentativa de cadastro!";
          header("location:../cadastro.php");
      }

   }