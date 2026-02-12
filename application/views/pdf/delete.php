<div class="container-fluid py-5">

    <!-- ================= HERO ================= -->
    <div id="uploadScreen" class="text-center">

        <h1 class="mb-3 font-weight-bold">Delete PDF Pages</h1>
        <p class="text-muted mb-4">
            Klik halaman yang ingin dihapus. Halaman bertanda X tidak akan disertakan.
        </p>

        <label class="btn btn-danger btn-lg px-5 shadow">
            Select PDF file
            <input type="file"
                   id="pdfFile"
                   accept="application/pdf"
                   hidden>
        </label>

        <p class="mt-3 text-muted">or drop PDF here</p>

    </div>


    <!-- ================= EDITOR ================= -->
    <div id="editorScreen" class="d-none">

        <div class="row">

            <!-- LEFT PREVIEW -->
            <div class="col-lg-8">
                <div class="preview-wrapper p-3 bg-light rounded">
                    <div id="pagesContainer" class="row"></div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="col-lg-4">
                <div class="card shadow border-0 sticky-top" style="top:20px;">
                    <div class="card-body p-4 text-center">

                        <h4 class="mb-3">Delete Pages</h4>

                        <div class="mb-3">
                            <span id="selectedCounter" class="badge badge-danger">
                                0 pages selected
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-sm btn-outline-danger" id="selectAll">
                                Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAll">
                                Clear
                            </button>
                        </div>

                        <form action="<?= base_url('pdf/process_delete') ?>"
                            method="post"
                            enctype="multipart/form-data"
                            id="deleteForm">

                            <input type="file" name="pdf_file" id="realFileInput" hidden required>
                            <input type="hidden" name="pages" id="deletedPages">

                            <button class="btn btn-danger btn-lg btn-block mt-3">
                                Delete Selected Pages →
                            </button>
                        </form>

                        <small class="text-muted d-block mt-3">
                            Click or drag to select pages
                        </small>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
<style>
.preview-wrapper { min-height: 600px; }

.page-box {
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 20px;
    text-align: center;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    transition: 0.2s;
}

.page-box:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.page-box.deleted {
    opacity: 0.5;
    border: 2px solid #dc3545;
}

.delete-overlay {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #dc3545;
    color: #fff;
    font-weight: bold;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.page-number {
    font-size: 13px;
    margin-top: 5px;
}
.page-box {
    background: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 20px;
    text-align: center;
    border-radius: 10px;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
}

.page-box.deleted {
    opacity: 0.6;
    border: 2px solid #dc3545;
    transform: scale(0.97);
}

.delete-overlay {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #dc3545;
    color: #fff;
    font-weight: bold;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    animation: pop 0.2s ease;
}

@keyframes pop {
    from { transform: scale(0); }
    to { transform: scale(1); }
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const uploadScreen = document.getElementById('uploadScreen');
    const editorScreen = document.getElementById('editorScreen');
    const fileInput = document.getElementById('pdfFile');
    const realFileInput = document.getElementById('realFileInput');
    const container = document.getElementById('pagesContainer');
    const deletedInput = document.getElementById('deletedPages');
    const counter = document.getElementById('selectedCounter');
    const selectAllBtn = document.getElementById('selectAll');
    const clearAllBtn = document.getElementById('clearAll');

    let deletedPages = [];
    let isDragging = false;

    function updateCounter() {
        counter.innerText = deletedPages.length + " pages selected";
        deletedInput.value = deletedPages.join(',');
    }

    function togglePage(box) {

        const page = parseInt(box.dataset.page);

        if (deletedPages.includes(page)) {

            deletedPages = deletedPages.filter(p => p !== page);
            box.classList.remove('deleted');

            const overlay = box.querySelector('.delete-overlay');
            if (overlay) overlay.remove();

        } else {

            deletedPages.push(page);
            box.classList.add('deleted');

            const overlay = document.createElement('div');
            overlay.className = 'delete-overlay';
            overlay.innerText = 'X';
            box.appendChild(overlay);
        }

        updateCounter();
    }

    fileInput.addEventListener('change', function () {

        const file = this.files[0];
        if (!file || file.type !== 'application/pdf') return;

        const dt = new DataTransfer();
        dt.items.add(file);
        realFileInput.files = dt.files;

        uploadScreen.classList.add('d-none');
        editorScreen.classList.remove('d-none');

        const reader = new FileReader();

        reader.onload = function () {

            const typedarray = new Uint8Array(this.result);

            pdfjsLib.getDocument(typedarray).promise.then(pdf => {

                container.innerHTML = '';
                deletedPages = [];
                updateCounter();

                for (let i = 1; i <= pdf.numPages; i++) {
                    renderPage(pdf, i);
                }

            });
        };

        reader.readAsArrayBuffer(file);
    });

    function renderPage(pdf, pageNumber) {

        pdf.getPage(pageNumber).then(page => {

            const scale = 0.25;
            const viewport = page.getViewport({ scale });

            const col = document.createElement('div');
            col.className = "col-md-3 mb-4";

            const box = document.createElement('div');
            box.className = "page-box";
            box.dataset.page = pageNumber;

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            page.render({ canvasContext: ctx, viewport: viewport });

            const number = document.createElement('div');
            number.className = "page-number";
            number.innerText = pageNumber;

            box.appendChild(canvas);
            box.appendChild(number);
            col.appendChild(box);
            container.appendChild(col);

            box.addEventListener('mousedown', () => {
                isDragging = true;
                togglePage(box);
            });

            box.addEventListener('mouseover', () => {
                if (isDragging) togglePage(box);
            });

        });
    }

    document.addEventListener('mouseup', () => isDragging = false);

    selectAllBtn.addEventListener('click', () => {
        document.querySelectorAll('.page-box').forEach(box => {
            if (!deletedPages.includes(parseInt(box.dataset.page))) {
                togglePage(box);
            }
        });
    });

    clearAllBtn.addEventListener('click', () => {
        deletedPages = [];
        document.querySelectorAll('.page-box').forEach(box => {
            box.classList.remove('deleted');
            const overlay = box.querySelector('.delete-overlay');
            if (overlay) overlay.remove();
        });
        updateCounter();
    });

});

</script>
