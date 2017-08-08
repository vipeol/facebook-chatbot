<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postback extends Model
{
    protected $fillable = ['value'];

    public function messages ()
    {
        return $this->hasMany('App\Message');
    }
}
