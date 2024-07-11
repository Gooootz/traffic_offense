<?php require_once ('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once ('inc/header.php'); ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $_settings->info('name'); ?> - Login</title>
  <link rel="icon" type="image/png" href="images/icons/favicon.ico">
  <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
  <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
  <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
  <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
  <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
  <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" type="text/css" href="util.css">
  <link rel="stylesheet" type="text/css" href="main.css">
  <style>
    body {
      background-image: url('<?php echo validate_image($_settings->info('cover')); ?>');
      background-size: cover;
      background-repeat: no-repeat;
    }

    a:hover span {
      text-shadow: 0 0 10px gray, 0 0 20px white, 0 0 30px white;
      /* Apply text-shadow on hover */
    }
  </style>
</head>

<body class="hold-transition login-page">
  <script>
    start_loader();
  </script>
  <h1 class="text-center pb-4 mb-4 text-light" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 1);">
    <?php echo $_settings->info('name'); ?>
  </h1>
  <div>
    <div class="wrap-login100 p-b-160 p-t-50">
      <form class="login100-form validate-form" id="login-frm" action="" method="post">
        <div class="wrap-input100 rs1 validate-input" data-validate="Username is required">
          <input type="text" class="input100" name="username">
          <span class="label-input100">Username</span>
        </div>
        <div class="wrap-input100 rs2 validate-input" data-validate="Password is required">
          <input type="password" class="input100" name="password">
          <span class="label-input100">Password</span>
        </div>
        <div class="container-login100-form-btn">
          <button type="submit" class="login100-form-btn" style="color:aliceblue">Sign
            In</button>
        </div>
        <div class="text-center w-full p-t-23">
          <a href="<?php echo base_url; ?>" style="font-family: arial; color:white; text-decoration: none;">
            <!-- Removed text-shadow -->
            <span style="transition: text-shadow 0.3s;">Go to Portal</span> <!-- Wrapped text in a span -->
          </a>
        </div>
      </form>
    </div>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script>
    $(document).ready(function () {
      end_loader();
    });
  </script>
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
  <script src="vendor/animsition/js/animsition.min.js"></script>
  <script src="vendor/bootstrap/js/popper.js"></script>
  <script src="vendor/select2/select2.min.js"></script>
  <script src="vendor/daterangepicker/moment.min.js"></script>
  <script src="vendor/daterangepicker/daterangepicker.js"></script>
  <script src="vendor/countdowntime/countdowntime.js"></script>
  <script src="js/main.js"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'UA-23581568-13');
  </script>

  <script>
    $(document).ready(function () {
      $('.input100').on('blur', function () {
        if ($(this).val().trim() !== "") {
          $(this).addClass('has-val');
        } else {
          $(this).removeClass('has-val');
        }
      });
    });
  </script>


  <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v84a3a4012de94ce1a686ba8c167c359c1696973893317"
    integrity="sha512-euoFGowhlaLqXsPWQ48qSkBSCFs3DPRyiwVu3FjR96cMPx+Fr+gpWRhIafcHwqwCqWS42RZhIudOvEI+Ckf6MA=="
    data-cf-beacon="{&quot;rayId&quot;:&quot;8667e0c3bef681a7&quot;,&quot;version&quot;:&quot;2024.2.4&quot;,&quot;token&quot;:&quot;cd0b4b3a733644fc843ef0b185f98241&quot;}"
    crossorigin="anonymous"></script>
</body>

</html>