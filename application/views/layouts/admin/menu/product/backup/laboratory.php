<div class="row">
  <div class="col-md-12">
    <?php include '_navigation.php';?>
    <div class="tab-content">
      <div class="tab-pane active" id="tab1">
        <div id="div-form-trans" style="display:none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
              <h5><b>Form <?php echo $title;?></b></h5>                            
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                  <form id="form-master" name="form-master" method="" action="">    
                    <input id="tipe" type="hidden" value="<?php echo $identity;?>">
                    <div class="col-md-12">
                      <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">              
                      <div class="col-md-6 col-xs-6 col-sm-12 padding-remove-side">
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-left">
                          <div class="form-group">
                            <label>Kode *</label>
                            <input id="kode" name="kode" type="text" value="" class="form-control" readonly='true'/>
                          </div>
                        </div>
                      </div>                    
                      <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                        <div class="form-group">
                          <label>Nama *</label>
                          <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                        </div>
                      </div>
                        
                                                                 
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                      <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                        <div class="form-group">                        
                          <label>Group *</label>
                          <select id="referensi" name="referensi" class="form-control" disabled readonly>
                            <option value="0">-- Pilih --</option>
                          </select>
                        </div>
                      </div>                       
                      <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">            
                          <div class="form-group">
                            <label>Harga Jual *</label>
                            <input id="harga_jual" name="harga_jual" type="text" value="" class="form-control" readonly='true'/>
                          </div>                             
                        </div>  
                      </div>                
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12">       
                      <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="form-group">
                          <label class="form-label">Akun Pembelian *</label>
                          <select id="account_buy" name="account_buy" class="form-control">
                            <!-- <option value="0">-- Pilih Akun --</option> -->
                            <?php echo '<option value="'.$account_purchase['account_id'].'">'.$account_purchase['account_code'].' - '.$account_purchase['account_name'].'</option>'; ?>                            
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                        <div class="form-group">
                          <label class="form-label">Akun Penjualan *</label>
                          <select id="account_sell" name="account_sell" class="form-control">
                            <!-- <option value="0">-- Pilih Akun --</option> -->
                            <?php echo '<option value="'.$account_sales['account_id'].'">'.$account_sales['account_code'].' - '.$account_sales['account_name'].'</option>'; ?>     
                          </select>
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
                  </form>                  
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
              <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                  <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                    <h5><b>Data <?php echo $title;?></b></h5>
                  </div>
                  <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                    <div class="pull-right">
                      <button id="btn-export" onClick="" class="btn btn-default btn-small" type="button"
                              style="display: none;">
                        <i class="fas fa-file-excel"></i>
                        Ekspor Excel
                      </button>                      
                      <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                              style="display: inline;">
                        <i class="fas fa-plus"></i>
                        Buat <?php echo $title; ?> Baru
                      </button>
                    </div>
                  </div>
                </div>                
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                  <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                      <label class="form-label">Group</label>
                      <select id="filter_ref" name="filter_ref" class="form-control">
                        <option value="0">-- Semua --</option>
                      </select>
                    </div>
                  </div>                    
                  <div class="col-lg-7 col-md-7 col-xs-12 col-sm-12 form-group padding-remove-right">
                    <label class="form-label">Cari</label>
                    <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                  </div>                                 
                  <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group">
                    <label class="form-label">Tampil</label>
                    <select id="filter_length" name="filter_length" class="form-control">
                      <option value="10">10 Baris</option>
                      <option value="25">25 Baris</option>
                      <option value="50">50 Baris</option>
                      <option value="100">100 Baris</option>
                    </select>
                  </div>                   
                </div>               
                <div class="col-md-12 col-xs-12 col-sm-12 table-responsive">
                  <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                  </table>
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