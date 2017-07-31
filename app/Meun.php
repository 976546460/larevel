<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meun extends Model
{
    //
    protected $table = 'ds_meun';
    public  function  SelectList()
    {
       return $this->get();
    }
}
