<?php

include "conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if (
        !$nome ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !$telefone ||
        strlen($senha) < 6
    ) {

        $erro = "Preencha os campos corretamente. A senha deve ter pelo menos 6 caracteres.";

    } else {

        $check = $conexao->prepare(
            "SELECT id FROM clientes WHERE email = ?"
        );

        $check->bind_param(
            "s",
            $email
        );

        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $erro = "Este e-mail já está cadastrado.";

        } else {

            $hash = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            $stmt = $conexao->prepare(
                "INSERT INTO clientes
                (nome,email,telefone,senha)
                VALUES(?,?,?,?)"
            );

            $stmt->bind_param(
                "ssss",
                $nome,
                $email,
                $telefone,
                $hash
            );

            if ($stmt->execute()) {

                $_SESSION["cliente_id"] =
                    $stmt->insert_id;

                $_SESSION["cliente_nome"] =
                    $nome;

                header(
                    "Location: painel_cliente.php"
                );

                exit;

            }

            $erro =
                "Não foi possível criar a conta.";
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
Criar conta | CasaResolve
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
            Entrar
        </a>

    </nav>

</header>


<!-- =========================
     FORMULÁRIO
========================= -->

<main class="form-page">

    <div class="form-card">

        <span class="eyebrow">
            CONTA DO CLIENTE
        </span>


        <h1>
            Crie sua conta.
        </h1>


        <p>
            Com sua conta você poderá contratar
            profissionais e acompanhar seus pedidos.
        </p>


        <?php if($erro): ?>

            <div class="alert error">

                <?= htmlspecialchars($erro) ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            class="form-grid"
        >


            <label>

                Nome completo

                <input
                    required
                    name="nome"
                    placeholder="Seu nome"
                >

            </label>


            <label>

                Telefone

                <input
                    required
                    name="telefone"
                    placeholder="(12) 99999-9999"
                >

            </label>


            <label class="wide">

                E-mail

                <input
                    required
                    type="email"
                    name="email"
                    placeholder="voce@email.com"
                >

            </label>


            <label class="wide">

                Senha

                <input
                    required
                    type="password"
                    name="senha"
                    placeholder="Mínimo de 6 caracteres"
                >

            </label>


            <button
                type="submit"
                class="btn primary full"
            >

                Criar minha conta

            </button>


        </form>


        <p class="form-bottom">

            Já tem uma conta?

            <a href="login.php">
                Entrar agora
            </a>

        </p>

    </div>

</main>


</body>

</html>