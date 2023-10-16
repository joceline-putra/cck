<script>
    $(document).ready(function () {

        var identity = "<?php echo $identity; ?>";
        var view = "<?php echo $_view; ?>";
        var url = "<?= base_url('konfigurasi/manage'); ?>";
        var url_image = '<?= base_url('upload/noimage.png'); ?>';

        let image_width = "<?= $image_width;?>";
        let image_height = "<?= $image_height;?>"; 

        // $("#img-preview1").attr('src', url_image);

        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="configuration/branch"]').addClass('active');

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
                    d.search = {
                        value: $("#filter_search").val()
                    };
                    d.filter_specialist = $("#filter_specialist").find(':selected').val();
                    d.filter_province = $("#filter_province").find(':selected').val();
                    d.filter_city = $("#filter_city").find(':selected').val();
                    // d.search['value']['contact_type'] = $("#filter_type").find(':selected').val();
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Logo", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Cabang", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Kontak", "searchable": true, "orderable": true},
                {"targets": 3, "title": "User", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'desc']
            ],
            "columns": [{
                    'data': 'branch_name',
                    className: 'text-left',
                    render: function (meta, data, row) {
                        var dsp = '';

                        var img = row.branch_logo;
                        if (img === null) {
                            dsp += '<img src="' + url_image + '" class="img-responsive" style="width:200px;height:40px;">';
                        } else {
                            var image = "<?php echo site_url(); ?>" + row.branch_logo;
                            dsp += '<img src="' + image + '" class="img-responsive" style="width:200px;height:40px;">';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'branch_address',
                    className: 'text-left',
                    render: function (meta, data, row) {
                        var dsp = '';
                        dsp += '<b>' + row.branch_name + '</b><br>';
                        dsp += '' + row.specialist_name + '';
                        if (row.branch_code != undefined) {
                            dsp += '<br><label class="label">' + row.branch_code + '</label>';
                        }
                        dsp += '<br><br>' + row.branch_address + '<br>';
                        dsp += row.branch_district + ', ';
                        dsp += row.branch_city + ', ';
                        dsp += row.branch_province + '<br>';
                        return dsp;
                    }
                }, {
                    'data': 'branch_email_1',
                    className: 'text-left',
                    render: function (meta, data, row) {
                        var dsp = '';
                        dsp += row.branch_phone_1 + '<br>';
                        dsp += row.branch_email_1 + '<br>';
                        return dsp;
                    }
                }, {
                    'data': 'user_username',
                    className: 'text-left',
                    render: function (meta, data, row) {
                        var dsp = '';
                        if(row.branch_user_id != undefined){
                            dsp += row.user_username + '<br>';
                            dsp += row.user_fullname + '<br>';
                            dsp += row.user_phone_1 + '<br>';
                            dsp += row.user_email_1 + '<br>';
                            dsp += row.user_group_name + '<br>';
                        }else{
                            dsp += '-';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'branch_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';
                        dsp += '<button style="margin-bottom:4px;" class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button><br>';

                        if (parseInt(row.branch_flag) === 1) {
                            dsp += '<button class="btn btn-set-active btn-mini btn-success"';
                            dsp += 'data-nama="' + row.branch_name + '" data-kode="' + row.branch_code + '" data-id="' + data + '" data-flag="' + row.branch_flag + '">';
                            dsp += '<span class="fas fa-archway primary"></span> Aktif</button>';
                        } else {
                            dsp += '<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.branch_name + '" data-kode="' + row.branch_code + '" data-id="' + data + '" data-flag="' + row.branch_flag + '">';
                            dsp += '<span class="fas fa-archway danger"></span> Nonaktif</button>';
                        }

                        return dsp;
                    }
                }]
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
            console.log('Showing page: ' + info.page + ' of ' + info.pages);
            console.log(limit_start, limit_end);
            $("#table-data-in").attr('data-limit-start', limit_start);
            $("#table-data-in").attr('data-limit-end', limit_end);
        });

        $('#specialist').select2({
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
                        source: 'specialist'
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
        $('#user').select2({
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
                        not_used: true,
                        source: 'users'
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
        $('#provinsi').select2({
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
                        source: 'provinces'
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
                    var province_id = $("#provinsi").find(':selected').val();
                    if (parseInt(province_id) > 0) {
                        var query = {
                            search: params.term,
                            province_id: $("#provinsi").find(':selected').val(),
                            source: 'cities'
                        }
                        return query;
                    } else {
                        notif(0, 'Masukkan Provinsi terlebih dahulu');
                    }
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
                // $(data.element).attr('data-province-id', data.province_id);  
                // $(data.element).attr('data-province-name', data.province_name);      
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#kecamatan').select2({
            placeholder: '--- Pilih Kota / Kabupaten ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    // var province_id = $("#provinsi").find(':selected').val(),
                    var city_id = $("#kota").find(':selected').val();
                    if (parseInt(city_id) > 0) {
                        var query = {
                            search: params.term,
                            province_id: $("#provinsi").find(':selected').val(),
                            city_id: $("#kota").find(':selected').val(),
                            source: 'districts'
                        }
                        return query;
                    } else {
                        notif(0, 'Masukkan Kota / Kabupaten terlebih dahulu');
                    }
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
                // $(data.element).attr('data-province-id', data.province_id);  
                // $(data.element).attr('data-province-name', data.province_name);    
                // $(data.element).attr('data-city-id', data.city_id);  
                // $(data.element).attr('data-city-name', data.city_name);                 
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });

        $('#filter_specialist').select2({
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
                        source: 'specialist'
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
        $('#filter_province').select2({
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
                        source: 'provinces'
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
        $('#filter_city').select2({
            placeholder: '--- Pilih Kota / Kabupaten ---',
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var province_id = $("#provinsi").find(':selected').val();
                    if (parseInt(province_id) > 0) {
                        var query = {
                            search: params.term,
                            province_id: $("#provinsi").find(':selected').val(),
                            source: 'cities'
                        }
                        return query;
                    } else {
                        notif(0, 'Masukkan Provinsi terlebih dahulu');
                    }
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
                // $(data.element).attr('data-province-id', data.province_id);  
                // $(data.element).attr('data-province-name', data.province_name);      
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });

        $("#provinsi").on('change', function (e) {
            $("select[id='kota']").val(0).trigger('change');
            $("select[id='kecamatan']").val(0).trigger('change');
        });
        $("#kota").on('change', function (e) {
            $("select[id='kecamatan']").val(0).trigger('change');
        });
        $("#kecamatan").on('change', function (e) {
        });
        $("#filter_specialist, #filter_province, #filter_city").on('change', function (e) {
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
            formCancel();
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

            var nama = $("#form-master input[name='nama']");

            if (next == true) {
                if ($("input[id='nama']").val().length == 0) {
                    notif(0, 'Nama Cabang wajib diisi');
                    $("#nama").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='specialist']").find(":selected").val() == 0) {
                    notif(0, 'Jenis usaha harus diisi');
                    next = false;
                }
            }

            if (next == true) {
                /*
                if ($("select[id='user']").find(":selected").val() == 0) {
                    notif(0, 'Penanggung jawab harus diisi');
                    next = false;
                }
                */
            }

            if (next == true) {
                if ($("input[id='telepon_1']").val().length == 0) {
                    notif(0, 'Telepon wajib diisi');
                    $("#telepon_1").focus();
                    next = false;
                }
            }

            if (next == true) {
                // if ($("input[id='email_1']").val().length == 0) {
                //     notif(0, 'Email wajib diisi');
                //     $("#email_1").focus();
                //     next = false;
                // }
            }

            if (next == true) {
                if ($("textarea[id='alamat']").val().length == 0) {
                    notif(0, 'Alamat wajib diisi');
                    $("#alamat").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='provinsi']").find(":selected").val() == 0) {
                    notif(0, 'Provinsi harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='kota']").find(":selected").val() == 0) {
                    notif(0, 'Kota harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='kecamatan']").find(":selected").val() == 0) {
                    notif(0, 'Kecamatan harus dipilih');
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
                form.append('telepon_1', $('#telepon_1').val());
                form.append('email_1', $('#email_1').val());
                form.append('alamat', $('#alamat').val());
                form.append('status', $('#status').find(':selected').val());
                form.append('specialist', $('#specialist').find(':selected').val());
                form.append('user', $('#user').find(':selected').val());
                form.append('provinsi', $('#provinsi').find(':selected').val());
                form.append('kota', $('#kota').find(':selected').val());
                form.append('kecamatan', $('#kecamatan').find(':selected').val());
                // form.append('with_stock', $('#with_stock').find(':selected').val());
                // form.append('with_journal', $('#with_journal').find(':selected').val());
                form.append('upload1', $("#files_preview").attr('data-save-img'));
                
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
                        notif(1,'Proses menambahkan');
                    },
                    success: function (d){
                        if (parseInt(d.status) == 1) {
                            notif(1, d.message);
                            index.ajax.reload();
                            $("#btn-save").attr('disabled',false);
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
                tipe: identity,
                id: id
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {
                },
                success: function (d) {
                    if (parseInt(d.status) == 1) { /* Success Message */
                        //activeTab('tab1'); // Open/Close Tab By ID
                        // notif(1,d.result.contact_id);
                        $("#kode").attr('readonly', true);
                        $("#form-master input[id='id_document']").val(d.result.branch_id);
                        $("#form-master input[name='kode']").val(d.result.branch_code);
                        $("#form-master input[name='nama']").val(d.result.branch_name);
                        $("#form-master input[name='telepon_1']").val(d.result.branch_phone_1);
                        $("#form-master input[name='email_1']").val(d.result.branch_email_1);
                        $("#form-master textarea[name='alamat']").val(d.result.branch_address);
                        $("#form-master select[name='status']").val(d.result.branch_flag).trigger('change');

                        // if (parseInt(d.result.branch_logo) == 0) {
                        //     $('#img-preview1').attr('src', url_image);
                        // } else {
                        //     var image = "<?php echo site_url(); ?>" + d.result.branch_logo;
                        //     $('#img-preview1').attr('src', image);
                        // }

                        if (parseInt(d.result.branch_logo) == 0) {
                            // $('#img-preview1').attr('src', url_image);
                            $("#files_preview").attr('src',url_image);
                            $(".files_link").attr('href',url_image);                            
                        } else {
                            var image = "<?php echo base_url(); ?>" + d.result.branch_logo;
                            // $('#img-preview1').attr('src', image);
                            $("#files_preview").attr('src',image);
                            $(".files_link").attr('href',image);                            
                        }

                        $("select[name='specialist']").append('' +
                                '<option value="' + d.result.specialist_id + '">' +
                                d.result.specialist_name +
                                '</option>');
                        $("select[name='specialist']").val(d.result.specialist_id).trigger('change');

                        $("select[name='user']").append('' +
                                '<option value="' + d.result.user_id + '">' +
                                d.result.user_username + ' - ' + d.result.user_group_name +
                                '</option>');
                        $("select[name='user']").val(d.result.user_id).trigger('change');

                        $("select[id='provinsi']").append('' +
                                '<option value="' + d.result.branch_province_id + '">' +
                                d.result.branch_province +
                                '</option>');
                        $("select[id='provinsi']").val(d.result.branch_province_id).trigger('change');

                        $("select[id='kota']").append('' +
                                '<option value="' + d.result.branch_city_id + '">' +
                                d.result.branch_city +
                                '</option>');
                        $("select[id='kota']").val(d.result.branch_city_id).trigger('change');

                        $("select[id='kecamatan']").append('' +
                                '<option value="' + d.result.branch_district_id + '">' +
                                d.result.branch_district +
                                '</option>');
                        $("select[id='kecamatan']").val(d.result.branch_district_id).trigger('change');

                        // $("select[id='with_stock']").val(d.result.branch_transaction_with_stock).trigger('change');
                        // $("select[id='with_journal']").val(d.result.branch_transaction_with_journal).trigger('change');

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
            var nama = $("#form-master input[name='nama']");

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

            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (next == true) {
                if ($("select[id='specialist']").find(":selected").val() == 0) {
                    notif(0, 'Jenis usaha harus diisi');
                    next = false;
                }
            }

            if (next == true) {
                /*
                if ($("select[id='user']").find(":selected").val() == 0) {
                    notif(0, 'Penanggung jawab harus diisi');
                    next = false;
                }
                */
            }


            if (next == true) {
                if ($("input[id='telepon_1']").val().length == 0) {
                    notif(0, 'Telepon wajib diisi');
                    $("#telepon_1").focus();
                    next = false;
                }
            }

            if (next == true) {
                // if ($("input[id='email_1']").val().length == 0) {
                //     notif(0, 'Email wajib diisi');
                //     $("#email_1").focus();
                //     next = false;
                // }
            }

            if (next == true) {
                if ($("textarea[id='alamat']").val().length == 0) {
                    notif(0, 'Alamat wajib diisi');
                    $("#alamat").focus();
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='provinsi']").find(":selected").val() == 0) {
                    notif(0, 'Provinsi harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='kota']").find(":selected").val() == 0) {
                    notif(0, 'Kota harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='kecamatan']").find(":selected").val() == 0) {
                    notif(0, 'Kecamatan harus dipilih');
                    next = false;
                }
            }

            if (next == true) {
                var form = new FormData();
                form.append('action', 'update');
                form.append('id', $('#id_document').val());
                // form.append('upload1', $('#upload1')[0].files[0]);
                form.append('tipe', identity);
                form.append('nama', $('#nama').val());
                form.append('telepon_1', $('#telepon_1').val());
                form.append('email_1', $('#email_1').val());
                form.append('alamat', $('#alamat').val());
                form.append('status', $('#status').find(':selected').val());
                form.append('specialist', $('#specialist').find(':selected').val());
                form.append('provinsi', $('#provinsi').find(':selected').val());
                form.append('kota', $('#kota').find(':selected').val());
                form.append('kecamatan', $('#kecamatan').find(':selected').val());
                form.append('user', $('#user').find(':selected').val());
                // form.append('with_stock', $('#with_stock').find(':selected').val());
                // form.append('with_journal', $('#with_journal').find(':selected').val());
                form.append('upload1', $("#files_preview").attr('data-save-img'));
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: "json",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        notif(1,'Sedang memperbarui');
                    },
                    success: function(d){
                        if (parseInt(d.status) == 1) {
                            // $("#btn-new").show();
                            // $("#btn-save").hide();
                            // $("#btn-update").hide();
                            // $("#btn-cancel").hide();
                            $("#form-master input").val();
                            // formMasterSetDisplay(1);      
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
                title: 'Apakah anda ingin <b>' + msg + '</b> cabang <b>' + user + '</b> ?',
                content: 'Perubahan aktif menjadi nonaktif akan mengakibatkan user tidak dapat masuk ke sistem',
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

        // $('#upload1').change(function (e) {
        //     var fileName = e.target.files[0].name;
        //     var reader = new FileReader();
        //     reader.onload = function (e) {
        //         $('#img-preview1').attr('src', e.target.result);
        //     };
        //     reader.readAsDataURL(this.files[0]);
        // });
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
        }
        function formCancel() {
            formMasterSetDisplay(1);
            $("#form-master input").val('');
            $("#form-master textarea").val('');            
            $("#form-master select").val(0).trigger('change');            
            $("#btn-new").css('display', 'inline');

            $("#files_link").attr('href',url_image);
            $("#files_preview").attr('src',url_image);
            $("#files_preview").attr('data-save-img','');
                    
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
            "nama",
            "kode",
            "telepon_1",
            "email_1",
        ];
        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            "alamat",
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "status",
            "specialist",
            "user",
            "provinsi", "kota", "kecamatan" 
            // "with_stock", "with_journal"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>