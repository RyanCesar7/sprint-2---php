<?php

session_start();

include "conexao.php";

/*
|--------------------------------------------------------------------------
| Verifica se o cliente está logado
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php?erro=login");
    exit;
}


/*
|--------------------------------------------------------------------------
| Dados do cliente
|--------------------------------------------------------------------------
*/

$clienteId = (int) $_SESSION["cliente_id"];


/*
|--------------------------------------------------------------------------
| Dados enviados pelo formulário
|--------------------------------------------------------------------------
*/

$profissionalId = (int) ($_POST["profissional_id"] ?? 0);

$descricao = trim($_POST["descricao"] ?? "");

$data = $_POST["data_servico"] ?? "";

$horario = $_POST["horario"] ?? "";

$endereco = trim($_POST["endereco"] ?? "");

$telefone = trim($_POST["telefone_cliente"] ?? "");


/*
|--------------------------------------------------------------------------
| Verifica os campos
|--------------------------------------------------------------------------
*/

if (
    !$profissionalId ||
    !$descricao ||
    !$data ||
    !$horario ||
    !$endereco ||
    !$telefone
) {

    die("Preencha todos os campos.");

}


/*
|--------------------------------------------------------------------------
| Busca o profissional
|--------------------------------------------------------------------------
*/

$stmt = $conexao->prepare(
    "SELECT id, nome, servico
     FROM profissionais
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param(
    "i",
    $profissionalId
);

$stmt->execute();

$result = $stmt->get_result();

$profissional = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Verifica se encontrou o profissional
|--------------------------------------------------------------------------
*/

if (!$profissional) {

    die("Profissional não encontrado.");

}


/*
|--------------------------------------------------------------------------
| Serviço do profissional
|--------------------------------------------------------------------------
*/

$servico = $profissional["servico"];


/*
|--------------------------------------------------------------------------
| Cadastra a contratação
|--------------------------------------------------------------------------
*/

$stmt = $conexao->prepare(
    "INSERT INTO contratacoes
    (
        cliente_id,
        profissional_id,
        servico,
        descricao,
        data_servico,
        horario,
        endereco,
        telefone_cliente,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendente')"
);


$stmt->bind_param(
    "iissssss",
    $clienteId,
    $profissionalId,
    $servico,
    $descricao,
    $data,
    $horario,
    $endereco,
    $telefone
);


/*
|--------------------------------------------------------------------------
| Executa o cadastro
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    /*
    | ID da contratação recém-criada
    */

    $contratacaoId = $conexao->insert_id;

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | Vai para a página de pagamento
    |--------------------------------------------------------------------------
    */

    header(
        "Location: pagamento.php?id=" . $contratacaoId
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| Erro
|--------------------------------------------------------------------------
*/

$erro = $stmt->error;

$stmt->close();

die(
    "Erro ao enviar pedido: " . $erro
);

?>