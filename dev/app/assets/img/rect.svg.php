<?php
header( 'Content-type: image/svg+xml' );
$time = time();
echo '<?xml version="1.0" encoding="utf-8"?>';
$width = 1920;
$height = 1080;
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
  x="0px" y="0px" width="<?= $width ?>px" height="<?= $height ?>px"
  viewBox="0 0 <?= $width ?> <?= $height ?>"
  enable-background="new 0 0 <?= $width ?> <?= $height ?>" xml:space="preserve">
  <defs>
    <filter id="blurFilter">
      <feGaussianBlur in="SourceGraphic" stdDeviation="3"/>
    </filter>
    <linearGradient id="fillGradient" gradientTransform="rotate(90)">
      <stop offset="5%"  stop-color="rgba(255,255,255,0.8)" />
      <stop offset="95%" stop-color="rgba(255,255,255,0.3)" />
    </linearGradient>
  </defs>
  <!--rect x="0" y="0" width="<?= $width ?>" height="<?= $height ?>" fill="green" stroke-width="2" /-->
  <?php for($i=0; $i<rand(5,8); $i++): ?>
    <circle cx="<?= rand(50,$width) ?>" cy="<?= rand(50,$height) ?>" r="<?= rand(100, 300) ?>"
      fill="url(#fillGradient)" filter="url(#blurFilter)"/>
  <?php endfor; ?>
</svg>