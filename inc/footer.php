<style>
  footer {
    position: relative;
  }
  .selector-idioma {
    position: absolute;
    top: 45px;
    right: 10%;
  }
  @media (max-width: 767px) {
    .selector-idioma {
      position: static;
      text-align: center;
      margin-top: 10px;
    }
  }
</style>
<footer>
   <div class="selector-idioma">
    <i class="fa fa-globe text-info" aria-hidden="true"></i> &nbsp;
    <a href="?lang=es" class="text-warning" style="<?php echo $idioma == 'es' ? 'font-weight:bold' : ''; ?>">Español</a>
    <span class="text-info">|</span>
    <a href="?lang=en" class="text-warning" style="<?php echo $idioma == 'en' ? 'font-weight:bold' : ''; ?>">English</a>
  </div>
  <div class="container">
    <div class="row">
       <div class="col-sm-4">
        <h4 class="text-info"><?php echo t('nuestra_organizacion'); ?></h4>
        <a href="mision.php" class="text-warning"><i class="fa fa-bullseye" aria-hidden="true"></i> &nbsp; <?php echo t('mision'); ?></a><br>
        <a href="vision.php" class="text-warning"><i class="fa fa-eye" aria-hidden="true"></i> &nbsp; <?php echo t('vision'); ?></a><br>
      </div>
      <div class="col-sm-4">
        <h4 class="text-info"><?php echo t('contacto'); ?></h4>
        <p class="text-warning"><i class="fa fa-phone" aria-hidden="true"></i> &nbsp; 50424169</p>
        <a href="mailto:Cocode2023@gmail.com" class="text-warning"><i class="fa fa-envelope" aria-hidden="true"></i> &nbsp; Cocode2023@gmail.com</a>
      </div>
      <div class="col-sm-4">
        <h4 class="text-info"><?php echo t('terminos'); ?></h4>
        <a href="terminos.php" class="text-warning"><i class="fa fa-file-text" aria-hidden="true"></i> &nbsp; <?php echo t('ver'); ?></a><br>
      </div>
    </div>
  </div>
  <br>
  <p class="text-center text-warning">© 2023 VictorGonzalez. </p>
</footer>
