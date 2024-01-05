<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTesting extends Model
{
    protected $table = 'email_testings';
    protected $guarded = [];

    public function scopeDatas($query,$id){
        return $query->select(
            'email_testings.*'
        )
        ->where('email_testings.id', $id)
        ->first();
    }
}
