<div class="container-fluid">
    <h4 class="mb-4">Remove Page PDF</h4>

    <form action="<?= base_url('pdf/process_remove_page') ?>"
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
            <label>Halaman yang dihapus</label>
            <input type="text"
                   name="pages"
                   class="form-control"
                   placeholder="Contoh: 1,3 atau 2-4"
                   required>
        </div>

        <button class="btn btn-danger">
            <i class="fas fa-trash"></i>
            Remove Page
        </button>
    </form>
</div>
