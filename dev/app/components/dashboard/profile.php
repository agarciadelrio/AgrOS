<?php

class Profile extends Model {
  static $form = [
    'name' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'lastname1' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'lastname2' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'email' => ['col'=>'md-4','type'=>'email','size'=>'sm', 'required'=>TRUE, ],
    'mobile' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'phone' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
  ];
  static $form2 = [
    'profile_photo_url' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'street' => ['col'=>'md-4','type'=>'text','size'=>'sm',],
    'street2' => ['col'=>'md-3','type'=>'text','size'=>'sm',],
    'zip' => ['col'=>'md-1','type'=>'text','size'=>'sm',],
    'state' => ['col'=>'md-2','type'=>'text','size'=>'sm',],
    'country_code' => ['col'=>'md-2','type'=>'text','size'=>'sm',],
  ];
}
