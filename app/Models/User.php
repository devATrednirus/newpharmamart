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

use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Traits\CountryTrait;
use App\Notifications\ResetPasswordNotification;
use App\Observer\UserObserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Jenssegers\Date\Date;
use Larapen\Admin\app\Models\Crud;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class User extends BaseUser
{
	use Crud, HasRoles, CountryTrait, HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';
    protected $appends = ['created_at_ta'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public $skip_package_update = false;

    protected $casts = [
        'params' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain',
        'template',
        'color',
        'created_by',
        'country_code',
		'language_code',
        'user_type_id',
        'gender_id',
        'name',
		'photo',
        'about',
        'phone',
        'phone_hidden',
        'email_hidden',
        'email',
        'username',
        'password',
        'remember_token',
        'is_admin',
		'can_be_impersonate',
        'disable_comments',
        'receive_newsletter',
        'receive_advice',
        'ip_addr',
        'provider',
        'provider_id',
		'email_token',
		'phone_token',
        'phone_token_created',
		'verified_email',
		'verified_phone',
        'blocked',
        'closed',
        'first_name',
        'last_name',
        'ceo_first_name',
        'ceo_last_name',
        'designation',
        'address1',
        'address2',
        'city_id',
        'pincode',
        'website',
        'establishment_year',
        'corporate_video',
        'corporate_video_title',
        'additional_contact_name',
        'business_type',
        'owner_type',
        'no_employees',
        'annual_turnover',
        'card_front',
        'card_back',
        'gstin',
        'pan_no',
        'tan_no',
        'cin_no',
        'dgft_no',
        'ifsc_code',
        'bank_name',
        'account_no',
        'email_to_send',
        'sms_to_send',
        'about_us',
        'why_us',
        'our_product',
        'account_type',
        'brochure',
        'package_id',
        'lat',
        'lon',
        'package_start_date',
        'package_end_date',
        'last_buy_lead',
        'buy_leads_alerts',
        'disable_sms_limit',
        'params'
    ];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_login_at', 'deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

		User::observe(UserObserver::class);

        // Don't apply the ActiveScope when:
        // - User forgot its Password
        // - User changes its Email or Phone
        if (
            !str_contains(Route::currentRouteAction(), 'Auth\ForgotPasswordController') &&
            !str_contains(Route::currentRouteAction(), 'Auth\ResetPasswordController') &&
            !session()->has('emailOrPhoneChanged') &&
			!str_contains(Route::currentRouteAction(), 'Impersonate\Controllers\ImpersonateController')
        ) {
            static::addGlobalScope(new VerifiedScope());
        }

		static::addGlobalScope(new LocalizedScope());
    }

    public function routeNotificationForMail()
    {



        if(isset($this->email_to_send)){
           return $this->email_to_send;
        }
        return $this->email;
    }

    public function routeNotificationForNexmo()
    {
		$phone = phoneFormatInt($this->phone, $this->country_code);
		$phone = setPhoneSign($phone, 'nexmo');

		return $phone;
    }

    public function routeNotificationForTwilio()
    {
        $phone = phoneFormatInt($this->phone, $this->country_code);
		$phone = setPhoneSign($phone, 'twilio');

        return $phone;
    }

    public function sendPasswordResetNotification($token)
    {
        if (request()->filled('email') || request()->filled('phone')) {
            if (request()->filled('email')) {
                $field = 'email';
            } else {
                $field = 'phone';
            }
        } else {
            if (!empty($this->email)) {
                $field = 'email';
            } else {
                $field = 'phone';
            }
        }

        try {
            $this->notify(new ResetPasswordNotification($this, $token, $field));
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }
    }

	/**
	 * @return bool
	 */
	public function canImpersonate()
	{
		// Cannot impersonate from Demo website,
		// Non admin users cannot impersonate
		if (isDemo() || !$this->can(Permission::getStaffPermissions())) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function canBeImpersonated()
	{
		// Cannot be impersonated from Demo website,
		// Admin users cannot be impersonated,
		// Users with the 'can_be_impersonated' attribute != 1 cannot be impersonated
		if (isDemo() || $this->can(Permission::getStaffPermissions()) || $this->can_be_impersonated != 1) {
			return false;
		}

		return true;
	}

	public function impersonateBtn($xPanel = false)
	{
		// Get all the User's attributes
		$user = self::findOrFail($this->getKey());

		// Get impersonate URL
		// $impersonateUrl = route('impersonate', $this->getKey());
		$impersonateUrl = localUrl($this->country_code, 'impersonate/take/' . $this->getKey(), false, false);

		// If the Domain Mapping plugin is installed,
		// Then, the impersonate feature need to be disabled
		if (config('plugins.domainmapping.installed')) {
			return null;
		}



		//dd(auth()->user());

		// Generate the impersonate link
		$out = '';
		if ($user->getKey() == auth()->user()->getAuthIdentifier()) {
			$tooltip = '" data-toggle="tooltip" title="' . t('Cannot impersonate yourself') . '"';
			$out .= '<a target="_private" class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa fa-btn fa-lock"></i></a>';
		} else if ($user->can(Permission::getStaffPermissions())) {
			$tooltip = '" data-toggle="tooltip" title="' . t('Cannot impersonate admin users') . '"';
			$out .= '<a  target="_private" class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa fa-btn fa-lock"></i></a>';
		} /*else if (!isVerifiedUser($user)) {
			$tooltip = '" data-toggle="tooltip" title="' . t('Cannot impersonate unactivated users') . '"';
			$out .= '<a class="btn btn-xs btn-warning" ' . $tooltip . '><i class="fa fa-btn fa-lock"></i></a>';
		} */else {
			$tooltip = '" data-toggle="tooltip" title="' . t('Impersonate this user') . '"';
			$out .= '<a  target="_private"  class="btn btn-xs btn-default" href="' . $impersonateUrl . '" ' . $tooltip . '><i class="fa fa-btn fa-sign-in"></i></a>';
		}

		return $out;
	}

    public function bannerBtn($xPanel = false)
    {
        $url = admin_url('company/' . $this->id.'/banners');

        $out = '';
        $out .= '<a href="' . $url . '" class="btn btn-xs btn-success" >';
        $out .= '<i class="fa fa-eye"></i> ';
        $out .= 'banners';
        $out .= '</a>';

        return $out;
    }

	public function deleteBtn($xPanel = false)
	{
		if (auth()->check()) {
			if ($this->id == auth()->user()->id) {
				return null;
			}
			if (isDemoDomain() && $this->id == 1) {
				return null;
			}
		}


        $url = admin_url('users/' . $this->id);

        $out = '';
        $out .= '<a href="' . $url . '" class="btn btn-xs btn-danger" data-button-type="delete">';
        $out .= '<i class="fa fa-trash"></i> ';
        $out .= trans('admin::messages.delete');
        $out .= '</a>';

		return $out;
	}

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function locationFilter()
    {
        return $this->belongsToMany(City::class, 'user_filter_locations','user_id', 'city_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id')->orderBy('id', 'DESC');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id', 'translation_of')->where('translation_lang', config('app.locale'));
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Post::class, 'user_id', 'post_id');
    }

    public function shared()
    {
        return $this->hasMany(Message::class, 'to_user_id')->where('blocked','0')->where(function($query){
            $query->where(function($query){
                $query->whereNotNull('message_id')->orWhereNotNull('quick_message_id');
            })->orWhere('include_in_share','1');
        });
    }

    public function direct()
    {
        return $this->hasMany(Message::class, 'to_user_id')->where('blocked','0')->where(function($query){
            $query->whereNull('message_id')->whereNull('quick_message_id')->where('include_in_share','0');
        });
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id');
    }

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_map_groups', 'user_id', 'group_id');
    }

    public function savedSearch()
    {
        return $this->hasMany(SavedSearch::class, 'user_id');
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }


    public function businessType()
    {
        return $this->belongsTo(BusinessType::class, 'business_type');
    }

    public function ownershipType()
    {
        return $this->belongsTo(OwnershipType::class, 'owner_type');
    }



    public function createdby()
    {
        return $this->belongsTo(Self::class, 'created_by');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function subscription()
    {
        return $this->hasOne(Payment::class, 'user_id')->where('payment_type','Subscription')->where('active','1');
    }

    public function locationHistory()
    {
        return $this->hasMany(LocationHistory::class, 'user_id')->orderBy('id', 'DESC');;
    }



    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
	public function scopeVerified($builder)
	{
		$builder->where(function($query) {
			$query->where('verified_email', 1)->where('verified_phone', 1);
		});

		return $builder;
	}

	public function scopeUnverified($builder)
	{
		$builder->where(function($query) {
			$query->where('verified_email', 0)->orWhere('verified_phone', 0);
		});

		return $builder;
	}

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getPackageHtml()
    {
        if (isset($this->package) and !empty($this->package)) {
            $url = admin_url('packages/' . $this->package->id . '/edit');
            $tooltip = ' data-toggle="tooltip" title="' . $this->package->name . '"';
            if($this->package->price>0 && $this->package_start_date && $this->package_end_date){
                $package_start_date = Date::parse($this->package_start_date);
                $package_start_date = $package_start_date->format('d-M-Y');

                $package_end_date = Date::parse($this->package_end_date);
                $package_end_date = $package_end_date->format('d-M-Y');
                return '<a href="' . $url . '"' . $tooltip . '>' . $this->package->name . '<br> '. $package_start_date. '<br> '. $package_end_date .' </a>';
            }
            else{

                return '<a href="' . $url . '"' . $tooltip . '>' . $this->package->name . ' </a>';

            }
        } else {
            return '';
        }
    }


    public function getCreatedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }
        // echo $value->format('l d F Y H:i:s').'<hr>'; exit();
        // echo $value->formatLocalized('%A %d %B %Y %H:%M').'<hr>'; exit(); // Multi-language

        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value;
    }

    public function getLastLoginAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value;
    }

    public function getDeletedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value;
    }

    public function getCreatedAtTaAttribute($value)
    {
        if (!isset($this->attributes['created_at']) and is_null($this->attributes['created_at'])) {
            return null;
        }

        $value = Date::parse($this->attributes['created_at']);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }
        $value = $value->ago();

        return $value;
    }

    public function getEmailAttribute($value)
    {
        if (
			isDemo() &&
			request()->segment(2) != 'password'
        ) {
            if (auth()->check()) {
                if (auth()->user()->id != 1) {
                    $value = hidePartOfEmail($value);
                }
            }

            return $value;
        }

		return $value;
    }

    public function getPhoneAttribute($value)
    {
        $countryCode = config('country.code');
        if (isset($this->country_code) && !empty($this->country_code)) {
            $countryCode = $this->country_code;
        }

        $value = phoneFormatInt($value, $countryCode);

        return $value;
    }

	public function getNameAttribute($value)
	{
		$value = mb_ucwords($value);

		return $value;
	}

    public static function getTurnOvers()
    {

        return ['Upto Rs. 50 Lakh','Rs. 50 Lakh - 1 Crore','Rs. 1 - 2 Crore','Rs. 2 - 5 Crore','Rs. 5 - 10 Crore','Rs. 10 - 25 Crore','Rs. 25 - 50 Crore','Rs. 50 - 100 Crore','Rs. 100 - 500 Crore','Rs. 500 - 1000 Crore','Rs. 1000 - 5000 Crore','Rs. 5000 - 10000 Crore','More than Rs. 10000 Crore' ];
    }

    public static function getNoOfEmployees()
    {

        return ['Upto 10 People','11 to 25 People','26 to 50 People','51 to 100 People','101 to 500 People','501 to 1000 People','1001 to 2000 People','2001 to 5000 People','More than 5000 People' ];
    }

    public function getPictureHtml()
    {
        // Get ad URL

         $style = ' style="width:auto; max-height:90px;"';
        $out = '<img src="' . resize(config('larapen.core.picture.default'), 'small') . '" data-toggle="tooltip" ' . $style . '>';

        if($this->photo){



         $out = '<img src="' . str_replace('storage','storage/app',resize($this->photo, 'small')) . '" data-toggle="tooltip"  ' . $style . '>';


        }
        return $out;
    }

    public function getCityHtml()
    {
        $out = '';

        if (isset($this->city)) {

            return $this->city->name.(($this->city->subAdmin1)?", ".$this->city->subAdmin1->name:"");
        }

        return $out;
    }

    public function getCreatedByHtml()
    {
        $out = '-';

        if (isset($this->createdby)) {

            return $this->createdby->name;
        }

        return $out;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
/*    public function setPhoneAttribute($value)
    {

        dd($value);

    }
    */
	public function setCreatedByAttribute($value)
    {


        $this->attributes['created_by'] = auth()->user()->id;


    }

    public function setPhotoAttribute($value)
	{
		$attribute_name = 'photo';

		// Path
		$destination_path = 'avatars/' . strtolower($this->country_code) . '/' . $this->id;

		// If the image was erased
		if (empty($value)) {
			// delete the image from disk
			Storage::delete($this->{$attribute_name});

			// set null in the database column
			$this->attributes[$attribute_name] = null;

			return false;
		}

		// Check the image file
		if ($value == url('/')) {
			$this->attributes[$attribute_name] = null;

			return false;
		}

		// If laravel request->file('filename') resource OR base64 was sent, store it in the db
		try {
			if (fileIsUploaded($value)) {
				// Remove all the current user's photos, by removing his photo directory.
				Storage::deleteDirectory($destination_path);

				// Get file extension
				$extension = getUploadedFileExtension($value);
				if (empty($extension)) {
					$extension = 'jpg';
				}

				// Image default sizes
				$width = (int)config('larapen.core.picture.size.width', 1000);
				$height = (int)config('larapen.core.picture.size.height', 1000);

				// Make the image
				if (exifExtIsEnabled()) {
					$image = Image::make($value)->orientate()->resize($width, $height, function ($constraint) {
						$constraint->aspectRatio();
					})->encode($extension, config('larapen.core.picture.quality', 100));
				} else {
					$image = Image::make($value)->resize($width, $height, function ($constraint) {
						$constraint->aspectRatio();
					})->encode($extension, config('larapen.core.picture.quality', 100));
				}

				// Generate a filename.
				$filename = md5($value . time()) . '.' . $extension;

				// Store the image on disk.
				Storage::put($destination_path . '/' . $filename, $image->stream());

				// Save the path to the database
				$this->attributes[$attribute_name] = $destination_path . '/' . $filename;
			} else {
				// Retrieve current value without upload a new file.
				if (starts_with($value, config('larapen.core.picture.default'))) {
					$value = null;
				} else {
					if (!starts_with($value, 'avatars/')) {
						$value = $destination_path . last(explode($destination_path, $value));
					}
				}
				$this->attributes[$attribute_name] = $value;
			}
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
			$this->attributes[$attribute_name] = null;

			return false;
		}
	}


    public function getApiKeyAttribute($value)
    {


        if(app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()!="1"){

            if($value){

                $mail_part = explode("@", $value);


                $mail_part[0] = substr($mail_part[0],0,4).str_repeat("*", (strlen($mail_part[0])-8)).substr($mail_part[0],-4);
                return implode("@", $mail_part);;

            }
        }

        return $value;
    }


    public function getFullNameAttribute($value)
    {


        $name = $this->first_name;

        if($this->last_name){
            $name.=" ".$this->last_name;
        }

        return $name;
    }



}
