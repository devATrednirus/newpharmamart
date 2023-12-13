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

namespace App\Helpers;


use App\Models\PostType;
use App\Models\Post;
use App\Models\City;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request as Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;
use GeoIp2\Database\Reader;

class Search
{
    protected $cacheExpiration;

    public $country;
    public $lang;
    public static $queryLength = 1; // Minimum query characters
    public static $distance = 100; // km
    public static $maxDistance = 500; // km
    public $perPage = 6;
    public $currentPage = 0;
    protected $table = 'posts';
    protected $searchable = [
        'columns' => [
            'a.title'       => 10,
            'u.name'       => 20,
            'a.description' => 10,
            'a.short_description' => 10,
            'cl.name'       => 5,
            'cpl.name'      => 2,
            //'cl.description'   => 1,
            //'cpl.description'  => 1,
        ],
        'joins'   => [
            'categories as c'  => ['c.id', 'posts.category_id'],
            'categories as cp' => ['cp.id', 'c.parent_id'],
            'categories as cpp' => ['ccp.id', 'cp.parent_id'],
        ],
    ];
    public $forceAverage = true; // Force relevance's average
    public $average = 1; // Set relevance's average

    // Pre-Search vars
    public $city = null;
    public $admin = null;
    public $keywords = "";
    /**
     * Ban this words in query search
     * @var array
     */
    //protected $banWords = ['sell', 'buy', 'vendre', 'vente', 'achat', 'acheter', 'ses', 'sur', 'de', 'la', 'le', 'les', 'des', 'pour', 'latest'];
    protected $banWords = [];
    protected $arrSql = [
        'select'  => [],
        'join'    => [],
        'where'   => [],
        'groupBy' => [],
        'having'  => [],
        'orderBy' => [],
    ];
    protected $bindings = [];
    protected $sql = [
        'select'  => '',
        'from'    => '',
        'join'    => '',
        'where'   => '',
        'groupBy' => '',
        'having'  => '',
        'orderBy' => '',
    ];
    // Only for WHERE
    protected $filters = [
        'type'       => 'a.post_type_id',
        'minPrice'   => 'calculatedPrice', // 'a.price',
        'maxPrice'   => 'calculatedPrice', // 'a.price',
        'postedDate' => 'a.created_at',
        'cf'         => '@dummy',
        'ct'         => 'u.name',
    ];
    protected $orderMapping = [
        'priceAsc'  => ['name' => 'a.price', 'order' => 'ASC'],
        'priceDesc' => ['name' => 'a.price', 'order' => 'DESC'],
        'relevance' => ['name' => 'relevance', 'order' => 'DESC'],
        'date'      => ['name' => 'a.created_at', 'order' => 'DESC'],
    ];

    /**
     * Search constructor.
     * @param array $preSearch
     */
    public function __construct($preSearch = [])
    {
        $this->cacheExpiration = (int)config('settings.other.cache_expiration', 1440);

        // Pre-Search
        if (isset($preSearch['city']) && !empty($preSearch['city'])) {
            $this->city = $preSearch['city'];
        }
        if (isset($preSearch['admin']) && !empty($preSearch['admin'])) {
            $this->admin = $preSearch['admin'];
        }

        // Distance (Max & Default distance)
        self::$maxDistance = config('settings.listing.search_distance_max', 0);
        self::$distance = config('settings.listing.search_distance_default', 0);

        // Ads per page
        if ($this->perPage < 4) $this->perPage = 4;
        if ($this->perPage > 40) $this->perPage = 40;

        // Init.
        array_push($this->banWords, strtolower(config('country.name')));
        $this->arrSql = Arr::toObject($this->arrSql);
        $this->sql = Arr::toObject($this->sql);
        $this->sql->select = '';
        $this->sql->from = '';
        $this->sql->join = '';
        $this->sql->where = '';
        $this->sql->groupBy = '';
        $this->sql->having = '';
        $this->sql->orderBy = '';

       // $this->arrSql->orderBy['p.lft'] = ' DESC';
        //$this->arrSql->groupBy[] = "";
          $this->arrSql->groupBy[] = "a.user_id";
        // Build the global SQL
        // $this->arrSql->select[] = "a.*";
          //DISTINCT(a.user_id),
        $this->arrSql->select[] = "a.*, (a.price * " . config('selectedCurrency.rate', 1) . ") as calculatedPrice,u.name as company_name,u.username ";
        // Post category relation
        $this->arrSql->join[] = "INNER JOIN " . DBTool::table('categories') . " as c ON c.id=a.category_id AND c.active=1";
        // Category parent relation
        $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('categories') . " as cp ON cp.id=c.parent_id AND cp.active=1";

         $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('users') . " as u ON u.id=a.user_id";
        // Post payment relation
        // $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('payments') . " as py ON py.post_id=a.id";
      //  $this->arrSql->join[] = "LEFT JOIN (SELECT MAX(id) max_id, post_id FROM " . DBTool::table('payments') . " WHERE active=1 GROUP BY post_id) mpy ON mpy.post_id = a.id AND a.featured=1";
        //$this->arrSql->join[] = "LEFT JOIN " . DBTool::table('payments') . " as py ON py.id=mpy.max_id";
        $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('packages') . " as p ON p.id=u.package_id";
        $this->arrSql->where = [
            'a.country_code'    => " = :countryCode",
            //'(a.verified_email' => " = 1 AND a.verified_phone = 1)",
            'a.archived'        => " != 1",
            'a.deleted_at'      => " IS NULL",
        ];

        if(isset($_POST['listings_ids'])){

            if(!empty($_POST['listings_ids'])) {
                $this->arrSql->where['a.user_id']=" not in (0) ";
            } else {
            $this->arrSql->where['a.user_id']=" not in (".implode(",",json_decode($_POST['listings_ids'])).") ";
          }

        //     $this->bindings['listings_ids'] = implode(",",json_decode($_POST['listings_ids']));

            // dd( $this->arrSql->where);
        }
        //


        $this->bindings['countryCode'] = config('country.code');

        // Check reviewed ads
        if (config('settings.single.posts_review_activation')) {
            $this->arrSql->where['a.reviewed'] = " = 1";
        }

        // Priority setter
        if (request()->filled('distance') && is_numeric(request()->get('distance')) && request()->get('distance') > 0) {
            self::$distance = request()->get('distance');
            if (request()->get('distance') > self::$maxDistance) {
                self::$distance = self::$maxDistance;
            }
        }
        if (request()->filled('orderBy')) {
            $this->setOrder(request()->get('orderBy'));
        }

        // Pagination Init.
        $this->currentPage = (request()->get('page') < 0) ? 0 : (int)request()->get('page');
        $page = (request()->get('page') <= 1) ? 1 : (int)request()->get('page');
        $this->sqlCurrLimit = ($page <= 1) ? 0 : $this->perPage * ($page - 1);
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return mixed
     */
    public static function query($sql, $bindings = [])
    {
        // DEBUG
        // echo 'SQL<hr><pre>' . $sql . '</pre><hr>'; //exit();
        // echo 'BINDINGS<hr><pre>'; print_r($bindings); echo '</pre><hr>';

        try {
            $result = DB::select(DB::raw($sql), $bindings);
        } catch (\Exception $e) {
            $result = null;

            // DEBUG
            if(isset($_GET['test']) && $_GET['test']=="pagei"){
                 echo $sql."\n\n";
                dump($bindings);
                 dd($e->getMessage());
             }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function fetch()
    {


        // DB::enableQueryLog();
        $banners  = Banner::with('user')->where('location','right')->where('active','1')->where(function($query){

            $query->where('category_id','0');
            if(isset($this->bindings['catId']) || isset($this->bindings['subCatId'])){
                if(isset($this->bindings['catId'])){

                    $query->orWhere('category_id',$this->bindings['catId']);

                }
                else if(isset($this->bindings['subCatId'])){
                   $query->orWhere('category_id',$this->bindings['subCatId']);
                }
            }


        })->limit(8)->inRandomOrder()->get();


        view()->share('listingBanners', $banners);

        // If Ad Type is filled, then check if the Ad Type exists
        if (request()->filled('type') && request()->get('type') != '') {
            $postTypeId = request()->get('type');
            $cacheId = 'postType.' . $postTypeId . '.' . config('app.locale');
            $postType = Cache::remember($cacheId, $this->cacheExpiration, function () use ($postTypeId) {
                $postType = PostType::query()
                    ->where('translation_of', $postTypeId)
                    ->where('translation_lang', config('app.locale'))
                    ->first();

                return $postType;
            });

            if (empty($postType)) {
                abort(404, t('The requested ad type does not exist.'));
            }
        }

        // Start Search
        $sql = $this->builder() . "\n" . "LIMIT " . (int)$this->sqlCurrLimit . ", " . (int)$this->perPage;
        if(isset($_GET['test']) && $_GET['test']=="pagei"){
            echo $sql."<br></br>";

        }
        $count = $this->countPosts();
        // Count real query ads
        if (request()->filled('type') && request()->get('type') != '') {
            $total = ($count->has(request()->get('type'))) ? $count->get(request()->get('type')) : 0;
        } else {
            $total = $count->get('all');
        }


        foreach ($this->bindings as $key => $value) {

           $sql= str_replace(":".$key, "'".$value."'", $sql);
        }



            // dd($sql);


            //echo $sql;


        // Fetch Query !
        $paginator = self::query($sql, $this->bindings, 0);

        $paginator = DB::select($sql);


        $paginator = new LengthAwarePaginator($paginator, $total, $this->perPage, $this->currentPage);
        $paginator->setPath(Request::url());
        //dd($paginator);


        // Append the Posts 'uri' attribute
        $paginator->getCollection()->transform(function ($post) {
            $post->title = mb_ucfirst($post->title);
            $post->uri = trans('routes.v-post', ['slug' => slugify($post->title), 'id' => $post->id]);
//dd($post);
            $post->related = Post::select('id','title')->where('user_id',$post->user_id)->where('reviewed','1')->where('archived','!=','1')->whereNull('deleted_at')->where('id','<>',$post->id)->limit(3)->with('picture')->inRandomOrder()->get();
        //  dd($post->related);

            return $post;
        });

        if(isset($_GET['test']) && $_GET['test']=="pagei"){
            echo $sql;

            dump( $total = $count->get('all'));

            dump([$total,$this->perPage, $this->currentPage]);
            dd($paginator);
        }



        if(request()->method() == "GET" ){
            $searchHistory = new SearchHistory;
            if(isset($this->bindings['catId'])){
                $searchHistory->category_id = $this->bindings['catId'];
            }
            else if(isset($this->bindings['subCatId'])){
                $searchHistory->category_id = $this->bindings['subCatId'];
            }
            else{
                $searchHistory->category_id = null;
            }

            if(isset($this->city) && $this->city->id>0){
                $searchHistory->city_id = $this->city->id;
            }
            else{
                $searchHistory->city_id = null;
            }
            if(isset($this->keywords)){

                $searchHistory->serach_term  = $this->keywords;
            }
            else{

                $searchHistory->serach_term = null;
            }


            $searchHistory->session_id = (isset($_COOKIE['__cfduid'])?$_COOKIE['__cfduid']:session()->getId());
            $searchHistory->ip_address = request()->ip();
            $searchHistory->count =$paginator->total();


            if(auth()->check()){

                $searchHistory->user_id =  auth()->user()->id;
            }
            else{
                $searchHistory->user_id = null;
            }


            $queryCheck = $searchHistory->user_id.$searchHistory->category_id.$searchHistory->city_id.$searchHistory->serach_term;
            $savedSession = \Session::get($searchHistory->session_id);


            if(!is_array($savedSession)){

                $savedSession=[];
            }





            if(!in_array($queryCheck,$savedSession)){

                $searchHistory->user_agent = request()->header('User-Agent');

                $save_record = true;
                if(preg_match("/bot\//", strtolower($searchHistory->user_agent))){
                    $save_record = false;
                }

                if($save_record){

                    $savedSession[]= $queryCheck;
                    \Session::put($searchHistory->session_id, $savedSession);
                    \Session::save();

                    $searchHistory->save();
                }


            }






        }
       // dd($paginator);
        return ['paginator' => $paginator, 'count' => $count];
    }

    /**
     * @return array
     */
    public function fechAll()
    {


        if (request()->filled('q')) {
            $this->setQuery(request()->get('q'));
        }
        if (request()->filled('c')) {
            if (request()->filled('sc')) {
                $this->setCategory(request()->get('c'), request()->get('sc'));
            } else {
                $this->setCategory(request()->get('c'));
            }
        }



        if (request()->filled('r') && !empty($this->admin) && !request()->filled('l')) {
            $this->setLocationByAdminCode($this->admin->code);
        }









        $this->arrSql->join[] = "INNER JOIN " . DBTool::table('cities') . " as cia ON cia.id=a.city_id";






        if (!empty($this->city)) {
           // $this->arrSql->where[] = " (uf.city_id is null or uf.city_id!='".$this->city->id."') ";
            $this->setLocationByCityCoordinates($this->city->latitude, $this->city->longitude, $this->city->id);

        }







        $this->setRequestFilters();

        // Execute
        return $this->fetch();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function countPosts()
    {
        // Get global where clause
        $where = $wherePostType = $this->arrSql->where;

        // Remove the type with her SQL clause
        if (request()->filled('type')) {
            unset($where['a.post_type_id']);
        }

        // Count all entries
        $sql = "SELECT count(*) as total FROM (" . $this->builder($where) . ") as x";
        $all = self::query($sql, $this->bindings, 0);
        $count['all'] = (isset($all[0])) ? $all[0]->total : 0;

        if(isset($_GET['test']) && $_GET['test']=="pagei"){
            echo $sql."<br></br>";
            dump($this->bindings);
            dump($all);
            dump($count);
        }


        // Get the Post's Types
        $postTypes = PostType::where('translation_lang', config('lang.abbr'))->orderBy('name')->get();

        // Count entries by post type
        if (!empty($postTypes)) {
            foreach ($postTypes as $postType) {
                $wherePostType = array_merge($where, ['a.post_type_id' => ' = ' . $postType->tid]);
                $sqlPostType = "SELECT count(*) as total FROM (" . $this->builder($wherePostType) . ") as x";
                $allByPostType = self::query($sqlPostType, $this->bindings, 0);
                $count[$postType->tid] = (isset($allByPostType[0])) ? $allByPostType[0]->total : 0;
            }
        }

        if(isset($_GET['test']) && $_GET['test']=="pagei"){

            dump($postTypes);
            dd($count);
        }

        return collect($count);
    }

    /**
     * @param array $where
     * @return string
     */
    private function builder($where = [])
    {
        // Set SELECT
        $this->sql->select = 'SELECT  ' . implode(', ', $this->arrSql->select) . ', p.id as py_package_id';

        // Set JOIN
        $this->sql->join = '';
        if (count($this->arrSql->join) > 0) {
            $this->sql->join = "\n" . implode("\n", $this->arrSql->join);
        }



        $arrWhere = ((count($where) > 0) ? $where : $this->arrSql->where);

        if(request()->get('ct')!="company")
        {


            $ipAddr= request()->getClientIp();



            $reader = new Reader(storage_path('database/maxmind/GeoLite2-City.mmdb'));
            $geocity = null;
            try {
                    $geocity = $reader->city($ipAddr);
            } catch (\Exception $e) {

            }

            if(isset($_GET['test_geo']) && $_GET['test_geo']=="ph"){
                if($geocity->city->name){
                    dump($ipAddr);
                    dd($geocity->city->name);
                }
                else{
                    dd($geocity);
                }
            }

           // dd($geocity->city->name);
            if(isset($geocity->city) && $geocity->city->name){

                    $checkCity = City::where('name','like','%'.$geocity->city->name.'%')->get();

                    $geo_cities=[];
                    foreach ($checkCity as $geo_city) {
                        $geo_cities[]=$geo_city->id;
                    }


                    if(count($geo_cities)>0){
                       //  $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('user_filter_locations') . " as uf ON u.id=uf.user_id";
                        $arrWhere[] = " NOT EXISTS(select u.id from " . DBTool::table('user_filter_locations') . " uf where  u.id=uf.user_id and uf.city_id  in (".implode(',',$geo_cities).")) ";

                    }



            }

        }
        // Set WHERE

        $this->sql->where = '';
        if (count($arrWhere) > 0) {
            foreach ($arrWhere as $key => $value) {
                if (is_numeric($key)) {
                    $key = '';
                }
                if ($this->sql->where == '') {
                    $this->sql->where .= "\n" . 'WHERE ' . $key . $value;
                } else {
                    $this->sql->where .= ' AND ' . $key . $value;
                }
            }
        }

//        dd($this->arrSql->groupBy);
        // Set GROUP BY
        $this->sql->groupBy = '';

        if(empty($this->arrSql->groupBy)){

            //$this->arrSql->groupBy[] = "a.user_id";
        }

        if (count($this->arrSql->groupBy) > 0) {
            //$this->sql->groupBy = "\n" . 'GROUP BY ' . implode(', ', $this->arrSql->groupBy);
        }

        // Set HAVING
        $this->sql->having = '';
        if (count($this->arrSql->having) > 0) {
            foreach ($this->arrSql->having as $key => $value) {
                if ($this->sql->having == '') {
                    $this->sql->having .= "\n" . 'HAVING ' . $key . $value;
                } else {
                    $this->sql->having .= ' AND ' . $key . $value;
                }
            }
        }

        // Set ORDER BY
       $this->sql->orderBy = '';
       //$this->sql->orderBy .= "\n" . 'ORDER BY p.lft DESC';
        if (count($this->arrSql->orderBy) > 0) {
            foreach ($this->arrSql->orderBy as $key => $value) {
                if ($this->sql->orderBy == '') {
                    $this->sql->orderBy .= "\n" . 'ORDER BY ' . $key . $value;
                } else {
                    $this->sql->orderBy .= ', ' . $key . $value;
                }
            }

            $this->sql->orderBy .= ', p.lft DESC, rand()';

        }
        else{
            $this->sql->orderBy .= "\n" . 'ORDER BY p.lft DESC, rand()';
        }
/*
        if (count($this->arrSql->orderBy) > 0) {
            if (!in_array('a.created_at', array_keys($this->arrSql->orderBy))) {
                $this->sql->orderBy .= ', a.created_at DESC';
            }
        } else {
            if ($this->sql->orderBy == '') {
                $this->sql->orderBy .= "\n" . 'ORDER BY a.created_at DESC';
            } else {
                $this->sql->orderBy .= ', a.created_at DESC';
            }
        }*/

        // Set Query
        $sql = $this->sql->select . "\n" . "FROM " . DBTool::table($this->table) . " as a" . $this->sql->join . $this->sql->where . $this->sql->groupBy . $this->sql->having . $this->sql->orderBy;

        return $sql;
    }

    /**
     * @param $keywords
     * @return bool
     */
    public function setQuery($keywords)
    {
        if (trim($keywords) == '') {
            return false;
        }

        // Query search SELECT array
        $select = [];

        // Get all keywords in array



        $commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');


        $title = $keywords;

        $keywords = strtolower(str_replace("  "," ",preg_replace('/\b('.implode('|',$commonWords).')\b/','',strtolower($keywords))));



        $words_tab = preg_split('/[\s,\+]+/', $keywords);

       //
        $city = City::where(function($query)use($words_tab){
            foreach ($words_tab as $value) {

                 $query->orWhere('name',$value);
                # code...
            }
        })->first();

        if($city){
            if (($key = array_search(strtolower($city->name), $words_tab)) !== false) {
                unset($words_tab[$key]);
            }
           $this->city = $city;
        }

        $keywords = implode(" ",$words_tab);

        $this->keywords = $keywords;


        //-- If third parameter is set as true, it will check if the column starts with the search
        //-- if then it adds relevance * 30
        //-- this ensures that relevant results will be at top
        $select[] = "(CASE WHEN a.title LIKE :title THEN 300 ELSE 0 END) ";
        $select[] = "(CASE WHEN u.name LIKE :keywords THEN 300 ELSE 0 END) ";
        $select[] = "(CASE WHEN a.description LIKE :keywords THEN 30 ELSE 0 END) ";
        $select[] = "(CASE WHEN a.short_description LIKE :keywords THEN 30 ELSE 0 END) ";
        if (!empty($this->city)) {

            $select[] = "(CASE WHEN cia.name = :city_name THEN 300 ELSE 0 END) ";

            $this->bindings['city_name'] = $this->city->name;
        }



        $this->bindings['keywords'] = '%'.$keywords . '%';
        $this->bindings['title'] = '%'.$title . '%';


        //$removeWords = ['in','']
        foreach ($this->searchable['columns'] as $column => $relevance) {
            //$tmp = [];
            foreach ($words_tab as $key => $word) {
                $tmp = [];
                // Skip short keywords
                if (strlen($word) <= self::$queryLength) {
                    continue;
                }
                // @todo: Find another way
                if (in_array(mb_strtolower($word), $this->banWords)) {
                    continue;
                }
                $tmp[] = $column . " LIKE :word_" . $key;
                $this->bindings['word_' . $key] = '%' . $word . '%';
                $select[] = "(CASE WHEN " . implode(' || ', $tmp) . " THEN " . $relevance . " ELSE 0 END) ";
            }
            /*if (count($tmp) > 0) {
                $select[] = "(CASE WHEN " . implode(' || ', $tmp) . " THEN " . $relevance . " ELSE 0 END) ";
            }*/
        }
        if (count($select) <= 0) {
            return false;
        }

        $this->arrSql->select[] = implode("+\n", $select) . "as relevance";

        // Post category relation
        if (!str_contains(implode(',', $this->arrSql->join), 'categories as c')) {
            $this->arrSql->join[] = "INNER JOIN " . DBTool::table('categories') . " as c ON c.id=a.category_id AND c.active=1";
        }
        // Category parent relation
        if (!str_contains(implode(',', $this->arrSql->join), 'categories as cp')) {
            $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('categories') . " as cp ON cp.id=c.parent_id AND cp.active=1";
        }
        // Category parent relation
        if (!str_contains(implode(',', $this->arrSql->join), 'categories as ccp')) {
            $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('categories') . " as ccp ON ccp.id=cp.parent_id AND ccp.active=1";
        }

        // Search with categories language
        $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('categories') . " as cl ON cl.translation_of=c.id AND cl.translation_lang = :translationLang";
        $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('categories') . " as cpl ON cpl.translation_of=cp.id AND cpl.translation_lang = :translationLang";
        $this->bindings['translationLang'] = config('lang.abbr');

        //-- Selects only the rows that have more than
        //-- the sum of all attributes relevances and divided by count of attributes
        //-- e.i. (20 + 5 + 2) / 4 = 6.75
        $average = array_sum($this->searchable['columns']) / count($this->searchable['columns']);
        $average = Number::toFloat($average);
        if ($this->forceAverage) {
            // Force average
            $average = $this->average;
        }
        $this->arrSql->having['relevance'] = ' >= :average';
        $this->bindings['average'] = $average;
        //$this->arrSql->groupBy[] = "relevance";
        //-- Orders the results by relevance
        $this->arrSql->orderBy['relevance'] = ' DESC';




    }

    /**
     * @param $catId
     * @param null $subCatId
     * @return $this
     */
    public function setCategory($catId, $subCatId = null)
    {
        if (empty($catId)) {
            return $this;
        }

        // Category
        if (empty($subCatId))
        {
            if (!str_contains(implode(',', $this->arrSql->join), 'categories as c')) {
                $this->arrSql->join[] = "INNER JOIN " . DBTool::table('categories') . " as c ON c.id=a.category_id AND c.active=1";
            }
            if (!str_contains(implode(',', $this->arrSql->join), 'categories as cp')) {
                $this->arrSql->join[] = "INNER JOIN " . DBTool::table('categories') . " as cp ON cp.id=c.parent_id AND cp.active=1";
            }

            // Category parent relation
            if (!str_contains(implode(',', $this->arrSql->join), 'categories as ccp')) {
                $this->arrSql->join[] = "LEFT JOIN " . DBTool::table('categories') . " as ccp ON ccp.id=cp.parent_id AND ccp.active=1";
            }

            //$this->arrSql->where['cp.id'] = ' = :catId';
            $this->arrSql->where[':catId'] = ' IN (c.id, cp.id,ccp.id)';

            //$this->arrSql->groupBy[] = "u.id";
             //dd($this->arrSql->groupBy);
            $this->bindings['catId'] = $catId;
        }
        // SubCategory
        else
        {


            if (!str_contains(implode(',', $this->arrSql->join), 'categories')) {
                $this->arrSql->join[] = "INNER JOIN " . DBTool::table('categories') . " as c ON c.id=a.category_id AND c.active=1 AND c.translation_lang = :translationLang";
                $this->bindings['translationLang'] = config('lang.abbr');
            }
            // $this->arrSql->where['a.category_id'] = ' = :subCatId';

            $this->arrSql->where[':catId'] = ' IN (c.id, cp.id)';
            $this->bindings['catId'] = $subCatId;

            // $this->bindings['subCatId'] = $subCatId;
        }

        return $this;
    }

    /**
     * @param $userId
     * @return $this
     */
    public function setUser($userId)
    {
        if (trim($userId) == '') {
            return $this;
        }
        $this->arrSql->where['a.user_id'] = ' = :userId';
        $this->bindings['userId'] = $userId;

        return $this;
    }

    /**
     * @param $tag
     * @return $this
     */
    public function setTag($tag)
    {
        if (trim($tag) == '') {
            return $this;
        }

        $tag = rawurldecode($tag);

        $this->arrSql->where[] = 'FIND_IN_SET(:tag, REPLACE(LOWER(a.tags)," ","") ) > 0';
        $this->bindings['tag'] = mb_strtolower(str_replace(" ","",$tag));

        return $this;
    }

    /**
     * Search including Administrative Division by adminCode
     *
     * @param $adminCode
     * @return $this|Search
     */
    public function setLocationByAdminCode($adminCode)
    {

        dd(config('country.admin_type'));
        if (in_array(config('country.admin_type'), ['1', '2'])) {
            // Get the admin. division table info
            $adminType = config('country.admin_type');
            $adminTable = 'subadmin'.$adminType;
            $adminForeignKey = 'subadmin'.$adminType.'_code';

            // Query
            $this->arrSql->join[] = "INNER JOIN " . DBTool::table('cities') . " as cia ON cia.id=a.city_id";
            $this->arrSql->join[] = "INNER JOIN " . DBTool::table($adminTable) . " as admin ON admin.code=cia." . $adminForeignKey;
            $this->arrSql->where['admin.code'] = ' = :adminCode';
            $this->bindings['adminCode'] = $adminCode;

            return $this;
        }

        return $this;
    }

    /**
     * Search including City by City Coordinates (lat & lon)
     *
     * @param $lat
     * @param $lon
     * @param null $cityId
     * @return $this
     */
    public function setLocationByCityCoordinates($lat, $lon, $cityId = null)
    {
        if ($lat == 0 || $lon == 0) {
            return $this;
        }



        $distanceCalculationFormula = config('larapen.core.distanceCalculationFormula');

        // Use the Cities Standard Searches
        if (!DBTool::checkIfMySQLFunctionExists($distanceCalculationFormula)) {
            return $this->setLocationByCityId($cityId);
        }

        // Use the Cities Extended Searches
        // by using the MySQL Distance Calculation function
        $sql = '(' . $distanceCalculationFormula . '(' . $lat . ', ' . $lon . ', u.lat, u.lon)) as distance';

        $this->arrSql->select[] = $sql;

        $this->arrSql->where[] = ' u.lat is not null';

        if(request()->get('ct')!="company")
        {

            //$this->arrSql->having['distance'] = ' <= :distance';

        }
        //$this->bindings['distance'] = self::$distance;
       // dd($this->arrSql->orderBy);

        $this->arrSql->orderBy['distance'] = ' ASC';


//        $this->arrSql->orderBy['a.created_at'] = ' DESC';

        return $this;
    }

    /**
     * Search including City by City Id
     *
     * @param $cityId
     * @return $this
     */
    public function setLocationByCityId($cityId)
    {
        if (trim($cityId) == '') {
            return $this;
        }

        $this->arrSql->where['a.city_id'] = ' = :cityId';
        $this->bindings['cityId'] = $cityId;

        return $this;
    }

    /**
     * @param $field
     * @return bool
     */
    public function setOrder($field)
    {
        if (!isset($this->orderMapping[$field])) {
            return false;
        }

        // Check essential field
        if ($field == 'relevance' and !str_contains($this->sql->orderBy, 'relevance')) {
            return false;
        }

        $this->arrSql->orderBy[$this->orderMapping[$field]['name']] = ' ' . $this->orderMapping[$field]['order'];
    }

    /**
     * @return $this
     */
    public function setRequestFilters()
    {
        $parameters = Request::all();
        if (count($parameters) == 0) {
            return $this;
        }

        foreach ($parameters as $key => $value) {
      //      dump($key);
            if (!isset($this->filters[$key])) {
                continue;
            }
            if (!is_array($value) and trim($value) == '') {
                continue;
            }

            // Special parameters
            $specParams = [];
            if ($key == 'minPrice') { // Min. Price
                // $this->arrSql->where[$this->filters[$key] . ' >= '] =  $value;
                $this->arrSql->having[$this->filters[$key] . ' >= '] =  $value;
                $specParams[] = $key;
            }
            if ($key == 'ct') { // Min. Price
                $this->arrSql->where[$this->filters[$key] . " like "] =  "'%".$parameters['q']."%'";
               // $this->arrSql->having[$this->filters[$key] . ' = '] =  $value;
                $specParams[] = $key;
                //dd($this->arrSql->having);
            }
            if ($key == 'maxPrice') { // Max. Price
                // $this->arrSql->where[$this->filters[$key] .  ' <= '] = $value;
                $this->arrSql->having[$this->filters[$key] .  ' <= '] = $value;
                $specParams[] = $key;
            }
            if ($key == 'postedDate') { // Date
                $this->arrSql->where[$this->filters[$key]] = ' BETWEEN DATE_SUB(NOW(), INTERVAL :postedDate DAY) AND NOW()';
                $this->bindings['postedDate'] = $value;
                $specParams[] = $key;
            }
            if ($key == 'cf') { // Custom Fields
                if (is_array($value)) {
                    $bindings = [];
                    foreach($value as $fieldId => $postValue) {
                        if (is_array($postValue)) {
                            foreach($postValue as $optionId => $optionValue) {
                                if (is_array($optionValue)) continue;
                                if (!is_array($optionValue) && trim($optionValue) == '') continue;

                                $bindId = $fieldId.$optionId;
                                $alias = 'av' . (int) $bindId; // (int) to prevent SQL injection attack
                                $where = '('.$alias.'.field_id = :fieldId'.$bindId.' AND '.$alias.'.option_id = :optionId'.$bindId.' AND '.$alias.'.value LIKE :value'.$bindId.')';
                                $this->arrSql->join[] = "INNER JOIN " . DBTool::table('post_values') . " as ".$alias." ON a.id=".$alias.".post_id AND " . $where;
                                $bindings['fieldId'.$bindId] = $fieldId;
                                $bindings['optionId'.$bindId] = $optionId;
                                $bindings['value'.$bindId] = $optionValue;
                                $this->bindings += $bindings;
                            }
                        } else {
                            if (trim($postValue) == '') {
                                continue;
                            }

                            $bindId = $fieldId;
                            $alias = 'av' . (int) $bindId; // (int) to prevent SQL injection attack
                            $where = '('.$alias.'.field_id = :fieldId'.$bindId.' AND '.$alias.'.value LIKE :value'.$bindId.')';
                            $this->arrSql->join[] = "INNER JOIN " . DBTool::table('post_values') . " as ".$alias." ON a.id=".$alias.".post_id AND " . $where;
                            $bindings['fieldId'.$bindId] = $fieldId;
                            $bindings['value'.$bindId] = $postValue;
                            $this->bindings += $bindings;
                        }
                    }
                }
                $specParams[] = $key;
            }

            // No-Special parameters
            if (!in_array($key, $specParams)) {
                if (is_array($value)) {
                    $tmpArr = [];
                    foreach($value as $k => $v) {
                        if (is_array($v)) continue;
                        if (!is_array($v) && trim($v) == '') continue;

                        $tmpArr[$k] = $v;
                    }
                    if (!empty($tmpArr)) {
                        $this->arrSql->where[$this->filters[$key]] = ' IN (' . implode(',', $tmpArr) . ')';
                    }
                } else {
                    $this->arrSql->where[$this->filters[$key]] = ' = ' . $value;
                }
            }
        }

        return $this;
    }
}
