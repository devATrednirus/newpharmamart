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

namespace App\Http\Controllers\Account;

use App\Http\Requests\ReplyMessageRequest;
use App\Models\User;
use App\Models\Message;
use App\Models\QuickMessage;
use App\Models\Payment;
use App\Notifications\ReplySent;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Jenssegers\Date\Date;
use App\Helpers\Arr;
use App\Exports\MessageExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use DB;

class BuyLeadsController extends AccountBaseController
{
	private $perPage = 10;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;
	}
	
	/**
	 * Conversations List
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		
		// Set the Page Path
		view()->share('pagePath', 'buy-leads');
		view()->share('pagePathSub', 'buy-new-leads');

		
		// Get the Conversations
		
		$data['conversations'] = $this->buyleads->paginate($this->perPage);
		 
		$data['user'] = User::with('package')->find(auth()->user()->id);
		//dd($data['user']);
		// Meta Tags
		MetaTag::set('title','Buy Leads');
		MetaTag::set('description', 'Buy Leads');
		
		$index= 0;
		$currentDate = Date::now()->startOfMonth();

		if (request()->get('index') != '') {

			$index= (int)request()->get('index');	
			if($index>0){
			$currentDate->subMonths($index);		

			}
		}


		$endDate = clone $currentDate;

		$endDate->endOfMonth();
		$data['date_range'] = $currentDate->format('d-M-Y')." to ".$endDate->format('d-M-Y');

		 
		$data['date_index'] =$index;
		// Get latest entries charts
		$statDayNumber = $currentDate->diff($endDate)->days+1;

		 

		return view('account.buy-leads', $data);
	}


	public function purchased()
	{
		$data = [];
		
		// Set the Page Path
		view()->share('pagePath', 'buy-leads');
		view()->share('pagePathSub', 'buy-my-leads');

		
		// Get the Conversations
		
		$data['conversations'] = $this->buyleads->paginate($this->perPage);
		 
		$data['user'] = User::with('package')->find(auth()->user()->id);
		//dd($data['user']);
		// Meta Tags
		MetaTag::set('title','Purchased Leads');
		MetaTag::set('description', 'Purchased Leads');
		
		$index= 0;
		$currentDate = Date::now()->startOfMonth();

		if (request()->get('index') != '') {

			$index= (int)request()->get('index');	
			if($index>0){
			$currentDate->subMonths($index);		

			}
		}


		$endDate = clone $currentDate;

		$endDate->endOfMonth();
		$data['date_range'] = $currentDate->format('d-M-Y')." to ".$endDate->format('d-M-Y');

		 
		$data['date_index'] =$index;
		// Get latest entries charts
		$statDayNumber = $currentDate->diff($endDate)->days+1;

		 

		return view('account.buy-leads', $data);
	}
	
	/**
	 * @param int $statDayNumber
	 * @return array
	 */
	private function getLatestMessageChart($statDayNumber = 30,$currentDate)
	{
		// Init.
		$statDayNumber = (is_numeric($statDayNumber)) ? $statDayNumber : 30;
		 
		
		$stats = [];
		$total = 0;
		for ($i = 1; $i <= $statDayNumber; $i++) {
			$dateObj = ($i == 1) ? $currentDate : $currentDate->addDay();
			$date = $dateObj->toDateString();
			
			// Ads Stats
			//DB::enableQueryLog();
			$countCompanyMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where('blocked','0')
				->where('to_user_id',auth()->user()->id)
				//->whereNull('message_id')->whereNull('quick_message_id')
				->count();



			/*$quickQuery = QuickMessage::select(DB::raw("id, name as from_name, query as message, created_at,sent_at , 'Quick query' as subject,'query' as type"))->where('user_id',auth()->user()->id)->where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
             
            ->whereNotNull('drugs_license');


        

        
			$countCompanyMessages =Message::select('id','from_name','message','created_at','sent_at','subject',DB::raw("'message' as type"))->with('latestReply')->with('post')
			// ->whereHas('post', function($query) {
			// 	$query->currentCountry();
			// })
			->where('sent_at', '>=', $date)
			->where('sent_at', '<=', $date . ' 23:59:59')
			->byUserId(auth()->user()->id)
			->where('parent_id', 0)
            ->whereNull('message_id')->whereNull('quick_message_id')
		
			->count();*/
 			//dd(DB::getQueryLog());
			
			$stats['messages'][$i]['y'] = mb_ucfirst($dateObj->formatLocalized('%b %d'));
			$stats['messages'][$i]['message'] = $countCompanyMessages; 

			$total+=$countCompanyMessages; 

			
		}

		
		
		//$stats['messages'] = array_reverse($stats['messages'], true);


		
		$data = json_encode(array_values($stats['messages']), JSON_NUMERIC_CHECK);
		
		$boxData = [
			'title' => 'Queries',
			'data'  => $data,
			'total'  => $total,
		];
		$boxData = Arr::toObject($boxData);
		  
		return $boxData;
	}
	/**
	 * Conversation Messages List
	 *
	 * @param $conversationId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function messages($conversationId)
	{

		if(!request()->ajax()){

			return redirect(config('app.locale') . '/account/buy-leads');
		}

		$data = [];
		
		// Set the Page Path
		view()->share('pagePath', 'buy-leads');
		view()->share('pagePathSub', 'buy-new-leads');
		view()->share('buy_leads', $this->buy_leads);
		


		$conversation = $this->buyleads->where('id', $conversationId)->first();


		view()->share('conversation', $conversation);

		
		// Meta Tags
		MetaTag::set('title', t('Messages Received'));
		MetaTag::set('description', t('Messages Received on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		return view('account.buy_lead_messages', $data);
	}

	/**
	 * Conversation Messages List
	 *
	 * @param $conversationId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function buy($conversationId)
	{
		$data = [];
	
		 


		$conversation = $this->buyleads->where('id', $conversationId)->first();

		 
		if(!$conversation){
			echo '<div  style="padding:30px; min-height:100px; color:red; font-size:16px">Invalid request</div>';
			 exit;
		}

		$user = auth()->user();

		$check_message = Message::where('type','buy')->where('message_id',$conversationId)->where('to_user_id',$user->id)->first();
		

		if($check_message){
			echo '<div  style="padding:30px; min-height:100px; color:red; font-size:16px">Already buyed</div>';
			 exit;
		}

		$to = \Carbon\Carbon::now();
		$from = $conversation->created_at;
		$diff_in_days = $to->diffInDays($from);

		$is_old = false;
		if($this->old_buy_leads && $diff_in_days>=$this->old_buy_leads->package->duration){

			$this->buy_leads = 1; 
			$is_old = true;

		}


		if($this->buy_leads<=0){
			
			 echo '<div  style="padding:30px; min-height:100px; color:red; font-size:16px">You don\'t have credits to buy this lead</div><a class="btn btn-success" href="'.lurl('/user/buy-leads').'"><i class="fa fa-pencil-square-o"></i> Get more Buy Leads</a>';
			 exit;
			
		}

		

		 
		$new_message = new Message();
        $input = $new_message->getFillable();
	

        foreach ($input as $value) {

            if(!in_array($value, ['post_id','to_user_id','to_name','to_email','to_phone','parent_id','message_id','is_sent','email_sent','sent_at','sending_log'])){

                $new_message->{$value} = $conversation->{$value};
            }
        }

        $new_message->type = 'buy';

        $new_message->subject = "Quick Query from Rednirus Mart";
       	
       

       	 

        $new_message->to_user_id = $user->id;
        $new_message->to_name = $user->name;
         
        $new_message->message_id = $conversation->id;


        $new_message->to_email = $user->email;
        $new_message->to_phone = $user->phone;
       

       

        
        
        if(isset($user->sms_to_send)){

            $new_message->to_phone = $user->sms_to_send;
        }

        if(isset($user->email_to_send)){
            $new_message->to_email = $user->email_to_send;
        }

 

        $buy_lead_pack = Payment::where('user_id', auth()->user()->id)->where('payment_type','Buy-Leads')->where('active','1')->where('remaining','>','0')->whereHas('package', function($query)use($is_old){

            if($is_old==true){
        		 
		        $query->where('duration','<>','0');
		        
        	}
        	else{

        		$query->where('duration','0');
        	}

        })->orderBy('id','asc')->first();

        
        $buy_lead_pack->remaining = $buy_lead_pack->remaining-1;
        

        //dd($new_message);
        $new_message->save();
        $buy_lead_pack->save();

 
        return redirect(config('app.locale') . '/account/conversations/' . $new_message->id . '/buy-messages');
      


	}
	
	/**
	 * @param $conversationId
	 * @param ReplyMessageRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function reply($conversationId, ReplyMessageRequest $request)
	{
		// Get Conversation
		$conversation = Message::findOrFail($conversationId);
		
		// Get Recipient Data
		if ($conversation->from_user_id != auth()->user()->id) {
			$toUserId = $conversation->from_user_id;
			$toName = $conversation->from_name;
			$toEmail = $conversation->from_email;
			$toPhone = $conversation->from_phone;
		} else {
			$toUserId = $conversation->to_user_id;
			$toName = $conversation->to_name;
			$toEmail = $conversation->to_email;
			$toPhone = $conversation->to_phone;
		}
		
		// Don't reply to deleted (or non exiting) users
		if (config('settings.single.guests_can_post_ads') != 1 && config('settings.single.guests_can_contact_ads_authors') != 1) {
			if (User::where('id', $toUserId)->count() <= 0) {
				flash(t("This user no longer exists.") . ' ' . t("Maybe the user's account has been disabled or deleted."))->error();
				return back();
			}
		}
		
		// New Message
		$message = new Message();
		$input = $request->only($message->getFillable());
		foreach ($input as $key => $value) {
			$message->{$key} = $value;
		}
		
		$message->post_id = $conversation->post->id;
		$message->parent_id = $conversation->id;
		$message->from_user_id = auth()->user()->id;
		$message->from_name = auth()->user()->name;
		$message->from_email = auth()->user()->email;
		$message->from_phone = auth()->user()->phone;
		$message->to_user_id = $toUserId;
		$message->to_name = $toName;
		$message->to_email = $toEmail;
		$message->to_phone = $toPhone;
		$message->subject = 'RE: ' . $conversation->subject;
		
		$attr = ['slug' => slugify($conversation->post->title), 'id' => $conversation->post->id];
		$message->message = $request->input('message')
			. '<br><br>'
			. t('Related to the ad')
			. ': <a href="' . lurl($conversation->post->uri, $attr) . '">' . t('Click here to see') . '</a>';
		
		// Save
		$message->save();
		
		// Save and Send user's resume
		if ($request->hasFile('filename')) {
			$message->filename = $request->file('filename');
			$message->save();
		}
		
		// Mark the Conversation as Unread
		if ($conversation->is_read != 0) {
			$conversation->is_read = 0;
			$conversation->save();
		}
		
		// Send Reply Email
		try {
			$conversation->notify(new ReplySent($message));
			flash(t("Your reply has been sent. Thank you!"))->success();
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
		}
		
		return back();
	}
	
	/**
	 * Delete Conversation
	 *
	 * @param null $conversationId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($conversationId = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($conversationId) && $conversationId <= 0) {
				$ids = [];
			} else {
				$ids[] = $conversationId;
			}
		}
		
		// Delete
		$nb = 0;
		foreach ($ids as $item) {
			// Get the conversation
			$message = Message::where('id', $item)
			
				->byUserId(auth()->user()->id)
				->first();
			
			if (!empty($message)) {
				if (empty($message->deleted_by)) {
					// Delete the Entry for current user
					$message->deleted_by = auth()->user()->id;
					$message->save();
					$nb = 1;
				} else {
					// If the 2nd user delete the Entry,
					// Delete the Entry (definitely)
					if ($message->deleted_by != auth()->user()->id) {
						$nb = $message->delete();
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
		} else {
			$count = count($ids);
			if ($count > 1) {
				flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
			} else {
				flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
			}
		}
		
		return back();
	}
	
	/**
	 * Delete Message
	 *
	 * @param $conversationId
	 * @param null $messageId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroyMessages($conversationId, $messageId = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($messageId) && $messageId <= 0) {
				$ids = [];
			} else {
				$ids[] = $messageId;
			}
		}
		
		// Delete
		$nb = 0;
		foreach ($ids as $item) {
			// Don't delete the main conversation
			if ($item == $conversationId) {
				continue;
			}
			
			// Get the message
			$message = Message::where('parent_id', $conversationId)->where('id', $item)
				->byUserId(auth()->user()->id)
				->first();
			
			if (!empty($message)) {
				if (empty($message->deleted_by)) {
					// Delete the Entry for current user
					$message->deleted_by = auth()->user()->id;
					$message->save();
					$nb = 1;
				} else {
					// If the 2nd user delete the Entry,
					// Delete the Entry (definitely)
					if ($message->deleted_by != auth()->user()->id) {
						$nb = $message->delete();
					}
				}
			}
		}
		
		// Confirmation
		if ($nb == 0) {
			flash(t("No deletion is done. Please try again."))->error();
		} else {
			$count = count($ids);
			if ($count > 1) {
				flash(t("x :entities has been deleted successfully.", ['entities' => t('messages'), 'count' => $count]))->success();
			} else {
				flash(t("1 :entity has been deleted successfully.", ['entity' => t('message')]))->success();
			}
		}
		
		return back();
	}
}
