<?php

include "conexao.php";

if (!isset($_SESSION["cliente_id"])) {

    header("Location: login.php");

    exit;
}


$clienteId =
    (int) $_SESSION["cliente_id"];

$pedidoId =
    (int) ($_POST["pedido_id"] ?? 0);

$forma =
    $_POST["forma_pagamento"] ?? "";


if (!$pedidoId || !$forma) {

    die("Dados de pagamento inválidos.");

}


if (!in_array($forma, ["pix", "cartao"])) {

    die("Forma de pagamento inválida.");

}


$stmt = $conexao->prepare("

    UPDATE contratacoes

    SET
        forma_pagamento = ?,
        pagamento_status = 'pago'

    WHERE id = ?
    AND cliente_id = ?

");

$stmt->bind_param(

    "sii",

    $forma,
    $pedidoId,
    $clienteId

);

$stmt->execute();


if ($forma === "pix") {

    header(
        "Location: pix.php?id=" . $pedidoId
    );

    exit;

}


header(
    "Location: painel_cliente.php?pagamento=sucesso"
);

exit;

?>