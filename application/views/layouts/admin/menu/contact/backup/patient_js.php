<script>
    $(document).ready(function () {
        $("#checkbox_patient").prop("checked", true);

        var identity = "<?php echo $identity; ?>";
        var view = "<?php echo $_view; ?>";
        var url = "<?= base_url('kontak/manage'); ?>";
        var url_image = "<?= site_url('upload/noimage.png'); ?>";
        $("#img-preview1").attr('src', url_image);
        // console.log("Identity:"+identity,"View:"+view);

        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="contact/patient"]').addClass('active');

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

        // $("#filter_search").focus();
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
                    d.length = $("#filter_length").find(':selected').val();
                    d.filter_kontak = $("#filter_kontak").find(':selected').val();
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
                {"targets": 0, "title": "Nomor RM", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Pasien Info", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Gender & Tgl Lahir", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Telepon & Email", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [
                {
                    'data': 'contact_code',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '<a class="btn-edit" style="cursor:pointer"';
                        dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + row.contact_id + '" data-flag="' + row.contact_flag + '">';
                        dsp += row.contact_code + '</a>';
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
                        if (row.contact_identity_number != undefined) {
                            dsp += '<br>' + row.contact_identity_number;
                        }
                        if (row.contact_company != undefined) {
                            dsp += '<br>' + row.contact_company;
                        }
                        dsp += '<br>' + row.contact_address;
                        return dsp;
                    }
                }, {
                    'data': 'contact_id',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (parseInt(row.contact_gender) == 1) {
                            dsp += 'Pria';
                        } else if (parseInt(row.contact_gender) == 2) {
                            dsp += 'Wanita';
                        } else {
                            dsp += 'Error';
                        }
                        dsp += '<br>' + row.contact_birth_city_name + ', ' + row.contact_birth_date;
                        dsp += '<br><label class="label">' + row.contact_parent_name + '</label>';
                        return dsp;

                    }
                }, {
                    'data': 'contact_id',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.contact_phone_1;
                        if (row.contact_email_1 != undefined) {
                            dsp += '<br>' + row.contact_email_1;
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
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
                            dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + data + '" data-flag="' + row.contact_flag + '">';
                            dsp += '<span class="fas fa-user-check primary"></span></button>';
                        } else {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.contact_name + '" data-kode="' + row.contact_code + '" data-id="' + data + '" data-flag="' + row.contact_flag + '">';
                            dsp += '<span class="fas fa-user-alt-slash danger"></span></button>';
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

        $('#asuransi').select2({
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
                        tipe: 5, //1=Supplier, 2=Asuransi
                        source: 'contacts'
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
                // if (!datas.id) { return datas.text; }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
                // else{
                // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
                // }        
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);            
                // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                if (parseInt(datas.id) > 0) {
                    return datas.text;
                }
            }
        });
        $('#tempat_lahir').select2({
            placeholder: '--- Pilih Kota / Kabupaten ---',
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
                        source: 'cities'
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
                // $(data.element).attr('data-province-id', data.customValue);  
                // $(data.element).attr('data-province-id', data.customValue);      
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#kota').select2({
            placeholder: '--- Pilih Kota / Kabupaten ---',
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
                        source: 'cities'
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
                // $(data.element).attr('data-province-id', data.customValue);  
                // $(data.element).attr('data-province-id', data.customValue);      
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
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
        $('#filter_kontak').select2({
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
                        tipe: 5, //1=Supplier, 2=Asuransi
                        source: 'contacts'
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
                // if (!datas.id) { return datas.text; }
                if ($.isNumeric(datas.id) == true) {
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
                // else{
                // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
                // }        
            },
            templateSelection: function (datas) { //When Option on Click
                if (!datas.id) {
                    return datas.text;
                }
                //Custom Data Attribute
                // $(datas.element).attr('data-alamat', datas.alamat);
                // $(datas.element).attr('data-telepon', datas.telepon);
                // $(datas.element).attr('data-email', datas.email);            
                // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                // if(parseInt(datas.id) > 0){
                return datas.text;
                // }
            }
        });

        $(document).on("change", "#filter_kontak", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_flag", function (e) {
            index.ajax.reload();
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
            $("#btn-new").css('display', 'inline');
            // formTransCancel();
            // btnNew.classList.remove('animate__animated', 'animate__fadeOutRight');    
            $("#div-form-trans").hide(300);
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
            // var type_supplier = ($("#checkbox_supplier").is(':checked')==true) ? 1 : 0;
            // var type_customer = ($("#checkbox_customer").is(':checked')==true) ? 1 : 0; 
            // var type_karyawan = ($("#checkbox_karyawan").is(':checked')==true) ? 1 : 0;
            var type_patient = ($("#checkbox_patient").is(':checked') == true) ? 1 : 0;

            // if(next==true){    
            //   if($("input[id='kode']").val().length == 0){
            //     notif(0,'Nomor Rekam Medis wajib diisi');
            //     $("#kode").focus();
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
                if ($("input[id='telepon']").val().length == 0) {
                    notif(0, 'Telepon wajib diisi');
                    $("#telepon_1").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("textarea[id='alamat']").val().length == 0) {
                    notif(0, 'Alamat wajib diisi');
                    $("#alamat").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='tempat_lahir']").find(':selected').val() < 1) {
                    notif(0, 'Tempat Lahir harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='tgl_lahir']").val().length == 0) {
                    notif(0, 'Tgl Lahir wajib diisi');
                    $("#tgl_lahir").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='gender']").find(':selected').val() < 1) {
                    notif(0, 'Jenis Kelamin harus dipilih');
                    next = false;
                }
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
                form.append('upload1', $('#upload1')[0].files[0]);
                form.append('tipe', $("input[id=tipe]").val());
                form.append('kode', $('#kode').val());
                form.append('nama', $('#nama').val());
                // form.append('perusahaan', $('#perusahaan').val());      
                form.append('telepon_1', $('#telepon').val());
                // form.append('telepon_2', $('#telepon_2').val());      
                form.append('email_1', $('#email').val());
                // form.append('email_2', $('#email_2').val());
                form.append('alamat', $('#alamat').val());
                form.append('status', $('#status').find(':selected').val());
                // form.append('handphone', $('#handphone').val());
                // form.append('fax', $('#fax').val());
                // form.append('npwp', $('#npwp').val());
                // form.append('note', $('#note').val());            
                // form.append('identity_type', $('#identity_type').find(':selected').val());       
                form.append('identity_number', $('#nomor').val());
                // form.append('supplier',type_supplier);
                // form.append('customer',type_customer);
                // form.append('karyawan',type_karyawan);   
                form.append('pasien', 1);
                // form.append('profesi', $('#profesi').find(':selected').val());   
                // form.append('nama_mandarin', $('#nama_mandarin').val());
                // form.append('tgl', $('#tgl').val());      
                form.append('gender', $('#gender').find(':selected').val());
                form.append('kontak_parent', $('#asuransi').find(':selected').val());
                form.append('tgl_lahir', $('#tgl_lahir').val());
                form.append('tempat_lahir', $('#tempat_lahir').find(':selected').val());
                form.append('kota', $('#kota').find(':selected').val());
                form.append('akun_hutang', $('#account_payable').find(':selected').val());
                form.append('akun_piutang', $('#account_receivable').find(':selected').val());
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) {
                            notif(1, d.message);
                            index.ajax.reload();
                            formCancel();
                            $("#img-preview1").attr('src', url_image);
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

        // Edit Button
        $(document).on("click", ".btn-edit", function (e) {
            formMasterSetDisplay(0);
            $("#form-master input[name='kode']").attr('readonly', true);
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
                        $("#form-master input[name='nama']").attr('readonly', true);
                        // $("#form-master input[name='perusahaan']").val(d.result.contact_company);                   
                        $("#form-master input[name='telepon']").val(d.result.contact_phone_1);
                        // $("#form-master input[name='telepon_2']").val(d.result.contact_phone_2);
                        $("#form-master input[name='email']").val(d.result.contact_email_1);
                        // $("#form-master input[name='email_2']").val(d.result.contact_email_2);          
                        $("#form-master textarea[name='alamat']").val(d.result.contact_address);
                        $("#form-master select[name='status']").val(d.result.contact_flag).trigger('change');

                        // $("#form-master input[name='handphone']").val(d.result.contact_handphone);
                        // $("#form-master input[name='fax']").val(d.result.contact_fax);
                        // $("#form-master input[name='npwp']").val(d.result.contact_npwp);
                        $("#form-master input[name='nomor']").val(d.result.contact_identity_number);
                        // $("#form-master textarea[name='note']").val(d.result.contact_note);          
                        // $("#form-master select[name='identity_type']").val(d.result.contact_identity_type).trigger('change');

                        //Account Payable & Receivable
                        // $("select[name='account_payable']").append(''+
                        //                   '<option value="'+d.result.payable_account_id+'">'+
                        //                     d.result.payable_account_code+' - '+d.result.payable_account_name+
                        //                   '</option>');
                        // $("select[name='account_payable']").val(d.result.payable_account_id).trigger('change');
                        // $("select[name='account_receivable']").append(''+
                        //                   '<option value="'+d.result.receivable_account_id+'">'+
                        //                     d.result.receivable_account_code+' - '+d.result.receivable_account_name+
                        //                   '</option>');
                        // $("select[name='account_receivable']").val(d.result.receivable_account_id).trigger('change');          

                        var parent = d.result.parent_code + ' - ' + d.result.parent_name + ', ' + d.result.parent_phone;
                        $("select[id='asuransi']").append('' +
                                '<option value="' + d.result.parent_id + '">' +
                                parent +
                                '</option>');
                        $("select[id='asuransi']").val(d.result.parent_id).trigger('change');

                        $("select[id='kota']").append('' +
                                '<option value="' + d.result.city_id + '">' +
                                d.result.city_name +
                                '</option>');
                        $("select[id='kota']").val(d.result.city_id).trigger('change');

                        $("select[id='tempat_lahir']").append('' +
                                '<option value="' + d.result.birth_city_id + '">' +
                                d.result.birth_city_name +
                                '</option>');
                        $("select[id='tempat_lahir']").val(d.result.birth_city_id).trigger('change');

                        $('select[id="gender"]').val(d.result.contact_gender).trigger('change');
                        $("#tgl_lahir").val(d.result.contact_birth_date);

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
                            $('#img-preview1').attr('src', url_image);
                        } else {
                            var image = "<?php echo site_url(); ?>" + d.result.contact_image;
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
            // var type_supplier = ($("#checkbox_supplier").is(':checked')==true) ? 1 : 0;
            // var type_customer = ($("#checkbox_customer").is(':checked')==true) ? 1 : 0; 
            // var type_karyawan = ($("#checkbox_karyawan").is(':checked')==true) ? 1 : 0;
            var type_patient = ($("#checkbox_patient").is(':checked') == true) ? 1 : 0;

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
                notif(0, 'Nomor RM wajib diisi');
                kode.focus();
                next = false;
            }


            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (next == true) {
                if ($("select[id='tempat_lahir']").find(':selected').val() < 1) {
                    notif(0, 'Tempat Lahir harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("input[id='tgl_lahir']").val().length == 0) {
                    notif(0, 'Tgl Lahir wajib diisi');
                    $("#tgl_lahir").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='gender']").find(':selected').val() < 1) {
                    notif(0, 'Jenis Kelamin harus dipilih');
                    next = false;
                }
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
                form.append('upload1', $('#upload1')[0].files[0]);
                form.append('tipe', $("input[id=tipe]").val());
                form.append('kode', $('#kode').val());
                form.append('nama', $('#nama').val());
                // form.append('perusahaan', $('#perusahaan').val());      
                form.append('telepon_1', $('#telepon').val());
                // form.append('telepon_2', $('#telepon_2').val());      
                form.append('email_1', $('#email').val());
                // form.append('email_2', $('#email_2').val());
                form.append('alamat', $('#alamat').val());
                form.append('status', $('#status').find(':selected').val());
                // form.append('handphone', $('#handphone').val());
                // form.append('fax', $('#fax').val());
                // form.append('npwp', $('#npwp').val());
                // form.append('note', $('#note').val());            
                // form.append('identity_type', $('#identity_type').find(':selected').val());       
                form.append('identity_number', $('#nomor').val());
                // form.append('supplier',type_supplier);
                // form.append('customer',type_customer);
                // form.append('karyawan',type_karyawan);       
                form.append('pasien', 1);
                // form.append('profesi', $('#profesi').find(':selected').val());   
                // form.append('nama_mandarin', $('#nama_mandarin').val());
                // form.append('tgl', $('#tgl').val());      
                form.append('gender', $('#gender').find(':selected').val());
                form.append('kontak_parent', $('#asuransi').find(':selected').val());
                form.append('tgl_lahir', $('#tgl_lahir').val());
                form.append('tempat_lahir', $('#tempat_lahir').find(':selected').val());
                form.append('kota', $('#kota').find(':selected').val());
                form.append('akun_hutang', $('#account_payable').find(':selected').val());
                form.append('akun_piutang', $('#account_receivable').find(':selected').val());

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
                            notif(1, d.message);
                            index.ajax.reload(null, false);
                            formCancel();
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

        $(document).on("click", "#btn-export", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $.alert('Nothing to do');
        });

        $('#upload1').change(function (e) {
            var fileName = e.target.files[0].name;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img-preview1').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });

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

    });

    function formNew() {
        formMasterSetDisplay(0);
        $("#form-master input").val('');
        $("#form-master textarea").val('');
        $("#btn-new").hide();
        $("#btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form-master input").val('');
        $("#form-master textarea").val('');
        $("#btn-new").css('display', 'inline');
        $("#btn-save").hide();
        $("#btn-update").hide();
        $("#btn-cancel").hide();
        $("#div-form-trans").hide(300);
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
            // "kode",
            "nomor",
            "nama",
            "telepon",
            "email",
            "identity_number"
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
            "gender",
            "asuransi",
            "tempat_lahir",
            "kota"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>