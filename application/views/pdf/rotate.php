<div class="container-fluid py-5">

    <!-- HERO -->
    <div id="uploadScreen" class="text-center">
        <h1 class="mb-3 font-weight-bold">Rotate PDF Pages</h1>
        <p class="text-muted mb-4">
            Klik icon rotate di tiap halaman untuk memutar.
        </p>

        <label class="btn btn-info btn-lg px-5 shadow">
            Select PDF file
            <input type="file"
                   id="pdfFile"
                   accept="application/pdf"
                   hidden>
        </label>
    </div>

    <!-- EDITOR -->
    <div id="editorScreen" class="d-none">
        <div class="row">

            <div class="col-lg-8">
                <div class="preview-wrapper p-3 bg-light rounded">
                    <div id="pagesContainer" class="row"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow border-0 sticky-top" style="top:20px;">
                    <div class="card-body text-center p-4">
                        <h4 class="mb-4">Apply Rotation</h4>

                        <form action="<?= base_url('pdf/process_rotate') ?>"
                              method="post"
                              enctype="multipart/form-data"
                              id="rotateForm">

                            <input type="file" name="pdf" id="realFileInput" hidden required>
                            <input type="hidden" name="rotations" id="rotationsInput">

                            <button class="btn btn-info btn-lg btn-block">
                                Rotate PDF →
                            </button>
                        </form>

                        <small class="text-muted d-block mt-3">
                            Klik icon ↻ untuk rotate 90°
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
    border-radius: 10px;
    padding: 10px;
    position: relative;
    margin-bottom: 20px;
    text-align: center;
    transition: 0.2s;
}

.page-box canvas {
    width: 100%;
    transition: transform 0.3s ease;
}

.rotate-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #17a2b8;
    color: #fff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
}
</style>
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
    const rotationsInput = document.getElementById('rotationsInput');

    let rotations = {};

    fileInput.addEventListener('change', function () {

        const file = this.files[0];
        if (!file) return;

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
                rotations = {};

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
            col.className = "col-md-3";

            const box = document.createElement('div');
            box.className = "page-box";

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            page.render({ canvasContext: ctx, viewport: viewport });

            const rotateBtn = document.createElement('div');
            rotateBtn.className = "rotate-btn";
            rotateBtn.innerHTML = "↻";

            rotations[pageNumber] = 0;

            rotateBtn.addEventListener('click', function() {

                rotations[pageNumber] += 90;
                if (rotations[pageNumber] >= 360) rotations[pageNumber] = 0;

                canvas.style.transform = "rotate(" + rotations[pageNumber] + "deg)";

                rotationsInput.value = JSON.stringify(rotations);
            });

            box.appendChild(canvas);
            box.appendChild(rotateBtn);
            col.appendChild(box);
            container.appendChild(col);

        });
    }

});
</script>
