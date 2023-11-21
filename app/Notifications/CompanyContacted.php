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

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Channels\SmsChannel;

class CompanyContacted extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	
	// CAUTION: Conflict between the Model Message $message and the Laravel Mail Message (Mailable) objects.
	// NOTE: No problem with Laravel Notification.
	protected $msg;
	
	public function __construct(User $user, Message $msg)
	{
		$this->user = $user;
		$this->msg = $msg;
	}
	
	public function via($notifiable)
	{

		if(config('app.env')=="development"){

            		return[];    
        	}

		if($this->msg->from_email==null){
			return[];
		}
		
 
		if (!empty($this->user->email)) {
			 
				if (!empty($this->msg->to_phone)) {
					 
					return ['mail', SmsChannel::class];
					 
				}
				
				return ['mail'];
			 
		} else {
			return [SmsChannel::class];
		}
	}
	
	public function toMail($notifiable)
	{
		 
		
 
		$mailMessage = (new MailMessage)
			->replyTo($this->msg->from_email, $this->msg->from_name)
			->subject(trans('mail.post_seller_contacted_title', [
				'title'   => $this->user->name,
				'appName' => config('app.name'),
			]))
			->line('Dear '.$this->user->name)
			->line('<center>
               <p></p>
               <h3 style="text-align:center;background: #dc0002;color: #fff;padding: 7px 20px;display: inline-block;border-radius: 50px;font-weight: normal;">Enquiry through Rednirus Mart</h3>
               <p></p>
            </center>')
            ->line('<div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer\'s Contact Details</div>

            <table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                <tbody>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Name</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->from_name.'</td>
                    </tr> 
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>E-mail</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->from_email.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Mobile</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->from_phone.'</td>
                    </tr>
                     
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>City</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->city.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Pincode</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->pincode.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Address</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->address.'</td>
                    </tr>
                    
                </tbody>
            </table>')
            ->line('<div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer is looking for "'.$this->msg->looking_for.'"</div>
            <table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;">
                <tbody>
                    <tr>
                        <td colspan="3" style="padding:4px 0;" valign="middle">'.$this->msg->message.'</td>
                    </tr>

                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Your Franchise Location</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->location.'</td>
                    </tr>
                    
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Do You Have Drugs License?</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->drugs_license.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Do You Have GST Number?</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->have_gst_number.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Select purchase period</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->purchase_period.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Select Call Back Time</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->call_back_time.'</td>
                    </tr>
                   <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Profession</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->profession.'</td>
                    </tr>
                   <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Minimum Investment</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->minimum_investment.'</td>
                    </tr>
                    
                     <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Any specific query</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->search_term.'</td>
                    </tr>

                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Verified By</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->getVerifiedStatusHtml().'</td>
                    </tr>

                </tbody>
            </table>')
            ->line('<div> 
                <a style="display:inline-block; background:#00bacb; color:#fff; border-radius:50px;padding: 12px 17px;font-size: 15px;text-decoration: none; margin:15px 0 20px 0; letter-spacing: 1px;" href="#">Reply To This Message</a>
            </div>')

// 		//	dd($this->msg);
// ->line(nl2br($this->msg->message))
// 			//dd($mailMessage);
// 			->line(nl2br($this->msg->message))
// 			->line(trans('mail.post_seller_contacted_content_1', [
// 				'name'    => $this->msg->from_name,
// 				'email'   => $this->msg->from_email,
// 				'phone'   => $this->msg->from_phone,
// 				'location'    => $this->msg->location,
// 				'address'    => $this->msg->address,
// 				'city'    => $this->msg->city,
// 				'pincode'    => $this->msg->pincode,
// 				'drugs_license'    => $this->msg->drugs_license,
// 				'have_gst_number'    => $this->msg->have_gst_number,
// 				'minimum_investment'    => $this->msg->minimum_investment,
// 				'purchase_period'    => $this->msg->purchase_period,
// 				'call_back_time'    => $this->msg->call_back_time,
// 				'profession'    => $this->msg->profession,
// 			]))
			 
			->line('<br>')
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
		

		//dd($mailMessage );
		// Attachment
		if (!empty($pathToFile) && file_exists($pathToFile)) {
			return $mailMessage->attach($pathToFile);
		} else {
			return $mailMessage;
		}
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
		 
		return str_replace("+91","",$this->msg->to_phone); 
	}
	
	protected function smsMessage()
	{

		 
	//	return 'Dear '.$this->user->name.",\n\n".$this->msg->from_name."(".$this->msg->from_phone." : ".$this->msg->getVerifiedStatusHtml().") enquired for location ".$this->msg->location.":\n".$this->msg->message;i
		$message = "Query from Rednirus\n".'Dear '.substr($this->user->name,0,30).", ".substr($this->msg->from_name,0,20).", ".$this->msg->from_phone.", enquired for location ".$this->msg->location.", ".substr($this->msg->message,0,30);

		if(strlen($message)>179){
			$message = substr($message,0,179);
		}

		$message.=",";

		return $message;

	}
}
