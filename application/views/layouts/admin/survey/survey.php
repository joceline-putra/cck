<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");
$branch_logo = $branch['branch_logo_login'];
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

        <!-- BEGIN PLUGIN CSS -->
        <link href="<?php echo base_url(); ?>assets/core/plugins/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />    
        <link href="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!--Sweet Alert-->
        <link href="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.css"> 
        <link href="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/sweetalert2.min.css"  rel="stylesheet" type="text/css"/>
        <!-- END PLUGIN CSS -->
        <!-- BEGIN CORE CSS FRAMEWORK -->
        <link href="<?php echo base_url(); ?>assets/webarch/css/webarch.css" rel="stylesheet" type="text/css" />
        <!-- END CORE CSS FRAMEWORK -->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
    </head>
    <!-- END HEAD -->
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
            margin-top:5%!important;
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

    <!-- <body class="error-body no-top" style="background: darkcyan url('assets/webarch/img/pattern.jpg') repeat center center fixed;"> -->
    <body class="error-body no-top" style="background: #cacaca!important;">
        <div class="container-fluid">
            <div class="row login-container column-seperation" style="">
                <div class="col-md-offset-4 col-md-5 col-xs-12 col-sm-12" style="padding: 30px;background-color: #ffffff;margin-bottom: 20px;border: 4px solid #cacaca!important;">
                    <input id="flag" name="flag" class="form-control" type="hidden" value="<?php echo $flag; ?>">

                    <div id="div-survey" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:none;text-align:center;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;width:98px;">
                        <h4 style="margin-bottom:0px;">Survey Kepuasan</h4>
                        <p>Silahkan isikan formulir dibawah ini</p>           

                        <form action="#" method="post" class="survey-form validate" id="survey-form" name="survey-form">
                            <input id="session" name="session" class="form-control" type="hidden" value="<?php echo $session; ?>">
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                <div class="row">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label">Apakah anda puas dengan pelayanan Sweet Home Residence ?</label>
                                        <select class="form-control" id="pelayanan" name="pelayanan">
                                            <option value="5">Sangat Baik</option>
                                            <option value="4">Cukup Baik</option>
                                            <option value="3">Baik</option>									
                                            <option value="2">Tidak Baik</option>																		
                                            <option value="1">Sangat Tidak Baik</option>																											
                                        </select>
                                    </div>			
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label">Kritik / Saran ?</label>
                                        <textarea class="form-control" id="note" name="note" type="text" placeholder="Mohon isikan Kritik dan Saran"></textarea>
                                    </div>
                                </div>           
                            </div>   
                            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top:20px;">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12 col-sm-12 div-login">
                                        <button id="btnSend" class="btn pull-right" type="submit" style="width: 100%;background-color:#318fff;color:white;">
                                            <i class="fas fa-hand-point-right"></i>&nbsp;
                                            Kirim
                                        </button>
                                    </div>
                                </div>            
                            </div>    
                        </form>
                    </div>

                    <div id="div-thankyou" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:none;text-align:center;">
                        <img src="<?php echo $branch_logo; ?>" class="img-responsive" style="padding-top:20px;margin:0 auto;width:98px;">
                        <h4 style="margin-bottom:0px;">Terimakasih Atas Feedback Anda</h4>
                        <p>Kritik dan Saran sangat berguna bagi kami.</p>
                    </div>        
                </div>
            </div>
        </div>

        <!-- BEGIN JS DEPENDECENCIES-->
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
        <!-- END CORE JS DEPENDECENCIES-->

        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>

        <!-- END -->
        <!-- BEGIN PAGE LEVEL JS -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/jquery-notifications/js/demo/demo.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/core/plugins/notifications.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/toastr/toastr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/plugins/sweetalert2/dist/sweetalert2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/core/js/jquery.redirect.js" type="text/javascript"></script>      


        <script>
            $(document).ready(function () {
                // $("#txtfullname").val('Joceline Putra');
                // $("#txtusername").val('joe');
                // $("#txttelepon").val('085224980588');
                // $("#txtemail").val('joceline.putra@gmail.com');
                // $("#txtpassword").val('masterjoe00');
                // $("#txtpassword2").val('masterjoe00');
                var flag = $("#flag").val();
                if (parseInt(flag) > 0) {
                    $("#div-survey").hide(300);
                    $("#div-thankyou").show(300);
                } else {
                    $("#div-survey").show(300);
                    $("#div-thankyou").hide(300);
                }

                $("#survey-form").on('submit', (function (e) {
                    e.preventDefault();
                    var next = true;

                    if ($("#survey-form select[name='pelayanan']").val().length == 0) {
                        notif(0, 'Pelayanan belum dipilih');
                        next = false;
                    }

                    if (next) {
                        if ($("#survey-form textarea[name='note']").val().length == 0) {
                            notif(0, 'Kritik dan Saran belum diisi');
                            $("#note").focus();
                            next = false;
                        }
                    }

                    if (next) {
                        var session = $("#session").val();
                        var data = {
                            action: 'update-from-outside',
                            session: session,
                            rating: $("#pelayanan").find(':selected').val(),
                            note: $("#note").val(),
                        };
                        $.ajax({
                            type: "POST",
                            url: "<?= base_url('survey/request/'); ?>" + session,
                            data: data,
                            dataType: 'json',
                            beforeSend: function () {
                                $('#btnSend').removeClass('btn-primary');
                                $('#btnSend').html('<i class="fas fa-spinner"></i> Silahkan Tunggu...');
                                $('#btnSend').prop('disabled', true);
                            },
                            success: function (d) {
                                var url_before = $("#url").val();
                                var base_url = "<?= base_url(); ?>";
                                if (parseInt(d.status) == 1) {
                                    notif(1, d.message);
                                    $("#div-survey").hide(300);
                                    $("#div-thankyou").show(300);
                                } else {
                                    notif(0, d.message);
                                    $('#btnSend').addClass('btn-primary');
                                    $("#survey-form select[name='pelayanan']").val(5).trigger('change');
                                    $("#survey-form input[name='note']").val('');
                                    $('#btnSend').html('<i class="fa-hand-point-right"></i> Kirim');
                                    $('#btnSend').prop('disabled', false);
                                }
                            },
                            error: function (xhr, Status, err) {
                                notif(0, 'Error');
                                $('#btnSend').addClass('btn-primary');
                                $("#survey-form select[name='pelayanan']").val(5).trigger('change');
                                $("#survey-form input[name='note']").val('');
                                $('#btnSend').html('<i class="fa-hand-point-right"></i> Kirim');
                                $('#btnSend').prop('disabled', false);
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