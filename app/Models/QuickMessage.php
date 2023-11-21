<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Models;

use App\Models\Traits\ConversationTrait;
use App\Observer\QuickMessageObserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Larapen\Admin\app\Models\Crud;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuickMessage extends BaseModel
{
	use Crud, Notifiable, ConversationTrait, SoftDeletes;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'quick_messages';

     protected $hidden = [
        'deleted_at'
      
    ];
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'category_id',
		'parent_id',
		'from_user_id',
		'from_name',
		'from_email',
		'from_phone',
		'to_user_id',
		'to_name',
		'to_email',
		'to_phone',
		'subject',
		'message',
		'filename',
		'is_read',
        'location',
        'address',
        'city' ,
        'pincode' ,
        'drugs_license',
        'have_gst_number'   ,
        'minimum_investment'   ,
        'purchase_period'   ,
        'call_back_time'   ,
        'profession',
        'is_sent',
        'message_id',
        'looking_for'
	];
    
    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
	
		 
    }
	
	public function routeNotificationForMail()
	{
		return 'sales@rednirus.in';
	}
	

    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function shared()
    {
        // Get the Conversation's latest Message
        return $this->hasMany(Message::class, 'quick_message_id')->orderBy('id','asc');
    }
     
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getSharedCounteHtml(){


        $out = $this->shared()->count();

        return $out;
    }

    public function getIsPostedHtml(){

        if($this->drugs_license==null){
            $url = admin_url('queries/' . $this->getKey() . '/edit');
           
            
            $out =  '<a href="' . $url . '"">edit</a>';
        }
        else{
            $out = "Yes";
        }
          

        return $out;
    }

    
    public function getUserNameHtml()
    {
        /*if (isset($this->user) and !empty($this->user)) {
            $url = admin_url('users/' . $this->user->getKey() . '/edit');
            $tooltip = ' data-toggle="tooltip" title="' . $this->user->name . '"';
            
            return '<a href="' . $url . '"' . $tooltip . '>' . $this->contact_name . '</a>';
        } else {
            return $this->contact_name;
        }*/
        if (isset($this->user)){
            if($this->user->user_type_id=="1"){

                return $this->user->name;    
            }
            else{

                if($this->user->first_name){
                    return $this->user->first_name.($this->user->last_name?" ".$this->user->last_name:"");
                }
                else{

                    return $this->user->phone;
                }
                
            }
        }
        else{

            return '-' ;
        }
        
    }
    
    public function getCityHtml()
    {
        if (isset($this->city) and !empty($this->city)) {
            return $this->city->name;
            /*if (config('settings.seo.multi_countries_urls')) {
                $uri = trans('routes.v-search-city', [
                    'countryCode' => strtolower($this->city->country_code),
                    'city'        => slugify($this->city->name),
                    'id'          => $this->city->id,
                ]);
            } else {
                $uri = trans('routes.v-search-city', [
                    'city' => slugify($this->city->name),
                    'id'   => $this->city->id,
                ]);
            }
            
            return '<a href="' . localUrl($this->city->country_code, $uri) . '" target="_blank">' . $this->city->name . '</a>';*/
        } else {
            return '-';
        }
    }

    public function getCategoryHtml()
    {
        if (isset($this->category) and !empty($this->category)) {
            return $this->category->name;
            /*if (config('settings.seo.multi_countries_urls')) {
                $uri = trans('routes.v-search-city', [
                    'countryCode' => strtolower($this->city->country_code),
                    'city'        => slugify($this->city->name),
                    'id'          => $this->city->id,
                ]);
            } else {
                $uri = trans('routes.v-search-city', [
                    'city' => slugify($this->city->name),
                    'id'   => $this->city->id,
                ]);
            }
            
            return '<a href="' . localUrl($this->city->country_code, $uri) . '" target="_blank">' . $this->city->name . '</a>';*/
        } else {
            return '-';
        }
    }
    public function getSearchesHtml()
    {

         $url = admin_url('searches/?session_id=' . $this->session_id );
         return '<a href="' . $url . '" target="_blank">' . $this->session_id . '</a>'; 

    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
   
}
