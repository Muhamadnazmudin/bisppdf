<div class="pdf-hero">
    <h1>Semua tools PDF sekolah dalam satu tempat</h1>
    <p>
        Gabung, pisahkan, kompres, dan konversi PDF dengan cepat.
        Gratis dan mudah digunakan untuk siswa & guru.
    </p>

    <div class="pdf-tabs">
        <button class="active">All</button>
        <button>Organize PDF</button>
        <button>Optimize PDF</button>
        <button>Convert PDF</button>
        <button>Edit PDF</button>
    </div>
</div>

<div class="container pdf-tools">
    <div class="tools-grid">

        <!-- Merge -->
        <a href="<?= base_url('pdf/merge') ?>" class="pdf-card">
            <div class="icon merge">
                <i class="fas fa-object-group"></i>
            </div>
            <h5>Merge PDF</h5>
            <p>Gabungkan beberapa PDF menjadi satu file</p>
        </a>

        <!-- Split -->
        <a href="<?= base_url('pdf/split') ?>" class="pdf-card">
            <div class="icon split">
                <i class="fas fa-cut"></i>
            </div>
            <h5>Split PDF</h5>
            <p>Pisahkan halaman PDF dengan mudah</p>
        </a>

        <!-- Compress -->
        <a href="<?= base_url('pdf/compress') ?>" class="pdf-card">
            <div class="icon compress">
                <i class="fas fa-compress"></i>
            </div>
            <h5>Compress PDF</h5>
            <p>Perkecil ukuran PDF tanpa mengurangi kualitas</p>
        </a>

        <!-- PDF to Word -->
        <div class="pdf-card disabled">
            <div class="icon word">
                <i class="fas fa-file-word"></i>
            </div>
            <h5>PDF to Word</h5>
            <p>Aktif di server</p>
        </div>

        <!-- Word to PDF -->
        <div class="pdf-card disabled">
            <div class="icon pdf">
                <i class="fas fa-file-pdf"></i>
            </div>
            <h5>Word to PDF</h5>
            <p>Aktif di server</p>
        </div>

        <!-- JPG to PDF -->
        <a href="<?= base_url('pdf/image_to_pdf') ?>" class="pdf-card">
            <div class="icon image">
                <i class="fas fa-file-image"></i>
            </div>
            <h5>JPG to PDF</h5>
            <p>Konversi gambar ke PDF</p>
        </a>

    </div>
</div>
