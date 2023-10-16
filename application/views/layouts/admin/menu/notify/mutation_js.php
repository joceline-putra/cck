<script>
$(document).ready(function() {   

  var identity = 0;
  var view = "<?php echo $_view; ?>";  
  var url = "<?= base_url('notify'); ?>"; 
  var url_image = "<?= site_url('upload/noimage.png'); ?>";
  $("#img-preview1").attr('src',url_image);
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="'+view+'"]').addClass('active');
  $("#start, #end").datepicker({
    // defaultDate: new Date(),
    format: 'dd-mm-yyyy',
    autoclose: true,
    enableOnReadOnly: true,
    language: "id",
    todayHighlight: true,
    weekStart: 1 
  }).on('changeDate', function(){ 
    index.ajax.reload(); 
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
        d.action = 'mutation_load';
        d.date_start = $("#start").val();
        d.date_end = $("#end").val();        
        d.length = $("#filter_length").find(':selected').val();
        d.filter_bank = $("#filter_bank").find(':selected').val();        
        d.search = {
          value:$("#filter_search").val()
        };
      },
      dataSrc: function(data) {
        return data.result;
      }
    },
    "columnDefs": [
      {"targets":0, "title":"Tanggal","searchable":true,"orderable":true},
      {"targets":1, "title":"Bank","searchable":true,"orderable":true},
      {"targets":2, "title":"Tipe","searchable":true,"orderable":true},   
      {"targets":3, "title":"Jumlah","searchable":true,"orderable":true,"className":'text-right'},
      {"targets":4, "title":"Berita","searchable":true,"orderable":true},
      // {"targets":5, "title":"Action","searchable":false,"orderable":false}    
    ],
    "order": [
      [0, 'asc']
    ],
    "columns": [{
        'data': 'mutation_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
            //   dsp += row.mutation_date;
            dsp += moment(row.mutation_date).format("DD-MMM-YYYY HH:mm");
            dsp += '<br><label class="label">'+row.mutation_date_time_ago+'</label>';
          return dsp;
        }
      },{
        'data': 'mutation_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += row.mutation_api_bank_name+'<br>';
          dsp += row.mutation_api_bank_account_number+'<br>';
          dsp += row.bank_account_name;
          return dsp;
        }
      },{
        'data': 'mutation_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';


          if(row.mutation_type == 'K'){
            dsp += '<i class="fas fa-arrow-alt-circle-down" style="color:#099a8c;"></i>';
          }else if(row.mutation_type == 'D'){
            dsp += '<i class="fas fa-arrow-alt-circle-up" style="color:#f35958;"></i>';
          }else{
            dsp += 'Error';
          }
          dsp += '&nbsp;'+row.type_name+'<br>';
          return dsp;
        }        
      },{
        'data': 'mutation_id',
        className: 'text-right',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += addCommas(row.mutation_total);
          return dsp;
        }        
      },{
        'data': 'mutation_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';
          dsp += row.mutation_text;
          return dsp;
        }
      },
    //   {
    //     'data': 'contact_id',
    //     className: 'text-left',
    //     render: function(data, meta, row) {
    //       var dsp = '';
    //       dsp += '<a href="#" class="activation-data mr-2" data-id="' + data + '" data-stat="' + row.flag + '">';
    //       dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="'+ data +'">';
    //       dsp += '<span class="fas fa-edit"></span>Edit';
    //       dsp += '</button>';
          
    //       return dsp;
    //     }
    // }
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
  $("#filter_bank").on('change', function(e){ index.ajax.reload(); });        
  $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ index.ajax.reload(); }else if(parseInt(ln) < 1){ index.ajax.reload();} });        
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

  $('#filter_bank').select2({
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
          source: 'banks',
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
  // Edit Button
  $(document).on("click",".btn-edit",function(e) {
    formMasterSetDisplay(0);
    $("#form-master input[name='kode']").attr('readonly',true);
    $("#div-form-trans").show(300);
    e.preventDefault();
    var id = $(this).data("id");
    var data = {
      action: 'mutation_read',
      id:id
    }
    $.ajax({
      type: "POST",     
      url: url,
      data: data,
      dataType:'json',
      cache: false,
      beforeSend:function(){
        $("#checkbox_supplier").prop("checked", false);
        $("#checkbox_customer").prop("checked", false);
        $("#checkbox_karyawan").prop("checked", false);        
      },
      success:function(d){
        if(parseInt(d.status)==1){ /* Success Message */
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
          $("#form-master input[name='identity_number']").val(d.result.contact_identity_number);          
          $("#form-master textarea[name='note']").val(d.result.contact_note);          
          $("#form-master select[name='identity_type']").val(d.result.contact_identity_type).trigger('change');

          //Account Payable & Receivable
          $("select[name='account_payable']").append(''+
                            '<option value="'+d.result.payable_account_id+'">'+
                              d.result.payable_account_code+' - '+d.result.payable_account_name+
                            '</option>');
          $("select[name='account_payable']").val(d.result.payable_account_id).trigger('change');
          $("select[name='account_receivable']").append(''+
                            '<option value="'+d.result.receivable_account_id+'">'+
                              d.result.receivable_account_code+' - '+d.result.receivable_account_name+
                            '</option>');
          $("select[name='account_receivable']").val(d.result.receivable_account_id).trigger('change');          
          
          //Contact Type
          var contact_type = d.result.contact_type, output = [], num = contact_type.toString();
          for(var i=0, len = num.length; i<len; i+=1) {
            if(num.charAt(i) == 1){
              $("#checkbox_supplier").prop("checked", true);                
            }
            if(num.charAt(i) == 2){
              $("#checkbox_customer").prop("checked", true);                
            } 
            if(num.charAt(i) == 3){
              $("#checkbox_karyawan").prop("checked", true);                
            }                           
          }                            
          
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
});
</script>