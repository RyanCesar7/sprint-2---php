<?php

include "conexao.php";


if (!isset($_SESSION["cliente_id"])) {

    header("Location: login.php");

    exit;
}


$clienteId =
    (int)$_SESSION["cliente_id"];


$stmt = $conexao->prepare(

"SELECT

c.id,
c.servico,
c.descricao,
c.data_servico,
c.horario,
c.endereco,
c.status,

p.nome AS profissional

FROM contratacoes c

INNER JOIN profissionais p
ON p.id = c.profissional_id

WHERE c.cliente_id = ?

ORDER BY c.id DESC"

);


$stmt->bind_param(
    "i",
    $clienteId
);

$stmt->execute();

$pedidos =
    $stmt->get_result();


$stmtCliente = $conexao->prepare(
    "SELECT nome
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
Minha conta | CasaResolve
</title>

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

<a href="logout.php">
Sair
</a>

</nav>

</header>


<main class="client-dashboard">

<div class="dashboard-header">

<div>

<span class="eyebrow">
MINHA CONTA
</span>

<h1>
Olá, <?= htmlspecialchars(
    $cliente["nome"]
) ?>.
</h1>

<p>
Aqui você acompanha seus pedidos.
</p>

</div>


<a
href="profissionais.php"
class="btn primary"
>

Contratar profissional

</a>

</div>


<?php if (isset($_GET["pedido"])): ?>

<div class="alert success">

Pedido enviado com sucesso!
Agora aguarde a resposta do profissional.

</div>

<?php endif; ?>


<section class="orders-section">

<div class="section-title">

<h2>
Meus pedidos
</h2>

<span>
<?= $pedidos->num_rows ?> pedido(s)
</span>

</div>


<?php if ($pedidos->num_rows > 0): ?>


<?php while ($pedido = $pedidos->fetch_assoc()): ?>

<article class="order-card">

<div>

<span class="professional-category">

<?= htmlspecialchars(
    $pedido["servico"]
) ?>

</span>

<h3>

<?= htmlspecialchars(
    $pedido["profissional"]
) ?>

</h3>

<p>

<?= htmlspecialchars(
    $pedido["descricao"]
) ?>

</p>

<div class="order-details">

<span>

<?= date(
    "d/m/Y",
    strtotime(
        $pedido["data_servico"]
    )
) ?>

</span>

<span>

<?= htmlspecialchars(
    $pedido["horario"]
) ?>

</span>

<span>

<?= htmlspecialchars(
    $pedido["endereco"]
) ?>

</span>

</div>

</div>


<span class="status <?= htmlspecialchars(
    $pedido["status"]
) ?>">

<?php

$nomes = [

    "pendente" => "Aguardando",
    "confirmado" => "Confirmado",
    "concluido" => "Concluído",
    "cancelado" => "Cancelado"

];

echo $nomes[
    $pedido["status"]
] ?? $pedido["status"];

?>

</span>

</article>

<?php endwhile; ?>


<?php else: ?>

<div class="empty-box">

<h2>
Você ainda não fez nenhum pedido.
</h2>

<p>
Encontre um profissional para começar.
</p>

<a
href="profissionais.php"
class="btn primary"
>
Ver profissionais
</a>

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