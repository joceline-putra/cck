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
                            <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side prs-0">
                                <div class="row">                
                                    <div class="col-md-12 col-xs-12 col-sm-12">
                                        <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                            <h5><b>Data <?php echo $title; ?></b></h5>
                                        </div>
                                        <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
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
                                            <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-left">
                                                <div class="form-group">
                                                    <label>Kode *</label>
                                                    <input id="kode" name="kode" type="text" value="" class="form-control" readonly='true'/>
                                                </div>
                                            </div>                  
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Nama *</label>
                                                    <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                                                </div>
                                            </div> 
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Keterangan</label>
                                                    <input id="keterangan" name="keterangan" type="text" value="" class="form-control" readonly='true'/>
                                                </div>
                                            </div>                   
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Status</label>
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