<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1"
      crossorigin="anonymous"
    />
    <!-- Title & Favicon -->
    <title>DNC SCRUBBING</title>
    <!-- CSS Link -->
    <link rel="stylesheet" href="{{asset('webtheme/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('webtheme/css/responsive.css')}}" />
  </head>
  <body>
    <!-- ====================================== Start Header Section Here ====================================== -->
    <header id="header-section">
      <!-- Navbar For Mobile -->
      <div id="fullnav" class="fullnav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
          <img src="{{asset('webtheme/img/menu-1.svg')}}" alt="menu" />
        </a>
        <div class="fullnav-content text-center">
          <ul class="navbar-nav ms-auto text-center text-white">
            <li class="nav-item">
              <a
                class="nav-link active"
                aria-current="page"
                href="#header-section"
                >Home</a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link" href="javascript:void(0)">About</a>
            </li>
            <li class="nav-item">
              <a
                href="{{ route('login') }}"
                class="nav-link button nav-btn px-5 text-white"
                >Login</a
              >
            </li>
            <li class="nav-item">
              <a
                onclick="document.getElementById('id01').style.display='block'"
                href="javascript:void(0)"
                class="nav-link button nav-btn px-5 text-white"
                >Contact Us</a
              >
            </li>
          </ul>
        </div>
      </div>
      <!-- Navbar -->
      <nav class="navbar fixed-top navbar-expand-lg py-4 py-xxl-5 bg-white">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}"
            ><img src="{{asset('webtheme/img/logo.png')}}" alt="logo" class="img-fluid"
          /></a>
          <button
            style="background-color: transparent"
            onclick="openNav()"
            class="border-0 d-lg-none"
          >
            <img src="{{asset('webtheme/img/menu-2.svg')}}" alt="menu" />
          </button>
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a
                  class="nav-link active"
                  aria-current="page"
                  href="#header-section"
                  >Home</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)">About</a>
              </li>
              <li class="nav-item">
                <a
                  onclick="document.getElementById('id01').style.display='block'"
                  href="javascript:void(0)"
                  class="nav-link button nav-btn px-5 text-white"
                  >Contact Us</a
                >
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('login') }}"
                  class="nav-link button nav-btn px-5 text-white"
                  >Login</a
                >
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- Header Content -->
      <div class="container">
        <div class="header-content">
          <div class="row justify-content start">
            <div class="col-lg-6 col-xxl-5">
              <h1 class="poligon-bold text-white">
                DNC Scrubbing has never been more secure, accurate, and fast.
              </h1>
              <div class="get-started-box mt-5">
                <form action="{{ url('contact') }}" method="post">
                  @csrf
                  <div class="row g-4">
                    <div class="col-6">
                      <input
                        required
                        type="text"
                        class="w-100"
                        placeholder="First Name" name="fName"
                      />
                    </div>
                    <div class="col-6">
                      <input
                        required
                        type="text"
                        class="w-100"
                        placeholder="Last Name" name="lName"
                      />
                    </div>
                    <div class="col-sm-6">
                      <input
                        required
                        type="email"
                        class="w-100"
                        placeholder="Email" name="email"
                      />
                    </div>
                    <div class="col-sm-6">
                      <input
                        type="text"
                        class="w-100"
                        placeholder="Phone Number" name="phone"
                      />
                    </div>
                    <div class="col-12">
                      <textarea
                        required
                        style="height: 9rem"
                        name="message"
                        class="w-100 border-0 pb-2"
                        placeholder="Message"
                      ></textarea>
                    </div>
                    <div class="col-12 text-center text-sm-start">
                      <button class="button border-0">Contact me!</button>
                    </div>
                  </div>
                </form>
              </div>
          </div>
        </div>
      </div>
    </header>
    <!-- ====================================== End Header Section Here ====================================== -->

    <!-- ====================================== Start Do Not Call Section Here ====================================== -->
    <section id="do-not-call-section">
      <div class="container">
        <div class="row text-center text-lg-start gy-5">
          <div class="col-lg-4 align-self-center">
            <h2 class="heading">What does <span>Dnc Scrubbing</span> Accomplish?</h2>
          </div>
          <div class="col-lg-1 d-none d-lg-block"></div>
          <div class="col-lg-7 align-self-center">
            <p class="para-size">
            Dnc Scrubbing tool allows you to compare and scrub your files against existing donot call list with fast and accurate results. 
            </p>
          </div>
        </div>
      </div>
    </section>
    <!-- ====================================== End Do Not Call Section Here ====================================== -->

    <!-- ====================================== Start Purchase Section Here ====================================== -->
    <section id="purchase-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-10 col-lg-8 text-center">
            <h2 class="heading text-white">
              DNC Scrubbing doesn't have to break your budget. We have affordable subscription plans that can accommodate companies of all sizes.
            </h2>
          </div>
        </div>
      </div>
    </section>
    <!-- ====================================== End Purchase Section Here ====================================== -->

    <!-- ====================================== Start Info Section Here ====================================== -->
    <section id="info-section">
      <div class="container">
        <div class="row g-5 mt-5 pt-5">
          <div class="col-md-6">
            <div class="info-box h-100">
              <h3 class="info-heading">
                Where Do I Get <br class="d-none d-md-block" />
                DNC Lists?
              </h3>
              <p class="para-size para-color-122136 mt-4">
                The National Do Not Call Registry was created in 2003 to offer
                consumers the ability to limit telemarketing calls. After
                <a href="javascript:void(0)"><b>Registering</b></a> with the
                National DNC Registry, files
                <a href="javascript:void(0)"
                  ><span>you can download DNC</span></a
                >
                that can be used with our software to compare against your
                in-house lists to determine if you have any phone numbers that
                exist on the Do Not Call lists.
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-box h-100">
              <h3 class="info-heading">
                How much does it cost to <br class="d-none d-md-block" />
                access the registry?
              </h3>
              <p class="para-size para-color-122136 mt-4">
                Starting October 1, 2020,
                <a href="javascript:void(0)"
                  ><span class="color-006fff">the annual fee</span></a
                >
                will be $66 per area code of data up to a max fee of $18,044.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====================================== End Info Section Here ====================================== -->

    <!-- ====================================== Start How It Works Section Here ====================================== -->
    <section id="how-it-works-section">
      <div class="container">
        <div class="row justify-content-center pb-5">
          <div class="col-md-10 col-lg-8 text-center">
            <h2 class="heading">How it works</h2>
          </div>
        </div>
        <!-- How It Works Card -->
        <div class="how-it-works-card">
          <div class="row justify-content-center mt-5 pt-5">
            <div class="col-lg-10 col-xl-9">
              <div class="row g-5">
                <div class="col-md-6 col-lg-4">
                  <div class="how-it-works-card-box h-100">
                    <h3>Step 1</h3>
                    <p class="para-size para-color-485668 mt-4">
                      Choose a plan that works for your budget and register your account
                    </p>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4">
                  <div class="how-it-works-card-box h-100">
                    <h3>Step 2</h3>
                    <p class="para-size para-color-485668 mt-4">
                      Acquire your login credentials for user name and passwords  to access your back office
                    </p>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4">
                  <div class="how-it-works-card-box h-100">
                    <h3>Step 3</h3>
                    <p class="para-size para-color-485668 mt-4">
                      Select the options that you need in regards to your DNC scrubbing needs.
                    </p>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4">
                  <div class="how-it-works-card-box h-100">
                    <h3>Step 4</h3>
                    <p class="para-size para-color-485668 mt-4">
                     Upload the appropriate file needed to be scrubbed.
                    </p>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="how-it-works-card-box h-100">
                    <h3>Step 5</h3>
                    <p class="para-size para-color-485668 mt-4">
                      Click the submit button. after you click submit After that your file will automatically processed which you
                      which you see 2 files one being DNC and other being Non DNC.
                    </p>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="how-it-works-card-box h-100">
                    <h3>Step 6</h3>
                    <p class="para-size para-color-485668 mt-4">
                    Select the files that you would like to download by clicking on the selected files.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====================================== End How It Works  Here ====================================== -->
    
    <!-- ============================= Start footer Section Here ============================= -->
    <footer>
      <div class="container pt-5">
        <div class="footer-widget py-5">
          <div class="row text-center text-sm-start">
            <div class="col-sm-6 col-lg-4">
              <a href="{{ url('/') }}"
                ><img
                  src="{{asset('webtheme/img/footer-logo.png')}}"
                  alt="footer-logo"
                  class="img-fluid"
              /></a>
              <p class="para-size text-white py-5">
              DNC Scrubbing has never been more secure, accurate, and fast.
              </p>
            </div>
            <div class="d-none d-lg-block col-lg-4"></div>
          </div>
        </div>
        <div class="footer-copyright text-white text-start py-5">
          <div class="row">
            <div
              class="col-sm-6 col-md-7 text-center text-sm-start align-self-center">
              <p>Copyright & Design by @ <span style="color:#f0d;"><strong>DNCScrubbing</strong></span></p>
            </div>
            <div
              class="col-sm-6 col-md-5 text-center text-sm-end align-self-center">
              <a href="javascript:void(0)"
                class="me-5 text-white footer-border-end-default">Terms of use
              </a>
              <a href="javascript:void(0)" class="text-white">Privacy Policy</a>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- ============================= End Footer Section Here ============================= -->

    <!-- Pop Up Contact Form -->
    <div id="id01" class="modal">
      <form class="modal-content animate" action="{{ url('contact') }}" method="post">
      @csrf
        <div class="mt-0 pt-0 imgcontainer">
          <span
            onclick="document.getElementById('id01').style.display='none'"
            class="close"
            title="Close Modal"
            >&times;</span
          >
        </div>
        <div class="container-form">
          <h3>Contact Us</h3>
          <div class="row">
            <div class="col-sm-6 px-2 px-sm-4">
              <label for="fName">First Name</label> <br />
              <input required class="w-100" type="text" name="fName" />
            </div>
            <div class="col-sm-6 px-2 px-sm-4">
              <label for="lName">Last Name</label> <br />
              <input required class="w-100" type="text" name="lName" />
            </div>
            <div class="col-sm-6 px-2 px-sm-4">
              <label for="email">Email</label> <br />
              <input required class="w-100" type="email" name="email" />
            </div>
            <div class="col-sm-6 px-2 px-sm-4">
              <label for="phone">Phone</label> <br />
              <input required class="w-100" type="text" name="phone" />
            </div>
            <div class="col-12 px-2 px-sm-4">
              <label for="message">Message</label> <br />
              <input required class="w-100" type="text" name="message" />
            </div>
            <div class="col-12 text-center pb-4">
              <button>Submit</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Back To Top -->
    <button class="animation animation-delay-2" id="myBtn" title="Go to top">
      <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </button>

    <!-- JavaScript Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
      crossorigin="anonymous"
    ></script>
    <!-- Main JS -->
    <script src="{{asset('webtheme/js/main.js')}}"></script>
  </body>
</html>
