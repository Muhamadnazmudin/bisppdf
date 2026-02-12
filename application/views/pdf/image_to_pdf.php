<div class="container-fluid py-5">

    <!-- ================= HERO ================= -->
    <div id="uploadScreen" class="text-center">

        <h1 class="mb-3 font-weight-bold">Image to PDF</h1>
        <p class="text-muted mb-4">
            Upload multiple JPG or PNG images and convert them into one PDF file.
        </p>

        <label class="btn btn-primary btn-lg px-5 shadow">
            Select Images
            <input type="file"
                   id="imageInput"
                   accept="image/*"
                   multiple
                   hidden>
        </label>

        <p class="mt-3 text-muted">or drag & drop images here</p>
    </div>


    <!-- ================= EDITOR ================= -->
    <div id="editorScreen" class="d-none">

        <div class="row">

            <!-- LEFT PREVIEW -->
            <div class="col-lg-8">
                <div class="preview-wrapper p-3 bg-light rounded">
                    <div id="imageContainer" class="row"></div>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="col-lg-4">
                <div class="card shadow border-0 sticky-top" style="top:20px;">
                    <div class="card-body p-4 text-center">

                        <h4 class="mb-3">Convert to PDF</h4>

                        <span id="imageCounter" class="badge badge-primary mb-3">
                            0 images
                        </span>

                        <form action="<?= base_url('pdf/process_image_to_pdf') ?>"
                              method="post"
                              enctype="multipart/form-data"
                              id="convertForm">

                            <input type="file"
                                   name="image[]"
                                   id="realImageInput"
                                   multiple
                                   hidden
                                   required>

                            <button class="btn btn-primary btn-lg btn-block mt-3">
                                Convert →
                            </button>
                        </form>

                        <button type="button"
                                class="btn btn-outline-danger btn-sm mt-3"
                                id="clearImages">
                            Clear All
                        </button>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
<style>
.preview-wrapper { min-height: 500px; }

.image-box {
    position: relative;
    margin-bottom: 20px;
    border-radius: 10px;
    overflow: hidden;
    cursor: grab;
    transition: 0.2s;
}

.image-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}

.image-box img {
    width: 100%;
    border-radius: 10px;
}

.image-remove {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #dc3545;
    color: #fff;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    cursor: pointer;
}

.dragging {
    opacity: 0.5;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {

    const uploadScreen = document.getElementById('uploadScreen');
    const editorScreen = document.getElementById('editorScreen');
    const imageInput = document.getElementById('imageInput');
    const realInput = document.getElementById('realImageInput');
    const container = document.getElementById('imageContainer');
    const counter = document.getElementById('imageCounter');
    const clearBtn = document.getElementById('clearImages');

    let filesArray = [];

    function updateCounter() {
        counter.innerText = filesArray.length + " images";
    }

    function refreshRealInput() {
        const dt = new DataTransfer();
        filesArray.forEach(file => dt.items.add(file));
        realInput.files = dt.files;
    }

    function renderImages() {
        container.innerHTML = '';

        filesArray.forEach((file, index) => {

            const col = document.createElement('div');
            col.className = "col-md-4";

            const box = document.createElement('div');
            box.className = "image-box";
            box.draggable = true;
            box.dataset.index = index;

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);

            const remove = document.createElement('div');
            remove.className = "image-remove";
            remove.innerText = "×";

            remove.onclick = () => {
                filesArray.splice(index, 1);
                renderImages();
                refreshRealInput();
                updateCounter();
            };

            box.appendChild(img);
            box.appendChild(remove);
            col.appendChild(box);
            container.appendChild(col);

            // Drag reorder
            box.addEventListener('dragstart', () => {
                box.classList.add('dragging');
            });

            box.addEventListener('dragend', () => {
                box.classList.remove('dragging');
            });

            box.addEventListener('dragover', e => {
                e.preventDefault();
                const dragging = document.querySelector('.dragging');
                if (dragging && dragging !== box) {
                    const from = parseInt(dragging.dataset.index);
                    const to = parseInt(box.dataset.index);
                    const moved = filesArray.splice(from, 1)[0];
                    filesArray.splice(to, 0, moved);
                    renderImages();
                    refreshRealInput();
                }
            });

        });
    }

    imageInput.addEventListener('change', function() {

        const newFiles = Array.from(this.files);
        filesArray = filesArray.concat(newFiles);

        uploadScreen.classList.add('d-none');
        editorScreen.classList.remove('d-none');

        renderImages();
        refreshRealInput();
        updateCounter();
    });

    clearBtn.addEventListener('click', () => {
        filesArray = [];
        container.innerHTML = '';
        refreshRealInput();
        updateCounter();
        editorScreen.classList.add('d-none');
        uploadScreen.classList.remove('d-none');
    });

});
</script>
