<script>
$(document).ready(function() {
  // $.alert('ID, Session, Update');
  // $("#checkbox_karyawan").prop("checked", true);
  var identity = 0;
  var view = "<?php echo $_view; ?>";  
  var url = "<?= base_url('notify'); ?>"; 
  var url_image = "<?= site_url('upload/noimage.png'); ?>";
  $("#img-preview1").attr('src',url_image);
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="'+view+'"]').addClass('active');
 
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
      data: function(d) {
        d.action = 'bank_load';
        d.tipe = identity;
        d.length = $("#filter_length").find(':selected').val();
        d.filter_flag = $("#filter_flag").find(':selected').val();   
        d.filter_category = $("#filter_category").find(':selected').val();        
        d.search = {
          value:$("#filter_search").val()
        };
      },
      dataSrc: function(data) {
        return data.result;
      }
    },
    "columnDefs": [
      {"targets":0, "title":"Bank","searchable":true,"orderable":true},
      {"targets":1, "title":"Rekening","searchable":true,"orderable":true},
      {"targets":2, "title":"Status","searchable":true,"orderable":true},      
      {"targets":3, "title":"Last Check In","searchable":true,"orderable":true},            
      {"targets":4, "title":"Action","searchable":false,"orderable":false}    
    ],
    "order": [
      [0, 'asc']
    ],
    "columns": [{
        'data': 'bank_session',
        className: 'text-left',
        render: function(data, meta, row) {
          // var dsp = '';
          // dsp += '<a class="btn-edit" style="cursor:pointer"';
          // dsp += 'data-nama="'+data.bank_session+'" data-flag="'+data.bank_flag+'">';
          // return dsp;
          return row.category_name;
        }
      },{
        'data': 'bank_session',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += row.bank_account_number+'<br>';
          dsp += row.bank_account_name+'<br>';          
          return dsp;
        }
      },{
        'data': 'bank_session',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
          if (parseInt(row.bank_flag) === 1) {
            dsp += 'Aktif';
          }else{ 
            dsp += 'Nonaktif';
          }
          return dsp;
        }
      },{
        'data': 'bank_api_last_check',
        className: 'text-left',
        render:function(data,meta,row){
          var dsp = '';
          if(parseInt(row.bank_api_last_check) !== 0){
            dsp += row.bank_api_last_check+'<br>';
            dsp += '<span style="color:#bbbfc0;">'+row.bank_api_last_check_format+'</span>';
          }else{
            dsp += '-';
          }
          return dsp;
        }
      },{
          'data': 'bank_session',
          className: 'text-left',
          render: function(data, meta, row) {
            var dsp = '';
            var cls = 'btn btn-mini ';
            
            dsp += '<button class="'+cls+' btn-primary btn-edit" data-number="'+row.bank_account_number+'" data-session="'+row.bank_session+'"><span class="fas fa-edit"></span>Edit</button>&nbsp;';
            dsp += '<button class="'+cls+' btn-danger btn-delete" data-number="'+row.bank_account_number+'" data-session="'+row.bank_session+'"><span class="fas fa-trash"></span>Hapus</button>&nbsp;';            
            if(parseInt(row.user_access) == 1){
              if(parseInt(row.bank_api_id) > 0){
                
              }else{
                dsp += '<button class="'+cls+' btn-success btn-approve" data-number="'+row.bank_account_number+'" data-session="'+row.bank_session+'"><span class="fas fa-check"></span>Approve</button>';
              }
            }
            return dsp;
          }
      }
    ]
  });

  //Datatable Config
  $("#table-data_filter").css('display','none');  
  $("#table-data_length").css('display','none');
  $("#filter_length").on('change', function(e){
    var value = $(this).find(':selected').val(); 
    $('select[name="table-data_length"]').val(value).trigger('change');
    index.ajax.reload();     
  });     
  $("#filter_flag").on('change', function(e){ index.ajax.reload(); }); 
  $("#filter_category").on('change', function(e){ index.ajax.reload(); });        
  $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 1){ index.ajax.reload(); }else if(parseInt(ln) < 1){ index.ajax.reload();} });        
  $('#table-data').on('page.dt', function () {
    var info = index.page.info();
    var limit_start = info.start;
    var limit_end = info.end;
    var length = info.length;
    var page = info.page;
    var pages = info.pages;
    // console.log( 'Showing page: '+info.page+' of '+info.pages);
    // console.log(limit_start,limit_end);
    $("#table-data-in").attr('data-limit-start',limit_start);
    $("#table-data-in").attr('data-limit-end',limit_end);
  });

  $('#category').select2({
    placeholder: '--- Pilih ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var query = {
          search: params.term,
          source: 'categories',
          tipe:2,
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
  $('#filter_category').select2({
    placeholder: '--- Pilih ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var query = {
          search: params.term,
          source: 'categories',
          tipe:2,
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
  $(document).on("click","#btn-cancel",function(e) {
    e.preventDefault();
    formCancel();        
    $("#img-preview1").attr('src',url_image);    
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
  $(document).on("click","#btn-save",function(e) {
    e.preventDefault();
    var next = true;
    
    if(next==true){
      if($("select[id='category']").find(':selected').val() < 1){
        notif(0,'Bank harus dipilih');
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
      if($("input[id='nomor_rekening']").val().length == 0){
        notif(0,'Nomor Rekening wajib diisi');
        $("#nomor_rekening").focus();
        next=false;
      }   
    }

    if(next==true){
      if($("input[id='username']").val().length == 0){
        notif(0,'Username wajib diisi');
        $("#username").focus();
        next=false;
      }   
    }    

    if(next==true){
      if($("input[id='password']").val().length == 0){
        notif(0,'Password wajib diisi');
        $("#password").focus();
        next=false;
      }   
    }        

    // if(next==true){
    //   if($("select[id='interval']").find(':selected').val() < 1){
    //     notif(0,'Interval harus dipilih');
    //     next=false;
    //   }   
    // }

    if(next==true){

      var form = new FormData();
      form.append('action', 'bank_create');
      form.append('nomor_rekening', $('#nomor_rekening').val());
      form.append('nama', $('#nama').val());
      form.append('account_bisnis', $('#account_bisnis').val());      
      form.append('username', $('#username').val());
      form.append('password', $('#password').val());      
      form.append('telepon', $('#telepon').val());  
      form.append('email', $('#email').val());
      form.append('category', $('#category').find(':selected').val());          
      form.append('status', $('#status').find(':selected').val());    
      form.append('interval', $('#interval').find(':selected').val());
      $.ajax({
        type: "POST",     
        url: url,
        data: form,   
        dataType:'json',
        cache: false,
        contentType: false,
        processData: false,        
        beforeSend:function(){},
        success:function(d){
          if(parseInt(d.status)==1){
            notif(1,d.message);
            index.ajax.reload();
            formCancel();
            // $("#img-preview1").attr('src',url_image);
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

  // Edit Button
  $(document).on("click",".btn-edit",function(e) {
    formMasterSetDisplay(0);
    $("#form-master input[name='kode']").attr('readonly',true);
    $("#div-form-trans").show(300);
    e.preventDefault();
    var session = $(this).attr("data-session");
    var data = {
      action:'bank_read',
      session:session
    }
    $.ajax({
      type: "POST",     
      url: url,
      data: data,
      dataType:'json',
      cache: false,
      success:function(d){
        if(parseInt(d.status)==1){ /* Success Message */
          //activeTab('tab1'); // Open/Close Tab By ID
          // notif(1,d.result.contact_id);
          // $("#form-master input[id='id_document']").val(d.result.bank_id);
          $("#form-master input[id='session']").val(d.result.bank_session);          
          $("#form-master input[name='nomor_rekening']").val(d.result.bank_account_number);
          $("#form-master input[name='nama']").val(d.result.bank_account_name);
          $("#form-master input[name='account_bisnis']").val(d.result.bank_account_business);                   
          $("#form-master input[name='username']").val(d.result.bank_account_username);
          $("#form-master input[name='telepon']").val(d.result.bank_phone_notification);
          $("#form-master input[name='email']").val(d.result.bank_email_notification);
          $("#form-master input[name='status']").val(d.result.bank_flag);          
          
          $("select[id='category']").append(''+
                            '<option value="'+d.result.category_id+'">'+
                              d.result.category_name+
                            '</option>');
          $("select[id='category']").val(d.result.category_id).trigger('change');
          $("#form-master input[name='password']").attr('placeholder','Kosongkan jika password tidak berubah');         

          //Contact Image
          if(d.result.contact_image == undefined) {
            $('#img-preview1').attr('src', url_image);    
          }else{
            var image = "<?php echo site_url();?>"+d.result.contact_image;
            $('#img-preview1').attr('src', image);
          }

          $("#btn-new").hide();
          $("#btn-save").hide();
          $("#btn-update").show();
          $("#btn-cancel").show();
          scrollUp('content');
        }else{
          notif(0,d.message);
        }
      },
      error:function(xhr, Status, err){
        notif(0,'Error');
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
  $(document).on("click","#btn-update",function(e) {
    e.preventDefault();
    var next = true;
    var session = $("#form-master input[name='session']").val();

    if(session == ''){
      notif(0,'Data tidak ditemukan');
      next=false;
    }

    if(next==true){
      if($("select[id='category']").find(':selected').val() < 1){
        notif(0,'Bank harus dipilih');
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
      if($("input[id='nomor_rekening']").val().length == 0){
        notif(0,'Nomor Rekening wajib diisi');
        $("#nomor_rekening").focus();
        next=false;
      }   
    }

    if(next==true){
      if($("input[id='username']").val().length == 0){
        notif(0,'Username wajib diisi');
        $("#username").focus();
        next=false;
      }   
    }    

    // if(next==true){
    //   if($("input[id='password']").val().length == 0){
    //     notif(0,'Password wajib diisi');
    //     $("#password").focus();
    //     next=false;
    //   }   
    // }        

    // if(next==true){
    //   if($("select[id='interval']").find(':selected').val() < 1){
    //     notif(0,'Interval harus dipilih');
    //     next=false;
    //   }   
    // }

    if(next==true){

      var form = new FormData();
      form.append('action', 'bank_update');
      form.append('session', $('#session').val());
      form.append('nomor_rekening', $('#nomor_rekening').val());
      form.append('nama', $('#nama').val());
      form.append('account_bisnis', $('#account_bisnis').val());      
      form.append('username', $('#username').val());
      form.append('password', $('#password').val());      
      form.append('telepon', $('#telepon').val());  
      form.append('email', $('#email').val());
      form.append('category', $('#category').find(':selected').val());          
      form.append('status', $('#status').find(':selected').val());    
      form.append('interval', $('#interval').find(':selected').val());
      $.ajax({
        type: "POST",     
        url: url,
        data: form,   
        dataType:'json',
        cache: false,
        contentType: false,
        processData: false,        
        beforeSend:function(){},
        success:function(d){
          if(parseInt(d.status)==1){
            notif(1,d.message);
            index.ajax.reload();
            formCancel();
            // $("#img-preview1").attr('src',url_image);
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

  // Delete Button
  $(document).on("click",".btn-delete",function() {
    event.preventDefault();
    var session = $(this).attr("data-session");   
    var number = $(this).attr("data-number");
    $.confirm({
      title: 'Hapus!',
      content: 'Apakah anda ingin menghapus <b>'+number+'</b> ?',
      buttons: {
        confirm:{ 
          btnClass: 'btn-danger',
          text: 'Ya',
          action: function () {
            var data = {
              action: 'bank_delete',
              session:session,
              number:number
            }
            $.ajax({
              type: "POST",     
              url : url,     
              data: data,
              dataType:'json',
              success:function(d){
                if(parseInt(d.status)==1){ 
                  notif(1,d.message); 
                  index.ajax.reload(null,false);
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
  }); 
  
  // Set Flag Button
  $(document).on("click",".btn-set-active",function(e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    var flag = $(this).attr("data-flag");
    if(flag==1){
      var set_flag = 0;
      var msg = 'nonaktifkan';
    }else{
      var set_flag = 1;
      var msg = 'aktifkan';
    }
    var kode = $(this).attr("data-kode");
    var user = $(this).attr("data-nama");
    $.confirm({
      title: 'Set Status',
      content: 'Apakah anda ingin <b>'+msg+'</b> dengan nama <b>'+user+'</b> ?',
      columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
      autoClose: 'button_2|10000',
      closeIcon: true,
      closeIconClass: 'fas fa-times',        
      buttons: {
        button_1: {
          text:'Ok',
          btnClass: 'btn-primary',
          keys: ['enter'],
          action: function(){
            var data = {
              action: 'bank_set_active',
              id:id,
              flag:set_flag,      
              user: user,
              kode: kode
            }
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
                  index.ajax.reload(null,false);
                }else{
                  notif(0,d.message);
                }
              },
              error:function(xhr, Status, err){
                notif(0,'Error');
              }
            });  
          }
        },
        button_2: {
            text: 'Batal',
            btnClass: 'btn-danger',
            keys: ['Escape'],
            action: function(){
              //Close
            }
        }
      }
    });
  });

  $(document).on("click",".btn-approve",function(e) {
    e.preventDefault();
    var number = $(this).attr("data-number");
    var session = $(this).attr("data-session");    

    $.confirm({
      title: 'Proses CekMutasi API',
      content: 'Mendaftarkan Bank <b>'+number+'</b> ke CekMutasi ?',
      columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',  
      autoClose: 'button_2|10000',
      closeIcon: true,
      closeIconClass: 'fas fa-times',        
      buttons: {
        button_1: {
          text:'Ok',
          btnClass: 'btn-primary',
          keys: ['enter'],
          action: function(){
            var data = {
              action: 'bank_register_cek_mutasi',
              session:session
            }
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
                  index.ajax.reload(null,false);
                }else{
                  notif(0,d.message);
                }
              },
              error:function(xhr, Status, err){
                notif(0,'Error');
              }
            });  
          }
        },
        button_2: {
            text: 'Batal',
            btnClass: 'btn-danger',
            keys: ['Escape'],
            action: function(){
              //Close
            }
        }
      }
    });
  });
  $(document).on("click","#btn-export",function(e) {
    e.preventDefault();
    e.stopPropagation();
    $.alert('Nothing to do');
  });
  
  $('#upload1').change(function(e) {
    var fileName = e.target.files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
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

  function formNew(){
    formMasterSetDisplay(0);
    $("#form-master input").val('');
    $("#form-master textarea").val('');
    $("#btn-new").hide();
    $("#btn-save").show();
    $("#btn-cancel").show();
  }
  function formCancel(){
    formMasterSetDisplay(1);
    $("#form-master input").val('');
    $("#form-master textarea").val('');
    $("#btn-new").css('display','inline');
    $("#btn-save").hide();
    $("#btn-update").hide();
    $("#btn-cancel").hide();
    $("#div-form-trans").hide(300);      
  } 
  function formMasterSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form-master'; 
    var attrInput = [
      "nomor_rekening",
      "nama",
      "account_bisnis",            
      "username",
      "password",      
      "telepon",
      "email"
    ];
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    var attrText = [
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
      "category",
      "status",
      "interval"
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }      
  }
</script>