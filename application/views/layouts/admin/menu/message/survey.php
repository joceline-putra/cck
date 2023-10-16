<div class="row">
    <div class="col-md-12">
        <?php include '_navigation.php'; ?>
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
                            <div class="col-md-6 col-xs-12 col-sm-12" style="padding-left: 0;">
                                <h5><b><?php echo $title; ?></b></h5>
                            </div>
                            <!--
                            <div class="col-md-6 col-xs-12 col-sm-12 padding-remove-right">
                              <div class="pull-right">
                                <button id="btn-cancel" class="btn btn-default btn-small" type="reset"
                                  style="display: inline;">
                                  <i class="fas fa-times"></i>
                                  Tutup
                                </button>
                              </div>
                            </div>  -->
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                    <form id="form-master" name="form-master" method="" action="" enctype="multipart/form-data">
                                        <div class="col-md-12">
                                            <input id="id_document" name="id_document" type="hidden" value="" placeholder="id" readonly>
                                        </div>
                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Platform</label>
                                                    <select id="platform" name="platform" class="form-control" disabled readonly>
                                                        <option value="1">WhatsApp</option>
                                                        <!-- <option value="2">Telegram</option> -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label id="branch-label">Device</label>
                                                    <select id="device" name="device" class="form-control" disabled readonly>
                                                        <option value="0">-- Pilih --</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-9 col-sm-12 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label id="contact-label">Kontak</label>
                                                    <select id="contact" name="contact" class="form-control">
                                                        <option value="0">-- Pilih --</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                                <div class="form-group">
                                                    <label>Pesan</label>
                                                    <textarea id="teks" name="teks" type="text" class="form-control" readonly='true' rows="4" style="height:200px!important;"/>Halo Bpk/Ibu #nama# Terimakasih atas kepercayaannya menginap bersama kami.
                                                    Kami ingin lebih dekat dengan anda.ðŸ™‚

                                                    Mohon mengisi survey kepuasan pelayanan Hotel melalui link berikut ini:
                                                    #link#

                                                    Ditunggu kedatangan berikutnya.ðŸ™?
                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12 col-xs-12 col-sm-12" style="margin-top: 10px;">
                                            <div class="form-group">
                                                <div class="pull-right">
                                                    <button id="btn-save-now" data-action="1" class="btn btn-save btn-primary btn-small" type="button" style="display:none;">
                                                        <i class="fas fa-paper-plane"></i>
                                                        Kirim Langsung
                                                    </button>
                                                    <button id="btn-update" class="btn btn-info btn-small" type="button" style="display: none;">
                                                        <i class="fas fa-edit"></i>
                                                        Update
                                                    </button>
                                                    <button id="btn-delete" class="btn btn-danger btn-small" type="button" style="display: none;">
                                                        <i class="fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                    <button id="btn-cancel" onClick="formCancel" class="btn btn-warning btn-small" type="button" style="display:none;">
                                                        <i class="fas fa-ban"></i>
                                                        Batal
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
                            <div class="col-md-3 col-xs-12 col-sm-12" style="padding-left: 0;">
                                <h5><b>Data <?php echo $title; ?></b></h5>
                            </div>
                            <div class="col-md-9 col-xs-12 col-sm-12 padding-remove-right">
                                <div class="pull-right">
                                    <button id="btn-new" onClick="" class="btn btn-success btn-small" type="button"
                                            style="display: inline;">
                                        <i class="fas fa-plus"></i>
                                        Buat <?php echo $title; ?> Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side" style="padding-top:8px;">
                            <div class="col-lg-3 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-left">
                                <label class="form-label">Periode Awal</label>
                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                        <input name="start" id="start" type="text" class="form-control input-sm" readonly="true"
                                               value="<?php echo $first_date; ?>">
                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-left">
                                <label class="form-label">Periode Akhir</label>
                                <div class="col-md-12 col-sm-12 padding-remove-side">
                                    <div class="input-append success date col-md-12 col-lg-12 no-padding">
                                        <input name="end" id="end" type="text" class="form-control input-sm" readonly="true"
                                               value="<?php echo $end_date; ?>">
                                        <span class="add-on date-add"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-left">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <label class="form-label">Status <?php echo $title; ?></label>
                                    <select id="filter_flag" name="filter_flag" class="form-control">
                                        <option value="1">Direspon</option>
                                        <option value="0">Tidak Direspon</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-side">
                                <div class="col-md-12 col-xs-12 col-sm-12 padding-remove-side">
                                    <label class="form-label">Rating</label>
                                    <select id="filter_rating" name="filter_rating" class="form-control">
                                        <option value="0">Semua</option>
                                        <option value="5">[5] Sangat Baik</option>
                                        <option value="4">[4] Cukup Baik</option>
                                        <option value="3">[3] Baik</option>
                                        <option value="2">[2] Kurang Baik</option>
                                        <option value="1">[1] Sangat Kurang Baik</option>                                                                                
                                    </select>
                                </div>
                            </div>            
                            <div class="clearfix"></div>  
                            <div class="col-lg-8 col-md-3 col-xs-12 col-sm-12 form-group padding-remove-left">
                                <label class="form-label">Cari</label>
                                <input id="filter_search" name="filter_search" type="text" value="" class="form-control" placeholder="Pencarian" />
                            </div>
                            <div class="col-lg-4 col-md-2 col-xs-12 col-sm-12 form-group padding-remove-side">
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
                            <table id="table-data" class="table table-bordered">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab2">
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal-read" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-product" name="form-product" method="" action="">
                <div class="modal-header" style="background-color: #6F7A8A;">
                    <h4 style="color:white;">View Message</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <img id="img-preview2" class="img-responsive"
                                         data-is-new="0"
                                         style="width:100%"
                                         src=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 col-xs-12">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Nomor Sesi</label>
                                            <input id="message_session" name="message_session" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Device Pengirim</label>
                                            <select id="message_device" name="message_device" class="form-control" disabled="true">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-left">
                                        <div class="form-group">
                                            <label>Tanggal Dibuat</label>
                                            <input id="message_date_created" name="message_date_created" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12 padding-remove-right">
                                        <div class="form-group">
                                            <label>Tanggal Dikirim</label>
                                            <input id="message_date_sent" name="message_date_sent" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Status Terkirim</label>
                                            <select id="message_status" name="message_status" class="form-control" disabled="true">
                                                <option value="0">Antrian Pengiriman</option>
                                                <option value="1">Terkirim</option>
                                                <option value="2">Proses</option>
                                                <option value="4">Gagal Mengirim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Jenis Pesan</label>
                                            <select id="message_type" name="message_type" class="form-control" disabled="true">
                                                <option value="0">Tidak Diketahui</option>
                                                <option value="1">Teks</option>
                                                <option value="2">Gambar</option>
                                                <option value="3">Video</option>
                                                <option value="4">Dokumen</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>URL File</label>
                                            <input id="message_url" name="message_url" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Nomor Penerima</label>
                                            <input id="message_contact_number" name="message_contact_number" type="text" value="" class="form-control" readonly="true"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Nama Penerima</label>
                                            <input id="message_contact_name" name="message_contact_name" type="text" value="" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">

                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-remove-side">
                                        <div class="form-group">
                                            <label>Teks</label>
                                            <textarea id="message_text" name="message_text" type="text" value="" class="form-control" rows="4" style="height:400px!important;width:100%;"/></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button id="btn-whatsapp-send-message" onClick="" class="btn btn-primary btn-small" type="button" data-id="0">
                        <i class="fas fa-save"></i>
                        Kirim WhatsApp
                    </button>
                    <button class="btn btn-outline-danger waves-effect btn-small" type="button" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Tutup
                    </button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>