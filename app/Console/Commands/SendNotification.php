<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\Category;
use App\Models\Package;
use App\Notifications\CompanyContacted;
use App\Helpers\Arr;
use App\Helpers\DBTool;
use Illuminate\Support\Facades\DB;
use App\Helpers\Search;
use App\Models\User;
use App\Models\UserMapGroup;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ActiveScope;
class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Notification';

    protected $package_company_count = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //


        $this->action = $this->argument('action');
         
        if(config('settings.mail.auto_distribute_queries')!="1"){
          $this->error('Auto distribution of quick and direct queries is OFF');
          exit;
        }


        
        $this->today = Carbon::now();
        $this->last72hours = Carbon::now()->subHour('72');




        
        dump(date("d-m-Y H:i:s")." Starting");
        

        $packages= Package::select('id','short_name','monthly_leads','share_per_lead','price','daily_send_limit')->where('share_per_lead','>','0')->where('active','1')->orderBy('lft','desc')->get();


        $total_share_lead= Package::where('active','1')->sum('share_per_lead');

        
        //
       //DB::enableQueryLog();
        $messages = Message::where('type','direct')->whereNotNull('from_email')->whereNotNull('city_id')->where(function($query)use($total_share_lead){

          if($this->action=="retry"){

              $query->where('shared_count','<',$total_share_lead)->where(function($query){
                $query->whereBetween('sent_at',[Carbon::yesterday()->format('Y-m-d')." 00:00:00",Carbon::yesterday()->format('Y-m-d')." 23:59:59"]);//->orWhereBetween('updated_at',[Carbon::yesterday()->format('Y-m-d')." 00:00:00",Carbon::yesterday()->format('Y-m-d')." 23:59:59"]);
              });


          }
      	  else{
      		$query->where('is_sent','0');

      	  }

        })->where(function($query){
        	$query->where('post_id','<>','0')->orWhere('company_only','No');
        })->where(function($query){

            $query->where('blocked','0')->orWhere(function($query){

                  $query->where('blocked','1')->where('profession','<>','Student')->whereDate('sent_at','<=',Carbon::now()->subMinutes('3'));
            });
        })->whereNull('message_id')->whereNull('quick_message_id')->whereIn('verified_status',['Verified By Phone','By OTP'])->with('post')->orderBy('id','asc')->get();
        \Config::set('country.code', 'IN');
        //dd(DB::getQueryLog());
        
     
        

        
	 
        foreach ($messages as $message) {
            
              try {
                $this->log = [];



                $log = date("d-m-Y H:i:s")." Sending Message ID:".$message->id;


                $this->log[] = $log;
                $this->info($log);
                $share_count = $total_share_lead;

                $alreadySentMessage = Message::where('message_id',$message->id)->select(DB::raw('GROUP_CONCAT(DISTINCT(to_user_id)) as user_ids'), DB::raw('count(*) as total'))->first();

                $alreadySent=[];
                if($alreadySentMessage->user_ids){
                  $alreadySent = array_merge($alreadySent,explode(",", $alreadySentMessage->user_ids));
                }
                


                $checkOldMessage = Message::where('from_phone',$message->from_phone)->where('to_user_id','<>','1')->where('id','<>',$message->id)->where('message_id','<>',$message->id)->whereNotNull('message_id')->where('email_sent','1')->whereDate('sent_at','>=',$this->last72hours)->select(DB::raw('GROUP_CONCAT(DISTINCT(message_id)) as message_ids'), DB::raw('GROUP_CONCAT(DISTINCT(to_user_id)) as user_ids'),DB::raw('count(*) as total'))->first();
                  

                if($checkOldMessage->user_ids){
                  $alreadySent = array_merge($alreadySent,explode(",", $checkOldMessage->user_ids));
                }

                
                 

                
                $totalSent = 0;
                $total_old_sent = ($alreadySentMessage->total+$checkOldMessage->total);
                $share_count = $share_count-$total_old_sent;
                
                if($message->limit_sent > 0 ){

                  $share_count = $message->limit_sent;
                }
              
                

                 $log = "Already Sent: ".$total_old_sent." ".(($total_old_sent>0 && $checkOldMessage->message_ids)?"(".$checkOldMessage->message_ids.")":"")." share_count:".$share_count;


  //              dump($log);                
                //dump($alreadySent);
    //            dump($checkOldMessage->user_ids);
/*
                $datework = Carbon::createFromDate($date);
                $now = Carbon::now();
                $testdate = $datework->diffInDays($now);

                dd($testdate);*/
                $this->log[] = $log;
                $this->info($log);


                
                if($share_count>0){

                  $totalSent = $this->sendQuery($message,$packages,0,$share_count,$alreadySent);
                }
                else{

                  $log = "Aleady Sent from ".$message->from_phone;

                  $this->log[] = $log;
                  $this->error($log);
                  
                }

                $message->sending_log=$message->sending_log."\n\n\n".implode("\n", $this->log); 
                $message->is_sent='1';
                $message->shared_count= $alreadySentMessage->total+$totalSent; 

                //if(!$alreadySent){
                  
                  $message->shareable_count = $total_share_lead - $message->shared_count;

                //}  

              } catch (\Exception $e) {
                $this->error($e->getMessage());
                dd($e);
                $message->sending_log=$e->getMessage();
                $message->blocked='1';
              }

                
                

               
               $message->save();


         
            

        }

        $this->info("Done");
    }


    public function sendQuery($message,$packages,$totalSent,$total_share_lead,$alreadySent,$tries=0)
    {

        //$type,$limit
       
        

        foreach ($packages as $package) {
           //    dump($alreadySent);
            $featured = $this->getCategorySimilarPosts($message,$package,$alreadySent);


            //dd($featured);

           /* if($featured){

                
            }*/
            //dump($featured);
             
            $pCount = 0;
            foreach ($featured as $post) {
                // New Message
                $new_message = new Message();
                $input = $new_message->getFillable();

                if($totalSent>=$total_share_lead){
                    break ;
                   
                }
                foreach ($input as $value) {
 
                    if(!in_array($value, ['post_id','to_user_id','to_name','to_email','to_phone','parent_id','message_id','is_sent','email_sent','sent_at'])){

                    $new_message->{$value} = $message->{$value};
                    }
                }
                
                
                $user = User::with('userGroups')->find($post->user_id);



                if(!$user){
                     $this->error("Invalid userID: ".$post->user_id);
                  continue;  
                }

                if(in_array($post->user_id,$alreadySent)){
                    $log = "Skipping: ".$user->name." ".$user->id;

                $this->log[] = $log;
                    $this->error($log);
                    continue;
                }

                

                //dump($user->name); 
                $new_message->subject = "Query from Rednirus Mart";
                $new_message->to_user_id = $post->user_id;
                $new_message->to_name = $post->contact_name;
                 
                $new_message->message_id = $message->id;

                $new_message->to_email = $user->email;
                $new_message->to_phone = $user->phone;
                
                
                if(isset($user->sms_to_send)){

                    $new_message->to_phone = $user->sms_to_send;
                }

                if(isset($user->email_to_send)){
                    $new_message->to_email = $user->email_to_send;
                }
                 
                //dd($new_message);
 //               DB::enableQueryLog(); 
                
                $chek_message = Message::where('to_user_id',$new_message->to_user_id)->where('from_phone',$new_message->from_phone)->whereBetween('sent_at',[$this->last72hours,$this->today])->count();


                $chek_today_sent = Message::where('to_user_id',$new_message->to_user_id)->where('type','<>','buy')->where(function($query){
                  $query->whereNotNull('message_id')->orWhere('include_in_share','1');
                })->whereDate('sent_at',$this->today)->count();
                  dump($user);
                // dd($user->subscription->daily_send_limit);
                if($user->subscription->daily_send_limit>"0"){
                   $daily_send_limit = $user->subscription->daily_send_limit;//*1.3;
                }
                else{
                   $daily_send_limit = $package->daily_send_limit;//*1.3;
                }

                $daily_send_limit = round($daily_send_limit);

                                
                if($daily_send_limit>0 && $chek_today_sent>=$daily_send_limit){

                     $this->error("Today Limit reached for: ".$user->name." : ".$chek_today_sent."/".$daily_send_limit);
                     $alreadSent[] =$new_message->to_user_id;
                  //  dump(DB::getQueryLog());
                    continue;
                }

                

                if($chek_message>0){
                 $alreadySent[] =$post->user_id;
                  $log = "Aleady Sent to: ".$user->name;

                $this->log[] = $log;
                  $this->error($log);
                  //  dump(DB::getQueryLog());
                    continue;
                }
 

                $userGroups =[];
                $otherUsers =[];
                
                if(count($user->userGroups)>0){

                    foreach ($user->userGroups as $group) {
                       $userGroups[]= $group->id;
                    }

                    
                    $otherGroupUsers = UserMapGroup::select('user_id')->where('user_id','<>',$post->user_id)->whereIn('group_id',$userGroups)->get();

                    if($otherGroupUsers){
                        foreach ($otherGroupUsers as $otherUser) {
                            $otherUsers[] = $otherUser->user_id;
                        }
                    }
                   

                }
                
                
                $alreadySent = array_merge($alreadySent,$otherUsers);
                $alreadySent[] =$post->user_id;
                
                $chek_other_message = Message::whereIn('to_user_id',$otherUsers)->where('from_phone',$new_message->from_phone)->whereBetween('sent_at',[$this->last72hours,$this->today])->count();


                if($chek_other_message>0){
                  $log = "Aleady Sent to: group member of ".$user->name;

                  $this->log[] = $log;
                  $this->error($log);
                  //  dump(DB::getQueryLog());
                    continue;
                }

               /*
                if($tries>0){
                  $log = "Sending (increased daily limit:".$daily_send_limit." (".$package->daily_send_limit.") ): ".$user->name;
                }
                else{

                  $log = "Sending : ".$user->name;
                }*/

                $log = "Sending : ".$user->name;

              
               // dd("Sending (increased daily limit:".$daily_send_limit." (".$package->daily_send_limit.") ): ".$user->name);              


                $this->log[] = $log;
                $this->info($log);

            
              //  $new_message->to_email = "munish.malhotra07@gmail.com";
                //$new_message->to_email = "gauravsehdev82@gmail.com";
               // $new_message->to_phone = "";
               // dd($new_message);
                $new_message->save();

           
                
//                  dd($log);



                //dump($alreadySent);
                
                $totalSent++;
                $pCount++;

                
              //  $new_message->notify(new CompanyContacted($user, $new_message));
                
                
                
            }

            $log = $package->short_name.": ".$pCount."/".$package->share_per_lead;

                $this->log[] = $log;

            $this->info($log);
             
        }   
          
        if(isset($this->package_company_count[$package->id])){
        	$tries_count = $this->package_company_count[$package->id];
        }
        else{
        	$tries_count = $total_share_lead; 
        }

         

        if($totalSent<$total_share_lead && $tries<=$tries_count){
            
            $tries++;
            return $this->sendQuery($message,$packages,$totalSent,$total_share_lead,$alreadySent,$tries);
        }

        
        
        
        
        return $totalSent;
    }

    /**
     * Get similar Posts (Posts in the same Category)
     *
     * @param $cat
     * @param int $currentPostId
     * @return array|null|\stdClass
     */
    private function getCategorySimilarPosts($message,$package,$alreadySent)
    {

        //dd($package);
       if($message->category_id){
       	  $cat=$message->category_id;
          $currentPostId = '0';
        	
       }
       else if($message->post->category_id){
       	  $cat=$message->post->category_id;
          $currentPostId = $message->post->id;
       }
       else{
        return [];
       }
        


        $limit = $package->share_per_lead;
        $featured = null;
 
         
        $alreadySentIds = implode(",",$alreadySent);
            //(a.verified_email = 1 AND a.verified_phone = 1) AND
        $sql = "SELECT DISTINCT a.id,a.title,a.user_id,a.contact_name,a.email,a.phone, (a.price * 1) as calculatedPrice,u.name as company_name,u.username , p.id as py_package_id,p.lft,p.short_name,CASE WHEN payments.monthly_leads > 0  THEN ( payments.monthly_leads ) ELSE (p.monthly_leads) END as monthly_leads,msg_mtd.total_message,msg_day.daily_message FROM posts as a INNER JOIN categories as c ON c.id=a.category_id AND c.active=1 LEFT JOIN categories as cp ON cp.id=c.parent_id AND cp.active=1 LEFT JOIN users as u ON u.id=a.user_id 

            LEFT JOIN (SELECT MAX(id) max_id, to_user_id FROM messages WHERE (message_id is not null or  quick_message_id is not null)  GROUP BY to_user_id) msg ON msg.to_user_id = a.user_id 

            LEFT JOIN (SELECT count(id) total_message,to_user_id FROM messages WHERE type!='buy' and   (message_id is not null or  quick_message_id is not null or include_in_share='1')  and sent_at >= '".Carbon::now()->startOfMonth()->format('Y-m-d')." 00:00:00'  GROUP BY to_user_id) msg_mtd ON msg_mtd.to_user_id = a.user_id 

            LEFT JOIN (SELECT count(id) daily_message,to_user_id FROM messages WHERE type!='buy' and   (message_id is not null or  quick_message_id is not null or include_in_share='1')  and sent_at >= '".Carbon::now()->format('Y-m-d')." 00:00:00'  GROUP BY to_user_id) msg_day ON msg_day.to_user_id = a.user_id 
            
            LEFT JOIN payments on payments.payment_type = 'Subscription' and payments.active = '1' and  payments.user_id = u.id

            LEFT JOIN packages as p ON p.id=u.package_id WHERE   a.archived != 1 AND a.deleted_at IS NULL AND a.reviewed = '1' AND a.category_id='".$cat."' AND a.id != '".$currentPostId."'   ";
            //$message->from_email
        //
 
	        $sql.=" and ( p.id ='".$package->id."' ) " ;   


	        $total_records = DB::select(DB::raw($sql."GROUP BY a.user_id"));
           	
           	$this->log[] = "Package: ".$package->short_name;
           	$this->log[] = "No of Companies:".count($total_records);

 
           	$this->package_company_count[$package->id] = (int)round(ceil(count($total_records)/(int)$limit)); 

           $main_sql_limit = $sql." and CASE WHEN payments.monthly_leads > 0  THEN (msg_mtd.total_message is null or msg_mtd.total_message >= payments.monthly_leads) ELSE (msg_mtd.total_message is null or msg_mtd.total_message >= p.monthly_leads)  END GROUP BY a.user_id ORDER BY msg.max_id asc,p.lft DESC, a.created_at DESC";  




           //echo $main_sql_limit."\n\n";
           
           $main_sql_records = DB::select(DB::raw($main_sql_limit));


           if($main_sql_records){
           	$record_limit = [];
           	foreach ($main_sql_records as $records) {
           		# code...
           		$record_limit[] = $records->company_name." ".$records->total_message."/".$records->monthly_leads;
           		
           	}

           	$this->log[] ="Skipped by monthly limits";
           	$this->log[] = implode(",", $record_limit);

           }


           if($message->city_id){


	           $main_sql_location_limit = $sql." and (EXISTS(select u.id from  user_filter_locations uf where  u.id=uf.user_id and uf.city_id  = '".$message->city_id."')) GROUP BY a.user_id ORDER BY msg.max_id asc,p.lft DESC, a.created_at DESC";  



	           //echo $main_sql_limit."\n\n";
	           
	           $main_sql_city_records = DB::select(DB::raw($main_sql_location_limit));


	           if($main_sql_city_records){
	           	$record_limit = [];
	           	foreach ($main_sql_city_records as $records) {
	           		# code...
	           		$record_limit[] = $records->company_name." ".$records->total_message."/".$records->monthly_leads;
	           		
	           	}

	           	$this->log[] ="Skipped due to location ".$message->city;
	           	$this->log[] = implode(",", $record_limit);

	           }
	       }


	        $sql.= "".(count($alreadySent)>0?" and u.id not in ($alreadySentIds) ":"");



	        $sql.= " and CASE WHEN payments.monthly_leads > 0  THEN (msg_mtd.total_message is null or msg_mtd.total_message < payments.monthly_leads) ELSE (msg_mtd.total_message is null or msg_mtd.total_message < p.monthly_leads)  END";



            if($message->city_id){
 
              $sql.= " and (NOT EXISTS(select u.id from  user_filter_locations uf where  u.id=uf.user_id and uf.city_id = '".$message->city_id."') or c.exclude_location ='1') ";

 
            }

            //NOT EXISTS(select u.id from " . DBTool::table('user_filter_locations') . " uf where  u.id=uf.user_id and uf.city_id not in (".implode(',',$geo_cities)."))
        /*if($package->price > ""){
            $sql.=" and ( p.id ='".$package->id."' ) " ;   
        } 
        else{
           $sql.=' and p.id is null ';   
        }
        $sql.='GROUP BY a.user_id ORDER BY msg.max_id asc,p.lft DESC, a.created_at DESC LIMIT 0,' . (int)$limit;
        */


	        $sql.='GROUP BY a.user_id ORDER BY msg.max_id asc,p.lft DESC  LIMIT 0,' . (int)$limit;


        //dd($sql);
       /* $bindings = [
            'cat_id' => $cat->id,
            'currentPostId' => $currentPostId,
            'alreadySent'=> implode(",",$alreadySent)
        ];
        */
        // if(count($alreadySent)>3){
           
        // }
     
        $posts = DB::select(DB::raw($sql));
        
       
        // dump($bindings);  
        /*if($posts){
              echo  $sql;
       // dd($bindings);    
        }*/
 
 
         
        return $posts;
    }
}
