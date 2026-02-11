<div class="container-fluid">
    <h4 class="mb-4">Compress PDF</h4>

    <form action="<?= base_url('pdf/process_compress') ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Pilih File PDF</label>
            <input type="file" name="pdf" class="form-control" accept="application/pdf" required>
        </div>

        <button class="btn btn-primary">
            <i class="fas fa-compress"></i> Compress
        </button>
    </form>
</div>
