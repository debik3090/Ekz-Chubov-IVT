<template>
    <div class="excel-upload-container">
        <div
            class="dropzone"
            :class="{ 'dragover': isDragging, 'success': uploadSuccess, 'error': uploadError }"
            @dragover.prevent="handleDragOver"
            @dragleave.prevent="handleDragLeave"
            @drop.prevent="handleDrop"
        >
            <div v-if="!isUploading && !uploadSuccess" class="dropzone-content">
                <div class="icon">📁</div>
                <h3>Перетащите Excel файл сюда</h3>
                <p>или</p>
                <label class="btn btn-primary">
                    Выберите файл
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileSelect"
                        accept=".xlsx,.xls,.csv"
                        style="display: none;"
                    >
                </label>
                <p class="text-muted mt-3">Поддерживаемые форматы: XLSX, XLS, CSV</p>
            </div>

            <div v-if="isUploading" class="upload-progress">
                <div class="spinner"></div>
                <p>Загрузка и обработка файла...</p>
            </div>

            <div v-if="uploadSuccess" class="upload-result success">
                <div class="icon">✅</div>
                <h3>Файл успешно загружен!</h3>
                <p>{{ uploadMessage }}</p>
                <button @click="reset" class="btn btn-success mt-3">Загрузить ещё один файл</button>
                <a href="/products" class="btn btn-primary mt-3">Перейти к товарам</a>
            </div>

            <div v-if="uploadError" class="upload-result error">
                <div class="icon">❌</div>
                <h3>Ошибка загрузки</h3>
                <p>{{ errorMessage }}</p>
                <button @click="reset" class="btn btn-danger mt-3">Попробовать снова</button>
            </div>
        </div>

        <div v-if="selectedFile && !isUploading && !uploadSuccess" class="file-info mt-3">
            <strong>Выбранный файл:</strong> {{ selectedFile.name }}
            <span class="text-muted">({{ formatFileSize(selectedFile.size) }})</span>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isDragging: false,
            selectedFile: null,
            isUploading: false,
            uploadSuccess: false,
            uploadError: false,
            uploadMessage: '',
            errorMessage: '',
        }
    },
    methods: {
        handleDragOver(e) {
            this.isDragging = true;
        },
        handleDragLeave(e) {
            this.isDragging = false;
        },
        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;

            if (files.length > 0) {
                this.validateAndUpload(files[0]);
            }
        },
        handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                this.validateAndUpload(files[0]);
            }
        },
        validateAndUpload(file) {
            // Проверка расширения файла
            const validExtensions = ['.xlsx', '.xls', '.csv'];
            const fileName = file.name.toLowerCase();
            const hasValidExtension = validExtensions.some(ext => fileName.endsWith(ext));

            if (!hasValidExtension) {
                this.showError('Неверный формат файла. Пожалуйста, загрузите файл Excel (.xlsx, .xls) или CSV (.csv)');
                return;
            }

            // Проверка размера (макс 10МБ)
            if (file.size > 10 * 1024 * 1024) {
                this.showError('Файл слишком большой. Максимальный размер: 10 МБ');
                return;
            }

            this.selectedFile = file;
            this.uploadFile(file);
        },
        uploadFile(file) {
            this.isUploading = true;
            this.uploadError = false;
            this.uploadSuccess = false;

            const formData = new FormData();
            formData.append('file', file);

            // Получаем CSRF токен
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                this.isUploading = false;

                if (data.success) {
                    this.uploadSuccess = true;
                    this.uploadMessage = data.message;
                } else {
                    this.showError(data.message || 'Произошла ошибка при загрузке файла');
                }
            })
            .catch(error => {
                this.isUploading = false;
                this.showError('Ошибка соединения с сервером');
                console.error('Upload error:', error);
            });
        },
        showError(message) {
            this.uploadError = true;
            this.errorMessage = message;
            this.selectedFile = null;
        },
        reset() {
            this.isDragging = false;
            this.selectedFile = null;
            this.isUploading = false;
            this.uploadSuccess = false;
            this.uploadError = false;
            this.uploadMessage = '';
            this.errorMessage = '';

            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
}
</script>

<style scoped>
.excel-upload-container {
    max-width: 600px;
    margin: 0 auto;
}

.dropzone {
    border: 3px dashed #ccc;
    border-radius: 10px;
    padding: 60px 20px;
    text-align: center;
    background: #f9f9f9;
    transition: all 0.3s ease;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropzone.dragover {
    border-color: #007bff;
    background: #e7f3ff;
}

.dropzone.success {
    border-color: #28a745;
    background: #d4edda;
}

.dropzone.error {
    border-color: #dc3545;
    background: #f8d7da;
}

.dropzone-content .icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.dropzone h3 {
    color: #333;
    margin-bottom: 10px;
}

.upload-progress {
    text-align: center;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #007bff;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.upload-result {
    text-align: center;
}

.upload-result .icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.file-info {
    padding: 15px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin: 0 5px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c82333;
}
</style>
