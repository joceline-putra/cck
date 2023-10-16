
<script>
    $(document).ready(function () {
        var url = "<?= base_url('survey'); ?>";
        // var url_image = '<?= base_url('assets/webarch/img/default-user-image.png'); ?>';
        var url_image = '<?= base_url('upload/noimage.png'); ?>';
        var url_preview = '<?php echo site_url(); ?>' + 'blog/';
        var view = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="' + view + '"]').addClass('active');
        // console.log(view);
        $("#img-preview1").attr('src', url_image);
        $("select").select2();
        $(".date").datepicker({
            // defaultDate: new Date(),
            format: 'yyyy-mm-dd',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        });
        $("#start, #end").datepicker({
            // defaultDate: new Date(),
            format: 'dd-mm-yyyy',
            autoclose: true,
            enableOnReadOnly: true,
            language: "id",
            todayHighlight: true,
            weekStart: 1
        }).on('changeDate', function () {
            index.ajax.reload();
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
                    d.date_start = $("#start").val();
                    d.date_end = $("#end").val();
                    // d.tipe = identity;
                    // d.start = $("#table-data").attr('data-limit-start');
                    // d.length = $("#table-data").attr('data-limit-end');
                    d.length = $("#filter_length").find(':selected').val();
                    d.rating = $("#filter_rating").find(':selected').val();
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
                {"targets": 1, "title": "Kritik & Saran", "searchable": true, "orderable": false},
                {"targets": 2, "title": "Rating", "searchable": true, "orderable": false},
                {"targets": 3, "title": "Kontak", "searchable": true, "orderable": false, "className": 'dt-body-right'},
                {"targets": 4, "title": "Action", "searchable": true, "orderable": false, "className": 'dt-body-right'}
            ],
            "order": [
                [0, 'desc']
            ],
            "columns": [{
                    'data': 'survey_date_created',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.survey_date_created;
                        return dsp;
                    }
                }, {
                    'data': 'survey_note',
                    render: function (data, meta, row) {
                        var dsp = row.survey_note;
                        return dsp.substring(0, 40);
                    }
                }, {
                    'data': 'survey_rating',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        if (parseInt(row.survey_rating) == 5) {
                            dsp += '[5] Sangat Baik';
                        } else if (parseInt(row.survey_rating) == 4) {
                            dsp += '[4] Cukup Baik';
                        } else if (parseInt(row.survey_rating) == 3) {
                            dsp += '[3] Baik';
                        } else if (parseInt(row.survey_rating) == 2) {
                            dsp += '[2] Kurang Baik';
                        } else if (parseInt(row.survey_rating) == 1) {
                            dsp += '[1] Sangat Kurang Baik';
                        }
                        return dsp;
                    }
                }, {
                    'data': 'survey_id',
                    className: 'text-right',
                    render: function (data, meta, row) {
                        var dsp = '';
                        dsp += row.survey_contact_number + '<br>';
                        dsp += '<' + row.survey_contact_name + '>';
                        return dsp;
                    }
                }, {
                    'data': 'survey_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // dsp += '&nbsp;<button class="btn btn-edit btn-mini btn-primary"';
                        // dsp += 'data-nama="'+row.news_title+'" data-id="'+data+'" data-flag="'+row.news_flag+'">';          
                        // dsp += '<span class="fas fa-edit"></span> View</button>';          
                        if (parseInt(row.news_flag) === 1) {
                            // dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            // dsp += 'data-nama="'+row.news_title+'" data-kode="'+row.news_title+'" data-id="'+data+'" data-flag="'+row.news_flag+'">';
                            // dsp += '<span class="fas fa-check-square primary"></span> Publish</button>';
                        } else {
                            // dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-default"';
                            // dsp += 'data-nama="'+row.news_title+'" data-kode="'+row.news_title+'" data-id="'+data+'" data-flag="'+row.news_flag+'">';
                            // dsp += '<span class="fas fa-times danger"></span> Unpublish</button>';
                        }
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
        $("#filter_rating").on('change', function (e) {
            index.ajax.reload();
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
        $('#contact').select2({
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
                        tipe: 2,
                        source: 'contacts_instant_messaging'
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
                $(data.element).attr('data-contact-id', data.id);
                $(data.element).attr('data-contact-name', data.nama);
                $(data.element).attr('data-contact-phone', data.telepon);
                // $("input[name='satuan']").val(data.satuan);
                return data.text;
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
                        source: 'devices'
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

        $(document).on("change", "#filter_contact", function (e) {
            index.ajax.reload();
        });
        $(document).on("change", "#filter_flag", function (e) {
            index.ajax.reload();
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
                // $("#contact-label").html('Kontak '+selected.length+ ' terpilih'); 
            } else {
                // $("#contact-label").html('Kontak'); 
            }
        });
        $(document).on("change", "#device", function (e) {
            e.preventDefault();
            e.stopPropagation();

            var id = $(this).find(':selected').val();
            if (parseInt(id) > 0) {
                var data = {
                    action: 'whatsapp-scan-qrcode'
                };
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 0) {
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
                                animation: 'zoom',
                                closeAnimation: 'bottom',
                                animateFromElement: false,
                                content: function () {
                                },
                                onContentReady: function () {
                                    this.setTitle('WhatsApp Disconnect');
                                    this.setContentAppend('<img src="' + d.result.qrcode + '" class="img-responsive" style="margin:0 auto;">');
                                    this.setContentAppend('<br><p style="text-align:center;">Silahkan Scan Menggunakan WhatsApp dari Smartphone/Tablet</p>');
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
                        } else {
                            // self.setContentAppend('<br><b><span class="fas fa-check"></span> ' + d.message+'<br>');
                            // alert(d.message);          
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            }
        });

        // Save Button (Send Now = 1 & Send Later = 0)
        $(document).on("click", ".btn-save", function (e) {
            e.preventDefault();
            var next = true;
            var sent_mode = $(this).attr('data-action'); //0=later, 1=now
            var branch = 0;
            var contact_list = [];

            if (next == true) {
                if ($("select[id='device']").find(':selected').val() == 0) {
                    notif(0, 'Device wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='contact']").find(':selected').val() == 0) {
                    notif(0, 'Kontak wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("select[id='platform']").find(':selected').val() == 0) {
                    notif(0, 'Platform wajib dipilih');
                    next = false;
                }
            }

            if (next == true) {
                if ($("textarea[id='teks']").val().length == 0) {
                    notif(0, 'Pesan harus di isi');
                    next = false;
                }
            }

            if (next == true) {
                if ($("textarea[id='teks']").val().length < 32) {
                    notif(0, 'Pesan minimal 32 karakter');
                    next = false;
                }
            }

            // console.log(contact_list);
            if (next == true) {
                // var prepare = {
                //   title: $("input[id='title']").val(),
                //   tags: $("input[id='tags']").val(),
                //   keywords: $("input[id='keywords']").val(),
                //   url: $("input[id='url']").val(),                
                //   content: $("textarea[id='content']").val(),
                //   categories: $("select[id='categories']").find(':selected').val(),
                //   status: $("select[id='status']").find(':selected').val()
                // }

                var form = new FormData();
                form.append('action', 'create');
                // form.append('upload1', $('#upload1')[0].files[0]);
                form.append('platform', $('#platform').find(':selected').val());
                form.append('text', $('#teks').val());
                // form.append('branch', $('#branch').find(':selected').val());
                form.append('contact_id', $('#contact').find(':selected').attr('data-contact-id'));
                form.append('contact_name', $('#contact').find(':selected').attr('data-contact-name'));
                form.append('contact_number', $('#contact').find(':selected').attr('data-contact-phone'));
                // form.append('contact_list',JSON.stringify(contact_list));
                // form.append('news', $('#news').find(':selected').val());      
                form.append('device', $('#device').find(':selected').val());
                form.append('send_now', sent_mode);
                // var prepare_data = JSON.stringify(formData);
                // var data = {
                //   action: 'create',
                //   data: prepare_data
                // };
                // console.log(data);
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

        // Edit Button
        $(document).on("click", ".btn-edit", function (e) {
            formMasterSetDisplay(0);
            e.preventDefault();
            var id = $(this).attr("data-id");
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
                        $("#message_url").val(d.result.message_url);
                        $("#message_text").val(d.result.message_text);
                        $("#btn-whatsapp-send-message").attr('data-id', d.result.message_id);
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
                        $("select[name='message_device']").append('' +
                                '<option value="' + d.result.device_id + '">' +
                                d.result.device_number +
                                '</option>');
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

        // WhatsApp
        $(document).on("click", "#btn-whatsapp-send-message", function (e) {
            e.preventDefault();
            var id = $(this).attr("data-id");
            var number = $("#message_contact_number").val();
            var name = $("#message_contact_name").val();
            var teks = $("#message_text").val();

            if (parseInt(id) > 0) {
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
            } else {
                notif(0, 'Data tidak ditemukan');
            }
        });

        // Set Preview
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
        });

        // $('#upload1').change(function(e) {
        //   var fileName = e.target.files[0].name;
        //   var reader = new FileReader();
        //   reader.onload = function(e) {
        //       $('#img-preview1').attr('src', e.target.result);
        //   };
        //   reader.readAsDataURL(this.files[0]);
        // });
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
        $("#form-master input").val();
        $("#btn-new").hide();
        $(".btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form-master input").val();
        $("#btn-new").show();
        $(".btn-save").hide();
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