<div class="row">
  <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
    <?php #include '_navigation.php';?>
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
              <h5><b>Form Properti</b></h5>                            
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                  <form id="form-master" name="form-master" method="" action="">    
                    <input id="tipe" type="hidden" value="<?php echo $identity;?>">
                    <div class="col-md-12">
                      <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side" style="margin-bottom: 20px;">
                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Gambar Utama</label>
                              <img id="img-preview1" class="img-responsive" 
                              data-is-new="0"
                              style="width:100%"
                              src="<?= site_url();?>/upload/noimage.png"/>
                              <div class="custom-file">
                                <input class="form-control" id="upload1" name="upload1" type="file" tabindex="1">
                              </div>                                                  
                            </div>
                          </div>                                                 
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Gambar 2</label>
                              <img id="img-preview2" class="img-responsive" 
                              data-is-new="0"
                              style="width:100%"
                              src="<?= site_url();?>/upload/noimage.png"/>
                              <div class="custom-file">
                                <input class="form-control" id="upload2" name="upload2" type="file" tabindex="1">
                              </div>                                                  
                            </div>
                          </div>                                                 
                        </div> 
                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Gambar 3</label>
                              <img id="img-preview3" class="img-responsive" 
                              data-is-new="0"
                              style="width:100%"
                              src="<?= site_url();?>/upload/noimage.png"/>
                              <div class="custom-file">
                                <input class="form-control" id="upload3" name="upload3" type="file" tabindex="1">
                              </div>                                                  
                            </div>
                          </div>                                                 
                        </div> 
                        <div class="col-md-3 col-sm-12 col-xs-12">
                          <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                            <div class="form-group">
                              <label>Gambar 4</label>
                              <img id="img-preview4" class="img-responsive" 
                              data-is-new="0"
                              style="width:100%"
                              src="<?= site_url();?>/upload/noimage.png"/>
                              <div class="custom-file">
                                <input class="form-control" id="upload4" name="upload4" type="file" tabindex="1">
                              </div>                                                  
                            </div>
                          </div>                                                 
                        </div>
                      </div>                    
                      <div class="col-md-6 col-sm-12 col-xs-12">    
                        <div class="col-lg-6 col-md-12 col-xs-12 padding-remove-left">
                          <div class="form-group">                        
                            <label>Jual / Sewa *</label>
                            <select id="ref" name="ref" class="form-control" disabled readonly>
                              <option value="1">Jual</option>
                              <option value="2">Sewa</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-xs-12 padding-remove-right">
                          <div class="form-group">                        
                            <label>Tipe Properti</label>
                            <select id="tipe_properti" name="tipe_properti" class="form-control">
                            </select>
                          </div>
                        </div> 
          
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                          <div class="form-group">
                            <label>Kota</label>
                            <select id="kota" name="kota" class="form-control" disabled readonly>
                            </select>
                          </div>
                        </div>                     
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                          <div class="form-group">                        
                            <label>Agen</label>
                            <select id="contact" name="contact" class="form-control">
                            </select>
                          </div>
                        </div>                                                            
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                          <div class="form-group">
                            <label>Judul *</label>
                            <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                          </div>
                        </div>     
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                          <div class="form-group">
                            <label>Status Properti</label>
                            <select id="status" name="status" class="form-control" disabled readonly>
                              <!-- <option value="">select</option> -->
                              <?php 
                              $status_values = array(
                                '1'=>'Tersedia',
                                '0'=>'Tidak Tersedia',
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
                      <div class="col-md-6 col-sm-12 col-xs-12">      
                        <!-- <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                          <div class="form-group">
                            <label>Label *</label>
                            <input id="manufacture" name="manufacture" type="text" value="" class="form-control" readonly='true'/>
                          </div>
                        </div> -->                      
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                          <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">            
                            <div class="form-group">
                              <label>Harga Jual *</label>
                              <input id="harga_jual" name="harga_jual" type="text" value="" class="form-control" readonly='true'/>
                            </div>                             
                          </div>                          
                        </div>   
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                          <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                            <div class="form-group">
                              <label>Luas Tanah (m2)</label>
                              <input id="luas_tanah" name="luas_tanah" type="text" value="" class="form-control" readonly='true'/>
                            </div>                          
                          </div>
                          <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-side">            
                            <div class="form-group">
                              <label>Luas Bangunan (m2)</label>
                              <input id="luas_bangunan" name="luas_bangunan" type="text" value="" class="form-control" readonly='true'/>
                            </div>                             
                          </div>                         
                        </div>   
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                          <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                            <div class="form-group">
                              <label>Kamar Tidur</label>
                              <input id="kamar_tidur" name="kamar_tidur" type="text" value="" class="form-control" readonly='true'/>
                            </div>                          
                          </div>
                          <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-side">            
                            <div class="form-group">
                              <label>Kamar Mandi</label>
                              <input id="kamar_mandi" name="kamar_mandi" type="text" value="" class="form-control" readonly='true'/>
                            </div>                             
                          </div>
                          <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">            
                            <div class="form-group">
                              <label>Garasi</label>
                              <input id="garasi" name="garasi" type="text" value="" class="form-control" readonly='true'/>
                            </div>                             
                          </div>                                                 
                        </div>                                                                  
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                          <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea id="keterangan" name="keterangan" type="text" class="form-control" readonly='true' rows="4"/></textarea>
                          </div>
                        </div>                          
                      </div>                                                                                                                       
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                      <div class="form-group">
                        <div class="pull-right">
                          <button id="btn-cancel" onClick="formCancel();" class="btn btn-warning btn-small" type="reset" style="display: none;">
                            <i class="fas fa-ban"></i> 
                            Batal
                          </button>                                                                  
                          <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                            <i class="fas fa-save"></i>                                 
                            Simpan
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
            <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
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
              <div class="col-lg-2 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-side">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                  <label class="form-label">Jual / Sewa</label>
                  <select id="filter_ref" name="filter_ref" class="form-control">
                    <option value="0">-- Semua --</option>
                    <option value="1">Jual</option>
                    <option value="2">Sewa</option>
                  </select>
                </div>
              </div> 
              <div class="col-lg-2 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                  <label class="form-label">Tipe Properti</label>
                  <select id="filter_type" name="filter_type" class="form-control">
                    <option value="0">-- Semua --</option>
                  </select>
                </div>
              </div> 
              <div class="col-lg-2 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                  <label class="form-label">Agent</label>
                  <select id="filter_contact" name="filter_contact" class="form-control">
                    <option value="0">-- Semua --</option>
                  </select>
                </div>
              </div>                    
              <div class="col-lg-4 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                  <label class="form-label">Kota</label>
                  <select id="filter_city" name="filter_city" class="form-control">
                    <option value="0">-- Semua --</option>
                  </select>
                </div>
              </div>   
              <div class="col-lg-2 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-right">
                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                  <label class="form-label">Status Properti</label>
                  <select id="filter_flag" name="filter_flag" class="form-control">
                    <option value="0">-- Semua --</option>
                    <option value="1">Tersedia</option>
                    <option value="2">Tidak Tersedia</option>
                  </select>
                </div>
              </div>                   
              <div class="clearfix"></div>              
              <div class="col-lg-10 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-side">
                <label class="form-label">Cari</label>
                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
              </div>                                 
              <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                <label class="form-label">Tampil</label>
                <select id="filter_length" name="filter_length" class="form-control">
                  <option value="10">10 Baris</option>
                  <option value="25">25 Baris</option>
                  <option value="50">50 Baris</option>
                  <option value="100">100 Baris</option>
                </select>
              </div>                   
            </div>                    
            <div class="col-md-12 col-xs-12 col-sm-12 table-responsive padding-remove-side">   
              <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10">
                <thead>
                  <tr>
                    <th>Properti</th>
                    <th>Dimensi</th>                  
                    <th>Agen</th>
                    <th>Harga</th>                     
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>      
      <div class="tab-pane" id="tab2">
      </div>
    </div>	
  </div>
</div>