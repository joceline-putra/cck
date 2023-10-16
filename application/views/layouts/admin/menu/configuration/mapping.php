<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php include '_navigation.php'; ?>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
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
                                        <h5><b>Data <?php echo $title; ?></b></h5>
                                    </div>
                                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                                        <div class="pull-right">
                                            <button id="btn-export" onClick="" class="btn btn-default btn-small" type="button"
                                                    style="display: none;">
                                                <i class="fas fa-file-excel"></i>
                                                Ekspor Excel
                                            </button>                      
                                        </div>
                                    </div>
                                </div>     
                                <div class="col-md-12 col-xs-12 col-sm-12">
                                    <div class="panel-group" id="accordion" data-toggle="collapse">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#panel_penjualan">
                                                        Penjualan
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="panel_penjualan" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pendapatan Penjualan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_pendapatan_penjualan" name="jual_pendapatan_penjualan" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pembayaran di muka
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_pembayaran_di_muka" name="jual_pembayaran_di_muka" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Diskon Penjualan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_diskon_penjualan" name="jual_diskon_penjualan" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Penjualan Belum Ditagih
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_penjualan_belum_ditagih" name="jual_penjualan_belum_ditagih" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Retur Penjualan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_retur_penjualan" name="jual_retur_penjualan" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Piutang Belum Ditagih
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_piutang_belum_ditagih" name="jual_piutang_belum_ditagih" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pengiriman Penjualan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_pengiriman_penjualan" name="jual_pengiriman_penjualan" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Hutang Pajak Penjualan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_hutang_pajak_penjualan" name="jual_hutang_pajak_penjualan" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Voucher Penjualan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="jual_voucher_penjualan" name="jual_voucher_penjualan" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#panel_pembelian">
                                                        Pembelian
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="panel_pembelian" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pembelian (COGS)
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="beli_pembelian" name="beli_pembelian" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pembayaran di muka
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="beli_pembayaran_di_muka" name="beli_pembayaran_di_muka" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                
                                                    </div>
                                                    <div class="row">
                                                        <!--
                                                        <div class="col-md-6 col-sm-12">
                                                                <div class="col-sm-6">
                                                                        Retur Pembelian
                                                                </div>
                                                                <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                                <select id="beli_retur_pembelian" name="beli_retur_pembelian" class="form-control">
                                                                                        <option value="0">Retur Penjualan</option>
                                                                                </select>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                        -->
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Hutang Belum Ditagih
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="beli_hutang_belum_ditagih" name="beli_hutang_belum_ditagih" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pengiriman Pembelian
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="beli_pengiriman_pembelian" name="beli_pengiriman_pembelian" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                  
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Pajak Pembelian
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="beli_pajak_pembelian" name="beli_pajak_pembelian" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#panel_arap">
                                                        AR/AP
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="panel_arap" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Piutang Usaha
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="account_receivable" name="account_receivable" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Hutang Usaha
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="account_payable" name="account_payable" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#panel_persediaan">
                                                        Persediaan
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="panel_persediaan" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Persediaan
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="persediaan_persediaan" name="persediaan_persediaan" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Persediaan Rusak
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="persediaan_rusak" name="persediaan_rusak" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Persediaan Umum
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="persediaan_umum" name="persediaan_umum" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Persediaan Produksi
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="persediaan_produksi" name="persediaan_produksi" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                
                                                    </div>                
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#panel_lainnya">
                                                        Lainnya
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="panel_lainnya" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Ekuitas Saldo Awal
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="lain_ekuitas" name="lain_ekuitas" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Aset Tetap
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="lain_aset_tetap" name="lain_aset_tetap" class="form-control" disabled>
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#panel_payment">
                                                        Metode Pembayaran
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="panel_payment" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Kas / Tunai
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="payment_cash" name="payment_cash" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Bank Transfer
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="payment_transfer" name="payment_transfer" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                EDC Card
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="payment_edc" name="payment_edc" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                QRIS
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="payment_qris" name="payment_qris" class="form-control">
                                                                        <option value="0">Pilih</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12">
                                                            <div class="col-sm-4">
                                                                Gratis
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <select id="payment_free" name="payment_free" class="form-control">
                                                                        <option value="0">Pilih</option>
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
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
