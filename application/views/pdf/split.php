<div class="container-fluid py-5">

    <!-- ================= HERO SCREEN (BEFORE UPLOAD) ================= -->
    <div id="uploadScreen" class="text-center">

        <h1 class="mb-3 font-weight-bold">Split PDF file</h1>
        <p class="text-muted mb-4">
            Separate one page or a whole set for easy conversion into independent PDF files.
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


    <!-- ================= EDITOR SCREEN (AFTER UPLOAD) ================= -->
    <div id="editorScreen" class="d-none">

        <div class="row">

            <!-- LEFT SIDE PREVIEW -->
            <div class="col-lg-8">
                <div class="preview-wrapper p-3 bg-light rounded">
                    <div id="pagesContainer" class="row"></div>
                </div>
            </div>

            <!-- RIGHT SIDE PANEL -->
            <div class="col-lg-4">
                <div class="card shadow border-0 sticky-top" style="top:20px;">
                    <div class="card-body p-4">

                        <h4 class="mb-4 text-center">Split</h4>

                        <form action="<?= base_url('pdf/process_split') ?>"
                              method="post"
                              enctype="multipart/form-data"
                              id="splitForm">

                            <!-- hidden input for submit -->
                            <input type="file" name="pdf" id="realFileInput" hidden required>

                            <!-- Mode -->
                            <label class="font-weight-bold">Range mode:</label>

                                <div class="btn-group d-flex mt-2 mb-3">
                                    <button type="button" id="btnCustom" class="btn btn-outline-danger w-50 active">
                                        Custom
                                    </button>
                                    <button type="button" id="btnFixed" class="btn btn-outline-secondary w-50">
                                        Fixed
                                    </button>
                                </div>

                                <input type="hidden" name="mode" id="splitMode" value="custom">

                                <!-- CUSTOM RANGE -->
                                <div id="customRangeBox" class="form-group">
                                    <input type="text"
                                        name="range"
                                        id="customRangeInput"
                                        class="form-control"
                                        placeholder="Contoh: 1-3,5">
                                </div>

                                <!-- FIXED RANGE -->
                                <div id="fixedRangeBox" class="form-group d-none">
                                    <label>Split into page ranges of:</label>
                                    <input type="number"
                                        name="fixed_range"
                                        id="fixedRangeInput"
                                        class="form-control"
                                        min="1"
                                        value="1">
                                </div>
                            <!-- Submit -->
                            <button class="btn btn-danger btn-lg btn-block mt-4">
                                Split PDF →
                            </button>

                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
.preview-wrapper {
    min-height: 600px;
}

.page-box {
    background: #fff;
    border: 1px dashed #ccc;
    padding: 10px;
    margin-bottom: 20px;
    text-align: center;
    border-radius: 8px;
    transition: 0.2s;
}

.page-box:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.page-number {
    font-size: 13px;
    color: #555;
    margin-top: 5px;
}
</style>

<!-- PDF JS -->
<script src="<?= base_url('assets/pdfjs/pdf.js') ?>"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc =
    "<?= base_url('assets/pdfjs/pdf.worker.js') ?>";
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const uploadScreen = document.getElementById('uploadScreen');
    const editorScreen = document.getElementById('editorScreen');
    const fileInput = document.getElementById('pdfFile');
    const realFileInput = document.getElementById('realFileInput');
    const container = document.getElementById('pagesContainer');

    fileInput.addEventListener('change', function () {

        const file = this.files[0];
        if (!file || file.type !== 'application/pdf') return;

        // copy file ke input form asli
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        realFileInput.files = dataTransfer.files;

        // hide hero, show editor
        uploadScreen.classList.add('d-none');
        editorScreen.classList.remove('d-none');

        const reader = new FileReader();

        reader.onload = function () {

            const typedarray = new Uint8Array(this.result);

            pdfjsLib.getDocument(typedarray).promise.then(pdf => {

                container.innerHTML = '';

                for (let i = 1; i <= pdf.numPages; i++) {
                    renderPage(pdf, i);
                }

            }).catch(err => {
                console.error("PDF load error:", err);
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

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            page.render({
                canvasContext: ctx,
                viewport: viewport
            });

            const number = document.createElement('div');
            number.className = "page-number";
            number.innerText = pageNumber;

            box.appendChild(canvas);
            box.appendChild(number);
            col.appendChild(box);
            container.appendChild(col);

        });
    }

});
const btnCustom = document.getElementById('btnCustom');
const btnFixed = document.getElementById('btnFixed');

const customBox = document.getElementById('customRangeBox');
const fixedBox = document.getElementById('fixedRangeBox');

const splitMode = document.getElementById('splitMode');

btnCustom.addEventListener('click', function () {

    btnCustom.classList.add('btn-outline-danger','active');
    btnCustom.classList.remove('btn-outline-secondary');

    btnFixed.classList.remove('btn-outline-danger','active');
    btnFixed.classList.add('btn-outline-secondary');

    customBox.classList.remove('d-none');
    fixedBox.classList.add('d-none');

    splitMode.value = 'custom';
});

btnFixed.addEventListener('click', function () {

    btnFixed.classList.add('btn-outline-danger','active');
    btnFixed.classList.remove('btn-outline-secondary');

    btnCustom.classList.remove('btn-outline-danger','active');
    btnCustom.classList.add('btn-outline-secondary');

    fixedBox.classList.remove('d-none');
    customBox.classList.add('d-none');

    splitMode.value = 'fixed';
});

</script>
