<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class profile extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile';

    /**
     * Define the relationship to the user table.
     */
    public function User(){
        
        return $this->belongsTo('\App\User');
    }
}
