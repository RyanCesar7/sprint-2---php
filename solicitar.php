<?php

include "conexao.php";


// =====================================================
// VERIFICA SE O CLIENTE ESTÁ LOGADO
// =====================================================

if (!isset($_SESSION["cliente_id"])) {

    header(
        "Location: login.php?redirect=solicitar.php"
    );

    exit;
}


$mensagem = "";


// =====================================================
// FORMULÁRIO ENVIADO
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $clienteId = (int) $_SESSION["cliente_id"];

    $servico = trim(
        $_POST["servico"] ?? ""
    );

    $descricao = trim(
        $_POST["descricao"] ?? ""
    );


    // =================================================
    // VERIFICA OS CAMPOS
    // =================================================

    if ($servico === "" || $descricao === "") {

        $mensagem = "Preencha todos os campos.";

    } else {


        // =============================================
        // BUSCA O NOME DO CLIENTE
        // =============================================

        $stmt = $conexao->prepare(
            "SELECT nome
             FROM clientes
             WHERE id = ?
             LIMIT 1"
        );

        $stmt->bind_param(
            "i",
            $clienteId
        );

        $stmt->execute();

        $resultado = $stmt->get_result();

        $cliente = $resultado->fetch_assoc();

        $stmt->close();


        if (!$cliente) {

            $mensagem = "Cliente não encontrado.";

        } else {


            // =========================================
            // CADASTRA A SOLICITAÇÃO
            // =========================================

            $nomeCliente = $cliente["nome"];

            $stmt = $conexao->prepare(
                "INSERT INTO solicitacoes
                (
                    cliente,
                    servico,
                    descricao
                )
                VALUES (?, ?, ?)"
            );


            $stmt->bind_param(
                "sss",
                $nomeCliente,
                $servico,
                $descricao
            );


            // =========================================
            // EXECUTA
            // =========================================

            if ($stmt->execute()) {

                $stmt->close();

                header(
                    "Location: profissionais.php"
                );

                exit;

            } else {

                $mensagem =
                    "Erro ao registrar solicitação: "
                    . $stmt->error;

                $stmt->close();

            }

        }

    }

}

?>


<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
Solicitar serviço | CasaResolve
</title>

<link
    rel="stylesheet"
    href="style.css"
>


<style>

/* =====================================================
   LOGO
===================================================== */

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.logo img {
    width: 100px;
    height: auto;
    display: block;
    object-fit: contain;
}

.footer-logo img {
    width: 170px;
    height: auto;
}


/* =====================================================
   RESPONSIVO
===================================================== */

@media (max-width: 768px) {

    .logo img {
        width: 125px;
    }

    .footer-logo img {
        width: 120px;
    }

}

</style>

</head>


<body>


<header class="header">


<a
    class="logo"
    href="index.php"
>

<img
    src="img/logo_projeto.png"
    alt="CasaResolve"
>

</a>



<nav>

<a href="index.php">
    Início
</a>

<a href="profissionais.php">
    Profissionais
</a>

<a href="painel_cliente.php">
    Minha conta
</a>

<a
    href="logout.php"
    class="nav-cta"
>
    Sair
</a>

</nav>

</header>



<main class="form-page">


<div class="form-card">


<span class="eyebrow">
    PRECISA DE AJUDA?
</span>


<h1>
    Solicite um serviço.
</h1>


<p>
    Registre o que você precisa
    e encontre um profissional.
</p>



<?php if ($mensagem): ?>

<div class="alert success">

<?= htmlspecialchars($mensagem) ?>

</div>

<?php endif; ?>



<form
    method="POST"
    class="form-grid"
>


<label class="wide">

    Serviço


    <select
        required
        name="servico"
    >

        <option value="">
            Selecione
        </option>

        <option value="Encanador">
            Encanador
        </option>

        <option value="Eletricista">
            Eletricista
        </option>

        <option value="Diarista">
            Diarista
        </option>

        <option value="Manutenção">
            Manutenção
        </option>

    </select>

</label>



<label class="wide">

    Descreva o que precisa


    <textarea
        required
        name="descricao"
        rows="7"
        placeholder="Ex.: Preciso resolver um vazamento..."
    ></textarea>

</label>



<button
    type="submit"
    class="btn primary full"
>

    Registrar solicitação

</button>


</form>

</div>

</main>


</body>

</html>