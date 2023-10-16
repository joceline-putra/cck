<div class="modal fade" id="modal-trans-diskon" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 style="">Pasang Diskon</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <style>
                            .btn-diskon {
                                cursor: pointer;
                                padding-bottom: 20px;
                            }

                            .btn-diskon>div {
                                background-color: #f94545;
                                height: 100px;
                            }

                            .btn-diskon:hover>div {
                                background-color: #616161;
                                height: 100px;
                            }

                            .btn-diskon>div>h4 {
                                padding-top: 28px;
                                font-size: 42px;
                                font-weight: 800;
                                color: white;
                                text-align: center;
                            }
                        </style>
                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="0" style="">
                            <div class="col-md-12 col-xs-12 col-sm-12" style="">
                                <h4 style="">Reset</h4>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="5" style="">
                            <div class="col-md-12 col-xs-12 col-sm-12" style="">
                                <h4 style="">5%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="10">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>10%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="15">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>15%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="20">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>20%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="25">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>25%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="50">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>50%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="75">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>75%</h4>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-6 col-sm-6 btn-diskon" data-diskon="100">
                            <div class="col-md-12 col-xs-12 col-sm-12">
                                <h4>100%</h4>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="hide modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-trans-save" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 style="">Sukses Menyimpan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-print fa-4x"></i>
                        </p>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <p>
                            Berhasil menyimpan transaksi pembelian, silahkan melanjutkan
                        </p>
                        <p>
                            <strong>Transaksi sudah disimpan ke dalam database
                                <u>one day</u>.
                            </strong>
                        </p>
                        <h2>
                            <span class="badge">v52gs1</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <a href="#" id="" class="btn-print btn btn-success" data-id="">
                    <i class="fas fa-print white"></i> Print
                </a>
                <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Tutup</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-trans-note" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 style="">Tambahkan Catatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-pencil fa-4x"></i>
                        </p>
                    </div>
                    <div class="col-md-9 col-xs-12">
                        <p id="trans-item-label">
                        </p>
                        <input id="trans-item-note" name="trans-item-note" type="text" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button id="btn-save-item-note" class="btn btn-success" data-id="">
                    <i class="fas fa-print white"></i> Update
                </button>
                <button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Tutup</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-journal-item-edit" role="dialog" style="" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffffff;">
                <h4 id="modal-journal-item-edit-title" style="">Edit Item</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <p></p>
                        <p class="text-center">
                            <i class="fas fa-edit fa-8x"></i>
                        </p>
                    </div>
                    <div class="col-md-10 col-xs-12">
                        <!-- Disini -->
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <form id="form-edit-item" name="form-journal-item" method="" action="">
                                <div class="col-md-12">
                                  <!-- <input id="id_document_item" name="id_document_item" type="hidden" value="" placeholder="id" readonly> -->
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Akun Perkiraan (Rekening) *</label>
                                            <select id="e_account" name="e_account" class="form-control" disabled readonly>
                                                <option value="0">-- Cari Akun--</option>
                                            </select>
                                        </div> 
                                    </div>
                                    <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                        <div class="col-md-4 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <input id="e_note" name="e_note" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                        <?php
                                        $set_debit_class = 'hide';
                                        $set_credit_class = 'hide';
                                        if (($identity == 4) or ($identity == 9)) { //Cash Out & Cost Out
                                            $set_debit_class = '';
                                            $set_credit_class = 'hide';
                                        } else if ($identity == 3) { //Cash In
                                            $set_debit_class = 'hide';
                                            $set_credit_class = '';
                                        } else if ($identity == 8) { //General Journal
                                            $set_debit_class = '';
                                            $set_credit_class = '';
                                        } else if ($identity == 5) { //Bank Statement
                                            $set_debit_class = 'hide';
                                            $set_credit_class = 'hide';
                                        } else {
                                            
                                        }
                                        ?>                                       
                                        <div class="<?php echo $set_debit_class; ?> col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Debit</label>
                                                <input id="e_total_debit" name="e_total_debit" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>
                                        <div class="<?php echo $set_credit_class; ?> col-md-3 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <label>Kredit</label>
                                                <input id="e_total_credit" name="e_total_credit" type="text" value="" class="form-control" readonly='true'/>
                                            </div>
                                        </div>              
                                        <div class="col-md-2 col-xs-12 col-sm-12 padding-remove-left">
                                            <div class="form-group">
                                                <button id="btn-update-item" data-journal-item-id="0" onClick="" class="btn btn-primary btn-small" type="button" style="margin-top:22px;">
                                                    <i class="fas fa-check-square"></i>
                                                    Perbarui
                                                </button> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                        <!--  End Disini -->
                    </div>
                </div>                                         
            </div>
            <!--
            <div class="modal-footer flex-center">
              <button id="btn-save-item-discount" class="btn btn-success" data-id="">
                <i class="fas fa-print white"></i> Pasang
              </button>
              <button type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Tutup</button>        
            </div>
            -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-contact" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-master" name="form-master" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Kontak Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-user-plus fa-5x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-6 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-5 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode</label>
                                        <input id="kode_contact" name="kode_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input id="nama_contact" name="nama_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Perusahaan</label>
                                        <input id="perusahaan_contact" name="perusahaan_contact" type="text" value="" class="form-control"/>
                                    </div>
                                </div>                      
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input id="telepon_1_contact" name="telepon_1_contact" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                                           
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">

                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea id="alamat_contact" name="alamat_contact" type="text" value="" class="form-control"rows="8"/></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input id="email_1_contact" name="email_1_contact" type="text" value="" class="form-control"/>
                                    </div>                          
                                </div>                                              
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-contact" onClick="" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>                                 
                        Simpan Kontak Baru
                    </button>    
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Batal
                    </button>                   
                </div>
            </form>      
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-account" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="form-account" name="form-account" method="" action="">         
                <div class="modal-header" style="background-color: #ffffff;">
                    <h4 style="">Buat Akun Perkiraan Baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <p></p>
                            <p class="text-center">
                                <i class="fas fa-balance-scale fa-4x"></i>
                            </p>
                        </div>
                        <div class="col-md-9 col-xs-12"> 
                            <div class="col-md-12 col-sm-12 col-xs-12">              
                                <div class="col-lg-5 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Kode Akun Perkiraan</label>
                                        <input id="kode-akun" name="kode-akun" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama Akun Perkiraan</label>
                                        <input id="nama-akun" name="nama-akun" type="text" value="" class="form-control"/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">                        
                                        <label>Group Akun *</label>
                                        <select id="group-akun" name="group-akun" class="form-control">
                                            <option value="0">-- Pilih --</option>
                                            <option value="1">Asset</option>
                                            <option value="2">Liabilitas</option>
                                            <option value="3">Ekuitas</option>
                                            <option value="4">Pendapatan</option>
                                            <option value="5">Biaya</option>          
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">                        
                                        <label>Group Sub Akun *</label>
                                        <select id="group-sub-akun" name="group-sub-akun" class="form-control">
                                            <option value="0">-- Pilih --</option>
                                            <option value="14">Pendapatan</option>
                                            <option value="16">Biaya</option>          
                                        </select>
                                    </div>
                                </div>                                                                           
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-save-account" onClick="" class="btn btn-primary btn-small" type="button" style="">
                        <i class="fas fa-save"></i>                                 
                        Simpan Akun Perkiraan Baru
                    </button>    
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>                                 
                        Batal
                    </button>                   
                </div>
            </form>      
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>