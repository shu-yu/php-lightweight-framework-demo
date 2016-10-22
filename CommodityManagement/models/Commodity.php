<?php

namespace Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Commodity extends Eloquent {

    protected $table = 't_commodity';

    protected $fillable = array(
        'name',
        'stock',
    );

}
