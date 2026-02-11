<style>
.merge-wrapper {
    max-width: 1150px;
    margin: auto;
}

/* DROP ZONE */
.drop-zone {
    border: 2px dashed #ef4444;
    border-radius: 18px;
    padding: 60px 20px;
    text-align: center;
    background: #fff;
    cursor: pointer;
    transition: .2s;
}
.drop-zone:hover { background: #fef2f2; }
.drop-zone.processing {
    opacity: .5;
    pointer-events: none;
}
.drop-zone i {
    font-size: 52px;
    color: #ef4444;
}
.drop-zone h5 {
    margin-top: 15px;
    font-weight: 600;
}
.drop-zone p {
    color: #6b7280;
    font-size: 14px;
}

/* PREVIEW */
.pdf-thumb {
    border-radius: 14px;
    background: #fff;
    transition: .2s;
}
.pdf-thumb:hover {
    transform: translateY(-4px);
}
.pdf-canvas {
    width: 100%;
    border-radius: 6px;
    background: #f1f5f9;
}
.pdf-thumb small {
    font-size: 12px;
}

/* STATE */
.hidden { display: none; }
.countdown {
    font-size: 14px;
    color: #6b7280;
}
</style>

<div class="container-fluid merge-wrapper">

    <h3 class="fw-bold mb-1">Merge PDF</h3>
    <p class="text-muted mb-4">
        Gabungkan beberapa file PDF menjadi satu file dengan urutan sesuai keinginan
    </p>

    <form id="mergeForm">

        <!-- DROP ZONE -->
        <div id="dropZone" class="drop-zone mb-4">
            <i class="fas fa-file-pdf"></i>
            <h5>Drag & drop file PDF di sini</h5>
            <p>atau klik untuk memilih file (minimal 2 PDF)</p>
            <input type="file"
                   id="pdfInput"
                   accept="application/pdf"
                   multiple
                   hidden>
        </div>

        <!-- ACTION BAR -->
        <div class="mb-3">
            <button type="button"
                    class="btn btn-outline-dark btn-sm"
                    id="addMoreBtn">
                <i class="fas fa-plus"></i> Tambah File
            </button>

            <span class="badge badge-secondary ml-2">
                <span id="fileCount">0</span> file dipilih
            </span>
        </div>

        <!-- PREVIEW -->
        <div id="previewWrapper">
            <div class="row" id="previewArea"></div>
        </div>

        <!-- PROCESSING -->
        <div id="processingBox" class="text-center hidden mt-5">
            <div class="spinner-border text-danger mb-3"></div>
            <p>Sedang menggabungkan PDF, mohon tunggu...</p>
        </div>

        <!-- RESULT -->
        <div id="resultBox" class="text-center hidden mt-5">
            <h5 class="mb-3">✅ PDF berhasil digabung</h5>

            <a id="downloadBtn"
               href="#"
               class="btn btn-danger btn-lg mb-3"
               download>
                <i class="fas fa-download"></i> Download PDF
            </a>

            <div class="countdown">
                Download otomatis dalam
                <b><span id="countdown">30</span></b> detik
            </div>
        </div>

        <!-- SUBMIT -->
        <button id="mergeBtn"
                class="btn btn-danger btn-lg mt-4"
                disabled>
            Merge PDF
        </button>

    </form>
</div>
<script>
const input      = document.getElementById('pdfInput');
const preview    = document.getElementById('previewArea');
const dropZone   = document.getElementById('dropZone');
const addBtn     = document.getElementById('addMoreBtn');
const mergeBtn   = document.getElementById('mergeBtn');
const fileCount  = document.getElementById('fileCount');

let filesArray = [];
let countdownTimer;

/* OPEN FILE PICKER */
dropZone.onclick = addBtn.onclick = () => input.click();

/* DRAG DROP */
dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('bg-light');
});
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('bg-light');
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('bg-light');
    handleFiles(e.dataTransfer.files);
});

/* INPUT CHANGE */
input.addEventListener('change', () => handleFiles(input.files));

function handleFiles(files) {
    Array.from(files).forEach(file => {
        if (!filesArray.some(f => f.name === file.name && f.size === file.size)) {
            filesArray.push(file);
        }
    });
    syncInput();
    renderPreview();
}

function syncInput() {
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
        col.className = 'col-lg-2 col-md-3 col-sm-4 mb-4';

        col.innerHTML = `
            <div class="pdf-thumb card shadow-sm p-2 text-center">
                <canvas id="canvas-${index}" class="pdf-canvas"></canvas>
                <small class="d-block text-truncate mt-2">${file.name}</small>
                <button type="button"
                        class="btn btn-sm btn-link text-danger"
                        onclick="removeFile(${index})">
                    Remove
                </button>
            </div>
        `;
        preview.appendChild(col);

        renderPdfThumbnail(file, `canvas-${index}`);
    });
}

function removeFile(index) {
    filesArray.splice(index, 1);
    syncInput();
    renderPreview();
}

/* PDF PREVIEW (HALAMAN 1) */
function renderPdfThumbnail(file, canvasId) {
    const reader = new FileReader();

    reader.onload = function () {
        const typedarray = new Uint8Array(this.result);

        pdfjsLib.getDocument(typedarray).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 0.4 });
                const canvas = document.getElementById(canvasId);
                const ctx = canvas.getContext('2d');

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                page.render({
                    canvasContext: ctx,
                    viewport: viewport
                });
            });
        });
    };
    reader.readAsArrayBuffer(file);
}

/* SUBMIT (AJAX) */
document.getElementById('mergeForm').addEventListener('submit', e => {
    e.preventDefault();

    document.getElementById('previewWrapper').classList.add('hidden');
    document.getElementById('processingBox').classList.remove('hidden');
    dropZone.classList.add('processing');

    const formData = new FormData();
    filesArray.forEach(f => formData.append('pdf[]', f));

    fetch("<?= base_url('pdf/process_merge') ?>", {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success || !res.download_url) {
            alert('Gagal merge PDF');
            return;
        }

        document.getElementById('processingBox').classList.add('hidden');
        document.getElementById('resultBox').classList.remove('hidden');

        document.getElementById('downloadBtn').href = res.download_url;
        startCountdown(res.download_url);
    });
});

/* AUTO DOWNLOAD 30s */
function startCountdown(url) {
    let t = 30;
    const el = document.getElementById('countdown');
    el.innerText = t;

    clearInterval(countdownTimer);
    countdownTimer = setInterval(() => {
        t--;
        el.innerText = t;

        if (t <= 0) {
            clearInterval(countdownTimer);
            window.location.href = url;
        }
    }, 1000);
}
</script>
