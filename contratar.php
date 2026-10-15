<?php

include "conexao.php";


if (!isset($_SESSION["cliente_id"])) {

    header("Location: login.php?redirect=contratar");

    exit;
}


$id = (int)($_GET["id"] ?? 0);


$stmt = $conexao->prepare(
    "SELECT id, nome, email, telefone, servico
     FROM profissionais
     WHERE id = ?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$profissional =
    $stmt->get_result()->fetch_assoc();


if (!$profissional) {

    header("Location: profissionais.php");

    exit;
}


$clienteId =
    $_SESSION["cliente_id"];


$stmtCliente = $conexao->prepare(
    "SELECT nome, telefone
     FROM clientes
     WHERE id = ?"
);

$stmtCliente->bind_param(
    "i",
    $clienteId
);

$stmtCliente->execute();

$cliente =
    $stmtCliente->get_result()->fetch_assoc();

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
Contratar | CasaResolve
</title>

<link rel="stylesheet" href="style.css">

<style>

/* =========================
   LOGO
========================= */

.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
}

.logo img {
    width: 100Px;
    height: auto;
    display: block;
    object-fit: contain;
}

.footer-logo img {
    width: 170px;
    height: auto;
}


/* =========================
   RESPONSIVIDADE
========================= */

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


<!-- =========================
     CABEÇALHO
========================= -->

<header class="header">

    <a
        href="index.php"
        class="logo"
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

        <a href="logout.php">
            Sair
        </a>

    </nav>

</header>


<!-- =========================
     CONTEÚDO
========================= -->

<main class="hire-page">

    <div class="hire-container">


        <!-- =========================
             PROFISSIONAL ESCOLHIDO
        ========================= -->

        <div class="hire-professional">

            <span class="eyebrow">
                PROFISSIONAL ESCOLHIDO
            </span>


            <div class="hire-person">

                <div class="professional-avatar">

                    <?= strtoupper(
                        substr($profissional["nome"], 0, 1)
                    ) ?>

                </div>


                <div>

                    <h2>
                        <?= htmlspecialchars(
                            $profissional["nome"]
                        ) ?>
                    </h2>

                    <span>
                        <?= htmlspecialchars(
                            $profissional["servico"]
                        ) ?>
                    </span>

                </div>

            </div>


            <div class="hire-info">

                <p>

                    <strong>
                        Telefone
                    </strong>

                    <?= htmlspecialchars(
                        $profissional["telefone"]
                    ) ?>

                </p>


                <p>

                    <strong>
                        E-mail
                    </strong>

                    <?= htmlspecialchars(
                        $profissional["email"]
                    ) ?>

                </p>

            </div>

        </div>


        <!-- =========================
             FORMULÁRIO
        ========================= -->

        <div class="hire-form">

            <span class="eyebrow">
                SOLICITAÇÃO
            </span>


            <h1>
                Agendar serviço
            </h1>


            <p>
                Preencha os dados abaixo para enviar
                o pedido ao profissional.
            </p>


            <form
                action="processar_contratacao.php"
                method="POST"
            >


                <!-- ID DO PROFISSIONAL -->

                <input
                    type="hidden"
                    name="profissional_id"
                    value="<?= $profissional["id"] ?>"
                >


                <!-- SERVIÇO -->

                <label>

                    Serviço

                    <input
                        type="text"
                        value="<?= htmlspecialchars(
                            $profissional["servico"]
                        ) ?>"
                        readonly
                    >

                </label>


                <!-- DESCRIÇÃO -->

                <label>

                    Descrição do problema

                    <textarea
                        name="descricao"
                        required
                        placeholder="Explique o que precisa ser feito..."
                    ></textarea>

                </label>


                <!-- DATA E HORÁRIO -->

                <div class="form-row">

                    <label>

                        Data

                        <input
                            type="date"
                            name="data_servico"
                            required
                            min="<?= date("Y-m-d") ?>"
                        >

                    </label>


                    <label>

                        Horário

                        <input
                            type="time"
                            name="horario"
                            required
                        >

                    </label>

                </div>


                <!-- ENDEREÇO -->

                <label>

                    Endereço do serviço

                    <input
                        type="text"
                        name="endereco"
                        required
                        placeholder="Rua, número e bairro"
                    >

                </label>


                <!-- TELEFONE -->

                <label>

                    Telefone para contato

                    <input
                        type="text"
                        name="telefone_cliente"
                        value="<?= htmlspecialchars(
                            $cliente["telefone"] ?? ""
                        ) ?>"
                        required
                        placeholder="(12) 99999-9999"
                    >

                </label>


                <!-- BOTÃO -->

                <button
                    type="submit"
                    class="btn primary full"
                >

                    Enviar pedido

                    <svg
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >

                        <path d="M22 2 11 13"/>

                        <path d="m22 2-7 20-4-9-9-4Z"/>

                    </svg>

                </button>

            </form>

        </div>

    </div>

</main>


<!-- =========================
     FOOTER
========================= -->

<footer>

    <div class="footer-content">


        <div>

            <a
                href="index.php"
                class="logo footer-logo"
            >

                <img
                    src="img/logo_projeto.png"
                    alt="CasaResolve"
                >

            </a>


            <p>
                Conectando pessoas e profissionais
                para cuidar da sua casa.
            </p>

        </div>


        <div class="footer-links">

            <a href="index.php">
                Início
            </a>

            <a href="profissionais.php">
                Profissionais
            </a>

            <a href="painel_cliente.php">
                Minha conta
            </a>

        </div>

    </div>


    <small>
        © 2026 CasaResolve
    </small>

</footer>


</body>

</html>