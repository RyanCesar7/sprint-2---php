<?php

include "conexao.php";

if(isset($_SESSION["cliente_id"])){

    header("Location: painel_cliente.php");
    exit;
}

$erro = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $stmt = $conexao->prepare(
        "SELECT id, nome, senha
        FROM clientes
        WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $cliente = $stmt
        ->get_result()
        ->fetch_assoc();

    if(
        $cliente &&
        password_verify(
            $senha,
            $cliente["senha"]
        )
    ){

        $_SESSION["cliente_id"] = $cliente["id"];

        $_SESSION["cliente_nome"] = $cliente["nome"];

        header("Location: painel_cliente.php");
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

<title>Entrar | CasaResolve</title>

<link rel="stylesheet" href="style.css">

<style>

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

@media (max-width: 768px) {

    .logo img {
        width: 125px;
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

<a
href="cadastro_cliente.php"
class="nav-cta"
>
Criar conta
</a>

</nav>

</header>


<main class="form-page">

<div class="login-card form-card">

<div class="login-symbol">
⌂
</div>

<span class="eyebrow">
ÁREA DO CLIENTE
</span>

<h1>
Bem-vindo de volta.
</h1>

<p>
Entre para contratar profissionais
e acompanhar seus serviços.
</p>


<?php if($erro): ?>

<div class="alert error">

<?= htmlspecialchars($erro) ?>

</div>

<?php endif; ?>


<form
method="POST"
class="form-grid one"
>

<label>

E-mail

<input
required
type="email"
name="email"
placeholder="voce@email.com"
>

</label>


<label>

Senha

<input
required
type="password"
name="senha"
placeholder="Sua senha"
>

</label>


<button
type="submit"
class="btn primary full"
>

Entrar na minha conta

</button>

</form>


<p class="form-bottom">

Ainda não tem conta?

<a href="cadastro_cliente.php">
Criar conta grátis
</a>

</p>

</div>

</main>

</body>

</html>