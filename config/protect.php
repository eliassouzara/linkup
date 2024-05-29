<?php 

// Configurações de segurança da sessão
session_set_cookie_params([
    'lifetime' => 0, // Sessão dura até o navegador ser fechado
    'path' => '/',
    'domain' => '', // Domínio padrão
    'secure' => true, // Só enviar em conexões seguras
    'httponly' => true, // Impedir acesso via JavaScript
    'samesite' => 'Strict' // Prevenir encio de cookies em requisições de terceiros
]);

// Impede ataques de fixação de sessão
ini_set('session.use_strict_mode', 1); 

session_start();

// Tempo de expiração de sessão 
$timeout = 1800;

// Verificar o tempo de inatividade
if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout){

    session_unset();
    session_destroy();
    header('Location: ../public/login.php');
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

// Regenerar o ID da sessão
if(!isset($_SESSION['CREATED'])){

    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > $timeout){

    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// Verificação adicional de segurança para User Agent
if(!isset($_SESSION['USER_AGENT'])){

    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

} elseif($_SESSION['USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT']){

    session_unset();
    session_destroy();
    header('Location: ../public/login.php');
    exit();
}

// Verificação adicional de segurança para IP Address
if(!isset($_SESSION['IP_ADDRESS'])){

    $_SESSION['IP_ADDRESS'] = $_SERVER['REMOTE_ADDR'];

} elseif ($_SESSION['IP_ADDRESS'] !== $_SERVER['REMOTE_ADDR']){
    session_unset();
    session_destroy();
    header('Location: ../public/login.php');
    exit();
}

// Verificar se o usuário está logado 
if (!isset($_SESSION['id'])){

    header('Location: ../public/index.php');
    exit();
}

// Funções CSRT (Caso usar)
function generate_csrt_token(){

    if(empty($_SESSION['csrt_token'])){

        $_SESSION['csrt_token'] = bin2hex(random_bytes(32));
    }
}

function verify_csrt_token($token){
    
    return isset($_SESSION['csrt_token']) && hash_equals($_SESSION['csrt_token'], $token);
}


// Gere e inclua o token nos formulários
generate_csrt_token();

?>