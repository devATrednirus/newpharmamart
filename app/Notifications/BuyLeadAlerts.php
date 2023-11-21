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

class BuyLeadAlerts extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	protected $messages;
	
	// CAUTION: Conflict between the Model Message $message and the Laravel Mail Message (Mailable) objects.
	// NOTE: No problem with Laravel Notification.
	protected $msg;
	
	public function __construct(User $user, $messages)
	{
		$this->user = $user;
		$this->messages = $messages;

		
	}
	
	public function via($notifiable)
	{

		if (!empty($this->user->email)) {
			 
				if (!empty($this->user->sms_to_send) || !empty($this->user->phone)) {
					 
					//return ['mail', SmsChannel::class];
					 
				}
				
				return ['mail'];
			 
		} 


	}
	
	public function toMail($notifiable)
	{
		 
		
 	
		$mailMessage = (new MailMessage)

			->subject(config('app.name').' Buy Lead Alert')
			->line('Dear '.$this->user->name);

		$html='';
		foreach ($this->messages as $message) {

			$sent_at = Carbon::parse($message->sent_at)->diffForHumans();;
			 
			//.' in '.$message->parent_cat_name
			$html.= '<table cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #d8d8d8;width: 100%;font-family: arial, sans-serif; margin-bottom:10px" class="tall"><tbody><tr>
          <td style="width: 100%;" colspan="3"><table cellpadding="0" cellspacing="0" border="0" style="padding: 05px 0">
              <tbody><tr>
                <td align="left" width="492px"><span style="font-size: 17px;color: #161616;font-size: 17px;font-weight: bold;padding-left:26px">'.$message->cat_name.'</span></td> <td align="right" width="70px" bgcolor="#3faa3f" style="text-align: center;padding: 0px 6px 3px"> <span class="ver_ico" style="font-size: 10px;color: #fff;padding:3px 4px 3px 5px">Verified '.$message->verified_status.'</span></td></tr>
              </tbody></table></td>
          </tr><tr>
            <td style="padding:05px 25px 0" colspan="3"><table cellpadding="0" cellspacing="0" style="border-bottom: 1px solid #ebebeb;padding-bottom:7px" width="100%">
                <tbody><tr>
                  <td style="font-size: 13px;color: #161616;font-weight: bold" width="30%" colspan="2"><img src="https://seller.imimg.com/blalert_images/clock_cio-min.png" alt="Time" style="display: inline;margin:0 0 -5px 0"><span class="clock_ico" style="display: inline-block;padding-left:7px;line-height: 19px">'.$sent_at.'</span></td>
                  <td style="font-size: 13px;color: #161616;font-weight: bold" width="50%" colspan="2"><img src="https://seller.imimg.com/blalert_images/location-min.png" alt="Location" style="display: inline;margin:0 0 -5px 0"><span class="clock_ico" style="display: inline-block;padding-left:7px;line-height: 19px">'.$message->city.'</span></td>
                </tr>
              </tbody></table></td>
          </tr><tr><td width="100%" style="padding:05px 5%" colspan="2"><table cellpadding="0" cellspacing="0" border="0" style="font-family: arial, sans-serif;font-size: 13px;line-height: 15px;width:100%" class="tall"><tbody><tr>
                <td colspan="3" style="line-height: 24px;padding: 8px 0;color: #353535">'.$message->message.'</td>
              </tr></tbody></table></td></tr><tr>
          <td colspan="3" style="padding: 5px 5% 0"><table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top: 1px solid #ebebeb;padding-top:05px;color: #161616;font-size: 13px; font-family: arial, sans-serif" class="tall">
              <tbody><tr>
                <td width="100%" style="padding:5px 0px 0px" colspan="2"><table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family: arial, sans-serif;font-size: 13px;line-height: 15px">
        <tbody>
        	<tr>
                        <td style="padding:4px 0" valign="middle"><strong>Your Franchise Location</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->location.'</td>
                    </tr>
                    
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Do You Have Drugs License?</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->drugs_license.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Do You Have GST Number?</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->have_gst_number.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Select purchase period</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->purchase_period.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Select Call Back Time</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->call_back_time.'</td>
                    </tr>
                   <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Profession</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->profession.'</td>
                    </tr>
                   <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Minimum Investment</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->minimum_investment.'</td>
                    </tr>
                    
                     <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Any specific query</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$message->search_term.'</td>
                    </tr>
 			</tbody></table></td>
                  </tr>
                </tbody></table></td>
            </tr><tr>
          <td colspan="3" align="center" style="padding: 05px 5%">
        <table cellpadding="0" cellspacing="0" border="0">
            <tbody><tr>
          <td width="300px" style="color: #fff;border-radius: 3px " align="center" bgcolor="#3faa3f" height="54px">
          <a style="text-decoration:none !important;color:#ffffff" href="'.lurl('/account/buy-leads#'.$message->id).'">
          <span style="font-size: 17px;font-weight: bold;display: block;width: 100%">Contact Buyer </span> 
          </a>
          </td>
            </tr>
        </tbody></table>
        </td>
      </tr>
      </tbody></table>';
		}
        
 		$mailMessage->line($html);
        /*$mailMessage->line('<div> 
                 For any queries please contact us at +91 78765 57373 / email us at info@bizzdigital.com
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
		if (!empty($message->filename)) {
			$storagePath = Storage::getDriver()->getAdapter()->getPathPrefix();
			$pathToFile = $storagePath . $message->filename;
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
