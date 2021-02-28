<?php

Widget::register('icons', function($icon) {
  $icons = [
    'authorization' => 'key',
    'categories' => 'tags',
    'companies' => 'building',
    'company' => 'building',
    'contact' => 'user',
    'contacts' => 'users',
    'dashboard' => 'dashboard',
    'desktop' => 'desktop',
    'farm' => 'warehouse',
    'handler' => 'user-cog',
    'item' => 'archive',
    'logout' => 'sign-out-alt',
    'member' => 'user',
    'notebook' => 'book-open',
    'parcel' => 'vector-square',
    'permission' => 'user-check',
    'plot' => 'square',
    'product' => 'box',
    'products' => 'boxes',
    'profile' => 'address-card',
    'property' => 'home',
    'stuff' => 'archive',
    'task' => 'tasks',
    'taxonomy' => 'tag',
    'team' => 'users',
    'uom' => 'ruler',
    'uoms' => 'pencil-ruler',
    'user' => 'user',
    'users' => 'users-cog',
    'variety' => 'seedling',
    'cog' => 'cog'
  ];
  return $icons[$icon[0] ?? 'cog'] ?? 'cog';
});

Widget::register('fa', function($icon) {
  return "fa fa-fw fa-" . W::icons($icon[0]);
});