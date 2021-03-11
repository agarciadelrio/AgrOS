<?php

define('ICONS', [
  'authorization' => 'key',
  'categories' => 'tags',
  'companies' => 'building',
  'company' => 'building',
  'contact' => 'user',
  'contacts' => 'users',
  'dashboard' => 'dashboard',
  'desktop' => 'desktop',
  'farm' => 'warehouse',
  'farms' => 'warehouse',
  'handler' => 'user-cog',
  'item' => 'archive',
  'logout' => 'sign-out-alt',
  'member' => 'user',
  'notebook' => 'book-open',
  'parcel' => 'vector-square',
  'parcels' => 'th',
  'permission' => 'user-check',
  'plot' => 'square',
  'plots' => 'square-o',
  'product' => 'box',
  'products' => 'boxes',
  'profile' => 'address-card',
  'property' => 'home',
  'stuff' => 'archive',
  'task' => 'tasks',
  'tasks' => 'tasks',
  'taxonomy' => 'tag',
  'team' => 'users',
  'uom' => 'ruler',
  'uoms' => 'pencil-ruler',
  'user' => 'user',
  'users' => 'users-cog',
  'variety' => 'seedling',
  'cog' => 'cog'
]);

Widget::register('icons', function($icon) {
  return ICONS[$icon[0] ?? 'cog'] ?? $icon[0];
});

Widget::register('fa', function($icon) {
  return "fa fa-fw fa-" . W::icons($icon[0]) ?? $icon[0];
});