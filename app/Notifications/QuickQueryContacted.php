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

class QuickQueryContacted extends Notification implements ShouldQueue
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
		
 
		return ['mail'];
        
	}
	
	public function toMail($notifiable)
	{
		 
		 
		$mailMessage = (new MailMessage)
			//->replyTo($this->msg->from_email, $this->msg->from_name)
			->subject('Quick Query Submitted')
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
                        <td style="padding:4px 0" valign="middle"><strong>Mobile</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->from_phone.'</td>
                    </tr>

                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Email</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->from_email.'</td>
                    </tr>
          
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Search City</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->city.'</td>
                    </tr>
                     
     
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Address</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->address.'</td>
                    </tr>
                  
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>IP Address</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->msg->ip_address.'</td>
                    </tr>

                 
                    
                </tbody>
            </table>')->line('<div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Buyer is looking for "'.$this->msg->looking_for.'"</div>
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
            </table>');
              
		
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
	
	 
}
