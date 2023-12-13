<?php

namespace App\models;

use App\Models\Scopes\ActiveScope;
use Larapen\Admin\app\Models\Crud;

class UserFilterLocation extends BaseModel
{
	use Crud;
	
    //

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
