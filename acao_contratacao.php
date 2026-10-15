<?php

include "conexao.php";


if (!isset($_SESSION["profissional_id"])) {

    header(
        "Location: login_profissional.php"
    );

    exit;
}


$profissionalId =
    (int)$_SESSION["profissional_id"];

$contratacaoId =
    (int)($_POST["contratacao_id"] ?? 0);

$acao =
    $_POST["acao"] ?? "";


if (
    !$contratacaoId ||
    !in_array(
        $acao,
        ["aceitar", "recusar"]
    )
) {

    header(
        "Location: painel_profissional.php"
    );

    exit;
}


if ($acao === "aceitar") {

    $novoStatus = "confirmado";

} else {

    $novoStatus = "cancelado";
}


$stmt = $conexao->prepare(

"UPDATE contratacoes

SET status = ?

WHERE id = ?

AND profissional_id = ?

AND status = 'pendente'"

);


$stmt->bind_param(

    "sii",

    $novoStatus,
    $contratacaoId,
    $profissionalId

);


$stmt->execute();


header(
    "Location: painel_profissional.php"
);

exit;

?>