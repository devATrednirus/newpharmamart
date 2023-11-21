<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Package;
use App\Models\PackageHistory;
use App\Models\Payment;
use App\Notifications\PackageExpiryReminder;
use App\Notifications\PackageRenewReminder;
use Carbon\Carbon;
use DB;
class PackageExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:Expiry {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Package Expiry';

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


     if($this->argument('type')=="reminders"){

    

        
        $this->sendRenewReminders('7');
    
        foreach (["1","2","7","15","30","45","60"] as $value) {
            
            $this->sendReminders($value);
        }            

     }
     else{

        $this->updateToFreePackage();
     }
      
    }

    public function updateToFreePackage()
    {


        $free_pack = Package::where('short_name','Free')->first();

        $users = User::where('user_type_id','1')->where('id','<>','1')->where('package_id','<>','0')->where(function($q){

            //$q->where('id','64');
        })->orderBY('id','asc')->get();

        foreach ($users as $user) {
            
            if($user->package->price > '0'){
                

                if(!$user->subscription || Carbon::now()->gte($user->subscription->end_date)){
                    
                    if($user->subscription){
                        $payment = $user->subscription;
                    }
                    else{
                        
                        $payment = Payment::where('payment_type','Subscription')->where('active','3')->where('user_id',$user->id)->orderby('id','desc')->first();

                    } 

                    $this->error($user->name." Expired");
		  
                    
                    $user->package_id = $free_pack->id;
                    if($free_pack){
                        
                        $user->package_start_date = null;
                        $user->package_end_date = null;

                        
                    }

                  $payment->active= '14';

                    $payment->save();
		            $user->save();
		       
                }
/*                else{

                    $user->package_start_date = $user->subscription->start_date;
                    $user->package_end_date = $user->subscription->end_date;
                    
                    $this->info($user->name." Active");
	 	    $user->save();
                }
*/

            }
        }


    }

    public function sendReminders($days)
    {
        # code...
  
         $payments = Payment::where('payment_type','Subscription')->where('active','1')->with('user')->where('end_date',Carbon::now()->addDay($days)->format('Y-m-d'))->orderby('end_date','asc')->get();
    
     
        foreach ($payments as $payment) {

            
            $user = $payment->user;
            $data = $user->notify(new PackageExpiryReminder($user,$payment,$days));


 
        }
        //
    }


    public function sendRenewReminders($days)
    {
        # code...
    
        $already_sent = [];
        $payments = Payment::where('payment_type','Subscription')->where('active','3')->with('user')->doesnthave('user.subscription')->where('end_date','>=',Carbon::now()->subDay($days)->format('Y-m-d'))->where('end_date','<',Carbon::now()->format('Y-m-d'))->orderby('end_date','desc')->get();
    
     
        foreach ($payments as $payment) {

            if(!in_array($payment->user_id,$already_sent)){
                $this->info($payment->user->name." ".$payment->end_date);


                
                $user = $payment->user;
                $data = $user->notify(new PackageRenewReminder($user,$payment));

                $already_sent[] = $payment->user_id;
            }
            else{

                $this->error($payment->user->name." ".$payment->end_date);
            }
            
            
        }
 
    }

 
}
