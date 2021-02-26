<!DOCTYPE html>
<html class="no-js" lang="<?= LANG ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?? 'Index' ?> : <?= APP_NAME ?> <?= APP_VERSION ?></title>
    <meta name="description" content="<?= $description ?? 'Index' ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/app/assets/img/agros-icon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="/app/assets/img/agros-icon.png">
    <link rel="stylesheet" href="/styles.css">
    <script src="/index.js"></script>
    <?= View::headScripts() ?>
  </head>
  <body class="application <?= View::bodyClassTheme() ?>">
    <div class="<?= View::bodyWrapperClass() ?>">
      <?= W::main_menu() ?>
      <?php /*
      <nav class="navbar navbar-expand-lg main-menu">
        <a class="navbar-brand mr-md-2" href="/dashboard">
          <img src="/app/assets/img/agros-logo.svg" width="30" height="30" style="vertical-align: bottom;"/>
          AgrOS 1.0
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
          <div class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php if(isset($user)): ?>
            <?= renderMenu($user->menus(), TRUE) ?>
            <?php endif;?>
          </div>
          <!--form action="#" class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="<?= _t('Search') ?>" aria-label="<?= _t('Search') ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?= _t('Search') ?></button>
          </form-->
          <div class="navbar-nav mt-2 mt-lg-0 ml-2">
            <?php if(isset($user) && $user->ownPropertyList): ?>
            <select name="default-property" id="defaultPropertySelector" class="custom-select mx-2">
              <?php foreach($user->ownPropertyList as $property): ?>
                <!--option value="<?= $property->id ?>"><?= $property->name ?></option-->
                <option value="<?= $property->id ?>" <?= $property->id==$user->property_id ? 'selected':'' ?>><?= $property->name ?></option>
              <?php endforeach; ?>
            </select>
            <?php endif ?>
            <?= renderMenu(MENUS['app'], TRUE) ?>
          </div>
        </div>
      </nav>
      */ ?>
      <?= View::header() ?>
      <?= View::aside() ?>
      <?php if(View::exists('notification')): ?>
        <div class="container mt-3">
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= View::notification() ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
      <?php endif ?>
      <?php if(isset($from_redirect_params)): ?>
        <div class="container mt-3">
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $from_redirect_params['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      <?php endif ?>
      <?= View::body() ?>
    </div>
    <?= View::footer() ?>
    <?= View::footSripts() ?>
  </body>
</html>