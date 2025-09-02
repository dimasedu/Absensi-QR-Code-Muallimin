 <?= $this->extend('templates/starting_page_layout'); ?>

 <?= $this->section('navaction') ?>
 <a href="<?= base_url('/'); ?>" class="btn btn-primary pull-right pl-3">
    <i class="material-icons mr-2">qr_code</i>
    Scan QR
 </a>
 <?= $this->endSection() ?>

 <?= $this->section('content'); ?>
 <style>
body {background:#1572e8!important;}
.navbar .navbar-brand {color: #fff;}
 </style>
 <div class="main-panel" id="login-page">
    <div class="content">
       <div class="container-fluid">
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Login petugas</h4>
               <p class="card-category">Silahkan masukkan username dan password anda</p>
            </div>

            <div class="card-body">
               <?= view('\App\Views\admin\_message_block') ?>
               <form action="<?= url_to('login') ?>" method="post">
                  <?= csrf_field() ?>
                  <div class="row">
                     <div class="col-md-12">
                        <?php if ($config->validFields === ['email']) : ?>
                           <div class="form-group">
                              <label class="bmd-label-floating"><?= lang('Auth.email') ?></label>
                              <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" autofocus>
                              <div class="invalid-feedback">
                                 <?= session('errors.login') ?>
                              </div>
                           </div>
                        <?php else : ?>
                           <div class="form-group">
                              <label class="bmd-label-floating"><?= lang('Auth.emailOrUsername') ?></label>
                              <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" name="login" autofocus>
                              <div class="invalid-feedback">
                                 <?= session('errors.login') ?>
                              </div>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
                  <div class="row mt-0">
                     <div class="col-md-12">
                        <div class="form-group">
                           <label class="bmd-label-floating">Password</label>
                           <input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>">
                           <div class="invalid-feedback">
                              <?= session('errors.password') ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- <button type="submit" class="btn btn-primary col-md-12">Login</button> -->
                  <?php if ($config->allowRemembering) : ?>
                     <div class="form-check">
                        <label class="form-check-label">
                           <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
                           <?= lang('Auth.rememberMe') ?>
                        </label>
                     </div>
                  <?php endif; ?>

                  <br>

                  <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.loginAction') ?></button>
                  <a href="<?= url_to('register') ?>" class="btn btn-info btn-block">Pendaftaran</a>
                  <a href="<?= url_to('izin') ?>" class="btn btn-danger btn-block">Form Izin / Sakit</a>

                  <?php if ($config->activeResetter) : ?>
                     <p><a href="<?= url_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a></p>
                  <?php endif; ?>
                  <div class="clearfix"></div>
               </form>
            </div>
         </div>
      </div>
    </div>
 </div>

 <script>
   function kirimwa(){
      $(function(){
         $.ajax({
            url : '<?=url_to('kirimwa')?>',
            type :'GET',
            success : function(result){
               console.log(result);
            }
         })
      });
   }

   
   // function kirimbroadcast(){
   //    $(function(){
   //       $.ajax({
   //          url : '<?=url_to('kirimbroadcast')?>',
   //          type :'GET',
   //          success : function(result){
   //             console.log(result);
   //          }
   //       })
   //    });
   // }

   function kirimulangwa(){
      $(function(){
         $.ajax({
            url : '<?=url_to('kirimlagi')?>',
            type :'GET',
            success : function(result){
               console.log(result);
            }
         })
      });
   }


   setInterval(function() {
      kirimwa(),
      kirimulangwa()
   }, 10000);


 </script>
 <?= $this->endSection(); ?>