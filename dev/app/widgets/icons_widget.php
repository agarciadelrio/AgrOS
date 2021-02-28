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
  ];
  return $icons[$icon[0] ?? 'cog'] ?? 'cog';
});

Widget::register('fa', function($icon) {
  return "fa fa-fw fa-" . W::icons($icon[0]);
});