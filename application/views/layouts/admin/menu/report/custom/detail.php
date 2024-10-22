<style>
    .scroll { 
        margin-top:4px;
        margin-bottom: 8px;
        margin-left:4px;
        margin-right: 4px; 
        padding:4px; 
        /*background-color: green; */
        width: 100%; 
        height: 400px; 
        overflow-x: hidden; 
        overflow-y: auto; 
        text-align:justify; 
    } 
    #filter_date{
		color:#5e5e5e!important;
	}
    .monthselect, .yearselect{
		height: 22px!important;
	}    
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="grid simple">
                        <div class="grid-body">
                            <div class="row">    
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                        <h5><b><?php echo $title; ?></b></h5>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                        <div class="pull-right">
                                            <!--
                                            <button id="btn-export" onClick="" class="btn btn-default btn-small" type="button"
                                              style="display: inline;">
                                              <i class="fas fa-file-excel"></i>
                                              Ekspor Excel
                                            </button>                      
                                            <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                                              style="display: inline;">
                                              <i class="fas fa-plus"></i>
                                              Buat <?php echo $title; ?> Baru
                                            </button>
                                            -->
                                            <a class="btn btn-default btn-small dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true"> 
                                                <i class="fas fa-print"></i>&nbsp;&nbsp;Print&nbsp;&nbsp;<span class="fa fa-angle-down"></span> 
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#" class="btn-print-all" data-action="1" data-format="html" data-request="report_sales_sell_detail">
                                                        <i class="fas fa-file-pdf"></i>&nbsp;&nbsp;PDF
                                                    </a>
                                                </li>
                                                <li class="hide">
                                                    <a href="#" class="btn-print-all" data-action="2" data-format="xls" data-request="report_sales_sell_detail">
                                                        <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Excel
                                                    </a>
                                                </li>
                                            </ul>                      
                                        </div>                   
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                                    <div class="col-lg-4 col-md-12 col-xs-12 col-sm-12 padding-remove-right">
                                        <label class="form-label" style="margin-bottom:0px;">Periode</label>
                                        <div id="filter_date" data-start="<?php echo $first_date;?>" data-end="<?php echo $end_date;?>" class="filter-daterangepicker" style="background: #ecf0f2;padding-top:6px;padding-bottom:6px;border:0px;">
                                            <i class="fas fa-calendar-alt"></i>&nbsp;
                                            <span></span> 
                                            &nbsp;&nbsp;&nbsp;<i class="fas fa-caret-down" style="position: absolute;right: 24px;top: 22px;"></i>
                                        </div>
                                    </div>                                         
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right hide">
                                        <label class="form-label">Periode Awal</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                                                       value="<?php echo $end_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right hide">
                                        <label class="form-label">Periode Akhir</label>
                                        <div class="col-md-12 col-sm-12 padding-remove-side">
                                            <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                                <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                                       value="<?php echo $end_date; ?>">
                                                <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Cabang</label>
                                            <select id="filter_branch" name="filter_branch" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>                                      
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Sorting</label>
                                            <select id="filter_order" name="filter_order" class="form-control">
                                                <option value="0">Tanggal</option>
                                                <option value="1">Nomor</option>
                                                <option value="4">Nama <?php echo $product_alias; ?></opton>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Urutan</label>
                                            <select id="filter_dir" name="filter_dir" class="form-control">
                                                <option value="asc">Ascending</option>
                                                <option value="desc">Descending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>                                  
                                    <!-- <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label"><?php #echo $product_alias; ?></label>
                                            <select id="filter_produk" name="filter_produk" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                            <label class="form-label">Sales By</label>
                                            <select id="filter_sales" name="filter_sales" class="form-control">
                                                <option value="0">-- Semua --</option>
                                            </select>
                                        </div>
                                    </div>  -->                                                                                                                     
                                </div>  
                                <div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
                                    <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10"
                                           style="width:100%;">
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
