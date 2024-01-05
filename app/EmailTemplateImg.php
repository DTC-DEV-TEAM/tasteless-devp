<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateImg extends Model
{
    protected $table = 'email_template_img';
    protected $fillable = [
        'header_id', 
        'file_name', 
        'ext',
        'created_by'
    ];

    public function scopeImages($query,$id){
        return $query->select(
            'email_template_img.*'
        )
        ->where('email_template_img.header_id', $id)
        ->get();
    }
}
