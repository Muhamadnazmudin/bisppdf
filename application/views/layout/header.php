<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? $title . ' | BISP PDF Cilimus' : 'BISP PDF Cilimus - Tools PDF Sekolah' ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO -->
    <meta name="description" content="<?= isset($description) ? $description : 'BISP PDF adalah aplikasi pengolah PDF resmi SMK Cilimus. Gabungkan, pisahkan, kompres dan konversi PDF dengan cepat dan mudah untuk kebutuhan administrasi sekolah.' ?>">
    
    <meta name="keywords" content="PDF sekolah, merge PDF, split PDF, kompres PDF, convert PDF, SMK Cilimus">
    <meta name="author" content="SMK Cilimus">

    <!-- Open Graph (Preview WhatsApp / Share) -->
    <meta property="og:title" content="<?= isset($title) ? $title . ' | BISP PDF Cilimus' : 'BISP PDF Cilimus' ?>">
    <meta property="og:description" content="<?= isset($description) ? $description : 'Tools PDF resmi sekolah untuk kebutuhan administrasi dan pembelajaran.' ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= base_url('assets/img/logo.png') ?>">

    <!-- Font & Icon -->
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">

    <!-- SB Admin -->
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- Custom PDF UI -->
    <link href="<?= base_url('assets/css/pdf-tools.css') ?>" rel="stylesheet">
</head>


<body id="page-top">

<!-- TOP NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid px-4">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold" href="<?= base_url() ?>">
            <span style="color:#ef4444">Bisp</span>
            <span style="color:#111827">Pdf</span>
            <small class="text-muted ms-1">SMKN 1 Cilimus</small>
        </a>

        <!-- TOGGLE -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#pdfNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="pdfNavbar">
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/merge') ?>">Merge PDF</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/split') ?>">Split PDF</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pdf/compress') ?>">Compress PDF</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        Convert PDF
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">PDF to Word</a>
                        <a class="dropdown-item" href="#">PDF to Excel</a>
                        <a class="dropdown-item" href="#">PDF to JPG</a>
                    </div>
                </li>

            </ul>

            <!-- RIGHT -->
            <div class="d-flex">
                <a href="#" class="btn btn-outline-secondary btn-sm mr-2">Login</a>
                <a href="#" class="btn btn-danger btn-sm">Sign Up</a>
            </div>
        </div>

    </div>
</nav>

<!-- PAGE WRAPPER -->
<div id="wrapper" style="padding-top:75px">
