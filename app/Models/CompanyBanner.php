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
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ActiveScope;
use App\Observer\BannerObserver;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Larapen\Admin\app\Models\Crud;

class CompanyBanner extends BaseModel
{
    use Crud;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_banners';
    
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
    protected $fillable = ['user_id', 'filename','title', 'position', 'active','lft','rgt','depth','parent_id','link','post_id'];
    
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
    protected $dates = ['created_at', 'updated_at'];
    
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();
        
        Banner::observe(BannerObserver::class);
        
        static::addGlobalScope(new ActiveScope());
        static::addGlobalScope(new LocalizedScope());
    }
    
    public function getFilenameHtml()
    {
        // Get picture
        $out = '<img src="' . resize($this->filename, 'small') . '" style="width:auto; max-height:90px;">';
        
        return $out;
    }
    
    public function getPostTitleHtml()
    {
        if ($this->post) {
            $postUrl = url(config('app.locale') . '/' . $this->post->uri);
            
            return '<a href="' . $postUrl . '" target="_blank">' . $this->post->title . '</a>';
        } else {
            return 'no-link';
        }
    }
    
    public function editPostBtn($xPanel = false)
    {
        $out = '';
        
        if ($this->post) {
            $url = admin_url('posts/' . $this->post->id . '/edit');
            
            $msg = trans('admin::messages.Edit the ad of this picture');
            $tooltip = ' data-toggle="tooltip" title="' . $msg . '"';
            
            $out .= '<a class="btn btn-xs btn-default" href="' . $url . '"' . $tooltip . '>';
            $out .= '<i class="fa fa-edit"></i> ';
            $out .= mb_ucfirst(trans('admin::messages.Edit the ad'));
            $out .= '</a>';
        }
        
        return $out;
    }
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
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

    public function getPostHtml()
    {

        $link = "";
        if (isset($this->link)) {
            $link = $this->link;
        }
        else if (isset($this->post)) {
            
            $link = $url = url(config('app.locale') . '/' . $this->post->uri);
        }

        if($link==""){
            return "-";
        }
        return '<a href="' . $link . '" target="_blank">link</a>';
    }
    public function getUserHtml()
    {
 
        if (isset($this->user)){
            return $this->user->name;   
        }
        else{

            return '-' ;
        }
        
    }

    public function getFilenameFromOldPath()
    {
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }
        
        $value = $this->attributes['filename'];
        
        // Fix path
        $value = str_replace('uploads/pictures/', '', $value);
        $value = str_replace('pictures/', '', $value);
        $value = 'pictures/' . $value;
        
        if (!Storage::exists($value)) {
            $value = null;
        }
        
        return $value;
    }
    
    public function getFilenameAttribute()
    {
        // OLD PATH
        $value = $this->getFilenameFromOldPath();
        if (!empty($value)) {
            return $value;
        }
        
        // NEW PATH
        if (!isset($this->attributes) || !isset($this->attributes['filename'])) {
            return null;
        }
        
        $value = $this->attributes['filename'];
        
        if (!Storage::exists($value)) {
            $value = config('larapen.core.picture.default');
        }
        
        return $value;
    }
    
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setFilenameAttribute($value)
    {
        $attribute_name = 'filename';
        
       
        
        // Path
        $destination_path = 'compnay-banners';
        
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
}
