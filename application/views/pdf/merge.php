<style>
    #previewArea .card {
    border-radius: 8px;
}
#previewArea small {
    font-size: 12px;
}
#addPlusBtn {
    font-size: 18px;
}
#fileCount {
    font-size: 12px;
}

    </style>
<div class="container-fluid">
    <h4 class="mb-4">Merge PDF</h4>

    <form action="<?= base_url('pdf/process_merge') ?>"
          method="post"
          enctype="multipart/form-data"
          id="mergeForm">

        <div class="form-group">
            <label>Pilih beberapa file PDF</label>
            <input type="file"
                   id="pdfInput"
                   name="pdf[]"
                   class="form-control"
                   accept="application/pdf"
                   multiple
                   required>
            <small class="text-muted">
                Minimal 2 file PDF
            </small>
        </div>
<div class="d-flex align-items-center mb-3">
    <button type="button" class="btn btn-dark btn-sm mr-2" id="addMoreBtn">
        Add more files
    </button>

    <div class="position-relative">
        <button type="button" class="btn btn-danger rounded-circle" id="addPlusBtn"
                style="width:44px;height:44px;">
            <i class="fas fa-plus"></i>
        </button>
        <span id="fileCount"
              class="badge badge-light position-absolute"
              style="top:-6px;right:-6px;">
            0
        </span>
    </div>
</div>

        <!-- Preview area -->
        <div class="row mt-4" id="previewArea"></div>

        <button class="btn btn-success mt-3" id="mergeBtn" disabled>
            <i class="fas fa-object-group"></i>
            Merge PDF
        </button>
    </form>
</div>
<script>
const input     = document.getElementById('pdfInput');
const preview   = document.getElementById('previewArea');
const mergeBtn  = document.getElementById('mergeBtn');
const addBtn    = document.getElementById('addMoreBtn');
const addPlus   = document.getElementById('addPlusBtn');
const fileCount = document.getElementById('fileCount');

let filesArray = [];

// buka file picker
addBtn.onclick  = () => input.click();
addPlus.onclick = () => input.click();

// saat pilih file
input.addEventListener('change', function () {
    const newFiles = Array.from(this.files);

    newFiles.forEach(f => {
        // cegah file duplikat (nama + size)
        if (!filesArray.some(x => x.name === f.name && x.size === f.size)) {
            filesArray.push(f);
        }
    });

    syncInputFiles();
    renderPreview();
});

function syncInputFiles() {
    const dt = new DataTransfer();
    filesArray.forEach(f => dt.items.add(f));
    input.files = dt.files;
}

function renderPreview() {
    preview.innerHTML = '';
    fileCount.innerText = filesArray.length;

    mergeBtn.disabled = filesArray.length < 2;

    filesArray.forEach((file, index) => {
        const col = document.createElement('div');
        col.className = 'col-md-2 mb-3';

        col.innerHTML = `
            <div class="card shadow-sm h-100 text-center p-2">
                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                <small class="text-truncate">${file.name}</small>
                <button type="button"
                        class="btn btn-sm btn-link text-danger"
                        onclick="removeFile(${index})">
                    Remove
                </button>
            </div>
        `;
        preview.appendChild(col);
    });
}

function removeFile(index) {
    filesArray.splice(index, 1);
    syncInputFiles();
    renderPreview();
}
</script>
