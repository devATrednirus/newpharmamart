<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Larapen\Admin\app\Models\Crud;

class SearchHistory extends BaseModel
{
    //
    use Crud;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
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

         $url = admin_url('queries/?session_id=' . $this->session_id );
         return '<a href="' . $url . '" target="_blank">' . $this->session_id . '</a>'; 

    }
}
