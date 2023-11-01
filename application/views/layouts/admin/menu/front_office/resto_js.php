
<script>
    $(document).ready(function () {
        //Identity
        var identity = "<?php echo $identity; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="front_office/resto"]').addClass('active');

        //Url
        var url = "<?= base_url('front_office/resto'); ?>";
    });
</script>