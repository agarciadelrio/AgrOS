<!DOCTYPE html>
<html class="minimal no-js" lang="<?= LANG ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'Index' ?> : <?= APP_NAME ?> <?= APP_VERSION ?></title>
    <meta name="description" content="<?= $description ?? 'Index' ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/app/assets/img/agros-icon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="/app/assets/img/agros-icon.png">
    <link rel="stylesheet" href="/styles.css">
    <?= View::headScripts() ?>
  </head>
  <body class="minimal">
    <?php if(isset($from_redirect_params)): ?>
      <div class="container mt-3">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <?= $from_redirect_params['msg'] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    <?php endif ?>
    <?= View::body() ?>
  </body>
</html>