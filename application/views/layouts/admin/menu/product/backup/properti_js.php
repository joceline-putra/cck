
<script>
$(document).ready(function() {   
  var identity = "<?php echo $identity; ?>";
  var menu_link = "<?php echo $_view;?>";
  $(".nav-tabs").find('li[class="active"]').removeClass('active');
  $(".nav-tabs").find('li[data-name="' + menu_link + '"]').addClass('active');
      
  // console.log(identity);
  var site_url = "<?= site_url(); ?>";
  var url = "<?= base_url('produk/manage'); ?>";
  var url_image = '<?= base_url('upload/noimage.png');?>';
      
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
    digitGroupSeparator : '.', 
    decimalCharacter  : ',', 
    decimalCharacterAlternative: ',', 
    decimalPlaces: 0,
    watchExternalChanges: true //!!!        
  };
  new AutoNumeric('#harga_jual', autoNumericOption);
  // new AutoNumeric('#harga_beli', autoNumericOption);
  // new AutoNumeric('#harga_promo', autoNumericOption);      

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
        d.filter_ref = $("#filter_ref").find(':selected').val();
        d.filter_type = $("#filter_type").find(':selected').val();
        d.filter_city = $("#filter_city").find(':selected').val();
        d.filter_flag = $("#filter_flag").find(':selected').val();  
        d.search = {
          value:$("#filter_search").val()
        };                 
      },
      dataSrc: function(data) {
          return data.result;
      }
    },
    "columnDefs": [{
        "searchable": false,
        "orderable": false,
        "targets": [4]
      },{
        "searchable": true,
        "orderable": true,
        "targets": [1, 2, 3]
      }
    ],
    "order": [
      [0, 'desc']
    ],
    "columns": [{
        'data': 'product_id',
        render:function(data,meta,row){
          var dsp = '';
          dsp += '<b>'+row.product_name+'</b><br>';
          dsp += row.city_name+', '+row.province_name;
          return dsp;
        }
      },{
        'data': 'product_id',
        render:function(data,meta,row){
          var dsp = '';
          // dsp += row.product_name+'<br>';
          // dsp += '<label class="label">'+row.product_manufacture+'</label>';
          // if(row.category_name == undefined){
          //   dsp += '&nbsp;<label class="label label-danger">Bahan Baku</label>';
          // }else{
          //   dsp += '&nbsp;<label class="label label-primary">'+row.category_name+'</label>';
          // }

          // if(parseFloat(row.product_price_promo) > 0){
          //   dsp += '&nbsp;<label class="label label-purple">Promo</label>';
          // }

          // if(parseInt(row.product_with_stock) > 0){
          //   dsp += '&nbsp;<span class="label">Proteksi Stok</label>';
          // }          
          dsp += 'Luas Tanah:'+row.product_square_size+', Luas Bangunan:'+row.product_building_size+'<br>';
          dsp += 'Kamar Tidur:'+row.product_bedroom+', Kamar Mandi:'+row.product_bathroom+', Garasi:'+row.product_garage;          
          // if(row.product_image == undefined){

          // }else{
          //   dsp += '&nbsp;<i class="fas fa-camera"></i>';
          // }

          return dsp;
        }
      },{
        'data': 'product_id',
        render:function(data,meta,row){
          return row.contact_name;
        }
      },{        
        'data': 'product_id', 
        className: 'text-right',
        render:function(data,meta,row){
          var dsp = '';
          dsp += 'Rp. '+ addCommas(row.product_price_sell);
          return dsp;
        }
      },{
        'data': 'product_id',
        className: 'text-left',
        render: function(data, meta, row) {
          var dsp = '';

          dsp += '<button class="btn-edit btn btn-mini btn-primary" data-id="'+ row.product_id +'">';
          dsp += '<span class="fas fa-edit"></span>Edit';
          dsp += '</button>';

          if (parseInt(row.product_flag) === 1) {
            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-primary"';
            dsp += 'data-nama="'+row.product_name+'" data-kode="'+row.product_code+'" data-id="'+row.product_id+'" data-flag="'+row.product_flag+'">';
            dsp += '<span class="fas fa-check-square primary"></span></button>';
          }else{ 
            dsp += '&nbsp;<button class="btn btn-set-active btn-mini btn-danger"';
            dsp += 'data-nama="'+row.product_name+'" data-kode="'+row.product_code+'" data-id="'+row.product_id+'" data-flag="'+row.product_flag+'">';
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

  $("#table-data_filter").css('display','none');  
  $("#table-data_length").css('display','none');     
  $("#filter_length").on('change', function(e){
    var value = $(this).find(':selected').val(); 
    $('select[name="table-data_length"]').val(value).trigger('change');
    index.ajax.reload();     
  });  
  $("#filter_search").on('input', function(e){ var ln = $(this).val().length; if(parseInt(ln) > 3){ index.ajax.reload(); } });   
  $("#filter_ref").on('change',function(e){ index.ajax.reload(); });
  $("#filter_type").on('change',function(e){ index.ajax.reload(); });
  $("#filter_city").on('change',function(e){ index.ajax.reload(); });
  $("#filter_flag").on('change',function(e){ index.ajax.reload(); });

  $('#filter_contact').select2({
    placeholder: '--- Semua ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var query = {
          search: params.term,
          tipe: 3, //1=Supplier, 2=Asuransi
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
    escapeMarkup: function(markup){ 
      return markup; 
    },
    templateResult: function(datas){ //When Select on Click
      // if (!datas.id) { return datas.text; }
      if($.isNumeric(datas.id) == true){
        // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
        return datas.text;          
      }
      // else{
        // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
      // }        
    },
    templateSelection: function(datas) { //When Option on Click
      if (!datas.id) { return datas.text; }
      //Custom Data Attribute
      // $(datas.element).attr('data-alamat', datas.alamat);
      // $(datas.element).attr('data-telepon', datas.telepon);
      // $(datas.element).attr('data-email', datas.email);            
      // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
      if(parseInt(datas.id) > 0){
        return datas.text;
      }
    }
  });
  $('#filter_type').select2({
    placeholder: '--- Semua ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
        var query = {
          search: params.term,
          tipe: 1, //1=Produk, 2=News
          source: 'product_type'
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
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
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
  
  $('#categories').select2({
    placeholder: '--- Pilih (abaikan jika bahan baku) ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
        var query = {
          search: params.term,
          tipe: 1, //1=Produk, 2=News
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
  $('#contact').select2({
    placeholder: '--- Pilih (Agent) ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        var query = {
          search: params.term,
          tipe: 3, //1=Supplier, 2=Asuransi
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
    escapeMarkup: function(markup){ 
      return markup; 
    },
    templateResult: function(datas){ //When Select on Click
      // if (!datas.id) { return datas.text; }
      if($.isNumeric(datas.id) == true){
        // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
        return datas.text;          
      }
      // else{
        // return '<i class="fas fa-plus '+datas.id.toLowerCase()+'"></i> '+datas.text;          
      // }        
    },
    templateSelection: function(datas) { //When Option on Click
      if (!datas.id) { return datas.text; }
      //Custom Data Attribute
      // $(datas.element).attr('data-alamat', datas.alamat);
      // $(datas.element).attr('data-telepon', datas.telepon);
      // $(datas.element).attr('data-email', datas.email);            
      // return '<i class="fas fa-user-check '+datas.id.toLowerCase()+'"></i> '+datas.text;
      if(parseInt(datas.id) > 0){
        return datas.text;
      }
    }
  });
  $('#kota').select2({
    placeholder: '--- Pilih Kota / Kabupaten ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
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
  $('#tipe_properti').select2({
    placeholder: '--- Pilih (Tanah,Rumah,Apartemen,dll) ---',
    minimumInputLength: 0,
    ajax: {
      type: "get",
      url: "<?= base_url('search/manage');?>",      
      dataType: 'json',
      delay: 250,
      data: function(params){
        var query = {
          search: params.term,
          tipe: 1, //1=Produk, 2=News
          source: 'product_type'
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
    
    // if(next==true){    
    //   if($("input[id='kode']").val().length == 0){
    //     notif(0,'Kode wajib diisi');
    //     $("#kode").focus();
    //     next=false;
    //   }
    // }

    // if(next==true){
    //   if($("select[id='categories']").find(':selected').val() == 0){
    //     notif(0,'Kategori wajib dipilih');
    //     next=false;
    //   }   
    // }        

    // if(next==true){
      if($("input[id='nama']").val().length == 0){
        notif(0,'Judul wajib diisi');
        $("#nama").focus();
        next=false;
      }   
    // }

    if(next==true){
      if($("select[id='tipe_properti']").find(':selected').val() == undefined){
        notif(0,'Tipe Properti wajib dipilih');
        next=false;
      }   
    } 
    
    if(next==true){
      if($("select[id='contact']").find(':selected').val() == undefined){
        notif(0,'Agen wajib dipilih');
        next=false;
      }   
    } 

    if(next==true){
      if($("select[id='kota']").find(':selected').val() == undefined){
        notif(0,'Kota wajib dipilih');
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
      /*var prepare = {
        tipe: identity,
        kode: $("input[id='kode']").val(),
        nama: $("input[id='nama']").val(),
        keterangan: $("textarea[id='keterangan']").val(),        
        harga_beli: $("input[id='harga_beli']").val(),
        harga_jual: $("input[id='harga_jual']").val(),
        stok_minimal: $("input[id='stok_minimal']").val(),
        stok_maksimal: $("input[id='stok_maksimal']").val(),        
        satuan: $("select[id='satuan']").find(':selected').val(),
        status: $("select[id='status']").find(':selected').val()
      }
      var prepare_data = JSON.stringify(prepare);
      var data = {
        action: 'create',
        data: prepare_data
      };*/
      var formData = new FormData();
      formData.append('action', 'create');
      formData.append('upload1', $('#upload1')[0].files[0]);
      formData.append('upload2', $('#upload2')[0].files[0]);
      formData.append('upload3', $('#upload3')[0].files[0]);
      formData.append('upload4', $('#upload4')[0].files[0]);

      formData.append('tipe', identity);
      // formData.append('kode', $('#kode').val());
      formData.append('nama', $('#nama').val());
      formData.append('keterangan', $('#keterangan').val());
      // formData.append('harga_beli', $('#harga_beli').val());
      formData.append('harga_jual', $('#harga_jual').val()); 
      // formData.append('harga_promo', $('#harga_promo').val());      
      // formData.append('stok_minimal', $('#stok_minimal').val());  
      // formData.append('stok_maksmal', $('#stok_maksimal').val());
      // formData.append('satuan', $('#satuan').find(':selected').val());   
      formData.append('status', $('#status').find(':selected').val());
      // formData.append('with_stock', $('#with_stock').find(':selected').val());       
      // formData.append('categories', $('#categories').find(':selected').val()); 
      // formData.append('manufacture', $('#manufacture').val());
      // formData.append('manufacture', $('#manufacture').find(':selected').val());
      formData.append('ref', $('#ref').find(':selected').val());
      formData.append('tipe_properti', $('#tipe_properti').find(':selected').val());
      formData.append('contact', $('#contact').find(':selected').val());      
      formData.append('kota', $('#kota').find(':selected').val());  
      formData.append('luas_tanah', $('#luas_tanah').val());      
      formData.append('luas_bangunan', $('#luas_bangunan').val());  
      formData.append('kamar_tidur', $('#kamar_tidur').val());
      formData.append('kamar_mandi', $('#kamar_mandi').val());                       
      formData.append('garasi', $('#garasi').val());      

      // formData.append('stok_minimal', $('#stok_minimal').val());  
      // formData.append('stok_maksmal', $('#stok_maksimal').val());
      $.ajax({
        type: "POST",     
        url: url,
        data: formData, 
        dataType:'json',
        cache: false,
        contentType: false,
        processData: false,        
        beforeSend:function(){},
        success:function(d){
          if(parseInt(d.status)==1){ /* Success Message */
            notif(1,d.message);
            index.ajax.reload();
            $("#btn-new").show();
            $("#btn-save").hide();
            $("#btn-cancel").hide();
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

    e.preventDefault();
    var id = $(this).data("id");
    var data = {
      action: 'read',
      id:id,
      tipe:1
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
          // notifSuccess(d.result.id);ss
          $("#form-master input[name='id_document']").val(d.result.product_id);
          // $("#form-master input[name='kode']").val(d.result.product_code);
          $("#form-master input[name='nama']").val(d.result.product_name);
          $("#form-master input[name='keterangan']").val(d.result.product_note);                   
          // $("#form-master input[name='harga_beli']").val(d.result.product_price_buy);
          $("#form-master input[name='harga_jual']").val(d.result.product_price_sell);
          // $("#form-master input[name='harga_promo']").val(d.result.product_price_promo);          
          // $("#form-master input[name='stok_minimal']").val(d.result.product_min_stock_limit);
          // $("#form-master input[name='stok_maksimal']").val(d.result.product_max_stock_limit);
          // $("#form-master input[name='manufacture']").val(d.result.product_manufacture);                    
          $("#form-master textarea[name='keterangan']").val(d.result.product_note);          
          // $("#form-master select[name='satuan']").val(d.result.product_unit).trigger('change');
          $("#form-master input[name='luas_tanah']").val(d.result.product_square_size);          
          $("#form-master input[name='luas_bangunan']").val(d.result.product_building_size);
          $("#form-master input[name='kamar_tidur']").val(d.result.product_bedroom);          
          $("#form-master input[name='kamar_mandi']").val(d.result.product_bathroom);
          $("#form-master input[name='garasi']").val(d.result.product_garage);

          $("#form-master select[name='status']").val(d.result.product_flag).trigger('change');
          $("#form-master select[name='ref']").val(d.result.product_ref_id).trigger('change');          
          // $("#form-master select[name='manufacture']").val(d.result.product_manufacture).trigger('change');
          // $("select[name='categories']").append(''+
                            // '<option value="'+d.result.category_id+'">'+
                              // d.result.category_name+
                            // '</option>');
          // $("select[name='categories']").val(d.result.category_id).trigger('change');

          $("select[name='kota']").append(''+
                            '<option value="'+d.location.city_id+'">'+
                              d.location.city_name+', '+d.location.province_name+
                            '</option>');
          $("select[name='kota']").val(d.location.city_id).trigger('change');
          
          $("select[name='tipe_properti']").append(''+
                            '<option value="'+d.result.product_type+'">'+
                              d.property_type+
                            '</option>');
          $("select[name='tipe_properti']").val(d.result.product_type).trigger('change');         

          $("select[name='contact']").append(''+
                            '<option value="'+d.contact.contact_id+'">'+
                              d.contact.contact_name+
                            '</option>');
          $("select[name='contact']").val(d.contact.contact_id).trigger('change');                    
          
          // if(parseInt(d.result.product_images) == 0) {
          //   $('#img-preview').attr('src', url_image);    
          // }else{
          //   $('#img-preview').attr('src', site_url+d.result.product_image);
          // }

          var total_image = d.image.length;
          console.log(total_image);
          var c = 1;
          for(var o=0; o<total_image; o++){
            var counter = c++;
            $("#img-preview"+counter).attr('src',site_url+d.image[o].product_item_image);
            console.log("#img-preview"+counter);
            console.log(d.image[o].product_item_image);
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

    // if(kode.val().length == 0){
    //   notif(0,'Kode wajib diisi');
    //   kode.focus();
    //   next=false;
    // }

    if(nama.val().length == 0){
      notif(0,'Nama wajib diisi');
      nama.focus();
      next=false;
    }    

    // if(next==true){
    //   if($("select[id='categories']").find(':selected').val() == 0){
    //     notif(0,'Kategori wajib dipilih');
    //     next=false;
    //   }   
    // }        

    if(next==true){
      if($("select[id='tipe_properti']").find(':selected').val() == undefined){
        notif(0,'Tipe Properti wajib dipilih');
        next=false;
      }   
    } 
    
    if(next==true){
      if($("select[id='contact']").find(':selected').val() == undefined){
        notif(0,'Agen wajib dipilih');
        next=false;
      }   
    } 

    if(next==true){
      if($("select[id='kota']").find(':selected').val() == undefined){
        notif(0,'Kota wajib dipilih');
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
      /*var prepare = {
        tipe: identity,
        id: $("input[id=id_document]").val(),
        kode: $("input[id='kode']").val(),
        nama: $("input[id='nama']").val(),
        keterangan: $("textarea[id='keterangan']").val(),        
        harga_beli: $("input[id='harga_beli']").val(),
        harga_jual: $("input[id='harga_jual']").val(),
        stok_minimal: $("input[id='stok_minimal']").val(),
        stok_maksimal: $("input[id='stok_maksimal']").val(),        
        satuan: $("select[id='satuan']").find(':selected').val(),
        upload: $('#upload')[0].files[0].name,
        status: $("select[id='status']").find(':selected').val()
      }
      var prepare_data = JSON.stringify(prepare);
      var data = {
        action: 'update',
        data: prepare_data
      };*/
      var formDataUpdate = new FormData();
      formDataUpdate.append('action', 'update');
      formDataUpdate.append('id', $('#id_document').val());  
      formDataUpdate.append('upload1', $('#upload1')[0].files[0]);
      formDataUpdate.append('upload2', $('#upload2')[0].files[0]);
      formDataUpdate.append('upload3', $('#upload3')[0].files[0]);
      formDataUpdate.append('upload4', $('#upload4')[0].files[0]);

      formDataUpdate.append('tipe', identity);
      // formDataUpdate.append('kode', $('#kode').val());
      formDataUpdate.append('nama', $('#nama').val());
      formDataUpdate.append('keterangan', $('#keterangan').val());
      // formDataUpdate.append('harga_beli', $('#harga_beli').val());
      formDataUpdate.append('harga_jual', $('#harga_jual').val());
      // formDataUpdate.append('harga_promo', $('#harga_promo').val());            
      // formDataUpdate.append('stok_minimal', $('#stok_minimal').val());  
      // formDataUpdate.append('stok_maksmal', $('#stok_maksimal').val());
      // formDataUpdate.append('manufacture', $('#manufacture').val());      
      // formDataUpdate.append('satuan', $('#satuan').find(':selected').val());   
      formDataUpdate.append('status', $('#status').find(':selected').val());
      // formDataUpdate.append('with_stock', $('#with_stock').find(':selected').val());      
      // formDataUpdate.append('categories', $('#categories').find(':selected').val());       
      formDataUpdate.append('ref', $('#ref').find(':selected').val());
      formDataUpdate.append('tipe_properti', $('#tipe_properti').find(':selected').val());
      formDataUpdate.append('contact', $('#contact').find(':selected').val());      
      formDataUpdate.append('kota', $('#kota').find(':selected').val());  
      formDataUpdate.append('luas_tanah', $('#luas_tanah').val());      
      formDataUpdate.append('luas_bangunan', $('#luas_bangunan').val());  
      formDataUpdate.append('kamar_tidur', $('#kamar_tidur').val());
      formDataUpdate.append('kamar_mandi', $('#kamar_mandi').val());                       
      formDataUpdate.append('garasi', $('#garasi').val());      
      $.ajax({
        type: "POST",     
        url: url,
        data: formDataUpdate,
        cache: false,
        dataType:"json",
        contentType: false,
        processData: false,         
        beforeSend:function(){},
        success:function(d){
          if(parseInt(d.status) == 1){
            $("#btn-new").show();
            $("#btn-save").hide();
            $("#btn-update").hide();
            $("#btn-cancel").hide();
            $("#form-master input").val(); 
            formMasterSetDisplay(1); 
            notif(1,'Berhasil Memperbarui');
            index.ajax.reload(null,false);
          }
          else{
            // notifError(d.message);  
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
      content: 'Apakah anda ingin <b>'+msg+'</b> properti <b>'+nama+'</b> ?',
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

  // Set Preview
  $(document).on("click",".btn-preview",function(e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    var title = $(this).attr("data-title");    
    var urls = $(this).attr("data-url");
    console.log(urls);
    $.alert('Harusnya Redirect to '+url_preview+urls);
  });  

  $('#upload1').change(function(e) {
    var fileName = e.target.files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#img-preview1').attr('src', e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  });
  $('#upload2').change(function(e) {
    var fileName = e.target.files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#img-preview2').attr('src', e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  });  
  $('#upload3').change(function(e) {
    var fileName = e.target.files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#img-preview3').attr('src', e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  });
  $('#upload4').change(function(e) {
    var fileName = e.target.files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#img-preview4').attr('src', e.target.result);
    };
    reader.readAsDataURL(this.files[0]);
  });  

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

  function formNew(){
    formMasterSetDisplay(0);
    $("#form-master input").val();
    $("#btn-new").hide();
    $("#btn-save").show();
    $("#btn-cancel").show();
  }
  function formCancel(){
    formMasterSetDisplay(1);
    $("#form-master input").val();      
    $("#btn-new").show();
    $("#btn-save").hide();
    $("#btn-update").hide();
    $("#btn-cancel").hide();
  } 
  function formMasterSetDisplay(value){ // 1 = Untuk Enable/ ditampilkan, 0 = Disabled/ disembunyikan
    if(value == 1){ var flag = true; }else{ var flag = false; }
    //Attr Input yang perlu di setel
    var form = '#form-master'; 
    var attrInput = [
      "kode",
      "nama",
      "barcode",            
      "harga_beli",
      "harga_jual",
      "harga_promo",
      "stok_minimal",
      "stok_maksimal",
      "luas_tanah","luas_bangunan","kamar_tidur","kamar_mandi","garasi"
      // "manufacture" 
    ];
    $("input[name='harga_beli']").val(0);
    $("input[name='harga_jual']").val(0);
    $("input[name='harga_promo']").val(0);    
    $("input[name='stok_minimal']").val(0);
    $("input[name='stok_maksimal']").val(0);    
    
    for (var i=0; i<=attrInput.length; i++) { $(""+ form +" input[name='"+attrInput[i]+"']").attr('readonly',flag); }

    //Attr Textarea yang perlu di setel
    var attrText = [
      "keterangan"
    ];
    for (var i=0; i<=attrText.length; i++) { $(""+ form +" textarea[name='"+attrText[i]+"']").attr('readonly',flag); }

    //Attr Select yang perlu di setel
    var atributSelect = [
      // "satuan",
      "status",
      // "categories",
      // "manufacture",
      // "with_stock",
      "tipe_properti","kota","ref"
    ];
    for (var i=0; i<=atributSelect.length; i++) { $(""+ form +" select[name='"+atributSelect[i]+"']").attr('disabled',flag); }      
  }
</script>