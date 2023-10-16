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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<div class="row">
    <div class="col-md-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div id="div_form_recipient" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                        <div class="grid simple">
                            <div class="grid-body">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <h5><b><?php echo $title;?></b></h5>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side"> 
                                            <form id="form_recipient" name="form_recipient" method="" action="">
                                                <div class="col-lg-12 col-md-12 col-xs-12">
                                                    <div class="form-group">
                                                        <input id="recipient_id" name="recipient_id" type="hidden" value="" placeholder="id" readonly>
                                                        <input id="recipient_session" name="recipient_session" type="hidden" value="" placeholder="session" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Nama</label>
                                                            <input id="recipient_name" name="recipient_name" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                                        <label class="form-label">Tgl Lahir</label>
                                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                <input name="recipient_birth" id="recipient_birth" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>      
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Email</label>
                                                            <input id="recipient_email" name="recipient_email" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">WhatsApp</label>
                                                            <input id="recipient_phone" name="recipient_phone" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>   
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">    
                                                    <div class="col-ld-12 col-md-12 padding-remove-side">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side form-group">
                                                                <label class="form-label">Group</label>
                                                                <select id="recipient_group_id" name="recipient_group_id" class="form-control" style="width:100%;">
                                                                    <option value="0">Pilih</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 form-group padding-remove-side">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side form-group">
                                                                <label class="form-label">Status *</label>
                                                                <select id="recipient_flag" name="recipient_flag" class="form-control" style="width:100%;">
                                                                    <option value="0">Nonaktif</option>
                                                                    <option value="1">Aktif</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                                    <div class="form-group">
                                                        <div class="pull-right">
                                                            <button id="btn_cancel_recipient" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                                <i class="fas fa-ban"></i> 
                                                                Cancel
                                                            </button>
                                                            <button id="btn_save_recipient" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                                <i class="fas fa-save"></i>
                                                                Save
                                                            </button>
                                                            <button id="btn_update_recipient" class="btn btn-info btn-small" type="button" style="display: none;">
                                                                <i class="fas fa-edit"></i> 
                                                                Update
                                                            </button> 
                                                            <button id="btn_delete_recipient" class="btn btn-danger btn-small" type="button" style="display: none;">
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
                                        <div class="col-md-12 col-xs-12 col-sm-12">
                                            <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                <h5><b>Data <?php echo $title;?></b></h5>
                                            </div>
                                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                <div class="pull-right">
                                                    <button id="btn_modal_recipient_group" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-list"></i>
                                                        Group <?php echo $title;?>
                                                    </button>   
                                                    <button id="btn_new_recipient" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                            <div class="clearfix"></div>
                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group padding-remove-right prs-15">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Cari</label>
                                                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group padding-remove-right prs-15">
                                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Status</label>
                                                    <select id="filter_flag" name="filter_flag" class="form-control">
                                                        <option value="All">Semua</option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Nonaktif</option>
                                                        <option value="4">Terhapus</option>
                                                    </select>
                                                </div>
                                            </div>                                            
                                            <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                    <label class="form-label">Tampil</label>
                                                    <select id="filter_length" name="filter_length" class="form-control">
                                                        <option value="10">10 Baris</option>
                                                        <option value="25">25 Baris</option>
                                                        <option value="50">50 Baris</option>
                                                        <option value="100">100 Baris</option>
                                                        <option value="-1">Semuanya</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:10px;">
                                            <div class="table-responsive">                                        
                                                <table id="table_recipient" class="table table-bordered" style="width:100%;">
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
                <button id="modal-croppie-cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal-croppie-save" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_recipient_group" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_recipient_group" name="form_recipient_group" method="" action="" enctype="multipart/form-data">         
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">Group <?php echo $title; ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="grid simple">
                                <div class="grid-body">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12 col-sm-12">
                                                <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                                    <h5><b>Data <?php echo $title;?></b></h5>
                                                </div>
                                                <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                                    <div class="pull-right">
                                                        <button id="btn_save_recipient_group" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                            <i class="fas fa-plus"></i>
                                                            Buat Group <?php echo $title; ?> Baru
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                                <div class="clearfix"></div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Status</label>
                                                        <select id="filter_group_flag" name="filter_group_flag" class="form-control">
                                                            <option value="All">Semua</option>
                                                            <option value="1">Aktif</option>
                                                            <option value="0">Nonaktif</option>
                                                            <option value="4">Terhapus</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group padding-remove-right">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Cari</label>
                                                        <input id="filter_group_search" name="filter_group_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <label class="form-label">Tampil</label>
                                                        <select id="filter_group_length" name="filter_group_length" class="form-control">
                                                            <option value="10">10 Baris</option>
                                                            <option value="25">25 Baris</option>
                                                            <option value="50">50 Baris</option>
                                                            <option value="100">100 Baris</option>
                                                            <option value="-1">Semuanya</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-xs-12 col-sm-12 table-responsive" style="padding-top:10px;">
                                                <table id="table_recipient_group" class="table table-bordered" style="width:100%;">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">                                                          
                    <!-- <button id="btn-save-now" data-action="1" class="btn btn-save btn-primary btn-small" type="button">
                        <i class="fas fa-paper-plane"></i>                                 
                        Simpan
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
                    </button>                  -->
                </div>
            </form>      
        </div>
    </div>
</div>