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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <?php $this->load->view('layouts/admin/menu/product/_navigation'); ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                    <div class="grid simple">
                        <div class="grid-body">                                   
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <h5><b>Data <?php echo $title; ?></b></h5>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                            <div class="pull-right">
                                                <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                                                        style="display: inline;">
                                                    <i class="fas fa-plus"></i>
                                                    Buat <?php echo $title; ?> Baru
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:10px;">
                                        <div class="col-lg-10 col-md-10 col-xs-12 form-group padding-remove-right">            
                                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <label class="form-label">Cari</label>                          
                                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                                            </div>  
                                        </div> 
                                        <div class="col-lg-2 col-md-2 col-xs-12 form-group prs-0">
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
            </div>
            <div class="tab-pane" id="tab2">
            </div>
        </div>	
    </div>
</div>

<div class="modal fade" id="modal_ref" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
       <div class="modal-content">
            <form id="form_ref" name="form_ref" method="" action="">
                <div class="modal-header">
                    <h4 style="text-align:left;">Form Jenis Kamar</h4>
                    <button class="btn btn-outline-danger waves-effect" type="button" data-dismiss="modal"
                        style="position:relative;top:-38px;float:right;">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                        <div class="col-md-12">
                            <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">       
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label class="form-label">Cabang</label>
                                    <div class="radio radio-success">
                                        <?php 
                                        foreach($branch as $i => $v){
                                            $c = '';
                                            if($i==0){
                                                $c = 'checked';
                                            }
                                        ?>
                                            <input id="branch_<?php echo $v['branch_id']; ?>" type="radio" name="ref_branch_id" value="<?php echo $v['branch_id']; ?>" <?php echo $c; ?>><label for="branch_<?php echo $v['branch_id']; ?>"><?php echo $v['branch_name']; ?></label>
                                        <?php 
                                        } 
                                        ?>
                                    </div>
                                </div>
                            </div> 
                                        
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label>Nama *</label>
                                    <input id="nama" name="nama" type="text" value="" class="form-control"/>
                                </div>
                            </div> 
                            <!-- <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input id="keterangan" name="keterangan" type="text" value="" class="form-control" readonly='true'/>
                                </div>
                            </div>  -->       
                            <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                <div class="form-group">
                                    <label>Promo</label>
                                    <input id="order_ref_price_id_0" name="order_ref_price_id_0" type="text" value="" class="form-control"/>
                                </div>
                            </div>               
                            <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                <div class="form-group">
                                    <label>Bulanan</label>
                                    <input id="order_ref_price_id_1" name="order_ref_price_id_1" type="text" value="" class="form-control"/>
                                </div>
                            </div>               
                            <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                <div class="form-group">
                                    <label>Harian</label>
                                    <input id="order_ref_price_id_2" name="order_ref_price_id_2" type="text" value="" class="form-control"/>
                                </div>
                            </div>                                                             
                            <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                <div class="form-group">
                                    <label>Midnight</label>
                                    <input id="order_ref_price_id_3" name="order_ref_price_id_3" type="text" value="" class="form-control"/>
                                </div>
                            </div>                                                            
                            <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                <div class="form-group">
                                    <label>4 Jam</label>
                                    <input id="order_ref_price_id_4" name="order_ref_price_id_4" type="text" value="" class="form-control"/>
                                </div>
                            </div>  
                            <div class="col-lg-6 col-md-6 col-xs-6 padding-remove-side">
                                <div class="form-group">
                                    <label>2 Jam</label>
                                    <input id="order_ref_price_id_5" name="order_ref_price_id_5" type="text" value="" class="form-control"/>
                                </div>
                            </div>                                                                                                                                                                                                   
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="status" name="status" class="form-control">
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

                                                                                                                     
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="margin-top: 22px;">
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
            </form>
        </div>
    </div>
</div>