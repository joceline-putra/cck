<style type="text/css">
    #table-data td{
        font-family: MONOSPACE;
    }

    /* Large desktops and laptops */
    @media (min-width: 1200px) {
        .table-responsive{
            overflow-x: unset;
        }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}        
    }

    /* Landscape tablets and medium desktops */
    @media (min-width: 992px) and (max-width: 1199px) {
        .table-responsive{
            overflow-x: unset;
        }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}        
    }

    /* Portrait tablets and small desktops */
    @media (min-width: 768px) and (max-width: 991px) {
        .table-responsive{
            overflow-x: unset;
        }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}        
    }

    /* Landscape phones and portrait tablets */
    @media (max-width: 767px) {
        .table-responsive{
            overflow-x: unset;
        }
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}        
    }

    /* Portrait phones and smaller */
    @media (max-width: 480px) {
        .table-responsive{
            overflow-x: unset;
        }        
        .prs-15{padding-left: 15px!important;padding-right: 15px!important;}        
    }    
</style>
<div class="row">
    <div class="col-md-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-left">
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
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <h5><b>Data <?php echo $title; ?></b></h5>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:10px;">                                  
                                        <div class="col-lg-7 col-md-7 col-xs-12 form-group padding-remove-right prs-15">            
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Cari</label>                          
                                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>  
                                        </div> 
                                        <div class="col-lg-3 col-md-3 col-xs-6 form-group padding-remove-right prs-15">
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Media</label>                          
                                                <select id="filter_media" name="filter_media" class="form-control">
                                                    <option value="ALL">Semua</option>
                                                    <option value="WhatsApp">WhatsApp</option>
                                                    <!-- <option value="Telegram">Telegram</option> -->
                                                    <!-- <option value="SMS">SMS</option> -->
                                                    <option value="Email">Email</option>
                                                </select>
                                            </div>
                                        </div>                                           
                                        <div class="col-lg-2 col-md-2 col-xs-6 form-group">
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
                                            <table id="table-data" class="table table-bordered" style="width:100%;">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>  
                <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">
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
                            <h5><b>Form <?php echo $title; ?></b></h5>                            
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <form id="form-master" name="form-master" method="" action="">    
                                        <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                        <div class="col-md-12">
                                            <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">          
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Media</label>
                                                    <select id="device_media" name="device_media" class="form-control" disabled readonly>
                                                        <option value="0">Pilih</option>
                                                        <option value="WhatsApp">WhatsApp</option>
                                                        <!-- <option value="Telegram">Telegram</option> -->
                                                        <!-- <option value="SMS">SMS</option> -->
                                                        <option value="Email">Email</option>                                                                                                                                                                        
                                                    </select>
                                                </div>         
                                            </div>    
                                            <div class="div_whatsapp col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="display:none;">                                            
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                                                    <div class="form-group">
                                                        <label class="form-label">Nomor *</label>
                                                        <input id="number" name="number" type="text" value="" class="form-control" readonly='true'/>
                                                    </div>
                                                </div>                  
                                                <div class="col-lg-6 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Label *</label>
                                                        <input id="label" name="label" type="text" value="" class="form-control" readonly='true'/>
                                                    </div>
                                                </div> 
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Token (otomatis)</label>
                                                        <input id="auth" name="auth" type="text" value="" class="form-control" readonly='true'/>
                                                    </div>
                                                </div>                   
                                            </div>
                                            <!--
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Group</label>
                                                    <select id="group" name="group" class="form-control" disabled readonly>
                                                        <option value="0">Pilih</option>
                                                    </select>
                                                </div>         
                                            </div>
                                            -->
                                            <div class="div_email col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="display:none;">
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">                                                
                                                    <div class="col-lg-8 col-md-8 col-xs-9 padding-remove-left">
                                                        <div class="form-group">
                                                            <label class="form-label">SMTP Host</label>
                                                            <input id="device_mail_host" name="device_mail_host" type="text" value="" class="form-control" readonly='true' placeholder="yoursite.com"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-3 padding-remove-right">
                                                        <div class="form-group">
                                                            <label class="form-label">SMTP Port</label>
                                                            <select id="device_mail_port" name="device_mail_port" class="form-control" disabled readonly>
                                                                <option value="465">465 / SSL</option>
                                                                <option value="587">587 / TLS</option>                                                                                                                                                            
                                                            </select>
                                                        </div>         
                                                    </div>                                                       
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-left">
                                                        <div class="form-group">
                                                            <label class="form-label">SMTP Email</label>
                                                            <input id="device_mail_email" name="device_mail_email" type="text" value="" class="form-control" readonly='true' placeholder="noreply@yoursite.com"/>
                                                        </div>
                                                    </div>      
                                                    <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-right">
                                                        <div class="form-group">
                                                            <label class="form-label">SMTP Email Password</label>
                                                            <input id="device_mail_password" name="device_mail_password" type="text" value="" class="form-control" readonly='true' placeholder="********"/>
                                                        </div>
                                                    </div> 
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <p class="p_information" style="padding:8px;background-color:#eaeaea;display:none;">
                                                            <i class="fas fa-info-circle"></i> 
                                                            Saat Mode Edit: Password jika diisi maka akan memperbarui, jika di kosongkan maka password sebelumnya yg berlaku
                                                        </p>
                                                    </div>                                                                                                            
                                                </div>   
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-left">
                                                        <div class="form-group">
                                                            <label class="form-label">SMTP Email From (Alias)</label>
                                                            <input id="device_mail_from_alias" name="device_mail_from_alias" type="text" value="" class="form-control" readonly='true' placeholder="noreply@yoursite.com"/>
                                                        </div>
                                                    </div>      
                                                    <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-right">
                                                        <div class="form-group">
                                                            <label class="form-label">SMTP Reply-To</label>
                                                            <input id="device_mail_reply_alias" name="device_mail_reply_alias" type="text" value="" class="form-control" readonly='true' placeholder="replyto@yoursite.com"/>
                                                        </div>
                                                    </div>                                                         
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

                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-top: 22px;">
                                                <div class="form-group">
                                                    <div class="pull-right">
                                                        <button id="btn-new" onClick="formNew();" class="btn btn-success btn-small" type="button">
                                                            <i class="fas fa-file-medical"></i> 
                                                            Buat Baru
                                                        </button>
                                                        <button id="btn-cancel" onClick="formCancel();" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                            <i class="fas fa-ban"></i> 
                                                            Cancel
                                                        </button>                                                                  
                                                        <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                            <i class="fas fa-save"></i>                                 
                                                            Save
                                                        </button>                                        
                                                        <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                                                            <i class="fas fa-edit"></i> 
                                                            Update
                                                        </button> 
                                                        <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                            <i class="fas fa-trash"></i> 
                                                            Delete
                                                        </button>                                   
                                                    </div>
                                                </div>
                                            </div>                                                                                                                       
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>                  
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
<!-- Modal -->
<div class="modal fade" id="modal-qrcode" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-product" name="form-product" method="" action="">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Scan QR Code</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <img id="qrcodes" src="#" class="img-responsive" style="margin:0 auto;">
                            <!-- <canvas id="qrcodes">
                            </canvas> -->
                            <!-- <div id="qrcodes"> -->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn_qrcode_repeat" onClick="" class="btn btn-primary btn-small" type="button" data-id="0">
                        <i class="fas fa-paper-plane"></i>                                 
                        Request Ulang
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