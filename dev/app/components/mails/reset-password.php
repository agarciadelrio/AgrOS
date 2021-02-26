<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recuperar acceso</title>
    <meta name="description" content="">
    <style>
      <?php require 'mail.css' ?>
    </style>
  </head>
  <body>
    <div class="text-center">
      <h1 class="my-5">AgrOS Recuperar acceso</h1>
      <img class="mb-5" src="<?= IMG_URL ?>/agros-logo.svg" />
      <p><?= _t('¿Has solicitado recuperar tu acceso a AgrOS?') ?></p>
      <p><?= _t('Por favor, sigue este enlace para cambiar la contraseña.') ?></p>
      <p><a href="<?= $url ?>"><?= _t('Recuperar acceso') ?></a></p>
    </div>
  </body>
</html>