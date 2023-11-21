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

class PackageActivated extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	protected $subscription;
	
	// CAUTION: Conflict between the Model Message $message and the Laravel Mail Message (Mailable) objects.
	// NOTE: No problem with Laravel Notification.
	protected $msg;
	
	public function __construct(User $user, Payment $subscription)
	{
		$this->user = $user;
		$this->subscription = $subscription;

		
	}
	
	public function via($notifiable)
	{

		if (!empty($this->user->email)) {
			 
				if (!empty($this->user->sms_to_send) || !empty($this->user->phone)) {
					 
					return ['mail', SmsChannel::class];
					 
				}
				
				return ['mail'];
			 
		} 


	}
	
	public function toMail($notifiable)
	{
		  
		$mailMessage = (new MailMessage)

			->subject('Your '.config('app.name').' Subscription for '.$this->subscription->package->name.' package has been activated')
			->line('Dear '.$this->user->name)
			->bcc('sales@rednirus.in');

			
        if($this->subscription){
        	$mailMessage->line('New Subscription for '.$this->subscription->package->name.' package has been activated');
         
        	if($this->subscription->payment_type=="Subscription"){
        		$mailMessage->line('Start Date : '.$this->subscription->start_date->format('d-M-Y').' End Date:'.$this->subscription->end_date->format('d-M-Y'));

        	}
        	else{
				$mailMessage->line('No of Leads : '.$this->subscription->no_leads);        		
        	}
        }
          
        /*$mailMessage->line('<div> 
                 Please call +91 78765 57373 / email us at info@bizzdigital.com
            </div>')
 */
			$mailMessage->line('<br>')
			->line(trans('mail.post_seller_contacted_content_3'))
			->line('<br>')
			/*->line(trans('mail.post_seller_contacted_content_4'))
			->line('<br>')
			->line(trans('mail.post_seller_contacted_content_5'))
			->line('<br>')*/
			->line(trans('mail.post_seller_contacted_content_6'))
			->line('<br>')
			->line(trans('mail.post_seller_contacted_content_7'))
			->line('<br>');
		
		// Check & get attachment file
		$pathToFile = null;
		if (!empty($this->msg->filename)) {
			$storagePath = Storage::getDriver()->getAdapter()->getPathPrefix();
			$pathToFile = $storagePath . $this->msg->filename;
		}

		return $mailMessage;
	}
	
	public function toNexmo($notifiable)
	{
		return (new NexmoMessage())->content($this->smsMessage())->unicode();
	}
	
	public function toSms($notifiable)
	{
		return $this->smsMessage();
		 
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

		 
		
		return "Dear ".substr($this->user->name,0,30).", Your Rednirus Listing Subscription for ".$this->subscription->package->name." package has been activated. For Help Reach Team Rednirus on @ 9888885364";
		

	}
}
