
<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="product/room"]').addClass('active');

        //Nav Tabs Resep & Price    
        // $(".nav-tabs-detail").find('li[class="active"]').removeClass('active');
        // $(".nav-tabs-detail").find('li[data-name="tab_price"]').addClass('active');
        activeTabDetail('tab_price');

        // console.log(menu_link);
        var url = "<?= base_url('produk/manage'); ?>";
        var url_print_all = "<?= base_url('product/print'); ?>";        
        var url_image = '<?= base_url('upload/noimage.png'); ?>';

        let image_width = "<?= $image_width;?>";
        let image_height = "<?= $image_height;?>";            
        
        var alias = "<?php echo $title; ?>";
        // $("#img-preview1").attr('src', url_image);

        //Croppie
        var upload_crop_img = null;
        upload_crop_img = $('#modal-croppie-canvas').croppie({
            enableExif: true,
            viewport: {width: image_width, height: image_height},
            boundary: {width: parseInt(image_width)+10, height: parseInt(image_height)+10},
            url: url_image,
        });
        
        // $("select").select2();
        $(".date").datepicker({
            // defaultDate: new Date(),
            format: 'yyyy-mm-dd',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        });
        // const autoNumericOption = {
        //     digitGroupSeparator: ',',
        //     decimalCharacter: '.',
        //     decimalCharacterAlternative: '.',
        //     decimalPlaces: 2,
        //     watchExternalChanges: true //!!!        
        // };
        // const autoNumericOption2 = {
        //     digitGroupSeparator: ',',
        //     decimalCharacter: '.',
        //     decimalCharacterAlternative: '.',
        //     decimalPlaces: 4,
        //     watchExternalChanges: true //!!!        
        // };
        // const autoNumericOption3 = {
        //     digitGroupSeparator: ',',
        //     decimalCharacter: '.',
        //     decimalCharacterAlternative: '.',
        //     decimalPlaces: 0,
        //     watchExternalChanges: true //!!!        
        // };
        // new AutoNumeric('#harga_jual', autoNumericOption);
        // new AutoNumeric('#harga_beli', autoNumericOption);
        // new AutoNumeric('#harga_promo', autoNumericOption);

        // new AutoNumeric('#stok_minimal', autoNumericOption3);
        // new AutoNumeric('#stok_maksimal', autoNumericOption);
        // new AutoNumeric('#recipe-qty', autoNumericOption2);
        // new AutoNumeric('#product_price_price', autoNumericOption);

        // var start = 0;
        // var length = $("#filter_length").find(':selected').val();
        var index = $("#table-data").DataTable({
            // "processing": true,
            // "rowReorder": { selector: 'td:nth-child(1)'},
            // "responsive": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.tipe = identity;
                    d.filter_type = 2;                    
                    d.filter_categories = 2;
                    d.filter_branch = $("#filter_branch").find(':selected').val();
                    d.filter_ref = $("#filter_ref").find(':selected').val();
                    d.filter_flag = $("#filter_flag").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Nama", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Kode", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Cabang", "searchable": false, "orderable": true, "className": "text-left"},
                {"targets": 3, "title": "Jenis Kamar", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [1, 'asc']
            ],
            "columns": [
                {
                    'data': 'product_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // var cat = !(row.category_name) ? 0 : 1;

                        // dsp += '<b>' + row.product_name + '</b><br>';
                        // if (row.product_type == 1) {
                        //     dsp += '<span class="label" style="color:white;background-color:#7c98f5;padding:1px 4px;">Barang</span>&nbsp;';
                        // }else if (row.product_type == 2) {
                        //     dsp += '<span class="label" style="color:white;background-color:#b87cf5;padding:1px 4px;">Jasa</span>&nbsp;';
                        // }

                        // if (parseInt(cat) > 0) {
                        //     dsp += '<span class="label" style="color:white;background-color:#a4a3a5;padding:1px 4px;">' + row.category_name + '</span>';
                        // }
                        dsp += row.product_name;
                        /*
                         if(parseFloat(row.product_price_promo) > 0){
                         dsp += '&nbsp;<label class="label label-purple">Promo</label>';
                         }
                         if(parseInt(row.product_with_stock) > 0){
                         dsp += '&nbsp;<span class="label">Proteksi Stok</label>';
                         }
                         if(row.product_image == undefined){
                         }else{ dsp += '&nbsp;<i class="fas fa-camera"></i>'; }
                         */

                        return dsp;
                    }
                }, {
                    'data': 'product_code',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += !(data) ? '-' : data;
                        return dsp;
                    }
                }, {
                    'data': 'branch_name',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.branch_name;
                        return dsp;
                    }
                }, {
                    'data': 'ref_name',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.ref_name;
                        return dsp;
                    }
                }, {
                    'data': 'product_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';

                        if (parseInt(row.product_flag) === 1) {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            dsp += 'data-nama="' + row.product_name + '" data-kode="' + row.product_code + '" data-id="' + data + '" data-flag="' + row.product_flag + '">';
                            dsp += '<span class="fas fa-check-square primary"></span> Aktif</button>';
                        } else {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.product_name + '" data-kode="' + row.product_code + '" data-id="' + data + '" data-flag="' + row.product_flag + '">';
                            dsp += '<span class="fas fa-times danger"></span> Nonaktif</button>';
                        }

                        return dsp;
                    }
                }],
            "initComplete": function (settings, json) {
                var info = index.page.info();
                // start = info.start;
                // length = info.length;
                // console.log("Start: "+start+", Length: "+info.length+", End: "+info.end);
                // console.log('Init: '+start+', '+length);
            }
        });
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            var value = $(this).find(':selected').val();
            $('select[name="table-data_length"]').val(value).trigger('change');
            index.ajax.reload();
        });
        $("#filter_flag").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                index.ajax.reload();
            }
        });
        $("#filter_branch").on('change', function (e) {
            index.ajax.reload();
        });         
        $("#filter_ref").on('change', function (e) {
            index.ajax.reload();
        });  

        $('#satuan').select2({
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
                        source: 'units'
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
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    // return datas.text;          
                } else {
                    // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
                }
                return datas.text;
            },
            templateSelection: function (datas) { //When Option on Click
                // if (!datas.id) { 
                return datas.text;
                // }
                //Custom Data Attribute         
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
            }
        });
        $('#categories').select2({
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
                        tipe: 1, //1=Produk, 2=News
                        source: 'categories',
                        search: 'Kamar'                        
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
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-custom-attribute', data.customValue);
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#referensi').select2({
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
                        tipe: 7, //1=Produk, 2=News
                        source: 'references'
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
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-custom-attribute', data.customValue);
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#account_buy').select2({
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
                        source: 'accounts',
                        // group:5,
                        group_sub: 15
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
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
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
                //Custom Data Attribute         
                return datas.text;
            }
        });
        $('#account_sell').select2({
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
                        source: 'accounts',
                        // group:4,
                        group_sub: 13
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
                // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
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
                //Custom Data Attribute         
                return datas.text;
            }
        });
        $('#product_branch_id').select2({
            placeholder: '--- Semua ---',
            allowClear:true,
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'branchs'
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
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-custom-attribute', data.customValue);
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });        
        $('#filter_branch').select2({
            placeholder: '--- Semua ---',
            allowClear:true,
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'branchs'
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
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-custom-attribute', data.customValue);
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#filter_ref').select2({
            placeholder: '--- Semua ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 10, //1=Produk, 2=News
                        source: 'references'
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
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-custom-attribute', data.customValue);
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#recipe-goods').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            // placeholder: '<i class="fas fa-boxes"></i> Search',
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
                        category: 1,
                        source: 'products'
                    }
                    return query;
                },
                processResults: function (datas, params) {
                    params.page = params.page || 1;
                    return {
                        results: datas,
                        pagination: {
                            more: (params.page * 10) < datas.count_filtered
                        }
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
                return datas.text;
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                // return '<i class="fas fa-boxes '+datas.id.toLowerCase()+'"></i> '+datas.text;
                $(datas.element).attr('data-unit', datas.satuan);
                return datas.text;
            }
        });
        $(document).on("change", "#recipe-goods", function (e) {
            var unit = $(this).find(':selected').attr('data-unit');
            $("#recipe-unit").val(unit);
        });
        $(document).on("change", "#checkbox_buy", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).prop('checked');
            if ($(this).prop('checked') == true) {
                $("#account_buy").removeAttr('disabled');
                $("#harga_beli").removeAttr('readonly');
            } else {
                $("#account_buy").attr('disabled', true);
                $("#harga_beli").attr('readonly', true);
            }
        });
        $(document).on("change", "#checkbox_sell", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).prop('checked');
            if ($(this).prop('checked') == true) {
                $("#account_sell").removeAttr('disabled');
                $("#harga_jual").removeAttr('readonly');
            } else {
                $("#account_sell").attr('disabled', true);
                $("#harga_jual").attr('readonly', true);
            }
        });
        $(document).on("change", "#checkbox_inventory", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).prop('checked');
            if ($(this).prop('checked') == true) {
                $("#account_inventory").removeAttr('disabled');
                $("#with_stock").removeAttr('disabled');
                $("#stok_minimal").removeAttr('readonly');
            } else {
                $("#account_inventory").attr('disabled', true);
                $("#with_stock").attr('disabled', true);
                $("#stok_minimal").attr('readonly', true);
            }
        });
        $(document).on("click", "#btn-new", function (e) {
            formNew();
            // $("#div-form-trans").show(300);
            $("#div-form-trans").show(300);
            $(this).hide();
        });
        $(document).on("click", "#btn-cancel", function (e) {
            formCancel();
            // $("#div-form-trans").hide(300);
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            // if(next==true){    
            //   if($("input[id='kode']").val().length == 0){
            //     notif(0,'Kode wajib diisi');
            //     $("#kode").focus();
            //     next=false;
            //   }
            // }

            // if(next==true){
            //   if($("select[id='categories']").find(':selected').val() == 0){
            //     notif(0,'Kategori wajib dipilih');
            //     next=false;
            //   }   
            // }        

            if (next == true) {
                if ($("input[id='nama']").val().length == 0) {
                    notif(0, 'Nama wajib diisi');
                    $("#nama").focus();
                    next = false;
                }
            }
            if (next == true) {
                if ($("select[id='product_manufacture']").find(':selected').val() == 0) {
                    notif(0, 'Jenis produk wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='satuan']").find(':selected').val() == 0) {
                    notif(0, 'Satuan wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='harga_jual']").val().length == 0) {
                    notif(0, 'Harga Jual wajib diisi');
                    $("#harga_jual").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_buy']").find(':selected').val() == 0) {
                    notif(0, 'Akun Pembelian harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_sell']").find(':selected').val() == 0) {
                    notif(0, 'Akun Penjualan harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                /*var prepare = {
                 tipe: identity,
                 kode: $("input[id='kode']").val(),
                 nama: $("input[id='nama']").val(),
                 keterangan: $("textarea[id='keterangan']").val(),        
                 harga_beli: $("input[id='harga_beli']").val(),
                 harga_jual: $("input[id='harga_jual']").val(),
                 stok_minimal: $("input[id='stok_minimal']").val(),
                 stok_maksimal: $("input[id='stok_maksimal']").val(),        
                 satuan: $("select[id='satuan']").find(':selected').val(),
                 status: $("select[id='status']").find(':selected').val()
                 }
                 var prepare_data = JSON.stringify(prepare);
                 var data = {
                 action: 'create',
                 data: prepare_data
                 };*/
                var formData = new FormData();
                formData.append('action', 'create');
                // formData.append('upload1', $('#upload1')[0].files[0]);
                formData.append('tipe', 2);
                formData.append('kode', $('#kode').val());
                formData.append('nama', $('#nama').val());
                formData.append('keterangan', $('#keterangan').val());
                // formData.append('harga_beli', $('#harga_beli').val());
                // formData.append('harga_jual', $('#harga_jual').val());
                // formData.append('harga_promo', $('#harga_promo').val());
                // formData.append('stok_minimal', $('#stok_minimal').val());
                // formData.append('stok_maksmal', $('#stok_maksimal').val());
                formData.append('satuan', $('#satuan').find(':selected').val());
                formData.append('status', $('#status').find(':selected').val());
                // formData.append('with_stock', $('#with_stock').find(':selected').val());
                formData.append('categories', $('#categories').find(':selected').val());
                // formData.append('manufacture', $('#manufacture').find(':selected').val());
                formData.append('referensi', $('#referensi').find(':selected').val());
                // formData.append('akun_beli', $('#account_buy').find(':selected').val());
                // formData.append('akun_jual', $('#account_sell').find(':selected').val());
                // formData.append('akun_inventory', $('#account_inventory').find(':selected').val());
                formData.append('upload1', $("#files_preview").attr('data-save-img'));
                formData.append('product_branch_id', $('#product_branch_id').find(':selected').val());                
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $("#btn-save").attr('disabled',true);
                        notif(1,'Sedang menambahkan');
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            index.ajax.reload();
                        } else { //Error
                            notif(0, d.message);
                        }
                        $("#btn-save").attr('disabled',false);
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });
            }
        });

        // Edit Button
        $(document).on("click", ".btn-edit", function (e) {
            formMasterSetDisplay(0);
            // $("#form-master input[name='kode']").attr('readonly',true);
            $("#div-form-trans").show(300);
            e.preventDefault();
            var id = $(this).data("id");
            var data = {
                action: 'read',
                id: id,
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
                    if (parseInt(d.status) == 1) { /* Success Message */
                        activeTab('tab1'); // Open/Close Tab By ID
                        // notifSuccess(d.result.id);ss
                        $("#form-master select[id='product_type']").val(d.result.product_type).trigger('change');
                        $("#form-master input[name='id_document']").val(d.result.product_id);
                        $("#form-master input[name='kode']").val(d.result.product_code);
                        $("#form-master input[name='nama']").val(d.result.product_name);
                        $("#form-master input[name='keterangan']").val(d.result.product_note);
                        $("#form-master input[name='harga_beli']").val(d.result.product_price_buy);
                        $("#form-master input[name='harga_jual']").val(d.result.product_price_sell);
                        $("#form-master input[name='harga_promo']").val(d.result.product_price_promo);
                        $("#form-master input[name='stok_minimal']").val(d.result.product_min_stock_limit);
                        $("#form-master input[name='stok_maksimal']").val(d.result.product_max_stock_limit);
                        // $("#form-master input[name='manufacture']").val(d.result.product_manufacture);                    
                        $("#form-master textarea[name='keterangan']").val(d.result.product_note);
                        $("#form-master select[name='status']").val(d.result.product_flag).trigger('change');
                        $("#form-master select[name='with_stock']").val(d.result.product_with_stock).trigger('change');
                        $("#form-master select[name='manufacture']").val(d.result.product_manufacture).trigger('change');

                        // $("#form-master select[name='satuan']").val(d.result.product_unit).trigger('change');
                        $("select[id='satuan']").append('' +
                                '<option value="' + d.result.product_unit + '">' +
                                d.result.product_unit +
                                '</option>');
                        $("select[id='satuan']").val(d.result.product_unit).trigger('change');

                        $("select[name='categories']").append('' +
                                '<option value="' + d.result.category_id + '">' +
                                d.result.category_name +
                                '</option>');
                        $("select[name='categories']").val(d.result.category_id).trigger('change');

                        $("select[name='referensi']").append('' +
                                '<option value="' + d.result.ref_id + '">' +
                                d.result.ref_name +
                                '</option>');
                        $("select[name='referensi']").val(d.result.ref_id).trigger('change');

                        $("select[name='account_buy']").append('' +
                                '<option value="' + d.result.buy_account_id + '">' +
                                d.result.buy_account_code + ' - ' + d.result.buy_account_name +
                                '</option>');
                        $("select[name='account_buy']").val(d.result.buy_account_id).trigger('change');

                        $("select[name='account_sell']").append('' +
                                '<option value="' + d.result.sell_account_id + '">' +
                                d.result.sell_account_code + ' - ' + d.result.sell_account_name +
                                '</option>');
                        $("select[name='account_sell']").val(d.result.sell_account_id).trigger('change');

                        $("select[name='account_inventory']").append('' +
                                '<option value="' + d.result.inventory_account_id + '">' +
                                d.result.inventory_account_code + ' - ' + d.result.inventory_account_name +
                                '</option>');
                        $("select[name='account_inventory']").val(d.result.inventory_account_id).trigger('change');

                        if (parseInt(d.result.product_images) == 0) {
                            $("#files_preview").attr('src',url_image);
                            $(".files_link").attr('href',url_image);                            
                        } else {
                            var image = "<?php echo base_url(); ?>" + d.result.product_image;
                            // $('#img-preview1').attr('src', image);
                            $("#files_preview").attr('src',image);
                            $(".files_link").attr('href',image);                            
                        }
                        // $("#files_preview").attr('src',d.result.product_images);
                        // $(".files_link").attr('href',d.result.product_images);
                        
                        //Varian Harga
                        $("#b_price_label").html(' Daftar Varian Harga Jual ' + d.result.product_name);
                        loadProductRecipe(d.result.product_id);
                        loadProductPrice(d.result.product_id);

                        $("#btn-new").hide();
                        $("#btn-save").hide();
                        $("#btn-update").show();
                        $("#btn-cancel").show();
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
            var id = $("#form-master input[name='id_dokumen']").val();
            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            // if(kode.val().length == 0){
            //   notif(0,'Kode wajib diisi');
            //   kode.focus();
            //   next=false;
            // }

            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            // if(next==true){
            //   if($("select[id='categories']").find(':selected').val() == 0){
            //     notif(0,'Kategori wajib dipilih');
            //     next=false;
            //   }   
            // }        

            if (next == true) {
                if ($("select[id='satuan']").find(':selected').val() == 0) {
                    notif(0, 'Satuan wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                // if ($("input[id='harga_jual']").val().length == 0) {
                //     notif(0, 'Harga Jual wajib diisi');
                //     $("#harga_jual").focus();
                //     next = false;
                // }
            }

            if (next == true) {
                // if ($("select[id='account_buy']").find(':selected').val() == 0) {
                //     notif(0, 'Akun Pembelian harus dipilih');
                //     next = false;
                // }
            }

            if (next == true) {
                // if ($("select[id='account_sell']").find(':selected').val() == 0) {
                //     notif(0, 'Akun Penjualan harus dipilih');
                //     next = false;
                // }
            }

            if (next == true) {
                /*var prepare = {
                 tipe: identity,
                 id: $("input[id=id_document]").val(),
                 kode: $("input[id='kode']").val(),
                 nama: $("input[id='nama']").val(),
                 keterangan: $("textarea[id='keterangan']").val(),        
                 harga_beli: $("input[id='harga_beli']").val(),
                 harga_jual: $("input[id='harga_jual']").val(),
                 stok_minimal: $("input[id='stok_minimal']").val(),
                 stok_maksimal: $("input[id='stok_maksimal']").val(),        
                 satuan: $("select[id='satuan']").find(':selected').val(),
                 upload: $('#upload')[0].files[0].name,
                 status: $("select[id='status']").find(':selected').val()
                 }
                 var prepare_data = JSON.stringify(prepare);
                 var data = {
                 action: 'update',
                 data: prepare_data
                 };*/
                var formData = new FormData();
                formData.append('action', 'update');
                formData.append('id', $('#id_document').val());
                // formData.append('upload1', $('#upload1')[0].files[0]);
                // formData.append('tipe', $('#product_type').find(':selected').val());
                formData.append('kode', $('#kode').val());
                formData.append('nama', $('#nama').val());
                formData.append('keterangan', $('#keterangan').val());
                // formData.append('harga_beli', $('#harga_beli').val());
                // formData.append('harga_jual', $('#harga_jual').val());
                // formData.append('harga_promo', $('#harga_promo').val());
                // formData.append('stok_minimal', $('#stok_minimal').val());
                // formData.append('stok_maksmal', $('#stok_maksimal').val());
                // formData.append('manufacture', $('#manufacture').val());      
                formData.append('referensi', $('#referensi').find(':selected').val());
                formData.append('satuan', $('#satuan').find(':selected').val());
                // formData.append('status', $('#status').find(':selected').val());
                // formData.append('with_stock', $('#with_stock').find(':selected').val());
                formData.append('categories', $('#categories').find(':selected').val());
                // formData.append('akun_beli', $('#account_buy').find(':selected').val());
                // formData.append('akun_jual', $('#account_sell').find(':selected').val());
                // formData.append('akun_inventory', $('#account_inventory').find(':selected').val());
                formData.append('product_branch_id', $('#product_branch_id').find(':selected').val());                   
                formData.append('upload1', $("#files_preview").attr('data-save-img'));
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    cache: false,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            // $("#btn-new").show();
                            // $("#btn-save").hide();
                            // $("#btn-update").hide();
                            // $("#btn-cancel").hide();
                            // $("#form-master input").val(); 
                            // formMasterSetDisplay(1); 
                            formCancel();
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                        } else {
                            notif(0, d.message);
                            // notifError(d.message);  
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
            var kode = $(this).attr("data-kode");
            var user = $(this).attr("data-nama");
            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus <b>' + user + '</b> ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'remove',
                                tipe: identity,
                                id: id
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) = 1) {
                                        notif(1, d.message);
                                        index.ajax.reload();
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
                                tipe: identity,
                                id: id,
                                flag: set_flag,
                                nama: nama,
                                kode: kode
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
                                        index.ajax.reload(null, false);
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
        $(document).on("click", "#btn_import_excel", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("#modal_product_excel").modal('show');
            $("#excel_file").val('');
        });
        $(document).on("click","#btn_import_excel_save", function (e) {
            e.preventDefault();
            var next = true;

			var _file_upload = $("#excel_file").val();
			if (_file_upload.length == 0) {
				return notif(0, 'Masukkan file excel');
			}

            if (next == true) {
                var form = new FormData();
                form.append('action', 'import_from_excel');    
                form.append('product_type', 1);         
                form.append('excel_file', $('#excel_file')[0].files[0]);  
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function (d) {
                        $("#btn_import_excel_save").html('Sedang Import Data...').attr('disabled', true);
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) { 
                            $("#btn_import_excel_save").html('<i class="fas fa-save"></i> Import').attr('disabled', false);
                            notif(1,d.message);
                            $("#modal_product_excel").modal('hide');
                            $("#excel_file").val('');
                            index.ajax.reload(null,false);
                        } else { //Errorss
                            $("#btn_import_excel_save").html('<i class="fas fa-save"></i> Import').attr('disabled', false);                        
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, 'Error');
                    }
                });
            }
        });

        // Set Preview
        $(document).on("click", ".btn-preview", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var title = $(this).attr("data-title");
            var urls = $(this).attr("data-url");
            console.log(urls);
            $.alert('Harusnya Redirect to ' + url_preview + urls);
        });

        $(document).on("click", ".btn-product-stock-DIE", function (e) { //Die Move to index.php
            e.preventDefault();
            e.stopPropagation();
            var product_id = $(this).attr('data-product-id');
            var product_name = $(this).attr('data-product-name');
            var product_unit = $(this).attr('data-product-unit');
            var title = 'Lokasi Stok';
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                // autoClose: 'button_2|30000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;

                    var form = new FormData();
                    form.append('action', 'stock');
                    form.append('id', product_id);
                    form.append('tipe', 1);

                    return $.ajax({
                        url: url,
                        data: form,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        if (parseInt(s) == 1) {
                            // notif(s,m);
                            // notifSuccess(m);
                            /* hint zz_for or zz_each */
                            var dsp = '';
                            var total_data = r.length;
                            dsp += 'Barang :<b>' + product_name + '</b><br>';
                            dsp += 'Satuan :<b>' + product_unit + '</b><br><br>';
                            dsp += '<table class="table table-bordered">';
                            dsp += '  <thead>';
                            dsp += '    <tr>';
                            dsp += '      <th>Gudang</th>';
                            dsp += '      <th>Stok</th>';
                            dsp += '      <th>Action</th>';
                            dsp += '    <tr>';
                            dsp += '  </thead>';
                            dsp += '  <tbody>';
                            for (var a = 0; a < total_data; a++) {
                                dsp += '<tr class="tr-price-item-id" data-id="' + d.result[a]['product_price_id'] + '">';
                                dsp += '<td>' + d.result[a]['location_name'] + '</td>';
                                dsp += '<td style="text-align:right;">' + addCommas(d.result[a]['qty_balance']) + '</td>';
                                dsp += '<td>';
                                dsp += '<button type="button" class="btn-product-stock-card btn btn-mini btn-primary" data-url="' + d.result[a]['stock_card_url'] + '">';
                                dsp += '<span class="fas fa-file-alt"></span>';
                                dsp += '&nbsp;Kartu Stok</button>';
                                dsp += '</td>';
                                dsp += '</tr>';
                            }
                            dsp += '  </tbody>';
                            dsp += '</table>';

                            self.setContentAppend(dsp);
                        } else {
                            // notif(s,m);
                            // notifSuccess(m);
                        }
                        // self.setTitle(m);
                        // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
                        /*type_your_code_here*/

                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    var self = this;
                    var content = '';
                    var dsp = '';

                    var d = self.ajaxResponse.data;

                    var s = d.status;
                    var m = d.message;
                    var r = d.result;

                    if (parseInt(s) == 1) {
                        // dsp += '<div>Content is ready after process !</div>';
                        // dsp += '<form id="jc_form">';
                        //     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        //     dsp += '    <div class="form-group">';
                        //     dsp += '    <label class="form-label">Input</label>';
                        //     dsp += '        <input id="jc_input" name="jc_input" class="form-control">';
                        //     dsp += '    </div>';
                        //     dsp += '</div>';
                        //     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        //     dsp += '    <div class="form-group">';
                        //     dsp += '    <label class="form-label">Textarea</label>';
                        //     dsp += '        <textarea id="jc_textarea" name="alamat" class="form-control" rows="4"></textarea>';
                        //     dsp += '    </div>';
                        //     dsp += '</div>';
                        //     dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        //     dsp += '    <div class="form-group">';
                        //     dsp += '    <label class="form-label">Select</label>';
                        //     dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
                        //     dsp += '            <option value="1">Ya</option>';
                        //     dsp += '            <option value="2">Tidak</option>';
                        //     dsp += '        </select>';
                        //     dsp += '    </div>';
                        //     dsp += '</div>';
                        // dsp += '</form>';
                        // content = dsp;
                        // self.setContentAppend(content);
                        // self.buttons.button_1.disable();
                        // self.buttons.button_2.disable();

                        // this.$content.find('form').on('submit', function (e) {
                        //      e.preventDefault();
                        //      self.$$formSubmit.trigger('click'); // reference the button and click it
                        // });
                    } else {
                        self.setContentAppend('<div>Content ready!</div>');
                    }
                },
                buttons: {
                    cancel: {
                        btnClass: 'btn-default',
                        text: 'Tutup',
                        action: function () {
                            // $.alert('Canceled!');
                        }
                    }
                }
            });
        });
        $(document).on("click", ".btn-product-stock-card", function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log($(this));
            var print_stock = $(this).attr('data-url');

            var x = screen.width / 2 - 700 / 2;
            var y = screen.height / 2 - 450 / 2;
            var print_url = print_stock;
            var win = window.open(print_url, 'Print Kartu Stok', 'width=880,height=500,left=' + x + ',top=' + y + '');
        });


        // Recipe
        $(document).on("click", "#btn-recipe", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var id = $("#id_document").val();
            if (parseInt(id) > 0) {
                var product_name = $("#nama").val();
                var product_unit = $("#satuan").find(':selected').val();
                $(".modal-title").html('Resep <br>' + product_name);
                $("#modal-product-name").html(product_name);
                $("#modal-product-unit").html(product_unit);
                $("#modal-recipe").modal('show');
                loadProductRecipe(id);
            } else {
                notif(0, 'Data belum dibuka');
            }
        });
        $(document).on("click", "#btn-recipe-save-item", function () {
            event.preventDefault();
            var product_id = $("#id_document").val();
            var recipe_product_id = $("#recipe-goods").find(':selected').val();
            var qty = $("#recipe-qty").val();
            var unit = $("#recipe-goods").find(':selected').attr('data-unit');
            var note = '-';
            var next = true;

            if (parseInt(recipe_product_id) > 0) {
                if (qty.length == 0) {
                    notif(0, 'Qty komponen harus diisi');
                    next = false;
                    $("#recipe-qty").focus();
                }

                if (next) {
                    var data = {
                        action: 'create-item-recipe',
                        product_id: product_id,
                        recipe_product_id: recipe_product_id,
                        qty: qty,
                        unit: unit,
                        note: note
                    }
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function (d) {
                            if (parseInt(d.status) == 1) {
                                notif(1, d.message);
                                loadProductRecipe(d.result.product_id);
                                $("#recipe-goods").val(0).trigger('change');
                                $("#recipe-qty").val('0,0000');
                                $("#recipe-unit").val('');
                            } else {
                                notif(0, d.message);
                            }
                        }
                    });
                }
            } else {
                notif(0, 'Komponen Barang harus dipilih');
            }
        });
        $(document).on("click", ".btn-recipe-delete", function () {
            event.preventDefault();
            var recipe_id = $(this).attr("data-recipe-id");
            var product_id = $(this).attr("data-recipe-product-id");
            var nama = $(this).attr("data-nama");
            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus komponen <b>' + nama + '</b> ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'delete-item-recipe',
                                recipe_id: recipe_id,
                                product_id: product_id
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        loadProductRecipe(d.result.product_id);
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
        });

        // Varian
        $(document).on("click", "#btn-price", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // console.log($(this));
            var id = $("#id_document").val();
            if (parseInt(id) > 0) {
                var product_name = $("#nama").val();
                var product_unit = $("#satuan").find(':selected').val();
                $(".modal-title").html('Varian Harga Jual <br>' + product_name);
                $("#modal-product-name").html(product_name);
                $("#modal-product-unit").html(product_unit);
                $("#modal-price").modal('show');
                loadProductPrice(id);
                $("#product_price_name").focus();
            } else {
                notif(0, 'Data belum dibuka / tersimpan');
            }
        });
        $(document).on("click", "#btn-price-save-item", function () {
            event.preventDefault();
            var product_price_product_id = $("#id_document").val();
            var product_price_name = $("#product_price_name").val();
            var product_price_price = $("#product_price_price").val();
            var next = true;

            if (parseInt(product_price_product_id) > 0) {
                if (product_price_name.length == 0) {
                    notif(0, 'Nama Varian harus diisi');
                    next = false;
                    $("#product_price_name").focus();
                }

                if (next) {
                    if (product_price_price.length == 0) {
                        notif(0, 'Varian Harga Jual harus diisi');
                        next = false;
                        $("#product_price_price").focus();
                    }
                }

                if (next) {
                    var data = {
                        action: 'create-item-price',
                        product_price_product_id: product_price_product_id,
                        product_price_name: product_price_name,
                        product_price_price: product_price_price
                    }
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function (d) {
                            if (parseInt(d.status) == 1) {
                                notif(1, d.message);
                                loadProductPrice(d.result.product_id);
                                $("#product_price_price").val('0,0000');
                                $("#product_price_name").val('');
                                $("#modal-price").modal('toggle');
                            } else {
                                notif(0, d.message);
                            }
                        }
                    });
                }
            } else {
                notif(0, 'Data Barang harus dibuka dahulu');
            }
        });
        $(document).on("click", ".btn-price-delete", function () {
            event.preventDefault();
            var product_price_product_id = $(this).attr("data-product-price-product-id");
            var product_price_id = $(this).attr("data-product-price-id");
            var product_price_name = $(this).attr("data-product-price-name");
            var product_price_price = $(this).attr("data-product-price-price");

            $.confirm({
                title: 'Hapus!',
                content: 'Apakah anda ingin menghapus varian <b>' + product_price_name + '</b> seharga <b>' + product_price_price + '</b> ?',
                buttons: {
                    confirm: {
                        btnClass: 'btn-danger',
                        text: 'Ya',
                        action: function () {
                            var data = {
                                action: 'delete-item-price',
                                product_price_id: product_price_id,
                                product_price_product_id: product_price_product_id
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'json',
                                success: function (d) {
                                    if (parseInt(d.status) == 1) {
                                        notif(1, d.message);
                                        loadProductPrice(d.result.product_price_product_id);
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
        });
        $(document).on("click", "#btn-print-all", function () {
            let alias1 = alias;
            let title   = 'Print Data '+alias1;
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                autoClose: 'button_2|100000',
                closeIcon: true, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){           
                },
                onContentReady: function(e){
                    let self    = this;
                    let d = self.ajaxResponse.data;
                    let content = '';
                    let dsp     = '';
                    
                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Kategori '+alias1+'</label>';
                        dsp += '        <select id="filter_category2" name="filter_category2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Tipe '+alias1+'</label>';
                        dsp += '        <select id="filter_type2" name="filter_type2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';
                        dsp += '            <option value="1">Barang</option>';
                        dsp += '            <option value="2">Jasa</option>';                                                                                                                                           
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';         
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Status '+alias1+'</label>';
                        dsp += '        <select id="filter_flag2" name="filter_flag2" class="form-control">';
                        dsp += '            <option value="a">Semua</option>';
                        dsp += '            <option value="1">Aktif</option>';
                        dsp += '            <option value="0">Nonaktif</option>';                                                
                        dsp += '        </select>';
                        dsp += '    </div>';
                        dsp += '</div>';                                                                                                         
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Urut Berdasarkan</label>';
                            dsp += '        <select id="filter_order2" name="filter_order2" class="form-control">';
                            dsp += '            <option value="1">Nama '+alias1+'</option>';
                            dsp += '            <option value="2">Kode '+alias1+'</option>';
                            dsp += '            <option value="3">Kategori '+alias1+'</option>';
                            dsp += '            <option value="4">Harga Beli</option>';
                            dsp += '            <option value="5">Harga Jual</option>';
                            dsp += '            <option value="6">Stok</option>';                                                                                                                        
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-6 col-xs-6 col-sm-6 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Sort</label>';
                            dsp += '        <select id="filter_dir2" name="filter_dir2" class="form-control">';
                            dsp += '            <option value="0">Urut Naik</option>';
                            dsp += '            <option value="1">Urut Menurun</option>';
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';        
                        dsp += '</div>';                
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);

                    $('#filter_category2').select2({
                        dropdownParent:$(".jconfirm-box-container"), //If Select2 Inside Modal
                        // tags:true,
                        minimumInputLength: 0,
                        allowClear: true,
                        minimumResultsForSearch: Infinity,
                        placeholder: {
                            id: '0',
                            text: 'Semua'
                        },                          
                        ajax: {
                            type: "get",
                            url: "<?= base_url('search/manage'); ?>",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = {
                                    search: params.term,
                                    tipe: 1,
                                    source: 'categories'
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
                        templateSelection: function (datas) { //When Option on Click
                            if (!datas.id) {
                                return datas.text;
                            }
                            if (parseInt(datas.id) > 0) {
                                return datas.text;
                            }
                        }
                    });       
                },
                buttons: {
                    button_1: {
                        text:'Print',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
                            let filter_order    = self.$content.find('#filter_order2').val();
                            let filter_dir      = self.$content.find('#filter_dir2').val(); 
                            let filter_cat      = self.$content.find('#filter_category2').val();
                            let filter_type      = self.$content.find('#filter_type2').val();   
                            let filter_flag      = self.$content.find('#filter_flag2').find(':selected').val();                                                                                                                
                            
                            if(filter_order == 0){
                                $.alert('Urut mohon dipilih dahulu');
                                return false;
                            } else{
                                // var filter_ref = $("#filter_ref").find(':selected').val();
                                // var filter_type = $("#filter_type").find(':selected').val();
                                // var filter_contact = $("#filter_contact").find(':selected').val();
                                // var filter_city = $("#filter_city").find(':selected').val();
                                // var filter_flag = $("#filter_flag").find(':selected').val();

                                // var filter_order    = 0;
                                // var filter_dir      = 0;
                                var request = $('.btn-print-all').data('request');
                                var p = url_print_all + '?cat=' + filter_cat;
                                    p += '&type=' + filter_type + '&flag=' + filter_flag;
                                    p += '&start=0&limit=0'; 
                                    p += '&order=' + filter_order + '&dir=' + filter_dir;
                                    p += '&image=0';
                                var win = window.open(p,'_blank').print();
                            }
                        }
                    },
                    button_2: {
                        text: 'Tutup',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });

        });        
        function loadProductRecipe(id = 0) {
            $("#table-recipe tbody").html('');
            var product_id = $("#id_document").val();
            var data = {
                action: 'load-recipe',
                product_id: product_id
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) { //Success
                        // notif(1,d.message);     
                        var total_records = d.total_records;
                        if (parseInt(total_records) > 0) {
                            var dsp = '';
                            for (var a = 0; a < total_records; a++) {
                                dsp += '<tr class="tr-recipe-item-id" data-id="' + d.result[a]['recipe_id'] + '">';
                                dsp += '<td>' + d.result[a]['product_name'] + '</td>';
                                dsp += '<td style="text-align:right;">' + addCommas(d.result[a]['recipe_qty']) + '</td>';
                                dsp += '<td style="text-align:left;">' + d.result[a]['recipe_unit'] + '</td>';
                                dsp += '<td>';
                                dsp += '<button type="button" class="btn-recipe-delete btn btn-mini btn-danger" data-recipe-product-id="' + d.result[a]['recipe_product_id'] + '" data-recipe-id="' + d.result[a]['recipe_id'] + '" data-nama="' + d.result[a]['product_name'] + '">';
                                dsp += '<span class="fas fa-trash-alt"></span>';
                                dsp += '</button>';
                                dsp += '</td>';
                                dsp += '</tr>';
                            }
                            $("#table-recipe tbody").html(dsp);
                        } else {
                            $("#table-recipe tbody").html('');
                            $("#table-recipe tbody").html('<tr><td colspan="4">Tidak ada data</td></tr>');
                        }
                    } else { //No Data
                        $("#table-recipe tbody").html('');
                        $("#table-recipe tbody").html('<tr><td colspan="4">Tidak ada data</td></tr>');
                    }
                }
            });
        }
        function loadProductPrice(id = 0) {
            $("#table-price tbody").html('');
            var product_id = $("#id_document").val();
            var data = {
                action: 'load-price',
                product_id: product_id
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: 'false',
                success: function (d) {
                    if (parseInt(d.status) === 1) { //Success
                        // notif(1,d.message);     
                        var total_records = d.total_records;
                        if (parseInt(total_records) > 0) {
                            var dsp = '';
                            for (var a = 0; a < total_records; a++) {
                                dsp += '<tr class="tr-price-item-id" data-id="' + d.result[a]['product_price_id'] + '">';
                                dsp += '<td>' + d.result[a]['product_price_name'] + '</td>';
                                dsp += '<td style="text-align:right;">' + addCommas(d.result[a]['product_price_price']) + '</td>';
                                dsp += '<td>';
                                dsp += '<button type="button" class="btn-price-delete btn btn-mini btn-danger"';
                                dsp += ' data-product-price-id="' + d.result[a]['product_price_id'] + '" data-product-price-product-id="' + d.result[a]['product_price_product_id'] + '"';
                                dsp += ' data-product-price-name="' + d.result[a]['product_price_name'] + '" data-product-price-price="' + addCommas(d.result[a]['product_price_price']) + '">';
                                dsp += '<span class="fas fa-trash-alt"></span>';
                                dsp += '</button>';
                                dsp += '</td>';
                                dsp += '</tr>';
                            }

                        } else {
                            dsp += '<tr><td colspan="3">Tidak ada data</td></tr>';
                        }
                    } else { //No Data
                        dsp += '<tr><td colspan="3">Tidak ada data</td></tr>';
                    }
                    $("#table-price tbody").html(dsp);
                }
            });
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
        function formNew() {
            formMasterSetDisplay(0);
            $("#form-master input").val('');
            $("#btn-new").hide();
            $("#btn-save").show();
            $("#btn-cancel").show();

            $("#harga_beli").val('0.00');
            $("#harga_jual").val('0.00');
            $("#harga_promo").val('0.00');
            $("#stok_minimal").val('0');
        }
        function formCancel() {
            formMasterSetDisplay(1);
            formResetCheckbox();
            $("#form-master input").val('');
            $("#btn-new").show();
            $("#btn-save").hide();
            $("#btn-update").hide();
            $("#btn-cancel").hide();
            $("#div-form-trans").hide(300);

            $("#files_link").attr('href',url_image);
            $("#files_preview").attr('src',url_image);
            $("#files_preview").attr('data-save-img','');
            
            $("#harga_beli").val('0.00');
            $("#harga_jual").val('0.00');
            $("#harga_promo").val('0.00');
            $("#stok_minimal").val('0');

            $("#b_varian_label").html('Daftar Varian Harga Jual');
            // loadProductPrice(0);
        }               
    });

    function formResetCheckbox() {
        $("#form-master checkbox").prop('checked', false);

        $("#account_buy").attr('disabled', true);
        $("#account_sell").attr('disabled', true);
        $("#account_inventory").attr('disabled', true);
        $("#with_stock").attr('disabled', true);

        $("#harga_beli").attr('readonly', true);
        $("#harga_jual").attr('readonly', true);
        $("#stok_minimal").attr('readonly', true);
        $("#with_stock").val(0).trigger('change');
    }
    function formMasterSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
        //Attr Input yang perlu di setel
        var form = '#form-master';
        var attrInput = [
            "kode",
            "nama",
            // "harga_beli",
            // "harga_jual",
            // "harga_promo",
            "stok_minimal",
                    // "stok_maksimal"
                    // "manufacture" 
        ];
        $("input[name='harga_beli']").val(0);
        $("input[name='harga_jual']").val(0);
        // $("input[name='harga_promo']").val(0);    
        $("input[name='stok_minimal']").val(0);
        $("input[name='stok_maksimal']").val(0);

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            "keterangan"
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "satuan",
            "status",
            "categories",
            "referensi",
            "product_type",
        ];
        for (var i = 0; i <= atributSelect.lengthcategories; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
    function activeTabDetail(tab) {
        $(".nav-tabs-detail").find('li[class="active"]').removeClass('active');
        $('.nav-tabs-detail li a[data-name="' + tab + '"]').tab('show');

        $('.tab-content-detail > div').hide(300);
        $('#' + tab + '').show(300);
    }
</script>