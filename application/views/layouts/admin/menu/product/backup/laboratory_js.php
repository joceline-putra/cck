
<script>
$(document).ready(function() {   
  var identity = "<?php echo $identity; ?>";
  var menu_link = "<?php echo $_view;?>";
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');
    
  // console.log(menu_link);
  var url = "<?= base_url('produk/manage'); ?>";  
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
  const autoNumericOption = {
    digitGroupSeparator : '.', 
    decimalCharacter  : ',', 
    decimalCharacterAlternative: ',', 
    decimalPlaces: 0,
    watchExternalChanges: true //!!!        
  };
  new AutoNumeric('#harga_jual', autoNumericOption);   

  var index = $("#table-data").DataTable({
    // "processing": true,
    "serverSide": true,
    "ajax": {
      url: url,
      type: 'post',
      dataType: 'json',
      cache: 'false',
      data: function(d) {
        d.action = 'load';
        d.tipe = identity;
        // d.start = $("#table-data").attr('data-limit-start');
        // d.length = $("#table-data").attr('data-limit-end');
        d.filter_type = identity;        
        d.filter_ref = $("#filter_ref").find(':selected').val();
        d.search = {
          value:$("#filter_search").val()
        };          
      },
      dataSrc: function(data) {
        return data.result;
      }
    },
    "columnDefs": [
      {"targets":0, "title":"Kode","searchable":true,"orderable":true},
      {"targets":1, "title":"Nama","searchable":true,"orderable":true},
      {"targets":2, "title":"Group","searchable":true,"orderable":true},      
      {"targets":3, "title":"Harga","searchable":false,"orderable":true},
      {"targets":4, "title":"Action","searchable":false,"orderable":false}     
    ],
    "order": [
      [0, 'asc']
    ],
    "columns": [{
        'data': 'product_code'
      },{
        'data': 'product_name'
      },{
        'data': 'ref_name'
      },{        
        'data': 'product_price_sell', 
        className: 'text-right',
        render: function(data,meta,row){
          return addCommas(data);
        }
      },{
        'data': 'product_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';

          dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="'+ data +'">';
          dsp += '<span class="fas fa-edit"></span>Edit';
          dsp += '</button>';

          if (parseInt(row.product_flag) === 1) {
            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
            dsp += 'data-nama="'+row.product_name+'" data-kode="'+row.product_code+'" data-id="'+data+'" data-flag="'+row.product_flag+'">';
            dsp += '<span class="fas fa-check-square primary"></span></button>';
          }else{ 
            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
            dsp += 'data-nama="'+row.product_name+'" data-kode="'+row.product_code+'" data-id="'+data+'" data-flag="'+row.product_flag+'">';
            dsp += '<span class="fas fa-times danger"></span></button>';
          }
          
          return dsp;
        }
    }]
  });
  $('#table-data').on('page.dt', function () {
    var info = index.page.info();
    console.log( 'Showing page: '+info.page+' of '+info.pages);
    var limit_start = info.start;
    var limit_end = info.end;
    var length = info.length;
    var page = info.page;
    var pages = info.pages;
    console.log(limit_start,limit_end);
    $("#table-data").attr('data-limit-start',limit_start);
    $("#table-data").attr('data-limit-end',limit_end);
  });
  $("#filter_length").on('change', function(e){
    var value = $(this).find(':selected').val(); 
    $('select[name="table-data_length"]').val(value).trigger('change');
    index.ajax.reload();     
  });
  $("#table-data_filter").css('display','none');  
  $("#table-data_length").css('display','none');    
  $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ index.ajax.reload(); } });   
  $("#filter_ref").on('change', function(e){ index.ajax.reload(); });   

  $('#referensi').select2({
    placeholder: '--- Pilih ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
        var query = {
          search: params.term,
          tipe: 5,
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
  $('#filter_ref').select2({
    placeholder: '--- Pilih ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
        var query = {
          search: params.term,
          tipe: 5,
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

  $(document).on("click", "#btn-new", function (e) {
    formNew();
    // $("#div-form-trans").show(300);
    $("#div-form-trans").show(300);
    $(this).hide();      
  });
  $(document).on("click", "#btn-cancel", function (e) {
    formCancel();
    $("#div-form-trans").hide(300);
  });  

  // Save Button
  $(document).on("click","#btn-save",function(e) {
    e.preventDefault();
    var next = true;

    var kode = $("#form-master input[name='kode']");
    var nama = $("#form-master input[name='nama']");
    
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
      if($("select[id='satuan']").find(':selected').val() == 0){
        notif(0,'Satuan wajib dipilih');
        next=false;
      }   
    }        

    if(next==true){
      if($("input[id='harga_jual']").val().length == 0){
        notif(0,'Harga Jual wajib diisi');
        $("#harga_jual").focus();
        next=false;
      }   
    }    

    if(next==true){
      if($("select[id='account_buy']").find(':selected').val() == 0){
        notif(0,'Akun Pembelian harus dipilih');
        next=false;
      }   
    }

    if(next==true){
      if($("select[id='account_sell']").find(':selected').val() == 0){
        notif(0,'Akun Penjualan harus dipilih');
        next=false;
      }   
    }

    if(next==true){
      var data = {
        action: 'create',
        tipe: identity,
        kode: $("input[id='kode']").val(),
        nama: $("input[id='nama']").val(), 
        harga_jual: $("input[id='harga_jual']").val(),  
        referensi: $("select[id='referensi']").find(':selected').val(),
        status: $("select[id='status']").find(':selected').val(),
        akun_beli: $("select[id='account_buy']").find(':selected').val(),
        akun_jual: $("select[id='account_sell']").find(':selected').val()        
      };
      $.ajax({
        type: "POST",     
        url: url,
        data: data, 
        dataType:'json',
        cache: false,
        beforeSend:function(){},
        success:function(d){
          if(parseInt(d.status)==1){ /* Success Message */
            notif(1,d.message);
            index.ajax.reload();
          }
          else{ //Error
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
    var id = $(this).data("id");
    var data = {
      action: 'read',
      tipe:identity,    
      id:id
    }
    $.ajax({
      type: "POST",     
      url: url,
      data: data,
      dataType:'json',
      cache: false,
      beforeSend:function(){},
      success:function(d){
        if(parseInt(d.status)==1){ /* Success Message */
          activeTab('tab1'); // Open/Close Tab By ID
          $("#form-master input[name='id_document']").val(d.result.product_id);
          $("#form-master input[name='kode']").val(d.result.product_code);
          $("#form-master input[name='nama']").val(d.result.product_name);  
          $("#form-master input[name='harga_jual']").val(d.result.product_price_sell);          
          $("#form-master select[name='status']").val(d.result.product_flag).trigger('change');
        
          $("select[id='referensi']").append(''+
                            '<option value="'+d.result.ref_id+'">'+
                              d.result.ref_name+
                            '</option>');
          $("select[id='referensi']").val(d.result.ref_id).trigger('change');

          $("select[name='account_buy']").append(''+
                            '<option value="'+d.result.buy_account_id+'">'+
                              d.result.buy_account_code+' - '+d.result.buy_account_name+
                            '</option>');
          $("select[name='account_buy']").val(d.result.buy_account_id).trigger('change');

          $("select[name='account_sell']").append(''+
                            '<option value="'+d.result.sell_account_id+'">'+
                              d.result.sell_account_code+' - '+d.result.sell_account_name+
                            '</option>');
          $("select[name='account_sell']").val(d.result.sell_account_id).trigger('change');

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
  $(document).on("click","#btn-update",function(e) {
    e.preventDefault();
    var next = true;
    var id = $("#form-master input[name='id_dokumen']").val();
    var kode = $("#form-master input[name='kode']");
    var nama = $("#form-master input[name='nama']");
    
    if(id == ''){
      notif(0,'ID tidak ditemukan');
      next=false;
    }

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
      if($("select[id='satuan']").find(':selected').val() == 0){
        notif(0,'Satuan wajib dipilih');
        next=false;
      }   
    }        

    if(next==true){
      if($("input[id='harga_jual']").val().length == 0){
        notif(0,'Harga Jual wajib diisi');
        $("#harga_jual").focus();
        next=false;
      }   
    }
    
    if(next==true){
      if($("select[id='account_buy']").find(':selected').val() == 0){
        notif(0,'Akun Pembelian harus dipilih');
        next=false;
      }   
    }

    if(next==true){
      if($("select[id='account_sell']").find(':selected').val() == 0){
        notif(0,'Akun Penjualan harus dipilih');
        next=false;
      }   
    }

    if(next==true){
      var data = {
        action: 'update',
        id: $("input[id=id_document]").val(),
        tipe: identity,        
        kode: $("input[id='kode']").val(),
        nama: $("input[id='nama']").val(), 
        harga_jual: $("input[id='harga_jual']").val(),    
        referensi: $("select[id='referensi']").find(':selected').val(),
        status: $("select[id='status']").find(':selected').val(),
        akun_beli: $("select[id='account_buy']").find(':selected').val(),
        akun_jual: $("select[id='account_sell']").find(':selected').val()        
      };
      $.ajax({
        type: "POST",     
        url: url,
        data: data,
        cache: false,
        dataType:"json", 
        beforeSend:function(){},
        success:function(d){
          if(parseInt(d.status) ==1){
            formCancel();
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
  });   

  // Delete Button
  $(document).on("click",".btn-delete",function() {
    event.preventDefault();
    var id = $(this).attr("data-id");   
    var kode = $(this).attr("data-kode");
    var user = $(this).attr("data-nama");       
    $.confirm({
      title: 'Hapus!',
      content: 'Apakah anda ingin menghapus <b>'+user+'</b> ?',
      buttons: {
        confirm:{ 
          btnClass: 'btn-danger',
          text: 'Ya',
          action: function () {
            var data = {
              action: 'remove',
              tipe:identity,              
              id:id
            }
            $.ajax({
              type: "POST",     
              url : url,     
              data: data,
              success:function(d){
                if(parseInt(d.status)=1){ 
                  notif(1,d.message); 
                  index.ajax.reload();
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
    var nama = $(this).attr("data-nama");
    $.confirm({
      title: 'Set Status',
      content: 'Apakah anda ingin <b>'+msg+'</b> dengan nama <b>'+nama+'</b> ?',
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
              action: 'set-active',
              tipe:identity,
              id:id,
              flag:set_flag,      
              nama: nama,
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
});

  function formNew(){
    formMasterSetDisplay(0);
    $("#form-master input").val('');
    $("#btn-new").hide();
    $("#btn-save").show();
    $("#btn-cancel").show();
  }
  function formCancel(){
    formMasterSetDisplay(1);
    $("#form-master input").val('');      
    $("#btn-new").show();
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
      "kode",
      "nama",      
      "harga_jual",    
    ];
    $("input[name='harga_jual']").val(0);
    
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    // var attrText = [
    // ];
    // for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
      "referensi",
      "status"
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }      
  }
</script>