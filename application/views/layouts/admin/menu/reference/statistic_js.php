
<script>
    $(document).ready(function () {
        // var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');
        console.log(menu_link);

    });
</script>