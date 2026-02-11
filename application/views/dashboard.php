<style>
/* ================= HERO ================= */
.dashboard-hero {
    padding: 100px 20px 70px;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa, #eef1f5);
}

.dashboard-hero h1 {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 20px;
}

.dashboard-hero p {
    font-size: 18px;
    color: #6c757d;
    max-width: 700px;
    margin: 0 auto 40px;
}

/* Tabs */
.pdf-tabs {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

.pdf-tabs button {
    border: none;
    background: #fff;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
    border: 1px solid #dee2e6;
}

.pdf-tabs button.active,
.pdf-tabs button:hover {
    background: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

/* ================= TOOLS GRID ================= */
.pdf-tools {
    padding: 60px 15px;
}

.tools-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
}

/* Card */
.pdf-card {
    background: #fff;
    border-radius: 16px;
    padding: 30px 20px;
    text-align: center;
    transition: 0.3s;
    text-decoration: none;
    color: #212529;
    border: 1px solid #eee;
}

.pdf-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

/* Icon circle */
.pdf-card .icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
}

/* Icon Colors */
.merge { background: #0d6efd; }
.split { background: #6610f2; }
.compress { background: #dc3545; }
.word { background: #198754; }
.pdf { background: #fd7e14; }
.image { background: #20c997; }

.pdf-card h5 {
    font-weight: 600;
    margin-bottom: 8px;
}

.pdf-card p {
    font-size: 14px;
    color: #6c757d;
}

.pdf-card.disabled {
    opacity: 0.6;
    pointer-events: none;
}

/* ================= MOBILE ================= */
@media (max-width: 768px) {

    .dashboard-hero {
        padding: 70px 15px 40px;
    }

    .dashboard-hero h1 {
        font-size: 28px;
    }

    .dashboard-hero p {
        font-size: 15px;
    }

    .pdf-card {
        padding: 25px 15px;
    }

}
</style>


<!-- ================= HERO ================= -->
<div class="dashboard-hero">
    <h1>Semua tools PDF sekolah dalam satu tempat</h1>
    <p>
        Gabung, pisahkan, kompres, dan konversi PDF dengan cepat.
        Gratis dan mudah digunakan untuk siswa & guru.
    </p>

    <div class="pdf-tabs">
        <button class="active">All</button>
        <button>Organize</button>
        <button>Optimize</button>
        <button>Convert</button>
        <button>Edit</button>
    </div>
</div>


<!-- ================= TOOLS ================= -->
<div class="container pdf-tools">
    <div class="tools-grid">

        <a href="<?= base_url('pdf/merge') ?>" class="pdf-card">
            <div class="icon merge">
                <i class="fas fa-object-group"></i>
            </div>
            <h5>Merge PDF</h5>
            <p>Gabungkan beberapa PDF menjadi satu file</p>
        </a>

        <a href="<?= base_url('pdf/split') ?>" class="pdf-card">
            <div class="icon split">
                <i class="fas fa-cut"></i>
            </div>
            <h5>Split PDF</h5>
            <p>Pisahkan halaman PDF dengan mudah</p>
        </a>

        <a href="<?= base_url('pdf/compress') ?>" class="pdf-card">
            <div class="icon compress">
                <i class="fas fa-compress"></i>
            </div>
            <h5>Compress PDF</h5>
            <p>Perkecil ukuran PDF tanpa mengurangi kualitas</p>
        </a>

        <div class="pdf-card disabled">
            <div class="icon word">
                <i class="fas fa-file-word"></i>
            </div>
            <h5>PDF to Word</h5>
            <p>Aktif di server</p>
        </div>

        <div class="pdf-card disabled">
            <div class="icon pdf">
                <i class="fas fa-file-pdf"></i>
            </div>
            <h5>Word to PDF</h5>
            <p>Aktif di server</p>
        </div>

        <a href="<?= base_url('pdf/image_to_pdf') ?>" class="pdf-card">
            <div class="icon image">
                <i class="fas fa-file-image"></i>
            </div>
            <h5>JPG to PDF</h5>
            <p>Konversi gambar ke PDF</p>
        </a>

    </div>
</div>
