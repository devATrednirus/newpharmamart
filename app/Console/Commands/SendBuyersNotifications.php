<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Notifications\BuyerNotification;
use Carbon\Carbon;
use DB;

class SendBuyersNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:buyers-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to buyers to sellers details';

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
        

        
        // $this->last72hours = Carbon::now()->subHour('72');

        $messages = Message::whereNull('message_id')->whereNotNull('sent_at')->where(function($query){

            $query->where('sms_details_sent','pending')->orWhereHas('shared',function($query){
                $query->whereNotNull('sent_at')->where('sms_details_sent','pending');
            });

        })->with('sender')->with('receiver')->orderBy('sent_at','asc')->get();
        
        foreach ($messages as $message) {
            
            $user = $message->sender;
            
            if($user->phone){

                $count=0;
                $contacts = [];
                $sellers = Message::where('message_id',$message->id)->whereNotNull('sent_at')->where('sms_details_sent','pending')->with('receiver')->orderBy('sent_at','asc')->get();

                if($message->sms_details_sent=="pending" ){

                    if($message->to_user_id=="1" ){

                        $message->sms_details_sent="no";
                    }
                    else{

                        $contacts[]= ($count+1).". Company: ".substr($message->receiver->name,0,30)." Person: ".substr($message->receiver->full_name,0,30)." Phone: ".$message->receiver->phone." City: ".substr((($message->receiver->city)?$message->receiver->city->name:'-'),0,30);
                        $count++;
                        $message->sms_details_sent="sent";
                    }


                    $message->save();
                }
                $this->info($message->sms_details_sent.":".count($sellers));


                foreach ($sellers as $seller) {

                    $contacts[]= ($count+1).". Company: ".substr($seller->receiver->name,0,30)." Person: ".substr($seller->receiver->full_name,0,30)." Phone: ".$seller->receiver->phone." City: ".substr((($seller->receiver->city)?$seller->receiver->city->name:'-'),0,30);


                    $seller->sms_details_sent="sent";
                    $seller->save();
                    $count++;
                    
                    if($count==4){
                        break;
                    }
                }

                
                $sendSMS = true;
                
                if(count($contacts)=="1"){

                    $sendSMS = false;    
                }
           

                if(!empty($contacts) && $sendSMS==true){

                    $user->disable_sms_limit =true;
                    $data = $user->notify(new BuyerNotification($user,$contacts));
                }

            }


        }

    
    }
}
