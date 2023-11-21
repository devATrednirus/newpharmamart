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

use App\Models\Package;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
use Illuminate\Support\HtmlString;

class PaymentApproved extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $payment;
	protected $user;
	protected $package;
	protected $paymentMethod;
	
	public function __construct(Payment $payment, User $user)
	{
		$this->payment = $payment;
		$this->user = $user;
		$this->package = Package::findTrans($payment->package_id);
		$this->paymentMethod = PaymentMethod::find($payment->payment_method_id);
	}
	
	public function via($notifiable)
	{
		if ($this->payment->active != 1) {
			return false;
		}
		
		if (!empty($this->user->email)) {
			return ['mail'];
		} else {
			if (config('settings.sms.driver') == 'twilio') {
				return [TwilioChannel::class];
			}
			
			return ['nexmo'];
		}
	}
	
	public function toMail($notifiable)
	{
		/*$attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
		$preview = !isVerifiedPost($this->post) ? '?preview=1' : '';
		$postUrl = lurl($this->post->uri, $attr) . $preview;*/
		
		$mailMessage = (new MailMessage)
		//	->replyTo($this->msg->from_email, $this->msg->from_name)
			->subject("Payment Confirmation - ". config('app.name'))
			->line('Dear '.$this->user->name)
		 
            ->line(new HtmlString('<div style="background: #efefef;padding: 5px 15px;font-size: 17px;font-family: arial;font-weight: 600;" class="">Payment Confirmation</div>'))

            ->line(new HtmlString('<table cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="font-size:12px; margin:15px 0 15px 0; padding:0px 0 0 0px;"><tbody>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Type</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.($this->payment->payment_type).'</td>
                    </tr> 
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Package</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->package->name.'</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 0" valign="middle"><strong>Amount</strong></td>
                        <td align="left" width="8" valign="middle"><strong>:</strong></td>
                        <td valign="middle">'.$this->payment->amount.'</td>
                    </tr>
                     
                     
                </tbody>
            </table>'))
             
         
			->line(trans('mail.post_seller_contacted_content_3'))
		 
			/*->line(trans('mail.post_seller_contacted_content_4'))
			->line('<br>')
			->line(trans('mail.post_seller_contacted_content_5'))
			->line('<br>')*/
			->line(trans('mail.post_seller_contacted_content_6'))
			
			->line(trans('mail.post_seller_contacted_content_7'));
			 
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



		return (new MailMessage)
			->subject(trans('mail.payment_approved_title'))
			->greeting(trans('mail.payment_approved_content_1'))
			/*->line(trans('mail.payment_approved_content_2', [
				'postUrl' => $postUrl,
				'title'   => $this->post->title,
			]))*/
			->line(trans('mail.payment_approved_content_3'))
			->line(trans('mail.payment_approved_content_4', [
				'adId'              => 'id',
				'packageName'       => (!empty($this->package->short_name)) ? $this->package->short_name : $this->package->name,
				'amount'            => $this->package->price,
				'currency'          => $this->package->currency_code,
				'paymentMethodName' => $this->paymentMethod->display_name
			]));
	}
	
	public function toNexmo($notifiable)
	{
		return (new NexmoMessage())->content($this->smsMessage())->unicode();
	}
	
	public function toTwilio($notifiable)
	{
		return (new TwilioSmsMessage())->content($this->smsMessage());
	}
	
	protected function smsMessage()
	{
		return trans('sms.payment_approved_content', ['appName' => config('app.name'), 'title' => $this->post->title]);
	}
}
