<style>
    .h-title{
        font-size: 14px;
        font-weight: 700;
        cursor:pointer;
        color:#4552af!important;
        margin-bottom: 0px!important;
    }
    .p-contents{
        font-size: 12px;
    }
    .grid-body{
        padding-bottom:10px!important;
        margin-bottom: 18px!important;
    }
    .col-report{
        padding-bottom:20px;
        /* border-bottom: 1px solid #e5e9ec; */
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#tab1" role="tab" data-toggle="tab" aria-expanded="true">
                    <i class="fas fa-business-time"></i> Sekilas Bisnis </a>
            </li>
            <li>
                <a href="#tab2" role="tab" data-toggle="tab" aria-expanded="false" style="cursor:pointer;">
                    <i class="fas fa-school"></i> Penjualan </a>
            </li>
            <li>
                <a href="#tab3" role="tab" data-toggle="tab" aria-expanded="false" style="cursor:pointer;">
                    <i class="fas fa-chart-bar"></i> Pembelian </a>
            </li>
            <li>
                <a href="#tab4" role="tab" data-toggle="tab" aria-expanded="false" style="cursor:pointer;">
                    <i class="fas fa-chart-bar"></i> Produk </a>
            </li>
            <li>
                <a href="#tab5" role="tab" data-toggle="tab" aria-expanded="false" style="cursor:pointer;">
                    <i class="fas fa-chart-bar"></i> Aset </a>
            </li>
            <li>
                <a href="#tab6" role="tab" data-toggle="tab" aria-expanded="false" style="cursor:pointer;">
                    <i class="fas fa-chart-bar"></i> Bank </a>
            </li>
            <li>
                <a href="#tab7" role="tab" data-toggle="tab" aria-expanded="false" style="cursor:pointer;">
                    <i class="fas fa-chart-bar"></i> Pajak </a>
            </li>
        </ul>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane active" id="tab1">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-left">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-business">Ringkasan Bisnis</h3>
                            <p class="p-contents">
                                Laporan Ringkasan Bisnis menampilkan ringkasan dari laporan
                                keuangan standar berserta wawasannya.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-finance-business" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>	
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-cash_flow">Arus Kas</h3>
                            <p class="p-contents">Laporan ini mengukur kas yang telah dihasilkan atau digunakan
                                oleh suatu perushaan dan menunjukan detail pergerakan dalan suatu priode.</p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-finance-cash_flow" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>							
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-journal">Jurnal</h3>
                            <p class="p-contents">
                                Daftar jurnal per transaksi yang terjadi priode waktu. Hal ini
                                berguna untuk melacak dimana transaksi anda masuk ke masing masing rekening.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-finance-journal" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-ledger">Buku Besar</h3>
                            <p class="p-contents">
                                Laporan ini menampilkan semua tansaksi yang telah di lakukan
                                untuk suatu periode. Laporan ini bermanfaat jika anda memerlukan daftar kronologis untuk semua
                                transaksi yang telah dilakukan oleh perushan anda. 
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-5 col-xs-5" style="padding-left:0px!important;">
                                <a data-value="report-finance-ledger" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-cash_in">Uang Masuk / Setoran</h3>
                            <p class="p-contents">
                                Laporan ini menampilkan uang yang di terima dari segala hal transaksi yang berkaitan dengan kas/bank. 
                                Bermanfaat untuk melacak pelunasan piutang, modal, uang muka pemasukan keuangan bisnis.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-5 col-xs-5" style="padding-left:0px!important;">
                                <a data-value="report-finance-cash_in" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>												
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-cash_out">Uang Keluar / Biaya</h3>
                            <p class="p-contents">
                                Laporan ini menampilkan uang yang di terima dari segala hal transaksi yang berkaitan dengan kas/bank. 
                                Bermanfaat untuk melacak pembayaran hutang, biaya operasional, biaya lain, uang muka pemasukan keuangan bisnis.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-5 col-xs-5" style="padding-left:0px!important;">
                                <a data-value="report-finance-cash_out" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>						
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-trial_balance">Trial Balance</h3>
                            <p class="p-contents">
                                Menampilkan saldo dari setiap akun, termasuk saldo awal,
                                pergerakan, dan saldo akhir dari priodebyang di tentukan.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-finance-trial_balance" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>	
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-profit_loss">Laporan Laba-Rugi</h3>
                            <p class="p-contents">
                                Menampilkan setiap tipe transaksi dan jumlah untuk pendapatan dan
                                pengeluaran anda
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-finance-profit_loss" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-finance-balance">Neraca</h3>
                            <p class="p-contents">
                                Menampilkan apa yang anda miliki (aset), apa yang anda hutang (liabilitas), 
                                dan apa yang anda sudah investasikan pada perushaan anda (ekuitas) 
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-finance-balance" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab2">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 padding-remove-left">					
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-sales-sell-recap">Daftar Penjualan</h3>
                            <p class="p-contents">
                                Menunjukkan daftar kronologis dari semua faktur, pemesanan, penawaran,
                                dan pembayaran Anda untuk rentang tanggal yang dipilih.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-sales-sell-recap" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-sales-sell-recap">Penjualan per Pelanggan</h3>
                            <p class="p-contents">
                                Menampilkan setiap transaksi penjualan untuk setiap pelanggan, termasuk tanggal, tipe,
                                jumlah dan total.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-sales-sell-recap" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-sales-sell-account_receivable">Laporan Piutang Pelanggan</h3>
                            <p class="p-contents">
                                Menampilkan tagihan yang belum dibayar untuk setiap pelanggan,
                                termasuk nomor & tanggal faktur, tanggal jatuh tempo, jumlah nilai,
                                dan sisa tagihan yang terhutang pada Anda.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-5 col-xs-5" style="padding-left:0px!important;">
                                <a data-value="report-sales-sell-account_receivable" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 padding-remove-right">
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-sales-sell-detail">Penjualan per Produk</h3>
                            <p class="p-contents">
                                Menampilkan daftar kuantitas penjualan per produk, termasuk jumlah retur, net penjualan, dan
                                harga penjualan rata-rata.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-sales-sell-detail" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>						
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title">Usia Piutang</h3>
                            <p class="p-contents">
                                Laporan ini memberikan ringkasan piutang Anda, yang menunjukkan setiap pelanggan karena Anda
                                secara bulanan, serta jumlah total dari waktu ke waktu. Hal ini praktis untuk membantu melacak
                                piutang Anda.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-usia-piutang" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-sales-order-recap">Penyelasaian Pemesanan Penjualan</h3>
                            <p class="p-contents">
                                Menampilkan ringkasan dari proses bisnis Anda dari penawaran, pemesanan, pengiriman, penagihan,
                                dan pembayaran per proses, agar Anda dapat melihat penawaran/pemesanan mana yang berlanjut ke
                                penagihan.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-sales-order-recap" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab3">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 padding-remove-left">							
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-purchase-buy-recap">Daftar Pembelian</h3>
                            <p class="p-contents">
                                Menunjukkan daftar kronologis dari semua pembelian, pemesanan, penawaran, dan pembayaran Anda
                                untuk rentang tanggal yang dipilih.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-purchase-buy-recap" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-purchase-buy-recap">Pembelian per Supplier</h3>
                            <p class="p-contents">
                                Menampilkan setiap pembelian dan jumlah untuk setiap Supplier.
                            </p>
                            <br/>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-purchase-buy-recap" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-purchase-buy-account-payable">Laporan Hutang Supplier</h3>
                            <p class="p-contents">
                                Menampilkan jumlah nilai yang Anda hutang pada setiap Supplier.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-5 col-xs-5" style="padding-left:0px!important;">
                                <a data-value="report-purchase-buy-account-payable" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 padding-remove-right">		
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-purchase-buy-detail">Pembelian per Produk</h3>
                            <p class="p-contents">
                                Menampilkan daftar kuantitas pembelian per produk, termasuk jumlah retur, net pembelian, dan harga
                                pembelian rata-rata.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-purchase-buy-detail" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>						
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title">Usia Hutang</h3>
                            <p class="p-contents">
                                Laporan ini memberikan ringkasan hutang Anda, menunjukkan setiap vendor Anda secara bulanan,
                                serta jumlah total dari waktu ke waktu. Hal ini praktis untuk membantu melacak hutang Anda.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-usia-hutang" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-purchase-order-recap">Penyelesaian Pemesanan Pembelian</h3>
                            <p class="p-contents">
                                Menampilkan rigkasan dari proses bisnis Anda, dari penawaran, pemesanan, pengiriman, penagihan,
                                dan pembayaran per proses, agar Anda dapat melihat penawaran/pemesanan mana yang berlanjut ke
                                penagihan.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-purchase-order-recap" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>						
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab4">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 padding-remove-left">					
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-inventory-product-stock_moving">Pergerakan Barang Gudang</h3>
                            <p class="p-contents">
                                Laporan ini menampilkan pergerakan stok per gudang dan merincikan transaksi yang menghasilkan
                                pergerakan stok per geudang untuk semua produk atau stok per produk untuk semua gudang dalam
                                suatu periode.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-inventory-product-stock_moving" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title" data-value="report-inventory-product-stock_warehouse">Kuantitas Stok Gudang</h3>
                            <p class="p-contents">
                                Laporan ini menampilkan kuantitas stok di setiap gudang untuk semua produk.
                            </p>
                            <br/>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-inventory-product-stock_warehouse" class="btn-report btn btn-primary btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>										
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title">Ringkasan Persediaan Barang</h3>
                            <p class="p-contents">
                                Menampilkan daftar kuantitas dan nilai seluruh barang persediaan per tanggal
                                yang ditentukan.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-ringkasan-persediaan-barang" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 padding-remove-right">	
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title">Nilai Persediaan Barang</h3>
                            <p class="p-contents">
                                Rangkuman informasi penting seperti sisa stok yang tersedia, nilai, dan biaya rata-rata, untuk
                                barang persediaan.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-nilai-persediaan-barang" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title">Nilai Stok Gudang</h3>
                            <p class="p-contents">
                                Laporan ini menampilkan penilaian persediaan per gudang untuk semua produk
                            </p>
                            <br/>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-nilai-stok-gudang" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 col-report">
                            <h3 class="h-title">Rincian Persediaan Barang</h3>
                            <p class="p-contents">
                                Menampilkan daftar transaksi yang terkait dengan setiap Barang dan Jasa, dan menjelaskan
                                bagaimana transaksi tersebut mempengaruhi jumlah stok barang, nilai, dan harga biayanya.
                            </p>
                            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                                <a data-value="report-rincian-persediaan-barang" class="btn-report btn btn-danger btn-mini" href="#">
                                    <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                            </div>
                        </div>					
                    </div>					
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab5">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Ringkasan Aset Tetap</h3>
                        <p class="p-contents">
                            Menampilkan daftar aset tetap yang tercatat, dengan harga awal, akumulasi penyusutan,
                            dan nilai bukunya.
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-ringkasan-aset-tetap" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Detail Aset Tetap</h3>
                        <p class="p-contents">
                            Menampilkan daftar transaksi yang terkait dengan setiap aset, dan menjelaskan bagaimana
                            transaksi tersebut mempengaruhi nilai bukunya.
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-detail-aset-tetap" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Sold and Dispossal Asset</h3>
                        <p class="p-contents">
                            Lists of asset that is being sold and/or dispossed.
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-sold-and-dispossal-asset" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab6">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Laporan Rekonsiliasi</h3>
                        <p class="p-contents">
                            Menampilkan ringkasan rekonsiliasi bank yang sudah tercatat, dan juga perubahan saldo yang
                            belum dicatat atau identifikasi
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-laporan-rekonsiliasi" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Mutasi Rekening</h3>
                        <p class="p-contents">
                            Daftar seluruh transaksi rekening bank dalam suatu periode.
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-mutasi-rekening" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab7">
        <div class="col-md-12 padding-remove-side">
            <div class="grid simple">
                <div class="grid-body">
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Laporan Pajak Pemotongan</h3>
                        <p class="p-contents">
                            Menampilkan ringkasan perhitungan pajak dengan tipe pemotongan yang
                            digunakan pada transaksi Anda berdasarkan objek pajak.
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-laporan-pajak-pemotongan" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-report">
                        <h3 class="h-title">Laporan Pajak Penjualan</h3>
                        <p class="p-contents">
                            Menampilkan ringkasan perhitungan pajak dengan tipe penambahan yang
                            digunakan pada transaksi Anda.
                        </p>
                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12" style="padding-left:0px!important;">
                            <a data-value="report-laporan-pajak-penjualan" class="btn-report btn btn-danger btn-mini" href="#">
                                <i class="fas fa-print" style="margin-right: 4px;"></i>Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>