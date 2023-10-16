
<script>
    $.getScript("https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js");

    $(document).ready(function () {
        // $("#modal-read").modal('show');
        var url = "<?= base_url('message'); ?>";
        // var url_image = '<?= base_url('assets/webarch/img/default-user-image.png'); ?>';
        var url_image = '<?= base_url('upload/noimage.png'); ?>';
        var url_preview = '<?php echo site_url(); ?>' + 'blog/';
        var view = "<?php echo $_view; ?>";
        
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="' + view + '"]').addClass('active');
        // console.log(view);
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
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalCharacterAlternative: ',',
            decimalPlaces: 0,
            watchExternalChanges: true //!!!        
        };
        // new AutoNumeric('#harga_jual', autoNumericOption);
        // new AutoNumeric('#harga_beli', autoNumericOption);

        setTimeout(() => {
            $('#teks').summernote({
                placeholder: 'Isi Pesan email disini',
                // airMode:true,
                tabsize: 4,
                height: 350,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link"]],
                    ["view", ["fullscreen", "codeview", "help"]]
                ]                
            });
            // ["insert", ["link", "picture", "video"]],
        }, 3000);

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
                    d.length = $("#filter_length").find(':selected').val();
                    d.platform = $("#filter_platform").find(':selected').val();
                    d.contact = $("#filter_contact").find(':selected').val();
                    d.flag = $("#filter_flag").find(':selected').val();
                    d.search = {
                        value: $("#filter_search").val()
                    };
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                {"targets": 0, "title": "Tanggal", "searchable": true, "orderable": false},
                {"targets": 1, "title": "Pesan", "searchable": true, "orderable": false},
                {"targets": 2, "title": "Tujuan", "searchable": true, "orderable": false},
                {"targets": 3, "title": "Status", "searchable": true, "orderable": false, "className": 'dt-body-right'},
                {"targets": 4, "title": "Action", "searchable": true, "orderable": false, "className": 'dt-body-right'}
            ],
            "order": [
                [0, 'desc']
            ],
            "columns": [{
                    'data': 'message_date_created',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += moment(row.message_date_created).format("DD-MMM-YYYY HH:mm")+'</br>';
                        if(row.message_platform ==1){
                            dsp += '<span class="label btn-label" style="cursor:pointer;color:white;background-color:#0aa699;padding:1px 4px;">WhatsApp</span>';
                        }else if(row.message_platform == 4){
                            dsp += '<span class="label btn-label btn-danger" style="cursor:pointer;color:white;padding:1px 4px;">Email</span>';
                        }                        
                        return dsp;
                    }
                }, {
                    'data': 'message_text',
                    render: function (data, meta, row) {
                        var dsp = row.message_text;
                        return dsp.substring(0, 40);
                    }
                }, {
                    'data': 'message_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if(row.message_platform==1){
                            dsp += row.message_contact_number;
                        }else if(row.message_platform==4){
                            dsp += row.message_contact_email;
                        }
                        if(row.message_contact_name != undefined) {
                            dsp += '<br>&lt;' + row.message_contact_name + '&gt;';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'message_id',
                    className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (parseInt(row.message_flag) == 0) {
                            dsp += 'Antrian Pengiriman';
                        } else if (parseInt(row.message_flag) == 1) {
                            dsp += 'Terkirim';
                        } else if (parseInt(row.message_flag) == 2) {
                            dsp += 'Proses';
                        } else if (parseInt(row.message_flag) == 4) {
                            dsp += 'Gagal Kirim';
                        } else {
                            dsp += '';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'message_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += '&nbsp;<button class="btn btn-edit btn-mini btn-info"';
                        dsp += 'data-nama="' + row.news_title + '" data-id="' + data + '" data-flag="' + row.news_flag + '">';
                        dsp += '<span class="fas fa-eye"></span> Lihat</button>';
                        if (parseInt(row.news_flag) === 1) {
                            // dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            // dsp += 'data-nama="'+row.news_title+'" data-kode="'+row.news_title+'" data-id="'+data+'" data-flag="'+row.news_flag+'">';
                            // dsp += '<span class="fas fa-check-square primary"></span> Publish</button>';
                        } 
                        // else {
                            // dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-default"';
                            // dsp += 'data-nama="'+row.news_title+'" data-kode="'+row.news_title+'" data-id="'+data+'" data-flag="'+row.news_flag+'">';
                            // dsp += '<span class="fas fa-times danger"></span> Unpublish</button>';
                        // }
                        // dsp += '&nbsp;<button class="btn btn-preview btn-mini btn-info"';
                        // dsp += 'data-nama="'+row.news_title+'" data-url="'+row.news_url+'" data-id="'+data+'" data-flag="'+row.news_flag+'" data-category="'+row.category_url+'">';
                        // dsp += '<span class="fas fa-eye"></span> Preview</button>';

                        return dsp;
                    }
                }]
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
            }
        });

        $('#branch').select2({
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
                        source: 'branchs'
                    };
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
        $('#contact').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Pilih Kontak --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        // tipe: 2,
                        source: 'contacts_instant_messaging'
                    };
                    return query;
                },
                processResults: function (data) {
                    var datas = [];
                    var platform = $("#platform").find(':selected').val();
                    $.each(data, function(key, val){
                        var teks = val.text;
                        if(parseInt(platform) == 1){
                            teks = val.text;
                            var ph = val.telepon;
                            if(ph.length > 2){
                                datas.push({
                                    'id' : val.id,
                                    'text' : teks,
                                    'text2' : teks + ' <'+val.nama+'>',
                                    'nama' : val.nama,
                                    'telepon' : val.telepon,
                                    'email' : val.email
                                });
                            }                            
                        }if(parseInt(platform) == 4){
                            teks = val.email + ' <'+val.nama+'>';
                            var em = val.email;
                            if(em.length > 2){
                                datas.push({
                                    'id' : val.id,
                                    'text' : teks,
                                    'text2' : teks + ' <'+val.nama+'>',
                                    'nama' : val.nama,
                                    'telepon' : val.telepon,
                                    'email' : val.email
                                });
                            }
                        }
                    });
                    return {
                        results: datas
                    };
                },
                cache: true
            },
            templateResult: function(datas){ //When Select on Click
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },            
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                $(data.element).attr('data-contact-id', data.id);
                $(data.element).attr('data-contact-name', data.nama);
                $(data.element).attr('data-contact-phone', data.telepon);
                $(data.element).attr('data-contact-email', data.email);
                // $("input[name='satuan']").val(data.satuan);
                if($.isNumeric(data.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return data.text;
                }
            }
        });
        $('#contact_type').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Pilih Kontak --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'contacts_types',
                        // platform: $("#platform").find(":selected").val()
                    };
                    return query;
                },
                processResults: function (data) {
                    var datas = [];
                    var platform = $("#platform").find(':selected').val();
                    $.each(data, function(key, val){
                        var teks = val.text;
                        var total = addCommas(val.total);
                        datas.push({
                            'id' : val.id,
                            'text' : teks + ' ['+total+' data]',
                            'total' : val.total
                        });
                    });
                    return {
                        results: datas
                    };                    
                },
                cache: true
            },
            templateResult: function(datas){ //When Select on Click
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },              
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                $(data.element).attr('data-contact-id', data.id);               
                // $("input[name='satuan']").val(data.satuan);
                if($.isNumeric(data.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return data.text;
                }
            }
        });
        $('#contact_category').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Pilih Kontak --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'contacts_categories',
                        // platform: $("#platform").find(":selected").val()
                    };
                    return query;
                },
                processResults: function (data) {
                    var datas = [];
                    var platform = $("#platform").find(':selected').val();
                    $.each(data, function(key, val){
                        var teks = val.text;
                        var total = addCommas(val.total);
                        datas.push({
                            'id' : val.id,
                            'text' : teks + ' ['+total+' data]',
                            'total' : val.total
                        });
                    });
                    return {
                        results: datas
                    };                    
                },
                cache: true
            },
            templateResult: function(datas){ //When Select on Click
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },              
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                $(data.element).attr('data-contact-id', data.id);               
                // $("input[name='satuan']").val(data.satuan);
                if($.isNumeric(data.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return data.text;
                }
            }
        });
        $('#recipient_group').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Pilih Kontak --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'recipients_groups',
                        // platform: $("#platform").find(":selected").val()
                    };
                    return query;
                },
                processResults: function (data) {
                    var datas = [];
                    var platform = $("#platform").find(':selected').val();
                    $.each(data, function(key, val){
                        var teks = val.text;
                        var total = addCommas(val.total);
                        datas.push({
                            'id' : val.id,
                            'text' : teks + ' ['+total+' data]',
                            'total' : val.total
                        });
                    });
                    return {
                        results: datas
                    };                    
                },
                cache: true
            },
            templateResult: function(datas){ //When Select on Click
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },              
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-contact-id', data.id);               
                // $("input[name='satuan']").val(data.satuan);
                if($.isNumeric(data.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return data.text;
                }
            }
        });
        $('#recipient_birthday').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Pilih Kontak --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        source: 'recipients_birthday',
                        // platform: $("#platform").find(":selected").val()
                    };
                    return query;
                },
                processResults: function (data) {
                    var datas = [];
                    var platform = $("#platform").find(':selected').val();
                    $.each(data, function(key, val){
                        var teks = val.text;
                        datas.push({
                            'id' : val.id,
                            'text' : teks
                        });
                    });
                    return {
                        results: datas
                    };                    
                },
                cache: true
            },
            templateResult: function(datas){ //When Select on Click
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;
                }
            },              
            templateSelection: function (data, container) {
                // Add custom attributes to the <option> tag for the selected option
                // $(data.element).attr('data-contact-id', data.id);               
                // $("input[name='satuan']").val(data.satuan);
                if($.isNumeric(data.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return data.text;
                }
            }
        });                        
        $('#device').select2({
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
                        source: 'devices',
                        tipe: $("#platform").find(':selected').text()
                    };
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
                // $(data.element).attr('data-contact-id', data.id);
                // $(data.element).attr('data-contact-name', data.nama);
                // $(data.element).attr('data-contact-phone', data.telepon);            
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });
        $('#broadcast_device').select2({
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
                        source: 'devices',
                        tipe: 'WhatsApp'
                    };
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
                // $(data.element).attr('data-contact-id', data.id);
                // $(data.element).attr('data-contact-name', data.nama);
                // $(data.element).attr('data-contact-phone', data.telepon);            
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        }); 
        $('#broadcast_device_email').select2({
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
                        source: 'devices',
                        tipe: 'Email'
                    };
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
                // $(data.element).attr('data-contact-id', data.id);
                // $(data.element).attr('data-contact-name', data.nama);
                // $(data.element).attr('data-contact-phone', data.telepon);            
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
            }
        });                
        $('#filter_contact').select2({
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
                        source: 'contacts_instant_messaging'
                    };
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
        $('#news').select2({
            // tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Tanpa Template Pesan --'
            },
            allowClear: true,
            // minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2,
                        source: 'news'
                    };
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
                    return datas.text;
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
        $('#broadcast_news').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Tanpa Template Pesan --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2,
                        source: 'news'
                    };
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
                    return datas.text;
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
        $('#broadcast_news_email').select2({
            tags:true,
            minimumInputLength: 0,
            placeholder: {
                id: '0',
                text: '-- Tanpa Template Pesan --'
            },
            allowClear: true,
            minimumResultsForSearch: Infinity,
            ajax: {
                type: "get",
                url: "<?= base_url('search/manage'); ?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 2,
                        source: 'news'
                    };
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
                    return datas.text;
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
        $(document).on("change", "#filter_contact", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_flag", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_platform", function (e) {
            index.ajax.reload();
        });        
        $(document).on("change", "#branch", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).find(':selected').val();
            $("#contact-label").html('Kontak');
            if (parseInt(id) > 0) {
                var data = {
                    action: 'contact-count',
                    branch: id
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    success: function (d) {
                        if (parseInt(d.status) === 1) {
                            notif(d.status, d.message);
                            // $("select[id='contact']").append('<option value="0">'+d.result+' Kontak Terpilih</option>');
                            // $("select[id='contact']").val(0).trigger('change');
                            $("#contact-label").html('Kontak terpilih ' + d.result + ' data');
                        }
                    }
                });

            }
        });
        $(document).on("change", "#contact", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).find(':selected').val();

            if (parseInt(id) > 0) {
                $("#branch").select2('val', 0);
                $("#branch").val(0).trigger('change');
            }

            var count = 0;
            var selected = $("#contact").val();
            count = selected.length;
            console.log(count);
            if (parseInt(count) > 0) {
                $("#contact-label").html('Kontak ' + selected.length + ' terpilih');
            } else {
                $("#contact-label").html('Kontak');
            }
        });
        $(document).on("change", "#news", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).find(':selected').val();
            var data = {
                action: "read",
                id: id
            };
            $.ajax({
                type: "post",
                url: "<?= base_url('news/manage'); ?>",
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {},
                success: function (d) {
                    if(parseInt(d.status) === 1) {
                        notif(d.message);
                        $("#teks").val(d.result.news_content);
                        var markupStr = d.result.news_content;
                        $('#teks').summernote('code', markupStr);                          
                        var img = (d['result']['news_image'] !== null) ? d['result']['news_image'] : '';
                        // alert(img.length);
                        if(parseInt(img.length) > 0) {
                            var set_url = "<?php base_url(); ?>" + img;
                            $("#img-preview1").attr('src', set_url);
                        } else {
                            $("#img-preview1").attr('src', url_image);
                        }
                    } else { //No Data
                        notif(d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0,err);
                }
            });
        });
        $(document).on("change", "#broadcast_news", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).find(':selected').val();
            var data = {
                action: "read",
                id: id
            };
            $.ajax({
                type: "post",
                url: "<?= base_url('news/manage'); ?>",
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {},
                success: function (d) {
                    if(parseInt(d.status) === 1) {
                        notif(d.message);
                        $("#broadcast_text").val(d.result.news_content);
                        // var img = (d['result']['news_image'] !== '') ? d['result']['news_image'] : '0';
                        // alert(img); return;
                        // if(parseInt(img.length) > 0) {
                        //     var set_url = "<?php base_url(); ?>" + img;
                        //     $("#img-preview1").attr('src', set_url);
                        // } else {
                        //     $("#img-preview1").attr('src', url_image);
                        // }
                    } else { //No Data
                        notif(d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0,err);
                }
            });
        });
        $(document).on("change", "#broadcast_news_email", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var id = $(this).find(':selected').val();
            var data = {
                action: "read",
                id: id
            };
            $.ajax({
                type: "post",
                url: "<?= base_url('news/manage'); ?>",
                data: data,
                dataType: 'json',
                cache: 'false',
                beforeSend: function () {},
                success: function (d) {
                    if(parseInt(d.status) === 1) {
                        notif(d.message);
                        $("#broadcast_text_email").val(d.result.news_content);
                        // var img = (d['result']['news_image'] !== '') ? d['result']['news_image'] : '0';
                        // alert(img); return;
                        // if(parseInt(img.length) > 0) {
                        //     var set_url = "<?php base_url(); ?>" + img;
                        //     $("#img-preview1").attr('src', set_url);
                        // } else {
                        //     $("#img-preview1").attr('src', url_image);
                        // }
                    } else { //No Data
                        notif(d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0,err);
                }
            });
        });         
        $(document).on("change", "#device", function (e) {
            e.preventDefault();
            e.stopPropagation();
            /*
             var id = $(this).find(':selected').val();
             if(parseInt(id) > 0){
             var data = {
             action: 'whatsapp-scan-qrcode'
             };
             $.ajax({
             type: "post",
             url: url,
             data: data,
             dataType: 'json',
             cache: 'false',    
             beforeSend:function(){},
             success:function(d){
             if(parseInt(d.status)==0){
             // self.setTitle('WhatsApp Disconnect');
             // self.setContentAppend('<img src="'+ d.result.qrcode+'" class="img-responsive" style="margin:0 auto;">');
             // self.setContentAppend('<br><p style="text-align:center;">Silahkan Scan Menggunakan WhatsApp dari Smartphone/Tablet</p>');    
             // alert(d.message);
             $("#device").val(0).trigger('change');
             $.confirm({
             title: 'WhatsApp Tidak Terhubung / QR Code Request',
             columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
             closeIcon: true,
             closeIconClass: 'fas fa-times',  
             autoClose: 'button_1|5000',        
             animation:'zoom',
             closeAnimation:'bottom',
             animateFromElement:false,      
             content: function(){
             },
             onContentReady: function(){
             this.setTitle('WhatsApp Disconnect');
             this.setContentAppend('<img src="'+ d.result.qrcode+'" class="img-responsive" style="margin:0 auto;">');
             this.setContentAppend('<br><p style="text-align:center;">Silahkan Scan Menggunakan WhatsApp dari Smartphone/Tablet</p>');
             //this.setContentAppend('<div>Content ready!</div>');
             },
             buttons: {
             button_1: {
             text:'Tutup',
             btnClass: 'btn-primary',
             keys: ['enter'],
             action: function(){
             }
             }
             } 
             });
             }else{            
             // self.setContentAppend('<br><b><span class="fas fa-check"></span> ' + d.message+'<br>');
             // alert(d.message);          
             }         
             },
             error:function(xhr, Status, err){
             notif(0,err);
             }
             });
             } 
             */
        });
        $(document).on("change","#platform",function(e) {
            e.preventDefault();
            e.stopPropagation();
            $("#device option").html('');
            $("#device").val(0).trigger('change');
            // $('#contact').val(0).trigger('change'); //null
            if($(this).find(':selected').val() == 4){
                $("#div_subject").show(300);
            }else{
                $("#div_subject").hide(300);
            }
        });
        
        $(document).on("click", ".btn-save", function (e) {
            e.preventDefault();
            var next = true;
            var sent_mode = $(this).attr('data-action'); //0=later, 1=now
            var branch = 0;
            var contact_list = [];
            var contact_type_list = [];
            var contact_cat_list = [];            
            var recipient_group_list = [];
            var contact_birthday_list = [];
            
            if (next == true) { //Device
                if ($("select[id='device']").find(':selected').val() == 0) {
                    notif(0, 'Device wajib dipilih');
                    next = false;
                }
            }

            if (next == true) { //Kontak
                // if ($("select[id='contact']").find(':selected').val() == 0) {
                //     notif(0, 'Kontak wajib dipilih');
                //     next = false;
                // }
            }

            if (next == true) { //Platform
                // if ($("select[id='platform']").find(':selected').val() == 0) {
                //     notif(0, 'Platform wajib dipilih');
                //     next = false;
                // }
            }

            if (next == true) { //Teks
                if ($("textarea[id='teks']").val().length == 0) {
                    notif(0, 'Pesan harus di isi');
                    next = false;
                }
            }

            if (next == true) { //Teks
                if ($("textarea[id='teks']").val().length < 5) {
                    notif(0, 'Pesan minimal 5 karakter');
                    next = false;
                }
            }

            if (next) { //Contacts List
                if (parseInt($("#contact").find(':selected').val()) > 0) {
                    var contact = $("#contact").find(':selected').val();
                    var contact_length = contact.length;
                    // for(var i=1; i < contact_length+1; i++){
                    var list_item = $("#contact").val();
                    for (item of list_item) {
                        var push_contact = {
                            'contact_id': ($("#contact [value=" + item + "]").attr('data-contact-id') > 0) ? parseInt($("#contact [value=" + item + "]").attr('data-contact-id')) : '-',
                            'contact_name': ($("#contact [value=" + item + "]").attr('data-contact-name').length > 0) ? $("#contact [value=" + item + "]").attr('data-contact-name') : '-',
                            'contact_phone': ($("#contact [value=" + item + "]").attr('data-contact-phone').length > 0) ? $("#contact [value=" + item + "]").attr('data-contact-phone') : '-',
                            'contact_email': ($("#contact [value=" + item + "]").attr('data-contact-email').length > 0) ? $("#contact [value=" + item + "]").attr('data-contact-email') : '-',                            
                        };
                        contact_list.push(push_contact);
                    }
                }

                if (parseInt($("#contact_type").find(':selected').val()) > 0) {
                    var contact_type = $("#contact_type").find(':selected').val();
                    var contact_type_length = contact_type.length;
                    // for(var i=1; i < contact_length+1; i++){
                    var list_type_item = $("#contact_type").val();
                    for (item of list_type_item) {
                        var push_contact_type = {
                            'contact_type_id': $("#contact_type [value=" + item + "]").attr('value'),
                            'contact_type_name': $("#contact_type [value=" + item + "]").text()
                        };
                        contact_type_list.push(push_contact_type);
                    }
                }   

                if (parseInt($("#contact_category").find(':selected').val()) > 0) {
                    var contact_category = $("#contact_category").find(':selected').val();
                    var contact_category_length = contact_category.length;
                    // for(var i=1; i < contact_length+1; i++){
                    var list_cat_item = $("#contact_category").val();
                    for (item of list_cat_item) {
                        var push_contact_cat = {
                            'contact_type_id': $("#contact_category [value=" + item + "]").attr('value'),
                            'contact_type_name': $("#contact_category [value=" + item + "]").text()
                        };
                        contact_cat_list.push(push_contact_cat);
                    }
                }    

                if (parseInt($("#recipient_group").find(':selected').val()) > 0) {
                    var group_recipient = $("#recipient_group").find(':selected').val();
                    var group_recipient_length = group_recipient.length;
                    // for(var i=1; i < contact_length+1; i++){
                    var list_group_item = $("#recipient_group").val();
                    for (item of list_group_item) {
                        var push_recipient_group = {
                            'group_id': $("#recipient_group [value=" + item + "]").attr('value'),
                            'group_name': $("#recipient_group [value=" + item + "]").text()
                        };
                        recipient_group_list.push(push_recipient_group);
                    }
                }        

                if (parseInt($("#recipient_birthday").find(':selected').val()) > 0) {
                    var contact_bday = $("#recipient_birthday").find(':selected').val();
                    var contact_bday_length = contact_bday.length;
                    // for(var i=1; i < contact_length+1; i++){
                    var list_bday_item = $("#recipient_birthday").val();
                    for (item of list_bday_item) {
                        var push_contact_bday = {
                            'recipient_id': $("#recipient_birthday [value=" + item + "]").attr('value'),
                            'recipient_name': $("#recipient_birthday [value=" + item + "]").text()
                        };
                        contact_birthday_list.push(push_contact_bday);
                    }
                }                                                       
            }

            if(next){
                // var cl = (contact_list.length > 0) ? true : false;
                // var ctl = (contact_type_list.length > 0) ? true : false;
                // var ccl = (contact_cat_list.length > 0) ? true : false;
                // var rgl = (recipient_group_list.length > 0) ? true : false;
                // var rbl = (contact_birthday_list.length > 0) ? true : false;                                                                
                // alert(cl+','+ctl+','+ccl+','+rgl+','+rbl);

                // if(contact_list.length == 0){
                //     next=false;
                // }
                
                // if(next){
                //     if(contact_type_list.length == 0){
                //         next=false;
                //     }
                // }
                
                // if(next){
                //     if(contact_cat_list.length == 0){
                //         next=false;
                //     }
                // }

                // if(next){    
                //     if(recipient_group_list.length == 0){
                //         next=false;
                //     }    
                // }

                // if(next){             
                //     if(contact_birthday_list.length == 0){
                //         next=false;
                //     }   
                // }

                // if(next==false){
                //     notif(0,'Minimal satu kontak diisi');
                //     return;
                // }
            }

            // console.log(contact_list);
            if (next == true) {
                var form = new FormData();
                form.append('action', 'create');
                form.append('upload1', $('#upload1')[0].files[0]);
                form.append('platform', $('#platform').find(':selected').val());
                form.append('device', $('#device').find(':selected').val());
                form.append('news', $('#news').find(':selected').val());
                form.append('subject', $('#subject').val());                
                form.append('text', $('#teks').val());
                form.append('branch', 0);
                form.append('contact_list', JSON.stringify(contact_list));
                form.append('contact_type_list', JSON.stringify(contact_type_list));
                form.append('contact_cat_list', JSON.stringify(contact_cat_list)); 
                form.append('recipient_group_list', JSON.stringify(recipient_group_list));
                form.append('recipient_birthday_list', JSON.stringify(contact_birthday_list));                                                                
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
        $(document).on("click", ".btn-edit", function (e) {
            formMasterSetDisplay(0);
            e.preventDefault();
            var id = $(this).attr("data-id");
            var data = {
                action: "read",
                id: id
            };
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'json',
                cache: false,
                beforeSend: function () {},
                success: function (d) {
                    if (parseInt(d.status) == 1) { /* Success Message */
                        // $("#div-form-trans").show(300);
                        // activeTab('tab1'); // Open/Close Tab By ID
                        // notif(1,d.result.id);ss
                        $("#message_session").val(d.result.message_session);
                        $("#message_date_created").val(d.result.message_date_created);
                        $("#message_date_sent").val(d.result.message_date_sent);
                        $("#message_status").val(d.result.message_flag).trigger('change');
                        $("#message_type").val(d.result.message_type).trigger('change');
                        $("#message_contact_number").val(d.result.message_contact_number);
                        $("#message_contact_name").val(d.result.message_contact_name);
                        $("#message_contact_email").val(d.result.message_contact_email);
                        $("#message_platform").val(d.result.message_platform).trigger('change');                        
                        $("#message_url").val(d.result.message_url);
                        $("#message_text").val(d.result.message_text);
                        $("#btn-whatsapp-send-message").attr('data-id', d.result.message_id);
                        $("#btn-email-send-message").attr('data-id', d.result.message_id);
                        // $("#form-master input[name='tags']").val(d.result.news_tags);
                        // $("#form-master input[name='keywords']").val(d.result.news_keywords);
                        // $("#form-master input[name='title']").val(d.result.news_title);                   
                        // $("#form-master input[name='url']").val(d.result.news_url);
                        // $("#form-master textarea[name='short']").val(d.result.news_short);
                        // var markupStr = d.result.news_content;
                        // $('#content-description').summernote('code', markupStr);
                        // if(parseInt(d.result.news_images) == 0) {
                        //   $('#img-preview1').attr('src', url_image);    
                        // }else{
                        //   $('#img-preview1').attr('src', d.result.news_image);
                        // }
                        // $("#form-master select[name='status']").val(d.result.news_flag).trigger('change');
                        // $("#form-master select[name='posisi']").val(d.result.news_position).trigger('change');          
                        var device_label = d.result.device_number;    
                        if(d.result.device_media == 'Email'){
                            device_label = d.result.device_mail_email;
                        }
                        // console.log(device_label+', '+d.result.device_id);
                        $("select[name='message_device']").append('' +'<option value="' + d.result.device_id + '">' + device_label +'</option>');
                        $("select[name='message_device']").val(d.result.device_id).trigger('change');

                        var img = d.result.message_url;
                        // if(parseInt(img.length) > 0){
                        if (d.result.message_url != undefined) {
                            var set_url = "<?php base_url(); ?>" + img;
                            $("#img-preview2").attr('src', set_url);
                        } else {
                            $("#img-preview2").attr('src', url_image);
                        }
                        // $("#btn-new").hide();
                        // $("#btn-save").hide();
                        // $("#btn-update").show();
                        // $("#btn-cancel").show();
                        // scrollUp('content');
                        $("#modal-read").modal({backdrop: 'static', keyboard: false});
                    } else {
                        notif(0, d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, 'Error');
                }
            });
        });
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
                            };
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
        $(document).on("click", "#btn-email-send-message", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var number = $("#message_contact_email").val();
            var name = $("#message_contact_name").val();
            var teks = $("#message_text").val();
            var next = true;
            if (parseInt(id) > 0) {
                if(number.length == 0){
                    notif(0,'Email wajib diisi');
                    $("#message_contact_email").focus();
                    next=false;
                }
                if(next){
                    $.confirm({
                        title: 'Kirim Pesan Email',
                        content: 'Apakah anda ingin mengirim ke email <b>' + number + '</b> dengan penerima <b>' + name + '</b> ?',
                        columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                        autoClose: 'button_2|10000',
                        closeIcon: true,
                        closeIconClass: 'fas fa-times',
                        buttons: {
                            button_1: {
                                text: '[Enter] Ok',
                                btnClass: 'btn-primary',
                                keys: ['enter'],
                                action: function () {
                                    var data = {
                                        action: 'email-send-message',
                                        id: id,
                                        nama: name,
                                        email: number,
                                        teks: JSON.stringify(teks)
                                    };
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
                                                $("#modal-read").modal('hide');
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
                                text: '[Esc] Batal',
                                btnClass: 'btn-danger',
                                keys: ['Escape'],
                                action: function () {
                                    //Close
                                }
                            }
                        }
                    });
                }
            } else {
                notif(0, 'Data tidak ditemukan');
            }
        });
        $(document).on("click", "#btn-whatsapp-send-message", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var number = $("#message_contact_number").val();
            var name = $("#message_contact_name").val();
            var teks = $("#message_text").val();
            var next = true;
            if (parseInt(id) > 0) {
                if(number.length == 0){
                    notif(0,'Nomor wajib diisi');
                    $("#message_contact_number").focus();
                    next=false;
                }
                if(next){                
                    $.confirm({
                        title: 'Kirim Pesan WhatsApp',
                        content: 'Apakah anda ingin mengirim ke nomor <b>' + number + '</b> dengan penerima <b>' + name + '</b> ?',
                        columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                        autoClose: 'button_2|10000',
                        closeIcon: true,
                        closeIconClass: 'fas fa-times',
                        buttons: {
                            button_1: {
                                text: '[Enter] Ok',
                                btnClass: 'btn-primary',
                                keys: ['enter'],
                                action: function () {
                                    var data = {
                                        action: 'whatsapp-send-message',
                                        id: id,
                                        nama: name,
                                        nomor: number,
                                        teks: JSON.stringify(teks)
                                    };
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
                                                $("#modal-read").modal('hide');
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
                                text: '[Esc] Batal',
                                btnClass: 'btn-danger',
                                keys: ['Escape'],
                                action: function () {
                                    //Close
                                }
                            }
                        }
                    });
                }
            } else {
                notif(0, 'Data tidak ditemukan');
            }
        });        
        $(document).on("click", "#btn-whatsapp-scan-qrcode", function (e) {
            e.preventDefault();
            e.stopPropagation();

        });
        $(document).on("click", "#btn-whatsapp-info", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $.confirm({
                title: 'WhatsApp Info Request',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                animation: 'zoom',
                closeAnimation: 'bottom',
                animateFromElement: false,
                content: function () {
                    var self = this;
                    var data = {
                        action: 'whatsapp-info'
                    };
                    return $.ajax({
                        url: url,
                        data: data,
                        dataType: 'json',
                        method: 'post',
                        cache: false
                    }).done(function (d) {
                        self.setContentAppend('<br>Authorization : <br><b>' + d.result.auth + '<br>');
                        self.setContentAppend('<br>Number : <br><b>' + d.result.sender + '<b><br>');
                        self.setContentAppend('<br>Info : <br><b>' + d.result.info + '</b><br>');
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
                    }).fail(function () {
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function () {
                    // this.setContentAppend('<div>Content ready!</div>');
                },
                buttons: {
                    button_1: {
                        text: 'Scan QR Code',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function () {
                            $.confirm({
                                title: 'WhatsApp QR Code Request',
                                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                                closeIcon: true,
                                closeIconClass: 'fas fa-times',
                                autoClose: 'button_1|5000',
                                animation: 'zoom',
                                closeAnimation: 'bottom',
                                animateFromElement: false,
                                content: function () {
                                    var self = this;
                                    var data = {
                                        action: 'whatsapp-scan-qrcode'
                                    };
                                    return $.ajax({
                                        url: url,
                                        data: data,
                                        dataType: 'json',
                                        method: 'post',
                                        cache: false
                                    }).done(function (d) {
                                        if (parseInt(d.status) == 1) {
                                            self.setTitle('WhatsApp Disconnect');
                                            self.setContentAppend('<img src="' + d.result.qrcode + '" class="img-responsive" style="margin:0 auto;">');
                                            self.setContentAppend('<p style="text-align:center;font-size:20px;"><b>' + d.sender + '</b></p>');
                                            self.setContentAppend('<br><p style="text-align:center;">Silahkan Scan Menggunakan WhatsApp dari Smartphone/Tablet</p>');
                                        } else {
                                            self.setContentAppend('<br><b><span class="fas fa-check"></span> ' + d.message + '<br>');
                                        }
                                        // self.setContentAppend('<br>Number : <br><b>' + d.result.token.sender+'<b><br>');
                                        // self.setContentAppend('<br>Expired : <br><b>' + d.result.token.expired+'</b><br>');
                                    }).fail(function () {
                                        self.setContent('Something went wrong, Please try again.');
                                    });
                                },
                                onContentReady: function () {
                                    //this.setContentAppend('<div>Content ready!</div>');
                                },
                                buttons: {
                                    button_1: {
                                        text: 'Tutup',
                                        btnClass: 'btn-primary',
                                        keys: ['enter'],
                                        action: function () {
                                        }
                                    }
                                }
                            });
                        }
                    },
                    button_2: {
                        text: 'Restart',
                        btnClass: 'btn-success',
                        keys: ['enter'],
                        action: function () {
                            document.getElementById("btn-whatsapp-scan-qrcode").click();
                        }
                    },
                    button_3: {
                        text: 'Tutup',
                        btnClass: 'btn-info',
                        keys: ['enter'],
                        action: function () {
                        }
                    }
                }
            });
        });
        $(document).on("click", ".btn-preview", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var title = $(this).attr("data-title");
            var urls = $(this).attr("data-url");
            var category = $(this).attr("data-category");
            var final_url = category + '/' + urls;
            // console.log(final_url);
            // $.alert('Harusnya Redirect to '+url_preview+urls);
            // window.open(url_preview+urls);
            $.confirm({
                title: 'Pengalihan Halaman',
                content: 'Anda akan diarahkan ke halaman <b>' + url_preview + final_url + '</b>',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times',
                buttons: {
                    button_1: {
                        text: 'Lanjutkan',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function () {
                            window.open(url_preview + final_url);
                        }
                    },
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
        $(document).on("click", "#btn-new", function (e) {
            formNew();
            // $("#div-form-trans").show(300);
            // $(this).hide();
            $("#modal-form").modal('show');            
        });
        $(document).on("click", "#btn-cancel", function (e) {
            e.preventDefault();
            // $("#btn-new").css('display', 'inline');
            // formTransCancel();
            $("#modal-form").modal('hide');            
            // btnNew.classList.remove('animate__animated', 'animate__fadeOutRight');    
            // $("#div-form-trans").hide(300);
        });
        $(document).on("click","#btn-excel",function(e) {
            $("#btn-new").css('display', 'inline');
            $("#div-form-trans").hide(300);

            var text = $("#teks").val().length;
            var current_text = $("#teks").val();

            $("#broadcast_text").val(current_text);
            var formData = new FormData();
            formData.append('action', 'request-session-for-broadcast');
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                dataType: 'json',
                cache: 'false',
                contentType: false,
                processData: false,
                beforeSend:function(){},
                success:function(d){
                    var s = d.status;
                    var m = d.message;
                    var r = d.result;
                    $("#broadcast_session").val(r); 
                },
                error:function(xhr, Status, err){
                    notif(0,err);
                }
            });
            $("#modal-excel").modal('toggle');
        });
        $(document).on("click","#btn-excel-save", function (e) {
            e.preventDefault();
            var next = true;

            if (next == true) { //Device
                if ($("select[id='broadcast_device']").find(':selected').val() == 0) {
                    notif(0, 'Device wajib dipilih');
                    next = false;
                }
            }

            if (next == true) { //Kontak not used
                // if ($("select[id='contact']").find(':selected').val() == 0) {
                //     notif(0, 'Kontak wajib dipilih');
                //     next = false;
                // }
            }

            if (next == true) { //Teks
                if ($("textarea[id='broadcast_text']").val().length == 0) {
                    notif(0, 'Teks harus di isi');
                    next = false;
                }
            }

            if (next == true) { //Teks 32 karakter
                if ($("textarea[id='broadcast_text']").val().length < 32) {
                    notif(0, 'Pesan minimal 32 karakter');
                    next = false;
                }
            }

            if (next == true) {
                var form = new FormData();
                form.append('action', 'create-whatsapp-excel-broadcast');    
                form.append('text', $('#teks').val());     
                form.append('broadcast_device', $('#broadcast_device').find(':selected').val());
                form.append('broadcast_text',$("#broadcast_text").val());    
                form.append('broadcast_session',$("#broadcast_session").val());      
                form.append('broadcast_file', $('#broadcast_file')[0].files[0]);  
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function (d) {
                        notif(1,'Sedang memproses');
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            // notif(1, d.message);
                            notif(1,'Broadcast '+d.broadcast.group_session+' berhasil');
                            confirmAfterBroadcastWhatsApp(d.broadcast.group_session);
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
        $(document).on("click","#btn-excel-email",function(e) {
            $("#btn-new").css('display', 'inline');
            $("#div-form-trans").hide(300);

            var text = $("#teks").val().length;
            var current_text = $("#teks").val();
            $("#broadcast_text").val(current_text);
            var formData = new FormData();
            formData.append('action', 'request-session-for-broadcast');
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                dataType: 'json',
                cache: 'false',
                contentType: false,
                processData: false,
                beforeSend:function(){},
                success:function(d){
                    var s = d.status;
                    var m = d.message;
                    var r = d.result;
                    $("#broadcast_session_email").val(r); 
                },
                error:function(xhr, Status, err){
                    notif(0,err);
                }
            });
            $("#modal-email-excel").modal('toggle');
        });   
        $(document).on("click","#btn-excel-email-save", function (e) {
            e.preventDefault();
            var next = true;

            if (next == true) { //Device
                if ($("select[id='broadcast_device_email']").find(':selected').val() == 0) {
                    notif(0, 'Device wajib dipilih');
                    next = false;
                }
            }

            if (next == true) { //Kontak not used
                // if ($("select[id='contact']").find(':selected').val() == 0) {
                //     notif(0, 'Kontak wajib dipilih');
                //     next = false;
                // }
            }

            if (next == true) { //Teks
                if ($("textarea[id='broadcast_text_email']").val().length == 0) {
                    notif(0, 'Teks harus di isi');
                    next = false;
                }
            }

            if (next == true) { //Teks 32 karakter
                if ($("textarea[id='broadcast_text_email']").val().length < 32) {
                    notif(0, 'Pesan minimal 32 karakter');
                    next = false;
                }
            }

            if (next == true) {
                var form = new FormData();
                form.append('action', 'create-email-excel-broadcast');    
                form.append('text', $('#teks').val());     
                form.append('broadcast_device', $('#broadcast_device_email').find(':selected').val());
                form.append('broadcast_text',$("#broadcast_text_email").val());    
                form.append('broadcast_session',$("#broadcast_session_email").val());      
                form.append('broadcast_file', $('#broadcast_file_email')[0].files[0]);   
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function (d) {
                        notif(1,'Sedang memproses');
                    },
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            // notif(1, d.message);
                            notif(1,'Broadcast '+d.broadcast.group_session+' berhasil');
                            confirmAfterBroadcastEmail(d.broadcast.group_session);
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
        function confirmAfterBroadcastWhatsapp(message_group_session){
            let title   = 'Sukses Mengirim WhatsApp';
            let content = 'Anda dapat tracking broadcast menggunakan Kode Tracking Broadcast '+message_group_session;
            $.confirm({
                title: title,
                icon: 'fas fa-check',
                content: content,
                columnClass: 'col-md-5 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'Lihat Broadcast '+message_group_session,
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            $("#modal-excel").modal('hide');
                            notif(1,'Mencari Data '+message_group_session);
                            $("#filter_search").val(message_group_session);
                            index.ajax.reload();
                        }
                    },
                    button_2: {
                        text: 'Tidak Perlu',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            $("#modal-excel").modal('hide');
                        }
                    }
                }
            });
        }
        function confirmAfterBroadcastEmail(message_group_session){
            let title   = 'Sukses Mengirim Email';
            let content = 'Anda dapat tracking broadcast menggunakan Kode Tracking Broadcast '+message_group_session;
            $.confirm({
                title: title,
                icon: 'fas fa-check',
                content: content,
                columnClass: 'col-md-5 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                buttons: {
                    button_1: {
                        text:'Lihat '+message_group_session,
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            $("#modal-email-excel").modal('hide');
                            notif(1,'Mencari Data '+message_group_session);
                            $("#filter_search").val(message_group_session);
                            index.ajax.reload();
                        }
                    },
                    button_2: {
                        text: 'Tidak Perlu',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            $("#modal-email-excel").modal('hide');
                        }
                    }
                }
            });        
        }

        //Summernote
        function disableSummernote(){
            $("#teks").summernote("disable");

            // Enables code button:
            $(".note-btn.btn-codeview").removeAttr("disabled").removeClass("disabled");

            // When switching from code to rich, toolbar buttons are clickable again, so we'll need to disable again in that case:
            $(".note-btn.btn-codeview").on("click", codeViewClick);

            // Disables code textarea:
            $("textarea.note-codable").attr("disabled", "disabled");
        }
        function enableSummernote(){
            // Re-enables edition and unbinds codeview button eventhandler
            $("#teks").summernote('enable');
            $(".note-btn.btn-codeview").off("click", codeViewClick);
        }
        function codeViewClickSummernote(){
            // If switching from code view to rich text, disables again the widget:
            if(!$(this).is(".active")){
                $("#teks").summernote("disable");
                $(".note-btn.btn-codeview").removeAttr("disabled").removeClass("disabled");
            }
        }               
    });

    function formNew() {
        formMasterSetDisplay(0);
        $("#form-master input").val();
        $("#form-master select").not("select[id='platform']").val(0).trigger('change');
        $('#teks').summernote('code', '');                
        // $("#btn-new").hide();
        // $(".btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form-master input").val();
        // $("#btn-new").show();
        // $(".btn-save").hide();
        $("#btn-update").hide();
        $("#btn-cancel").hide();
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
            // "namanya"
        ];

        for (var i = 0; i <= attrInput.length; i++) {
            $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        }

        //Attr Textarea yang perlu di setel
        var attrText = [
            "teks",
        ];
        for (var i = 0; i <= attrText.length; i++) {
            $("" + form + " textarea[name='" + attrText[i] + "']").attr('readonly', flag);
        }

        //Attr Select yang perlu di setel
        var atributSelect = [
            "branch",
            "device",
            "contact",
            "status",
            "platform"
        ];
        for (var i = 0; i <= atributSelect.length; i++) {
            $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        }
    }
</script>