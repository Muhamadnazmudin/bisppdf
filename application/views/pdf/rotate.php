<div class="container-fluid">
    <h4 class="mb-4">Rotate PDF</h4>

    <form action="<?= base_url('pdf/process_rotate') ?>"
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
            <label>Rotasi</label>
            <select name="rotate" class="form-control" required>
                <option value="90">90°</option>
                <option value="180">180°</option>
                <option value="270">270°</option>
            </select>
        </div>

        <button class="btn btn-info">
            <i class="fas fa-sync"></i>
            Rotate PDF
        </button>
    </form>
</div>
