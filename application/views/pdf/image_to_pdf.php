<div class="container-fluid">
    <h4 class="mb-4">Image to PDF</h4>

    <form action="<?= base_url('pdf/process_image_to_pdf') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="form-group">
            <label>Pilih Gambar (JPG / PNG)</label>
            <input type="file"
                   name="image[]"
                   class="form-control"
                   accept="image/*"
                   multiple
                   required>
        </div>

        <button class="btn btn-primary">
            <i class="fas fa-file-image"></i>
            Convert to PDF
        </button>
    </form>
</div>
