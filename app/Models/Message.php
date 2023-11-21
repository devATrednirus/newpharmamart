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
use App\Observer\MessageObserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Larapen\Admin\app\Models\Crud;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends BaseModel
{
	use Crud, Notifiable, ConversationTrait, SoftDeletes;
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';
    
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
        'verified_status',
        'city_id',
    	'post_id',
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
        'looking_for',
        'session_id',
        'is_updated_by_admin',
        'sent_at',
        'category_id',
        'deleted_at',
        'email_sent',
        'limit_sent',
        'sending_log',
        'type',
        'company_only'
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
	
		Message::observe(MessageObserver::class);
    }
	
	public function routeNotificationForMail()
	{

        if($this->to_user_id=="1"){

            
            return 'sales@rednirus.in';
        }
        else{
		  
          return $this->to_email;

        }
       
	}
	
	public function routeNotificationForNexmo()
	{
		$phone = phoneFormatInt($this->to_phone, config('country.code'));
		$phone = setPhoneSign($phone, 'nexmo');
		
		return $phone;
	}
	
	public function routeNotificationForTwilio()
	{
        $phone = phoneFormatInt($this->to_phone, config('country.code'));
		$phone = setPhoneSign($phone, 'twilio');
        
        return $phone;
	}
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
	
	public function parent()
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

    public function mainQuery()
    {
        return $this->belongsTo(self::class, 'message_id');
    }
    
    public function latestReply()
	{
		// Get the Conversation's latest Message
		return $this->hasOne(self::class, 'parent_id')->latest('id');
	}

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }


    public function shared()
    {
        // Get the Conversation's latest Message
        return $this->hasMany(self::class, 'message_id')->where('type','<>','buy')->orderBy('id','asc');
    }

    public function buy()
    {
        // Get the Conversation's latest Message
        return $this->hasMany(self::class, 'message_id')->where('type','=','buy')->orderBy('id','asc');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
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

    public function getIsPostedHtml(){

        $url = admin_url('messages/' . $this->getKey() . '/edit');
        if($this->blocked=="1"){
            
            $out =  'Blocked | <a href="' . $url . '"">edit</a>';
        }
        else if($this->is_sent=="0" || $this->shared()->count()< 15){
            
           
            
            $out =  '<a href="' . $url . '"">edit</a>';
        }
        else{
            $out = "Yes";
        }
          

        return $out;
    }

    public function getFilenameFromOldPath()
    {
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }

        $value = $this->attributes['filename'];

        // Fix path
        $value = str_replace('uploads/resumes/', '', $value);
        $value = str_replace('resumes/', '', $value);
        $value = 'resumes/' . $value;

        if (!Storage::exists($value)) {
            return null;
        }

        $value = 'uploads/' . $value;

        return $value;
    }

    public function getSharedCounteHtml(){

        
        if($this->is_sent=="0"){
            $out = "-";
        }
        else{
            
            $out = "Shared:".$this->shared()->count()."<br>Purchased:".$this->buy()->count()."<br>Available for Buy: ".$this->shareable_count;

        }

        return $out;
    }

    public function isSubmittedHtml(){

        
        if($this->drugs_license==null){
            $out = "No";
        }
        else{
            
            $out = "Yes";

        }

        return $out;
    }
    public function getFilenameAttribute()
    {
        $value = $this->getFilenameFromOldPath();
        if (!empty($value)) {
            return $value;
        }

        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }

        $value = $this->attributes['filename'];
        $value = 'uploads/' . $value;

        return $value;
    }

    public function getReceiverHtml()
    {
 
        if (isset($this->receiver)){
            if($this->receiver->user_type_id=="1"){

                return $this->receiver->name;    
            }
            else{

                if($this->receiver->first_name){
                    return $this->receiver->first_name.($this->receiver->last_name?" ".$this->receiver->last_name:"");
                }
                else{

                    return $this->receiver->phone;
                }
                
            }
        }
        else{

            return '-' ;
        }
        
    }

    public function getSenderHtml()
    {
 
        if (isset($this->sender)){
            if($this->sender->user_type_id=="1"){

                return $this->sender->name;    
            }
            else{

                if($this->sender->first_name){
                    return $this->sender->first_name.($this->sender->last_name?" ".$this->sender->last_name:"");
                }
                else{

                    return $this->sender->phone;
                }
                
            }
        }
        else{

            return '-' ;
        }
        
    }

    public function getPostTitleHtml()
    {
        $out = '';
        
        if (isset($this->post)){


            $out .= "Post: " .getPostUrl($this->post);
           
        
        }
        else if (isset($this->receiver)){



            if($this->receiver->id=="1"){
                
                $out .= $this->receiver->name ;
                if($this->category){
                    $out = "category: ".$this->category->name;
                }

            }
            else{

                $out .= "Company: <a href='/".$this->receiver->username."' target='_blank'>".$this->receiver->name."</a>";
            }
           
        
        }
        
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function getFromEmailAttribute($value)
    {


          
        if(app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()!="1"){

            if($value){

                $mail_part = explode("@", $value);
                    

                $mail_part[0] = substr($mail_part[0],0,1).str_repeat("*", (strlen($mail_part[0])-2)).substr($mail_part[0],-1);
                return implode("@", $mail_part);;
            }
     
        }

        return $value;
    }

    public function getFromPhoneAttribute($value)
    {

        if(app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()!="1"){

            if($value){
                $value = substr($value,0,1).str_repeat("*", (strlen($value)-2)).substr($value,-1);
                return $value;
            }
        }

        return $value;
    }
    public function setFilenameAttribute($value)
    {
        $attribute_name = 'filename';
        $disk = config('filesystems.default');

        // Get ad details
        $post = Post::find($this->post_id);
        if (empty($post)) {
            $this->attributes[$attribute_name] = null;
            return false;
        }

        // Path
        $destination_path = 'files/' . strtolower($post->country_code) . '/' . $post->id . '/applications';

        // Upload
        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }

    public function getSearchesHtml()
    {

         $url = admin_url('searches/?session_id=' . $this->session_id );
         return '<a href="' . $url . '" target="_blank">' . $this->session_id . '</a>'; 

    }


    public function getVerifiedStatusHtml()
    {
        
        
        if ($this->verified_status) {
            return $this->verified_status;
        }
        else if ($this->sender && $this->sender->phone && $this->sender->verified_phone == 1) {
            return 'By OTP';
        } 
        else{

            return '-';

        } 
    }
    
    public function getVerifiedPhoneHtml()
    {

        if (!isset($this->sender->verified_phone) || $this->sender->phone==null) return '-';
        
  
     
        if ($this->sender->verified_phone != 1) {
            return 'No';
        }
        else{

            return 'Yes';
        }  
    }
}
