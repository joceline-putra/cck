<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="configuration/mapping"]').addClass('active');
        var url = "<?= base_url('konfigurasi/manage'); ?>";
        $(".collapse").addClass('in');

        //Penjualan
        $('#jual_pendapatan_penjualan, #jual_pembayaran_di_muka, #jual_diskon_penjualan, #jual_penjualan_belum_ditagih, #jual_retur_penjualan, #jual_piutang_belum_ditagih, #jual_pengiriman_penjualan, #jual_hutang_pajak_penjualan, #jual_voucher_penjualan').select2({
            placeholder: '--- Pilih ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        source: 'accounts',
                        // group: 5
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });

        //Pembelian
        $('#beli_pembelian, #beli_pembayaran_di_muka, #beli_hutang_belum_ditagih, #beli_pengiriman_pembelian, #beli_pajak_pembelian').select2({
            placeholder: '--- Pilih ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        // category: 0,
                        source: 'accounts',
                        // group: 5
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });

        //AR/AP
        $('#account_payable, #account_receivable').select2({
            placeholder: '--- Pilih ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1, //1=Supplier, 2=Asuransi
                        // category: 0,
                        source: 'accounts',
                        // group: 5
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });

        //Persediaan
        $('#persediaan_persediaan, #persediaan_rusak, #persediaan_umum, #persediaan_produksi').select2({
            placeholder: '--- Pilih ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        // category: 0,
                        source: 'accounts',
                        // group: 5
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });

        //Lainnya
        $('#lain_ekuitas, #lain_aset_tetap').select2({
            placeholder: '--- Pilih ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        // category: 0,
                        source: 'accounts',
                        // group: 5
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });

        //Metode Bayar
        $('#payment_cash, #payment_transfer, #payment_edc, #payment_qris, #payment_free').select2({
            placeholder: '--- Pilih ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        // category: 0,
                        source: 'accounts',
                        group: 1,
                        group_sub:3
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (datas) { //When Select on Click
                if (!datas.id) {
                    return datas.text;
                }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                } else {
                    // return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute         
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });

        /* Untuk menangani sub-menu */
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
            // var select_id = $(this).attr('id');
            // var select_name = $(this).attr('name');
            var map_id = $(this).attr('data-account-map-id');

            //Selected by User on HTML
            var selected_value = $(this).find(':selected').val();
            var selected_text = $(this).find(':selected').text();

            //Default Account ID from accounts_maps
            var previous_account_id = $(this).attr('data-account-id');
            var previous_account_text = $(this).attr('data-account-text');

            //Remove Div Button Where not
            $(".div-button").find("[data-map-id!='" + map_id + "']").hide(300).parent().remove();

            if (parseInt(map_id) > 0) {
                if (previous_account_id != selected_value) {
                    // console.log('MapID: '+map_id+', AccountID: '+previous_account_id);

                    //Menampilkan Div dan Tombol2
                    $(".div-button").hide(300);
                    var dsp = '';
                    dsp += '<div class="div-button form-group" data-map-id="' + map_id + '" style="display:none;">';
                    dsp += '<button type="button" class="btn btn_set btn-mini btn-small btn-primary" data-map-id="' + map_id + '" data-previous-account-id="' + previous_account_id + '" data-previous-account-text="' + previous_account_text + '" data-selected-account-id="' + selected_value + '" data-selected-account-text="' + selected_text + '">Perbarui</button>&nbsp;';
                    dsp += '<button type="button" class="btn btn_reset btn-mini btn-small btn-warning" data-map-id="' + map_id + '" data-account-id="' + previous_account_id + '" data-account-text="' + previous_account_text + '">Batal</button>';
                    dsp += '</div>';
                    $(this).parent().parent().append(dsp);
                    $(".div-button").show(300);
                } else {
                    //Menghilangkan Div dan Tombol2
                    $("[data-map-id='" + map_id + "']").hide(300);
                    setTimeout(function () {
                        $("[data-map-id='" + map_id + "']").remove();
                    }, 200);
                }
            }
        });
        $(document).on("click", ".btn_reset", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var map_id = $(this).attr('data-map-id');
            var previous_account_id = $(this).attr('data-account-id');
            // var previous_account_text = $(this).attr('data-account-text');			

            $("[data-account-map-id='" + map_id + "']").val(previous_account_id).trigger('change');
        });
        $(document).on("click", ".btn_set", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var map_id = $(this).attr('data-map-id');
            var previous_account_id = $(this).attr('data-previous-account-id');
            var previous_account_text = $(this).attr('data-previous-account-text');

            var selected_account_id = $(this).attr('data-selected-account-id');
            var selected_account_text = $(this).attr('data-selected-account-text');

            if(parseInt(selected_account_id) > 0){
                let title = 'Konfirmasi Perubahan';
                let content = 'Anda akan merubah setelan akun dari<br><b style="color:red;">' + previous_account_text + '</b><br> menjadi setelan akun <br><b style="color:green;">' + selected_account_text + '</b> ?';
                $.confirm({
                    title: title,
                    content: content,
                    columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                    closeIcon: true, closeIconClass: 'fas fa-times',
                    animation: 'zoom', closeAnimation: 'bottom', animateFromElement: false, useBootstrap: true,
                    buttons: {
                        button_1: {
                            text: 'Proses',
                            btnClass: 'btn-primary',
                            keys: ['enter'],
                            action: function () {
                                var form = new FormData();
                                form.append('action', 'update-account-map');
                                form.append('map_id', map_id);
                                form.append('tipe', 33);
                                form.append('account_id', selected_account_id);

                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: form,
                                    dataType: 'json', cache: 'false',
                                    contentType: false, processData: false,
                                    beforeSend: function () {},
                                    success: function (d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if (parseInt(s) == 1) {
                                            notif(s, m);

                                            //Set Default on <select>
                                            $("[data-account-map-id='" + map_id + "']").attr('data-account-id', selected_account_id);
                                            $("[data-account-map-id='" + map_id + "']").attr('data-account-text', selected_account_text);

                                            //Remove Button
                                            $("[data-map-id='" + map_id + "']").hide(300);
                                            setTimeout(function () {
                                                $("[data-map-id='" + map_id + "']").remove();
                                            }, 200);
                                        } else {
                                            notif(s, m);
                                        }
                                    },
                                    error: function (xhr, status, err) {
                                        notif(0, err);
                                    }
                                });
                            }
                        },
                        button_2: {
                            text: 'Batalkan',
                            btnClass: 'btn-danger',
                            keys: ['Escape'],
                            action: function () {
                                //Close
                            }
                        }
                    }
                });
            }else{
                notif(0,'Pilih Akun yang sesuai');
            }
        });
        function loadAccountMap() {
            // var prepare = {
            //     id: $("#BY_DATA_ID").attr('data-id'),
            //     code: $("#BY_INPUT").val(),    
            //     name: $("#BY_SELECT").find(':selected').val()    
            // };
            var data = {
                action: 'load-account-map',
                tipe: 33
                        // data: JSON.stringify(prepare)
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {},
                success: function (d) {
                    notif(d.status, d.message);
                    if (parseInt(d.status) === 1) {
                        var r = d.result;
                        // if(r['purchase'].length > 0){
                        // }
                        // if(r['sales'].length > 0){ alert('he');
                        var sl = [];
                        sl = r['sales'];
                        $("select[id='jual_pendapatan_penjualan']").append('' + '<option value="' + sl[1]['account_id'] + '">' + sl[1]['account_code'] + ' - ' + sl[1]['account_name'] + '</option>');
                        $("select[id='jual_pendapatan_penjualan']").val(sl[1]['account_id']).trigger('change');
                        $("select[id='jual_pendapatan_penjualan']").attr("data-account-map-id", +sl[1]['map_id']).attr("data-account-id", +sl[1]['account_id']).attr("data-account-text", "" + sl[1]['account_code'] + ' - ' + sl[1]['account_name'] + "");

                        $("select[id='jual_diskon_penjualan']").append('' + '<option value="' + sl[5]['account_id'] + '">' + sl[5]['account_code'] + ' - ' + sl[5]['account_name'] + '</option>');
                        $("select[id='jual_diskon_penjualan']").val(sl[5]['account_id']).trigger('change');
                        $("select[id='jual_diskon_penjualan']").attr("data-account-map-id", +sl[5]['map_id']).attr("data-account-id", +sl[5]['account_id']).attr("data-account-text", "" + sl[5]['account_code'] + ' - ' + sl[5]['account_name'] + "");

                        $("select[id='jual_retur_penjualan']").append('' + '<option value="' + sl[4]['account_id'] + '">' + sl[4]['account_code'] + ' - ' + sl[4]['account_name'] + '</option>');
                        $("select[id='jual_retur_penjualan']").val(sl[4]['account_id']).trigger('change');
                        $("select[id='jual_retur_penjualan']").attr("data-account-map-id", +sl[4]['map_id']).attr("data-account-id", +sl[4]['account_id']).attr("data-account-text", "" + sl[4]['account_code'] + ' - ' + sl[4]['account_name'] + "");

                        $("select[id='jual_pengiriman_penjualan']").append('' + '<option value="' + sl[7]['account_id'] + '">' + sl[7]['account_code'] + ' - ' + sl[7]['account_name'] + '</option>');
                        $("select[id='jual_pengiriman_penjualan']").val(sl[7]['account_id']).trigger('change');
                        $("select[id='jual_pengiriman_penjualan']").attr("data-account-map-id", +sl[7]['map_id']).attr("data-account-id", +sl[7]['account_id']).attr("data-account-text", "" + sl[7]['account_code'] + ' - ' + sl[7]['account_name'] + "");

                        $("select[id='jual_pembayaran_di_muka']").append('' + '<option value="' + sl[3]['account_id'] + '">' + sl[3]['account_code'] + ' - ' + sl[3]['account_name'] + '</option>');
                        $("select[id='jual_pembayaran_di_muka']").val(sl[3]['account_id']).trigger('change');
                        $("select[id='jual_pembayaran_di_muka']").attr("data-account-map-id", +sl[3]['map_id']).attr("data-account-id", +sl[3]['account_id']).attr("data-account-text", "" + sl[3]['account_code'] + ' - ' + sl[3]['account_name'] + "");

                        $("select[id='jual_penjualan_belum_ditagih']").append('' + '<option value="' + sl[8]['account_id'] + '">' + sl[8]['account_code'] + ' - ' + sl[8]['account_name'] + '</option>');
                        $("select[id='jual_penjualan_belum_ditagih']").val(sl[8]['account_id']).trigger('change');
                        $("select[id='jual_penjualan_belum_ditagih']").attr("data-account-map-id", +sl[8]['map_id']).attr("data-account-id", +sl[8]['account_id']).attr("data-account-text", "" + sl[8]['account_code'] + ' - ' + sl[8]['account_name'] + "");

                        $("select[id='jual_piutang_belum_ditagih']").append('' + '<option value="' + sl[6]['account_id'] + '">' + sl[6]['account_code'] + ' - ' + sl[6]['account_name'] + '</option>');
                        $("select[id='jual_piutang_belum_ditagih']").val(sl[6]['account_id']).trigger('change');
                        $("select[id='jual_piutang_belum_ditagih']").attr("data-account-map-id", +sl[6]['map_id']).attr("data-account-id", +sl[6]['account_id']).attr("data-account-text", "" + sl[6]['account_code'] + ' - ' + sl[6]['account_name'] + "");

                        $("select[id='jual_hutang_pajak_penjualan']").append('' + '<option value="' + sl[2]['account_id'] + '">' + sl[2]['account_code'] + ' - ' + sl[2]['account_name'] + '</option>');
                        $("select[id='jual_hutang_pajak_penjualan']").val(sl[2]['account_id']).trigger('change');
                        $("select[id='jual_hutang_pajak_penjualan']").attr("data-account-map-id", +sl[2]['map_id']).attr("data-account-id", +sl[2]['account_id']).attr("data-account-text", "" + sl[2]['account_code'] + ' - ' + sl[2]['account_name'] + "");
                        // }

                        $("select[id='jual_voucher_penjualan']").append('' + '<option value="' + sl[9]['account_id'] + '">' + sl[9]['account_code'] + ' - ' + sl[9]['account_name'] + '</option>');
                        $("select[id='jual_voucher_penjualan']").val(sl[9]['account_id']).trigger('change');
                        $("select[id='jual_voucher_penjualan']").attr("data-account-map-id", +sl[9]['map_id']).attr("data-account-id", +sl[9]['account_id']).attr("data-account-text", "" + sl[9]['account_code'] + ' - ' + sl[9]['account_name'] + "");

                        var pr = [];
                        pr = r['purchase'];
                        // if(r['inventory'].length > 0){
                        $("select[id='beli_pembelian']").append('' + '<option value="' + pr[1]['account_id'] + '">' + pr[1]['account_code'] + ' - ' + pr[1]['account_name'] + '</option>');
                        $("select[id='beli_pembelian']").val(pr[1]['account_id']).trigger('change');
                        $("select[id='beli_pembelian']").attr("data-account-map-id", +pr[1]['map_id']).attr("data-account-id", +pr[1]['account_id']).attr("data-account-text", "" + pr[1]['account_code'] + ' - ' + pr[1]['account_name'] + "");

                        $("select[id='beli_hutang_belum_ditagih']").append('' + '<option value="' + pr[6]['account_id'] + '">' + pr[6]['account_code'] + ' - ' + pr[6]['account_name'] + '</option>');
                        $("select[id='beli_hutang_belum_ditagih']").val(pr[6]['account_id']).trigger('change');
                        $("select[id='beli_hutang_belum_ditagih']").attr("data-account-map-id", +pr[6]['map_id']).attr("data-account-id", +pr[6]['account_id']).attr("data-account-text", "" + pr[6]['account_code'] + ' - ' + pr[6]['account_name'] + "");

                        $("select[id='beli_pajak_pembelian']").append('' + '<option value="' + pr[2]['account_id'] + '">' + pr[2]['account_code'] + ' - ' + pr[2]['account_name'] + '</option>');
                        $("select[id='beli_pajak_pembelian']").val(pr[2]['account_id']).trigger('change');
                        $("select[id='beli_pajak_pembelian']").attr("data-account-map-id", +pr[2]['map_id']).attr("data-account-id", +pr[2]['account_id']).attr("data-account-text", "" + pr[2]['account_code'] + ' - ' + pr[2]['account_name'] + "");

                        $("select[id='beli_pembayaran_di_muka']").append('' + '<option value="' + pr[3]['account_id'] + '">' + pr[3]['account_code'] + ' - ' + pr[3]['account_name'] + '</option>');
                        $("select[id='beli_pembayaran_di_muka']").val(pr[3]['account_id']).trigger('change');
                        $("select[id='beli_pajak_pembelian']").attr("data-account-map-id", +pr[3]['map_id']).attr("data-account-id", +pr[3]['account_id']).attr("data-account-text", "" + pr[3]['account_code'] + ' - ' + pr[3]['account_name'] + "");

                        $("select[id='beli_pengiriman_pembelian']").append('' + '<option value="' + pr[5]['account_id'] + '">' + pr[5]['account_code'] + ' - ' + pr[5]['account_name'] + '</option>');
                        $("select[id='beli_pengiriman_pembelian']").val(pr[5]['account_id']).trigger('change');
                        $("select[id='beli_pengiriman_pembelian']").attr("data-account-map-id", +pr[5]['map_id']).attr("data-account-id", +pr[5]['account_id']).attr("data-account-text", "" + pr[5]['account_code'] + ' - ' + pr[5]['account_name'] + "");

                        var ps = [];
                        ps = r['inventory'];
                        // if(r['inventory'].length > 0){
                        $("select[id='persediaan_persediaan']").append('' + '<option value="' + ps[1]['account_id'] + '">' + ps[1]['account_code'] + ' - ' + ps[1]['account_name'] + '</option>');
                        $("select[id='persediaan_persediaan']").val(ps[1]['account_id']).trigger('change');
                        $("select[id='persediaan_persediaan']").attr("data-account-map-id", +ps[1]['map_id']).attr("data-account-id", +ps[1]['account_id']).attr("data-account-text", "" + ps[1]['account_code'] + ' - ' + ps[1]['account_name'] + "");

                        $("select[id='persediaan_umum']").append('' + '<option value="' + ps[2]['account_id'] + '">' + ps[2]['account_code'] + ' - ' + ps[2]['account_name'] + '</option>');
                        $("select[id='persediaan_umum']").val(ps[2]['account_id']).trigger('change');
                        $("select[id='persediaan_umum']").attr("data-account-map-id", +ps[2]['map_id']).attr("data-account-id", +ps[2]['account_id']).attr("data-account-text", "" + ps[2]['account_code'] + ' - ' + ps[2]['account_name'] + "");

                        $("select[id='persediaan_rusak']").append('' + '<option value="' + ps[3]['account_id'] + '">' + ps[3]['account_code'] + ' - ' + ps[3]['account_name'] + '</option>');
                        $("select[id='persediaan_rusak']").val(ps[3]['account_id']).trigger('change');
                        $("select[id='persediaan_rusak']").attr("data-account-map-id", +ps[3]['map_id']).attr("data-account-id", +ps[3]['account_id']).attr("data-account-text", "" + ps[3]['account_code'] + ' - ' + ps[3]['account_name'] + "");

                        $("select[id='persediaan_produksi']").append('' + '<option value="' + ps[4]['account_id'] + '">' + ps[4]['account_code'] + ' - ' + ps[4]['account_name'] + '</option>');
                        $("select[id='persediaan_produksi']").val(ps[4]['account_id']).trigger('change');
                        $("select[id='persediaan_produksi']").attr("data-account-map-id", +ps[4]['map_id']).attr("data-account-id", +ps[4]['account_id']).attr("data-account-text", "" + ps[4]['account_code'] + ' - ' + ps[4]['account_name'] + "");
                        // }

                        var ar = [];
                        ar = r['receivable_payable'];
                        // if(r['receivable_payable'].length > 0){
                        $("select[id='account_payable']").append('' + '<option value="' + ar[1]['account_id'] + '">' + ar[1]['account_code'] + ' - ' + ar[1]['account_name'] + '</option>');
                        $("select[id='account_payable']").val(ar[1]['account_id']).trigger('change');
                        $("select[id='account_payable']").attr("data-account-map-id", +ar[1]['map_id']).attr("data-account-id", +ar[1]['account_id']).attr("data-account-text", "" + ar[1]['account_code'] + ' - ' + ar[1]['account_name'] + "");

                        $("select[id='account_receivable']").append('' + '<option value="' + ar[2]['account_id'] + '">' + ar[2]['account_code'] + ' - ' + ar[2]['account_name'] + '</option>');
                        $("select[id='account_receivable']").val(ar[2]['account_id']).trigger('change');
                        $("select[id='account_receivable']").attr("data-account-map-id", +ar[2]['map_id']).attr("data-account-id", +ar[2]['account_id']).attr("data-account-text", "" + ar[2]['account_code'] + ' - ' + ar[2]['account_name'] + "");
                        // }

                        var ln = [];
                        ln = r['other'];
                        // if(r['receivable_payable'].length > 0){
                        $("select[id='lain_ekuitas']").append('' + '<option value="' + ln[1]['account_id'] + '">' + ln[1]['account_code'] + ' - ' + ln[1]['account_name'] + '</option>');
                        $("select[id='lain_ekuitas']").val(ln[1]['account_id']).trigger('change');
                        $("select[id='lain_ekuitas']").attr("data-account-map-id", +ln[1]['map_id']).attr("data-account-id", +ln[1]['account_id']).attr("data-account-text", "" + ln[1]['account_code'] + ' - ' + ln[1]['account_name'] + "");

                        $("select[id='lain_aset_tetap']").append('' + '<option value="' + ln[2]['account_id'] + '">' + ln[2]['account_code'] + ' - ' + ln[2]['account_name'] + '</option>');
                        $("select[id='lain_aset_tetap']").val(ln[2]['account_id']).trigger('change');
                        $("select[id='lain_aset_tetap']").attr("data-account-map-id", +ln[2]['map_id']).attr("data-account-id", +ln[2]['account_id']).attr("data-account-text", "" + ln[2]['account_code'] + ' - ' + ln[2]['account_name'] + "");
                        // } 

                        var pm = [];
                        pm = r['payment_method'];
                        $("select[id='payment_cash']").append('' + '<option value="' + pm[1]['account_id'] + '">' + pm[1]['account_code'] + ' - ' + pm[1]['account_name'] + '</option>');
                        $("select[id='payment_cash']").val(pm[1]['account_id']).trigger('change');
                        $("select[id='payment_cash']").attr("data-account-map-id", +pm[1]['map_id']).attr("data-account-id", +pm[1]['account_id']).attr("data-account-text", "" + pm[1]['account_code'] + ' - ' + pm[1]['account_name'] + "");

                        $("select[id='payment_transfer']").append('' + '<option value="' + pm[2]['account_id'] + '">' + pm[2]['account_code'] + ' - ' + pm[2]['account_name'] + '</option>');
                        $("select[id='payment_transfer']").val(pm[2]['account_id']).trigger('change');
                        $("select[id='payment_transfer']").attr("data-account-map-id", +pm[2]['map_id']).attr("data-account-id", +pm[2]['account_id']).attr("data-account-text", "" + pm[2]['account_code'] + ' - ' + pm[2]['account_name'] + "");                        

                        $("select[id='payment_edc']").append('' + '<option value="' + pm[3]['account_id'] + '">' + pm[3]['account_code'] + ' - ' + pm[3]['account_name'] + '</option>');
                        $("select[id='payment_edc']").val(pm[3]['account_id']).trigger('change');
                        $("select[id='payment_edc']").attr("data-account-map-id", +pm[3]['map_id']).attr("data-account-id", +pm[3]['account_id']).attr("data-account-text", "" + pm[3]['account_code'] + ' - ' + pm[3]['account_name'] + "");

                        $("select[id='payment_qris']").append('' + '<option value="' + pm[4]['account_id'] + '">' + pm[4]['account_code'] + ' - ' + pm[4]['account_name'] + '</option>');
                        $("select[id='payment_qris']").val(pm[4]['account_id']).trigger('change');
                        $("select[id='payment_qris']").attr("data-account-map-id", +pm[4]['map_id']).attr("data-account-id", +pm[4]['account_id']).attr("data-account-text", "" + pm[4]['account_code'] + ' - ' + pm[4]['account_name'] + "");                                                                         

                        $("select[id='payment_free']").append('' + '<option value="' + pm[5]['account_id'] + '">' + pm[5]['account_code'] + ' - ' + pm[5]['account_name'] + '</option>');
                        $("select[id='payment_free']").val(pm[5]['account_id']).trigger('change');
                        $("select[id='payment_free']").attr("data-account-map-id", +pm[5]['map_id']).attr("data-account-id", +pm[5]['account_id']).attr("data-account-text", "" + pm[5]['account_code'] + ' - ' + pm[5]['account_name'] + "");                                                                                                 
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });
        }
        loadAccountMap();
    });
</script>