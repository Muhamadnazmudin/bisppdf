<div class="container-fluid">
    <h4 class="mb-4">Split PDF</h4>

    <form action="<?= base_url('pdf/process_split') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="form-group">
            <label>Pilih File PDF</label>
            <input type="file"
                   name="pdf"
                   class="form-control"
                   accept="application/pdf"
                   required>
        </div>

        <div class="form-group">
            <label>Range Halaman</label>
            <input type="text"
                   name="range"
                   class="form-control"
                   placeholder="Contoh: 1-3 atau 5"
                   required>
        </div>

        <button class="btn btn-warning">
            <i class="fas fa-cut"></i>
            Split PDF
        </button>
    </form>
</div>
