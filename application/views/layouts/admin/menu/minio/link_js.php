<script>
    $(document).ready(function() {   
        let url_datatable = "<?= base_url('minio'); ?>";                
        let url = "<?= base_url('minio'); ?>";    
        
        // $("select").select2();
        // $("#start, #end").datepicker({
        //     // defaultDate: new Date(),
        //     format: 'dd-mm-yyyy',
        //     autoclose: true,
        //     enableOnReadOnly: true,
        //     language: "id",
        //     todayHighlight: true,
        //     weekStart: 1 
        // }).on('change', function(){
        //     index.ajax.reload();
        // });
    
        const autoNumericOption = {
            digitGroupSeparator : ',', 
            decimalCharacter  : '.',
            decimalCharacterAlternative: '.', 
            decimalPlaces: 0,
            watchExternalChanges: true //!!!
        };
    
        // new AutoNumeric('#some_id', autoNumericOption);  
        let index = $("#table-data").DataTable({
            "serverSide": true,
            "ajax": {
                url: url_datatable,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                data: function (d) {
                    d.action = 'load';
                    d.length = $("#filter_length").find(':selected').val();
                    d.search = {
                        value:$("#filter_search").val()
                    };              
                    d.filter_link_label = $("#filter_link_label").find(':selected').val();           
                },
                dataSrc: function (data) {
                    return data.result;
                }
            },
            "columnDefs": [
                { "target": 0, "title":"Column 0", "width": "10%", "searchable": true, "orderable": false },
                { "target": 1, "title":"Column 1", "width": "20%", "searchable": true, "orderable": false },
                { "target": 2, "title":"Column 2", "width": "40%", "searchable": true, "orderable": false },
                { "target": 3, "title":"Column 3", "width": "40%", "searchable": true, "orderable": false, "className": 'dt-body-right' },                
                { "target": 4, "title":"Column 4", "width": "20%", "searchable": true, "orderable": false, "className": 'dt-body-right' },
                { "target": 5, "title":"Column 5", "width": "20%", "searchable": false, "orderable": false },
            ],    
            "order": [
                [4, 'desc']
            ],
            "columns": [
                {'data': 'link_url',
                    render: function (data, meta, row) {
                        var disp = '';
                        if(data !== 0){
                            var label = data;
                        }else{
                            var label = '-';
                        }
                        disp += '<a href="#" style="margin-right: 5px;" class="text-info btn-preview-url" data-link-session="' + row.link_session + '" data-link-name="'+row.link_name+'" data-link-url-full="'+row.link_url_full+'" data-link-label="'+row.link_label+'">';
                        disp += '<i class="fas fa-pencil"></i>'+label+'</a>';
                        
                        return disp;
                    }                
                },
                {'data': 'link_label',
                    render: function (data, meta, row) {
                        var disp = '';
                        if(data !== 0){
                            var label = data;
                        }else{
                            var label = '-';
                        }
                        disp += '<a href="#" style="margin-right: 5px;" class="text-info btn-change-label" data-link-session="' + row.link_session + '" data-link-name="'+row.link_name+'" data-link-url="'+row.link_url+'" data-link-label="'+row.link_label+'">';
                        disp += '<i class="fas fa-pencil"></i>'+label+'</a>';
                        
                        return disp;
                    }
                },
                {'data': 'time_ago'},    
                {'data': 'link_visit'},
                {'data': 'link_name'},                       
                {'data': 'link_session', 
                    render: function (data, meta, row) {
                        var disp = '';
                        disp += '<a href="#" style="margin-right: 5px;" class="text-info btn-edit" data-id="' + data + '">';
                        disp += '<i class="fas fa-pencil"></i></a>';
                        disp += '<a href="#" style="margin-right: 5px;" class="text-danger btn-delete" data-session="' + data + '">';
                        disp += '<i class="fas fa-trash"></i></a>';
                        return disp;
                    }
                }
            ],
            "language": {
                "emptyTable": "Data tidak tersedia"
            }
        });
        
        // Datatable Config  
        $("#table-data_filter").css('display','none');  
        $("#table-data_length").css('display','none');
        $("#filter_length").on('change', function(e){
            var value = $(this).find(':selected').val(); 
            $('select[name="table-data_length"]').val(value).trigger('change');
            index.ajax.reload();     
        }); 
        $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 2){ index.ajax.reload(); } });   
                    
        // $("#table-data_paginate").css('display','none');
        // $("#table-data_info").css('display','none');

        $('#filter_link_label').select2({
            //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
            placeholder: 'Search',
            //width:'100%',
            tags:true,
            minimumInputLength: 0,
            ajax: {
                type: "get",
                url: "<?= base_url('search');?>",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        tipe: 1,
                        source: 'links'
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
                if($.isNumeric(datas.id) == true){
                    // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
                    return datas.text;          
                }else{
                    return datas.text;          
                }  
            },
            templateSelection: function(datas) { //When Option on Click
                if (!datas.id) { return datas.text; }
                //Custom Data Attribute
                //$(datas.element).attr('data-column', datas.column);        
                return datas.text;
            }
        }); 
        $(document).on("change","#filter_link_label",function(e) {
            e.preventDefault();
            e.stopPropagation();
            // var var_custom = $(this).find(':selected').attr('data-column');
            var var_custom = $(this).find(':selected').attr('value');            
            // alert(var_custom);
            index.ajax.reload();
        });
        
        $(document).on("click","#btn-new",function(e) {
            $("#div-form-trans").show(500);
            $(".btn-close").show();
            $("#btn-new").hide(300);            
        });
        $(document).on("click",".btn-close",function(e) {
            $("#div-form-trans").hide(500);
            $(".btn-close").hide(300);
            $("#btn-new").show(300);
        });
        $(document).on("click","#btn-save",function(e) {
            e.preventDefault();
            e.stopPropagation();
            let next = true;
        
            if(next){
                if ($("#link_name").val().length === 0) {
                    next = false;
                    notif(0,'Link must fill');
                }
            }
        
            /* Prepare ajax for SAVE */
            if(next){
                /* let form = new FormData(); */
            
                let form = new FormData($("#form-master")[0]);
                form.append('action', 'create');     
                form.append('redirect', false);                    
                $.ajax({
                    type: "post",
                    url: url,
                    data: form, 
                    dataType: 'json', cache: 'false', 
                    contentType: false, processData: false,   
                    beforeSend:function(){},
                    success:function(d){
                        let s = d.status;
                        let m = d.message;
                        let r = d.result;
                        if(parseInt(s) == 1){
                            notif(s,m);
                            $("#link_name").val(r.link_name);
                            $("#link_url").val(r.link_url);
                            $("#link_label").val(r.link_label);                           
                            index.ajax.reload();
                        }else{
                            notif(s,m);
                        }
                    },
                    error:function(xhr,status,err){
                        notif(0,err);
                    }
                });
            }   
        });
        $(document).on("click",".btn-delete", function(e) {
            e.preventDefault();
            e.stopPropagation();
            let next     = true;
            let session  = $(this).attr('data-session');
            $.confirm({
                title: 'Delete Confirmation ?',
                content: "Are you sure to delete ?",
                typeAnimated: 'true',
                buttons: {
                    Ya: {
                        text: 'Ya',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function() {
        
                            var form = new FormData();
                            form.append('action', 'delete');
                            form.append('link_session', session);
        
                            $.ajax({
                                type: "post",
                                url: url,
                                data: form, 
                                dataType: 'json', cache: 'false', 
                                contentType: false, processData: false,
                                beforeSend:function(){},
                                success:function(d){
                                    let s = d.status;
                                    let m = d.message;
                                    let r = d.result;
                                    if(parseInt(s) == 1){
                                        notif(s,m);
                                        // notifSuccess(m);
                                        /* hint zz_for or zz_each */
                                        index.ajax.reload(null,false);
                                    }else{
                                        notif(s,m);
                                        // notifSuccess(m);
                                    }
                                },
                                error:function(xhr,status,err){
                                    notif(0,err);
                                    // notifError(err);
                                }
                            });
                        
                        }
                    },
                    Tidak: {
                        text: 'Tidak',
                        btnClass: 'btn-red',
                        key: ['Escape'],
                        action: function() {
        
                        }
                    }
                }
            });
        });
        $(document).on("click",".btn-change-label",function(e) {
            e.preventDefault();
            e.stopPropagation();

            var lsession = $(this).attr('data-link-session');
            var lurl = $(this).attr('data-link-url');    
            var lname = $(this).attr('data-link-name');
            var llabel = $(this).attr('data-link-label');                    
            /* hint zz_ajax */  
            // alert(id+', '+qty);
            var title   = 'Change Label';
            $.confirm({
                title: title,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',      
                autoClose: 'button_2|10000',
                closeIcon: true,
                closeIconClass: 'fas fa-times', 
                animation:'zoom',
                closeAnimation:'bottom',
                animateFromElement:false,   
                content: function(){
            
                },
                onContentReady: function(e){
                    var self    = this;
                    var content = '';
                    var dsp     = '';
                    dsp += '<form id="jc_form">';
                    dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Short Url</label>';
                        dsp += '        <input id="jc_input1" name="jc_input1" class="form-control" value="'+lurl+'" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Redirect</label>';
                        dsp += '        <input id="jc_input2" name="jc_input2" class="form-control" value="'+lname+'" readonly>';
                        dsp += '    </div>';
                        dsp += '</div>';                        
                        dsp += '<div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">';
                        dsp += '    <div class="form-group">';
                        dsp += '    <label class="form-label">Label</label>';
                        dsp += '        <input id="jc_input" name="jc_input" class="form-control" value="'+llabel+'">';
                        dsp += '    </div>';
                        dsp += '</div>';               
                    dsp += '</form>';
                    content = dsp;
                    self.setContentAppend(content);
                    // $("#jc_select").val(unit).trigger('change');
                    // self.buttons.button_1.disable();
                    // self.buttons.button_2.disable();
            
                    // this.$content.find('form').on('submit', function (e) {
                    //      e.preventDefault();
                    //      self.$$formSubmit.trigger('click'); // reference the button and click it
                    // });
                    // new AutoNumeric('#jc_input', autoNumericOption);            
                },
                buttons: {
                    button_1: {
                        text:'Update Label',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(e){
                            var self      = this;
            
                            var input     = self.$content.find('#jc_input').val();
                            if(!input){
                                $.alert('Label cannot empty');
                                return false;
                            }else{
                                /* var url = "<?= base_url(''); ?>"; */
            
                                var form = new FormData();
                                form.append('action', 'update-label');
                                form.append('session', lsession);
                                form.append('label',input);
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data: form, dataType: 'json',
                                    cache: 'false', contentType: false, processData: false,
                                    beforeSend: function() {},
                                    success: function(d) {
                                        var s = d.status;
                                        var m = d.message;
                                        var r = d.result;
                                        if(parseInt(s) == 1){
                                            notif(s, m);
                                            index.ajax.reload(null,false);
                                        }else{
                                            notif(s,m);
                                            // notifSuccess(m);
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
        $(document).on("click",".btn-preview-url",function(e) {
            e.preventDefault();
            e.stopPropagation();
            var lsession = $(this).attr('data-session');
            var lurl = $(this).attr('data-link-url-full');
            
            /* hint zz_ajax */
            let title   = 'Are you sure to open ?';
            let content = lurl;
            $.confirm({
                title: title,
                content: content,
                columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
                autoClose: 'button_2|30000',
                closeIcon: true, closeIconClass: 'fas fa-times',
                animation:'zoom', closeAnimation:'bottom', animateFromElement:false,    
                buttons: {
                    button_1: {
                        text:'Ok',
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function(){
                            /* hint zz_for or zz_each */
                            window.open(lurl,"_blank");
                        }
                    },
                    button_2: {
                        text: 'Close',
                        btnClass: 'btn-danger',
                        keys: ['Escape'],
                        action: function(){
                            //Close
                        }
                    }
                }
            });
        });
        
    });
</script>