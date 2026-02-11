<div class="container-fluid">
    <h4 class="mb-4">PDF to Word</h4>

    <form action="<?= base_url('pdf/process_pdf_to_word') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="form-group">
            <label>Pilih File PDF</label>
            <input type="file"
                   name="pdf"
                   class="form-control"
                   accept="application/pdf"
                   required>
            <small class="text-muted">
                Paling baik untuk PDF berbasis teks (bukan scan)
            </small>
        </div>

        <button class="btn btn-primary">
            <i class="fas fa-file-word"></i>
            Convert to Word
        </button>
    </form>
</div>
