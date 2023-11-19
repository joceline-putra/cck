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
    .popover {
        z-index: 9999;
    }    
</style>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
                                                    <!-- <button id="btn_export_order" onClick="" class="btn btn-default btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-file-excel"></i>
                                                        Ekspor Excel
                                                    </button> -->
                                                    <button id="btn_new_order_2" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-check-double"></i>
                                                        Status Kamar
                                                    </button>
                                                    <button id="btn_new_order" class="btn btn-success btn-small" type="button" style="display: inline;">
                                                        <i class="fas fa-plus"></i>
                                                        Buat <?php echo $title; ?> Baru
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                            <div class="clearfix"></div>
                                            <div class="col-lg-12 col-md-12 col-xs-16 col-sm-16 form-group">
                                                <div class="panel-group" id="accordion" data-toggle="collapse" style="background-color: #eaeaea;border: 1px solid #eaeaea;margin-bottom:0px;">
                                                    <div id="panel-one" class="panel panel-default" style="display:inline;">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                                    <i class="fa fa-filter"></i> 
                                                                    Filter
                                                                </a>
                                                            </h4>                                                       
                                                        </div>
                                                        <div id="collapseOne" class="panel-collapse collapse">
                                                            <div class="panel-body" style="padding:0px;">
                                                                <div class="col-md-12 col-xs-12">

                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                                        <label class="form-label">Periode Awal</label>
                                                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                                <input name="filter_start_date" id="filter_start_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $first_date;?>">
                                                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                                        <label class="form-label">Periode Akhir</label>
                                                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                                                <input name="filter_end_date" id="filter_end_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group prs-15">
                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <label class="form-label">Cari</label>
                                                                            <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                                                        </div>
                                                                    </div>                                                                    
                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <label class="form-label">Cabang</label>
                                                                            <select id="filter_flag" name="filter_flag" class="form-control">
                                                                                <option value="All">Semua</option>
                                                                                <option value="1">Aktif</option>
                                                                                <option value="0">Nonaktif</option>
                                                                                <option value="4">Terhapus</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>    

                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <label class="form-label">Type</label>
                                                                            <select id="filter_flag" name="filter_flag" class="form-control">
                                                                                <option value="All">Semua</option>
                                                                                <option value="1">Aktif</option>
                                                                                <option value="0">Nonaktif</option>
                                                                                <option value="4">Terhapus</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>  

                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                                                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                                            <label class="form-label">Jenis Kamar</label>
                                                                            <select id="filter_flag" name="filter_flag" class="form-control">
                                                                                <option value="All">Semua</option>
                                                                                <option value="1">Aktif</option>
                                                                                <option value="0">Nonaktif</option>
                                                                                <option value="4">Terhapus</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>    
                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
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
                                                                    <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group">
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                   
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top:10px;">
                                            <div class="table-responsive">
                                                <table id="table_order" class="table table-bordered" style="width:100%;">
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
<!-- Modal -->
<div class="modal fade" id="modal_croppie" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal_croppie_canvas"></div>
            </div>
            <div class="modal-footer">
                <button id="modal_croppie_cancel" type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button id="modal_croppie_save" type="button" class="btn btn-primary">Crop</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_order" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
       <div class="modal-content">
            <form id="form_order" name="form_order" method="" action="">
                <div class="modal-header">
                    <h4 style="text-align:left;">Form Booking</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                        style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side"> 
                                        <form id="form_order" name="form_order" method="" action="">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Cabang (order_branch)</label>
                                                        <div class="radio radio-success">
                                                            <?php 
                                                            foreach($branch as $i => $v){
                                                                $c = '';
                                                                if($i==0){
                                                                    $c = 'checked';
                                                                }
                                                            ?>
                                                                <input id="branch_<?php echo $v['branch_id']; ?>" type="radio" name="order_branch_id" class="order_branch_id" value="branch_<?php echo $v['branch_id']; ?>" <?php echo $c; ?>><label for="branch_<?php echo $v['branch_id']; ?>"><?php echo $v['branch_name']; ?></label>
                                                            <?php 
                                                            } 
                                                            ?>
                                                            <!-- <input id="Cabang1" type="radio" name="order_branch" value="1" checked><label for="Cabang1">Cabang1</label> -->
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Jenis (order_type_2)</label>
                                                        <div class="radio radio-success">
                                                            <input id="Bulanan" type="radio" name="order_type_2" class="order_type_2" value="Bulanan" checked><label for="Bulanan">Bulanan</label>
                                                            <input id="Transit" type="radio" name="order_type_2" class="order_type_2" value="Transit"><label for="Transit">Transit</label>
                                                        </div>
                                                    </div>
                                                </div>                                                          
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Type (order_ref_price_id)</label>
                                                        <div class="radio radio-success">
                                                            <input id="Harian" type="radio" name="order_ref_price_id" class="order_ref_price_id" value="1" checked><label for="Harian">Harian</label>
                                                            <input id="Midnight" type="radio" name="order_ref_price_id" class="order_ref_price_id" value="2"><label for="Midnight">Midnight</label>
                                                            <input id="4Jam" type="radio" name="order_ref_price_id" class="order_ref_price_id" value="3"><label for="4Jam">4 Jam</label>
                                                            <input id="2Jam" type="radio" name="order_ref_price_id" class="order_ref_price_id" value="4"><label for="2Jam">2 Jam</label>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Jenis Kamar (order_ref)</label>
                                                        <div class="radio radio-success">
                                                            <?php 
                                                            foreach($ref as $i => $v){
                                                                $c = '';
                                                                if($i==0){
                                                                    $c = 'checked';
                                                                }
                                                            ?>
                                                                <input id="ref_<?php echo $v['ref_id']; ?>" type="radio" name="order_ref_id" class="order_ref_id" value="<?php echo $v['ref_id']; ?>" <?php echo $c; ?>><label for="ref_<?php echo $v['ref_id']; ?>"><?php echo $v['ref_name']; ?></label>
                                                            <?php 
                                                            } 
                                                            ?>
                                                            <!-- <input id="Cabang1" type="radio" name="order_branch" value="1" checked><label for="Cabang1">Cabang1</label> -->
                                                        </div>
                                                    </div>
                                                </div>                                                  
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                                    <label class="form-label">Tanggal (order_start_date)</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="order_start_date" id="order_start_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                                    <label class="form-label">Tanggal (order_end_date)</label>
                                                    <div class="col-md-12 col-sm-12 padding-remove-side">
                                                        <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                            <input name="order_end_date" id="order_end_date" type="text" class="form-control input-sm" readonly="true" value="<?php echo $end_date;?>">
                                                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group padding-remove-side">
                                                        <label class="form-label">Jam (order_start_hour)</label>
                                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                                            <div class="input-group transparent clockpicker col-md-12">
                                                                <input name="order_start_hour" id="order_start_hour" type="text" class="form-control input-sm" value="<?php echo $hour; ?>" >
                                                                <span class="input-group-addon clock-add"><i class="fas fa-clock"></i></span>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </div>             
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Harga (order_price)</label>
                                                        <input id="order_price" name="order_price" type="text" value="" class="form-control"/>
                                                    </div>
                                                </div>                                                                                                                                                                                                                                   
                                            </div> 
                                            <div class="col-md-6 col-sm-12 col-xs-12">
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">KTP</label>
                                                        <input id="order_contact_code" name="order_contact_code" type="text" value="" class="form-control"/>
                                                    </div>
                                                </div>     
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">Nama</label>
                                                        <input id="order_contact_name" name="order_contact_name" type="text" value="" class="form-control"/>
                                                    </div>
                                                </div>                                                         
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                        <label class="form-label">WhatsApp</label>
                                                        <input id="order_contact_phone" name="order_contact_phone" type="text" value="" class="form-control"/>
                                                    </div>
                                                </div>   
                                            </div>
                                            <!-- Start of Approval & Attachment -->
                                            <?php if(($module_approval == 1) or ($module_attachment == 1)){ ?>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <?php if($module_approval == 1){ ?>
                                                <div class="panel-group" id="accordion" data-toggle="collapse" style="background-color: #eaeaea;border: 1px solid #eaeaea;margin-bottom:0px;">
                                                    <div id="panel-zero" class="panel panel-default" style="display:inline;">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseZero">
                                                                    <i class="fa fa-lock"></i> 
                                                                    Data Persetujuan 
                                                                    <span id="badge_approval" class="badge badge-default">0</span> 
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseZero" class="panel-collapse collapse">
                                                            <div class="panel-body" style="padding:0px;">
                                                                <table class="table" id="table_approval" style="background-color:white; ">
                                                                    <thead>
                                                                        <tr>
                                                                            <td>Date</td>
                                                                            <td>User</td>
                                                                            <td>Status</td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!-- <tr>
                                                                            <td>20-Juni-2023</td>
                                                                            <td>Root Admin</td>
                                                                            <td><label class="label label-primary">Approved</label></td>
                                                                        </tr>                                                                                 -->
                                                                    </tbody>
                                                                </table>
                                                                <div class="col-md-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        <div class="pull-right">                            
                                                                            <button id="btn_approval_add" class="btn btn-primary btn-small" type="button">
                                                                                <i class="fas fa-file-signature"></i>
                                                                                Tambah Persetujuan
                                                                            </button>                                                                                                                     
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <?php } ?>
                                                <?php if($module_attachment == 1){ ?>
                                                <div class="panel-group" id="accordion" data-toggle="collapse" style="background-color: #eaeaea;border: 1px solid #eaeaea;margin-bottom:0px;">
                                                    <div id="panel-one" class="panel panel-default" style="display:inline;">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a class="" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                                    <i class="fa fa-paperclip"></i> 
                                                                    Data Attachment 
                                                                    <span id="badge_attachment" class="badge badge-default">0</span>  
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapseOne" class="panel-collapse collapse in">
                                                            <div class="panel-body" style="padding:0px;">
                                                                <table class="table" id="table_attachment" style="background-color:white; ">
                                                                    <thead>    
                                                                        <tr>
                                                                            <td>Name</td>
                                                                            <td style="text-align:right;">Size</td>
                                                                            <td>Date Created</td>
                                                                            <td>Format</td>                                            
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <!-- <tr>
                                                                            <td>20-Juni-2023</td>
                                                                            <td><a class="btn_attachment_preview" href="#" style="cursor:pointer;">Data_bulan_sep.pdf</a></td>
                                                                            <td><label class="label label-primary"><a class="btn_attachment_preview" href="#" style="cursor:pointer;color:white;">pdf</a></label></td>                                                                                    
                                                                        </tr>                                                                                 -->
                                                                    </tbody>
                                                                </table>
                                                                <div class="col-md-12 col-xs-12">
                                                                    <div class="form-group">
                                                                        <div class="pull-right">                            
                                                                            <button id="btn_link_add" class="btn btn-primary btn-small" type="button">
                                                                                <i class="fas fa-link"></i>
                                                                                Tambah Link Sharing
                                                                            </button>    
                                                                            <button id="btn_attachment_add" class="btn btn-primary btn-small" type="button">
                                                                                <i class="fas fa-paperclip"></i>
                                                                                Tambah Attachment
                                                                            </button>                                                                                                                                                                                                         
                                                                        </div>
                                                                    </div>
                                                                </div>                                                                        
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                              
                                                <?php } ?>                                                                                    
                                            </div>           
                                            <?php } ?>                                                                        
                                            <!-- End of Approval & Attachment -->                                             
                                            <div class="clearfix"></div>
                                            <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                                <div class="form-group">
                                                    <div class="pull-right">
                                                        <button id="btn_cancel_order" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                            <i class="fas fa-ban"></i> 
                                                            Cancel
                                                        </button>
                                                        <button id="btn_save_order" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                            <i class="fas fa-save"></i>
                                                            Save
                                                        </button>
                                                        <button id="btn_update_order" class="btn btn-info btn-small" type="button" style="display: none;">
                                                            <i class="fas fa-edit"></i> 
                                                            Update
                                                        </button> 
                                                        <button id="btn_delete_order" class="btn btn-danger btn-small" type="button" style="display: none;">
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
                <div class="modal-footer flex-center">
                    <button id="btn_save_order" class="btn btn-primary" type="button" style="width:45%;">
                        <i class="fas fa-save"></i> 
                        Simpan
                    </button>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal" style="width:45%;">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>