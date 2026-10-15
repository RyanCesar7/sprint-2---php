<?php

// Inicia a sessão somente se ela ainda não estiver iniciada.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do MySQL/XAMPP.
$servidor = "127.0.0.1";
$usuario = "root";
$senha = "";
$banco = "casaResolve";
$porta = 3306;

// Cria a conexão com o banco.
$conexao = new mysqli(
    $servidor,
    $usuario,
    $senha,
    $banco,
    $porta
);

// Verifica a conexão.
if ($conexao->connect_errno) {
    die(
        "Erro ao conectar ao banco de dados.<br>" .
        "Código: " . $conexao->connect_errno . "<br>" .
        "Mensagem: " . htmlspecialchars($conexao->connect_error)
    );
}

// Define o padrão de caracteres.
if (!$conexao->set_charset("utf8mb4")) {
    die("Erro ao configurar o charset do banco: " . htmlspecialchars($conexao->error));
}

?>
