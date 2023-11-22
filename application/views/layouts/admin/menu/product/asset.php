<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div id="div-form-trans" style="display:none;" class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                    <div class="grid simple">
                        <div class="grid-body">
                            <h5><b>Form <?php echo $title; ?></b></h5>                            
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <form id="form-master" name="form-master" method="" action="">    
                                        <input id="tipe" type="hidden" value="<?php echo $identity; ?>">
                                        <div class="col-md-12">
                                            <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Gambar *</label>
                                                    <img id="img-preview1" class="img-responsive" 
                                                         data-is-new="0"
                                                         style="width:100%"
                                                         src=""/>
                                                    <div class="custom-file">
                                                        <input class="form-control" id="upload1" name="upload1" type="file" tabindex="1">
                                                    </div>                                                  
                                                </div>
                                            </div>
                                            <!--
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                  <div class="form-group">
                                                    <label>Jenis Produk *</label>
                                                    <select id="manufacture" name="manufacture" class="form-control" disabled readonly>
                                                          <option value="">-- Pilih --</option>
                                                          <option value="Food">Food</option>
                                                          <option value="Drink">Drink</option>
                                                          <option value="Snack">Snack</option>
                                                          <option value="Topping">Topping</option>
                                                          <option value="Paket">Paket</option>
                                                          <option value="Bahan Baku">Bahan Baku</option>
                                                    </select>
                                                  </div>
                                            </div>
                                            -->                                                                              
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Jenis <?php echo $title; ?> *</label>
                                                    <select id="product_ref_id" name="product_ref_id" class="form-control" disabled readonly>
                                                        <option value="0">Pilih</option>
                                                    </select>
                                                </div>
                                            </div>									
                                            <div class="col-md-12 col-xs-6 col-sm-12 padding-remove-side">
                                                <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-left">
                                                    <div class="form-group">
                                                        <label class="form-label">Kode <?php echo $title; ?> / Barcode</label>
                                                        <input id="kode" name="kode" type="text" value="" class="form-control" readonly="true"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Nama <?php echo $title; ?> *</label>
                                                    <input id="nama" name="nama" type="text" value="" class="form-control" readonly='true'/>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label class="form-label">Satuan *</label>
                                                    <select id="satuan" name="satuan" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih --</option>
                                                    </select>
                                                </div>
                                            </div>		
                                            <!-- 									  											
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                            <label class="form-label">Kategori *</label>
                                                            <select id="categories" name="categories" class="form-control" disabled readonly>
                                                                    <option value="0">-- Pilih --</option>
                                                            </select>
                                                    </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <div class="form-group">
                                                    <label>Label *</label>
                                                    <input id="manufacture" name="manufacture" type="text" value="" class="form-control" readonly='true'/>
                                                    </div>
                                            </div> 
                                            -->
                                            <div class="hide col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="margin-top:0px;">
                                                    <div class="form-group" style="margin-bottom: 0px;">                     
                                                        <label class="form-label" style="cursor:pointer;"><input id="checkbox_buy" type="checkbox" value="">&nbsp;Saya beli produk ini</label>
                                                        <p style="
                                                           margin-bottom: 0px;
                                                           color: #9e9e9e;
                                                           ">Jika diisi, harga akan muncul saat Pembelian kepada Supplier</p>
                                                    </div>
                                                    <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Pembelian *</label>
                                                            <select id="account_buy" name="account_buy" class="form-control" disabled>
                                                                <?php echo '<option value="' . $account_purchase['account_id'] . '">' . $account_purchase['account_code'] . ' - ' . $account_purchase['account_name'] . '</option>'; ?>                            
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">
                                                        <div class="form-group">
                                                            <label class="form-label">Harga Beli</label>
                                                            <input id="harga_beli" name="harga_beli" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>	
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="margin-top:12px;">
                                                    <div class="form-group" style="margin-bottom: 0px;">                     
                                                        <label class="form-label" style="cursor:pointer;"><input id="checkbox_sell" type="checkbox" value="">&nbsp;Saya jual produk ini</label>
                                                        <p style="
                                                           margin-bottom: 0px;
                                                           color: #9e9e9e;
                                                           ">Jika diisi, harga akan muncul saat Penjualan kepada Customer</p>
                                                    </div>
                                                    <div class="col-md-8 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Penjualan *</label>
                                                            <select id="account_sell" name="account_sell" class="form-control" disabled>
                                                                <?php echo '<option value="' . $account_sales['account_id'] . '">' . $account_sales['account_code'] . ' - ' . $account_sales['account_name'] . '</option>'; ?>     
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-right">
                                                        <div class="form-group">
                                                            <label class="form-label">Harga Jual</label>
                                                            <input id="harga_jual" name="harga_jual" type="text" value="" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="margin-top:12px;">
                                                    <div class="form-group" style="margin-bottom: 0px;">                     
                                                        <label class="form-label" style="cursor:pointer;"><input id="checkbox_inventory" type="checkbox" value="">&nbsp;Monitor Persediaan Stok</label>
                                                        <p style="
                                                           margin-bottom: 0px;
                                                           color: #9e9e9e;
                                                           ">Jika produk berupa Jasa, tidak perlu mengaktifkan ini</p>											  		
                                                    </div>
                                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Akun Persediaan</label>
                                                            <select id="account_inventory" name="account_inventory" class="form-control" disabled>
                                                                <?php echo '<option value="' . $account_inventory['account_id'] . '">' . $account_inventory['account_code'] . ' - ' . $account_inventory['account_name'] . '</option>'; ?>     
                                                            </select>
                                                        </div>
                                                    </div>  		
                                                    <div class="col-lg-8 col-md-8 col-xs-12 padding-remove-side">
                                                        <div class="form-group">
                                                            <label class="form-label">Proteksi Stok</label>
                                                            <select id="with_stock" name="with_stock" class="form-control" disabled>
                                                                <?php
                                                                $status_values = array(
                                                                    '0' => 'Tidak',
                                                                    '1' => 'Ya',
                                                                );
                                                                foreach ($status_values as $value => $display_text) {
                                                                    echo '<option value="' . $value . '">' . $display_text . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12 padding-remove-right">
                                                        <div class="form-group">
                                                            <label class="form-label">Stok Minimal</label>
                                                            <input id="stok_minimal" name="stok_minimal" type="text" value="0" class="form-control" readonly='true'/>
                                                        </div>
                                                    </div>	
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5 col-sm-12 col-xs-12">
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
                                                            <input id="branch_<?php echo $v['branch_id']; ?>" type="radio" name="product_branch_id" value="<?php echo $v['branch_id']; ?>" <?php echo $c; ?>><label for="branch_<?php echo $v['branch_id']; ?>"><?php echo $v['branch_name']; ?></label>
                                                        <?php 
                                                        } 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side" style="margin-bottom:14px;">
                                                <div class="form-group">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea id="keterangan" name="keterangan" type="text" class="form-control" readonly='true' rows="4"/></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-left">
                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-remove-side">
                                                    <div class="form-group" style="">
                                                        <label class="form-label">Status</label>
                                                        <select id="status" name="status" class="form-control" disabled>
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
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                    <p><i class="fas fa-info"></i> <?php echo $title; ?> nonaktif tidak akan dimunculkan di semua transaksi</p>
                                                </div>                                                                                
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                            <div class="form-group">
                                                <div class="hide pull-left">                            
                                                    <button id="btn-recipe" class="btn btn-default btn-small" type="button">
                                                        <i class="fas fa-list-alt"></i>
                                                        Resep Untuk Barang Ini
                                                    </button>
                                                </div>                        
                                                <div class="pull-right">
                                                    <button id="btn-cancel" class="btn btn-warning btn-small" type="reset" style="display: none;">
                                                        <i class="fas fa-ban"></i> 
                                                        Batal
                                                    </button>                                                                  
                                                    <button id="btn-save" onClick="" class="btn btn-primary btn-small" type="button" style="display:none;">
                                                        <i class="fas fa-save"></i>                                 
                                                        Simpan
                                                    </button>                                        
                                                    <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                                                        <i class="fas fa-edit"></i> 
                                                        Perbarui
                                                    </button> 
                                                    <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                        <i class="fas fa-trash"></i> 
                                                        Hapus
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
                        <div class="grid-body">
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                        <h5><b>Data <?php echo $title; ?></b></h5>
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
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <label class="form-label">Jenis</label>
                                        <select id="filter_ref" name="filter_ref" class="form-control">
                                            <option value="0">Semua</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-right">
                                        <label class="form-label">Status</label>
                                        <select id="filter_flag" name="filter_flag" class="form-control">
                                            <option value="100">Semua</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>                                    
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12 form-group padding-remove-right">
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
                                    <table id="table-data" class="table table-bordered" data-limit-start="0" data-limit-end="10" style="width:100%;">
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