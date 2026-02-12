<div class="container py-5">

    <div class="text-center mb-5">
        <h1 class="font-weight-bold">Compress PDF</h1>
        <p class="text-muted">Reduce PDF size while keeping quality.</p>
    </div>

    <div class="card shadow-sm p-5 text-center">

        <!-- Upload Area -->
        <input type="file" id="pdfFile" accept="application/pdf" hidden>

        <button id="selectBtn" class="btn btn-danger btn-lg px-5">
            Select PDF File
        </button>

        <div id="fileInfo" class="mt-4 d-none">
            <h5 id="fileName"></h5>
            <small class="text-muted" id="fileSize"></small>
        </div>

        <!-- Compression Option -->
        <div class="mt-4 d-none" id="optionsBox">
            <select id="compressionLevel" class="form-control w-50 mx-auto">
                <option value="low">Low Compression (Best Quality)</option>
                <option value="medium" selected>Medium Compression</option>
                <option value="high">High Compression (Smallest Size)</option>
            </select>

            <button id="compressBtn" class="btn btn-danger btn-lg mt-4 px-5">
                Compress PDF
            </button>
        </div>

        <!-- Progress -->
        <div class="progress mt-4 d-none" style="height:25px;" id="progressBox">
            <div id="progressBar"
                 class="progress-bar progress-bar-striped progress-bar-animated"
                 role="progressbar"
                 style="width: 0%">0%</div>
        </div>

    </div>

</div>
<script>
const selectBtn = document.getElementById('selectBtn');
const fileInput = document.getElementById('pdfFile');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const optionsBox = document.getElementById('optionsBox');
const compressBtn = document.getElementById('compressBtn');
const progressBox = document.getElementById('progressBox');
const progressBar = document.getElementById('progressBar');

let selectedFile = null;

selectBtn.onclick = () => fileInput.click();

fileInput.onchange = function() {

    selectedFile = this.files[0];
    if (!selectedFile) return;

    fileName.innerText = selectedFile.name;
    fileSize.innerText = (selectedFile.size / (1024*1024)).toFixed(2) + " MB";

    fileInfo.classList.remove('d-none');
    optionsBox.classList.remove('d-none');

    progressBox.classList.add('d-none');
    progressBar.style.width = "0%";
    progressBar.innerText = "0%";
};

compressBtn.onclick = function() {

    if (!selectedFile) return;

    const level = document.getElementById('compressionLevel').value;

    const formData = new FormData();
    formData.append('pdf', selectedFile);
    formData.append('level', level);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "<?= base_url('pdf/process_compress') ?>", true);
    xhr.responseType = "blob";

    progressBox.classList.remove('d-none');
    compressBtn.disabled = true;

    // 🔥 REAL UPLOAD PROGRESS
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            let percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + "%";
            progressBar.innerText = percent + "%";
        }
    };

    xhr.onload = function() {
        compressBtn.disabled = false;

        if (xhr.status === 200) {

            progressBar.style.width = "100%";
            progressBar.innerText = "Processing...";

            // sedikit delay biar smooth
            setTimeout(() => {
                progressBar.innerText = "Download ready";

                const blob = new Blob([xhr.response], { type: "application/pdf" });
                const link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = "compressed.pdf";
                link.click();
            }, 500);

        } else {
            alert("Gagal compress PDF");
        }
    };

    xhr.onerror = function() {
        compressBtn.disabled = false;
        alert("Upload gagal");
    };

    xhr.send(formData);
};
</script>
