<?php
/**
 * @param mixed $route
 *
 * @return [type]
 */
function isActive($route, $class='active') {
  if(Router::$route == $route) return $class;
}

/**
 * @param mixed $route
 * @param mixed $icon=False
 *
 * @return [type]
 */
function menuItem($route, $icon=False, $extraclass='', $active='active') {
  if($icon) {
    return "<a class=\"text-nowrap nav-link $extraclass " . isActive("/$route",$active) . "\" href=\"/$route\">"
      . '<i class="' . W::fa($icon) . '"></i> '
      . _t($route) . "</a>";
  } else {
    return "<a class=\"nav-link " . isActive("/$route",$active) . "\" href=\"/$route\">" . _t($route) . "</a>";
  }
}

Widget::register('main_menu', function($p1) {
?>
  <nav class="navbar navbar-agros navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand mr-md-3" href="/dashboard">
          <img src="/app/assets/img/agros-logo.svg" width="30" height="30" style="vertical-align: bottom;"/>
          AgrOS 1.0
        </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <?= menuItem('dashboard') ?>
          </li>
          <li class="nav-item">
            <?php if($_SESSION['user'] && ($_SESSION['user']['admin']||$_SESSION['user']['owner'])): ?>
              <?= menuItem('companies') ?>
            <? else: ?>
              <?= menuItem('plots') ?>
            <?php endif ?>
          </li>
          <?php if($_SESSION['user'] && ($_SESSION['user']['admin']||$_SESSION['user']['owner'])): ?>
          <li class="nav-item">
            <?= menuItem('categories') ?>
          </li>
          <?php endif ?>
          <li class="nav-item">
            <?= menuItem('contacts') ?>
          </li>
          <?php if($_SESSION['user'] && $_SESSION['user']['admin']): ?>
          <li class="nav-item">
            <?= menuItem('users') ?>
          </li>
          <?php endif ?>
          <?php /*
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
          </li>
          */ ?>
        </ul>
        <?php /*
        <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        */ ?>
        <div class="d-flex">

          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <?= menuItem('profile', 'profile') ?>
            </li>
            <li class="nav-item">
              <?= menuItem('logout','logout') ?>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

<?php });

Widget::register('category_menu', function($p1) {
?>
<div class="sticky-top">
  <nav class="nav flex-column navbar-dark bg-dark">
    <?= menuItem('categories', 'categories', 'link-secondary', 'link-light') ?>
    <?= menuItem('uoms', 'uoms', 'link-secondary', 'link-light') ?>
    <?= menuItem('products', 'products', 'link-secondary', 'link-light') ?>
  </nav>
</div>
<?php });

Widget::register('company_menu', function($p1) {
  ?>
  <div class="sticky-top">
    <nav class="nav flex-column navbar-dark bg-dark">
      <? if($_SESSION['user'] && ($_SESSION['user']['admin']||$_SESSION['user']['owner'])): ?>
      <?= menuItem('companies', 'companies', 'link-secondary', 'link-light') ?>
      <?= menuItem('farms', 'farms', 'link-secondary', 'link-light') ?>
      <?= menuItem('parcels', 'parcels', 'link-secondary', 'link-light') ?>
      <? endif ?>
      <?= menuItem('plots', 'plots', 'link-secondary', 'link-light') ?>
      <?= menuItem('tasks', 'tasks', 'link-secondary', 'link-light') ?>
    </nav>
  </div>
  <?php });