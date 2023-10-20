<script>
    $(document).ready(function () {
        $("#checkbox_customer").prop("checked", true);
        // $.alert('Create Update CATEGORIES');
        var identity = "<?php echo $identity; ?>";
        var view = "<?php echo $_view; ?>";
        var url = "<?= base_url('kontak/manage'); ?>";
        var url_image = "<?= site_url('upload/noimage.png'); ?>";
        var url_finance = "<?= base_url('keuangan/manage'); ?>";
        // $("#img-preview1").attr('src', url_image);
        // console.log("Identity:"+identity,"View:"+view);
        var alias = "<?= $title; ?>";
        
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="contact/customer"]').addClass('active');

        let image_width = "<?= $image_width;?>";
        let image_height = "<?= $image_height;?>";  

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
        //Autonumeric
        const autoNumericOption = {
            digitGroupSeparator: ',',
            decimalCharacter: '.',
            decimalCharacterAlternative: '.',
            decimalPlaces: 2,
            watchExternalChanges: true //!!!        
        };
        new AutoNumeric('#contact_receivable_limit', autoNumericOption);

        // $("#filter_search").focus();
        var index = $("#table-data").DataTable({
            // "processing": true,
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
                    d.length = $("#filter_length").find(':selected').val();
                    d.filter_flag = $("#filter_flag").find(':selected').val();
                    d.filter_categories = $("#filter_categories").find(':selected').val();                    
                    d.search = {
                        value: $("#filter_search").val()
                    };
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Kode", "searchable": true, "orderable": true, "width":"10%"},
                {"targets": 1, "title": "Nama", "searchable": true, "orderable": true, "width":"30%"},
                {"targets": 2, "title": "Telepon", "searchable": true, "orderable": true, "width":"15%"},
                {"targets": 3, "title": "Alamat", "searchable": true, "orderable": true, "width":"30%"},
                {"targets": 4, "title": "Action", "searchable": false, "orderable": false, "width":"15%"}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [{
                    'data': 'contact_code',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += '<a class="btn-edit" style="cursor:pointer"';
                        // dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + row.contact_id + '" data-flag="' + row.contact_flag + '">';
                        // dsp += row.contact_code + '</a>';
                        // dsp += row.contact_code;
                        if (row.contact_code != undefined) {
                            dsp += row.contact_code;
                        }else{
                            dsp += '-';
                        }                        
                        return dsp;
                    }
                }, {
                    'data': 'contact_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-edit" style="cursor:pointer"';
                        dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + data + '" data-flag="' + row.contact_flag + '">';
                        dsp += row.contact_name + '</a>';
    
                        // if (row.contact_company != undefined) {
                        //     dsp += '<br>' + row.contact_company;
                        // }else{
                        //     dsp += '<br>-';
                        // }

                        // if (row.contact_parent_id != undefined) {
                        //     dsp += '<br><label class="label" style="background-color:#33b0b6;color:white;padding:1px 4px;">' + row.contact_parent_name+'</label>';
                        // }                                        
                        return dsp;
                    }
                }, {
                    'data': 'contact_phone_1',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.contact_phone_1;
                        if (row.contact_email_1 != undefined) {
                            dsp += '<br>' + row.contact_email_1;
                        }
                        return dsp;
                    }
                }, {
                    'data': 'contact_address',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (row.contact_address != undefined) {
                            dsp += row.contact_address + '<br>';
                        }
                        if (row.contact_category_id != undefined) {
                            dsp += '<label class="label label-inverse" style="padding:1px 4px;">' + row.category_name+'</label>';
                        }                              
                        if (parseFloat(row.contact_receivable_balance) > 0) {
                            // dsp += '<br><label class="btn-receivable-payable label label-danger" style="cursor:pointer;" data-contact-id="' + row.contact_id + '" data-trans-type="2"><i class="fas fa-exclamation-triangle"></i> Sisa Piutang: ' + addCommas(row.contact_receivable_balance) + "</label>";
                        }
                        if (parseFloat(row.contact_receivable_limit) > 0) {
                            // dsp += '<label class="label label-info"><i class="fas fa-hand-holding-usd"></i> Limit Piutang: ' + addCommas(row.contact_receivable_limit) + "</label>";
                        }
                        return dsp;
                    }
                }, {
                    'data': 'contact_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';
                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';

                        if (parseInt(row.contact_flag) === 1) {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + data + '" data-flag="' + row.contact_flag + '">';
                            dsp += '<span class="fas fa-user-check primary"></span> Aktif</button>';
                        } else {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + data + '" data-flag="' + row.contact_flag + '">';
                            dsp += '<span class="fas fa-user-alt-slash danger"></span> Nonaktif</button>';
                        }

                        return dsp;
                    }
                }]
        });

        //Datatable Config
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
        $("#filter_categories").on('change', function (e) {
            index.ajax.reload();
        });        
        $("#filter_search").on('input', function (e) {
            var ln = $(this).val().length;
            if (parseInt(ln) > 3) {
                index.ajax.reload();
            } else if (parseInt(ln) < 1) {
                index.ajax.reload();
            }
        });
        $('#table-data').on('page.dt', function () {
            var info = index.page.info();
            var limit_start = info.start;
            var limit_end = info.end;
            var length = info.length;
            var page = info.page;
            var pages = info.pages;
            // console.log( 'Showing page: '+info.page+' of '+info.pages);
            // console.log(limit_start,limit_end);
            $("#table-data-in").attr('data-limit-start', limit_start);
            $("#table-data-in").attr('data-limit-end', limit_end);
        });

        $('#account_payable').select2({
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
                        // group:1,
                        group_sub: 8
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
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        $('#account_receivable').select2({
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
                        // group:1,
                        group_sub: 1
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
                return '<i class="fas fa-balance-scale ' + datas.id.toLowerCase() + '"></i> ' + datas.text;
            }
        });
        $('#contact_termin').select2({
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
                        source: 'references-contact-termin',
                        tipe: 8
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
        $('#categories').select2({
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
                        tipe: 4, //1=Produk, 2=News
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
        $('#filter_categories').select2({
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
                        tipe: 4, //1=Produk, 2=News
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
        $('#contact_parent_id').select2({
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
                        source: 'contacts',
                        tipe:3
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

        // New Button
        $(document).on("click", "#btn-new", function (e) {
            formNew();
            // $("#div-form-trans").show(300);
            $("#div-form-trans").show(300);
            $(this).hide();
            // animateCSS('#btn-new', 'backOutLeft','true');

            // btnNew.classList.add('animate__animated', 'animate__fadeOutRight');
        });
        // Cancel Button
        $(document).on("click", "#btn-cancel", function (e) {
            e.preventDefault();
            formCancel();
            $("#img-preview1").attr('src', url_image);
        });
        // Save Button 
        /*
         $(document).on("click","#btn-save",function(e) {
         e.preventDefault();
         var next = true;
         
         var kode = $("#form-master input[name='kode']");
         var nama = $("#form-master input[name='nama']");
         
         //Contact Type
         var type_supplier = $("#checkbox_supplier").is(':checked'), 
         type_customer = $("#checkbox_customer").is(':checked'), 
         type_karyawan = $("#checkbox_karyawan").is(':checked');
         
         if(next==true){    
         if($("input[id='kode']").val().length == 0){
         notif(0,'Kode wajib diisi');
         $("#kode").focus();
         next=false;
         }
         }
         
         if(next==true){
         if($("input[id='nama']").val().length == 0){
         notif(0,'Nama wajib diisi');
         $("#nama").focus();
         next=false;
         }   
         }
         
         if(next==true){
         if($("input[id='telepon_1']").val().length == 0){
         notif(0,'Telepon 1 wajib diisi');
         $("#telepon_1").focus();
         next=false;
         }   
         }    
         
         if(next==true){
         if($("textarea[id='alamat']").val().length == 0){
         notif(0,'Alamat wajib diisi');
         $("#alamat").focus();
         next=false;
         }   
         }        
         
         
         if(next==true){
         var prepare = {
         tipe: $("input[id=tipe]").val(),
         kode: $("input[id='kode']").val(),
         nama: $("input[id='nama']").val(),
         perusahaan: $("input[id='perusahaan']").val(),        
         telepon_1: $("input[id='telepon_1']").val(),
         telepon_2: $("input[id='telepon_2']").val(),
         email_1: $("input[id='email_1']").val(),
         email_2: $("input[id='email_2']").val(),        
         alamat: $("textarea[id='alamat']").val(),
         status: $("select[id='status']").find(':selected').val(),
         handphone: $("input[id='handphone']").val(),
         fax: $("input[id='fax']").val(),
         npwp: $("input[id='npwp']").val(),        
         note: $("textarea[id='note']").val(),
         identity_type: $("select[id='identity_type']").find(':selected').val(),
         identity_number: $("input[id='identity_number']").val(),
         supplier: type_supplier,
         customer: type_customer,
         karyawan: type_karyawan        
         }
         var prepare_data = JSON.stringify(prepare);
         var data = {
         action: 'create',
         data: prepare_data
         };
         $.ajax({
         type: "POST",     
         url: url,
         data: data, 
         dataType:'json',
         cache: false,
         beforeSend:function(){},
         success:function(d){
         if(parseInt(d.status)==1){
         notif(1,d.message);
         index.ajax.reload();
         }else{
         notif(0,d.message);  
         }            
         },
         error:function(xhr, Status, err){
         notif(0,'Error');
         }
         });
         }
         });  
         */
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;

            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            //Contact Type
            var type_supplier = ($("#checkbox_supplier").is(':checked') == true) ? 1 : 0;
            var type_customer = ($("#checkbox_customer").is(':checked') == true) ? 1 : 0;
            var type_karyawan = ($("#checkbox_karyawan").is(':checked') == true) ? 1 : 0;

            if (next == true) {
                // if ($("input[id='kode']").val().length == 0) {
                //     notif(0, 'Kode wajib diisi');
                //     $("#kode").focus();
                //     next = false;
                // }
            }

            if (next == true) {
                if ($("input[id='nama']").val().length == 0) {
                    notif(0, 'Nama wajib diisi');
                    $("#nama").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='telepon_1']").val().length == 0) {
                    notif(0, 'Telepon 1 wajib diisi');
                    $("#telepon_1").focus();
                    next = false;
                }
            }

            if (next == true) {
                // if ($("textarea[id='alamat']").val().length == 0) {
                //     notif(0, 'Alamat wajib diisi');
                //     $("#alamat").focus();
                //     next = false;
                // }
            }

            if (next == true) {
                if ($("select[id='account_payable']").find(':selected').val() < 1) {
                    notif(0, 'Akun Hutang harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_receivable']").find(':selected').val() < 1) {
                    notif(0, 'Akun Piutang harus dipilih');
                    next = false;
                }
            }

            if (next == true) {

                var form = new FormData();
                form.append('action', 'create');
                // form.append('upload1', $('#upload1')[0].files[0]);
                form.append('tipe', identity);
                form.append('kode', $('#kode').val());
                form.append('nama', $('#nama').val());
                form.append('perusahaan', $('#perusahaan').val());
                form.append('telepon_1', $('#telepon_1').val());
                // form.append('telepon_2', $('#telepon_2').val());      
                form.append('email_1', $('#email_1').val());
                // form.append('email_2', $('#email_2').val());
                form.append('alamat', $('#alamat').val());
                form.append('status', $('#status').find(':selected').val());
                // form.append('handphone', $('#handphone').val());
                // form.append('fax', $('#fax').val());
                // form.append('npwp', $('#npwp').val());
                form.append('note', $('#note').val());
                // form.append('identity_type', $('#identity_type').find(':selected').val());
                // form.append('identity_number', $('#identity_number').val());
                form.append('supplier', type_supplier);
                form.append('customer', type_customer);
                form.append('karyawan', type_karyawan);
                form.append('akun_hutang', $('#account_payable').find(':selected').val());
                form.append('akun_piutang', $('#account_receivable').find(':selected').val());
                form.append('contact_termin', $('#contact_termin').find(':selected').val());
                // form.append('profesi', $('#profesi').find(':selected').val());   
                // form.append('nama_mandarin', $('#nama_mandarin').val());
                // form.append('tgl', $('#tgl').val());      
                form.append('contact_receivable_limit', removeCommas($('#contact_receivable_limit').val()));
                form.append('categories', $('#categories').find(':selected').val());
                form.append('upload1', $("#files_preview").attr('data-save-img'));                 
                form.append('kontak_parent',$("#contact_parent_id").find(":selected").val());                
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $("#btn-save").attr('disabled',true);
                        notif(1,'Sedang menambahkan');                    
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            notif(1, d.message);
                            index.ajax.reload();
                            formCancel();
                        } else {
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
                id: id
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                    $("#checkbox_supplier").prop("checked", false);
                    $("#checkbox_customer").prop("checked", false);
                    $("#checkbox_karyawan").prop("checked", false);
                },
                success: function (d) {
                    if (parseInt(d.status) == 1) { /* Success Message */
                        //activeTab('tab1'); // Open/Close Tab By ID
                        // notif(1,d.result.contact_id);
                        $("#form-master input[id='id_document']").val(d.result.contact_id);
                        $("#form-master input[name='kode']").val(d.result.contact_code);
                        $("#form-master input[name='nama']").val(d.result.contact_name);
                        $("#form-master input[name='perusahaan']").val(d.result.contact_company);
                        $("#form-master input[name='telepon_1']").val(d.result.contact_phone_1);
                        $("#form-master input[name='telepon_2']").val(d.result.contact_phone_2);
                        $("#form-master input[name='email_1']").val(d.result.contact_email_1);
                        $("#form-master input[name='email_2']").val(d.result.contact_email_2);
                        $("#form-master textarea[name='alamat']").val(d.result.contact_address);
                        $("#form-master select[name='status']").val(d.result.contact_flag).trigger('change');

                        $("#form-master input[name='handphone']").val(d.result.contact_handphone);
                        $("#form-master input[name='fax']").val(d.result.contact_fax);
                        $("#form-master input[name='npwp']").val(d.result.contact_npwp);
                        // $("#form-master input[name='identity_number']").val(d.result.contact_identity_number);
                        $("#form-master textarea[name='note']").val(d.result.contact_note);
                        // $("#form-master select[name='identity_type']").val(d.result.contact_identity_type).trigger('change');

                        //Account Payable & Receivable
                        $("select[name='account_payable']").append('' +
                                '<option value="' + d.result.payable_account_id + '">' +
                                d.result.payable_account_code + ' - ' + d.result.payable_account_name +
                                '</option>');
                        $("select[name='account_payable']").val(d.result.payable_account_id).trigger('change');
                        $("select[name='account_receivable']").append('' +
                                '<option value="' + d.result.receivable_account_id + '">' +
                                d.result.receivable_account_code + ' - ' + d.result.receivable_account_name +
                                '</option>');
                        $("select[name='account_receivable']").val(d.result.receivable_account_id).trigger('change');

                        if(d.result.contact_category_id != undefined){
                            $("select[name='categories']").append('' +
                                    '<option value="' + d.result.category_id + '">' +
                                    d.result.category_name +
                                    '</option>');
                            $("select[name='categories']").val(d.result.category_id).trigger('change');
                        }

                        //Contact Type
                        var contact_type = d.result.contact_type, output = [], num = contact_type.toString();
                        for (var i = 0, len = num.length; i < len; i += 1) {
                            if (num.charAt(i) == 1) {
                                $("#checkbox_supplier").prop("checked", true);
                            }
                            if (num.charAt(i) == 2) {
                                $("#checkbox_customer").prop("checked", true);
                            }
                            if (num.charAt(i) == 3) {
                                $("#checkbox_karyawan").prop("checked", true);
                            }
                        }

                        //Contact Image
                        if (d.result.contact_image == undefined) {
                            // $('#img-preview1').attr('src', url_image);
                            $("#files_preview").attr('src',url_image);
                            $(".files_link").attr('href',url_image);                            
                        } else {
                            var image = "<?php echo base_url(); ?>" + d.result.contact_image;
                            // $('#img-preview1').attr('src', image);
                            $("#files_preview").attr('src',image);
                            $(".files_link").attr('href',image);                            
                        } 
                        
                        if(d.result.parent_id != undefined){
                            $("select[id='contact_parent_id']").append(''+'<option value="'+d.result.parent_id+'">'+d.result.parent_name+'</option>');
                            $("select[id='contact_parent_id']").val(d.result.parent_id).trigger('change');
                        }

                        if (parseInt(d.result.contact_termin) > 0) {
                            $("select[name='contact_termin']").append('<option value="' + d.result.contact_termin + '">' + d.result.contact_termin + ' Hari</option>');
                        } else {
                            $("select[name='contact_termin']").append('<option value="' + d.result.contact_termin + '">Cash (COD)</option>');
                        }
                        $("select[name='contact_termin']").val(d.result.contact_termin).trigger('change');
                        $("#contact_receivable_limit").val(d.result.contact_receivable_limit);

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
        /*
         $(document).on("click","#btn-update",function(e) {
         e.preventDefault();
         var next = true;
         var id = $("#form-master input[name='id_dokumen']").val();
         var kode = $("#form-master input[name='kode']");
         var nama = $("#form-master input[name='nama']");
         
         //Contact Type
         var type_supplier = $("#checkbox_supplier").is(':checked'), 
         type_customer = $("#checkbox_customer").is(':checked'), 
         type_karyawan = $("#checkbox_karyawan").is(':checked');
         
         if(id == ''){
         notif(0,'ID tidak ditemukan');
         next=false;
         }
         
         // $("input[type=checkbox]").each(function(){
         //   var input = $(this).is(':checked');
         //   // console.log(input);
         //   if(input == false){
         //   }
         // });
         
         if(kode.val().length == 0){
         notif(0,'Kode wajib diisi');
         kode.focus();
         next=false;
         }
         
         
         if(nama.val().length == 0){
         notif(0,'Nama wajib diisi');
         nama.focus();
         next=false;
         }    
         
         if(next==true){
         var prepare = {
         id: $("input[id=id_document]").val(),
         tipe: $("input[id=tipe]").val(),
         kode: $("input[id='kode']").val(),
         nama: $("input[id='nama']").val(),
         perusahaan: $("input[id='perusahaan']").val(),        
         telepon_1: $("input[id='telepon_1']").val(),
         telepon_2: $("input[id='telepon_2']").val(),
         email_1: $("input[id='email_1']").val(),
         email_2: $("input[id='email_2']").val(),        
         alamat: $("textarea[id='alamat']").val(),
         status: $("select[id='status']").find(':selected').val(),
         handphone: $("input[id='handphone']").val(),
         fax: $("input[id='fax']").val(),
         npwp: $("input[id='npwp']").val(),        
         note: $("textarea[id='note']").val(),
         identity_type: $("select[id='identity_type']").find(':selected').val(),
         identity_number: $("input[id='identity_number']").val(),
         supplier: type_supplier,
         customer: type_customer,
         karyawan: type_karyawan
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
         dataType:"json", 
         beforeSend:function(){},
         success:function(d){
         console.log(d);
         if(parseInt(d.status)==1){
         $("#btn-new").show();
         $("#btn-save").hide();
         $("#btn-update").hide();
         $("#btn-cancel").hide();
         $("#form-master input").val(); 
         formMasterSetDisplay(1);      
         notif(1,d.message);
         index.ajax.reload();
         }
         else{
         notif(0,d.message);  
         }            
         },
         error:function(xhr, Status, err){
         notif(0,'Error');
         }
         });
         }
         });
         */
        $(document).on("click", "#btn-update", function (e) {
            e.preventDefault();
            var next = true;
            var id = $("#form-master input[name='id_dokumen']").val();
            var kode = $("#form-master input[name='kode']");
            var nama = $("#form-master input[name='nama']");

            //Contact Type
            var type_supplier = ($("#checkbox_supplier").is(':checked') == true) ? 1 : 0;
            var type_customer = ($("#checkbox_customer").is(':checked') == true) ? 1 : 0;
            var type_karyawan = ($("#checkbox_karyawan").is(':checked') == true) ? 1 : 0;

            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            // $("input[type=checkbox]").each(function(){
            //   var input = $(this).is(':checked');
            //   // console.log(input);
            //   if(input == false){
            //   }
            // });

            if (kode.val().length == 0) {
                // notif(0, 'Kode wajib diisi');
                // kode.focus();
                // next = false;
            }


            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (next == true) {
                if ($("select[id='account_payable']").find(':selected').val() < 1) {
                    notif(0, 'Akun Hutang harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='account_receivable']").find(':selected').val() < 1) {
                    notif(0, 'Akun Piutang harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                var form = new FormData();
                form.append('action', 'update');
                form.append('id', $('#id_document').val());
                // form.append('upload1', $('#upload1')[0].files[0]);
                form.append('tipe', identity);
                form.append('kode', $('#kode').val());
                form.append('nama', $('#nama').val());
                form.append('perusahaan', $('#perusahaan').val());
                form.append('telepon_1', $('#telepon_1').val());
                // form.append('telepon_2', $('#telepon_2').val());      
                form.append('email_1', $('#email_1').val());
                // form.append('email_2', $('#email_2').val());
                form.append('alamat', $('#alamat').val());
                form.append('status', $('#status').find(':selected').val());
                // form.append('handphone', $('#handphone').val());
                // form.append('fax', $('#fax').val());
                // form.append('npwp', $('#npwp').val());
                form.append('note', $('#note').val());
                // form.append('identity_type', $('#identity_type').find(':selected').val());
                // form.append('identity_number', $('#identity_number').val());
                form.append('supplier', type_supplier);
                form.append('customer', type_customer);
                form.append('karyawan', type_karyawan);
                form.append('akun_hutang', $('#account_payable').find(':selected').val());
                form.append('akun_piutang', $('#account_receivable').find(':selected').val());
                form.append('contact_termin', $('#contact_termin').find(':selected').val());
                form.append('contact_receivable_limit', removeCommas($('#contact_receivable_limit').val()));
                form.append('categories', $('#categories').find(':selected').val());
                form.append('upload1', $("#files_preview").attr('data-save-img'));
                form.append('kontak_parent',$("#contact_parent_id").find(":selected").val());                
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        console.log(d);
                        if (parseInt(d.status) == 1) {
                            formCancel();
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
                                id: id
                            }
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function (d) {
                                    if (parseInt(d.status) = 1) {
                                        notif(1, d.message);
                                        index.ajax.reload(null, false);
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
            var user = $(this).attr("data-nama");
            $.confirm({
                title: 'Set Status',
                content: 'Apakah anda ingin <b>' + msg + '</b> dengan nama <b>' + user + '</b> ?',
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
                                user: user,
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
            $("#modal_contact_excel").modal('show');
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
                form.append('contact_type', 2);         
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
                            $("#modal_contact_excel").modal('hide');
                            $("#excel_file").val('');
                            index.ajax.reload(null,false);
                        } else { //Error
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

        $(document).on("click", ".btn-receivable-payable", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var contact_id = $(this).attr('data-contact-id');
            var trans_type = $(this).attr('data-trans-type');
            var title = 'Riwayat Transaksi';
            $.confirm({
                title: title,
                columnClass: 'col-lg-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|40000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                useBootstrap: false, // Key line
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var form = new FormData();
                    form.append('action', 'load-account-receivable');
                    form.append('contact_id', contact_id);
                    form.append('tipe', trans_type);
                    return $.ajax({
                        url: url_finance,
                        data: form,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        if (parseInt(s) == 1) {

                            if (tt == 1) {
                                var label = 'Supplier';
                                var type = 'Hutang';
                                var title = 'Riwayat Pembayaran Hutang ';
                            } else if (tt == 2) {
                                var label = alias;
                                var type = 'Piutang';
                                var title = 'Riwayat Pelunasan Piutang ';
                            }
                            var dsp = '';
                            dsp += '<table class="table-default">';
                            dsp += '<tr><td><b>Nomor Dokumen </b></td><td> : ' + tn + '</td></tr>';
                            dsp += '<tr><td><b>' + label + '</b></td><td> : ' + cn + '</td></tr>';
                            dsp += '<tr><td><b>Total (Rp) </b></td><td> : ' + addCommas(tto) + '</td></tr>';
                            dsp += '</table>';


                            dsp += '<table id="table-order" class="table table-bordered">';
                            dsp += '  <thead>';
                            dsp += '    <th>Tanggal</th>';
                            dsp += '    <th class="text-left">Keterangan</th>';
                            dsp += '    <th class="text-right">Debit</th>';
                            dsp += '    <th class="text-right">Kredit</th>';
                            dsp += '    <th class="text-right">Balance</th>';
                            dsp += '  </thead>';
                            dsp += '  <tbody>';
                            if (parseInt(d['total_data']) > 0) {
                                // var related = r[p]['related'];
                                var debt = 0;
                                var crdt = 0;
                                for (var p = 0; p < d['total_data']; p++) {
                                    dsp += '    <tr>';
                                    dsp += '      <td>' + r[p]['temp_date'] + '</td>';
                                    dsp += '      <td>';
                                    dsp += '<a href="' + r[p]['temp_url'] + '" target="_blank"><i class="fas fa-file-alt"></i> ' + r[p]['temp_number'] + '</a><br>';
                                    dsp += '<b>' + r[p]['temp_note'] + '</b>';
                                    if (r[p]['temp_memo'] != undefined) {
                                        dsp += '<br>' + r[p]['temp_memo'];
                                    }
                                    dsp += '      </td>';
                                    dsp += '      <td class="text-right">' + addCommas(r[p]['temp_debit']) + '</td>';
                                    dsp += '      <td class="text-right">' + addCommas(r[p]['temp_credit']) + '</td>';
                                    dsp += '      <td class="text-right">' + addCommas(r[p]['temp_balance']) + '</td>';
                                    dsp += '    </tr>';

                                    debt = parseFloat(debt) + parseFloat(r[p]['temp_debit']);
                                    crdt = parseFloat(crdt) + parseFloat(r[p]['temp_credit']);
                                }
                                if (tt == 1) {
                                    var balance = parseFloat(crdt) - parseFloat(debt);
                                } else if (tt == 2) {
                                    var balance = parseFloat(debt) - parseFloat(crdt);
                                }
                                dsp += '<tr>';
                                dsp += '<td colspan="4"><b>Sisa ' + type + ' (Rp)</b></td>';
                                dsp += '<td class="text-right"><b>' + addCommas(balance) + '</b></td>';
                                dsp += '</tr>';
                            } else {
                                dsp += '    <tr><td colspan="4">Tidak ada data riwayat</td></tr>';
                                dsp += '<tr>';
                                dsp += '<td colspan="3"><b>Sisa ' + type + ' (Rp)</b></td>';
                                dsp += '<td class="text-right"><b>' + addCommas(tto) + '</b></td>';
                                dsp += '</tr>';
                            }
                            dsp += '  </tbody>';
                            dsp += '</table>';

                        } else {
                            // notif(s,m);
                            // notifSuccess(m);
                        }
                        self.setTitle(title);
                        self.setContentAppend(dsp);
                        /*type_your_code_here*/
                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                buttons: {
                    button_2: {
                        text: 'Tutup',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function () {
                            //Close
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
                        dsp += '    <label class="form-label">Group '+alias1+'</label>';
                        dsp += '        <select id="filter_category2" name="filter_category2" class="form-control">';
                        dsp += '            <option value="0">Semua</option>';
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
                            dsp += '            <option value="3">Perusahaan</option>';    
                            dsp += '            <option value="4">Group '+alias1+'</option>';                                                                                                                                           
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
                                    tipe: 4,
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
                            let filter_type      = 2;   
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
                                var p = "<?= base_url('contact/print'); ?>" + '?';
                                    p += '&cat='+filter_cat;
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
        // $('#upload1').change(function (e) {
        //     var fileName = e.target.files[0].name;
        //     var reader = new FileReader();
        //     reader.onload = function (e) {
        //         $('#img-preview1').attr('src', e.target.result);
        //     };
        //     reader.readAsDataURL(this.files[0]);
        // });

        /*
         //Save akses Button
         $(document).on("click","#btn-save-akses",function(e) {
         e.preventDefault();
         var data = {
         action : 'save-akses',
         data: $("#form-akses").serialize()
         }
         $.ajax({
         type: "POST",     
         url: url,
         data: data, 
         beforeSend:function(){},
         success:function(result){
         if(parseInt(result['status'])==1){
         notif(1,result['message']);
         activeTab('tab1');
         showData();
         }
         else{ //Error
         notif(0,result['message']);  
         }            
         },
         error:function(xhr, Status, err){
         notif(0,'Error');
         }
         });
         });
         */

        /*
         // Delete akses Button
         $(document).on("click","#btn-delete-akses",function(e) {
         e.preventDefault();
         var data = {
         action : 'remove-akses',
         data: $("#form-akses").serialize()
         }
         $.ajax({
         type: "POST",     
         url: url,
         data: data,
         beforeSend:function(){},
         success:function(result){
         if(parseInt(result['status'])==1){
         notif(1,result['message']);
         activeTab('tab1');
         showData();
         }
         else{
         notif(0,result['message']);  
         }            
         },
         error:function(xhr, Status, err){
         notif(0,'Error');
         }
         });
         });
        */
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
            $("#form-master textarea").val('');
            $("#btn-new").hide();
            $("#btn-save").show();
            $("#btn-cancel").show();
            $("#contact_receivable_limit").val('0.00');
        }
        function formCancel() {
            formMasterSetDisplay(1);
            $("#form-master input").val('');
            $("#form-master textarea").val('');
            $("#btn-new").css('display', 'inline');
            $("#contact_termin").val(0).trigger('change');
            $("#files_link").attr('href',url_image);
            $("#files_preview").attr('src',url_image);
            $("#files_preview").attr('data-save-img','');

            $("#contact_parent_id").val(0).trigger('change');

            $("#btn-save").hide();
            $("#btn-update").hide();
            $("#btn-cancel").hide();
            $("#div-form-trans").hide(300);
        }
    });
    function formMasterSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        if (value == 1) {
            var flag = true;
        } else {
            var flag = false;
        }
        //Attr Input yang perlu di setel
        var form = '#form-master';
        var attrInput = [
            // "kode",
            "nama",
            "perusahaan",
            "telepon_1",
            "telepon_2",
            "email_1",
            "email_2",
            "handphone",
            "fax",
            "npwp",
            // "identity_number",
            "contact_receivable_limit"
        ];
        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            "alamat",
            "note"
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "status",
            // "identity_type",
            "contact_parent_id",            
            "type",
            "contact_termin",
            "categories"            
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>