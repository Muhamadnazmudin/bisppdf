<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= base_url('assets/css/pdf-tools.css') ?>">

<div class="pdf-page">

    <!-- HEADER -->
    <div class="pdf-header">
        <h1>Semua tools PDF sekolah dalam satu tempat</h1>
        <p>
            Gabungkan, pisahkan, kompres dan konversi PDF dengan cepat dan mudah.
            Dirancang untuk kebutuhan administrasi sekolah.
        </p>
    </div>

    <!-- TOOLS GRID -->
    <div class="tools-grid">

        <!-- Merge PDF -->
        <a href="<?= base_url('pdf/merge') ?>" class="tool-card">
            <div class="icon merge">
                <i class="fas fa-object-group"></i>
            </div>
            <h4>Merge PDF</h4>
            <p>Gabungkan beberapa file PDF menjadi satu dokumen.</p>
        </a>

        <!-- Split PDF -->
        <a href="<?= base_url('pdf/split') ?>" class="tool-card">
            <div class="icon split">
                <i class="fas fa-cut"></i>
            </div>
            <h4>Split PDF</h4>
            <p>Pisahkan halaman PDF menjadi beberapa file.</p>
        </a>

        <!-- Compress PDF -->
        <a href="<?= base_url('pdf/compress') ?>" class="tool-card">
            <div class="icon compress">
                <i class="fas fa-compress"></i>
            </div>
            <h4>Compress PDF</h4>
            <p>Perkecil ukuran file tanpa mengurangi kualitas signifikan.</p>
        </a>

        <!-- JPG to PDF -->
        <a href="<?= base_url('pdf/image_to_pdf') ?>" class="tool-card">
            <div class="icon image">
                <i class="fas fa-file-image"></i>
            </div>
            <h4>JPG to PDF</h4>
            <p>Konversi gambar JPG atau PNG menjadi PDF.</p>
        </a>

       <!-- PDF to Word -->
<a href="<?= base_url('pdf/pdf_to_word') ?>" class="tool-card">
    <div class="icon word">
        <i class="fas fa-file-word"></i>
    </div>
    <h4>PDF to Word</h4>
    <p>Konversi file PDF menjadi dokumen Word (.docx).</p>
</a>

<!-- Word to PDF -->
<a href="<?= base_url('pdf/word_to_pdf') ?>" class="tool-card">
    <div class="icon pdf">
        <i class="fas fa-file-pdf"></i>
    </div>
    <h4>Word to PDF</h4>
    <p>Ubah dokumen Word menjadi file PDF.</p>
</a>

    </div>

</div>
