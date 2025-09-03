<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <title>ITSM</title>

    <meta name="description" content>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link href="{{asset('css/vendor/fonts/boxicons.css')}}" rel="stylesheet">

    <!-- Core CSS -->
    <link class="template-customizer-core-css" href="{{asset('css/vendor/css/core.css')}}" rel="stylesheet">
    <link class="template-customizer-theme-css" href="{{asset('css/vendor/css/theme-default.css')}}" rel="stylesheet">
    
    <link href="{{asset('css/css/demo.css')}}" rel="stylesheet" /> 
    <!-- Vendors CSS -->
    <link href="{{asset('css/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet">

    <link href="{{asset('css/vendor/libs/apex-charts/apex-charts.css')}}" rel="stylesheet">

    <!-- Page CSS -->
 
    <!-- Helpers -->
     <script src="{{asset('css/vendor/js/helpers.js')}}"></script>
    <script src="{{asset('css/js/config.js')}}"></script>
    <style>
    .limited-text {
      max-width: 200px; /* ou 150px si vous voulez plus étroit */
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .table td {
    font-size: 13px; /* Adjust this value as needed */
    max-width: 200px; /* Limit width */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    }

    .table th {
      font-size: 14px;
    }
    th {
      color: #fff
    }
     .ai-form {
      background: #1e1e1e;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 15px rgba(0,255,200,0.2);
    }
    .result-section {
      display: none;
      background: #1b4459;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
      animation: fadeInUp 0.5s ease;
    }
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
  </style>

  </head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-page">
      <!-- Basic -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5" >
        <div class="container-fluid" style="background-color: #1b4459">
          <a class="navbar-brand" href="javascript:void(0)" style="color: #fff">ITSM Auditor</a>
          <button class="navbar-toggler" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" href="{{route('genarate_incident')}}" aria-current="page" style="color: #fff"><span class="tf-icons bx bx-refresh"></span>&nbsp;Sync Data from ServiceNow</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="{{route('index')}}" aria-current="page" style="color: #fff"> <span class="tf-icons bx bx-analyse"></span>&nbsp;Bulk Prediction</a>
              </li>
              <li class="nav-item" >
                <a class="nav-link" href="{{route('single_predict')}}" style="color: #fff"><span class="tf-icons bx bx-edit-alt"></span>&nbsp;Single Prediction</a>
              </li>
            </ul>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">Name</span>
                          <small class="text-muted">Admin</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-cog me-2"></i>
                      <span class="align-middle">Settings</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <span class="d-flex align-items-center align-middle">
                        <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                        <span class="flex-grow-1 align-middle">Billing</span>
                        <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                      </span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="auth-login-basic.html">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </div>
      </nav>
      <!--/ Basic -->


      <!-- Hoverable Table rows -->
      <div class="container-fluid" >
      @yield('content')
      </div>
      <!--/ Hoverable Table rows -->

      <!-- Basic footer -->
      <section id="basic-footer">

        <footer class="footer bg-light">
          <div class="container-fluid d-flex flex-md-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-3">
            <div>
              <a class="footer-text fw-bolder" href="" target="_blank">Skill center Servicenow Axian</a>
              ©
            </div>
            <div>
              <a class="footer-link me-4" href="" target="_blank">License</a>
              <a class="footer-link me-4" href="">Help</a>
              <a class="footer-link me-4" href="j">Contact</a>
              <a class="footer-link" href="">Terms &amp; Conditions</a>
            </div>
          </div>
        </footer>
      </section>
      <!--/ Basic footer -->
    </div>
  </div>

  <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('css/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('css/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('css/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('css/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('css/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{asset('css/js/main.js')}}"></script>

    <!-- Page JS -->
    <script>
      document.getElementById("categoryDisplay").classList.add("d-none");
      document.getElementById("categoryEdit").classList.remove("d-none");

      function showResult(id) {
        document.querySelectorAll('.result-section').forEach(el => el.style.display = 'none');
        document.getElementById(id).style.display = 'block';
      }

      function toggleCategoryEdit() {
        const display = document.getElementById("categoryDisplay");
        const edit = document.getElementById("categoryEdit");

        if (!display || !edit) {
          console.error("Missing elements with IDs categoryDisplay or categoryEdit");
          return;
        }

        display.classList.toggle("d-none");
      }

      function toggleTicketTypeEdit() {
        const display = document.getElementById("ticketTypeSelect");
        if (!display) {
          console.error("Missing elements with IDs ticketTypeSelect or categoryEdit");
          return;
        }
        display.classList.toggle("d-none");
      }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>