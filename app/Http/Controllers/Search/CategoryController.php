<?php
namespace App\Http\Controllers\Search;

use App\Helpers\Search;
use App\Models\Category;
use App\Models\CategoryField;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Support\Facades\Cache;


class CategoryController extends BaseController
{
    public $isCatSearch = true;

    protected $cat = null;
    protected $subCat = null;

    /**
     * @param $countryCode
     * @param $catSlug
     * @param null $subCatSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index($countryCode, $catSlug, $subCatSlug = null)
    {



        // Check multi-countries site parameters
        //if (!config('settings.seo.multi_countries_urls')) {
            $subCatSlug = $catSlug;
            $catSlug = $countryCode;
        //}


        view()->share('isCatSearch', $this->isCatSearch);
        //->where('parent_id', 0)
        // Get Category
        $cacheId = $countryCode . $catSlug . '.cateorySections';
        $checkCat = Cache::remember($cacheId, $this->cacheExpiration, function () use ($catSlug) {
            return  Category::trans()->where('slug', '=', $catSlug)->firstOrFail();
        });
        if ($checkCat->parent_id == "0") {

            $this->cat =  $checkCat;
        }
        else{

            $this->cat =  $checkCat->parent;

        }


        //echo  $this->cat;
        view()->share('cat', $this->cat);

        // Get common Data
        $catName = $this->cat->name;
        $catDescription = $this->cat->description;
        $catBottomText = $this->cat->bottom_text;

        // Get Category nested IDs
        $catNestedIds = (object)[
            'parentId' => $this->cat->parent_id,
            'id'       => $this->cat->tid,
        ];

        // Check if this is SubCategory Request
        if ($checkCat->parent_id!="0")
        {
            $this->isSubCatSearch = true;
            view()->share('isSubCatSearch', $this->isSubCatSearch);

            // Get SubCategory
            $this->subCat = $checkCat;
            view()->share('subCat', $this->subCat);

            // Get common Data
            $catName = $this->subCat->name;
            $catDescription = $this->subCat->description;
            $catBottomText = $this->subCat->bottom_text;

            // Get Category nested IDs
            $catNestedIds = (object)[
                'parentId' => $this->subCat->parent_id,
                'id'       => $this->subCat->tid,
            ];
        }

        // Get Custom Fields
         $cacheId = $catNestedIds->parentId.$catNestedIds->id . 'categoryPageCache1';
        $customFields = Cache::remember($cacheId, $this->cacheExpiration, function () use ($catNestedIds) {
            return CategoryField::getFields($catNestedIds);
        });
        view()->share('customFields', $customFields);

        // Search
        $tid = $this->cat->tid;
        if(isset($this->subCat->tid)){
            $subCatTid = $this->subCat->tid;
        }else {
            $subCatTid = '-';
        }
        $subCat = $this->subCat;
        $cacheId = $tid.$subCatTid.$subCat . 'categoryPageCache2';

        $data = Cache::remember($cacheId, $this->cacheExpiration, function () use ($tid, $subCatTid, $subCat) {

        $search = new Search();

            if (isset($subCat) && !empty($subCat)) {
                return $search->setCategory($tid, $subCatTid)->setRequestFilters()->fetch();
        } else {
                return $search->setCategory($tid)->setRequestFilters()->fetch();
        }
        });

        $search =  new Search();

        //view()->share('keywords', $search->keywords);
        view()->share('city', $search->city);

        if(isset($_POST['view']) && $_POST['view']=="ajax"){
           return view('search.inc.posts', $data);
        }

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // SEO
        $title = $this->getTitle();

        $title = str_replace('{LOCATION}','', $title);

        if (isset($catDescription) && !empty($catDescription)) {
            $catDescription = str_replace('{LOCATION}','', $catDescription);
            $description = str_limit($catDescription, 200);
        } else {
            $description = str_limit(t('Free ads :category in :location', [
                    'category' => $catName,
                    'location' => config('country.name')
                ]) . '. ' . t('Looking for a product or service') . ' - ' . config('country.name'), 200);
        }

        if (isset($catBottomText) && !empty($catBottomText)) {
            $catBottomText = str_replace('{LOCATION}','', $catBottomText);

        }

        view()->share('bottom_text', $catBottomText);
        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        $keywords = $this->getKeywords();


        if($keywords=="" && $data['paginator']->getCollection()->count() > 0 ){
            $customKeywords=[];
            foreach($data['paginator']->getCollection() as $key => $post){

                $customKeywords[]= $post->title;

                if(count($customKeywords)>9){
                    break;
                }
            }

            $keywords = implode(", ", $customKeywords);

        }

        //dd($keywords);
        MetaTag::set('keywords', $keywords);

        // Open Graph
        $this->og->title($title)->description($description)->type('website');
        if ($data['count']->get('all') > 0) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
        }
        view()->share('og', $this->og);

        // Translation vars
        view()->share('uriPathCatSlug', $catSlug);
        if (!empty($subCatSlug)) {
            view()->share('uriPathSubCatSlug', $subCatSlug);
        }

        return view('search.serp', $data);
    }

    public function loadmore($countryCode, $catSlug, $subCatSlug = null)
    {



        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $subCatSlug = $catSlug;
            $catSlug = $countryCode;
        }


        view()->share('isCatSearch', $this->isCatSearch);
        //->where('parent_id', 0)
        // Get Category
        $cacheId = $countryCode . $catSlug . '.cateorySections';
        $checkCat = Cache::remember($cacheId, $this->cacheExpiration, function () use ($catSlug) {
            return  Category::trans()->where('slug', '=', $catSlug)->firstOrFail();
        });
        if ($checkCat->parent_id == "0") {

            $this->cat =  $checkCat;
        }
        else{

            $this->cat =  $checkCat->parent;

        }



        view()->share('cat', $this->cat);

        // Get common Data
        $catName = $this->cat->name;
        $catDescription = $this->cat->description;
        $catBottomText = $this->cat->bottom_text;

        // Get Category nested IDs
        $catNestedIds = (object)[
            'parentId' => $this->cat->parent_id,
            'id'       => $this->cat->tid,
        ];

        // Check if this is SubCategory Request
        if ($checkCat->parent_id!="0")
        {
            $this->isSubCatSearch = true;
            view()->share('isSubCatSearch', $this->isSubCatSearch);

            // Get SubCategory
            $this->subCat = $checkCat;
            view()->share('subCat', $this->subCat);

            // Get common Data
            $catName = $this->subCat->name;
            $catDescription = $this->subCat->description;
            $catBottomText = $this->subCat->bottom_text;

            // Get Category nested IDs
            $catNestedIds = (object)[
                'parentId' => $this->subCat->parent_id,
                'id'       => $this->subCat->tid,
            ];
        }

        // Get Custom Fields
         $cacheId = $catNestedIds->parentId.$catNestedIds->id . 'categoryPageCache1';
        $customFields = Cache::remember($cacheId, $this->cacheExpiration, function () use ($catNestedIds) {
            return CategoryField::getFields($catNestedIds);
        });
        view()->share('customFields', $customFields);

        // Search
        $tid = $this->cat->tid;
        if(isset($this->subCat->tid)){
            $subCatTid = $this->subCat->tid;
        }else {
            $subCatTid = '-';
        }
        $subCat = $this->subCat;
        $cacheId = $tid.$subCatTid.$subCat . 'categoryPageCache2';

       // $data = Cache::remember($cacheId, $this->cacheExpiration, function () use ($tid, $subCatTid, $subCat) {

        $search = new Search();

        if (isset($subCat) && !empty($subCat)) {
                $data = $search->setCategory($tid, $subCatTid)->setRequestFilters()->fetch();
        } else {
                $data = $search->setCategory($tid)->setRequestFilters()->fetch();
        }
        //});

        $search =  new Search();

        //view()->share('keywords', $search->keywords);
        view()->share('city', $search->city);

        if(isset($_POST['view']) && $_POST['view']=="ajax"){
           return view('search.inc.posts', $data);
        }

    }
}
