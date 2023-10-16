<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
$branch_logo = !empty($branch['branch_logo_login']) ? $branch['branch_logo_login'] : site_url() . 'upload/branch/default_logo.png';


$message = !empty($this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
$status = !empty($this->session->flashdata('status')) ? $this->session->flashdata('status') : 0;
if ($status == 0) {
    // redirect(base_url(), 'refresh');
}

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
        <title><?php echo $title; ?></title>
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
        <link href="<?php echo base_url(); ?>assets/webarch/css/custom.css" rel="stylesheet" type="text/css" />        
        <link href="<?php echo base_url(); ?>assets/webarch/css/webarch.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.css" rel="stylesheet">        
    </head>
    <style>
        /*@font-family: 'Open Sans', sans-serif!important;*/
        .form-control{
            height: 34px!important;
        }
        .form-label{
            display: block!important;
            text-align: left!important;
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
        .select2-container .select2-selection--single {
            height: 34px;
        }
        .select2-selection__rendered {
            padding-top:2px;
            text-align: left!important;
        }
        #captcha_image{
            width: 100%;
            padding-top:2px;
            padding-bottom:10px;    
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
            <div class="row login-container column-seperation">
                <div class="col-md-offset-5 col-md-3 col-xs-12 col-sm-12" style="padding: 20px;background-color: #ffffff;margin-bottom: 10px;border: 4px solid #cacaca!important;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="text-align:center;padding-left:0px;padding-right:0px;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;width:150px;">
                        <h4 style="margin-bottom:0px;">Registrasi Akun</h4>
                        <p>Silahkan isikan formulir dibawah ini</p>           
                        <form action="#" method="post" class="login-form validate" id="register-form" name="register-form">
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input class="form-control" id="txtfullname" name="fullname" type="text" placeholder="" value="" required>
                                    </div>
                                </div>                                        
                            </div>                    
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label" style="display: flex;">Negara</label>
                                        <select class="form-control" id="select" name="select">
                                            <option value="62" data-country-phone="62">Indonesia (+62)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label" style="display: flex;">Nomor WhatsApp</label>
                                        <input class="form-control" id="code" name="code" type="text" required placeholder="" value="+62" style="width:25%;float:left;" readonly>
                                        <input class="form-control" id="txttelepon" name="telepon" type="number" required placeholder="" value="" style="width:75%;float:left;">
                                    </div>
                                </div>      
                                <!--      
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label">Email</label>
                                        <input class="form-control" id="txtemail" name="email" type="email" required placeholder="" value="">
                                    </div>
                                </div>
                                -->            
                            </div>                            
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Password</label> <span class="help"></span>
                                        <input class="form-control" id="txtpassword" name="password" type="password" required placeholder="" value="">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12" style="padding-left:0px;padding-right:0px;">
                                    <div class="form-group col-md-12">
                                        <label class="form-label">Konfirmasi Password</label> <span class="help"></span>
                                        <input class="form-control" id="txtpassword2" name="password2" type="password" required placeholder="" value="">
                                    </div>
                                </div>        	
                            </div>     
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top:10px;">
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
                            </div>                            
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                        <button id="btnRegister" class="btn pull-right" type="submit" style="width: 100%;background-color:#318fff;color:white;">
                                            <i class="fas fa-hand-point-right"></i>&nbsp;
                                            Daftar
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="control-group col-md-12 col-sm-12 col-xs-12" style="text-align: center;padding-bottom:10px;">
                                        <div class="checkbox checkbox check-success">
                                            <a class="" href="<?php echo base_url('login'); ?>">Sudah Punya Akun ?</a>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                </div>              
                            </div>    
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- BEGIN JS DEPENDECENCIES-->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script> -->
        <script src="<?php echo base_url(); ?>assets/core/plugins/select2-4.0.8/js/select2.min.js" type="text/javascript"></script>
        <!-- END CORE JS DEPENDECENCIES-->

        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>

        <!-- END -->
        <!-- BEGIN PAGE LEVEL JS -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/notifications.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery.redirect.js" type="text/javascript"></script>  
        <script src="<?php echo base_url(); ?>assets/core/plugins/jconfirm-3.3.4/dist/jquery-confirm.min.js"></script>
    </body>
    <script>
        $(document).ready(function () {
            $("#txtfullname").focus();
            // $("#txtfullname").val('Joceline Putra');
            // $("#txtusername").val('joe');
            // $("#txttelepon").val('81225518118');
            // $("#txtemail").val('joceline.putra@gmail.com');
            // $("#txtpassword").val('masterjoe00');
            // $("#txtpassword2").val('masterjoe00');

            // var form = '#register-form';   
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
                    if ($.isNumeric(datas.id) == true) {
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
                $("#txttelepon").focus();
            });                     
            $("#register-form").on('submit', (function (e) {
                e.preventDefault();
                var next = true;

                if ($("#register-form input[name='fullname']").val().length == 0) {
                    notif(0, 'Full Name belum diisi');
                    $("#txtfullname").focus();
                    next = false;
                }

                if (next) {
                    /*
                     if($("#register-form input[name='username']").val().length == 0){
                     notif(0,'Username belum diisi');
                     $("#txtusername").focus();
                     next = false;
                     } */
                }

                if (next) {
                    if ($("#register-form input[name='telepon']").val().length == 0) {
                        notif(0, 'Telepon belum diisi');
                        $("#txttelepon").focus();
                        next = false;
                    }
                }

                if (next) {
                    // if ($("#register-form input[name='email']").val().length == 0) {
                    //     notif(0, 'Email belum diisi');
                    //     $("#txtemail").focus();
                    //     next = false;
                    // }
                }

                if (next) {
                    if ($("#register-form input[name='password']").val().length == 0) {
                        notif(0, 'Password belum diisi');
                        $("#txtpassword").focus();
                        next = false;
                    }
                }

                if (next) {
                    if ($("#register-form input[name='password2']").val().length == 0) {
                        notif(0, 'Konfirmasi Password belum diisi');
                        $("#txtpassword2").focus();
                        next = false;
                    }
                }

                if (next) {
                    if ($("#register-form input[name='password']").val() != $("#register-form input[name='password2']").val()) {
                        notif(0, 'Password tidak sama');
                        next = false;
                    }
                }                

                if (next) {
                    if ($("#register-form input[name='captcha']").val().length == 0) {
                        notif(0, 'Angka belum diisi');
                        $("#captcha").focus();
                        next = false;
                    }
                }
                
                if (next) {
                    var data = {
                        action: 'register-create',
                        fullname: $("#txtfullname").val(),
                        // username: $("#txtusername").val(),
                        // email: $("#txtemail").val(),
                        email:'',
                        code: $("#code").val(),
                        telepon: $("#txttelepon").val(),
                        password: $("#txtpassword").val(),
                        password2: $("#txtpassword2").val(),
                        captcha: $("#captcha").val()
                    };
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url('login/manage'); ?>",
                        data: data,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#btnRegister').removeClass('btn-primary');
                            $('#btnRegister').html('<i class="fas fa-spinner"></i> Silahkan Tunggu...');
                            $('#btnRegister').prop('disabled', true);
                        },
                        success: function (d) {
                            var url_before = $("#url").val();
                            var base_url = "<?= base_url(); ?>";
                            if (parseInt(d.status) == 1) {
                                notif(1, d.message);
                                var data = {activation_code: d.result.user_activation};
                                // $.redirect(d.result.return_url,data,"POST","_self");                  
                                window.location.href = d.result.return_url;
                            } else {
                                notif(0, d.message);
                                $('#btnRegister').addClass('btn-primary');
                                $("#register-form input[name='password']").val('');
                                $("#register-form input[name='password2']").val('');
                                $('#btnRegister').html('<i class="fas fa-hand-point-right"></i> Daftar');
                                $('#btnRegister').prop('disabled', false);
                            }
                        },
                        error: function (xhr, Status, err) {
                            notif(0, 'Error');
                            $('#btnRegister').addClass('btn-primary');
                            $("#register-form input[name='password']").val('');
                            $("#register-form input[name='password2']").val('');
                            $("#register-form input[name='captcha']").val('');                            
                            $('#btnRegister').html('<i class="fas fa-hand-point-right"></i> Daftar');
                            $('#btnRegister').prop('disabled', false);
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
</html>