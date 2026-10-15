<?php

include "conexao.php";


if (!isset($_SESSION["profissional_id"])) {

    header("Location: login_profissional.php");

    exit;
}


$profissionalId =
    (int)$_SESSION["profissional_id"];

$nome =
    $_SESSION["profissional_nome"];

$servico =
    $_SESSION["profissional_servico"];


/*
|--------------------------------------------------------------------------
| PEDIDOS PENDENTES
|--------------------------------------------------------------------------
*/

$stmt = $conexao->prepare(

"SELECT
    c.id,
    c.servico,
    c.descricao,
    c.data_servico,
    c.horario,
    c.status,
    cl.nome AS cliente,
    cl.telefone AS cliente_telefone,
    cl.email AS cliente_email

FROM contratacoes c

INNER JOIN clientes cl
ON cl.id = c.cliente_id

WHERE c.profissional_id = ?
AND c.status = 'pendente'

ORDER BY
    c.data_servico ASC,
    c.horario ASC"

);

$stmt->bind_param(
    "i",
    $profissionalId
);

$stmt->execute();

$pedidosPendentes =
    $stmt->get_result();


/*
|--------------------------------------------------------------------------
| PEDIDOS ACEITOS
|--------------------------------------------------------------------------
*/

$stmt2 = $conexao->prepare(

"SELECT
    c.id,
    c.servico,
    c.descricao,
    c.data_servico,
    c.horario,
    c.status,
    cl.nome AS cliente,
    cl.telefone AS cliente_telefone

FROM contratacoes c

INNER JOIN clientes cl
ON cl.id = c.cliente_id

WHERE c.profissional_id = ?
AND c.status = 'confirmado'

ORDER BY
    c.data_servico ASC,
    c.horario ASC"

);

$stmt2->bind_param(
    "i",
    $profissionalId
);

$stmt2->execute();

$pedidosAceitos =
    $stmt2->get_result();


/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/

$stmt3 = $conexao->prepare(

"SELECT
    COUNT(*) AS total
FROM contratacoes
WHERE profissional_id = ?"

);

$stmt3->bind_param(
    "i",
    $profissionalId
);

$stmt3->execute();

$total =
    $stmt3
    ->get_result()
    ->fetch_assoc()["total"];

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
Painel profissional | CasaResolve
</title>

<link rel="stylesheet" href="style.css">


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
Meu perfil
</a>

<a
href="logout.php"
class="nav-cta"
>
Sair
</a>

</nav>

</header>



<main class="professional-dashboard">


<!-- CABEÇALHO -->

<div class="professional-top">

<div>

<span class="eyebrow">
PAINEL DO PROFISSIONAL
</span>

<h1>

Olá,
<?= htmlspecialchars($nome) ?>.

</h1>

<p>

Gerencie seus pedidos de
<?= htmlspecialchars($servico) ?>.

</p>

</div>


<div class="professional-service">

<svg viewBox="0 0 24 24">

<path d="M14.7 6.3a4.5 4.5 0 0 0-5.8 5.8L3.5 17.5a2.1 2.1 0 0 0 3 3l5.4-5.4a4.5 4.5 0 0 0 5.8-5.8l-3 3-2.3-2.3 3-3Z"/>

</svg>

<?= htmlspecialchars($servico) ?>

</div>

</div>



<!-- ESTATÍSTICAS -->

<div class="professional-stats">


<div class="professional-stat">

<span>
Pedidos recebidos
</span>

<strong>
<?= $total ?>
</strong>

</div>


<div class="professional-stat">

<span>
Novos pedidos
</span>

<strong>
<?= $pedidosPendentes->num_rows ?>
</strong>

</div>


<div class="professional-stat">

<span>
Aceitos
</span>

<strong>
<?= $pedidosAceitos->num_rows ?>
</strong>

</div>


</div>



<!-- PEDIDOS PENDENTES -->

<section class="professional-panel">


<div class="professional-panel-header">

<div>

<span class="eyebrow">
AGUARDANDO RESPOSTA
</span>

<h2>
Novos pedidos
</h2>

</div>


<span class="request-count">

<?= $pedidosPendentes->num_rows ?>

</span>

</div>



<?php if ($pedidosPendentes->num_rows > 0): ?>


<?php while (
    $pedido =
    $pedidosPendentes->fetch_assoc()
): ?>


<article class="request-card">


<div class="request-date">

<strong>

<?= date(
    "d",
    strtotime(
        $pedido["data_servico"]
    )
) ?>

</strong>

<span>

<?= strtoupper(
    date(
        "M",
        strtotime(
            $pedido["data_servico"]
        )
    )
) ?>

</span>

</div>



<div class="request-content">


<div class="request-title">

<div>

<span class="tag">

<?= htmlspecialchars(
    $pedido["servico"]
) ?>

</span>

<h3>

Pedido de
<?= htmlspecialchars(
    $pedido["cliente"]
) ?>

</h3>

</div>


<span class="status pendente">

Pendente

</span>

</div>



<p class="request-description">

<?= htmlspecialchars(
    $pedido["descricao"]
) ?>

</p>



<div class="request-details">


<span>

<svg viewBox="0 0 24 24">

<circle
cx="12"
cy="12"
r="9"
/>

<path d="M12 7v5l3 2"/>

</svg>

<?= htmlspecialchars(
    $pedido["horario"]
) ?>

</span>



<span>

<svg viewBox="0 0 24 24">

<path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/>

<circle
cx="12"
cy="10"
r="2.5"
/>

</svg>

Atendimento residencial

</span>

</div>



<div class="client-contact">

<strong>
Dados do cliente
</strong>

<span>

<?= htmlspecialchars(
    $pedido["cliente_telefone"]
) ?>

</span>

<span>

<?= htmlspecialchars(
    $pedido["cliente_email"]
) ?>

</span>

</div>



<div class="request-actions">


<form
method="POST"
action="acao_contratacao.php"
>

<input
type="hidden"
name="contratacao_id"
value="<?= (int)$pedido["id"] ?>"
>

<input
type="hidden"
name="acao"
value="aceitar"
>

<button
class="btn primary"
>

<svg viewBox="0 0 24 24">

<path d="m5 12 4 4L19 6"/>

</svg>

Aceitar pedido

</button>

</form>



<form
method="POST"
action="acao_contratacao.php"
>

<input
type="hidden"
name="contratacao_id"
value="<?= (int)$pedido["id"] ?>"
>

<input
type="hidden"
name="acao"
value="recusar"
>

<button
class="btn danger"
>

<svg viewBox="0 0 24 24">

<path d="M6 6l12 12"/>

<path d="M18 6 6 18"/>

</svg>

Recusar

</button>

</form>

</div>

</div>

</article>


<?php endwhile; ?>


<?php else: ?>


<div class="professional-empty">

<div>

<svg viewBox="0 0 24 24">

<path d="M6 3h12v18H6z"/>

<path d="M9 8h6"/>

<path d="M9 12h6"/>

<path d="M9 16h4"/>

</svg>

</div>

<h3>
Tudo em dia!
</h3>

<p>
Você não possui novos pedidos para responder.
</p>

</div>


<?php endif; ?>

</section>



<!-- PEDIDOS ACEITOS -->

<section class="professional-panel">


<div class="professional-panel-header">

<div>

<span class="eyebrow">
SEUS SERVIÇOS
</span>

<h2>
Pedidos aceitos
</h2>

</div>

</div>



<?php if ($pedidosAceitos->num_rows > 0): ?>


<?php while (
    $pedido =
    $pedidosAceitos->fetch_assoc()
): ?>


<div class="accepted-card">


<div class="accepted-icon">

<svg viewBox="0 0 24 24">

<path d="m5 12 4 4L19 6"/>

</svg>

</div>



<div>

<span class="tag">

<?= htmlspecialchars(
    $pedido["servico"]
) ?>

</span>

<h3>

<?= htmlspecialchars(
    $pedido["cliente"]
) ?>

</h3>

<p>

<?= date(
    "d/m/Y",
    strtotime(
        $pedido["data_servico"]
    )
) ?>

às

<?= htmlspecialchars(
    $pedido["horario"]
) ?>

</p>

<p>

Telefone:

<?= htmlspecialchars(
    $pedido["cliente_telefone"]
) ?>

</p>

</div>



<span class="status confirmado">

Confirmado

</span>

</div>


<?php endwhile; ?>


<?php else: ?>


<div class="professional-empty">

<p>
Nenhum pedido aceito ainda.
</p>

</div>


<?php endif; ?>

</section>


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

</div>

</div>



<small>

© 2026 CasaResolve

</small>

</footer>


</body>

</html>