<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta content="text/html;charset=UTF-8" http-equiv="content-type"/>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport"/>
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->
        <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />  -->
        <style>
            /*@import url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');*/
            /*@import url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css');*/
            /*@import url('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');*/

          .receipt-content {
            background: #a2a2a2; 
          }
          .receipt-content .logo a:hover {
            text-decoration: none;
            color: #7793C4; 
          }

          .receipt-content .invoice-wrapper {
            background: #FFF;
            /*border: 1px solid #CDD3E2;*/
            box-shadow: 0px 0px 1px #CCC;
            padding: 40px 40px 60px;
            margin-top: 40px;
            border-radius: 4px; 
          }

          .receipt-content .invoice-wrapper .payment-details span {
            color: #A9B0BB;
            display: block; 
          }
          .receipt-content .invoice-wrapper .payment-details a {
            display: inline-block;
            margin-top: 5px; 
          }

          .receipt-content .invoice-wrapper .line-items .print a {
            display: inline-block;
            border: 1px solid #9CB5D6;
            padding: 13px 13px;
            border-radius: 5px;
            color: #708DC0;
            font-size: 13px;
            -webkit-transition: all 0.2s linear;
            -moz-transition: all 0.2s linear;
            -ms-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            transition: all 0.2s linear; 
          }

          .receipt-content .invoice-wrapper .line-items .print a:hover {
            text-decoration: none;
            border-color: #333;
            color: #333; 
          }


          @media (min-width: 1200px) {
            .receipt-content .container {width: 900px; } 
          }

          .receipt-content .logo {
            text-align: center;
            margin-top: 50px; 
          }

          .receipt-content .logo a {
            font-family: Myriad Pro, Lato, Helvetica Neue, Arial;
            font-size: 36px;
            letter-spacing: .1px;
            color: #555;
            font-weight: 300;
            -webkit-transition: all 0.2s linear;
            -moz-transition: all 0.2s linear;
            -ms-transition: all 0.2s linear;
            -o-transition: all 0.2s linear;
            transition: all 0.2s linear; 
          }

          .receipt-content .invoice-wrapper .intro {
            line-height: 25px;
            color: #444; 
          }

          .receipt-content .invoice-wrapper .payment-info {
            margin-top: 25px;
            padding-top: 15px; 
          }

          .receipt-content .invoice-wrapper .payment-info span {
            color: #A9B0BB; 
          }

          .receipt-content .invoice-wrapper .payment-info strong {
            display: block;
            color: #444;
            margin-top: 3px; 
          }

          @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .payment-info .text-right {
            text-align: left;
            margin-top: 20px; } 
          }
          .receipt-content .invoice-wrapper .payment-details {
            border-top: 2px solid #EBECEE;
            margin-top: 30px;
            padding-top: 20px;
            line-height: 22px; 
          }


          @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .payment-details .text-right {
            text-align: left;
            margin-top: 20px; } 
          }
          .receipt-content .invoice-wrapper .line-items {
            margin-top: 40px; 
          }
          .receipt-content .invoice-wrapper .line-items .headers {
            color: #A9B0BB;
            font-size: 13px;
            letter-spacing: .3px;
            border-bottom: 2px solid #EBECEE;
            padding-bottom: 4px; 
          }
          .receipt-content .invoice-wrapper .line-items .items {
            margin-top: 8px;
            border-bottom: 2px solid #EBECEE;
            padding-bottom: 8px; 
          }
          .receipt-content .invoice-wrapper .line-items .items .item {
            padding: 10px 0;
            color: #696969;
            font-size: 15px; 
          }
          @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .line-items .items .item {
            font-size: 13px; } 
          }
          .receipt-content .invoice-wrapper .line-items .items .item .amount {
            letter-spacing: 0.1px;
            color: #84868A;
            font-size: 16px;
           }
          @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .line-items .items .item .amount {
            font-size: 13px; } 
          }

          .receipt-content .invoice-wrapper .line-items .total {
            margin-top: 30px; 
          }

          .receipt-content .invoice-wrapper .line-items .total .extra-notes {
            float: left;
            width: 40%;
            text-align: left;
            font-size: 13px;
            color: #7A7A7A;
            line-height: 20px; 
          }

          @media (max-width: 767px) {
            .receipt-content .invoice-wrapper .line-items .total .extra-notes {
            width: 100%;
            margin-bottom: 30px;
            float: none; } 
          }

          .receipt-content .invoice-wrapper .line-items .total .extra-notes strong {
            display: block;
            margin-bottom: 5px;
            color: #454545; 
          }

          .receipt-content .invoice-wrapper .line-items .total .field {
            margin-bottom: 7px;
            font-size: 14px;
            color: #555; 
          }

          .receipt-content .invoice-wrapper .line-items .total .field.grand-total {
            margin-top: 10px;
            font-size: 16px;
            font-weight: 500; 
          }

          .receipt-content .invoice-wrapper .line-items .total .field.grand-total span {
            color: #20A720;
            font-size: 16px; 
          }

          .receipt-content .invoice-wrapper .line-items .total .field span {
            display: inline-block;
            margin-left: 20px;
            min-width: 85px;
            color: #84868A;
            font-size: 15px; 
          }

          .receipt-content .invoice-wrapper .line-items .print {
            margin-top: 50px;
            text-align: center; 
          }



          .receipt-content .invoice-wrapper .line-items .print a i {
            margin-right: 3px;
            font-size: 14px; 
          }

          .receipt-content .footer {
            margin-top: 40px;
            /*margin-bottom: 110px;*/
            text-align: center;
            font-size: 12px;
            color: #969CAD; 
          }

            .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
                border:0!important;
                padding:0!important;
                margin-left:-0.00001!important;
            }
        </style>    
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">          
                <div class="receipt-content">
                    <div class="container bootstrap snippets bootdey">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                <div class="invoice-wrapper">
                                    <div class="intro">
                                        Hi <strong>John McClane</strong>, 
                                        <br>
                                        This is the receipt for a payment of <strong>$312.00</strong> (USD) for your works
                                    </div>

                                    <div class="payment-info">
                                        <div class="row">
                                            <div style="width: 50%" class="col-sm-6 col-xs-6 col-ml-12 col-lg-12">
                                                <span>Payment No.</span>
                                                <strong>434334343</strong>
                                            </div>
                                            <div style="width: 50%" class="col-sm-6 col-xs-6 col-ml-12 col-lg-12 text-right">
                                                <span>Payment Date</span>
                                                <strong>Jul 09, 2014 - 12:20 pm</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="payment-details">
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6 ">
                                                <span>Client</span>
                                                <strong>
                                                    Andres felipe posada
                                                </strong>
                                                <p>
                                                    989 5th Avenue <br>
                                                    City of monterrey <br>
                                                    55839 <br>
                                                    USA <br>
                                                    <a href="#">
                                                        jonnydeff@gmail.com
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="col-sm-6 col-xs-6  text-right">
                                                <span>Payment To</span>
                                                <strong>
                                                    Juan fernando arias
                                                </strong>
                                                <p>
                                                    344 9th Avenue <br>
                                                    San Francisco <br>
                                                    99383 <br>
                                                    USA <br>
                                                    <a href="#">
                                                        juanfer@gmail.com
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="line-items">
                                        <div class="headers clearfix">
                                            <div class="row">
                                                <div class="col-xs-4">Description</div>
                                                <div class="col-xs-3">Quantity</div>
                                                <div class="col-xs-5 text-right">Amount</div>
                                            </div>
                                        </div>
                                        <div class="items">
                                            <div class="row item">
                                                <div class="col-xs-4 desc">
                                                    Html theme
                                                </div>
                                                <div class="col-xs-3 qty">
                                                    3
                                                </div>
                                                <div class="col-xs-5 amount text-right">
                                                    $60.00
                                                </div>
                                            </div>
                                            <div class="row item">
                                                <div class="col-xs-4 desc">
                                                    Bootstrap snippet
                                                </div>
                                                <div class="col-xs-3 qty">
                                                    1
                                                </div>
                                                <div class="col-xs-5 amount text-right">
                                                    $20.00
                                                </div>
                                            </div>
                                            <div class="row item">
                                                <div class="col-xs-4 desc">
                                                    Snippets on bootdey 
                                                </div>
                                                <div class="col-xs-3 qty">
                                                    2
                                                </div>
                                                <div class="col-xs-5 amount text-right">
                                                    $18.00
                                                </div>
                                            </div>
                                        </div>
                                        <div class="total text-right">
                                            <p class="extra-notes">
                                                <strong>Extra Notes</strong>
                                                Please send all items at the same time to shipping address by next week.
                                                Thanks a lot.
                                            </p>
                                            <div class="field">
                                                Subtotal <span>$379.00</span>
                                            </div>
                                            <div class="field">
                                                Shipping <span>$0.00</span>
                                            </div>
                                            <div class="field">
                                                Discount <span>4.5%</span>
                                            </div>
                                            <div class="field grand-total">
                                                Total <span>$312.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="footer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>         
            </div>
        </div>
    </body>           
</html>