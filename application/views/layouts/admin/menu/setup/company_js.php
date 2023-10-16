<script>
    $(document).ready(function () {
        // $("select").select2();
        var url = "<?= base_url('konfigurasi/manage'); ?>";
        // $.alert('Pembelian dan Penjualan Belum loadAccountMap');
        $(".collapse").addClass('in');

        /**
         * Untuk menangani sub-menu
         */
        $("[data-sub-menu-parent]").each(function (index, element) {
            // Elemen parent untuk sub-menu
            var $element = $(element);

            // Nama parent
            var name = $element.attr("data-sub-menu-parent");

            // Anak-anak dari parent
            var $submenus = $("[data-sub-menu-child-of='" + name + "']")

            // Sembunyikan
            $submenus.hide();

            // Tampilkan / sembunyikan saat parent diklik
            $element.click(function () {
                $submenus.toggle();
            })
        });

        $("select").attr('data-id', 0);
        $("select").on('change', function () {
            var select_id = $(this).attr('id');
            var select_name = $(this).attr('name');
            var select_value = $(this).find(':selected').val();
            var select_value_previous = 0;
            console.log(select_id + ', ' + select_name + ', Before: ' + select_value_previous + ' => After: ' + select_value);
        });
    });
</script>