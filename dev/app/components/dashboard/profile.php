<?php

class Profile extends Model {
  static $input = [
    'name',
    'lastname1',
    'lastname2',
    'email',
    'mobile',
    'phone',
    'profile_photo_url',
    'street',
    'street2',
    'city',
    'zip',
    'state',
    'country_code',
  ];

  static $form = [
    'name' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'lastname1' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'lastname2' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'email' => ['col'=>'md-4','type'=>'email','size'=>'sm', 'required'=>TRUE, ],
    'mobile' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'phone' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'profile_photo_url' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
  ];
  static $form2 = [
    'street' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'street2' => ['col'=>'md-3','type'=>'text','size'=>'sm',],
    'city' => ['col'=>'md-3','type'=>'text','size'=>'sm',],
    'zip' => ['col'=>'md-1','type'=>'text','size'=>'sm',],
    'state' => ['col'=>'md-2','type'=>'text','size'=>'sm',],
    'country_code' => ['col'=>'md-2','type'=>'select','size'=>'sm',
      'options' => 'countriesList', 'columns'=> 'code,name', 'array' => COUNTRIES],
  ];
}
