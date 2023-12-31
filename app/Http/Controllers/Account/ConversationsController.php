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
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use App\Models\Message;
use App\Models\QuickMessage;
use App\Notifications\ReplySent;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Jenssegers\Date\Date;
use App\Helpers\Arr;
use App\Exports\MessageExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use DB;

class ConversationsController extends AccountBaseController
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

		$data['user'] = User::with('package')->find(auth()->user()->id);

		if(!is_array($data['user']->params)){
			$data['user']->params = ['buy_leads_filters'=>[]];
		}



		//if(\Route::currentRouteName()=="company_name"){$fullUrl = request()->getRequestUri();
		$routeName = \Route::currentRouteName();
		
		if($routeName=="conversations"){
			$data['conversations'] = $this->conversations->paginate($this->perPage);
			MetaTag::set('title', t('Conversations Received'));
			MetaTag::set('description', t('Conversations Received on :app_name', ['app_name' => config('settings.app.app_name')]));
		}
		else{
			
			$data['conversations'] = $this->buyleads->where(function($query)use($data){

				$params = $data['user']->params;
				if(request()->get('category_id')){
					// dd(request()->get('category_id'));
					
					$params['buy_leads_filters']= request()->get('category_id');


				}
				else if(request()->get('action')=="filter"){

					$params['buy_leads_filters']=[];					
				}

				$data['user']->params = $params;

				if($data['user']->isDirty()){

					$data['user']->save();
				}

				if(!empty(request()->get('category_id'))){

					$query->whereIn('category_id', request()->get('category_id'));
				}
				
				if(request()->get('keyword')){

					$query->where(function($query){

						$query->where('message', 'like', '%'.request()->get('keyword').'%')->orWhere('looking_for', 'like', '%'.request()->get('keyword').'%');
					});
				}
				
			})->paginate($this->perPage)->appends(request()->except('page'));
			

			MetaTag::set('title','Buy Leads');
			MetaTag::set('description', 'Buy Leads');


			$data['category_id'] = request()->get('category_id')?request()->get('category_id'):array();
			$data['categories'] = Category::trans()->with([
					'children' => function ($query) {
						$query->trans()->orderBy('name');
					},
				])->whereHas('children.messages', function($query) {
				 	$query ->where('parent_id', '0')
		            // ->where('type','quick')
		            ->where('is_sent', '1')
		            ->where('shareable_count','>', '0')
		            ->whereNull('message_id')
		            ->whereDoesntHave("buy", function($subQuery){
		                $subQuery->where("to_user_id", "=", auth()->user()->id);
		            });
				})->orderBy('name')->get();
			

			view()->share('categories', $data['categories']);
		}
		
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		
		// Get the Conversations

		

		//dd($data['conversations']);
		
		//dd($data['user']);
		// Meta Tags
		
		
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


			 
		if($routeName=="conversations" && request()->get('action')=="download"){
			if(app('impersonate')->isImpersonating() && app('impersonate')->getImpersonatorId()!="1"){

					return redirect(config('app.locale') . '/account/conversations');
			}
 
 		$countCompanyMessages = Message::select('from_name','from_email','from_phone','message','location','address','city','pincode','drugs_license','have_gst_number','minimum_investment','purchase_period','call_back_time','profession','sent_at')->where('blocked','0')->whereBetween('sent_at',[$currentDate,$endDate])
			 ->byUserId(auth()->user()->id)->orderBy('sent_at','asc')->get();


 
		$csvExporter = new \Laracsv\Export();
		$csvExporter->build($countCompanyMessages, ['from_name','from_email','from_phone','message','location','address','city','pincode','drugs_license','have_gst_number','minimum_investment','purchase_period','call_back_time','profession','sent_at'])->download('Query-Export-'.Str::slug($data['date_range'],'-').'.csv');

		exit; 

		}

		 
		$data['date_index'] =$index;
		// Get latest entries charts
		$statDayNumber = $currentDate->diff($endDate)->days+1;

		
		$data['latestMessageChart'] = $this->getLatestMessageChart($statDayNumber,clone $currentDate);

		if($routeName=="conversations"){


			//dd($data['latestMessageChart']);

			return view('account.conversations', $data);
		}
		else{

			

			return view('account.buy-leads', $data);
		}
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
				->where('type','<>','buy')
				->where('to_user_id',auth()->user()->id)
				//->whereNull('message_id')->whereNull('quick_message_id')
				->count();

			$countCompanyBuyMessages = Message::where('sent_at', '>=', $date)
				->where('sent_at', '<=', $date . ' 23:59:59')
				->where('blocked','0')
				->where('type','=','buy')
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
			$stats['messages'][$i]['buy'] = $countCompanyBuyMessages;



			$total+=$countCompanyMessages+$countCompanyBuyMessages; 

			
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
		$data = [];


		$routeName = \Route::currentRouteName();

		
		view()->share('routeName', $routeName);
		// Set the Page Path
		view()->share('pagePath', 'conversations');
		


		$conversation = Message::where('id', $conversationId)
			->byUserId(auth()->user()->id)
			->where('blocked','0')
			->firstOrFail();


		$next = Message::where('sent_at','>', $conversation->sent_at)
			->byUserId(auth()->user()->id)
			->where('blocked','0')
			->orderBy('sent_at','asc')
			->first();
		
		$previous = Message::where('sent_at','<', $conversation->sent_at)
			->byUserId(auth()->user()->id)
			->where('blocked','0')
			->orderBy('sent_at','desc')
			->first();
		
	 
		view()->share('conversation', $conversation);
		view()->share('previous', $previous);
		view()->share('next', $next);
		
		// Get the Conversation's Messages
		$data['messages'] = Message::where('parent_id', $conversation->id)
			->byUserId(auth()->user()->id)
			->where('blocked','0')
			->orderByDesc('id');
		$data['countMessages'] = $data['messages']->count();
		$data['messages'] = $data['messages']->paginate($this->perPage);
		
		//if(request()->type!="query"){
			// Mark the Conversation as Read
			if ($conversation->is_read != 1) {
				if ($data['countMessages'] > 0) {
					// Check if the latest Message is from the current logged user
					if ($data['messages']->has(0)) {
						$latestMessage = $data['messages']->get(0);
						if ($latestMessage->from_user_id != auth()->user()->id) {
							$conversation->is_read = 1;
							$conversation->save();
						}
					}
				} else {
					if ($conversation->from_user_id != auth()->user()->id) {
						$conversation->is_read = 1;
						$conversation->save();
					}
				}
			}
		//}
		
		// Meta Tags
		MetaTag::set('title', t('Messages Received'));
		MetaTag::set('description', t('Messages Received on :app_name', ['app_name' => config('settings.app.app_name')]));
		
		return view('account.messages', $data);
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
