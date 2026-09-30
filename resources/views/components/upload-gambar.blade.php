<div class="upload-gambar-component">
    <div class="row">
        <div class="col-md-6">
            <div class="dropzone-area border rounded p-4 text-center" style="border: 2px dashed #ccc; cursor: pointer;" id="dropzoneArea">
                <i class="bi bi-cloud-upload fs-1"></i>
                <p class="mt-2">Drag & drop gambar disini atau klik untuk upload</p>
                <p class="text-muted small">Maksimal 20MB, format: JPG, PNG</p>
                <input type="file" id="fileInput" class="d-none" accept="image/*" multiple>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center" id="cameraSection">
                    <i class="bi bi-camera fs-1"></i>
                    <p class="mt-2">Ambil foto dari kamera</p>
                    <button type="button" class="btn btn-primary" id="cameraButton">
                        <i class="bi bi-camera"></i> Buka Kamera
                    </button>
                    <video id="cameraVideo" class="w-100 mt-2" style="display: none; border-radius: 5px;" autoplay></video>
                    <canvas id="cameraCanvas" style="display: none;"></canvas>
                    <button type="button" class="btn btn-success mt-2" id="captureButton" style="display: none;">
                        <i class="bi bi-camera-fill"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-danger mt-2" id="closeCameraButton" style="display: none;">
                        <i class="bi bi-x-circle"></i> Tutup Kamera
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3" id="previewContainer">
        <!-- Preview gambar akan muncul disini -->
    </div>
</div>

<style>
    .dropzone-area:hover {
        background-color: #f8f9fa;
        border-color: #3498db !important;
    }
    .preview-item {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    .preview-item img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
        border: 2px solid #ddd;
    }
    .preview-item .remove-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .preview-item .compress-badge {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
    }
    .preview-item .size-badge {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let files = [];
    let stream = null;
    let isCameraOpen = false;

    const dropzoneArea = document.getElementById('dropzoneArea');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const cameraButton = document.getElementById('cameraButton');
    const cameraVideo = document.getElementById('cameraVideo');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const captureButton = document.getElementById('captureButton');
    const closeCameraButton = document.getElementById('closeCameraButton');

    // Dropzone click
    dropzoneArea.addEventListener('click', function() {
        fileInput.click();
    });

    // Dropzone drag and drop
    dropzoneArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#3498db';
        this.style.backgroundColor = '#f0f8ff';
    });

    dropzoneArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#ccc';
        this.style.backgroundColor = 'transparent';
    });

    dropzoneArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#ccc';
        this.style.backgroundColor = 'transparent';
        
        const droppedFiles = e.dataTransfer.files;
        handleFiles(droppedFiles);
    });

    // File input change
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
        this.value = '';
    });

    // Handle files
    function handleFiles(fileList) {
        for (let file of fileList) {
            if (file.type.startsWith('image/')) {
                files.push(file);
                previewFile(file);
            } else {
                alert('File harus berupa gambar');
            }
        }
        updateHiddenInput();
    }

    // Preview file
    function previewFile(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.dataset.filename = file.name;
            
            const img = document.createElement('img');
            img.src = e.target.result;
            div.appendChild(img);

            // Badge compress
            const compressBadge = document.createElement('span');
            compressBadge.className = 'compress-badge';
            compressBadge.textContent = '🔄 Compress';
            div.appendChild(compressBadge);

            // Badge size
            const sizeBadge = document.createElement('span');
            sizeBadge.className = 'size-badge';
            sizeBadge.textContent = formatSize(file.size);
            div.appendChild(sizeBadge);

            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '×';
            removeBtn.onclick = function() {
                div.remove();
                files = files.filter(f => f.name !== file.name);
                updateHiddenInput();
            };
            div.appendChild(removeBtn);

            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    }

    // Format size
    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // Update hidden input
    function updateHiddenInput() {
        // Hapus input tersembunyi lama
        document.querySelectorAll('.file-data-input').forEach(el => el.remove());
        
        // Tambahkan input tersembunyi untuk setiap file
        files.forEach((file, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `gambar[${index}]`;
            input.className = 'file-data-input';
            input.value = file.name;
            // Simpan file reference
            input.dataset.file = JSON.stringify({
                name: file.name,
                size: file.size,
                type: file.type
            });
            document.querySelector('form').appendChild(input);
        });
    }

    // Camera functions
    cameraButton.addEventListener('click', async function() {
        if (isCameraOpen) {
            closeCamera();
            return;
        }

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });
            cameraVideo.srcObject = stream;
            cameraVideo.style.display = 'block';
            captureButton.style.display = 'inline-block';
            closeCameraButton.style.display = 'inline-block';
            cameraButton.textContent = '📷 Tutup Kamera';
            isCameraOpen = true;

            // Sembunyikan dropzone area
            dropzoneArea.style.display = 'none';

        } catch (err) {
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
            console.error(err);
        }
    });

    captureButton.addEventListener('click', function() {
        const context = cameraCanvas.getContext('2d');
        cameraCanvas.width = cameraVideo.videoWidth;
        cameraCanvas.height = cameraVideo.videoHeight;
        context.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

        // Convert to file
        cameraCanvas.toBlob(function(blob) {
            const file = new File([blob], 'camera-capture-' + Date.now() + '.jpg', {
                type: 'image/jpeg'
            });
            files.push(file);
            previewFile(file);
            updateHiddenInput();
            
            // Close camera after capture
            closeCamera();
        }, 'image/jpeg', 0.92);
    });

    closeCameraButton.addEventListener('click', closeCamera);

    function closeCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraVideo.style.display = 'none';
        captureButton.style.display = 'none';
        closeCameraButton.style.display = 'none';
        cameraButton.textContent = '📷 Buka Kamera';
        isCameraOpen = false;
        dropzoneArea.style.display = 'block';
    }
});
</script>