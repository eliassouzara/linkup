<?php

    // Inculi a configurção
    include('../config/config.php');

    // Se o metado post email existe
    if(isset ($_POST['email'])){

    // Pega o email e a senha fornecidos pelo usuário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Faz uma consulta SQL para selecionar da tabela usuário o email digitado
    $sql_code = "SELECT * FROM usuarios WHERE email = ? LIMIT 1 ";

    // Prepara a consulta SQL
    $stmt = $conn->prepare($sql_code);
    
    // Lifa o parâmetro da consulta ao valor do email
    $stmt-> bind_param("s", $email);

    // Executa a consulta
    $stmt-> execute();
    
    // Obtem o resultado da consulta
    $result = $stmt->get_result();

    // Verifica se a consulta retornoualgum resultado
    if($result->num_rows > 0){
    //Pega os dados do usuário como um array associativo
        $usuario = $result->fetch_assoc();
    
    // Verificar se a senha fornecida corresponde á senha armazenada no banco de dados
    if(password_verify($senha, $usuario['senha'])){
        // Inicia a sessão
        if(!isset($_SESSION)){
            session_start();
        }

    // Armazena informações do usuário na sessão
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];

    // Redirecionar o usuário para o home
    header('Location: index.php');
    exit;
    } else{
        //Exibe erro
        echo "Falha ao logar! Por favor, tente novamente";
    }
    }
    }


?>



<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>login</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
  </head>
  <body>
   <div class="content">
    <div class="flex-div">
        <div class="nome-content">
            <h1 class="logo">Linkup</h1>
            <p>Entrar na sua conta</p>
        </div>
        <form action="" method="POST">
            <input type="text" placeholder="Email" name="email" id="email" required>
            <br>
            <input type="text" placeholder="Senha" name="senha" id="senha" required>
            <button class="login" type="submit" >Entrar</button>

            <a href="">Esqueceu sua senha?</a>
            <hr>
            <a href="register.php">Criar nova conta</a>
        </form>
    </div>
   </div>


    <script src="../assets/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>