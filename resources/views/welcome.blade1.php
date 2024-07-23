<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>DNC Management</title>
  <link href="//db.onlinewebfonts.com/c/1dc8ecd8056a5ea7aa7de1db42b5b639?family=Gilroy" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/menu.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('theme/default/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ asset('theme/plugins/switchery/switchery.min.css')}}">
  <script src="assets/js/modernizr.min.js"></script>
  <style type="text/css">
    .bttn {
      background-image: linear-gradient(to right, #7d702f 0%, #0a3a49 51%, #5f8374 100%);
      border: none;
      color: white;
      padding: 15px 100px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 30px;
    }

    .bttn:hover {
      background-image: linear-gradient(to right, #0a3a49 0%, #5f8374 51%, #7d702f 100%);
      /* change the direction of the change here */
      color: #fff;
    }


    body {
      /*margin: 0% 3% 0% 3%; ;*/
    }

    .back {
      margin: 20px;
      width: 75%;
      height: 40px;
      display: inline-block;
      color: #fff;
      border: 1px solid black;
      transform: skewX(-10deg);
    }

    p {
      color: #0a3a49;
      font-size: 22px;
      font-family: sans-serif;
    }

    .head {
      text-align: center;
      color: #fff;
      padding: 6%;
      font-size: 24px;
      font-weight: 700;
    }

    .body {
      color: #fff;
      font-size: 16px;
      line-height: 2;
      text-align: justify;
      padding-top: 4%;
    }

    li {
      margin-right: 5%;
      font-weight: 500;
      font-family: sans-serif;
    }

    li:before {
      content: "\";
      /* FontAwesome Unicode */
      font-family: FontAwesome;
      display: inline-block;
      margin-left: -1.3em;
      /* same as padding-left set on li */
      width: 1.3em;
      /* same as padding-left set on li */
    }

    ul {
      height: 600px;
      padding-bottom: 20px;
      list-style-type: none;

    }

    .fa-rotate-130 {
      -webkit-transform: rotate(130deg);
      -moz-transform: rotate(130deg);
      -ms-transform: rotate(130deg);
      -o-transform: rotate(130deg);
      transform: rotate(130deg);
    }

    .col-lg-1,
    .col-lg-10,
    .col-lg-11,
    .col-lg-12,
    .col-lg-2,
    .col-lg-3,
    .col-lg-4,
    .col-lg-5,
    .col-lg-6,
    .col-lg-7,
    .col-lg-8,
    .col-lg-9,
    .col-md-1,
    .col-md-10,
    .col-md-11,
    .col-md-12,
    .col-md-2,
    .col-md-3,
    .col-md-4,
    .col-md-5,
    .col-md-6,
    .col-md-7,
    .col-md-8,
    .col-md-9,
    .col-sm-1,
    .col-sm-10,
    .col-sm-11,
    .col-sm-12,
    .col-sm-2,
    .col-sm-3,
    .col-sm-4,
    .col-sm-5,
    .col-sm-6,
    .col-sm-7,
    .col-sm-8,
    .col-sm-9,
    .col-xs-1,
    .col-xs-10,
    .col-xs-11,
    .col-xs-12,
    .col-xs-2,
    .col-xs-3,
    .col-xs-4,
    .col-xs-5,
    .col-xs-6,
    .col-xs-7,
    .col-xs-8,
    .col-xs-9 {
      padding-left: 0px;
      padding-right: 0px;
    }


    .m-l-custom {
      /*padding-top: 30%; */
      margin-left: 20%;
    }

    .m-l-custom-login {
      /*padding-top: 12%;*/
      margin-left: 35%
    }

    .dot {
      position: absolute;
      top: 84.3%;
      left: 65%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: red;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }

    .dot-login {
      position: absolute;
      top: 82.25%;
      left: 87%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: red;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }

    .m-l-n-29 {
      margin-left: -6px !important;
      font-weight: 900;
    }

    .m-r-n-19 {
      margin-right: 0px !important;
      font-weight: 900;
    }

    .btn-desktop {
      border: #00AEEF !important;
      width: 160px !important;
    }


    .dot2 {
      position: absolute;
      top: 68%;
      left: 25.2%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: #a1a441;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }



    .dot5 {
      position: absolute;
      top: 80%;
      left: 63%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: #70ad9a;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }



    .dot4 {
      position: absolute;
      top: 75%;
      left: 50%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: #034a62;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }



    .dot3 {
      position: absolute;
      top: 80%;
      left: 40%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: #034a62;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }


    .dot6 {
      position: absolute;
      top: 73%;
      left: 74.8%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      height: 25px;
      width: 25px;
      background-color: #70ad9a;
      border: 5px solid #fff;
      border-radius: 50%;
      display: inline-block;
    }


    .box {
      width: 500px;
      height: 100px;
      border: solid 5px #000;
      border-color: #000 transparent transparent transparent;
      border-radius: 50%/100px 100px 0 0;
    }

    .box {
      width: 500px;
      height: 100px;
      border: solid 3px #000;
      border-color: transparent transparent #000 transparent;
      border-radius: 0 0 240px 50%/60px;
    }


    @media screen and (max-width: 600px) {
      .btn-desktop {
        border: #00AEEF !important;
        width: 162px !important;
        margin-left: -30px;
      }

      .m-l-n-29 {
        margin-left: 0px !important;
        font-weight: 900;
      }

      .m-r-n-19 {
        margin-right: 0px !important;
        font-weight: 900;
      }

      p {
        font-weight: 500;
        font-size: 18px;
      }

      li {
        font-size: 18px;
        font-weight: 500;
        color: white;
      }

      ul {
        height: 100%;
        padding-bottom: 19px;
      }

      h3 {
        font-weight: 500;
      }

      .m-l-custom {
        padding-top: 2%;
        margin-left: 30%
      }

      .m-l-custom-login {
        margin-left: 30%;
        padding-top: 5%;
        padding-bottom: 5%;
      }

      .dot {
        display: none;
      }

      .dot2 {
        display: none;
      }

      .dot3 {
        display: none;
      }

      .dot4 {
        display: none;
      }

      .dot5 {
        display: none;
      }

      .dot6 {
        display: none;
      }

      .dot-login {
        display: none;
      }

    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row" style="text-align: center;">
      <div class="col-sm-12 col-md-4 col-lg-4">
        <h3 class="back" style="padding-top: 8px;   background: #00aff0; border: #2EEEFC 1px;  border-radius: 20px 20px 0px 20px;"><i class="fa fa-arrows-h fa-rotate-130    "></i> Size Doesn’t Matter</h3>
        <p>No matter if you have one team or<br>
          thousands. DNC Blocker can remove<br>
          the need to have each individual team<br>
          handle the DNC list on their own. </p>
      </div>
      <div class="col-sm-12 col-md-4 col-lg-4"><img src="{{ asset('theme/default/assets/images/logo.png') }}" width="70%">
        <p style="color: #000; font-size: 20px">Block the DNC not the business</p>
      </div>
      <div class="col-sm-12 col-md-4 col-lg-4">
        <h3 class="back" style="padding-top: 8px;   background: #00aff0; border: #2EEEFC 1px; border-radius: 20px 20px 0px 20px;"><i class="fa fa-exclamation-triangle"></i> Reduce the Risk</h3>
        <p>Don’t get caught with your pants down.<br>
          Lawsuits are no laughing matter!</p>
      </div>
    </div>
    <!-- <img src="{{asset('theme/default/assets/images/preview.png')}}"> -->
    <div class="row">
      <div class="col-sm-12 col-md-3 col-lg-3">
        <div style="background: #8b8d38;" class="head">Whats The Problem?</div>
        <div style="background: #a1a441;" class="body">
          <ul>
            <li>The DNC Registry is growing
              Everyday. Federal DNC is
              over 640 million numbers</li>
            <li>Access to customers for your
              product or service is
              declining</li>
            <li>Risk of costly lawsuits by
              serial litigators is increasing
              due to DNC violations
            </li>
            <li>Exposure to risks for your
              business grows as your
              salesforce increases .
            </li>
            <div class="m-l-custom" style="margin-top: 40%">
              <a href="#" class="btn btn-primary btn-rounded btn-lg btn-desktop" style="background-color: #00AEEF !important;" data-toggle="modal" data-target="#con-close-modal"> <strong class="m-l-n-29" style="font-size: 20px;">Contact us <img src="{{ asset('theme/default/assets/images/dot.png') }}" style="margin-left: -7px !important"></strong></a>
              <!-- <div class="dot"></div> -->
            </div>
          </ul>
        </div>
      </div>
      <div class="col-sm-12 col-md-3 col-lg-3">
        <div style="background: #034055;" class="head">Whats the Solution?</div>
        <div style="background: #034a62;" class="body">
          <ul>
            <li>DNC Blocker is the safest and
              cost-effective solution to
              securing your telesales
              campaigns</li>
            <li>We have over 10 years in the
              telesales industry.
            </li>
            <li>We have the most accurate
              compiled DNC and Litigator
              lists in the industry.
            </li>
            <li>Designed to follow the DNC
              and TCPA rules and
              regulations.
            </li>
            <div class="m-l-custom" style="margin-top: 47%">
              <a href="#" class="btn btn-primary btn-rounded btn-lg btn-desktop" style="background-color: #00AEEF !important; display: none" data-toggle="modal" data-target="#con-close-modal"> <strong class="m-l-n-29" style="font-size: 20px;">Contact us <img src="{{ asset('theme/default/assets/images/dot.png') }}"></strong></a>
              <!-- <div class="dot"></div> -->
            </div>
          </ul>
        </div>
      </div>
      <div class="col-sm-12 col-md-3 col-lg-3">
        <div style="background: #619585;" class="head">How it Works?</div>
        <div style="background: #70ad9a;" class="body">
          <ul>
            <li>DNC Blocker plugs our DNC
              Source database directly into
              your teams dialers from our
              centralized remote location.
            </li>
            <li>We then block our DNC
              Sourced database from
              inbound and outbound
              contact to and from your
              teams dialers.
            </li>
            <div class="m-l-custom" style="margin-top: 76%">
              <a href="#" class="btn btn-primary btn-rounded btn-lg btn-desktop" style="background-color: #00AEEF !important; display: none" data-toggle="modal" data-target="#con-close-modal"> <strong class="m-l-n-29" style="font-size: 20px; ">Contact us <img src="{{ asset('theme/default/assets/images/dot.png') }}"></strong></a>
              <!-- <div class="dot"></div> -->
            </div>
          </ul>
        </div>
      </div>
      <div class="col-sm-12 col-md-3 col-lg-3">
        <div style="background: #3e8d75;" class="head">Why use DNC Blocker?</div>
        <div style="background: #48a388;" class="body">
          <ul>
            <li>
              Remove the risk for a higher
              chance of a mistakes and
              costly lawsuits to happen.
            </li>
            <li>The more teams you have
              dialing, the higher the risk of
              a mistake being made by
              dialing a DNC number
            </li>
            <li>Doesn’t it make sense to
              block all of your sales teams
              from one source that’s been
              compiled for over 10 years
              using DNC Blocker rather
              than having each team
              having the responsibility to
              do this themselves?
            </li>
            <div class="m-l-custom-login" style="margin-top: 21%">
              <a href="{{ route('login') }}" class="btn btn-primary btn-rounded btn-lg" style="background-color: #00AEEF !important; border: #00AEEF !important; width: 136px !important;"><strong class="m-r-n-19" style="font-size: 20px;"><img src="{{ asset('theme/default/assets/images/dot.png') }}"> Log in</strong></a>
            </div>
          </ul>
        </div>
      </div>
      <!-- <div class="dot2"></div>
<div class="dot3"></div>
<div class="dot4"></div>
<div class="dot5"></div>
<div class="dot6"></div> -->
    </div>
  </div>


  <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h2 class="modal-title" align="center">Contact Us</h2>
        </div>
        <div class="modal-body">
          <form method="post" action="{{ url('contact') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-5" style="margin-left: 2%">
                <div class="form-group">
                  <label for="field-1" class="control-label">First Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="field-1" placeholder="John" name="fname" required="">
                </div>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="field-2" class="control-label">Last name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="field-2" placeholder="Doe" name="lname" required="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5" style="margin-left: 2%">
                <div class="form-group">
                  <label for="field-1" class="control-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="field-1" placeholder="example@gmail.com" name="email" required="">
                </div>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="field-2" class="control-label">Phone <span class="text-danger">*</span></label>
                  <input type="text" class="form-control number" id="field-2" placeholder="1234567890" maxlength="10" minlength="10" required="" name="phone">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-11" style="margin-left: 2%">
                <div class="form-group no-margin">
                  <label for="field-7" class="control-label">Message <span class="text-danger">*</span></label>
                  <textarea class="form-control autogrow" name="message" placeholder="Message" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 104px;"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group no-margin">
                  <center><input type="submit" class="btn btn-primary btn-lg" style="width: 300px;" name="submit" value="Submit"></center>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="text-align: center;">
                <div class="form-group no-margin">
                  <h4 class="text-primary">Call Us on <i class="fa fa-phone"></i> <a href="tel:+1 123 456 1245">+1 123 456 1245</a></h4>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div><!-- /.modal -->


  <script src="{{ asset('theme/default/assets/js/jquery.min.js')}}"></script>
  <script src="{{ asset('theme/default/assets/js/bootstrap.min.js')}}"></script>
  <script>
    $('.number').keyup(function(e) {
      if (/\D/g.test(this.value)) {
        this.value = this.value.replace(/\D/g, '');
      }
    });
  </script>
</body>

</html>