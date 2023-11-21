<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use Illuminate\Support\Facades\Cache;
use Torann\LaravelMetaTags\Facades\MetaTag;

class SitemapController extends FrontController
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function index($cat_slug="")
    {


        $data = array();
        
        // Get Categories
        $cacheId = 'categories.all.'.$cat_slug . config('app.locale');
        $cats = Cache::remember($cacheId, $this->cacheExpiration, function () use($cat_slug){
            $cats = Category::where(function($query)use($cat_slug){
                if($cat_slug){
                    $query->whereSlug($cat_slug);
                }
            })->trans()->orderBy('lft')->get();

            return $cats;
        });
        $cats = collect($cats)->keyBy('translation_of');
        $cats = $subCats = $cats->groupBy('parent_id');
        if(count($cats)==0){

            return redirect('/sitemap');
        }

        if($cat_slug){
          $cats = $cats[0][0]->children;
          
        }
        else{
          $cats = $cats[0];  
        }
        
        $col =  1;
        $data['cats'] = $cats;//->get(0)->chunk($col);
        // dd($data);
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'sitemap'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'sitemap')));
        MetaTag::set('keywords', getMetaTag('keywords', 'sitemap'));
        
        return view('sitemap.index', $data);
    }
    public function loadmore ($cat_slug="")
    {


        $data = array();
        $page = $_POST['page'];
        $limit = 4;

        $data['categories'] = Category::where('parent_id', $_POST['catid'])->skip($limit)->take(1000)->get();

        // Get Categories
         
   
        return view('sitemap.loadmore_index', $data);
    }
}
