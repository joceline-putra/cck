<script>
    $(document).ready(function () {
        var identity = "<?php echo $identity; ?>";
        var menu_link = "<?php echo $_view; ?>";
        $(".nav-tabs").find('li[class="active"]').removeClass('active');
        $(".nav-tabs").find('li[data-name="reference/room_type"]').addClass('active');

        // console.log(identity);
        var url = "<?= base_url('referensi/manage'); ?>";
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
            digitGroupSeparator : ',', 
            decimalCharacter  : '.',
            decimalCharacterAlternative: '.', 
            decimalPlaces: 0,
            watchExternalChanges: true
        };
        // let price0 = new AutoNumeric('#order_ref_price_id_0', autoNumericOption);
        let price1 = new AutoNumeric('#order_ref_price_id_1', autoNumericOption);
        let price2 = new AutoNumeric('#order_ref_price_id_2', autoNumericOption);
        let price3 = new AutoNumeric('#order_ref_price_id_3', autoNumericOption);                        
        let price4 = new AutoNumeric('#order_ref_price_id_4', autoNumericOption);  
        let price5 = new AutoNumeric('#order_ref_price_id_5', autoNumericOption);
        let price6 = new AutoNumeric('#order_ref_price_id_6', autoNumericOption);                                                

        var index = $("#table-data").DataTable({
            // "processing": true,
            "serverSide": true,
            "ajax": {
                url: url,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load_ref_room_type';
                    d.tipe = identity;
                    d.length = $("#filter_length").find(':selected').val();
                    d.filter_branch = $("#filter_branch").find(':selected').val();
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
                {"targets": 0, "title": "Cabang", "searchable": true, "orderable": true},
                {"targets": 1, "title": "Jenis Kamar", "searchable": true, "orderable": true},
                {"targets": 2, "title": "Bulanan", "searchable": true, "orderable": true},
                {"targets": 3, "title": "Harian", "searchable": true, "orderable": true},
                {"targets": 4, "title": "Midnight", "searchable": true, "orderable": true},                
                {"targets": 5, "title": "4 Jam", "searchable": true, "orderable": true},
                {"targets": 6, "title": "3 Jam", "searchable": true, "orderable": true},                                                                                                        
                {"targets": 7, "title": "2 Jam", "searchable": true, "orderable": true},        
                {"targets": 8, "title": "Action", "searchable": false, "orderable": false}
            ],
            "order": [
                [0, 'asc']
            ],
            "columns": [{
                    'data': 'branch_name'
                }, {
                    'data': 'ref_name'
                }, {
                    'data': 'ref_price_1', className: 'text-right', 
                    render: function(data,meta,row){
                        var dsp = '';
                        var set_att = 'data-ref-id="'+row.ref_id+'" data-value="'+data+'" data-room-name="'+row.ref_name+'" data-branch-name="'+row.branch_name+'" data-sort="1" data-sort-name="Bulanan"';
                        dsp += '<button class="btn btn-mini btn-small btn_ref_price" '+set_att+'>'+numberWithCommas(data)+'</button>';
                        return dsp;
                    }
                }, {
                    'data': 'ref_price_2',className: 'text-right', 
                    render: function(data,meta,row){ 
                        var dsp = '';
                        var set_att = 'data-ref-id="'+row.ref_id+'" data-value="'+data+'" data-room-name="'+row.ref_name+'" data-branch-name="'+row.branch_name+'" data-sort="2" data-sort-name="Harian"';
                        dsp += '<button class="btn btn-mini btn-small btn_ref_price" '+set_att+'>'+numberWithCommas(data)+'</button>';
                        return dsp;
                    }
                }, {
                    'data': 'ref_price_3',className: 'text-right', 
                    render: function(data,meta,row){ 
                        var dsp = '';
                        var set_att = 'data-ref-id="'+row.ref_id+'" data-value="'+data+'" data-room-name="'+row.ref_name+'" data-branch-name="'+row.branch_name+'" data-sort="3" data-sort-name="Midnight"';
                        dsp += '<button class="btn btn-mini btn-small btn_ref_price" '+set_att+'>'+numberWithCommas(data)+'</button>';
                        return dsp;
                    }
                }, {
                    'data': 'ref_price_4',className: 'text-right', 
                    render: function(data,meta,row){
                        var dsp = '';
                        var set_att = 'data-ref-id="'+row.ref_id+'" data-value="'+data+'" data-room-name="'+row.ref_name+'" data-branch-name="'+row.branch_name+'" data-sort="4" data-sort-name="4 Jam"';
                        dsp += '<button class="btn btn-mini btn-small btn_ref_price" '+set_att+'>'+numberWithCommas(data)+'</button>';
                        return dsp;
                    }
                }, {                    
                    'data': 'ref_price_6',className: 'text-right', 
                    render: function(data,meta,row){ 
                        var dsp = '';
                        var set_att = 'data-ref-id="'+row.ref_id+'" data-value="'+data+'" data-room-name="'+row.ref_name+'" data-branch-name="'+row.branch_name+'" data-sort="6" data-sort-name="3 Jam"';
                        dsp += '<button class="btn btn-mini btn-small btn_ref_price" '+set_att+'>'+numberWithCommas(data)+'</button>';
                        return dsp;
                    }
                }, {
                    'data': 'ref_price_5',className: 'text-right', 
                    render: function(data,meta,row){ 
                        var dsp = '';
                        var set_att = 'data-ref-id="'+row.ref_id+'" data-value="'+data+'" data-room-name="'+row.ref_name+'" data-branch-name="'+row.branch_name+'" data-sort="5" data-sort-name="2 Jam"';
                        dsp += '<button class="btn btn-mini btn-small btn_ref_price" '+set_att+'>'+numberWithCommas(data)+'</button>';
                        return dsp;
                    }
                }, {
                    'data': 'ref_id',
                    className: 'text-left',
                    render: function (data, meta, row) {
                        var dsp = '';
                        // disp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';

                        dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="' + data + '">';
                        dsp += '<span class="fas fa-edit"></span>Edit';
                        dsp += '</button>';

                        if (parseInt(row.ref_flag) === 1) {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-success"';
                            dsp += 'data-nama="' + row.ref_name + '" data-id="' + data + '" data-flag="' + row.ref_flag + '">';
                            dsp += '<span class="fas fa-check-square primary"></span> Aktif</button>';
                        } else {
                            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
                            dsp += 'data-nama="' + row.ref_name + '" data-id="' + data + '" data-flag="' + row.ref_flag + '">';
                            dsp += '<span class="fas fa-times danger"></span> Nonaktif</button>';
                        }

                        return dsp;
                    }
                }]
        });

        $(document).on("click",".btn_ref_price", function(e) {
            let ref      = $(this).attr('data-ref-id');            
            let dva      = $(this).attr('data-value');
            let drn    = $(this).attr('data-room-name');
            let dbn    = $(this).attr('data-branch-name');
            let dsn    = $(this).attr('data-sort-name');
            let ds    = $(this).attr('data-sort');                                    
        
            $.confirm({
                title: 'Pengaturan Harga Lanjutan',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                closeIcon: true, closeIconClass: 'fas fa-times', 
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                    let self = this;
                    let form = new FormData();
                    form.append('action','get_price');
                    form.append('ref_id',ref);
                    form.append('ref_sort',ds);                    
            
                    return $.ajax({
                        url: url,
                        data: form,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                    }).fail(function(){
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function(e){
                    let self    = this;
                    let content = '';
                    let dsp     = '';
                    let d = self.ajaxResponse.data;

                    var set_price = d['result']['price_ref_json'];
                    var p = JSON.parse(set_price);
                    console.log(p);
                    /* dsp += '<div>Content is ready after process !</div>'; */
                    dsp += '<form id="jc_form">';
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Cabang</label>';
                        dsp += '        <input id="branch_name" name="branch_name" class="form-control" readonly value="'+dbn+'">';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Jenis Kamar</label>';
                        dsp += '        <input id="ref_name" name="ref_name" class="form-control" readonly value="'+drn+'">';
                        dsp += '    </div>';
                        dsp += '</div>';                        
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Untuk</label>';
                        dsp += '        <input id="ref_for" name="ref_for" class="form-control" readonly value="'+dsn+'">';
                        dsp += '    </div>';
                        dsp += '</div>';   
                        dsp += '<div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Harga Default</label>';
                        dsp += '        <input id="ref_price" name="ref_price" class="form-control" value="'+d['ref_price_default']+'">';
                        dsp += '    </div>';
                        dsp += '</div>';  
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">&nbsp;';
                        dsp += '</div>';                           
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Senin</label></div>';
                        dsp += '    <div class="col-md-8"><input id="monday" name="monday" class="form-control" value="'+p['monday']+'"></div>';
                        dsp += '</div>';  
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Selasa</label></div>';
                        dsp += '    <div class="col-md-8"><input id="tuesday" name="tuesday" class="form-control" value="'+p['tuesday']+'"></div>';
                        dsp += '</div>';               
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Rabu</label></div>';
                        dsp += '    <div class="col-md-8"><input id="wednesday" name="wednesday" class="form-control" value="'+p['wednesday']+'"></div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Kamis</label></div>';
                        dsp += '    <div class="col-md-8"><input id="thursday" name="thursday" class="form-control" value="'+p['thursday']+'"></div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Jumat</label></div>';
                        dsp += '    <div class="col-md-8"><input id="friday" name="friday" class="form-control" value="'+p['friday']+'"></div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Sabtu</label></div>';
                        dsp += '    <div class="col-md-8"><input id="saturday" name="saturday" class="form-control" value="'+p['saturday']+'"></div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="col-md-4"><label class="form-label">Minggu</label></div>';
                        dsp += '    <div class="col-md-8"><input id="sunday" name="sunday" class="form-control" value="'+p['sunday']+'"></div>';
                        dsp += '</div>';                                                                                                                                                                                                                                                                                
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    // self.buttons.button_1.disable();
                    // self.buttons.button_2.disable();
        
                    // this.$content.find('form').on('submit', function (e) {
                    //      e.preventDefault();
                    //      self.$$formSubmit.trigger('click'); // reference the button and click it
                    // });
                },
                buttons: {
                    button_1: {
                        text:'Update',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            let self      = this;
        
                            let input     = self.$content.find('#ref_price').val();
                            if(!input){
                                notif(0,'Harga Default mohon diisi dahulu');
                                return false;
                            } else{
                                let form = new FormData($("#jc_form")[0]);
                                // let form = new FormData();
                                form.append('action', 'update_price');
                                form.append('ref_id', ref);    
                                form.append('ref_sort', ds);                            
                                // form.append('input', input);
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: form, dataType: 'json',
                                    cache: 'false', contentType: false, processData: false,
                                    beforeSend:function(x){
                                        // x.setRequestHeader('Authorization',"Bearer " + bearer_token);
                                        // x.setRequestHeader('X-CSRF-TOKEN',csrf_token);
                                    },
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            notif(s, m);
                                            index.ajax.reload(null,false);
                                            /*type_your_code_here*/
                                        }else{
                                            // notif(s,m);
                                        }
                                    },
                                    error: function(xhr, status, err) {}
                                });
                            }
                        }
                    },
                    button_2: {
                        text: 'Cancel',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });
        });

        function tt(){
            let title   = 'Your Title';
            $.confirm({
                title: title,
                // icon: 'fas fa-check',
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                // autoClose: 'button_2|10000',
                // closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false, useBootstrap:true,
                content: function(){
                    let self = this;
                    /*let url = "services/controls/Your Title.php?action=action_name";*/ //Native
                    let url = "<?= base_url('Your Title/manage'); ?>"; //CI
            
                    let data = {
                        action: 'action_name',
                        id: $("#BY_DATA_ID").attr('data-id'),
                        code: $("#BY_INPUT").val(),    
                        name: $("#BY_SELECT").find(':selected').val()    
                    };        
                    
                    let form = new FormData();
                    form.append('action','action_name');
                    form.append('id',$("#BY_INPUT").val());
            
                    return $.ajax({
                        url: url,
                        data: data,
                        dataType: 'json',
                        type: 'post',
                        cache: 'false', contentType: false, processData: false,
                    }).done(function (d) {
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        var dsp = '';
                        //dsp += '<table id="table_dom" class="table table-bordered" style="width:100%;">';
                            //dsp += '<thead>';
                                //dsp += '<tr>';
                                    //dsp += '<th>Information</th>';
                                    //dsp += '<th>Action</th>';
                                //dsp += '<tr>';
                            //dsp += '</thead>';
                        //dsp += '<tbody>';
            
                        if(parseInt(s) == 1){
                            // notif(s,m);
                            // notifSuccess(m);
                            /* hint zz_for or zz_each */
                        }else{
                            // notif(s,m);
                            // notifSuccess(m);
                            //dsp += '<tr>';
                                //dsp += '<th>Information</th>';
                                //dsp += '<th>Action</th>';
                            //dsp += '<tr>';
                        }            
                        //dsp += '</tbody>';
            
                        self.setTitle(m);
                        self.setContentAppend(dsp);
                        // self.setContentAppend('<br>Version: ' + d.short_name); //Json Return
                        // self.setContentAppend('<img src="'+ d.icons[0].src+'" class="img-responsive" style="margin:0 auto;">'); // Image Return
                        /*type_your_code_here*/
            
                    }).fail(function(){
                        self.setContent('Something went wrong, Please try again.');
                    });
                },
                onContentReady: function(){
                    let self = this;
                    let content = '';
                    let dsp     = '';
            
                    let d = self.ajaxResponse.data;
                    let s = d.status;
                    let m = d.message;
                    let r = d.result;
            
                    if(parseInt(s)==1){
                        dsp += '<div>Content is ready after process !</div>';
                        dsp += '<form id="jc_form">';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Input</label>';
                            dsp += '        <input id="jc_input" name="jc_input" class="form-control">';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Textarea</label>';
                            dsp += '        <textarea id="jc_textarea" name="alamat" class="form-control" rows="4"></textarea>';
                            dsp += '    </div>';
                            dsp += '</div>';
                            dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                            dsp += '    <div class="form-group">';
                            dsp += '    <label class="form-label">Select</label>';
                            dsp += '        <select id="jc_select" name="jc_select" class="form-control">';
                            dsp += '            <option value="1">Ya</option>';
                            dsp += '            <option value="2">Tidak</option>';
                            dsp += '        </select>';
                            dsp += '    </div>';
                            dsp += '</div>';
                        dsp += '</form>';
                        content = dsp;
                        self.setContentAppend(content);
                        // self.buttons.button_1.disable();
                        // self.buttons.button_2.disable();
            
                        // this.$content.find('form').on('submit', function (e) {
                        //      e.preventDefault();
                        //      self.$$formSubmit.trigger('click'); // reference the button and click it
                        // });
                    }else{
                        self.setContentAppend('<div>Content ready!</div>');
                    }
                },
                buttons: {
                    button_1: {
                        text:'<i class="fas fa-check white"></i> Process',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            let self      = this;
            
                            let input     = self.$content.find('#jc_input').val();
                            let textarea  = self.$content.find('#jc_textarea').val();
                            let select    = self.$content.find('#jc_select').val();
                            
                            if(!input){
                                $.alert('Input mohon diisi dahulu');
                                return false;
                            } else if(!textarea){
                                $.alert('Textarea mohon diisi dahulu');
                                return false;
                            } else if(select == 0){
                                $.alert('Select mohon dipilih dahulu');
                                return false;
                            } else{
                                /* let url = "<?= base_url(''); ?>"; */
            
                                let form = new FormData();
                                form.append('action', 'action');
                                form.append('input', input);
                                form.append('textarea', textarea);
                                form.append('select', select);
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: form, dataType: 'json',
                                    cache: 'false', contentType: false, processData: false,
                                    beforeSend: function() {},
                                    success: function(d) {
                                        let s = d.status;
                                        let m = d.message;
                                        let r = d.result;
                                        if(parseInt(s) == 1){
                                            // notif(s, m);
                                            /*type_your_code_here*/
                                        }else{
                                            // notif(s,m);
                                            // notifSuccess(m);
                                        }
                                    },
                                    error: function(xhr, status, err) {}
                                });
                            }            
                        }
                    },
                    button_2: {
                        text: '<i class="fas fa-times white"></i> Close',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });
        }

        //Datatable Config
        $("#table-data_filter").css('display', 'none');
        $("#table-data_length").css('display', 'none');
        $("#filter_length").on('change', function (e) {
            index.ajax.reload();
        });
        $("#filter_branch").on('change', function(e){ index.ajax.reload(); });
        $("#filter_flag").on('change', function(e){ index.ajax.reload(); });        
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

        $(document).on("click","#btn-new", (e) => {
            e.preventDefault();
            e.stopPropagation();
            formNew();
            $("#modal_ref").modal('show');
        });
        $(document).on("click","#btn-cancel", (e) => {
            e.preventDefault();
            e.stopPropagation();
            formCancel();
            $("#modal_ref").modal('hide');
        });

        // Save Button
        $(document).on("click", "#btn-save", function (e) {
            e.preventDefault();
            var next = true;

            // var kode = $("#form_ref input[name='kode']");
            var nama = $("#form_ref input[name='nama']");

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
                var prepare = {
                    tipe: identity,
                    // kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    keterangan: $("input[id='keterangan']").val(),
                    status: $("select[id='status']").find(':selected').val(),
                    // order_ref_price_id_0: price0.rawValue,
                    order_ref_price_id_1: price1.rawValue,
                    order_ref_price_id_2: price2.rawValue,
                    order_ref_price_id_3: price3.rawValue,
                    order_ref_price_id_4: price4.rawValue,   
                    order_ref_price_id_5: price5.rawValue,
                    order_ref_price_id_6: price6.rawValue,                 
                    ref_branch_id: $("input[name='ref_branch_id']:checked").val()                                                                                           
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
                    dataType: 'json',
                    cache: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) == 1) { /* Success Message */
                            notif(1, d.message);
                            index.ajax.reload();
                            $("#modal_ref").modal('hide');
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
            // $("#form_ref input[name='kode']").attr('readonly', true);

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
                        // notif(1,d.result.id);ss
                        $("#id_document").val(d.result.ref_id);
                        // $("#form_ref input[name='kode']").val(d.result.ref_code);
                        $("#nama").val(d.result.ref_name);
                        $("#keterangan").val(d.result.ref_note);
                        $("#status").val(d.result.ref_flag).trigger('change');

                        $("input[name='ref_branch_id'][value="+d.result.ref_branch_id+"]").prop("checked", true).change();

                        // price_data = d.result_price;
                        // if(price_data.length > 0){
                        //     for(var i = 0; i < price_data.length; i++){
                        //         $("#order_ref_price_id_"+i).val(d.result_price[i].price_value);
                        //     }
                        // }else{
                        //     for(var i = 0; i < 5; i++){
                        //         $("#order_ref_price_id_"+i).val(0);
                        //     }
                        // }
                        
                        $("#order_ref_price_id_0").val(d.result.ref_price_0);
                        $("#order_ref_price_id_1").val(d.result.ref_price_1);
                        $("#order_ref_price_id_2").val(d.result.ref_price_2);
                        $("#order_ref_price_id_3").val(d.result.ref_price_3);
                        $("#order_ref_price_id_4").val(d.result.ref_price_4);
                        $("#order_ref_price_id_5").val(d.result.ref_price_5);     
                        $("#order_ref_price_id_6").val(d.result.ref_price_6);                                                 

                        $("#modal_ref").modal('show');
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
            var id = $("#form_ref input[name='id_dokumen']").val();
            // var kode = $("#form_ref input[name='kode']");
            var nama = $("#form_ref input[name='nama']");

            if (id == '') {
                notif(0, 'ID tidak ditemukan');
                next = false;
            }

            // if (kode.val().length == 0) {
                // notif(0, 'Kode wajib diisi');
                // kode.focus();
                // next = false;
            // }

            if (nama.val().length == 0) {
                notif(0, 'Nama wajib diisi');
                nama.focus();
                next = false;
            }

            if (next == true) {
                var prepare = {
                    tipe: identity,
                    id: $("input[id=id_document]").val(),
                    // kode: $("input[id='kode']").val(),
                    nama: $("input[id='nama']").val(),
                    keterangan: $("input[id='keterangan']").val(),
                    status: $("select[id='status']").find(':selected').val(),
                    // order_ref_price_id_0: price0.rawValue,
                    order_ref_price_id_1: price1.rawValue,
                    order_ref_price_id_2: price2.rawValue,
                    order_ref_price_id_3: price3.rawValue,
                    order_ref_price_id_4: price4.rawValue,   
                    order_ref_price_id_5: price5.rawValue,
                    order_ref_price_id_6: price6.rawValue,
                    ref_branch_id: $("input[name='ref_branch_id']:checked").val()                 
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
                            $("#btn-save").hide();
                            $("#btn-update").hide();
                            $("#btn-cancel").hide();
                            $("#form_ref input").val();
                            formMasterSetDisplay(1);
                            notif(1, d.message);
                            $("#modal_ref").modal('hide');
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
            // var kode = $(this).attr("data-kode");
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
            // var kode = $(this).attr("data-kode");
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
                                // kode: kode
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

    });

    function formNew() {
        formMasterSetDisplay(0);
        $("#form_ref input").val();
        $("#btn-save").show();
        $("#btn-cancel").show();
    }
    function formCancel() {
        formMasterSetDisplay(1);
        $("#form_ref input").val();
        $("#btn-save").hide();
        $("#btn-update").hide();
        $("#btn-cancel").hide();
    }
    function formMasterSetDisplay(value) { // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
        // if (value == 1) {
        //     var flag = true;
        // } else {
        //     var flag = false;
        // }
        // //Attr Input yang perlu di setel
        // var form = '#form_ref';
        // var attrInput = [
        //     // "kode",
        //     "nama",
        //     "keterangan",
        //     "order_ref_price_id_0", "order_ref_price_id_1", "order_ref_price_id_2", "order_ref_price_id_3", "order_ref_price_id_4"
        // ];

        // for (var i = 0; i <= attrInput.length; i++) {
        //     $("" + form + " input[name='" + attrInput[i] + "']").attr('readonly', flag);
        // }

        // // Attr Textarea yang perlu di setel
        // // var attrText = [
        // //   "keterangan"
        // // ];
        // // for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

        // //Attr Select yang perlu di setel
        // var atributSelect = [
        //     "status"
        // ];
        // for (var i = 0; i <= atributSelect.length; i++) {
        //     $("" + form + " select[name='" + atributSelect[i] + "']").attr('disabled', flag);
        // }
    }
</script>