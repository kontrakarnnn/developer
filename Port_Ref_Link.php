<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Port_Ref_Link extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'port_ref_link';


    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}
