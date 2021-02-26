<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User registered OK</title>
    <meta name="description" content="">
    <style>
      <?php require 'mail.css' ?>
    </style>
  </head>
  <body>
    <div class="text-center">
      <h1 class="my-5">AgrOS registro de usuario</h1>
      <img class="mb-5" src="<?= IMG_URL ?>/agros-logo.svg" />
      <p><?= _t('Tu cuenta de AgrOS ha sido creada correctamente.') ?></p>
      <p><?= _t('Por favor, sigue este enlace para activarla.') ?></p>
      <p><a href="<?= $url ?>"><?= _t('Activa tu cuenta') ?></a></p>
    </div>
  </body>
</html>