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
    }    
</style>
<div class="row">
  <div class="col-md-12">
    <?php include '_navigation.php';?>
    <div class="tab-content">
      <div class="tab-pane active" id="tab1">
        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
                      <div class="hide col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                        <div class="pull-right">
                          <button id="btn-export" onClick="" class="btn btn-default btn-small" type="button"
                            style="display: inline;">
                            <i class="fas fa-file-excel"></i>
                            Ekspor Excel
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                      <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group padding-remove-right prs-15">
                        <label class="form-label">Periode Awal</label>
                        <div class="col-md-12 col-sm-12 padding-remove-side">
                          <div class="input-append success date col-md-12 col-lg-12 no-padding">
                            <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                              value="<?php echo $first_date;?>">
                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 col-md-2 col-xs-6 col-sm-12 form-group padding-remove-right prs-15">
                        <label class="form-label">Periode Akhir</label>
                        <div class="col-md-12 col-sm-12 padding-remove-side">
                          <div class="input-append success date col-md-12 col-lg-12 no-padding">
                            <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                              value="<?php echo $end_date;?>">
                            <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="clearfix"></div>      
                      <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12 form-group padding-remove-right prs-15">
                        <label class="form-label">Cari</label>
                        <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                      </div>  
                      <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12 form-group">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                          <label class="form-label">Rekening</label>
                          <select id="filter_bank" name="filter_bank" class="form-control">
                            <option value="0">Semua</option>
                          </select>
                        </div>
                      </div>                                             
                      <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group">
                        <label class="form-label">Tampil</label>
                        <select id="filter_length" name="filter_length" class="form-control">
                          <option value="10">10 Baris</option>
                          <option value="25">25 Baris</option>
                          <option value="50">50 Baris</option>
                          <option value="100">100 Baris</option>
                        </select>
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