<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seletor para todos os elementos com a classe .text-area-ckeditor
        var textAreas = document.querySelectorAll('.text-area-ckeditor');

        textAreas.forEach(function (textarea) {
            ClassicEditor
                .create(textarea)
                .catch(function (error) {
                    console.error(error);
                });
        });
    });
</script>