<?php

include "conexao.php";

if (!isset($_SESSION["cliente_id"])) {

    header("Location: login.php");

    exit;
}

$pedidoId =
    (int) ($_GET["id"] ?? 0);

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>PIX | CasaResolve</title>

<link
    rel="stylesheet"
    href="style.css"
>

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
></script>


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

<a href="painel_cliente.php">
Minha conta
</a>

</nav>

</header>



<main class="pix-page">


<div class="pix-card">


<span class="eyebrow">
PAGAMENTO PIX
</span>


<h1>
Escaneie o QR Code
</h1>


<p>
Use o aplicativo do seu banco para
ler o código abaixo.
</p>



<div
    id="qrcode"
    class="qrcode"
></div>



<div class="pix-code">


<strong>
Código PIX
</strong>


<input
    type="text"
    id="pixCode"
    value="CASARESOLVE-PEDIDO-<?= $pedidoId ?>"
    readonly
>


<button
    onclick="copiarPix()"
    class="btn secondary"
>

Copiar código

</button>

</div>



<div class="pix-notice">

Pagamento de demonstração do projeto CasaResolve.

</div>



<a
    href="painel_cliente.php?pagamento=sucesso"
    class="btn primary"
>

Já realizei o pagamento

</a>


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


const codigo =
"CASARESOLVE-PEDIDO-<?= $pedidoId ?>";


new QRCode(

    document.getElementById("qrcode"),

    {

        text: codigo,

        width: 210,

        height: 210

    }

);



function copiarPix() {

    const campo =
        document.getElementById("pixCode");

    campo.select();

    navigator.clipboard.writeText(
        campo.value
    );

    alert("Código PIX copiado!");

}


</script>


</body>

</html>