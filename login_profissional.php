<?php

include "conexao.php";

if (isset($_SESSION["profissional_id"])) {

    header("Location: painel_profissional.php");
    exit;
}

$erro = "";

$sucesso = isset($_GET["cadastro"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $stmt = $conexao->prepare(
        "SELECT
            id,
            nome,
            senha,
            servico
         FROM profissionais
         WHERE email = ?"
    );

    $stmt->bind_param(
        "s",
        $email
    );

    $stmt->execute();

    $profissional =
        $stmt
        ->get_result()
        ->fetch_assoc();

    if (
        $profissional &&
        !empty($profissional["senha"]) &&
        password_verify(
            $senha,
            $profissional["senha"]
        )
    ) {

        $_SESSION["profissional_id"] =
            $profissional["id"];

        $_SESSION["profissional_nome"] =
            $profissional["nome"];

        $_SESSION["profissional_servico"] =
            $profissional["servico"];

        header(
            "Location: painel_profissional.php"
        );

        exit;
    }

    $erro = "E-mail ou senha inválidos.";
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
Área profissional | CasaResolve
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
    width: 100px;
    height: auto;
    display: block;
    object-fit: contain;
}


/* =========================
   RESPONSIVIDADE
========================= */

@media (max-width: 768px) {

    .logo img {
        width: 125px;
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

        <a href="login.php">
            Cliente
        </a>

        <a
            href="cadastro_profissional.php"
            class="nav-cta"
        >
            Criar conta
        </a>

    </nav>

</header>


<!-- =========================
     LOGIN
========================= -->

<main class="form-page">

    <div class="form-card login-card">


        <div class="login-symbol">

            <svg viewBox="0 0 24 24">

                <path
                    d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"
                />

                <path
                    d="M4 21a8 8 0 0 1 16 0"
                />

            </svg>

        </div>


        <span class="eyebrow">
            ÁREA PROFISSIONAL
        </span>


        <h1>
            Bem-vindo de volta.
        </h1>


        <p>
            Entre para visualizar e aceitar
            seus pedidos de serviço.
        </p>


        <?php if ($sucesso): ?>

            <div class="alert success">

                Cadastro realizado com sucesso!
                Agora entre na sua conta.

            </div>

        <?php endif; ?>


        <?php if ($erro): ?>

            <div class="alert error">

                <?= htmlspecialchars($erro) ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            class="form-grid one"
        >


            <!-- E-MAIL -->

            <label>

                E-mail

                <input
                    required
                    type="email"
                    name="email"
                    placeholder="voce@email.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                >

            </label>


            <!-- SENHA -->

            <label>

                Senha

                <input
                    required
                    type="password"
                    name="senha"
                    placeholder="Sua senha"
                >

            </label>


            <!-- BOTÃO -->

            <button
                type="submit"
                class="btn primary full"
            >

                Entrar na área profissional

                <svg
                    class="btn-icon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >

                    <path d="M5 12h14"/>

                    <path d="m13 6 6 6-6 6"/>

                </svg>

            </button>

        </form>


        <!-- CADASTRO -->

        <p class="form-bottom">

            Ainda não sou profissional.

            <a href="cadastro_profissional.php">
                Criar conta
            </a>

        </p>

    </div>

</main>


</body>

</html>