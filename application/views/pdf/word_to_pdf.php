<div class="container-fluid">
    <h4 class="mb-4">Word to PDF</h4>

    <form action="<?= base_url('pdf/process_word_to_pdf') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="form-group">
            <label>Pilih File Word (.doc / .docx)</label>
            <input type="file"
                   name="doc"
                   class="form-control"
                   accept=".doc,.docx"
                   required>
        </div>

        <button class="btn btn-danger">
            <i class="fas fa-file-pdf"></i>
            Convert to PDF
        </button>
    </form>
</div>
