<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<style>
    select{min-height: 28px!important; height: 28px!important;} 
    .form-control{padding:0px 8px!important;}
    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Landscape tablets and medium desktops */
    @media (min-width: 992px) and (max-width: 1199px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Portrait tablets and small desktops */
    @media (min-width: 768px) and (max-width: 991px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
    }
    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .table-responsive{ overflow-x: unset; }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}
        .pull-right{height: auto!important;}        
    }    
</style>
<div class="row">
    <div class="col-md-12">
        <?php #include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="grid simple">
                    <div class="hidden grid-title">
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="#grid-config" data-toggle="modal" class="config"></a>
                            <a href="javascript:;" class="reload"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="grid-body">   
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                            <div class="col-md-3 col-xs-12 col-sm-12" style="padding-left: 0;">
                                <h5><b>Data <?php echo $title; ?></b></h5>
                            </div>
                            <div class="col-md-9 col-xs-12 col-sm-12 padding-remove-right">
                                <div class="pull-right">
                                    <!-- <button id="btn-excel-email" class="btn btn-default btn-small" type="button" style="display:inline;">
                                        <i class="fas fa-envelope"></i>
                                        Broadcast Email via Excel
                                    </button>
                                    <button id="btn-excel" class="btn btn-default btn-small" type="button" style="display:inline;">
                                        <i class="fab fa-whatsapp"></i>
                                        Broadcast WhatsApp via Excel
                                    </button>  
                                    <button id="btn-new" onClick="" class="btn btn-de btn-small" type="button"
                                            style="display: inline;">
                                        <i class="fas fa-plus"></i>
                                        Buat <?php echo $title; ?> Baru
                                    </button> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">                    
                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group padding-remove-left">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                    <label class="form-label">Cari</label>
                                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                </div>
                            </div>  
                            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-left">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <label class="form-label">Kontak</label>
                                    <select id="filter_contact" name="filter_contact" class="form-control">
                                        <option value="0">-- Semua --</option>
                                    </select>
                                </div>
                            </div>    -->
                            <!-- <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group padding-remove-left">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <label class="form-label">Media Pengiriman</label>
                                    <select id="filter_platform" name="filter_platform" class="form-control">
                                        <option value="0">Semua</option>
                                        <option value="1">WhatsApp</option>
                                        <option value="4">Email</option>   
                                    </select>
                                </div>
                            </div>   -->
                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group padding-remove-left">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <label class="form-label">Status <?php echo $title; ?></label>
                                    <select id="filter_flag" name="filter_flag" class="form-control">
                                        <option value="1">Terkirim</option>
                                        <option value="0">Belum Terkirim</option>
                                    </select>
                                </div>
                            </div>                              
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-side">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                    <label class="form-label">Tampil</label>
                                    <select id="filter_length" name="filter_length" class="form-control">
                                        <option value="10">10 Baris</option>
                                        <option value="25">25 Baris</option>
                                        <option value="50">50 Baris</option>
                                        <option value="100">100 Baris</option>
                                    </select>
                                </div>
                            </div>                   
                        </div>              
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">    
                            <div class="table-responsive">
                                <table id="table-data" class="table table-bordered">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab2">      
            </div>
        </div>	
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="" enctype="multipart/form-data">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Kirim Pesan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="grid simple">
                                <div class="grid-body" style="padding-bottom:8px;">
                                    <div class="col-md-3 col-sm-12 col-xs-12 padding-remove-side">
                                        <div class="grid simple">
                                            <div class="grid-body">   
                                                <h5><b>Device Pengiriman</b></h5> 
                                                <!-- <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Gambar*</label>
                                                        <img id="img-preview1" class="img-responsive" 
                                                                data-is-new="0"
                                                                style="width:100%"
                                                                src=""/>
                                                        <div class="hide custom-file">
                                                            <input class="form-control" id="upload1" name="upload1" type="file" tabindex="1">
                                                            <label class="custom-file-label">Maksimal 10MB</label>
                                                        </div>                                                                                                       
                                                    </div>
                                                </div> -->
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Media Pengiriman</label>
                                                        <select id="platform" name="platform" class="form-control" disabled>
                                                            <option value="1">WhatsApp</option>
                                                            <!-- <option value="2">Telegram</option> -->
                                                            <!-- <option value="3">SMS</option> -->
                                                            <option value="4">Email</option>                                                        
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- 
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="branch-label">Cabang</label>
                                                        <select id="branch" name="branch" class="form-control" disabled readonly>
                                                            <option value="0">-- Pilih --</option>                    
                                                        </select>            
                                                    </div>
                                                </div>       
                                                -->                                                                                                                                                                                                    
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="branch-label" class="form-label">Device</label>
                                                        <select id="device" name="device" class="form-control" disabled readonly>
                                                            <option value="0">-- Pilih --</option>                    
                                                        </select>            
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>                                                                                                                                                                                  
                                    </div>
                                    <div class="col-md-9 col-sm-12 col-xs-12 padding-remove-right">
                                        <div class="grid simple">
                                            <div class="grid-body">                            
                                                <h5><b>Penerima</b></h5>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="contact-label" class="form-label">Kontak</label>
                                                        <select id="contact" name="contact[]" multiple="multiple" class="form-control">
                                                            <option value="0">-- Pilih Kontak --</option>                    
                                                        </select>                          
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="contact-label" class="form-label">Tipe Kontak</label>
                                                        <select id="contact_type" name="contact_type[]" multiple="multiple" class="form-control">
                                                            <option value="0">-- Pilih Kontak Type --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="contact-label" class="form-label">Kategori Kontak</label>
                                                        <select id="contact_category" name="contact_category[]" multiple="multiple" class="form-control">
                                                            <option value="0">-- Pilih Kategori Kontak --</option>
                                                        </select>
                                                    </div>
                                                </div>   
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="contact-label" class="form-label">Group Kontak Broadcast</label>
                                                        <select id="recipient_group" name="recipient_group[]" multiple="multiple" class="form-control">
                                                            <option value="0">-- Pilih Kategori Kontak --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">                        
                                                        <label id="contact-label" class="form-label">Kontak Yg Akan Berulang Tahun</label>
                                                        <select id="recipient_birthday" name="recipient_birthday[]" multiple="multiple" class="form-control">
                                                            <option value="0">-- Pilih Kategori Kontak --</option>
                                                        </select>
                                                    </div>
                                                </div>                                                                                                  
                                            </div>
                                        </div>      
                                    </div>
                                </div>
                            </div>
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-12 col-sm-12 col-xs-12">                            
                                        <h5><b>Teks / Isi Pesan</b></h5>                                                         
                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                            <div class="form-group">
                                                <label class="form-label">Template Pesan (opsional)</label>
                                                <select id="news" name="news" class="form-control">
                                                    <option value="0">Tanpa Template Pesan</option>
                                                </select>
                                            </div>
                                        </div>    
                                        <div id="div_subject" class="col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="display:none;">
                                            <div class="form-group">
                                                <label class="form-label">Subject Email</label>
                                                <div class="controls">
                                                    <input name="subject" id="subject" type="text" class="form-control input-sm">
                                                </div>
                                            </div>
                                        </div>                                                                     
                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                            <div class="form-group">
                                                <label class="form-label">Isi Pesan</label>
                                                <textarea id="teks" name="teks" type="text" class="form-control" readonly='true' rows="4" style="height:200px!important;"/></textarea>
                                            </div>
                                        </div>           
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <!--
                    <button id="btn-save" data-action="0" class="btn btn-save btn-default btn-small" type="button" style="display:none;">
                        <i class="fas fa-file-import"></i>                                 
                        Kirim Nanti
                    </button>
                    -->                                                          
                    <button id="btn-save-now" data-action="1" class="btn btn-save btn-primary btn-small" type="button">
                        <i class="fas fa-paper-plane"></i>                                 
                        Kirim Langsung
                    </button>                                                                  
                    <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                        <i class="fas fa-edit"></i> 
                        Update
                    </button> 
                    <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                        <i class="fas fa-trash"></i> 
                        Delete
                    </button>   
                    <button id="btn-cancel" onClick="formCancel" class="btn btn-warning btn-small" type="button" style="display:none;">
                        <i class="fas fa-ban"></i>                                 
                        Batal
                    </button>                 
                </div>
            </form>      
        </div>
    </div>
</div>
<div class="modal fade" id="modal-read" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-product" name="form-product" method="" action="">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Deskripsi Pesan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <!-- <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Gambar</label>
                                    <img id="img-preview2" class="img-responsive" data-is-new="0" style="width:100%" src=""/>                                               
                                </div>
                            </div>   -->
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input id="message_date_created" name="message_date_created" type="text" value="" class="form-control" readonly="true"/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Dikirim</label>
                                    <input id="message_date_sent" name="message_date_sent" type="text" value="" class="form-control" readonly="true"/>
                                </div>
                            </div>         
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                <label class="form-label">Media Pengiriman</label>
                                <select id="message_platform" name="message_platform" class="form-control" readonly>
                                    <option value="0">Pilih</option>
                                    <option value="1">WhatsApp</option>
                                    <!-- <option value="2">Telegram</option> -->
                                    <!-- <option value="3">SMS</option> -->
                                    <option value="4">Email</option>   
                                </select>
                            </div>                                                                
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-md-6 col-sm-12 col-xs-12 padding-remove-left">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Sesi</label>
                                            <input id="message_session" name="message_session" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>  
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Device Pengirim</label>
                                            <select id="message_device" name="message_device" class="form-control" disabled="true">
                                            </select>
                                        </div>
                                    </div>
        
                                    <br><br>                         
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Status Terkirim</label>
                                            <select id="message_status" name="message_status" class="form-control" disabled="true">
                                                <option value="0">Antrian Pengiriman</option>
                                                <option value="1">Terkirim</option>
                                                <option value="2">Proses</option>
                                                <option value="4">Gagal Mengirim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Jenis Pesan</label>
                                            <select id="message_type" name="message_type" class="form-control" disabled="true">
                                                <option value="0">Tidak Diketahui</option>
                                                <option value="1">Teks</option>
                                                <option value="2">Gambar</option>
                                                <option value="3">Video</option>
                                                <option value="4">Dokumen</option>                                                                                      
                                            </select>                         
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">URL File</label>
                                            <input id="message_url" name="message_url" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                    <br><br>                
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Nomor Penerima</label>
                                            <input id="message_contact_number" name="message_contact_number" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Email Penerima</label>
                                            <input id="message_contact_email" name="message_contact_email" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>                                     -->
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Nama Penerima</label>
                                            <input id="message_contact_name" name="message_contact_name" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>                                                                                                                                  
                                </div>              
                                <div class="col-md-6 col-sm-12 col-xs-12 padding-remove-side">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Teks</label>
                                            <textarea id="message_text" name="message_text" type="text" value="" class="form-control" rows="4" style="height:400px!important;width:100%;"/></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <!-- <button id="btn-email-send-message" class="btn btn-success btn-small" type="button" data-id="0">
                        <i class="fas fa-paper-plane"></i>                                 
                        Kirim Ulang via Email
                    </button>     -->
                    <button id="btn-whatsapp-send-message" class="btn btn-primary btn-small" type="button" data-id="0">
                        <i class="fab fa-whatsapp"></i>                                 
                        Kirim Ulang via WhatsApp
                    </button>
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                   
                </div>
            </form>      
        </div>
    </div>
</div>
<div class="modal fade" id="modal-excel" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-excel" name="form-excel" method="" action="" enctype="multipart/form-data">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Broadcast WhatsApp via Excel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12"> 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-6 col-sm-12 col-xs-12">      
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Template Pesan (opsional)</label>
                                            <select id="broadcast_news" name="broadcast_news" class="form-control">
                                                <option value="0">Tanpa Template Pesan</option>
                                            </select>
                                        </div>
                                    </div>                                                                                                                       
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Teks / Isi Broadcast Pesan</label>
                                            <textarea id="broadcast_text" name="broadcast_text" type="text" value="" class="form-control" rows="4" style="height:400px!important;width:100%;"/></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">                        
                                            <label id="branch-label" class="form-label">Device</label>
                                            <select id="broadcast_device" name="broadcast_device" class="form-control">
                                                <option value="0">-- Pilih --</option>                    
                                            </select>            
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Contoh Penulisan Excel</label>
                                            <img src="<?php echo base_url('upload/template/broadcast_template_whatsapp.png');?>" class="img-responsive" style="width:100%;">
                                        </div>               
                                    </div>
                                    <!--
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">                        
                                            <label id="contact-label">Group Device Pengiriman</label>
                                            <select id="broadcast_group" name="broadcast_group" class="form-control">
                                            <option value="0">-- Pilih --</option>
                                            </select>                          
                                        </div>
                                    </div>  
                                    -->                
                                    <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Kode Tracking Broadcast</label>
                                            <input id="broadcast_session" name="broadcast_session" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <p class="p_information" style="padding:8px;background-color:#eaeaea;">
                                            <i class="fas fa-info-circle"></i> 
                                            Kode Tracking dapat diinput sendiri, anda dapat menyalin untuk digunakan saat pencarian data broadcast, 
                                            hanya boleh karakter A-Z, a-z, 0-9, tidak boleh menggunakan spasi
                                        </p>
                                    </div>                                                      
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Pilih File Excel ( .xls / .xlsx )</label>
                                            <input id="broadcast_file" type="file" class="form-control" name="broadcast_file" accept=".xls, .xlsx" required>
                                            <p>Template excel dapat diunduh <a href="<?php echo base_url('upload/template/broadcast_template_whatsapp.xlsx');?>">disini</a>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">                        
                                            <label id="contact-label">Pola Delay Setiap Pesan</label>
                                            <select id="broadcast_delay" name="broadcast_delay" class="form-control">
                                                <option value="123">Detik 1,2,3</option>
                                                <option value="246">Detik 2,4,6</option>
                                                <option value="357">Detik 3,5,7</option>                      
                                            </select>                          
                                        </div>
                                    </div>
                                    -->                   
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-excel-save" class="btn btn-primary btn-small" type="button" style="display:inline;">
                        <i class="fas fa-paper-plane"></i>                                 
                        Kirim Broadcast WhatsApp via Excel
                    </button>            
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                   
                </div>
            </form>      
        </div>
    </div>
</div>
<div class="modal fade" id="modal-email-excel" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-email-excel" name="form-email-excel" method="" action="" enctype="multipart/form-data">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Broadcast Email via Excel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12"> 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-6 col-sm-12 col-xs-12">      
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Template Pesan (opsional)</label>
                                            <select id="broadcast_news_email" name="broadcast_news_email" class="form-control">
                                                <option value="0">Tanpa Template Pesan</option>
                                            </select>
                                        </div>
                                    </div>                                                                                                                       
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Teks / Isi Broadcast Pesan</label>
                                            <textarea id="broadcast_text_email" name="broadcast_text_email" type="text" value="" class="form-control" rows="4" style="height:400px!important;width:100%;"/></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">                        
                                            <label id="branch-label" class="form-label">Device</label>
                                            <select id="broadcast_device_email" name="broadcast_device_email" class="form-control">
                                                <option value="0">-- Pilih --</option>                    
                                            </select>            
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Contoh Penulisan Excel</label>
                                            <img src="<?php echo base_url('upload/template/broadcast_template_email.png');?>" class="img-responsive" style="width:100%;">
                                        </div>               
                                    </div>
                                    <!--
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">                        
                                            <label id="contact-label">Group Device Pengiriman</label>
                                            <select id="broadcast_group" name="broadcast_group" class="form-control">
                                            <option value="0">-- Pilih --</option>
                                            </select>                          
                                        </div>
                                    </div>  
                                    -->                
                                    <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Kode Tracking Broadcast</label>
                                            <input id="broadcast_session_email" name="broadcast_session_email" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <p class="p_information" style="padding:8px;background-color:#eaeaea;">
                                            <i class="fas fa-info-circle"></i> 
                                            Kode Tracking dapat diinput sendiri, anda dapat menyalin untuk digunakan saat pencarian data broadcast, 
                                            hanya boleh karakter A-Z, a-z, 0-9, tidak boleh menggunakan spasi
                                        </p>
                                    </div>                                                      
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Pilih File Excel ( .xls / .xlsx )</label>
                                            <input id="broadcast_file_email" type="file" class="form-control" name="broadcast_file_email" accept=".xls, .xlsx" required>
                                            <p>Template excel dapat diunduh <a href="<?php echo base_url('upload/template/broadcast_template_email.xlsx');?>">disini</a>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">                        
                                            <label id="contact-label">Pola Delay Setiap Pesan</label>
                                            <select id="broadcast_delay" name="broadcast_delay" class="form-control">
                                                <option value="123">Detik 1,2,3</option>
                                                <option value="246">Detik 2,4,6</option>
                                                <option value="357">Detik 3,5,7</option>                      
                                            </select>                          
                                        </div>
                                    </div>
                                    -->                   
                                </div>   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-excel-email-save" class="btn btn-primary btn-small" type="button" style="display:inline;">
                        <i class="fas fa-paper-plane"></i>                                 
                        Kirim Broadcast Email via Excel
                    </button>            
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Tutup
                    </button>                   
                </div>
            </form>      
        </div>
    </div>
</div>