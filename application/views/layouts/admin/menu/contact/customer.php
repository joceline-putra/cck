<style>
    .scroll {
        margin-top: 4px;
        margin-bottom: 8px;
        margin-left: 4px;
        margin-right: 4px;
        padding: 4px;
        /*background-color: green; */
        width: 100%;
        height: 200px;
        overflow-x: hidden;
        overflow-y: auto;
        text-align: justify;
    }
    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Landscape tablets and medium desktops */
    @media (min-width: 992px) and (max-width: 1199px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Portrait tablets and small desktops */
    @media (min-width: 768px) and (max-width: 991px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        .table-responsive{
            overflow-x: unset;
        }
    }

    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .tab-content > .active{
            padding: 8px!important;
        }  
        .padding-remove-left, .padding-remove-right{
            padding-left:0px!important;
            padding-right:0px!important;    
        }
        .padding-remove-side{
            padding-left: 5px!important;
            padding-right: 5px!important;
        }
        .form-label{
            /*padding-left: 5px!important;*/
        }
        .prs-0{
            padding-left: 0px!important;
            padding-right: 0px!important;    
        }
        .prs-0 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-0 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-0 > input{
            margin-left: 0px!important;
            margin-right: 0px!important;    
        }
        .prs-0 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }

        .prs-5{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > label{
            padding-left: 5px!important;
            padding-right: 5px!important;    
        }
        .prs-5 > div{
            /*padding-left: 5px!important;*/
            /*padding-right: 5px!important;    */
        }
        .prs-5 > input{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }
        .prs-5 > select{
            margin-left: 5px!important;
            margin-right: 5px!important;    
        }    

        .prl-2{
            padding-left: 2.5px!important;
        }
        .prr-2{
            padding-right: 2.5px!important;
        }    
    }    
</style>
<div class="row">
    <div class="col-md-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">

                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div id="div-form-trans" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">            
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                    <h5><b><?php echo $title; ?></b></h5>  
                                    <div class="row">        
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0"> 
                                            <form id="form-master" name="form-master" method="" action="">
                                                <div class="col-lg-12 col-md-12 col-xs-12">
                                                    <div class="form-group">
                                                        <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                                        <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>                            
                                                    </div>
                                                </div>    
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 padding-remove-left prl-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Kode *</label>
                                                            <input id="kode" name="kode" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7 col-md-12 col-sm-7 col-xs-7 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Group *</label>
                                                            <select id="categories" name="categories" class="form-control" disabled readonly>
                                                                <option value="0">-- Pilih --</option>
                                                            </select>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Nama Lengkap *</label>
                                                            <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>  
                                                    <!--    
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Identitas</label>
                                                                <select id="identity_type" name="identity_type" class="form-control" disabled readonly>
                                                                    <option value="0">Pilih</option>
                                                                    <option value="KTP">KTP</option>
                                                                    <option value="SIM">SIM</option>
                                                                    <option value="PASPORT">Pasport</option>
                                                                </select>
                                                            </div>
                                                        </div>    
                                                        <div class="col-lg-8 col-md-8 col-xs-8 col-sm-8 padding-remove-side prr-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Nomor Identitas</label>
                                                                <input id="identity_number" name="identity_number" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>                          
                                                        </div>             
                                                    </div> 
                                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                                                      <div class="form-group">
                                                        <label>NPWP</label>
                                                        <input id="npwp" name="npwp" type="text" value="" class="form-control" readonly='true'/>
                                                      </div>                          
                                                    </div>  
                                                    -->     
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> 
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Telepon *</label>
                                                                <input id="telepon_1" name="telepon_1" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>                          
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 padding-remove-side prr-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Email</label>
                                                                <input id="email_1" name="email_1" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>                          
                                                        </div>   
                                                    </div>                       
                                                    <!--
                                                      <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">            
                                                        <div class="form-group">
                                                          <label>Telepon 2</label>
                                                          <input id="telepon_2" name="telepon_2" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>                             
                                                      </div>
                                                      <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">            
                                                        <div class="form-group">
                                                          <label>Email 2</label>
                                                          <input id="email_2" name="email_2" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>                             
                                                      </div>                          
                                                    -->                                                   
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Alamat *</label>
                                                            <textarea id="alamat" name="alamat" type="text" value="" class="form-control" readonly='true' rows="4"/></textarea>
                                                        </div>
                                                    </div>                                                                                                          
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Perusahaan</label>
                                                            <input id="perusahaan" name="perusahaan" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <!--
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                      <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                                                        <div class="form-group">
                                                          <label>Handphone</label>
                                                          <input id="handphone" name="handphone" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>                          
                                                      </div>
                                                      <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                                                        <div class="form-group">
                                                          <label>Fax</label>
                                                          <input id="fax" name="fax" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>                          
                                                      </div>
                                                    </div>  
                                                    -->
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">                                                    
                                                        <div class="col-md-6 col-xs-6 col-sm-6 padding-remove-left">
                                                            <div class="form-group">
                                                                <label class="form-label">Aturan Termin (Penjualan)</label>
                                                                <select id="contact_termin" name="contact_termin" class="form-control">
                                                                    <!-- <option value="0">-- Pilih Akun --</option> -->                                
                                                                </select>
                                                            </div>
                                                        </div>    
                                                        <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                                            <div class="form-group">
                                                                <label class="form-label">Limit Piutang</label>
                                                                <input id="contact_receivable_limit" name="contact_receivable_limit" type="text" value="" class="form-control" readonly='true'/>
                                                            </div>
                                                        </div>                                                     
                                                    </div>    
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Sales / Karyawan</label>
                                                            <select id="contact_parent_id" name="contact_parent_id" class="form-control" disabled readonly>
                                                                <option value="0">Pilih</option>
                                                            </select>
                                                        </div>
                                                    </div>                    
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Catatan</label>
                                                            <textarea id="note" name="note" type="text" value="" class="form-control" readonly='true' rows="4"/></textarea>
                                                        </div>
                                                    </div> 
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Status</label>
                                                            <select id="status" name="status" class="form-control" disabled readonly>
                                                                <!-- <option value="">select</option> -->
                                                                <?php
                                                                $status_values = array(
                                                                    '1' => 'Aktif',
                                                                    '0' => 'Nonaktif',
                                                                );

                                                                foreach ($status_values as $value => $display_text) {
                                                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display_text . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>                                
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Hutang *</label>
                                                            <select id="account_payable" name="account_payable" class="form-control">
                                                                <!-- <option value="0">-- Pilih Akun --</option> -->
                                                                <?php echo '<option value="' . $account_payable['account_id'] . '">' . $account_payable['account_code'] . ' - ' . $account_payable['account_name'] . '</option>'; ?>                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Piutang *</label>
                                                            <select id="account_receivable" name="account_receivable" class="form-control">
                                                                <!-- <option value="0">-- Pilih Akun --</option> -->
                                                                <?php echo '<option value="' . $account_receivable['account_id'] . '">' . $account_receivable['account_code'] . ' - ' . $account_receivable['account_name'] . '</option>'; ?>                                
                                                            </select>
                                                        </div>
                                                    </div>                                                                                                                               
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Gambar <?php echo $title; ?> <?php echo $image_width; ?> x <?php echo $image_height; ?> px</label>
                                                            <!--
                                                            <img id="img-preview1" class="img-responsive" 
                                                                data-is-new="0"
                                                                style="width:100%"
                                                                src=""/>
                                                            -->
                                                            <a class="files_link" href="<?= site_url('upload/noimage.png'); ?>">
                                                                <img id="files_preview" src="<?= site_url('upload/noimage.png'); ?>" class="img-responsive" height="120px" width="240px" style="margin-bottom:5px;"/>
                                                            </a>
                                                            <div class="custom-file">
                                                                <input class="form-control" id="files" name="files" type="file" tabindex="1">
                                                                <!-- <label class="custom-file-label">Pilih Gambar</label> -->
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">                          
                                                            <label>Fungsi Kontak Sebagai</label>                          
                                                            <!-- <label><input id="checkbox_supplier" type="checkbox"  id="checkbox_supplier" value="1">&nbsp;<?php #echo $title; ?></label> -->
                                                            <!-- <label><input id="checkbox_customer" type="checkbox"  id="checkbox_customer" value="2">&nbsp;Customer</label> -->
                                                            <!-- <label><input id="checkbox_karyawan" type="checkbox"  id="checkbox_karyawan" value="3">&nbsp;Karyawan</label> -->
                                                        </div>
                                                        <div class="checkbox check-success">
                                                            <input id="checkbox_supplier" type="checkbox" value="1"><label for="checkbox_supplier">&nbsp;Supplier</label>
                                                        </div>
                                                        <div class="checkbox check-success">
                                                            <input id="checkbox_customer" type="checkbox" value="2"><label for="checkbox_customer">&nbsp;<?php echo $title; ?></label>
                                                        </div>
                                                        <div class="checkbox check-success">
                                                            <input id="checkbox_karyawan" type="checkbox" value="3"><label for="checkbox_karyawan">&nbsp;Karyawan</label>
                                                        </div>                                                                                                                
                                                    </div>
                                                </div>                        
                                                <div class="clearfix"></div>
                                                <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                                    <div class="form-group">
                                                        <div class="pull-right">
                                                            <button id="btn-cancel" onClick="formCancel();" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                                <i class="fas fa-ban"></i> 
                                                                Cancel
                                                            </button>                                                                  
                                                            <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                                <i class="fas fa-save"></i>                                 
                                                                Save
                                                            </button>                                        
                                                            <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-edit""></i> 
                                                                Update
                                                            </button> 
                                                            <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-trash"></i> 
                                                                Delete
                                                            </button>                                   
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>      
                                </div>                    
                            </div>
                        </div>  
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">                
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
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 col-sm-12 prs-5">
                                            <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                <h5><b>Data <?php echo $title; ?></b></h5>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <button id="btn_import_excel" class="hidden-xs hidden-sm btn btn-default btn-small" type="button"
                                                            style="display: inline;">
                                                        <i class="fas fa-file-excel"></i>
                                                        Import via Excel
                                                    </button>        
                                                    <button id="btn-print-all" class="btn btn-default btn-small" type="button"
                                                            style="display: inline;">
                                                        <i class="fas fa-print"></i>
                                                        Print Data
                                                    </button>                                                                  
                                                    <button id="btn-new" class="btn btn-success btn-small" type="button"
                                                            style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                            <div class="col-lg-3 col-md-3 col-xs-8 col-sm-8 form-group padding-remove-right">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Group <?php echo $title; ?></label>
                                                    <select id="filter_categories" name="filter_categories" class="form-control">
                                                        <option value="0">Semua</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-4 col-sm-4 form-group padding-remove-right">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Status</label>
                                                    <select id="filter_flag" name="filter_flag" class="form-control">
                                                        <option value="100">Semua</option>                            
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>                          
                                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Cari</label>
                                                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                </div>
                                            </div>                                 
                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group prs-0">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table id="table-data" class="table table-bordered" style="width:100%;" data-limit-start="0" data-limit-end="10">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<div class="modal fade" id="modal-croppie" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal-croppie-canvas"></div>
            </div>
            <div class="modal-footer">
                <button id="modal-croppie-save" type="button" class="btn btn-primary"><span class="fas fa-crop"></span> Crop Gambar</button>                
                <button id="modal-croppie-cancel" type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span> Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_contact_excel" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_contact_excel" name="form_contact_excel" method="" action="" enctype="multipart/form-data">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Import <?php echo $title; ?> via Excel</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 padding-remove-side"> 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Contoh Penulisan Excel</label>
                                            <img src="<?php echo base_url('upload/template/template_customer.png');?>" class="img-responsive" style="width:100%;">
                                        </div>               
                                    </div>                                 
                                </div> 
                                <div class="col-md-12 col-sm-12 col-xs-12">                                                 
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label class="form-label">Pilih File Excel ( .xls / .xlsx )</label>
                                            <input id="excel_file" type="file" class="form-control" name="excel_file" accept=".xls, .xlsx" required>
                                            <p>Template excel dapat diunduh <a href="<?php echo base_url('upload/template/template_customer.xlsx');?>">disini</a>
                                        </div>
                                    </div>              
                                </div>                                   
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn_import_excel_save" class="btn btn-primary btn-small" type="button" style="display:inline;">
                        <i class="fas fa-paper-plane"></i>                                 
                        Import
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