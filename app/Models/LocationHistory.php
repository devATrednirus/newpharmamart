<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    //
    protected $casts = [
        'old_locations' => 'array',
        'new_locations' => 'array',
    ];


    public function updatedby()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
