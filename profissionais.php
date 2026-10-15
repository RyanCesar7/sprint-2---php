<?php

include "conexao.php";

$servico = $_GET["servico"] ?? "";

if ($servico !== "") {

    $stmt = $conexao->prepare(
        "SELECT id, nome, email, telefone, servico
         FROM profissionais
         WHERE servico = ?
         ORDER BY nome"
    );

    $stmt->bind_param("s", $servico);
    $stmt->execute();

    $profissionais = $stmt->get_result();

} else {

    $profissionais = $conexao->query(
        "SELECT id, nome, email, telefone, servico
         FROM profissionais
         ORDER BY nome"
    );
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
Profissionais | CasaResolve
</title>

<link
rel="stylesheet"
href="style.css"
>


<style>

/* LOGO */

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


/* RESPONSIVO */

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

<a href="solicitar.php">
Solicitar serviço
</a>


<?php if (isset($_SESSION["cliente_id"])): ?>

<a href="painel_cliente.php">
Minha conta
</a>

<?php else: ?>

<a href="login.php">
Entrar
</a>

<?php endif; ?>

</nav>

</header>



<main class="professionals-page">


<div class="page-title">

<span class="eyebrow">
PROFISSIONAIS
</span>

<h1>
Encontre alguém para resolver seu problema
</h1>

<p>
Escolha uma categoria e veja os profissionais disponíveis.
</p>

</div>



<div class="filter-buttons">


<a
href="profissionais.php"
class="<?= $servico === "" ? "active" : "" ?>"
>
Todos
</a>


<a
href="profissionais.php?servico=Encanador"
class="<?= $servico === "Encanador" ? "active" : "" ?>"
>
Encanadores
</a>


<a
href="profissionais.php?servico=Eletricista"
class="<?= $servico === "Eletricista" ? "active" : "" ?>"
>
Eletricistas
</a>


<a
href="profissionais.php?servico=Diarista"
class="<?= $servico === "Diarista" ? "active" : "" ?>"
>
Diaristas
</a>


<a
href="profissionais.php?servico=Manutenção"
class="<?= $servico === "Manutenção" ? "active" : "" ?>"
>
Manutenção
</a>


<a
href="profissionais.php?servico=Manutenção"
class="<?= $servico === "Manutenção" ? "active" : "" ?>"
>
Manutenção
</a>

</div>



<div class="professionals-list">


<?php if (
    $profissionais &&
    $profissionais->num_rows > 0
): ?>


<?php while (
    $profissional =
    $profissionais->fetch_assoc()
): ?>


<article class="professional-card">


<div class="professional-avatar">

<?= strtoupper(
    substr(
        $profissional["nome"],
        0,
        1
    )
) ?>

</div>



<div class="professional-info">


<span class="professional-category">

<?= htmlspecialchars(
    $profissional["servico"]
) ?>

</span>


<h2>

<?= htmlspecialchars(
    $profissional["nome"]
) ?>

</h2>



<div class="professional-contact">


<span>

<svg viewBox="0 0 24 24">

<path d="M22 16.92v3a2 2 0 0 1-2.18 2
A19.79 19.79 0 0 1 3.08 5.18
A2 2 0 0 1 5.06 3h3
a2 2 0 0 1 2 1.72
c.12.9.33 1.78.62 2.63
a2 2 0 0 1-.45 2.11L9 10.73
a16 16 0 0 0 4.27 4.27l1.27-1.23
a2 2 0 0 1 2.11-.45
c.85.29 1.73.5 2.63.62
A2 2 0 0 1 22 16.92z"/>

</svg>

<?= htmlspecialchars(
    $profissional["telefone"]
) ?>

</span>



<span>

<svg viewBox="0 0 24 24">

<rect
x="3"
y="5"
width="18"
height="14"
rx="2"
/>

<path d="m3 7 9 6 9-6"/>

</svg>

<?= htmlspecialchars(
    $profissional["email"]
) ?>

</span>

</div>

</div>



<div class="professional-action">


<a
href="contratar.php?id=<?= $profissional["id"] ?>"
class="btn primary"
>

Contratar


<svg viewBox="0 0 24 24">

<path d="M5 12h14"/>

<path d="m13 6 6 6-6 6"/>

</svg>

</a>

</div>

</article>


<?php endwhile; ?>


<?php else: ?>


<div class="empty-box">


<h2>
Nenhum profissional encontrado.
</h2>


<p>
Ainda não existem profissionais nessa categoria.
</p>


<a
href="cadastro_profissional.php"
class="btn primary"
>
Cadastrar profissional
</a>


</div>


<?php endif; ?>

</div>

</main>



<footer>


<div class="footer-content">


<div>


<div class="logo footer-logo">

<img
src="img/logo_projeto.png"
alt="CasaResolve"
>

</div>


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


<a href="solicitar.php">
Solicitar serviço
</a>


<a href="login.php">
Área do cliente
</a>


</div>

</div>



<small>

© 2026 CasaResolve

</small>


</footer>


</body>

</html>