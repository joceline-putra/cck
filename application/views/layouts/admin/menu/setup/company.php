<div class="tabbable tabs-left">

    <!-- Daftar tabs -->
    <ul class="nav nav-tabs" role="tablist" style="width: 161px;">
        <li class="active">
            <a href="#perusahaan" role="tab" data-toggle="tab" aria-expanded="true">Perusahaan</a>
        </li>
        <!-- BEGIN SUB MENU PENJUALAN -->
        <li data-sub-menu-parent="penjualan">
            <a href="#" role="tab" data-toggle="tab" aria-expanded="false">Penjualan</a>
        </li>
        <li data-sub-menu-child-of="penjualan" style="display: none">
            <a href="#format_pengaturan" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Format Pengaturan
            </a>
        </li>
        <li data-sub-menu-child-of="penjualan" style="display: none">
            <a href="#pengingat_faktur" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Pengingat Faktur
            </a>
        </li>
        <!-- END SUB MENU PENJUALAN -->
        <li>
            <a href="#pembelian" role="tab" data-toggle="tab" aria-expanded="false">Pembelian</a>
        </li>
        <li>
            <a href="#produk_jasa" role="tab" data-toggle="tab" aria-expanded="false">Produk & Jasa</a>
        </li>
        <!-- BEGIN SUB MENU TEMPLATE -->
        <li data-sub-menu-parent="template">
            <a href="#" role="tab" data-toggle="tab" aria-expanded="false">Template</a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none; cursor: default!important;">
            <a class="m-l-10 bold" onclick="return false" style="cursor: default!important">
                TEMPLATE EMAIL
            </a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none">
            <a href="#faktur_penjualan" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Faktur Penjualan
            </a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none">
            <a href="#penawaran_penjualan" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Penawaran Penjualan
            </a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none">
            <a href="#pemesanan_penjualan" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Pemesanan Penjualan
            </a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none">
            <a href="#pemesanan_pembelian" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Pemesanan Pembelian
            </a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none; cursor: default!important;">
            <a class="m-l-10 bold" onclick="return false" style="cursor: default!important">
                PENGATURAN PDF
            </a>
        </li>
        <li data-sub-menu-child-of="template" style="display: none">
            <a href="#pemesanan_pembelian" class="m-l-10" role="tab" data-toggle="tab" aria-expanded="false">
                Pengaturan PDF
            </a>
        </li>
        <!-- END SUB MENU TEMPLATE -->
        <li>
            <a href="#pengaturan_pengguna" role="tab" data-toggle="tab" aria-expanded="false">Pengaturan Pengguna</a>
        </li>
        <li>
            <a href="#tagihan" role="tab" data-toggle="tab" aria-expanded="false">Tagihan</a>
        </li>
    </ul>
    <div class="tab-content">
        <!-- PERUSAHAAN START -->
        <div class="tab-pane active" id="perusahaan">
            <div class="grid-body">
                <h5><b>Form Perusahaan</b></h5>
                <form name="form_perusahaan" method="" action="">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <div class="col-md-12">
                                <h4>Pengaturan Perusahaan</h4>
                            </div>
                            <div class="col-md-12">
                                <input name="id_document" type="hidden" value="" placeholder="id" readonly>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Logo</label>
                                        <input name="logo" type="file" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="tampilkan_logo" type="checkbox" readonly='true'>
                                            <label>Tampilkan Logo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama Perusahaan</label>
                                        <input name="nama_perusahaan" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea name="alamat" type="text" value="" class="form-control" readonly='true' rows="7"/>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Alamat Pengiriman</label>
                                        <textarea name="alamat_pengiriman" type="text" value="" class="form-control" readonly='true' rows="7"/>
                                        </textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Telepon</label>
                                        <input name="telepon" type="number" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Fax</label>
                                        <input name="fax" type="number" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>NPWP</label>
                                        <input name="npwp" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Website</label>
                                        <input name="website" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input name="email" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <div class="col-md-12 padding-remove-side">
                                    <h4>Detail Akun Bank</h4>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nama Bank</label>
                                        <input name="nama_bank" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Cabang Bank</label>
                                        <input name="cabang_bank" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Alamat Bank</label>
                                        <input name="alamat_bank" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Nomor Rekening</label>
                                        <input name="nomor_rekening" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Atas Nama</label>
                                        <input name="atas_nama" type="text" value="" class="form-control" readonly='true'/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4>Pengaturan Fitur Tambahan</h4>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="fitur_approval" type="checkbox" readonly='true'>
                                            <label>Aktifkan Fitur Approval (BETA)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="tampilkan-panduan-awal" type="checkbox" readonly='true'>
                                            <label>Tampilkan Panduan Awal</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-cons">
                                <i class="fa fa-check"></i>&nbsp;Save
                            </button>
                            <button type="button" class="btn btn-default btn-cons">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- PERUSAHAAN END -->

        <!-- FORMAT PENGATURAN START -->
        <div class="tab-pane" id="format_pengaturan">
            <div class="grid-body">
                <h5><b><i class="fas fa-file-contract"></i>Form Penjualan</b></h5>
                <form name="form_penjualan" method="" action="">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <div class="col-md-12">
                                <h4>Penjualan</h4>
                            </div>
                            <div class="col-md-12">
                                <input name="id_document" type="hidden" value="" placeholder="id" readonly>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Syarat Pembayaran Penjualan Utama</label>
                                        <select>
                                            <option value="net 30">Net 30</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="pengiriman" type="checkbox" readonly='true'>
                                            <label>Pengiriman</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="diskon" type="checkbox" readonly='true'>
                                            <label>Diskon</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="diskon_per_baris" type="checkbox" checked readonly='true'>
                                            <label>Diskon per Baris</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="uang_muka" type="checkbox" readonly='true'>
                                            <label>Uang Muka</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="tampilkan_persen_keuntungan" type="checkbox" readonly='true'>
                                            <label>Tampilkan % Keuntungan di Faktur Penjualan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="tolak_penjualan_jika_kekurangan_kuantitas" type="checkbox" readonly='true'>
                                            <label>Tolak Penjualan Jika Kekurangan Kuantitas</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="harga_penjualan_mengikuti_price_rule" type="checkbox" readonly='true'>
                                            <label>Harga Penjualan Mengikuti Price Rule</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Pesan Penjualan Standar</label>
                                        <textarea name="pesan_penjualan_standar" type="text" value="" class="form-control" readonly='true' rows="7"/>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Pesan Surat Jalan Standar</label>
                                        <textarea name="pesan_surat_jalan_standar" type="text" value="" class="form-control" readonly='true' rows="7"/>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-cons">
                                    <i class="fa fa-check"></i>&nbsp;Save
                                </button>
                                <button type="button" class="btn btn-default btn-cons">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- FORMAT PENGATURAN END -->

        <!-- PENGINGAT FAKTUR START -->
        <div class="tab-pane" id="pengingat_faktur">
            <h1>Pengingat Faktur</h1>
        </div>
        <!-- PENGINGAT FAKTUR END -->

        <div class="tab-pane" id="pembelian">
            <div class="grid-body">
                <h5><b><i class="fas fa-file-contract"></i>Form Pembelian</b></h5>
                <form name="form_pembelian" method="" action="">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <div class="col-md-12">
                                <h4>Pembelian</h4>
                            </div>
                            <div class="col-md-12">
                                <input name="id_document" type="hidden" value="" placeholder="id" readonly>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Syarat Pembayaran Pembelian Utama</label>
                                        <select>
                                            <option value="net 30">Net 30</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="pengiriman" type="checkbox" readonly='true'>
                                            <label>Pengiriman</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="diskon" type="checkbox" readonly='true'>
                                            <label>Diskon</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="diskon_per_baris" type="checkbox" checked readonly='true'>
                                            <label>Diskon per Baris</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox check-default">
                                            <input name="uang_muka" type="checkbox" readonly='true'>
                                            <label>Uang Muka</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                    <div class="form-group">
                                        <label>Pesan Pembelian Standar</label>
                                        <textarea name="pesan_pembelian_standar" type="text" value="" class="form-control" readonly='true' rows="7"/>
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-cons">
                                <i class="fa fa-check"></i>&nbsp;Save
                            </button>
                            <button type="button" class="btn btn-default btn-cons">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane" id="produk_jasa">
            <div class="grid-body">
                <h5><b><i class="fas fa-file-contract"></i>Produk dan Jasa</b></h5>
                <form name="form_produk_jasa" method="" action="">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                            <div class="col-md-12">
                                <h4>Produk</h4>
                            </div>
                            <div class="col-md-12">
                                <input name="id_document" type="hidden" value="" placeholder="id" readonly>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    Tampilkan Stok Produk
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    <div class="fluid-row">
                                        <div class="checkbox">
                                            <input name="pengiriman" type="checkbox" readonly='true'>
                                            <label></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    <p>Kategori Produk</p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    <a href="#">Pengaturan Kategori</a>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    <p>Satuan Produk</p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    <a href="#">Pengaturan Satuan</a>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    <p>Metode Perhitungan Biaya Inventori</p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-side">
                                    FIFO
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-default btn-cons">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane" id="faktur_penjualan">
            <div class="grid-body">
                <h5><b><i class="fas fa-file-contract"></i>Template Email Penjualan</b></h5>
                <form name="form_pembelian" method="" action="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Subject</label>
                                <input name="subject" type="text" value="" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Pesan</label>
                                <textarea name="subject" type="text" value="" class="form-control" rows="10" style="height: auto!important"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Variable</label>
                                <textarea name="variable" type="text" class="form-control" style="height: auto!important" disabled>[NamaCustomer] [PerusahaanCustomer] [NomorTransaksi] [TanggalTransaksi] [TanggalJatuhTempo] [NamaPerusahaan] [EmailPerusahaan] [SisaTagihan] [TanggalHariIni]</textarea>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-cons">
                                Ubah Template
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane" id="pemetaan_akun">
            <div class="grid-body">
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
                                        <div class="col-sm-6">
                                            Pendapatan Penjualan
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_pendapatan_penjualan" name="jual_pendapatan_penjualan" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Pembayaran di muka
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_pembayaran_di_muka" name="jual_pembayaran_di_muka" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Diskon Penjualan
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_diskon_penjualan" name="jual_diskon_penjualan" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Penjualan Belum Ditagih
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_penjualan_belum_ditagih" name="jual_penjualan_belum_ditagih" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Retur Penjualan
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_retur_penjualan" name="jual_retur_penjualan" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Piutang Belum Ditagih
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_piutang_belum_ditagih" name="jual_piutang_belum_ditagih" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Pengiriman Penjualan
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_pengiriman_penjualan" name="jual_pengiriman_penjualan" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Hutang Pajak Penjualan
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="jual_hutang_pajak_penjualan" name="jual_hutang_pajak_penjualan" class="form-control">
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
                                        <div class="col-sm-6">
                                            Pembelian (COGS)
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="beli_pembelian" name="beli_pembelian" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Pembayaran di muka
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="beli_pembayaran_di_muka" name="beli_pembayaran_di_muka" class="form-control">
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
                                        <div class="col-sm-6">
                                            Hutang Belum Ditagih
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="beli_hutang_belum_ditagih" name="beli_hutang_belum_ditagih" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Pengiriman Pembelian
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="beli_pengiriman_pembelian" name="beli_pengiriman_pembelian" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                  
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Pajak Pembelian
                                        </div>
                                        <div class="col-sm-6">
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
                                        <div class="col-sm-6">
                                            Piutang Usaha
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="account_receivable" name="account_receivable" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Hutang Usaha
                                        </div>
                                        <div class="col-sm-6">
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
                                        <div class="col-sm-6">
                                            Persediaan
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="persediaan_persediaan" name="persediaan_persediaan" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Persediaan Rusak
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="persediaan_rusak" name="persediaan_rusak" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Persediaan Umum
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="persediaan_umum" name="persediaan_umum" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Persediaan Produksi
                                        </div>
                                        <div class="col-sm-6">
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
                                        <div class="col-sm-6">
                                            Ekuitas Saldo Awal
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="lain_ekuitas" name="lain_ekuitas" class="form-control">
                                                    <option value="0">Pilih</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="col-sm-6">
                                            Aset Tetap
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <select id="lain_aset_tetap" name="lain_aset_tetap" class="form-control">
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
        <div class="tab-pane" id="pengaturan_pengguna">
            <h1>Pengaturan Pengguna</h1>
            <table class="table table-striped table-bordered" data-limit-start="0" data-limit-end="10"> 
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Roles</th>                    
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Calvine Winarto</td>
                        <td>joe.calvine@gmail.com</td>
                        <td>Owner</td>
                        <td>Terdaftar</td>
                        <td>Members</td>
                        <td>Lihat</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane" id="tagihan">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <h4>PAKET BERLANGGANAN</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6"><p class="text-left">Paket</p></div>
                        <div class="col-sm-6"><p class="text-right">Trial Enterprise+</p></div>
                        <div class="col-sm-6"><p class="text-left">Pangguna</p></div>
                        <div class="col-sm-6"><p class="text-right">1 dari -</p></div>
                        <div class="col-sm-6"><p class="text-left">Partners</p></div>
                        <div class="col-sm-6"><p class="text-right">0</p></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <h4>INFORMASI TAGIHAN</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6"><p class="text-left">Paket</p></div>
                        <div class="col-sm-6"><p class="text-right">Rp. 0,00</p></div>
                        <div class="col-sm-6"><p class="text-left">Pengguna Tambahan</p></div>
                        <div class="col-sm-6"><p class="text-right">Rp. 0,00</p></div>
                    </div>
                    <hr/>
                    <h4>Total Tagihan</h4>
                    <div class="row">
                        <div class="col-sm-6"><p class="text-left">Jangka Pembayaran</p></div>
                        <div class="col-sm-6"><p class="text-right">-</p></div>
                        <div class="col-sm-6"><p class="text-left">Tanggal Pembayaran Berikutnya</p></div>
                        <div class="col-sm-6"><p class="text-right">-</p></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <h4>DETIL PEMBAYARAN</h4>
                    <hr/>
                </div>
                <div class="col-sm-12">
                    <h4>RIWAYAT PEMBAYARAN</h4>
                    <table class="table table-striped table-bordered" data-limit-start="0" data-limit-end="10"> 
                        <thead>
                            <tr>
                                <th>Tanggal Bayar</th>
                                <th>Periode Tagihan</th>
                                <th>Total Bayar</th>
                                <th>Unduh Tagihan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1 Januari 2021</td>
                                <td>1 Januari 2021</td>
                                <td>Rp. 1,000,000.00</td>
                                <td>Unduh</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr/>
                </div>
            </div>
        </div>
    </div>
</div>