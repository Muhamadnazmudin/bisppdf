<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/pdf-tools.css') ?>">

<div class="container-fluid">

    <!-- HERO SECTION -->
    <div class="pdf-hero text-center">
        <h1>Semua tools PDF sekolah dalam satu tempat</h1>
        <p>
            Gabungkan, pisahkan, kompres dan konversi PDF dengan cepat dan mudah.
            Dirancang untuk kebutuhan administrasi sekolah.
        </p>
    </div>

    <!-- TOOLS GRID -->
    <div class="pdf-tools">
        <div class="tools-grid">

            <!-- Merge PDF -->
            <a href="<?= base_url('pdf/merge') ?>" class="pdf-card">
                <div class="icon merge">
                    <i class="fas fa-object-group"></i>
                </div>
                <h5>Merge PDF</h5>
                <p>Gabungkan beberapa file PDF menjadi satu dokumen.</p>
            </a>

            <!-- Split PDF -->
            <a href="<?= base_url('pdf/split') ?>" class="pdf-card">
                <div class="icon split">
                    <i class="fas fa-cut"></i>
                </div>
                <h5>Split PDF</h5>
                <p>Pisahkan halaman PDF menjadi beberapa file.</p>
            </a>

            <!-- Compress PDF -->
            <a href="<?= base_url('pdf/compress') ?>" class="pdf-card">
                <div class="icon compress">
                    <i class="fas fa-compress"></i>
                </div>
                <h5>Compress PDF</h5>
                <p>Perkecil ukuran file tanpa mengurangi kualitas signifikan.</p>
            </a>

            <!-- Image to PDF -->
            <a href="<?= base_url('pdf/image_to_pdf') ?>" class="pdf-card">
                <div class="icon image">
                    <i class="fas fa-file-image"></i>
                </div>
                <h5>JPG to PDF</h5>
                <p>Konversi gambar JPG atau PNG menjadi PDF.</p>
            </a>

            <!-- PDF to Word (Disabled) -->
            <div class="pdf-card disabled">
                <div class="icon word">
                    <i class="fas fa-file-word"></i>
                </div>
                <h5>PDF to Word</h5>
                <p>Segera hadir.</p>
            </div>

            <!-- Word to PDF (Disabled) -->
            <div class="pdf-card disabled">
                <div class="icon pdf">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <h5>Word to PDF</h5>
                <p>Segera hadir.</p>
            </div>

        </div>
    </div>

</div>
