<?php
    // Verificar se o metado post email, nome, senha existe
    if(isset($_POST['email'], $_POST['nome'], $_POST['senha'])){

        include('../config/config.php');
        
    // Função para validar e-mail usando expressão regular 
    function validar_email($email){

    // Expressão regular para validar e-mails
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email); 
    }

    // Função para sanitizar string removendo tags HTML e caracteres especiais
    function sanitizar_string($string){

    //Remove tags HTML e carateres especiais
        return strip_tags($string);
    }

    // Validar e sanitizar os dados do formulário 
    $email = isset ($_POST['email']) ? $_POST['email'] : '';
    $nome = isset ($_POST['nome']) ? sanitizar_string($_POST['nome']) : '';
    $senha = isset ($_POST['senha']) ? $_POST['senha'] : '';

    // Verificar se o e-mail é válido
    if(!validar_email($email)){
        echo "O e-mail fornecido não é válido.";
        exit;
    }

    // Verificar se os dados foram validados corretamente
    if($email && $senha){
    
    // Verificar se o e-mail já está cadastrado
    $stmt_check = $conn ->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check ->bind_param("s", $email);
    $stmt_check ->execute();
    $stmt_check -> store_result();

    if($stmt_check->num_rows > 0){
        echo "O e-mail já está cadastrado.";
    } else {

        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Preparar e executar a consulta usando prepared statement
        $stmt_insert = $conn ->prepare("INSERT INTO usuarios (email, senha, nome) VALUES (?,?,?)");
        $stmt_insert ->bind_param("sss", $email, $senha_hash, $nome);
        $stmt_insert ->execute();

        // Redirecionar após cadastro
        header("Location: login.php");
        exit; //Encerrar

    }
    } else{
        echo "Dados inválidos.";
    }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Register</title>
</head>
<body>
   <div class="content">
    <div class="flex-div">
        <div class="nome-content">
            <h1 class="logo">Linkup</h1>
            <p>Fazer conta</p>
        </div>
        <form action="" method="POST">
            <input type="text" placeholder="Email" name="email" id="email" required>
            <br>
            <input type="text" placeholder="Nome" name="nome" id="nome" required>
            <br>
            <input type="password" placeholder="Senha" name="senha" id="senha" required>
            <button class="login" type="submit" >Entrar</button>

            
            <hr>
            <a href="login.php">Já tenho conta</a>
        </form>
    </div>
   </div>

<script src="../assets/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>