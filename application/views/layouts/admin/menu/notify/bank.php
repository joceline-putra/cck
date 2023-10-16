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
          <div id="div-form-trans" style="display: none;" class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
            <div class="grid simple">
              <div class="grid-body">            
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                  <h5><b><?php echo $title;?></b></h5>  
                  <div class="row">        
                    <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side"> 
                      <form id="form-master" name="form-master" method="" action="">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                          <div class="form-group">
                            <input id="session" name="session" type="hidden" value="" placeholder="session" readonly>                                                        
                          </div>
                        </div>    
                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <div class="hide col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Gambar *</label>
                              <img id="img-preview1" class="img-responsive" 
                                data-is-new="0"
                                style="width:30%"
                                src=""/>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-side">
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                              <label class="form-label">Bank</label>
                              <select id="category" name="category" class="form-control">
                                <option value="0">Semua</option>
                              </select>
                            </div>
                          </div> 
                          <div class="col-md-8 col-xs-8 col-sm-12 padding-remove-side">
                            <div class="form-group">
                              <label>Nomor Rekening *</label>
                              <input id="nomor_rekening" name="nomor_rekening" type="text" value="" class="form-control" readonly='true'/>
                            </div>                          
                          </div>                             
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Nama Pemilik Rekening *</label>
                              <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                            </div>
                          </div>
                        </div>                          
                        <div class="col-md-4 col-sm-12 col-xs-12">                          

                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Account Bisnis</label>
                              <input id="account_bisnis" name="account_bisnis" type="text" value="" class="form-control" readonly='true'/>
                            </div>
                          </div> 
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Username *</label>
                              <input id="username" name="username" type="text" value="" class="form-control" readonly='true'/>
                            </div>
                          </div> 
                          <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                            <div class="form-group">
                              <label>Password *</label>
                              <input id="password" name="password" type="text" value="" class="form-control" readonly='true'/>
                            </div>                          
                          </div>                                                    
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">    
                          <!--<div class="col-ld-12 col-md-12 padding-remove-side">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                              <div class="form-group">
                                <label>Interval Pengecekan</label>
                                <select id="interval" name="interval" class="form-control">
                                  <option value="0">Pilih</option>
                                  <option value="1">1 Menit / Rp 7.000/hari</option>
                                  <option value="5">5 Menit / Rp 4.000/hari</option>
                                  <option value="15">15 Menit / Rp 2.000/hari</option>
                                </select>
                              </div>
                            </div>    
                          </div>
                          -->  
                          <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side"> 
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                              <div class="form-group">
                                <label>WhatsApp *</label>
                                <input id="telepon" name="telepon" type="text" value="" class="form-control" readonly='true'/>
                              </div>                          
                            </div>
                            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                              <div class="form-group">
                                <label>Email</label>
                                <input id="email" name="email" type="text" value="" class="form-control" readonly='true'/>
                              </div>                          
                            </div>   
                          </div>
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Status</label>
                              <select id="status" name="status" class="form-control" disabled readonly>
                                <!-- <option value="">select</option> -->
                                <?php 
                                $status_values = array(
                                  '1'=>'Aktif',
                                  '0'=>'Nonaktif',
                                );

                                foreach($status_values as $value => $display_text)
                                {
                                  echo '<option value="'.$value.'" '.$selected.'>'.$display_text.'</option>';
                                } 
                                ?>
                              </select>
                            </div>
                          </div>                                                                           
                        </div>
                      
                        <div class="clearfix"></div>
                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                          <div class="form-group">
                            <div class="pull-right">
                              <button id="btn-cancel" class="btn btn-warning btn-small" type="reset" style="display: none;">
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
                    <div class="col-md-12 col-xs-12 col-sm-12">
                      <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                        <h5><b>Data <?php echo $title;?></b></h5>
                      </div>
                      <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                        <div class="pull-right">                   
                          <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                            style="display: inline;">
                            <i class="fas fa-plus"></i>
                            Tambah <?php echo $title; ?> Baru
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">                                         
                      <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 form-group padding-remove-right prs-15">
                        <label class="form-label">Cari</label>
                        <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                      </div>  
                      <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right prs-15">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                          <label class="form-label">Bank</label>
                          <select id="filter_category" name="filter_category" class="form-control">
                            <option value="0">Semua</option>
                          </select>
                        </div>
                      </div>      
                      <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group padding-remove-right prs-15">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                          <label class="form-label">Status</label>
                          <select id="filter_flag" name="filter_flag" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                          </select>
                        </div>
                      </div>                                                 
                      <div class="col-lg-2 col-md-2 col-xs-6 col-sm-6 form-group">
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