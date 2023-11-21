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
use App\Models\Scopes\StrictActiveScope;
use App\Observer\PaymentObserver;
use Larapen\Admin\app\Models\Crud;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Payment extends BaseModel
{
	use Crud;
	
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

	public $skip_notification = false;

	protected $dates = [
        'start_date',
        'end_date',
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
	protected $fillable = ['user_id', 'package_id', 'payment_method_id', 'transaction_id', 'active','payment_type','amount','no_leads','remaining','invoice','monthly_leads','daily_send_limit'];
	
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
	// protected $dates = [];
	
	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	protected static function boot()
	{
		parent::boot();
		
		Payment::observe(PaymentObserver::class);
		
		//static::addGlobalScope(new StrictActiveScope());
		static::addGlobalScope(new LocalizedScope());
	}
	
	public function getPostTitleHtml()
	{
		$out = '#' . $this->post_id;
		if ($this->post) {
			$postUrl = url(config('app.locale') . '/' . $this->post->uri);
			$out .= ' | ';
			$out .= '<a href="' . $postUrl . '" target="_blank">' . $this->post->title . '</a>';
			
			if (config('settings.single.posts_review_activation')) {
				$outLeft = '<div class="pull-left">' . $out . '</div>';
				$outRight = '<div class="pull-right"></div>';
				
				if ($this->active != 1) {
					// Check if this ad has at least successful payment
					$countSuccessfulPayments = Payment::where('post_id', $this->post_id)->where('active', 1)->count();
					if ($countSuccessfulPayments <= 0) {
						$msg = trans('admin::messages.payment_post_delete_btn_tooltip');
						$tooltip = ' data-toggle="tooltip" title="' . $msg . '"';
						
						$outRight = '';
						$outRight .= '<div class="pull-right">';
						$outRight .= '<a href="' . admin_url('posts/' . $this->post_id) . '" class="btn btn-xs btn-danger" data-button-type="delete"' . $tooltip . '>';
						$outRight .= '<i class="fa fa-trash"></i> ';
						$outRight .= trans('admin::messages.Delete');
						$outRight .= '</a>';
						$outRight .= '</div>';
					}
				}
				
				$out = $outLeft . $outRight;
			}
		}
		
		return $out;
	}

	public function getUserTitleHtml()
	{
		$out = '';

		
		if ($this->user) {

			return $this->user->name;
			/*$postUrl = url(config('app.locale') . '/' . $this->post->uri);
			$out .= ' | ';
			$out .= '<a href="' . $postUrl . '" target="_blank">' . $this->post->title . '</a>';
			
			if (config('settings.single.posts_review_activation')) {
				$outLeft = '<div class="pull-left">' . $out . '</div>';
				$outRight = '<div class="pull-right"></div>';
				
				if ($this->active != 1) {
					// Check if this ad has at least successful payment
					$countSuccessfulPayments = Payment::where('post_id', $this->post_id)->where('active', 1)->count();
					if ($countSuccessfulPayments <= 0) {
						$msg = trans('admin::messages.payment_post_delete_btn_tooltip');
						$tooltip = ' data-toggle="tooltip" title="' . $msg . '"';
						
						$outRight = '';
						$outRight .= '<div class="pull-right">';
						$outRight .= '<a href="' . admin_url('posts/' . $this->post_id) . '" class="btn btn-xs btn-danger" data-button-type="delete"' . $tooltip . '>';
						$outRight .= '<i class="fa fa-trash"></i> ';
						$outRight .= trans('admin::messages.Delete');
						$outRight .= '</a>';
						$outRight .= '</div>';
					}
				}
				
				$out = $outLeft . $outRight;
			}*/
		}
		
		return $out;
	}

	public function getInvoiceHtml()
	{
		$out = '-';

		
		if (in_array($this->active ,["1","3"])) {
			$invoiceUrl = admin_url('payments/' . $this->id . '/edit?invoice=true');

			if($this->invoice){
				
				$invoiceViewUrl = Storage::url($this->invoice);
				
				$out = '<a href="'.$invoiceViewUrl.'" target="_blank" class="btn btn-xs btn-primary">
					view invoice
			    </a> | <a href="'.$invoiceUrl.'" class="btn btn-xs btn-primary">
					Upload invoice
			    </a>';
			}
			else{

				
				
				$out = '<a href="'.$invoiceUrl.'" class="btn btn-xs btn-primary">
					Upload invoice
			    </a>';
			}			
			 	
		}
		
		return $out;
	}


	public function getLimitsHtml()
	{
		$out = '-';

		
		if (in_array($this->active ,["1"])) {
			$invoiceUrl = admin_url('payments/' . $this->id . '/edit');
 
				
			$out = '<a href="'.$invoiceUrl.'" class="btn btn-xs btn-primary">
					update
			    </a>';
						
			 	
		}
		
		return $out;
	}

	
	
	public function getPackageNameHtml()
	{
		$out = $this->package_id;
		
		if (!empty($this->package)) {
			$packageUrl = admin_url('packages/' . $this->package_id . '/edit');
			
			$out = '';
			$out .= '<a href="' . $packageUrl . '">';
			$out .= $this->package->name;
			$out .= '</a>';
			$out .= ' (' . $this->amount . ' ' . $this->package->currency_code . ')';

			if($this->monthly_leads>0){
				
				$out .="<br>Monthly Leads:".$this->monthly_leads;				
			}

			if($this->daily_send_limit>0){
				
				$out .="<br>Daily Send Limit:".$this->daily_send_limit;				
			}


		}
		
		return $out;
	}

	public function getPackageLeadsHtml()
	{
		$out = '';
		


		if($this->payment_type=="Buy-Leads"){

			$out .= $this->remaining . '/' . $this->no_leads ;
			
		}
		else{

			$out .= $this->no_leads;			
		} 
		
		 
		 
		
		return $out;
	}

	
	
	public function getPaymentMethodNameHtml()
	{
		$out = '--';
		
		if (!empty($this->paymentMethod)) {
			$paymentMethodUrl = admin_url('payment_methods/' . $this->payment_method_id . '/edit');
			
			$out = '';
			$out .= '<a href="' . $paymentMethodUrl . '">';
			if ($this->paymentMethod->name == 'offlinepayment') {
				$out .= trans('offlinepayment::messages.Offline Payment');
			} else {
				$out .= $this->paymentMethod->display_name;
			}
			$out .= '</a>';
		}
		
		return $out;
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

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	
 	
 	public function histories()
    {
        return $this->hasMany(PackageHistory::class, 'package_id')->orderBy('id', 'DESC');
    }

	public function package()
	{
		return $this->belongsTo(Package::class, 'package_id', 'translation_of')->where('translation_lang', config('app.locale'));
	}
	
	public function paymentMethod()
	{
		return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
	}
	
	public function getInvoiceFromOldPath()
    {
        if (!isset($this->attributes) || !isset($this->attributes['invoice'])) {
            return null;
        }
        
        $value = $this->attributes['invoice'];
        
        // Fix path
        $value = str_replace('uploads/pictures/', '', $value);
        $value = str_replace('pictures/', '', $value);
        $value = 'pictures/' . $value;
        
        if (!Storage::exists($value)) {
            $value = null;
        }
        
        return $value;
    }


	public function getInvoiceAttribute()
    {
        // OLD PATH
        $value = $this->getInvoiceFromOldPath();
        if (!empty($value)) {
            return $value;
        }
        
        // NEW PATH
        if (!isset($this->attributes) || !isset($this->attributes['invoice'])) {
            return null;
        }
        
        $value = $this->attributes['invoice'];
        
        if (!Storage::exists($value)) {
            $value = config('larapen.core.picture.default');
        }
        
        return $value;
    }

    public function getActivePaymentHtml(){

    	if($this->active=="3"){
    		return "<font color='red'>Expired</font>";
    	}
    	else if($this->active=="1"){
    		return "<font color='green'>Active</font>";
    	}
    	else{

    		return $this->getActiveHtml();
    	}

    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setInvoiceAttribute($value)
    {
        $attribute_name = 'invoice';
        
       
        
        // Path
        $destination_path = 'invoices';
        
        // If the image was erased
        if (empty($value)) {
            // delete the image from disk
            if (!str_contains($this->{$attribute_name}, config('larapen.core.picture.default'))) {
                Storage::delete($this->{$attribute_name});
            }
            
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
                    $image = Image::make($value)->orientate()->encode($extension, config('larapen.core.picture.quality', 100));
                } else {
                    $image = Image::make($value)->encode($extension, config('larapen.core.picture.quality', 100));
                }
                
                
                
                // Generate a filename.
                $filename = md5($value . time()) . '.' . $extension;
                
                // Store the image on disk.
                Storage::put($destination_path . '/' . $filename, $image->stream());
               /* dump(Storage::delete($this->{$attribute_name}));

                dd($this->{$attribute_name});*/
                // Save the path to the database
                $this->attributes[$attribute_name] = $destination_path . '/' . $filename;


            } else {
                // Retrieve current value without upload a new file.
                if (starts_with($value, config('larapen.core.picture.default'))) {
                    $value = null;
                } else {
                    if (!starts_with($value, 'files/')) {
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
	
	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}
