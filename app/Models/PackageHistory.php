<?php

namespace App\Models;

use App\Models\Scopes\LocalizedScope;
use App\Models\Scopes\StrictActiveScope;
use App\Observer\PaymentObserver;
use Larapen\Admin\app\Models\Crud;
use Jenssegers\Date\Date;

class PackageHistory extends BaseModel
{
	use Crud;
    //
	protected $casts = [
        'monthly_leads' => 'integer' 
        
    ];

    public function getPackageNameHtml()
	{
		$out = $this->package_id;
		
		if (!empty($this->package)) {
			$packageUrl = admin_url('packages/' . $this->package_id . '/edit');
			
			$out = '';
			$out .= '<a href="' . $packageUrl . '">';
			$out .= $this->package->name;
			$out .= '</a>';
			$out .= ' (' . $this->package->price . ' ' . $this->package->currency_code . ')';
		}
		
		return $out;
	}

	public function package()
	{
		return $this->belongsTo(Package::class, 'package_id', 'translation_of')->where('translation_lang', config('app.locale'));
	}


	public function getUserNameHtml()
	{
		$out = "";
		
		if (!empty($this->user)) {
			$packageUrl = admin_url('users/' . $this->user_id . '/edit');
			
			$out = '';
			$out .= '<a href="' . $packageUrl . '">';
			$out .= $this->user->email;
			$out .= '</a>';
			 
		}
		
		return $out;
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}


	/*public function getCreatedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }
 
        return $value->formatLocalized('%A %d %B %Y %H:%M');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value->formatLocalized('%A %d %B %Y %H:%M %P');
    }


    public function getStartDateAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }
   
        return $value->formatLocalized('%A %d %B %Y');
    }
    
    public function getEndDateAttribute($value)
    {
        $value = Date::parse($value);
        if (config('timezone.id')) {
            $value->timezone(config('timezone.id'));
        }

        return $value->formatLocalized('%A %d %B %Y');
    }*/
    

}
