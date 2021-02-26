<?php

class Task extends Model {
  static $form = [
    # Cuándo
    'date' => ['col'=>'md-3','type'=>'date','size'=>'sm',],
    'time' => ['col'=>'md-2','type'=>'time','size'=>'sm',],
    # Dónde
    'company_id' => ['col'=>'md-7','type'=>'select','size'=>'sm',
      'options' => 'companiesList', 'columns'=>'id,name', 'caption'=>'Seleccione Empresa'],
    'farm_id' => ['col'=>'md-3','type'=>'select','size'=>'sm',
      'options' => 'farmsList', 'columns'=>'id,name', 'caption'=>'Seleccione Granja'],
    'parcel_id' => ['col'=>'md-3','type'=>'select','size'=>'sm',
      'options' => 'parcelsList', 'columns'=>'id,name', 'caption'=>'Seleccione Parcela'],
    'plot_id' => ['col'=>'md-6','type'=>'select','size'=>'sm',
      'options' => 'plotsList', 'columns'=>'id,name', 'caption'=>'Seleccione Marco'],
    # Quién
    'user' => ['col'=>'md-2','size'=>'sm','disabled'=>True,],
    'team_id' => ['col'=>'md-2','type'=>'select','size'=>'sm',
      'options' => 'teamsList', 'columns'=>'id,name', 'caption'=>'Seleccione Equipo'],
    'member_id' => ['col'=>'md-2','type'=>'select','size'=>'sm',
      'options' => 'membersList', 'columns'=>'id,name', 'caption'=>'Seleccione Miembro'],
    'contact' => ['col'=>'md-6','type'=>'datalist','size'=>'sm',],
    # Qué
    'move_type' => ['col'=>'md-3','type'=>'select','size'=>'sm',
      'options'=>'moveTypeValues','columns'=>'id,text', 'caption'=>'Seleccione Tipo'],
    'category_id' => ['col'=>'md-3','type'=>'select','size'=>'sm',
      'options' => 'categoriesList', 'columns'=>'id,name', 'caption'=>'Seleccione Categoría'],
    'product_id' => ['col'=>'md-6','type'=>'select','size'=>'sm',
      'options' => 'productsList', 'columns'=>'id,name', 'caption'=>'Seleccione Producto'],
    'name' => ['col'=>'md-6','size'=>'sm',],
    'description' => ['col'=>'md-6','type'=>'textarea','size'=>'sm',],
    # Cuánto
    'uom' => ['col'=>'md-2','type'=>'select','size'=>'sm',],
    'quantity' => ['col'=>'md-2', 'type'=>'number','size'=>'sm',],
    'price' => ['col'=>'md-2', 'type'=>'number','size'=>'sm',],
    'taxes' => ['col'=>'md-6', 'type'=>'select2','size'=>'sm',],
    # Cómo
    'notes' => ['col'=>'md-12','type'=>'textarea','size'=>'sm',],
  ];
}