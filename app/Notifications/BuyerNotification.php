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

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;
use App\Channels\SmsChannel;
use Carbon\Carbon;

class BuyerNotification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	protected $contacts;
	protected $disable_sms_limit = false ;

	// CAUTION: Conflict between the Model Message $message and the Laravel Mail Message (Mailable) objects.
	// NOTE: No problem with Laravel Notification.
	protected $msg;
	
	public function __construct(User $user, $contacts)
	{
		$this->user = $user;
		$this->contacts = $contacts;
		
	}
	
	public function via($notifiable)
	{

		if (!empty($this->user->email)) {
			 
				if (!empty($this->user->sms_to_send) || !empty($this->user->phone)) {
					 
					return [SmsChannel::class];
					 
				}
				
				// return ['mail'];
			 
		} 


	}
	
	public function toMail($notifiable)
	{

	}
	
	public function toNexmo($notifiable)
	{
		return (new NexmoMessage())->content($this->smsMessage())->unicode();
	}
	
	public function toSms($notifiable)
	{	
		// return $this->smsMessage();
		return ['sms_text'=>$this->smsMessage(),'limit_text'=>false];
		 
	}
	public function getDisableSms()
	{	

		return $this->disable_sms_limit;

	}
	public function getTo($notifiable)
	{

		if (!empty($this->user->sms_to_send)) {
					 
			return str_replace("+91","",$this->user->sms_to_send); 
			 
		}
		else{

		 
			return str_replace("+91","",$this->user->phone); 
		}
	}
	
	protected function smsMessage()
	{

	
		$sms = "Thanks for Your Query with ednirus Martizz Digital, Your details are shared with following companies:- ";

        $sms.=implode("\n", $this->contacts);
        $sms.=" for Help Call @ 9306853498";

        
		return $sms;

	}
}
