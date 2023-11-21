<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Models\Payment;
use App\Notifications\BuyLeadAlerts;
use Carbon\Carbon;
use DB;
class BuyLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:buyleads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Buy Lead alerts';

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

        
        $this->last72hours = Carbon::now()->subHour('72');

        $payments = Payment::where('active','1')->with('user')->groupBy('user_id')->get();
    
        
        foreach ($payments as $payment) {

            if($payment->user){

                $user = $payment->user;

                if($user->buy_leads_alerts=="Yes"){
                    //and m.type='quick' and m.parent_id='0' and m.is_sent='1'
                    $sql = "select m.*,c.name as cat_name ,p.name as parent_cat_name from messages m INNER JOIN categories as c ON c.id=m.category_id INNER JOIN categories as p ON p.id=c.parent_id AND c.active=1 where m.shareable_count > '0'  and m.message_id is null and m.sent_at > '".$this->last72hours->format('Y-m-d H:i:s')."'  and category_id in (select DISTINCT category_id from posts where category_id is not null and user_id = '".$payment->user_id."' )  and NOT EXISTS(select m.id from  user_filter_locations uf where uf.user_id = '".$payment->user_id."' and uf.city_id = m.city_id) and NOT EXISTS(select m.id from  messages ms where ms.to_user_id = '".$payment->user_id."' and ms.from_user_id = m.from_user_id and ms.sent_at > '".$this->last72hours->format('Y-m-d H:i:s')."') "; 

                    $sql.='ORDER BY m.id DESC  LIMIT 0,10' ;
                 
                    $messages = DB::select(DB::raw($sql));
                
                    if(count($messages)>"0"){
    		            
                        

                        if($user->last_buy_lead != $messages[0]->id){

                            $this->info($payment->user->name);
                            
                            $user->last_buy_lead = $messages[0]->id;
                            $user->save();
                            
                            $data = $user->notify(new BuyLeadAlerts($user,$messages));
                        }
                        
                    }
                } 

            }


        }

    }
 
}
