<!DOCTYPE HTML>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" href="/themes/default/images/icons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/themes/default/images/icons/favicon.svg" />
    <link rel="shortcut icon" href="/themes/default/images/icons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/themes/default/images/icons/apple-touch-icon.png" />
    <link rel="manifest" href="/themes/default/images/icons/site.webmanifest" />
  <meta name="theme-color" content="#ffffff">
  <title><?= _e(CLIENT_NAME) ?></title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <style>
    body {
    /* Zorg dat de body de volledige hoogte van het scherm gebruikt */
    margin: 0;
    padding: 0;
    min-height: 100vh;

    /* De achtergrondinstellingen */
    /* background-image: url('/dist/img/login_back.jpg'); */

    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center center;
    background-size: cover;
}
  </style>

  <script>
    if (window.top !== window.self) {
    window.top.location.replace(window.self.location.href);
    }
  </script>

</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="/"><img style="width:auto;margin-top:10px;margin-bottom:20px;" src="<?= getSiteImage('bh-logo.png') ?>" alt="<?= CLIENT_NAME ?>" /></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">

          <?php if ($bLoginEnabled && $bShowLoginAttemptsWarning) { ?>
        <div class="alert alert-danger errorColor " style="text-align: left; margin-bottom:10px;">Login mislukt : verkeerde logingegevens</b></div>
      <?php } ?>

      <strong>'t Blauwe Hert login</strong></p>


      <?php

      if (!$bDeactivation) {
        if ($bLoginEnabled) {
      ?>

          <form action="" method="post">
            <input type="hidden" value="send" name="login_form" />
            <?= CSRFSynchronizerToken::field() ?>
            <div class="input-group mb-3">
              <input type="text" name="username" id="username" class="form-control" placeholder="<?= sysTranslations::get('user_username') ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" id="password" class="form-control" placeholder="<?= sysTranslations::get('user_password') ?>">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <!--<div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember">
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>-->
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" value="Login" name="verzendBtn" class="btn btn-primary btn-block">Inloggen</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
        <?php

        } else {
        ?>
          <div class="alert alert-danger errorColor" style="text-align: left; margin-bottom:10px;">U
            heeft <?= AccessLogManager::max_login_attempts_account_lock . sysTranslations::get('login_attempts') . sysTranslations::get('user_account_is_blocked_part1') . AccessLogManager::account_locked_time . sysTranslations::get(
                    'user_account_is_blocked_part2'
                  ) ?>
          </div>
          <a href="<?= ADMIN_FOLDER ?>/login" class="btn btn-default"><?= sysTranslations::get('back_to_login') ?></a>
        <?php

        }
      } else {
        ?>
        <div class="alert alert-danger errorColor" style="text-align: left; margin-bottom:10px;"><?= sysTranslations::get('user_account_blocked') ?><br />
          <?php

          if (!empty($oUser->lockedReason)) {

            echo sysTranslations::get('locked_reason') . ' ' . _e($oUser->lockedReason) ?>
          <?php

          } ?>
        </div>
        <a href="<?= ADMIN_FOLDER ?>/login" class="btn btn-default"><?= sysTranslations::get('back_to_login') ?></a>
      <?php

      } ?>

      <!--
      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="/dist/js/adminlte.min.js"></script>
</body>

</html>




<?php

if (ENVIRONMENT != 'live') {
?>
  <div style="display: block; padding:10px;">&nbsp;</div>
  <div style="color: #FFF; font-size: 15pt; opacity: .5; z-index:9111; position: fixed; bottom: 0; right: 0; width: auto; background-color: #F00; ">
    <div style="padding: 5px 20px 5px 20px;"><?= ENVIRONMENT ?></div>
  </div>
<?php

}
?>
</body>

</html>