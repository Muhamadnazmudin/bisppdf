<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">BISP Kompres PDF</h1>

    <div class="row">

        <!-- Compress -->
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('pdf/compress') ?>" class="tool-card">
                <i class="fas fa-compress text-primary"></i>
                <h6>Compress PDF</h6>
                <p>Perkecil ukuran file PDF</p>
            </a>
        </div>

        <!-- Merge -->
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('pdf/merge') ?>" class="tool-card">
                <i class="fas fa-object-group text-success"></i>
                <h6>Merge PDF</h6>
                <p>Gabungkan beberapa PDF</p>
            </a>
        </div>

        <!-- Split -->
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('pdf/split') ?>" class="tool-card">
                <i class="fas fa-cut text-warning"></i>
                <h6>Split PDF</h6>
                <p>Pisahkan halaman PDF</p>
            </a>
        </div>

        <!-- Rotate (disabled) -->
        <div class="col-md-3 mb-4">
            <div class="tool-card disabled">
                <i class="fas fa-sync"></i>
                <h6>Rotate PDF</h6>
                <p>Aktif di server</p>
            </div>
        </div>

        <!-- Remove Page -->
        <div class="col-md-3 mb-4">
            <div class="tool-card disabled">
                <i class="fas fa-trash"></i>
                <h6>Remove Page</h6>
                <p>Aktif di server</p>
            </div>
        </div>

        <!-- PDF to Word -->
        <div class="col-md-3 mb-4">
            <div class="tool-card disabled">
                <i class="fas fa-file-word"></i>
                <h6>PDF to Word</h6>
                <p>Aktif di server</p>
            </div>
        </div>

        <!-- Word to PDF -->
        <div class="col-md-3 mb-4">
            <div class="tool-card disabled">
                <i class="fas fa-file-pdf"></i>
                <h6>Word to PDF</h6>
                <p>Aktif di server</p>
            </div>
        </div>

        <!-- Image to PDF -->
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('pdf/image_to_pdf') ?>" class="tool-card">
                <i class="fas fa-file-image text-primary"></i>
                <h6>Image to PDF</h6>
                <p>JPG / PNG ke PDF</p>
            </a>
        </div>

    </div>
</div>
<style>
    .tool-card {
    display: block;
    background: #fff;
    border-radius: 10px;
    padding: 30px 20px;
    text-align: center;
    height: 100%;
    box-shadow: 0 0.15rem 1.75rem rgba(58,59,69,.15);
    text-decoration: none;
    color: #333;
    transition: all .2s ease;
}

.tool-card i {
    font-size: 42px;
    margin-bottom: 15px;
}

.tool-card h6 {
    font-weight: 600;
    margin-bottom: 6px;
}

.tool-card p {
    font-size: 13px;
    color: #6c757d;
    margin: 0;
}

.tool-card:hover {
    transform: translateY(-4px);
    text-decoration: none;
    color: #000;
}

.tool-card.disabled {
    background: #f8f9fc;
    color: #aaa;
    cursor: not-allowed;
}

.tool-card.disabled i {
    color: #bbb;
}

    </style>