<?php
include "conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">         

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CasaResolve | Serviços para sua casa</title>

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


        .category-icon {
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 18px;
            background: #edf2ea;
        }

        .category-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-icon img {
            transform: scale(1.05);
        }

        .house-drawing {
            width: 100%;
            height: 180px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .house-drawing img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }


        @media (max-width: 768px) {

            .logo img {
                width: 125px;
            }

            .category-icon {
                height: 140px;
            }

            .house-drawing {
                height: 160px;
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

        <a href="index.php" class="active">
            Início
        </a>

        <a href="profissionais.php">
            Profissionais
        </a>

        <a href="solicitar.php">
            Solicitar serviço
        </a>

        <a href="login.php">
            Entrar
        </a>

    </nav>

</header>

<main>

<section class="home-intro">


    <div class="intro-text">

        <span class="eyebrow">
            SERVIÇOS PARA SUA CASA
        </span>

        <h1>
            Precisa de ajuda em casa?
        </h1>

        <p>
            Encontre profissionais para pequenos
            reparos, limpeza e manutenção residencial.
        </p>


        <div class="intro-buttons">

            <a
                href="profissionais.php"
                class="btn primary"
            >
                Encontrar profissional
            </a>

            <a
                href="solicitar.php"
                class="btn secondary"
            >
                Solicitar serviço
            </a>

        </div>

    </div>


    <div class="intro-box">


        <div class="house-drawing">

            <img
                src="img/logo_projeto.png"
                alt="CasaResolve"
            >

        </div>


        <div class="intro-box-content">

            <span class="intro-label">
                CASA RESOLVE
            </span>

            <strong>
                Casa em ordem
            </strong>

            <p>
                Profissionais para cuidar
                do que sua casa precisa.
            </p>


            <div class="intro-features">

                <span>
                    ✓ Profissionais cadastrados
                </span>

                <span>
                    ✓ Serviços residenciais
                </span>

                <span>
                    ✓ Contratação simples
                </span>

            </div>

        </div>

    </div>

</section>


<section class="home-section">


    <div class="section-heading">

        <div>

            <span class="eyebrow">
                CATEGORIAS
            </span>

            <h2>
                O que você precisa?
            </h2>

        </div>


        <a href="profissionais.php">
            Ver todos
        </a>

    </div>


    <div class="category-grid">


        <a
            href="profissionais.php?servico=Encanador"
            class="category-card"
        >

            <div class="category-icon">

                <img
                    src="img/encanador.png"
                    alt="Encanador"
                >

            </div>


            <h3>
                Encanador
            </h3>

            <p>
                Vazamentos, torneiras e encanamentos.
            </p>

        </a>

        <a
            href="profissionais.php?servico=Eletricista"
            class="category-card"
        >

            <div class="category-icon">

                <img
                    src="img/eletricista.png"
                    alt="Eletricista"
                >

            </div>


            <h3>
                Eletricista
            </h3>

            <p>
                Instalações, tomadas e problemas elétricos.
            </p>

        </a>

        <a
            href="profissionais.php?servico=Diarista"
            class="category-card"
        >

            <div class="category-icon">

                <img
                    src="img/diarista.png"
                    alt="Diarista"
                >

            </div>


            <h3>
                Diarista
            </h3>

            <p>
                Limpeza e organização da sua casa.
            </p>

        </a>


        <a
            href="profissionais.php?servico=Manutenção"
            class="category-card"
        >

            <div class="category-icon">

                <img
                    src="img/manutencao.png"
                    alt="Manutenção"
                >

            </div>


            <h3>
                Manutenção
            </h3>

            <p>
                Reparos e pequenos serviços residenciais.
            </p>

        </a>

    </div>

</section>

<section class="how-section">


    <div class="section-heading">

        <div>

            <span class="eyebrow">
                COMO FUNCIONA
            </span>

            <h2>
                É simples contratar
            </h2>

        </div>

    </div>


    <div class="steps">

        <div class="step">

            <span class="step-number">
                01
            </span>

            <div>

                <h3>
                    Escolha o serviço
                </h3>

                <p>
                    Encontre a categoria que
                    corresponde ao que você precisa.
                </p>

            </div>

        </div>

        <div class="step">

            <span class="step-number">
                02
            </span>

            <div>

                <h3>
                    Escolha o profissional
                </h3>

                <p>
                    Veja os profissionais cadastrados
                    e escolha quem deseja contratar.
                </p>

            </div>

        </div>


        <div class="step">

            <span class="step-number">
                03
            </span>

            <div>

                <h3>
                    Envie seu pedido
                </h3>

                <p>
                    Informe o problema, endereço,
                    data e horário desejados.
                </p>

            </div>

        </div>

    </div>

</section>

<section class="professional-call">


    <div>

        <span class="eyebrow">
            PARA PROFISSIONAIS
        </span>

        <h2>
            Você trabalha com serviços residenciais?
        </h2>

        <p>
            Cadastre-se no CasaResolve e receba
            pedidos de clientes que precisam do seu trabalho.
        </p>

    </div>


    <a
        href="cadastro_profissional.php"
        class="btn light"
    >
        Quero ser profissional
    </a>

</section>

</main>
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