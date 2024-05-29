<?php
//Configurações do banco de dados
$sevidor = "local";
$dbusuario = "root";
$dbsenha = "";
$dbnome = "linkup";

// Estabeler conexão com o banco de dados
$conn = mysqli_connect($sevidor, $dbusuario, $dbsenha, $dbnome);

// Verficar se a conexão foi bem-scedida
if (!$conn){

    die("Falha" . mysqli_connect_error());
}

// Definir o charset como utf8 para suportar carateres especiais

mysqli_set_charset($conn, "utf8");

?>