<?php

include "conexao.php";

if (!isset($_SESSION["cliente_id"])) {
    header("Location: login.php");
    exit;
}

$clienteId = (int) $_SESSION["cliente_id"];
$pedidoId = (int) ($_GET["id"] ?? 0);

$stmt = $conexao->prepare("
    SELECT
        c.id,
        c.servico,
        c.descricao,
        c.data_servico,
        c.horario,
        c.endereco,
        p.nome AS profissional
    FROM contratacoes c
    INNER JOIN profissionais p
        ON p.id = c.profissional_id
    WHERE c.id = ?
    AND c.cliente_id = ?
");

$stmt->bind_param(
    "ii",
    $pedidoId,
    $clienteId
);

$stmt->execute();

$pedido = $stmt->get_result()->fetch_assoc();

if (!$pedido) {
    header("Location: painel_cliente.php");
    exit;
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

<title>Pagamento | CasaResolve</title>

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

.footer-logo img {
    width: 170px;
    height: auto;
}

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

<a href="index.php" class="logo">

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


<main class="payment-page">

<div class="payment-container">


<div class="payment-summary">

<span class="eyebrow">
PEDIDO
</span>

<h1>
Finalizar pagamento
</h1>

<p>
Confira os dados do seu pedido antes de escolher a forma de pagamento.
</p>


<div class="summary-item">

<span>Profissional</span>

<strong>
<?= htmlspecialchars($pedido["profissional"]) ?>
</strong>

</div>


<div class="summary-item">

<span>Serviço</span>

<strong>
<?= htmlspecialchars($pedido["servico"]) ?>
</strong>

</div>


<div class="summary-item">

<span>Data</span>

<strong>
<?= date(
    "d/m/Y",
    strtotime($pedido["data_servico"])
) ?>
</strong>

</div>


<div class="summary-item">

<span>Horário</span>

<strong>
<?= htmlspecialchars($pedido["horario"]) ?>
</strong>

</div>

</div>


<div class="payment-box">

<span class="eyebrow">
FORMA DE PAGAMENTO
</span>

<h2>
Como deseja pagar?
</h2>

<p>
Escolha uma das opções abaixo.
</p>


<form
    action="confirmar_pagamento.php"
    method="POST"
    id="paymentForm"
>

<input
    type="hidden"
    name="pedido_id"
    value="<?= $pedidoId ?>"
>


<div class="payment-options">


<label class="payment-option">

<input
    type="radio"
    name="forma_pagamento"
    value="pix"
    onchange="mostrarPagamento('pix')"
    required
>

<div>

<strong>
PIX
</strong>

<span>
Pagamento por QR Code
</span>

</div>

</label>


<label class="payment-option">

<input
    type="radio"
    name="forma_pagamento"
    value="cartao"
    onchange="mostrarPagamento('cartao')"
>

<div>

<strong>
Cartão de crédito
</strong>

<span>
Pagamento com cartão
</span>

</div>

</label>

</div>


<div
    id="pixArea"
    class="payment-extra"
>

<div class="pix-icon">

<svg viewBox="0 0 24 24">

<rect
    x="4"
    y="4"
    width="6"
    height="6"
/>

<rect
    x="14"
    y="4"
    width="6"
    height="6"
/>

<rect
    x="4"
    y="14"
    width="6"
    height="6"
/>

<path d="M14 14h3v3h3v3h-6v-3h-3v-3h3z"/>

</svg>

</div>

<h3>
Pagamento via PIX
</h3>

<p>
Depois de confirmar, o QR Code será apresentado.
</p>

</div>


<div
    id="cartaoArea"
    class="payment-extra"
>

<label>

Número do cartão

<input
    type="text"
    name="cartao_numero"
    placeholder="0000 0000 0000 0000"
    maxlength="19"
>

</label>


<div class="card-row">

<label>

Validade

<input
    type="text"
    name="cartao_validade"
    placeholder="MM/AA"
    maxlength="5"
>

</label>


<label>

CVV

<input
    type="text"
    name="cartao_cvv"
    placeholder="123"
    maxlength="3"
>

</label>

</div>

</div>


<button
    type="submit"
    class="btn primary payment-button"
>

Continuar pagamento

<svg viewBox="0 0 24 24">

<path d="M5 12h14"/>
<path d="m13 6 6 6-6 6"/>

</svg>

</button>

</form>

</div>

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

<a href="painel_cliente.php">
Minha conta
</a>

</div>

</div>

<small>
© 2026 CasaResolve
</small>

</footer>


<script>

function mostrarPagamento(tipo) {

    const pix =
        document.getElementById("pixArea");

    const cartao =
        document.getElementById("cartaoArea");


    pix.style.display = "none";

    cartao.style.display = "none";


    if (tipo === "pix") {

        pix.style.display = "block";

    }


    if (tipo === "cartao") {

        cartao.style.display = "block";

    }

}

</script>

</body>

</html>