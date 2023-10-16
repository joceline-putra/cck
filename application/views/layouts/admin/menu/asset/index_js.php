
<script>
    $(document).ready(function () {
        //Identity
        var identity = 0;
        var menu_link = "<?php echo $_view; ?>";

        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');

        //Url
        var url = "<?= base_url('asset'); ?>";
        var url_finance = "<?= base_url('keuangan/manage'); ?>";
        var url_print = "<?= base_url('order/prints'); ?>";
        var url_print_all = "<?= base_url('report/report_pembelian'); ?>";

        var url_image = '<?= base_url('upload/noimage.png'); ?>';

        let image_width = "<?= $image_width;?>";
        let image_height = "<?= $image_height;?>"; 

        // $("#img-preview1").attr('src', url_image);
        
        //Croppie
        var upload_crop_img = null;
        upload_crop_img = $('#modal-croppie-canvas').croppie({
            enableExif: true,
            viewport: {width: image_width, height: image_height},
            boundary: {width: parseInt(image_width)+10, height: parseInt(image_height)+10},
            url: url_image,
        });

        var asset_id = 0;

        // Start of Daterange
        var start_date = moment().startOf('month');
        var end_date = moment();

        //DatePicker
        $("#product_asset_acquisition_date").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('change', function (e) { });
        $("#product_asset_accumulated_depreciation_date").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('change', function (e) { });
        $("#start, #end").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('change', function () {
            index.ajax.reload();
        });

        //Autonumeric
        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        new AutoNumeric('#product_asset_acquisition_value', autoNumericOption);
        new AutoNumeric('#product_asset_dep_percentage', autoNumericOption);
        new AutoNumeric('#product_asset_accumulated_depreciation_value', autoNumericOption);
        // new AutoNumeric('#e_qty', autoNumericOption);  

        //Animate
        const btnNew = document.querySelector('#btn-new');
        const btnCancel = document.querySelector('#btn-cancel');

        var index = $("#table-data").DataTable({
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.tipe = identity;
                    // d.date_start = $("#start").val();
                    // d.date_end = $("#end").val();
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end'); 
                    d.length = $("#filter_length").find(':selected').val();
                    // d.kontak = $("#filter_kontak").find(':selected').val();     
                    d.search = {
                        value: $("#filter_search").val()
                    };
                    // d.user_role =  $("#select_role").val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": [4],
                }, {
                    "searchable": false,
                    "orderable": true,
                    "targets": [2, 3]
                }
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {
                    'data': 'product_asset_acquisition_date'
                }, {
                    'data': 'product_asset_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.product_asset_code + '<br>';
                        dsp += data;
                        return dsp;
                    }
                }, {
                    'data': 'fixed_account_name', className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.fixed_account_code + '<br>';
                        dsp += data;
                        return dsp;
                    }
                }, {
                    'data': 'product_asset_acquisition_value', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += addCommas(data);
                        return dsp;
                    }
                }, {
                    'data': 'product_asset_accumulated_depreciation_value', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += addCommas(data);
                        return dsp;
                    }
                }, {
                    'data': 'product_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';

                        dsp += '&nbsp;<button class="btn-sell btn btn-mini btn-info" data-id="' + data + '">';
                        dsp += '<span class="fas fa-cash-register"></span>Jual';
                        dsp += '</button>';

                        // dsp += '<button class="btn-print btn btn-mini btn-info" data-id="'+ data +'" data-number="'+row.order_number+'">';
                        // dsp += '<span class="fas fa-print"></span> Print';
                        // dsp += '</button>';          

                        dsp += '&nbsp;<button class="btn-history-depreciation btn btn-mini btn-success" data-id="' + data + '" data-name="' + row.product_asset_name + '">';
                        dsp += '<span class="fas fa-history"></span> Penyusutan';
                        dsp += '</button>';

                        dsp += '&nbsp;<button class="btn-delete btn btn-mini btn-danger" data-id="' + data + '" data-name="' + row.product_asset_name + '">';
                        dsp += '<span class="fas fa-trash"></span> Hapus';
                        dsp += '</button>';

                        // if (parseInt(row.flag) === 1) {
                        //   dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
                        //   dsp += 'data-nomor="'+row.trans_nomor+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.trans_flag+'">';
                        //   dsp += '<span class="fas fa-check-square primary"></span></button>';
                        // }else{ 
                        //   dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                        //   dsp += 'data-nama="'+row.nama+'" data-kode="'+row.kode+'" data-id="'+data+'" data-flag="'+row.flag+'">';
                        //   dsp += '<span class="fas fa-times danger"></span></button>';
                        // }

                        return dsp;
                    }
                }
            ]
        });

        var index_depreciation = $("#table-data-depreciation").DataTable({
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load-depreciation';
                    d.tipe = identity;
                    // d.date_start = $("#start").val();
                    // d.date_end = $("#end").val();
                    d.length = $("#filter_length").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                    d.asset_id = asset_id;
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"searchable": false, "orderable": false, "targets": [3]},
                {"searchable": false, "orderable": true, "targets": [2, 3]}
            ],
            "order": [
                [0, 'desc']
            ],
            "columns": [
                {
                    'data': 'journal_date_format'
                }, {
                    'data': 'journal_number',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.journal_number + '<br>';
                        return dsp;
                    }
                }, {
                    'data': 'journal_total', className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += addCommas(data);
                        return dsp;
                    }
                }, {
                    'data': 'journal_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        dsp += '<button class="btn-journal btn btn-mini btn-success" data-id="' + data + '">';
                        dsp += '<span class="fas fa-eye"></span> Jurnal Entri';
                        dsp += '</button>';

                        // dsp += '&nbsp;<button class="btn-edit btn btn-mini btn-primary" data-id="'+ data +'">';
                        // dsp += '<span class="fas fa-edit"></span> Edit';
                        // dsp += '</button>';

                        dsp += '&nbsp;<button class="btn-delete-depreciation btn btn-mini btn-danger" data-id="' + data + '" data-number="' + row.journal_number + '">';
                        dsp += '<span class="fas fa-trash"></span> Hapus';
                        dsp += '</button>';

                        return dsp;
                    }
                }
            ]
        });

        //Datatable Config
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                index.ajax.reload();
            }
        });
        $('#table-data').on('page.dt', function () {
            var info = index.page.info();
            // console.log( 'Showing page: '+info.page+' of '+info.pages);
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            console.log(limit_start, limit_end);
            $("#table-data").attr('data-limit-start', limit_start);
            $("#table-data").attr('data-limit-end', limit_end);
        });

        //Select2
        $('#product_asset_fixed_account_id').select2({
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
                        group: 1,
                        group_sub: 5,
                        source: 'accounts'
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
                    //return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                $(datas.element).attr('data-alamat', datas.alamat);
                $(datas.element).attr('data-telepon', datas.telepon);
                $(datas.element).attr('data-email', datas.email);
                return datas.text;
            }
        });
        $('#product_asset_cost_account_id').select2({
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
                        group: 1,
                        group_sub: 3,
                        source: 'accounts'
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
                    //return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);            
                return datas.text;
            }
        });
        $('#product_asset_depreciation_account_id').select2({
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
                        group: 5,
                        group_sub: 16,
                        source: 'accounts'
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
                    return '<i class="fas fa-plus ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);            
                return datas.text;
            }
        });
        $('#product_asset_accumulated_depreciation_account_id').select2({
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
                        group: 1,
                        group_sub: 7,
                        source: 'accounts'
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
                    //return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);            
                return datas.text;
            }
        });
        $('#filter_asset_fixed_account_id').select2({
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
                        group: 1,
                        group_sub: 5,
                        source: 'accounts'
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
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
                }
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                return datas.text;
            }
        });

        // $(document).on("change","#kontak",function(e) {
        //     e.preventDefault();
        //     e.stopPropagation();
        //     // console.log($(this));
        //     var this_val = $(this).find(':selected').val();
        //     if(this_val == '-'){
        //     $("#modal-contact").modal('toggle');
        //     formKontakNew();               
        //     }else{
        //     var id = $(this).find(':selected').val();
        //     var alamat = $(this).find(':selected').attr('data-alamat');
        //     $("#alamat").val(alamat);
        //     // alert(id);
        //     }
        // });
        // $(document).on("change","#filter_kontak",function(e) {
        //     index.ajax.reload();
        // });

        //Function Load
        //formTransNew();
        formTransSetDisplay(0);

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            e.stopPropagation();
            let next = true;

            if (next) {
                if ($("#product_asset_name").val().length === 0) {
                    next = false;
                    notifError('Nama wajib diisi');
                }
            }

            if (next) {
                if ($("#product_asset_fixed_account_id").find(':selected').val() === 0) {
                    next = false;
                    notifError('Akun Aset Tetap wajib dipilih');
                }
            }

            /* Prepare ajax for SAVE */
            /* If Form Validation Complete checked */
            if (next) {
                /* let form = new FormData(); */
                let form = new FormData($("#form-trans")[0]);
                form.append('action', 'create');
                form.append('product_asset_dep_flag', $(".checkbox-label").attr('data-flag'));
                form.append('upload1', $("#files_preview").attr('data-save-img'));                
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
                            index.ajax.reload();
                        } else {
                            // notif(s,m);
                            // notifSuccess(m);
                        }
                    },
                    error: function (xhr, status, err) {
                        // notif(0,err);
                        // notifError(err);
                    }
                });
            }
        });

        // Edit Button
        $(document).on("click", ".btn-edit", function (e) {
            // formMasterSetDisplay(0);
            $("#div-form-trans").show(300);
            // $(this).hide();    
            // $("#form-trans input[name='kode']").attr('readonly',true);

            e.preventDefault();
            var id = $(this).data("id");
            var data = {
                action: 'read',
                tipe: identity,
                product_id: id
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {},
                success: function (d) {
                    if (parseInt(d.status) == 1) { /* Success Message */
                        activeTab('tab1'); // Open/Close Tab By ID
                        $("#form-trans input[name='id_document']").val(d.result.product_id);
                        var dd1 = d.result.product_asset_accumulated_depreciation_date.substr(8, 2);
                        var mm1 = d.result.product_asset_accumulated_depreciation_date.substr(5, 2);
                        var yy1 = d.result.product_asset_accumulated_depreciation_date.substr(0, 4);

                        var dd2 = d.result.product_asset_acquisition_date.substr(8, 2);
                        var mm2 = d.result.product_asset_acquisition_date.substr(5, 2);
                        var yy2 = d.result.product_asset_acquisition_date.substr(0, 4);

                        var set_date1 = dd1 + '-' + mm1 + '-' + yy1;
                        var set_date2 = dd2 + '-' + mm2 + '-' + yy2;

                        $("#product_id").val(d.result.product_id);
                        $("#product_flag").val(d.result.product_flag);
                        $("#product_date_created").val(d.result.product_date_created);
                        $("#product_date_updated").val(d.result.product_date_updated);
                        $("#product_type").val(d.result.product_type);
                        $("#product_type_name").val(d.result.product_type_name);
                        $("#product_asset_name").val(d.result.product_asset_name);
                        $("#product_asset_code").val(d.result.product_asset_code);
                        $("#product_asset_note").val(d.result.product_asset_note);
                        $("#product_asset_acquisition_date").datepicker("update", set_date1);
                        $("#product_asset_acquisition_value").val(d.result.product_asset_acquisition_value);
                        ///$("#product_asset_dep_flag").val(d.result.product_asset_dep_flag);
                        $("#product_asset_dep_method").val(d.result.product_asset_dep_method);
                        $("#product_asset_dep_period").val(d.result.product_asset_dep_period);
                        $("#product_asset_dep_percentage").val(d.result.product_asset_dep_percentage);

                        $("#product_asset_accumulated_depreciation_value").val(d.result.product_asset_accumulated_depreciation_value);
                        $("#product_asset_accumulated_depreciation_date").datepicker("update", set_date2);

                        $("#product_asset_fixed_account_id").val(d.result.product_asset_fixed_account_id);
                        $("#product_asset_cost_account_id").val(d.result.product_asset_cost_account_id);
                        $("#product_asset_depreciation_account_id").val(d.result.product_asset_depreciation_account_id);
                        $("#product_asset_accumulated_depreciation_account_id").val(d.result.product_asset_accumulated_depreciation_account_id);

                        $("select[id='product_asset_fixed_account_id']").append('' + '<option value="' + d.result.product_asset_fixed_account_id + '">' + d.result.fixed_account_code + ' - ' + d.result.fixed_account_name + '</option>');
                        $("select[id='product_asset_fixed_account_id']").val(d.result.product_asset_fixed_account_id).trigger('change');

                        $("select[id='product_asset_cost_account_id']").append('' + '<option value="' + d.result.product_asset_cost_account_id + '">' + d.result.cost_account_code + ' - ' + d.result.cost_account_name + '</option>');
                        $("select[id='product_asset_cost_account_id']").val(d.result.product_asset_cost_account_id).trigger('change');

                        $("select[id='product_asset_depreciation_account_id']").append('' + '<option value="' + d.result.product_asset_depreciation_account_id + '">' + d.result.depreciation_account_code + ' - ' + d.result.depreciation_account_name + '</option>');
                        $("select[id='product_asset_depreciation_account_id']").val(d.result.product_asset_depreciation_account_id).trigger('change');

                        $("select[id='product_asset_accumulated_depreciation_account_id']").append('' + '<option value="' + d.result.product_asset_accumulated_depreciation_account_id + '">' + d.result.accumulated_account_code + ' - ' + d.result.accumulated_account_name + '</option>');
                        $("select[id='product_asset_accumulated_depreciation_account_id']").val(d.result.product_asset_accumulated_depreciation_account_id).trigger('change');

                        // $("#fixed_account_id").val(d.result.fixed_account_id);
                        // $("#fixed_account_code").val(d.result.fixed_account_code);
                        // $("#fixed_account_name").val(d.result.fixed_account_name);

                        // $("#cost_account_id").val(d.result.cost_account_id);
                        // $("#cost_account_code").val(d.result.cost_account_code);
                        // $("#cost_account_name").val(d.result.cost_account_name);

                        // $("#depreciation_account_id").val(d.result.depreciation_account_id);
                        // $("#depreciation_account_code").val(d.result.depreciation_account_code);
                        // $("#depreciation_account_name").val(d.result.depreciation_account_name);

                        // $("#accumulated_account_id").val(d.result.accumulated_account_id);
                        // $("#accumulated_account_code").val(d.result.accumulated_account_code);
                        // $("#accumulated_account_name").val(d.result.accumulated_account_name);


                        if (parseInt(d.result.product_image) == 0) {
                            $("#files_preview").attr('src',url_image);
                            $(".files_link").attr('href',url_image);                            
                        } else {
                            var image = "<?php echo base_url(); ?>" + d.result.product_image;
                            // $('#img-preview1').attr('src', image);
                            $("#files_preview").attr('src',image);
                            $(".files_link").attr('href',image);                            
                        }
                        checkBoxDepreciation(d.result.product_asset_dep_flag);

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-print").show();
                        $("#btn-cancel").show();

                        // $("#btn-update").attr('data-id',d.result.order_id);
                        // $("#btn-print").attr('data-id',d.result.order_id);
                        scrollUp('content');
                    } else {
                        notif(0, d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, 'Error');
                }
            });
        });

        // Sell Button
        $(document).on("click", ".btn-sell", function (e) {
            $.alert('.btn-sell');
            return;
            // formMasterSetDisplay(0);
            $("#div-form-trans").show(300);
            // $(this).hide();    
            // $("#form-trans input[name='kode']").attr('readonly',true);
            e.preventDefault();
            var id = $(this).data("id");
            var data = {
                action: 'read',
                tipe: identity,
                id: id
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {},
                success: function (d) {
                    if (parseInt(d.status) == 1) { /* Success Message */
                        activeTab('tab1'); // Open/Close Tab By ID
                        // notif(1,d.result.id);
                        $("#form-trans input[name='id_document']").val(d.result.order_id);
                        var dd = d.result.order_date.substr(8, 2);
                        var mm = d.result.order_date.substr(5, 2);
                        var yy = d.result.order_date.substr(0, 4);
                        var set_date = dd + '-' + mm + '-' + yy;
                        // $("#form-trans input[name='tgl']").val(set_date).trigger('changeDate');
                        // $("#form-trans input[name='tgl']").attr('data-value',set_date);
                        $("#form-trans input[name='tgl']").datepicker("update", set_date);
                        // alert(dd+'-'+mm+'-'+yy);
                        $("#form-trans input[name='nomor']").val(d.result.order_number);
                        $("#form-trans input[name='nomor_ref']").val(d.result.order_ref_number);
                        // $("#form-trans input[name='harga_beli']").val(d.result.harga_beli);
                        // $("#form-trans input[name='harga_jual']").val(d.result.harga_jual);
                        // $("#form-trans input[name='stok_minimal']").val(d.result.stok_minimal);
                        // $("#form-trans input[name='stok_maksimal']").val(d.result.stok_maksimal);          
                        $("textarea[id='keterangan']").val(d.result.order_note);
                        // $("#form-trans select[name='satuan']").val(d.result.satuan).trigger('change');
                        // $("#form-trans select[name='status']").val(d.result.flag).trigger('change');

                        $("select[name='kontak']").append('' +
                                '<option value="' + d.result.contact_id + '" data-alamat="' + d.result.contact_address + '" data-telepon="' + d.result.phone_1 + '" data-email="' + d.result.email_1 + '">' +
                                d.result.contact_name +
                                '</option>');
                        $("select[name='kontak']").val(d.result.contact_id).trigger('change');

                        // alert(dd+'-'+mm+'-'+yy);
                        // loadOrderItems(d.result.order_id);/

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-print").show();
                        $("#btn-cancel").show();

                        // $("#btn-update").attr('data-id',d.result.order_id);
                        // $("#btn-print").attr('data-id',d.result.order_id);
                        scrollUp('content');
                    } else {
                        notif(0, d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, 'Error');
                }
            });
        });

        // Update Button
        $(document).on("click", "#btn-update", function (e) {
            e.preventDefault();
            var next = true;
            var id = $("#form-trans input[name='id_document']").val();
            var kode = $("#form-trans input[name='kode']");
            var nama = $("#form-trans input[name='nama']");

            if ((id == '') || parseInt(id) == 0) {
                notif(0, 'Dokumen tidak ditemukan');
                next = false;
            }

            if (next == true) {
                if ($("select[id='kontak']").find(':selected').val() == 0) {
                    notif(0, 'Supplier harus dipilih dahulu');
                    next = false;
                }
            }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: id,
                    tgl: $("input[id='tgl']").val(),
                    kontak: $("select[id='kontak']").find(':selected').val(),
                    keterangan: $("#keterangan").val(),
                    upload1: $("#files_preview").attr('data-save-img')
                }
                var prepare_data = JSON.stringify(prepare);
                var data = {
                    action: 'update',
                    data: prepare_data
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    cache: false,
                    dataType: "json",
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            // $("#btn-new").show();
                            // $("#btn-save").hide();
                            // $("#btn-update").hide();
                            // $("#btn-cancel").hide();
                            // $("#form-trans input").val(); 
                            formTransSetDisplay(1);
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                        } else {
                            // notif(0,d.message);  
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });
            }
        });

        // Delete Button
        $(document).on("click", ".btn-delete", function () {
            event.preventDefault();
            var id = $(this).attr("data-id");
            var number = $(this).attr("data-name");

            /*
             $.confirm({
             title: 'Hapus!',
             content: 'Apakah anda ingin menghapus <b>'+number+'</b> ?',
             buttons: {
             confirm:{ 
             btnClass: 'btn-danger',
             text: 'Ya',
             action: function () {
             var data = {
             action: 'delete',
             id:id,
             number: number,
             tipe: identity
             }
             $.ajax({
             type: "POST",     
             url : url,
             data: data,
             dataType:'json',
             success:function(d){
             if(parseInt(d.status)==1){ 
             notif(1,d.message); 
             index.ajax.reload();
             }else{ 
             notif(0,d.message); 
             }
             }
             });
             }
             },
             cancel:{
             btnClass: 'btn-success',
             text: 'Batal', 
             action: function () {
             // $.alert('Canceled!');
             }
             }
             }
             }); 
             */

            $.confirm({
                title: 'Hapus Asset Ini!',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'read',
                        id: id,
                        number: number,
                        tipe: identity
                    }
                    return $.ajax({
                        url: url,
                        data: data,
                        dataType: 'json',
                        method: 'post',
                        cache: false
                    }).done(function (d) {

                        // self.setTitle('Your Title After');
                        // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return

                        // Total Record dan Each Prepare  
                        if (d.total_records > 0) {

                            // table tag
                            var head = '';
                            head += '<table class="table table-bordered table-striped"><tbody>';
                            head += '<tr>';
                            head += '<td style="padding:4px 0px!important;text-align:center"><b>Stok</b>&nbsp;</td>';
                            head += '<td style="padding:4px 0px!important;text-align:center">&nbsp;<b>Gudang</b></td>';
                            head += '<td style="padding:4px 0px!important;text-align:center">&nbsp;<b>Riwayat</b></td>';
                            head += '</tr>';

                            // table body
                            var body = '';

                            // end tag table
                            var end = '</tbody></table>';

                            $.each(d.result, function (i, val) {

                                // table body
                                body += '<tr>' +
                                        '<td style="padding:4px 0px!important;text-align:right;"><b>' + val.qty_akhir + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;">&nbsp;<b>' + val.gudang_kode + ' - ' + val.gudang_nama + '</b>&nbsp;</td>' +
                                        '<td style="text-align:center;">' +
                                        '<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"' +
                                        ' data-id-barang=' + val.id_barang + '' +
                                        ' data-id-lokasi=' + val.id_gudang + '' +
                                        '>' +
                                        'Kartu Stok</button></td>' +
                                        '</tr>';

                            });

                            // table structure
                            var table = head + body + end;
                            // content        
                            self.setContent(table);
                        }
                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    this.setContentAppend('<div>Apakah anda ingin menghapus <b>' + number + '</b> ?</div>');
                },
                buttons: {
                    button_1: {
                        text: 'Hapus',
                        btnClass: 'btn-default',
                        keys: ['enter'],
                        action: function () {
                            var data = {
                                action: 'delete',
                                id: id,
                                number: number,
                                tipe: identity
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        index.ajax.reload(null, false);
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    button_2: {
                        text: 'Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            // Close 
                        }
                    }
                }
            });

        });
        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formTransNew();
            // $("#div-form-trans").show(300);
            $("#div-form-trans").show(300);
            $(this).hide();
            // animateCSS('#btn-new', 'backOutLeft','true');

            // btnNew.classList.add('animate__animated', 'animate__fadeOutRight');
        });
        // Cancel Button
        $(document).on("click", "#btn-cancel", function (e) {
            e.preventDefault();
            formTransCancel();
            // btnNew.classList.remove('animate__animated', 'animate__fadeOutRight');    
            $("#div-form-trans").hide(300);
            formTransDepSetDisplay(1);
        });

        // Print Button
        $(document).on("click", "#btn-print", function () {
            // var id = $(this).attr("data-id");
            var id = $("#form-trans input[id='id_document']").val();
            if (parseInt(id) > 0) {
                var x = screen.width / 2 - 700 / 2;
                var y = screen.height / 2 - 450 / 2;
                var print_url = url_print + '/' + id;
                // console.log(print_url);
                var win = window.open(print_url, 'Print Pembelian', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
                var data = id;
                // $.post(url_print, {id:data}, function (data) {
                //     var w = window.open(print_url,'Print');
                //     w.document.open();
                //     w.document.write(data);
                //     w.document.close();
                // });
            } else {
                notif(0, 'Dokumen belum di buka');
            }
        });
        $(document).on("click", ".btn-print-all", function () {
            var id = $(this).attr("data-id");
            var action = $(this).attr('data-action');
            var request = $(this).attr('data-request');
            //alert('#btn-print-all on Click'+action+','+request);
            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            // var print_url = url_print +'/'+ action + '/' +request+ '/' + $("#start").val() + '/' + $("#end").val();
            // window.open(print_url,'_blank');
            var request = $('.btn-print-all').data('request');
            var print_url = url_print_all + '/' + $("#start").val() + '/' + $("#end").val();
            var win = window.open(print_url, 'Print', 'width=700,height=485,left=' + x + ',top=' + y + '').print();
        });

        // Set Flag Button
        $(document).on("click", ".btn-set-active", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var flag = $(this).attr("data-flag");
            if (flag == 1) {
                var set_flag = 0;
                var msg = 'nonaktifkan';
            } else {
                var set_flag = 1;
                var msg = 'aktifkan';
            }
            var kode = $(this).attr("data-kode");
            var nama = $(this).attr("data-nama");
            $.confirm({
                title: 'Set Status',
                content: 'Apakah anda ingin <b>' + msg + '</b> dengan nama <b>' + nama + '</b> ?',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                buttons: {
                    button_1: {
                        text: 'Ok',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function () {
                            var data = {
                                action: 'set-active',
                                id: id,
                                flag: set_flag,
                                nama: nama,
                                kode: kode,
                                tipe: identity
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                cache: false,
                                beforeSend: function () {},
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        index.ajax.reload();
                                    } else {
                                        notif(0, d.message);
                                    }
                                },
                                error: function (xhr, Status, err) {
                                    notif(0, 'Error');
                                }
                            });
                        }
                    },
                    button_2: {
                        text: 'Batal',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
                        }
                    }
                }
            });
        });

        $(document).on("click", "#btn-preview", function (e) {
            index.ajax.reload();
        });

        $(document).on("click", ".checkbox-label", function (e) {
            var check = $(".checkbox-label").attr('data-flag');
            if (check == 0) {
                checkBoxDepreciation(0);
            } else {
                checkBoxDepreciation(1);
            }
        });
        $(document).on("click", ".btn-journal", function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var date = new Date();
            // var start_date = '01-'+date.getMonth()+'-'+date.getFullYear();
            // var end_date   = date.getDate()+'-'+date.getMonth()+'-'+date.getFullYear();
            var start_date = $("#start").val();
            var end_date = $("#end").val();

            var url_ledger = '<?= base_url('report/report_finance_ledger/'); ?>' + start_date + '/' + end_date + '/';
            var order = '?format=html&order=journal_item_date&dir=asc';

            $.confirm({
                title: 'Jurnal Entri',
                columnClass: 'col-md-8 col-md-offset-2 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'journal',
                        id: id,
                        tipe: identity
                    }
                    return $.ajax({
                        url: url_finance,
                        data: data,
                        dataType: 'json',
                        method: 'post',
                        cache: false
                    }).done(function (d) {

                        // self.setTitle('Your Title After');
                        // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return

                        // Total Record dan Each Prepare  
                        if (d.status > 0) {

                            // table tag
                            var head = '';
                            head += '<table class="table table-bordered table-striped"><tbody>';
                            head += '<tr>';
                            head += '<td style="padding:4px 0px!important;text-align:left"><b>Tanggal</b>&nbsp;</td>';
                            head += '<td style="padding:4px 0px!important;text-align:left"><b>Akun</b>&nbsp;</td>';
                            head += '<td style="padding:4px 0px!important;text-align:left"><b>Nama</b>&nbsp;</td>';
                            head += '<td style="padding:4px 0px!important;text-align:left"><b>Keterangan</b>&nbsp;</td>';
                            head += '<td style="padding:4px 0px!important;text-align:right">&nbsp;<b>Debit</b></td>';
                            head += '<td style="padding:4px 0px!important;text-align:right">&nbsp;<b>Kredit</b></td>';
                            head += '<td class="hide" style="padding:4px 0px!important;text-align:left">&nbsp;<b>Action</b></td>';
                            head += '</tr>';

                            // table body
                            var body = '';

                            // end tag table
                            var end = '</tbody></table>';
                            var total_debit = 0;
                            var total_credit = 0;
                            $.each(d.result, function (i, val) {
                                var account_code = '<a href="' + url_ledger + val.account_id + order + '" target="_blank">' + val.account_code + '</a>';
                                var account_name = '<a href="' + url_ledger + val.account_id + order + '" target="_blank">' + val.account_name + '</a>';
                                // table body
                                body += '<tr>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_date + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + account_code + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + account_name + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:left;">' + val.journal_item_note + '</b>&nbsp;<span class="hide fa fa-arrow-right"></td>' +
                                        '<td style="padding:4px 0px!important;text-align:right;">&nbsp;' + addCommas(val.journal_item_debit) + '&nbsp;</td>' +
                                        '<td style="padding:4px 0px!important;text-align:right;">&nbsp;' + addCommas(val.journal_item_credit) + '&nbsp;</td>' +
                                        '<td class="hide" style="text-align:center;">' +
                                        '<button class="btn btn-primary btn-mini btn-riwayat-kartu-stok"' +
                                        ' data-id-barang=' + val.id_barang + '' +
                                        ' data-id-lokasi=' + val.id_gudang + '' +
                                        '>' +
                                        'Kartu Stok</button></td>' +
                                        '</tr>';
                                total_debit = parseFloat(total_debit) + parseFloat(val.journal_item_debit);
                                total_credit = parseFloat(total_credit) + parseFloat(val.journal_item_credit);
                            });
                            body += '<tr><td colspan="4" style="padding:4px 0px!important;">&nbsp;<b>Total</b></td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>' + addCommas(total_debit) + '</b>&nbsp;</td><td style="padding:4px 0px!important;text-align:right">&nbsp;<b>' + addCommas(total_credit) + '</b>&nbsp;</td></tr>';
                            // table structure
                            var table = head + body + end;
                            // content        
                            self.setContent(table);
                        }
                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    // this.setContentAppend('<div>Apakah anda ingin menghapus <b>'+number+'</b> ?</div>');
                },
                buttons: {
                    button_2: {
                        text: 'Tutup',
                        btnClass: 'btn-default',
                        keys: ['Escape'],
                        action: function () {
                            // Close 
                        }
                    }
                }
            });
        });
        $(document).on("click", ".btn-history-depreciation", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var id = $(this).attr('data-id');
            // var name = $(this).attr('data-name');        
            // alert('btn-deprectiation '+name);
            asset_id = id;
            index_depreciation.ajax.reload();
            $("#modal-depreciation").modal('show');
        });
        $(document).on("click", ".btn-delete-depreciation", function () {
            event.preventDefault();
            var id = $(this).attr("data-id");
            var number = $(this).attr("data-number");

            $.confirm({
                title: 'Hapus Penyusutan!',
                content: 'Apakah anda ingin menghapus <b>' + number + '</b> ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'delete-depreciation',
                                id: id,
                                number: number,
                                tipe: 17
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        index_depreciation.ajax.reload();
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        btnClass: 'btn-success',
                        text: 'Batal',
                        action: function () {
                            // $.alert('Canceled!');
                        }
                    }
                }
            });

            $.confirm({
                title: 'Hapus Asset Ini!',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'read',
                        id: id,
                        number: number,
                        tipe: identity
                    }
                    return $.ajax({
                        url: url,
                        data: data,
                        dataType: 'json',
                        method: 'post',
                        cache: false
                    }).done(function (d) {

                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    this.setContentAppend('<div>Apakah anda ingin menghapus <b>' + number + '</b> ?</div>');
                },
                buttons: {
                    button_1: {
                        text: 'Hapus',
                        btnClass: 'btn-default',
                        keys: ['enter'],
                        action: function () {
                            var data = {
                                action: 'delete',
                                id: id,
                                number: number,
                                tipe: identity
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        index.ajax.reload(null, false);
                                    } else {
                                        notif(0, d.message);
                                    }
                                }
                            });
                        }
                    },
                    button_2: {
                        text: 'Tidak Jadi',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            // Close 
                        }
                    }
                }
            });

        });
        $('#upload1').change(function (e) {
            var fileName = e.target.files[0].name;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview1').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });
        function addCommas(string) {
            string += '';
            var x = string.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
        function removeCommas(string) {

            return string.split(',').join("");
        }
        function formTransNew() {
            formTransSetDisplay(0);

            // $("#form-trans input[id='id_document']").val(0);    
            if (parseInt($("#id_document").val()) == 0) {
                $("#form-trans input").not("input[id='tipe']")
                        .not("input[id='tgl']")
                        .not("input[id='product_asset_acquisition_value']")
                        .not("input[id='product_asset_dep_period']")
                        .not("input[id='product_asset_dep_percentage']")
                        .not("input[id='product_asset_accumulated_depreciation_value']")
                        .val('');
                $("#form-trans select").not("select[id='product_asset_dep_method']").val(0).trigger('change');
                $("#form-trans textarea").val('');
                $("#product_asset_acquisition_value").val(0);
                $("#product_asset_dep_period").val(0);
                $("#product_asset_dep_percentage").val(0);
                $("#product_asset_accumulated_depreciation_value").val(0);
                checkBoxDepreciation(0);

                $("#product_asset_acquisition_date").datepicker("update", end_date.format('DD-MM-YYYY'));
                $("#product_asset_accumulated_depreciation_date").datepicker("update", end_date.format('DD-MM-YYYY'));
                $("#img-preview1").attr('src', url_image);
            }
            $("#btn-new").hide();
            $("#btn-save").show();
            $("#btn-cancel").show();
        }
        function formTransCancel() {
            formTransSetDisplay(1);
            $("#form-trans input").not("input[id='tipe']")
                    .not("input[id='tgl']")
                    .not("input[id='product_asset_acquisition_value']")
                    .not("input[id='product_asset_dep_period']")
                    .not("input[id='product_asset_dep_percentage']")
                    .not("input[id='product_asset_accumulated_depreciation_value']")
                    .val('');
            $("#form-trans input[id='id_document']").val(0);
            $("#form-trans select").not("select[id='product_asset_dep_method']").val(0).trigger('change');
            $("#form-trans textarea").val('');
            $("#product_asset_acquisition_value").val(0);
            $("#product_asset_dep_period").val(0);
            $("#product_asset_dep_percentage").val(0);
            $("#product_asset_accumulated_depreciation_value").val(0);
            checkBoxDepreciation(0);
            $("#btn-new").show();
            $("#btn-save").show();
            $("#btn-print").hide();
            $("#btn-update").hide();
        }
        //Image Croppie
        $(document).on('change', '#files', function(e) {
            if($("#files").val() == ''){
                $("#files_preview").attr('src', url_image);
                $("#files_link").attr('href', url_image);            
                $("#files_preview").attr('data-save-img', '');
                return;
            }
            var reader = new FileReader();
            reader.onload = function(e) {
                upload_crop_img.croppie('bind', {
                    url: e.target.result
                }).then(function () {
                    $("#modal-croppie").modal("show");
                    setTimeout(function(){$('#modal-croppie-canvas').croppie('bind');}, 300);
                });
            };
            reader.readAsDataURL(this.files[0]);
        });
        $(document).on('click', '#modal-croppie-cancel', function(e){
            e.preventDefault();
            e.stopPropagation();
            $("#files").val('');
            $("#files_preview").attr('data-save-img', '');
            $("#files_preview").attr('src', url_image);
            $("#files_link").attr('href', url_image);
        });
        $(document).on('click', '#modal-croppie-save', function(e){
            e.preventDefault();
            e.stopPropagation();
            upload_crop_img.croppie('result', {
                type: 'canvas',
                size: 'viewport',
            }).then(function (resp) {
                $("#files_preview").attr('src', resp);
                $("#files_link").attr('href', resp);
                $("#files_preview").attr('data-save-img', resp);
                $("#modal-croppie").modal("hide");
            });
        }); 
    });
    function checkBoxDepreciation(flag) {
        if (flag == 0) {
            $("#product_asset_dep_flag").prop("checked", true);
            $(".checkbox-label").attr("data-flag", 1);
            formTransDepSetDisplay(1);
            $("#asset-checkbox-label").html('Asset non-depresiasi');
        } else {
            $("#product_asset_dep_flag").prop("checked", false);
            $(".checkbox-label").attr("data-flag", 0);
            formTransDepSetDisplay(0);
            $("#asset-checkbox-label").html('Asset depresiasi');
        }
    }
    function formTransSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
        //Attr Input yang perlu di setel
        var form = '#form-trans';
        var attrInput = [
            "product_asset_code", "product_asset_name",
            "product_asset_acquisition_value"
        ];
        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            "product_asset_note"
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel 
        var atributSelect = [
            "product_asset_fixed_account_id",
            "product_asset_cost_account_id",
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
    function formTransDepSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
        //Attr Input yang perlu di setel
        var form = '#form-trans';
        var attrInput = [
            "product_asset_dep_period", "product_asset_dep_percentage", "product_asset_accumulated_depreciation_value"
        ];
        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            // "product_asset_note"
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel 
        var atributSelect = [
            "product_asset_dep_method", "product_asset_depreciation_account_id", "product_asset_accumulated_depreciation_account_id"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>