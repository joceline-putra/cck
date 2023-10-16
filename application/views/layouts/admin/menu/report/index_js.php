
<script>
    $(document).ready(function () {
        $(document).on("click", ".h-title", function (e) {
            var operation = $(this).attr('data-value');
            operation = operation.replace(/-/g, "/");
            window.open(operation, '_blank');
        });
        $(document).on("click", ".btn-report", function (e) {
            var operation = $(this).attr('data-value');
            operation = operation.replace(/-/g, "/");
            window.open(operation, '_blank');
        });
    });
</script>