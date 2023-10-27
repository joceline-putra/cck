<?php
$branch_logo = !empty($branch['branch_logo_login']) ? $branch['branch_logo_login'] : site_url() . 'upload/branch/default_logo.png';
// var_dump($branch_logo);die;

// var_dump($captcha);die;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

$return_url = $this->session->userdata('url_before');

// $captcha = str_split($captcha);
$width = (strlen($captcha) * 9) + 60;
$height = 30;

$textImage = imagecreate($width, $height);
$color = imagecolorallocate($textImage, 0, 0, 0);
imagecolortransparent($textImage, $color);
imagestring($textImage, 5, 35, 5, $captcha, 0xFFFFFF);
// Increase text-font size
imageline($textImage, 30, 45, 165, 45, $color);

//break lines
$captcha = explode("\\n", $captcha);
$lines = count($captcha);

// create background image layer
$background = imagecreatefromjpeg('https://img.freepik.com/free-vector/abstract-organic-lines-background_1017-26669.jpg?size=626&ext=jpg');
// Merge background image and text image layers
imagecopymerge($background, $textImage, 15, 15, 0, 0, $width, $height, 100);


$output = imagecreatetruecolor($width, $height);
imagecopy($output, $background, 0, 0, 20, 13, $width, $height);

ob_start();
imagepng($output);
$image_base_64 = base64_encode(ob_get_clean());
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Lupa Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link href="<?php echo base_url(); ?>assets/core/favicon.png" sizes="16x16 32x32" type="image/png" rel="icon">     
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/css/messenger-theme-flat.css" rel="stylesheet" type="text/css" media="screen" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/select2-4.0.8/css/select2.css" rel="stylesheet" type="text/css" media="screen"/> 	
        <link href="<?php echo base_url(); ?>assets/core/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />    
        <link href="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.css"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url(); ?>assets/core/css/custom.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/css/webarch.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.css" rel="stylesheet">      
    </head>
    <style>
        /*@font-family: 'Open Sans', sans-serif!important;*/
        .form-control{
            height: 34px!important;
        }
        .login-container{
            margin-top:2%!important;
        }
        .login-container > div{
            /*padding: 15px;*/
            background-color: #ffffff;
            margin-bottom: 20px;
            border: 1px solid #cacaca!important;
            padding:0px!important;
        }
        .login-form input{
            font-family: var(--font-family);
            font-size: 14px;
            font-weight: 600;
        }
        .div-login{
            margin-bottom:6px;
        }
        #btnLogin{
            width: 100%;
        } 

        #captcha_image{
            width: 100%;
            padding-top:2px;
            padding-bottom:10px;    
        }
        .select2-container .select2-selection--single {
            height: 34px;
        }
        .select2-selection__rendered {
            padding-top:2px;
            text-align: left!important;
        }
        /*@background-url: '';*/
        /*// Extra small devices (portrait phones, less than 576px)*/
        /*// No media query for `xs` since this is the default in Bootstrap*/

        /*// Small devices (landscape phones, 576px and up)*/
        @media only screen and (min-width: 576px) {
            #body{
                background-color:#f89b1c!important;
                /*background: white url('services/assets/images/wallpaper/corona.png') no-repeat center center fixed; */
                /*background: white url('services/assets/images/wallpaper/pattern.jpg') repeat center center fixed;       */
                /*background: url('https://wallpaperaccess.com/full/417163.jpg') no-repeat center center fixed;   */
            } 
        }

        /*// Medium devices (tablets, 768px and up)*/
        @media only screen and (min-width: 768px) {

        }

        /*// Large devices (desktops, 992px and up)*/
        @media only screen and (min-width: 992px) {

        }

        /*// Extra large devices (large desktops, 1200px and up)*/
        @media only screen and (min-width: 1200px) {

        }
    </style>  
    <body class="error-body no-top" style="background: #cacaca!important;">
        <div class="container-fluid">
            <div class="row login-container column-seperation" style="">
                <div class="col-md-offset-5 col-md-3 col-xs-12 col-sm-12" style="padding: 30px;background-color: #ffffff;margin-bottom: 20px;/*border-radius:20px;*/border: 4px solid #cacaca!important;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;width:150px;">
                        <h4 style="margin-bottom:0px;">Lupa Password</h4>
                        <p>Silahkan masukkan nomor whatsapp anda untuk permintaan lupa password</p>           
                        <form action="#" method="post" class="password-form validate" id="password-form" name="password-form">
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <label class="form-label" style="display: flex;">Reset via</label>
                                    <select class="form-control" id="via" name="via">
                                        <option value="WA" selected>WhatsApp</option>
                                        <!-- <option value="EM">Email</option>                                         -->
                                    </select>
                                </div>
                            </div>                            
                            <!-- If WhatsApp -->
                            <div id="wa">
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label" style="display: flex;">Negara</label>
                                        <select class="form-control" id="select" name="select">
                                            <option value="62" data-country-phone="62">Indonesia (+62)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label" style="display: flex;">Nomor WhatsApp</label>
                                        <input class="form-control" id="code" name="code" type="text" required placeholder="" value="+62" style="width:20%;float:left;" readonly>
                                        <input class="form-control" id="whatsapp" name="whatsapp" type="text" placeholder="Nomor WhatsApp" value="" style="width:80%;float:left;">
                                    </div>
                                </div>
                            </div>
                            <!-- End if WhatsApp -->

                            <div id="em" style="display:none;">                            
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label" style="display: flex;">Email</label>
                                        <input class="form-control" id="email" name="email" type="text" placeholder="Email" value="">
                                    </div>
                                </div>					
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <label class="form-label" style="display: flex;">Angka Yang Muncul</label>
                                    <?php printf('<img id="captcha_image" src="data:image/png;base64,%s" />', $image_base_64); ?>
                                </div>
                            </div>            
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="form-label" style="display: flex;">Masukkan Angka Diatas</label>
                                    <input class="form-control" id="captcha" name="captcha" type="text" required placeholder="?" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                    <button id="btnLogin" class="btn pull-right" type="submit" style="background-color:#318fff;color:white;">
                                        <i class="fas fa-paper-plane"></i>&nbsp;
                                        Kirim Permintaan
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="control-group col-md-12 col-sm-12 col-xs-12" style="text-align: center;padding-bottom:10px;">
                                    <div class="checkbox checkbox check-success">
                                        <a id="btn-login" href="#">Sudah Punya Akun ?</a>&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>              
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-reset" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="myModalLabel" class="semi-bold">Reset Password</h4>
                        <p class="no-margin">Mendaftar disini untuk login ke system :</p>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active">
                                        <a href="#tab11" role="tab" data-toggle="tab" aria-expanded="true">Reset</a>
                                    </li>
                                    <li class="">
                                        <a href="#tab22" role="tab" data-toggle="tab" aria-expanded="false">Trouble</a>
                                    </li>
                                </ul>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse"></a>
                                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                                    <a href="javascript:;" class="reload"></a>
                                    <a href="javascript:;" class="remove"></a>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab11">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">     
                                            <form id="form-reset" action="#">
                                                <div class="col-md-12">
                                                    <h5>Permintaan Reset Password</h5>
                                                    <div class="row form-row">
                                                        <div class="col-md-12">
                                                            <input name="resetEmail" type="text" class="form-control" placeholder="Address">
                                                        </div>
                                                    </div>
                                                    <div class="row small-text">
                                                        <p class="col-md-12">
                                                            Note: Masukkan email terdaftar jika anda lupa dengan kata sandi, password akan di kirim ke email anda.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="form-actions">
                                                    <div class="pull-left">
                                                        <div class="checkbox checkbox check-success">
                                                            <input type="checkbox" value="1" id="chkTerms">
                                                            <label for="chkTerms">Saya setuju dengan reset password ini. </label>
                                                        </div>
                                                    </div>
                                                    <div class="pull-right">
                                                        <button class="btn btn-danger btn-cons" type="button" onclick="resetPassword();"><i class="icon-ok"></i> Reset </button>
                                                    </div>
                                                </div>
                                            </form>    
                                        </div>    
                                    </div>
                                    <div class="tab-pane" id="tab22">
                                        <div class="col-md-12 col-sm-12 col-xs-12 padding-remove-side">
                                        </div>  
                                    </div>
                                </div> 
                            </div>
                        </div>  
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/select2-4.0.8/js/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/notifications.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery.redirect.js" type="text/javascript"></script>    
        <script src="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.js"></script>
        <script>
            $(document).ready(function () {
                var form = '#password-form';
                var operator = true;
                var url = "<?= base_url('login/process_sent_lost_password'); ?>";

                $("#via").select2();
                $('#select').select2({
                    //dropdownParent:$("#modal-id"), //If Select2 Inside Modal
                    placeholder: 'Pilih',
                    //width:'100%',
                    tags: true,
                    minimumInputLength: 0,
                    ajax: {
                        type: "get",
                        url: "<?= base_url('message/search'); ?>",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var query = {
                                search: params.term,
                                tipe: 1,
                                source: 'countries'
                            }
                            return query;
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    templateResult: function (datas) { //When Select on Click
                        if (!datas.id) {
                            return datas.text;
                        }
                        if ($.isNumeric(datas.id) == true) {
                            return datas.text;
                        } else {
                            return datas.text;
                        }
                    },
                    templateSelection: function (datas) { //When Option on Click
                        if (!datas.id) {
                            return datas.text;
                        }
                        //Custom Data Attribute
                        $(datas.element).attr('data-country-phone', datas.country_phone);
                        return datas.text;
                    }
                });
                $(document).on("change", "#select", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var var_custom = $(this).find(':selected').attr('data-country-phone');
                    // alert(var_custom);
                    $("#code").val('+' + var_custom);
                    $("#email").focus();
                });
                $(document).on("click", "#btn-login", function (e) {
                    var set_url = "<?php echo site_url(); ?>" + "login";
                    $.redirect(set_url, null, "POST", "_self");
                });
                $(document).on("change","#via",function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let id = $(this).find(':selected').val();
                    if(id == 'WA'){
                        $("#em").hide(300);
                        $("#wa").show(300);                        
                        $("#whatsapp").focus();
                    }else if(id == 'EM'){
                        $("#wa").hide(300);
                        $("#em").show(300);
                        $("#email").focus();
                    }
                });
                
                $("#password-form").on('submit', (function (e) {
                    e.preventDefault();
                    var next = true;
                    var ch = $("#via").find(":selected").val();

                    if(ch == 'WA'){
                        var user = $("#code").val() + $("#whatsapp").val();
                        var ch_via = 'Nomor WhatsApp';
                        if ($("#password-form input[name='whatsapp']").val().length == 0) {
                            notif(0, 'Nomor WhatsApp belum diisi');
                            $("#whatsapp").focus();
                            next = false;
                        }
                    }else if(ch == 'EM'){
                        var user = $("#email").val();
                        var ch_via = 'Email';
                        if ($("#password-form input[name='email']").val().length == 0) {
                            notif(0, 'Email belum diisi');
                            $("#email").focus();
                            next = false;
                        }
                    }


                    if (next) {
                        if ($("#password-form input[name='captcha']").val().length == 0) {
                            notif(0, 'Angka belum diisi');
                            $("#captcha").focus();
                            next = false;
                        }
                    }

                    if (next) {
                        var title = 'Konfirmasi';
                        var content = 'Apakah ini ' + ch_via + ' anda <b>' + user + '</b> ?';
                        $.confirm({
                            title: title,
                            content: content,
                            columnClass: 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
                            autoClose: 'button_2|10000',
                            closeIcon: true,
                            closeIconClass: 'fas fa-times',
                            animation: 'zoom',
                            closeAnimation: 'bottom',
                            animateFromElement: false,
                            buttons: {
                                button_1: {
                                    text: 'Ya',
                                    btnClass: 'btn-primary',
                                    keys: ['enter'],
                                    action: function () {
                                        $.ajax({
                                            type: "POST",
                                            url: url,
                                            data: $(form).serialize(),
                                            dataType: 'json',
                                            beforeSend: function () {
                                                $('#btnLogin').removeClass('btn-primary');
                                                $('#btnLogin').html('<i class="fas fa-spinner"></i> Please wait...');
                                                $('#btnLogin').prop('disabled', true);
                                            },
                                            success: function (d) {
                                                // var base_url = "<?= base_url(); ?>";
                                                if (parseInt(d.status) == 1) {
                                                    notif(1, d.message);
                                                    window.location.href = d.return_url;
                                                } else {
                                                    notif(0, d.message);
                                                    $('#btnLogin').addClass('btn-primary');
                                                    $("#password-form input[name='captcha']").val('');
                                                    $('#btnLogin').html('<i class="fas fa-paper-plane"></i> Kirim Permintaan');
                                                    $('#btnLogin').prop('disabled', false);
                                                    // window.location.href = d.return_url;                
                                                }
                                            },
                                            error: function (xhr, Status, err) {
                                                notif(0, 'Error');
                                                $('#btnLogin').addClass('btn-primary');
                                                $("#password-form input[name='password']").val('');
                                                $('#btnLogin').html('<i class="fas fa-lock-open"></i> Kirim Permintaan');
                                                $('#btnLogin').prop('disabled', false);
                                            }
                                        });
                                    }
                                },
                                button_2: {
                                    text: 'Tidak',
                                    btnClass: 'btn-danger',
                                    keys: ['Escape'],
                                    action: function () {
                                        //Close
                                    }
                                }
                            }
                        });

                    }
                }));
            });
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            function notif($type, $msg) {
                if (parseInt($type) === 1) {
                    //Toastr.success($msg);
                    Toast.fire({
                        type: 'success',
                        title: $msg
                    });
                } else if (parseInt($type) === 0) {
                    //Toastr.error($msg);
                    Toast.fire({
                        type: 'error',
                        title: $msg
                    });
                }
            }
        </script>        
    </body>
</html>