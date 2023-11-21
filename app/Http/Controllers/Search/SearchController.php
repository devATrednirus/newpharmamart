<?php
namespace App\Http\Controllers\Search;

use App\Helpers\Search;
use App\Http\Controllers\Search\Traits\PreSearchTrait;
use App\Models\CategoryField;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\Category;
use App\Models\User;
use App\Models\Post;
use App\Models\ProductGroup;
use App\Models\Division;
use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use App\Models\CompanyBanner;
class SearchController extends BaseController
{
    use PreSearchTrait;

    use CustomFieldTrait;

    public $isIndexSearch = true;
    
    protected $cat = null;
    protected $subCat = null;
    protected $city = null;
    protected $admin = null;
    protected $whitelisted = false;
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($locationSlug=null,$categorySlug=null,$subCatSlug=null)
    {



        if(\Route::currentRouteName()=="company_name"){
             
            $categorySlug = $locationSlug;

            $domain = str_replace("www.", "", $_SERVER['SERVER_NAME']);


            $seller = User::where('id','<>','1')->where('blocked','0')->where('domain', $domain)->first();
 	


            if(!$seller){
                dd($_SERVER['SERVER_NAME']);
                headerLocation(\Config::get('app.url'));
                exit;
            }
            $this->whitelisted = true;
            $locationSlug = $seller->username;
        }
        
        if($locationSlug==null){
            return $this->searchDisplay($locationSlug,$categorySlug,$subCatSlug);           
        }
        else{



            $profile = $this->profile($locationSlug,$categorySlug);
            if($profile==false){

                if($locationSlug!=null){
                    $locationSlug = str_replace("-", " ", $locationSlug);    
                    $city = $this->getCity(request()->get('l'), $locationSlug);

                    if($locationSlug=="india"){
                        $city_check = $this->getCity(request()->get('l'), 'chandigarh');
                        
                        $city->latitude    = $city_check->latitude;
                        $city->longitude    = $city_check->longitude;
                    }
                    

                    if($locationSlug!="india" && $city->id=="-999999"){

                        abort(404);

                    }


                }

                return $this->searchDisplay($locationSlug,$categorySlug,$subCatSlug);
            }
            else{

                return $profile;
            }
            
        }

        


    }

    public function searchDisplay($locationSlug=null,$categorySlug=null,$subCatSlug=null)
    {

$urlnotfound=str_replace('%20', '-', $_SERVER['REQUEST_URI']);
$arr=explode('/', $urlnotfound);

if(in_array('index.php',$arr)){
    header("location:/".$arr[count($arr)-2]."/".$categorySlug); 
    exit;
}
        //dd($categorySlug);

        
        view()->share('isIndexSearch', $this->isIndexSearch);
        
        $cat = null;
        $subCat = null;

        if($categorySlug){

            $catCheck = Category::trans()->where('slug', '=', $categorySlug)->firstOrFail();

            if($catCheck->parent_id=="0"){

                $cat = $catCheck;
            }
            else{
                $cat = $catCheck->parent;
                
                $subCat = $catCheck;    
            }

        } 




        



 
        //view()->share('cat', $this->cat);

        // Pre-Search
        if (request()->filled('c') || $cat) {
            
            $c = ($cat?$cat->id:request()->get('c'));

            if (request()->filled('sc') || $subCat) {

                $sc = ($subCat?$subCat->id:request()->get('sc'));
                $this->getCategory($c, $sc);
                
                // Get Category nested IDs
                $catNestedIds = (object)[
                    'parentId' => $c,
                    'id'       => $sc,
                ];
            } else {
                $this->getCategory($c);
                
                // Get Category nested IDs
                $catNestedIds = (object)[
                    'parentId' => 0,
                    'id'       => $c,
                ];
            }
            
            // Get Custom Fields
            $customFields = CategoryField::getFields($catNestedIds);
            view()->share('customFields', $customFields);
        }
        if (request()->filled('l') || $locationSlug) {
            $locationSlug = str_replace("-", " ", $locationSlug);
            $city = $this->getCity(request()->get('l'), $locationSlug);

            if($locationSlug=="india"){
                $city_check = $this->getCity(request()->get('l'), 'chandigarh');
                
                $city->latitude    = $city_check->latitude;
                $city->longitude    = $city_check->longitude;
            }
        
        }
        if (request()->filled('r') && !request()->filled('l')) {
            $admin = $this->getAdmin(request()->get('r'));
        }
        
        // Pre-Search values
        $preSearch = [
            'city'  => (isset($city) && !empty($city)) ? $city : null,
            'admin' => (isset($admin) && !empty($admin)) ? $admin : null,
        ];
        
        // Search
        $search = new Search($preSearch);

        $catDescription="";
        $catBottomText=""; 
        if ($cat && $subCat) {
            $search = $search->setCategory($cat->tid, $subCat->tid);

            $catDescription = $subCat->description;
            $catBottomText = $subCat->bottom_text;
        } else if($cat){
            $search = $search->setCategory($cat->tid);
             $catDescription = $cat->description;
             $catBottomText = $cat->bottom_text;
        }



        $data = $search->fechAll();

        view()->share('keywords', $search->keywords);

         

        $city = $search->city;
        
        $cityName ="";

        if($city){
            $cityName = "in ". $city->name;
        }
        $catDescription = str_replace('{LOCATION}',$cityName, $catDescription);
        $catBottomText = str_replace('{LOCATION}',$cityName, $catBottomText);
     

        view()->share('city', $city);
         
        
        if(isset($_POST['view']) && $_POST['view']=="ajax"){
           return view('search.inc.posts', $data);
        }
        
        // Export Search Result
        view()->share('count', $data['count']);
        view()->share('paginator', $data['paginator']);
        view()->share('bottom_text', $catBottomText);
        
        
        // Get Titles
        $title = $this->getTitle();

        $title = str_replace('{LOCATION}',$cityName, $title);

        //dd($catDescription);
        $this->getBreadcrumb();
        $this->getHtmlTitle();

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $catDescription);
        
        $keywords = $this->getKeywords();
        MetaTag::set('keywords', $keywords);

        return view('search.serp');
    }


    /**
     * @param $countryCode
     * @param null $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($username,$slug)
    {


        

        // Get User
        $this->sUser = User::with('businessType')->with('ownershipType')->with('city.subAdmin1')->with(['posts'=>function($query){

            $query->select('id','title','user_id')->limit(5);
        }])->where('id','<>','1')->where('username', $username)->first();

        if($this->sUser){
                return $this->searchByUserId($this->sUser->id,$username,$slug); 
        }
        else{
            return false;
        }
        
    }
    
    /**
     * @param $userId
     * @param null $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function searchByUserId($userId,$username,$slug=null)
    {




        if($this->whitelisted==true){

            $compnay_route_home = 'routes.v-domain-home';
            $compnay_route_inner = 'routes.v-domain-inner';

        }
        else{
            $compnay_route_home = 'routes.search-user';
            $compnay_route_inner = 'routes.v-company-group';
        }

        

        view()->share('compnay_route_home', $compnay_route_home);
        view()->share('compnay_route_inner', $compnay_route_inner);


        $about_us = trans($compnay_route_inner, [
                    'slug' => 'about-us',
                    'username'   =>  $this->sUser->username,
                ]);
        view()->share('about_us', $about_us);
         


      $product_range = trans($compnay_route_inner, [
                    'slug' => 'product-range',
                    'username'   =>  $this->sUser->username,
                ]);
        view()->share('product_range', $product_range);










        $contact_us = trans($compnay_route_inner, [
                        'slug' => 'contact-us',
                        'username'   =>  $this->sUser->username,
                    ]);
        view()->share('contact_us', $contact_us);

        $company_url  = trans($compnay_route_home, [
                        'username'   =>  $this->sUser->username,
                    ]);
        view()->share('company_url', $company_url);


        view()->share('isUserSearch', $this->isUserSearch);

        $group = null;
   

        $active = "home";
        
        $banners = CompanyBanner::where('user_id',$userId)->where('active','1')->orderBy('lft', 'ASC')->get();

        if($banners->count()==0){

            $banners = CompanyBanner::where('user_id','1')->where('active','1')->orderBy('lft', 'ASC')->get();

        }
        //dd($banners);        
        //App\Models\CompanyBanner

        view()->share('banners', $banners);


        $divisions = Division::where('user_id',$this->sUser->id)->orderBy('name')->get();

        view()->share('divisions', $divisions);
        
        $posts = Post::with('group')->where('user_id',$userId)->orderBy('group_id','desc')->get();

        
        $groups=[];
        foreach ($posts as $key => $post) {
                
                if($post->group){
                    if(!isset($groups[$post->group_id])){

                        $groups[$post->group_id]=['data'=>$post->group,'posts'=>[]];
                    }

                   $groups[$post->group_id]['posts'][]=$post; 
                }
                else{
                    if(!isset($groups['others'])){

                        $groups['others']=['data'=>['name'=>'Others','id'=>'others'],'posts'=>[]];
                    }

                 $groups['others']['posts'][]=$post;    
                }
        }

        
        view()->share('groups', $groups);


        view()->share('slug', $slug);
       
        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // Meta Tags
        
        $this->sUser->package;
        $title = $this->sUser->name;
        $description = "";
        

        /*if($this->sUser->ownershipType){

            $description.=  " ".$this->sUser->ownershipType->name;

        }
*/

        if(!in_array($slug,["contact-us"])){
            if($this->sUser->businessType){

                $description.=  $this->sUser->businessType->name;
                $title.=  " - ".$this->sUser->businessType->name;

            }
        }

        $services = "";

        //$this->sUser->about_us="";
        if($this->sUser->about_us==""){
                
            $cmp_groups = ucwords(implode(", ",ProductGroup::where('user_id',$this->sUser->id)->orderBy('name')->pluck('name')->take(5)->toArray()));
            
            $this->sUser->about_us= "<p><strong>".$this->sUser->name.'</strong>'.($this->sUser->businessType?" is <strong>".$this->sUser->businessType->name.'</strong>':'').($this->sUser->city?" in ".$this->sUser->city->name.($this->sUser->city->name!=$this->sUser->city->subAdmin1->name?", ".$this->sUser->city->subAdmin1->name:''):'').".".($this->sUser->establishment_year?" Established in Year <strong>".$this->sUser->establishment_year."</strong>.":'').'<strong>'.$this->sUser->name."</strong> is Running under the Guidance of ".($this->sUser->ceo_first_name?" <strong>".ucwords($this->sUser->ceo_first_name).($this->sUser->ceo_last_name?" ".ucwords($this->sUser->ceo_last_name):'').'</strong>':' Our CEO').($this->sUser->no_employees?" with <strong>".$this->sUser->no_employees.'</strong> team members.':'').($this->sUser->annual_turnover?" Our Company has annual turnover <strong>".$this->sUser->annual_turnover.'</strong>.':'').($cmp_groups?" We are offering <strong>".$cmp_groups.'</strong>.':'')."</p>";

        }                




        $titleServices=[];
        $allServices=[];
        
        
        if($slug){
            $active = "products";            

            if($slug=="about-us"){

                $active = "about-us";   

                //$title= "About Us | ".$title;  
            }
            else if($slug=="contact-us"){

                $active = "contact-us";     
                $title= $title."| Contact Details";
            }
         else if($slug=="product-range"){

                $active = "product-range";     
                $title= $title."|Product Range";
            }
                   
         
            else{

                if(strtolower($slug)=="other"){
                    $group = "other";

                    $title= "Others | ".$title;
                    $description= "Others | ".$description;
                    $allServices[] = "Others";

                }
                else{

                     $group = ProductGroup::where('user_id',$this->sUser->id)->whereSlug($slug)->first();

                     if($group){
                         
                         $title= $group->name." | ".$title;
                         $description= $group->name." ".$description;
                         $allServices[] = $group->name;

                     }

                }

                view()->share('activeGroup', $group);
            
                if(!$group){


                    headerLocation($company_url);
                }

                  $posts = Post::with('group')->where('user_id',$userId)->where(function($query)use($group){
                        if($group){
                            if($group=="other")
                            {
                                $query->whereNull('group_id')->orwhere('group_id','0');
                            }
                            else{
                                $query->where('group_id',$group->id);

                            }
                        }
                })->orderBy('group_id','desc')->get();
                
                
                // $keywords=[];
                 $product_groups=[];
                    foreach ($posts as $key => $post) {

                            $catNestedIds = (object)[
                                'parentId' => $post->category->parent_id,
                                'id'       => $post->category->tid,
                            ];
                            $post->customFields = $this->getPostFieldsValues($catNestedIds, $post->id);

                             
                           // $keywords[]=$post->title;
                            if($post->group){
                                if(!isset($product_groups[$post->group_id])){

                                    $product_groups[$post->group_id]=['data'=>$post->group,'posts'=>[]];
                                }

                               $product_groups[$post->group_id]['posts'][]=$post; 
                            }
                            else{
                                if(!isset($product_groups['others'])){

                                    $product_groups['others']=['data'=>['name'=>'Others','id'=>'others'],'posts'=>[]];
                                }

                                $product_groups['others']['posts'][]=$post;    
                            }
                    }

                  //  $keywords = implode(', ', $keywords).", ";
                    
                view()->share('product_groups', $product_groups);
            }
        }
        else{


            $posts = $this->sUser->posts;
        }
        
        view()->share('active', $active);


        if($posts){

             

            


            foreach ($posts as $key => $value) {
                
                 $allServices[]=$value->title;
                //$title.="";
                if($key<=1){
                    
                    $titleServices[]=$value->title;

                }
                if($key>0 ){
                    if(($key<count($this->sUser->posts)-1)){
                        
                        $services.=",";    

                    }
                    else{
                        $services.=" and";                          
                    }
                }
                $services.=" ".$value->title;

            }

            if(!in_array($slug,["contact-us","about-us"])){

                //$description.=  " - ".$this->sUser->businessType->name;
                $title.=  " of ".implode(" and ", $titleServices);

            }
            $keywords = implode(', ', $allServices).", ";
            

        }

         // dd($keywords);
         $keywords.=$this->sUser->name;

        if($services != ""){
            if($description!=""){
                $description.=" of";
            }
            $description.=$services." offered by ".$this->sUser->name;
        }

        

        if($this->sUser->city){

            $description.=" from ".$this->sUser->city->name; 
            $keywords.=", ".$this->sUser->city->name; 
            
            if($this->sUser->city->subAdmin1){

                 $description.=", ".$this->sUser->city->subAdmin1->name; 
                //
            }
        }

        $userPhoto =  null;
        if (isset($this->sUser->photo) && !empty($this->sUser->photo)) {

            //$userPhoto = resize($this->sUser->photo, 'small');
            $userPhoto = 'storage/'.$this->sUser->photo;

        }


        if(!in_array($slug,["contact-us"])){


            if($this->sUser->city){

              //  $description.=  ", ".$this->sUser->city->name;
                $title.=  " from ".$this->sUser->city->name;
                
                if(in_array($slug,["about-us",null])){

                    if($this->sUser->city->subAdmin1 && $this->sUser->city->name!=$this->sUser->city->subAdmin1->name){

                        $title.=  ", ".$this->sUser->city->subAdmin1->name;
                    }
                }

                $title.=  ", India";
            }
        }

        $description = $this->sUser->about_us;
        /*
        dump($title);
        dump($description);
        dd($keywords); */

        view()->share('userPhoto', $userPhoto);

       // dd($this->sUser);
        view()->share('sUser', $this->sUser);
 
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);
        MetaTag::set('keywords', $keywords);
    
        // Translation vars
        view()->share('uriPathUserId', $userId);
        view()->share('uriPathUsername', $username);

        $template = 'template1';
        $template_color = '';

        if($this->sUser->template){

                $template = $this->sUser->template;

        }

        if($this->sUser->color){

                $template_color = $this->sUser->color;

        }

        if(isset($_GET['template']) && in_array($_GET['template'],['2'])){

            $template = 'template'.$_GET['template'];
        }

        if(isset($_GET['template_color'])){

            $template_color = $_GET['template_color'];
        }

        view()->share('template', $template);

        view()->share('template_color', $template_color);

        

        return view('search.inc.'.$template.'.company');
    }
}
