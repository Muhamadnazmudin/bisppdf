<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="utf-8">
    <title><?= isset($title) ? $title . ' | BISP PDF Cilimus' : 'BISP PDF Cilimus - Tools PDF Sekolah' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/logobispar.png') ?>">

    <!-- SEO -->
    <meta name="description" content="<?= isset($description) ? $description : 'BISP PDF adalah aplikasi pengolah PDF resmi SMK Cilimus. Gabungkan, pisahkan, kompres dan konversi PDF dengan cepat dan mudah.' ?>">
    <meta property="og:site_name" content="BISP PDF SMKN 1 Cilimus">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Open Graph -->
<meta property="og:title" content="BISP PDF SMKN 1 Cilimus - Tools PDF Resmi Sekolah">
<meta property="og:description" content="Gabungkan, pisahkan, kompres dan konversi PDF dengan cepat, aman, dan gratis untuk kebutuhan administrasi & pembelajaran di SMKN 1 Cilimus.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://bisppdf.smkncilimus1.sch.id/">
<meta property="og:image" content="https://bisppdf.smkncilimus1.sch.id/assets/img/logobispar.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">


    <!-- FontAwesome -->
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- Custom -->
    <link href="<?= base_url('assets/css/pdf-tools.css') ?>" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .modern-navbar {
            backdrop-filter: blur(8px);
            background: rgba(255,255,255,0.85);
            transition: 0.3s ease;
        }

        .navbar-brand span:first-child {
            color: #ef4444;
        }

        .navbar-brand span:last-child {
            color: #111827;
        }

        .nav-link {
            font-weight: 500;
            color: #374151 !important;
            transition: 0.2s ease;
        }

        .nav-link:hover {
            color: #ef4444 !important;
        }

        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .dropdown-item:hover {
            background: #fef2f2;
            color: #ef4444;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                padding-top: 10px;
            }
        }
        .navbar-toggler-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(17,24,39, 0.8)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
}

    </style>

</head>

<body id="page-top">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light modern-navbar shadow-sm fixed-top">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand font-weight-bold" href="<?= base_url() ?>">
            <span>Bisp</span><span>Pdf</span>
            <small class="text-muted ml-2">SMKN 1 Cilimus</small>
        </a>

        <!-- TOGGLE -->
        <button class="navbar-toggler border-0" 
        type="button" 
        data-toggle="collapse" 
        data-target="#pdfNavbar"
        aria-controls="pdfNavbar"
        aria-expanded="false"
        aria-label="Toggle navigation">

    <span class="navbar-toggler-icon"></span>
</button>


        <!-- MENU -->
        <div class="collapse navbar-collapse" id="pdfNavbar">
            <ul class="navbar-nav ml-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/merge') ?>">Gabungkan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/split') ?>">Pisahkan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/compress') ?>">Kompres</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/delete') ?>">Hapus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/rotate') ?>">Putar</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        Convert
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?= base_url('pdf/image_to_pdf') ?>">Image to PDF</a>
                        <a class="dropdown-item" href="<?= base_url('#') ?>">PDF to Word</a>
                        <a class="dropdown-item" href="<?= base_url('#') ?>">Word to PDF</a>
                    </div>
                </li>

            </ul>
        </div>

    </div>
</nav>

<!-- WRAPPER -->
<div id="wrapper" style="padding-top:90px;">
