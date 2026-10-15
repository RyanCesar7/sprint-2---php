<?php

include "conexao.php";

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $servico = $_POST["servico"] ?? "";
    $senha = $_POST["senha"] ?? "";

    if (
        !$nome ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !$telefone ||
        !$servico ||
        strlen($senha) < 6
    ) {

        $erro = "Preencha todos os campos corretamente. A senha deve ter pelo menos 6 caracteres.";

    } else {

        $check = $conexao->prepare(
            "SELECT id FROM profissionais WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $erro = "Este e-mail já está cadastrado.";

        } else {

            $hash = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            $stmt = $conexao->prepare(
                "INSERT INTO profissionais
                (nome, email, telefone, servico, senha)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssss",
                $nome,
                $email,
                $telefone,
                $servico,
                $hash
            );

            if ($stmt->execute()) {

                header(
                    "Location: login_profissional.php?cadastro=sucesso"
                );

                exit;

            } else {

                $erro =
                    "Não foi possível realizar o cadastro.";
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
Cadastro profissional | CasaResolve
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
            Área do cliente
        </a>

        <a
            href="login_profissional.php"
            class="nav-cta"
        >
            Área profissional
        </a>

    </nav>

</header>


<!-- =========================
     FORMULÁRIO
========================= -->

<main class="form-page">

    <div class="form-card">

        <span class="eyebrow">
            SEJA UM PROFISSIONAL
        </span>


        <h1>
            Faça parte do CasaResolve.
        </h1>


        <p>
            Cadastre seu serviço e receba pedidos
            de clientes através da plataforma.
        </p>


        <?php if ($erro): ?>

            <div class="alert error">

                <?= htmlspecialchars($erro) ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            class="form-grid"
        >


            <!-- NOME -->

            <label>

                Nome completo

                <input
                    required
                    name="nome"
                    placeholder="Seu nome"
                    value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                >

            </label>


            <!-- TELEFONE -->

            <label>

                Telefone

                <input
                    required
                    name="telefone"
                    placeholder="(12) 99999-9999"
                    value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>"
                >

            </label>


            <!-- EMAIL -->

            <label class="wide">

                E-mail

                <input
                    required
                    type="email"
                    name="email"
                    placeholder="voce@email.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                >

            </label>


            <!-- SERVIÇO -->

            <label>

                Serviço

                <select
                    required
                    name="servico"
                >

                    <option value="">
                        Selecione
                    </option>

                    <option
                        value="Encanador"
                        <?= (($_POST['servico'] ?? '') === 'Encanador') ? 'selected' : '' ?>
                    >
                        Encanador
                    </option>

                    <option
                        value="Eletricista"
                        <?= (($_POST['servico'] ?? '') === 'Eletricista') ? 'selected' : '' ?>
                    >
                        Eletricista
                    </option>

                    <option
                        value="Diarista"
                        <?= (($_POST['servico'] ?? '') === 'Diarista') ? 'selected' : '' ?>
                    >
                        Diarista
                    </option>

                    <option
                        value="Manutenção"
                        <?= (($_POST['servico'] ?? '') === 'Manutenção') ? 'selected' : '' ?>
                    >
                        Manutenção
                    </option>

                </select>

            </label>


            <!-- SENHA -->

            <label>

                Senha

                <input
                    required
                    type="password"
                    name="senha"
                    placeholder="Mínimo de 6 caracteres"
                >

            </label>


            <!-- BOTÃO -->

            <button
                type="submit"
                class="btn primary full"
            >

                Criar conta profissional

                <svg
                    class="btn-icon"
                    viewBox="0 0 24 24"
                >

                    <path d="M5 12h14"/>
                    <path d="m13 6 6 6-6 6"/>

                </svg>

            </button>

        </form>


        <!-- LOGIN -->

        <p class="form-bottom">

            Já possui uma conta?

            <a href="login_profissional.php">
                Entrar como profissional
            </a>

        </p>

    </div>

</main>


</body>

</html>