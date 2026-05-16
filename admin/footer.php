    <footer class="mt-5 mb-3 text-center text-muted small">
        <hr>
        <p class="mb-1">&copy; <?php echo date('Y'); ?> SMK Cokroaminoto 2 Banjarnegara.</p>
        <p>SmartSchool Lite By <a href="https://www.clasnet.co.id" target="_blank" class="text-decoration-none">Clasnet</a></p>
    </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Tulis konten di sini...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadImage(files[i]);
                    }
                }
            }
        });

        function uploadImage(file) {
            let data = new FormData();
            data.append("image", file);
            $.ajax({
                url: "upload_handler.php",
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "POST",
                success: function(url) {
                    // Prepend parent directory if needed (depending on where the page is)
                    // But usually, relative to root is better if base href is set.
                    // For now, assume url returned is correct.
                    $('.summernote').summernote('insertImage', '../' + url);
                },
                error: function(data) {
                    console.log(data);
                    alert("Gagal mengupload gambar.");
                }
            });
        }
    });
</script>
</body>
</html>
