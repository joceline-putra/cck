<!-- js-files -->
<!-- jquery -->
<script src="<?php echo $template; ?>js/jquery-2.1.4.min.js"></script>
<!-- //jquery -->
<script src="<?php echo base_url(); ?>assets/webarch/plugins/jquery-notifications/js/messenger.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/webarch/plugins/jquery-notifications/js/messenger-theme-future.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/webarch/plugins/toastr/toastr.min.js"></script>

<!-- popup modal (for signin & signup)-->
<script src="<?php echo $template; ?>js/jquery.magnific-popup.js"></script>
<script>
    $(document).ready(function () {
        var url = "<?php echo base_url('website'); ?>";
        $('.popup-with-zoom-anim').magnificPopup({
            type: 'inline',
            fixedContentPos: false,
            fixedBgPos: true,
            overflowY: 'auto',
            closeBtnInside: true,
            preloader: false,
            midClick: true,
            removalDelay: 300,
            mainClass: 'my-mfp-zoom-in'
        });

        $(document).on("click", "#btn_subscribe", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var formData = new FormData();
            formData.append('action', 'subscribe');
            formData.append('subscribe_email', $("#subscribe_email").val());
            if ($("#subscribe_email").val().length == 0) {
                notif(0, 'Email harus diisi');
            } else {
                $.ajax({
                    type: "post",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: 'false',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) === 1) {
                            notif(0, d.message);
                        } else {
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            }
        });
        $(document).on("click", "#btn_search", function (e) {
            e.preventDefault();
            e.stopPropagation();
            alert('btn_search');
        });
        $(document).on("click", "#btn_cart", function (e) {
            e.preventDefault();
            e.stopPropagation();
            // alert('btn_cart');
            window.location.href = '<?php echo base_url('cart'); ?>';
        });
        $(document).on("click", ".btn_cart_add", function (e) {
            var id = $(this).attr('data-product-id');
            var unit = $(this).attr('data-product-unit');
            var formData = new FormData();
            formData.append('action', 'cart_add');
            formData.append('product_id', id);
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                dataType: 'json',
                cache: 'false',
                contentType: false,
                processData: false,
                beforeSend: function () {},
                success: function (d) {
                    if (parseInt(d.status) === 1) {
                        notif(0, d.message);
                    } else {
                        notif(0, d.message);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });
        });

        $(document).on("click", "#btn_signin", function (e) {
            e.preventDefault();
            e.stopPropagation();

            if ($("#signin_username").val().length == 0) {
                notif(0, 'User harus diisi');
            } else if ($("#signin_password").val().length == 0) {
                notif(0, 'Password harus diisi');
            } else {
                var formData = new FormData();
                formData.append('action', 'signin');
                formData.append('signin_username', $("#signin_username").val());
                formData.append('signin_password', $("#signin_password").val());
                $.ajax({
                    type: "post",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: 'false',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        if (parseInt(d.status) === 1) {
                            notif(0, d.message);
                        } else {
                            notif(0, d.message);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            }
        });
        $(document).on("click", "#btn_signup", function (e) {
            e.preventDefault();
            e.stopPropagation();

            if ($("#signup_name").val().length == 0) {
                notif(0, 'Nama harus diisi');
            } else if ($("#signup_number").val().length == 0) {
                notif(0, 'Nomor WhatsApp harus diisi');
            } else if ($("#signup_password").val().length == 0) {
                notif(0, 'Password harus diisi');
            } else {

                if ($("#signup_password").val() != $("#signup_password2").val()) {
                    notif(0, 'Konfirmasi Password Tidak Sesuai');
                } else {
                    var formData = new FormData();
                    formData.append('action', 'signin');
                    formData.append('signup_name', $("#signup_name").val());
                    formData.append('signup_number', $("#signup_number").val());
                    formData.append('signup_password', $("#signup_password").val());
                    formData.append('signup_password2', $("#signup_password2").val());
                    $.ajax({
                        type: "post",
                        url: url,
                        data: formData,
                        dataType: 'json',
                        cache: 'false',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {},
                        success: function (d) {
                            if (parseInt(d.status) === 1) {
                                notif(0, d.message);
                            } else {
                                notif(0, d.message);
                            }
                        },
                        error: function (xhr, Status, err) {
                            notif(0, err);
                        }
                    });
                }
            }
        });
        $(document).on("click", "#btn_checkout", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if ($("#checkout_name").val().length == 0) {
                notif(0, 'Nama harus diisi');
            } else if ($("#checkout_number").val().length == 0) {
                notif(0, 'Nomor WhatsApp harus diisi');
            } else if ($("#checkout_address").val().length == 0) {
                notif(0, 'Alamat harus diisi');
            } else if ($("#checkout_city").find(':selected').val() == 0) {
                notif(0, 'Kota harus dipilih');
            } else {
                var formData = new FormData();
                formData.append('action', 'checkout');
                formData.append('checkout_name', $("#checkout_name").val());
                formData.append('checkout_number', $("#checkout_number").val());
                formData.append('checkout_address', $("#checkout_address").val());
                formData.append('checkout_city', $("#checkout_city").find(':selected').val());
                $.ajax({
                    type: "post",
                    url: url,
                    data: formData,
                    dataType: 'json',
                    cache: 'false',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {},
                    success: function (d) {
                        var s = d.status;
                        var m = d.message;
                        var r = d.result;
                        if (parseInt(s) === 1) {
                            notif(s, m);

                            /* hint zz_for or zz_each */


                        } else {
                            notif(s, m);
                        }
                    },
                    error: function (xhr, Status, err) {
                        notif(0, err);
                    }
                });
            }
        });

        function loadCart() {
            var table = $("#table_cart");
            var formData = new FormData();
            formData.append('action', 'cart');
            // formData.append('id', $("#BY_DATA_ID").attr('data-id'));
            $.ajax({
                type: "post",
                url: url,
                data: formData,
                dataType: 'json',
                cache: 'false',
                contentType: false,
                processData: false,
                beforeSend: function () {},
                success: function (d) {
                    var s = d.status;
                    var m = d.message;
                    var r = d.result;
                    if (parseInt(s) === 1) {
                        // notif(s,m);

                        /* hint zz_for or zz_each */
                        if (parseInt(d.total_records) > 0) {
                            $("#table_cart tbody").html('');

                            var dsp = '';
                            var total_records = parseInt(d.total_records);
                            for (var a = 0; a < total_records; a++) {

                                var product_url = '#';
                                dsp += '<tr class="rem' + a + '">';
                                dsp += '  <td class="invert">' + a + '</td>';
                                dsp += '  <td class="invert-image">';
                                dsp += '    <a href="' + product_url + '">';
                                dsp += '      <img src="' + r[a]['product_image'] + '" alt=" " class="img-responsive" style="width:100px;height:100px;margin:0;">';
                                dsp += '    </a>';
                                dsp += '  </td>';
                                dsp += '  <td class="invert">';
                                dsp += '    <div class="quantity">';
                                dsp += '      <div class="quantity-select">';
                                dsp += '        <div class="entry value-minus">&nbsp;</div>';
                                dsp += '        <div class="entry value">';
                                dsp += '          <span>' + r[a]['item_qty'] + '</span>';
                                dsp += '        </div>';
                                dsp += '        <div class="entry value-plus active">&nbsp;</div>';
                                dsp += '      </div>';
                                dsp += '    </div>';
                                dsp += '  </td>';
                                dsp += '  <td class="invert">' + r[a]['product_name'] + '</td>';
                                dsp += '  <td class="invert">' + r[a]['item_price'] + '</td>';
                                dsp += '  <td class="invert">';
                                dsp += '    <div class="rem">';
                                dsp += '      <div class="close' + a + '"> </div>';
                                dsp += '    </div>';
                                dsp += '  </td>';
                                dsp += '</tr>';

                            }
                            $("#table_cart tbody").html(dsp);
                        }


                    } else {
                        // notif(s,m);
                    }
                },
                error: function (xhr, Status, err) {
                    notif(0, err);
                }
            });
        }
        loadCart();
    });


    function notif($type, $msg) {
        // if (parseInt($type) === 1) {
        //   //Toastr.success($msg);
        //   Toast.fire({
        //     type: 'success',
        //     title: $msg
        //   });
        // } else if (parseInt($type) === 0) {
        //   //Toastr.error($msg);
        //   Toast.fire({
        //     type: 'error',
        //     title: $msg
        //   });
        // }
        alert($msg);
    }
    function notifSuccess(msg) {
        Messenger().post({
            message: msg,
            type: 'success',
            showCloseButton: true
        });
    }
    function notifError(msg) {
        Messenger().post({
            message: msg,
            type: 'error',
            showCloseButton: true
        });
    }

</script>
<!-- Large modal -->
<!-- <script>
  $('#').modal('show');
</script> -->
<!-- //popup modal (for signin & signup)-->

<!-- cart-js -->
<script src="<?php echo $template; ?>js/minicart.js"></script>
<script>
    paypalm.minicartk.render(); //use only unique class names other than paypalm.minicartk.Also Replace same class name in css and minicart.min.js

    paypalm.minicartk.cart.on('checkout', function (evt) {
        var items = this.items(),
                len = items.length,
                total = 0,
                i;

        // Count the number of each item in the cart
        for (i = 0; i < len; i++) {
            total += items[i].get('quantity');
        }

        if (total < 3) {
            alert('The minimum order quantity is 3. Please add more to your shopping cart before checking out');
            evt.preventDefault();
        }
    });
</script>
<!-- //cart-js -->
<!--quantity-->
<script>
    $('.value-plus').on('click', function () {
        var divUpd = $(this).parent().find('.value'),
                newVal = parseInt(divUpd.text(), 10) + 1;
        divUpd.text(newVal);
    });

    $('.value-minus').on('click', function () {
        var divUpd = $(this).parent().find('.value'),
                newVal = parseInt(divUpd.text(), 10) - 1;
        if (newVal >= 1)
            divUpd.text(newVal);
    });
</script>
<!--quantity-->
<script>
    $(document).ready(function (c) {
        $('.close1').on('click', function (c) {
            $('.rem1').fadeOut('slow', function (c) {
                $('.rem1').remove();
            });
        });
    });
</script>
<script>
    $(document).ready(function (c) {
        $('.close2').on('click', function (c) {
            $('.rem2').fadeOut('slow', function (c) {
                $('.rem2').remove();
            });
        });
    });
</script>
<script>
    $(document).ready(function (c) {
        $('.close3').on('click', function (c) {
            $('.rem3').fadeOut('slow', function (c) {
                $('.rem3').remove();
            });
        });
    });
</script>
<!--//quantity-->
<!-- price range (top products) -->
<script src="<?php echo $template; ?>js/jquery-ui.js"></script>
<script>
    //<![CDATA[ 
    $(window).load(function () {
        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 9000,
            values: [50, 6000],
            slide: function (event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));

    }); //]]>
</script>
<!-- //price range (top products) -->

<!-- flexisel (for special offers) -->
<script src="<?php echo $template; ?>js/jquery.flexisel.js"></script>
<script>
    $(window).load(function () {
        $("#flexiselDemo1").flexisel({
            visibleItems: 3,
            animationSpeed: 1000,
            autoPlay: true,
            autoPlaySpeed: 3000,
            pauseOnHover: true,
            enableResponsiveBreakpoints: true,
            responsiveBreakpoints: {
                portrait: {
                    changePoint: 480,
                    visibleItems: 1
                },
                landscape: {
                    changePoint: 640,
                    visibleItems: 2
                },
                tablet: {
                    changePoint: 768,
                    visibleItems: 2
                }
            }
        });

    });
</script>
<!-- //flexisel (for special offers) -->

<!-- password-script -->
<script>
    window.onload = function () {
        document.getElementById("password1").onchange = validatePassword;
        document.getElementById("password2").onchange = validatePassword;
    }

    function validatePassword() {
        var pass2 = document.getElementById("password2").value;
        var pass1 = document.getElementById("password1").value;
        if (pass1 != pass2)
            document.getElementById("password2").setCustomValidity("Passwords Don't Match");
        else
            document.getElementById("password2").setCustomValidity('');
        //empty string means no validation error
    }
</script>
<!-- //password-script -->

<!-- smoothscroll -->
<script src="<?php echo $template; ?>js/SmoothScroll.min.js"></script>
<!-- //smoothscroll -->

<!-- start-smooth-scrolling -->
<script src="<?php echo $template; ?>js/move-top.js"></script>
<script src="<?php echo $template; ?>js/easing.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $(".scroll").click(function (event) {
            event.preventDefault();

            $('html,body').animate({
                scrollTop: $(this.hash).offset().top
            }, 1000);
        });
    });
</script>
<!-- //end-smooth-scrolling -->

<!-- smooth-scrolling-of-move-up -->
<script>
    $(document).ready(function () {
        /*
         var defaults = {
         containerID: 'toTop', // fading element id
         containerHoverID: 'toTopHover', // fading element hover id
         scrollSpeed: 1200,
         easingType: 'linear' 
         };
         */
        $().UItoTop({
            easingType: 'easeOutQuart'
        });

    });
</script>
<!-- //smooth-scrolling-of-move-up -->

<!-- imagezoom -->
<script src="<?php echo $template; ?>js/imagezoom.js"></script>
<!-- //imagezoom -->

<!-- FlexSlider -->
<script src="<?php echo $template; ?>js/jquery.flexslider.js"></script>
<script>
    // Can also be used with $(document).ready()
    $(window).load(function () {
        $('.flexslider').flexslider({
            animation: "slide",
            controlNav: "thumbnails"
        });
    });
</script>
<!-- for bootstrap working -->
<script src="<?php echo $template; ?>js/bootstrap.js"></script>
<!-- //for bootstrap working -->
<!-- //js-files -->

