<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileApp extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table='mobile_apps';
    protected $dates=['last_check'];
    protected $dateFormat='Y-m-d H:i:s';

    function contacts(){
        return $this->hasMany(AppContact::class,'mobile_app_id');
    }

}
