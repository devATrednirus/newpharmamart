<?php

namespace App\Plugins\cash;

use App\Helpers\Number;
use App\Models\Post;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Helpers\Payment;
use App\Models\Package;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;
use App\Models\Payment as PaymentModel;
class Cash extends Payment
{
	/**
	 * Send Payment
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post $post
	 * @return \App\Helpers\Payment|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function sendPayment(Request $request, User $user,$type)
	{
		// Set URLs

	 
		/*parent::$uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['previousUrl']);
		parent::$uri['nextUrl'] = str_replace(['#entryToken', '#entryId', '#title'], [$post->tmp_token, $post->id, slugify($post->title)], parent::$uri['nextUrl']);
		parent::$uri['paymentCancelUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['paymentCancelUrl']);
		parent::$uri['paymentReturnUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['paymentReturnUrl']);*/
		
		// Get the Package
		$package = Package::find($request->input('package_id'));
		
		// Don't make a payment if 'price' = 0 or null
		if (empty($package) || $package->price <= 0) {
			return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
		}
		
 


 
        $paymentInfo = [
			'user_id'           => $user->id,
			'package_id'        => $package->id,
			'payment_method_id' => $request->input('payment_method_id'),
			'transaction_id'    => null,
			'active'    => 0,
			'amount'    =>  Number::toFloat($package->price),
			'payment_type' =>$type
		];
 		

 		if($type=='Buy-Leads'){

 			$paymentInfo['no_leads'] = $package->monthly_leads;
 			$paymentInfo['remaining'] = $package->monthly_leads;

 		}

 		$payment = new PaymentModel($paymentInfo);
		

		$payment->save();
 		
		return parent::paymentConfirmationActions('success', $payment,'');
		//return self::paymentConfirmationActions($localParams, $post);
	}
	
	/**
	 * @param $params
	 * @param $post
	 * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function paymentConfirmation($params)
	{
		// Set form page URL
		parent::$uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['previousUrl']);
		parent::$uri['nextUrl'] = str_replace(['#entryToken', '#entryId', '#title'], [$post->tmp_token, $post->id, slugify($post->title)], parent::$uri['nextUrl']);
		
		// Try to make the Payment
		try {
			$gateway = Omnipay::create('PayPal_Express');
			$gateway->setUsername(config('payment.paypal.username'));
			$gateway->setPassword(config('payment.paypal.password'));
			$gateway->setSignature(config('payment.paypal.signature'));
			$gateway->setTestMode((config('payment.paypal.mode') == 'live') ? false : true);
			
			// Make the payment
			$response = $gateway->completePurchase($params)->send();
			
			// Get raw data
			$rawData = $response->getData();
			
			// Check the Payment
			if (isset($rawData['PAYMENTINFO_0_ACK']) && $rawData['PAYMENTINFO_0_ACK'] === 'Success') {
				
				// Save the Transaction ID at the Provider (CORRELATIONID | PAYMENTINFO_0_TRANSACTIONID)
				if (isset($rawData['PAYMENTINFO_0_TRANSACTIONID'])) {
					$params['transaction_id'] = $rawData['PAYMENTINFO_0_TRANSACTIONID'];
				}
				
				// Apply actions after successful Payment
				return parent::paymentConfirmationActions($params, $post);
				
			} else {
				
				// Apply actions when Payment failed
				return parent::paymentFailureActions($post);
				
			}
		} catch (\Exception $e) {
			
			// Apply actions when API failed
			return parent::paymentApiErrorActions($post, $e);
			
		}
	}
	
	/**
	 * @return array
	 */
	public static function getOptions()
	{
		$options = [];
		
		$paymentMethod = PaymentMethod::active()->where('name', 'cash')->first();
		if (!empty($paymentMethod)) {
			$options[] = (object)[
				'name'     => mb_ucfirst(trans('admin::messages.settings')),
				'url'      => admin_url('payment_methods/' . $paymentMethod->id . '/edit'),
				'btnClass' => 'btn-info',
			];
		}
		
		return $options;
	}
	
	/**
	 * @return bool
	 */
	public static function installed()
	{
		$paymentMethod = PaymentMethod::active()->where('name', 'cash')->first();
		if (empty($paymentMethod)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function install()
	{
		// Remove the plugin entry
		self::uninstall();
		
		// Plugin data
		$data = [
			 
			'name'              => 'cash',
			'display_name'      => 'Cash/Cheque',
			'description'       => 'Payment with Cash/Cheque',
			'has_ccbox'         => 0,
			'is_compatible_api' => 0,
			'lft'               => 0,
			'rgt'               => 0,
			'depth'             => 1,
			'active'            => 1,
		];
		
		try {
			// Create plugin data
			$paymentMethod = PaymentMethod::create($data);
			if (empty($paymentMethod)) {
				return false;
			}
		} catch (\Exception $e) {

			 
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return bool
	 */
	public static function uninstall()
	{
		$paymentMethod = PaymentMethod::where('name', 'cash')->first();
		if (!empty($paymentMethod)) {
			$deleted = $paymentMethod->delete();
			if ($deleted > 0) {
				return true;
			}
		}
		
		return false;
	}
}
