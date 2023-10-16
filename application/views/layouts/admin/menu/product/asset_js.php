
<script>
    $(document).ready(function () {
        // $.alert('Produk Jasa belum ready');
        // $("#modal-recipe").modal('show');
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');

        //Nav Tabs Resep & Price    
        // $(".nav-tabs-detail").find('li[class="active"]').removeClass('active');
        // $(".nav-tabs-detail").find('li[data-name="tab_price"]').addClass('active');
        activeTabDetail('tab_price');

        // console.log(menu_link);
        var url = "<?= base_url('produk/manage'); ?>";
        var url_image = '<?= base_url('upload/noimage.png'); ?>';
        $("#img-preview1").attr('src', url_image);

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
        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        const autoNumericOption2 = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 4,
            watchExternalChanges: true //!!!        
        };
        const autoNumericOption3 = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 0,
            watchExternalChanges: true //!!!        
        };
        // new AutoNumeric('#harga_jual', autoNumericOption);
        // new AutoNumeric('#harga_beli', autoNumericOption);
        // new AutoNumeric('#harga_promo', autoNumericOption);     

        // var start = 0;
        // var length = $("#filter_length").find(':selected').val();
        var index = $("#table-data").DataTable({
            // "processing": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.tipe = identity;
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end');
                    // d.start = start;
                    // d.length = length;
                    // d.filter_type = identity;
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
                {"targets": 0, "title": "Jenis", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Kode", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Nama", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Satuan", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Pengingat", "searchable": true, "orderable": true},
                {"targets": 5, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [1, 'asc']
            ],
            "columns": [
                {'data': 'ref_name'
                }, {'data': 'product_code'
                }, {
                    'data': 'product_name',
                    render: function (data, meta, row) {
                        var dsp = '';
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
                }, {'data': 'product_unit'
                }, {
                    'data': 'product_reminder',
                    className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (data != undefined) {
                            dsp += data;
                            dsp += '<br>' + row.product_reminder_date_format;
                            dsp += '<br>' + row.product_reminder_date_next + ' hari lagi';
                        } else {
                            dsp += '-';
                        }
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
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
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
        $("#filter_ref").on('change', function (e) {
            index.ajax.reload();
        });
        $('#table-data').on('page.dt', function () {
            var info = index.page.info();
            // console.log( 'Showing page: '+info.page+' of '+info.pages);
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            // start = limit_start;
            // length = info.length;
            // console.log("Start: "+info.start+", Length: "+info.length+", End: "+info.end); 
            // console.log("page .dt : "+info.start+", "+info.length);        
            // $("#table-data").attr('data-limit-start',limit_start);
            // $("#table-data").attr('data-limit-end',limit_end);
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
        /*
         $('#categories').select2({
         placeholder: '--- Pilih ---',
         minimumInputLength: 0,
         ajax: {
         type: "get",
         url: "<?= base_url('search/manage'); ?>",      
         dataType: 'json',
         delay: 250,
         data: function(params){
         var query = {
         search: params.term,
         tipe: 1, //1=Produk, 2=News
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
         templateSelection: function (data, container) {
         // Add custom attributes to the <option> tag for the selected option
         // $(data.element).attr('data-custom-attribute', data.customValue);
         // $("input[name='satuan']").val(data.satuan);
         return data.text;
         }        
         }); 
         */
        $('#product_ref_id').select2({
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
                        tipe: 6, //1=Produk, 2=News
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
        /*  
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
         group_sub:15
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         if($.isNumeric(datas.id) == true){
         // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         return datas.text;          
         }else{
         // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
         }           
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
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
         group_sub:13
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
         escapeMarkup: function(markup){ 
         return markup; 
         },
         templateResult: function(datas){ //When Select on Click
         if (!datas.id) { return datas.text; }
         // return '<i class="fas fa-balance-scale '+datas.id.toLowerCase()+'"></i> '+datas.text;
         if($.isNumeric(datas.id) == true){
         // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
         return datas.text;          
         }else{
         // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;    
         }           
         },
         templateSelection: function(datas) { //When Option on Click
         if (!datas.id) { return datas.text; }
         //Custom Data Attribute         
         return datas.text;
         }
         });
         */
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
                        tipe: 6, //1=Produk, 2=News
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
        /*
         $(document).on("change","#checkbox_buy",function(e) {
         e.preventDefault();
         e.stopPropagation();
         $(this).prop('checked');
         if($(this).prop('checked') == true){
         $("#account_buy").removeAttr('disabled');
         $("#harga_beli").removeAttr('readonly');
         }else{
         $("#account_buy").attr('disabled',true);
         $("#harga_beli").attr('readonly',true);
         }
         });
         $(document).on("change","#checkbox_sell",function(e) {
         e.preventDefault();
         e.stopPropagation();
         $(this).prop('checked');
         if($(this).prop('checked') == true){
         $("#account_sell").removeAttr('disabled');
         $("#harga_jual").removeAttr('readonly');
         }else{
         $("#account_sell").attr('disabled',true);
         $("#harga_jual").attr('readonly',true);
         }
         });  
         $(document).on("change","#checkbox_inventory",function(e) {
         e.preventDefault();
         e.stopPropagation();
         $(this).prop('checked');
         if($(this).prop('checked') == true){
         $("#account_inventory").removeAttr('disabled');
         $("#with_stock").removeAttr('disabled');      
         $("#stok_minimal").removeAttr('readonly');
         }else{
         $("#account_inventory").attr('disabled',true);
         $("#with_stock").attr('disabled',true);      
         $("#stok_minimal").attr('readonly',true);
         }
         });
         */
        $(document).on("click", "#btn-new", function (e) {
            formNew();
            $("#div-form-trans").show(300);
            $(this).hide();
        });
        $(document).on("click", "#btn-cancel", function (e) {
            formCancel();
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
            // if(next==true){
            //   if($("select[id='product_manufacture']").find(':selected').val() == 0){
            //     notif(0,'Jenis produk wajib dipilih');
            //     next=false;
            //   }
            // }

            if (next == true) {
                if ($("select[id='satuan']").find(':selected').val() == 0) {
                    notif(0, 'Satuan wajib dipilih');
                    next = false;
                }
            }

            // if(next==true){
            //   if($("input[id='harga_jual']").val().length == 0){
            //     notif(0,'Harga Jual wajib diisi');
            //     $("#harga_jual").focus();
            //     next=false;
            //   }   
            // }    

            // if(next==true){
            //   if($("select[id='account_buy']").find(':selected').val() == 0){
            //     notif(0,'Akun Pembelian harus dipilih');
            //     next=false;
            //   }   
            // }

            // if(next==true){
            //   if($("select[id='account_sell']").find(':selected').val() == 0){
            //     notif(0,'Akun Penjualan harus dipilih');
            //     next=false;
            //   }   
            // }

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
                formData.append('upload1', $('#upload1')[0].files[0]);
                formData.append('tipe', 3);
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
                // formData.append('categories', $('#categories').find(':selected').val()); 
                // formData.append('manufacture', $('#manufacture').find(':selected').val());
                formData.append('referensi', $('#product_ref_id').find(':selected').val());
                // formData.append('akun_beli', $('#account_buy').find(':selected').val());             
                // formData.append('akun_jual', $('#account_sell').find(':selected').val());
                // formData.append('akun_inventory', $('#account_inventory').find(':selected').val());      
                formData.append('product_reminder', $('#product_reminder').val());

                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            index.ajax.reload();
                        } else { //Error
                            notif(0, d.message);
                        }
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
            $("#form-master input[name='kode']").attr('readonly', true);
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
                        // $("#form-master select[id='product_type']").val(d.result.product_type).trigger('change');
                        $("#form-master input[name='id_document']").val(d.result.product_id);
                        $("#form-master input[name='kode']").val(d.result.product_code);
                        $("#form-master input[name='nama']").val(d.result.product_name);
                        $("#form-master input[name='keterangan']").val(d.result.product_note);
                        // $("#form-master input[name='harga_beli']").val(d.result.product_price_buy);
                        // $("#form-master input[name='harga_jual']").val(d.result.product_price_sell);
                        // $("#form-master input[name='harga_promo']").val(d.result.product_price_promo);          
                        // $("#form-master input[name='stok_minimal']").val(d.result.product_min_stock_limit);
                        // $("#form-master input[name='stok_maksimal']").val(d.result.product_max_stock_limit);
                        // $("#form-master input[name='manufacture']").val(d.result.product_manufacture);                    
                        $("#form-master textarea[name='keterangan']").val(d.result.product_note);
                        $("#form-master select[name='status']").val(d.result.product_flag).trigger('change');
                        // $("#form-master select[name='with_stock']").val(d.result.product_with_stock).trigger('change');          
                        // $("#form-master select[name='manufacture']").val(d.result.product_manufacture).trigger('change');

                        // $("#form-master select[name='satuan']").val(d.result.product_unit).trigger('change');
                        $("select[id='satuan']").append('' +
                                '<option value="' + d.result.product_unit + '">' +
                                d.result.product_unit +
                                '</option>');
                        $("select[id='satuan']").val(d.result.product_unit).trigger('change');

                        // $("select[name='categories']").append(''+
                        //                   '<option value="'+d.result.category_id+'">'+
                        //                     d.result.category_name+
                        //                   '</option>');
                        // $("select[name='categories']").val(d.result.category_id).trigger('change');

                        $("select[name='referensi']").append('' +
                                '<option value="' + d.result.ref_id + '">' +
                                d.result.ref_name +
                                '</option>');
                        $("select[name='referensi']").val(d.result.ref_id).trigger('change');

                        if (d.result.product_reminder != undefined) {
                            $("#product_reminder").val(d.result.product_reminder).trigger('change');
                        }
                        // $("select[name='account_buy']").append(''+
                        //                   '<option value="'+d.result.buy_account_id+'">'+
                        //                     d.result.buy_account_code+' - '+d.result.buy_account_name+
                        //                   '</option>');
                        // $("select[name='account_buy']").val(d.result.buy_account_id).trigger('change');

                        // $("select[name='account_sell']").append(''+
                        //                   '<option value="'+d.result.sell_account_id+'">'+
                        //                     d.result.sell_account_code+' - '+d.result.sell_account_name+
                        //                   '</option>');
                        // $("select[name='account_sell']").val(d.result.sell_account_id).trigger('change');

                        // $("select[name='account_inventory']").append(''+
                        //                   '<option value="'+d.result.inventory_account_id+'">'+
                        //                     d.result.inventory_account_code+' - '+d.result.inventory_account_name+
                        //                   '</option>');
                        // $("select[name='account_inventory']").val(d.result.inventory_account_id).trigger('change');

                        if (parseInt(d.result.product_images) == 0) {
                            $('#img-preview1').attr('src', url_image);
                        } else {
                            var image = "<?php echo site_url(); ?>" + d.result.product_image;
                            $('#img-preview1').attr('src', image);
                        }


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

            // if(next==true){
            //   if($("input[id='harga_jual']").val().length == 0){
            //     notif(0,'Harga Jual wajib diisi');
            //     $("#harga_jual").focus();
            //     next=false;
            //   }   
            // }

            // if(next==true){
            //   if($("select[id='account_buy']").find(':selected').val() == 0){
            //     notif(0,'Akun Pembelian harus dipilih');
            //     next=false;
            //   }   
            // }

            // if(next==true){
            //   if($("select[id='account_sell']").find(':selected').val() == 0){
            //     notif(0,'Akun Penjualan harus dipilih');
            //     next=false;
            //   }   
            // }

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
                formData.append('upload1', $('#upload1')[0].files[0]);
                formData.append('tipe', 3);
                formData.append('kode', $('#kode').val());
                formData.append('nama', $('#nama').val());
                formData.append('keterangan', $('#keterangan').val());
                // formData.append('harga_beli', $('#harga_beli').val());
                // formData.append('harga_jual', $('#harga_jual').val());
                // formData.append('harga_promo', $('#harga_promo').val());            
                // formData.append('stok_minimal', $('#stok_minimal').val());  
                // formData.append('stok_maksmal', $('#stok_maksimal').val());
                // formData.append('manufacture', $('#manufacture').val());      
                formData.append('referensi', $('#product_ref_id').find(':selected').val());
                formData.append('satuan', $('#satuan').find(':selected').val());
                formData.append('status', $('#status').find(':selected').val());
                // formData.append('with_stock', $('#with_stock').find(':selected').val());      
                // formData.append('categories', $('#categories').find(':selected').val());
                // formData.append('akun_beli', $('#account_buy').find(':selected').val());             
                // formData.append('akun_jual', $('#account_sell').find(':selected').val());
                // formData.append('akun_inventory', $('#account_inventory').find(':selected').val());    
                formData.append('product_reminder', $('#product_reminder').val());

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

        // Set Preview
        $(document).on("click", ".btn-preview", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var title = $(this).attr("data-title");
            var urls = $(this).attr("data-url");
            console.log(urls);
            $.alert('Harusnya Redirect to ' + url_preview + urls);
        });

        /*
         $(document).on("click",".btn-product-stock",function(e) {
         e.preventDefault();
         e.stopPropagation();
         var product_id = $(this).attr('data-product-id');
         var product_name = $(this).attr('data-product-name');
         var product_unit = $(this).attr('data-product-unit');
         var title   = 'Lokasi Stok';
         $.confirm({
         title: title,
         columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
         autoClose: 'button_2|30000',    
         closeIcon: true,
         closeIconClass: 'fas fa-times',    
         animation:'zoom',
         closeAnimation:'bottom',
         animateFromElement:false,      
         content: function(){
         var self = this;
         
         var form = new FormData();
         form.append('action', 'stock');
         form.append('id',product_id);
         form.append('tipe',1);
         
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
         if(parseInt(s) == 1){
         // notif(s,m);
         // notifSuccess(m);
         var dsp = '';
         var total_data = r.length;
         dsp += 'Barang :<b>'+product_name+'</b><br>';
         dsp += 'Satuan :<b>'+product_unit+'</b><br><br>';
         dsp += '<table class="table table-bordered">';
         dsp += '  <thead>';
         dsp += '    <tr>';
         dsp += '      <th>Gudang</th>';
         dsp += '      <th>Stok</th>';
         dsp += '      <th>Action</th>';  
         dsp += '    <tr>';
         dsp += '  </thead>';
         dsp += '  <tbody>';
         for(var a=0; a<total_data; a++){  
         dsp += '<tr class="tr-price-item-id" data-id="'+d.result[a]['product_price_id']+'">';
         dsp += '<td>'+d.result[a]['location_name']+'</td>';
         dsp += '<td style="text-align:right;">'+addCommas(d.result[a]['qty_balance'])+'</td>';         
         dsp += '<td>';
         dsp += '<button type="button" class="btn-product-stock-card btn btn-mini btn-primary" data-url="'+d.result[a]['stock_card_url']+'">';
         dsp += '<span class="fas fa-file-alt"></span>';
         dsp += '&nbsp;Kartu Stok</button>';
         dsp += '</td>';
         dsp += '</tr>';
         }
         dsp += '  </tbody>';
         dsp += '</table>';
         
         self.setContentAppend(dsp);
         }else{
         alert('error');
         }            
         }).fail(function(){
         self.setContent('Something went wrong, Please try again.');
         });
         
         },
         onContentReady: function(){
         var self = this;
         var content = '';
         var dsp     = '';
         
         var d = self.ajaxResponse.data;
         
         var s = d.status;
         var m = d.message;
         var r = d.result;
         
         if(parseInt(s)==1){
         }else{
         self.setContentAppend('<div>Content ready!</div>');
         }
         }
         });
         });
         $(document).on("click",".btn-product-stock-card",function(e) {
         e.preventDefault();
         e.stopPropagation();
         console.log($(this));
         var print_stock = $(this).attr('data-url');
         
         var x = screen.width / 2 - 700 / 2;
         var y = screen.height / 2 - 450 / 2;
         var print_url = print_stock;
         var win = window.open(print_url,'Print Kartu Stok','width=880,height=500,left=' + x + ',top=' + y + '');
         });
         */

        $('#upload1').change(function (e) {
            var fileName = e.target.files[0].name;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview1').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });
        // function readURL(input) {
        //   if (input.files && input.files[0]) {
        //     var reader = new FileReader();
        //     reader.onload = function (e) {
        //         $('.uploadpdf').text(input.files[0].name);
        //     }
        //     reader.readAsDataURL(input.files[0]);
        //   }
        // } 
    });

    function formNew() {
        formMasterSetDisplay(0);
        $("#form-master input").val('');
        $("#btn-new").hide();
        $("#btn-save").show();
        $("#btn-cancel").show();

        // $("#harga_beli").val('0.00');
        // $("#harga_jual").val('0.00');
        // $("#harga_promo").val('0.00');    
        // $("#stok_minimal").val('0');      
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

        // $("#harga_beli").val('0.00');
        // $("#harga_jual").val('0.00');
        // $("#harga_promo").val('0.00');
        // $("#stok_minimal").val('0');    
    }
    function formResetCheckbox() {
        $("#form-master checkbox").prop('checked', false);

        // $("#account_buy").attr('disabled',true);
        // $("#account_sell").attr('disabled',true);
        // $("#account_inventory").attr('disabled',true);    
        // $("#with_stock").attr('disabled',true);        

        // $("#harga_beli").attr('readonly',true);
        // $("#harga_jual").attr('readonly',true);  
        // $("#stok_minimal").attr('readonly',true); 
        // $("#with_stock").val(0).trigger('change');                     
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
                    // "stok_minimal",
                    // "stok_maksimal"
                    // "manufacture" 
        ];
        // $("input[name='harga_beli']").val(0);
        // $("input[name='harga_jual']").val(0);
        // $("input[name='stok_minimal']").val(0);
        // $("input[name='stok_maksimal']").val(0);    

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
            "product_ref_id",
            "product_reminder",
            "status",
                    // "categories",
                    // "manufacture",
                    // "product_type",
                    // "with_stock",
                    // "account_buy",
                    // "account_sell"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
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