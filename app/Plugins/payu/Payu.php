<?php

namespace App\Plugins\payu;

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

class Payu extends Payment
{
	/**
	 * Send Payment
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\Post $post
	 * @return \App\Helpers\Payment|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function sendPayment(Request $request,User $user,$type)
	{

		 
		$user  = auth()->user();
		  
		 // Get the Package
		$package = Package::find($request->input('package_id'));

		// Don't make a payment if 'price' = 0 or null
		if (empty($package) || $package->price <= 0) {
			return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
		}

        $payment = new PaymentModel;

        $paymentInfo = [
			'user_id'           => $user->id,
			'package_id'        => $request->input('package_id'),
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

 		

 



		

		// Save the payment
		$payment = new PaymentModel($paymentInfo);
		$payment->save();

		
        $txnid =  "PHK".sprintf("%08d",$payment->id);

		$MERCHANT_KEY = config('payment.payu.merchant_key');
		$SALT = config('payment.payu.merchant_salt');
		// Merchant Key and Salt as provided by Payu.
		if(config('payment.payu.mode')=="test"){
			
			$PAYU_BASE_URL = "https://sandboxsecure.payu.in";		// For Sandbox Mode

		}
		else{
			
			$PAYU_BASE_URL = "https://secure.payu.in";			// For Production Mode

		}
 
		$action = '';

		$posted = array();





		$formError = 0;

		 
		$hash = '';
		// Hash Sequence
		$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

		$package->price = $package->price*(1.03);


		$posted['key'] = $MERCHANT_KEY ;
		$posted['txnid'] = $txnid ;
		$posted['udf1'] = $type ;

		$posted['amount'] = Number::toFloat($package->price);

		//$posted['amount'] = '20';
		$posted['productinfo'] = $package->name;

		$posted['firstname'] = $user->name;
		$posted['email'] = $user->email;
		$posted['phone'] = $user->phone;
		$posted['surl'] = parent::$uri['paymentReturnUrl'];
		$posted['furl'] = parent::$uri['paymentCancelUrl'];
		$posted['service_provider'] = 'payu_paisa';
 
		$hashVarsSeq = explode('|', $hashSequence);
	    $hash_string = '';	
	    $formString = '';

	    foreach ($posted as $key => $value) {
	    	 
		    $formString.='<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
		     
	    }

		foreach($hashVarsSeq as $hash_var) {
	      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';

	      $hash_string .= '|';
	    }

	    $hash_string .= $SALT;
	    //5123456789012346
	    //05/20
	    //123
	    //123456

	    $hash = strtolower(hash('sha512', $hash_string));
	    $action = $PAYU_BASE_URL . '/_payment';
		
 
		$form = <<<EOF
		<form action="$action" method="post" name="payuForm" id="payuForm">
      
      	<input type="hidden" name="hash" value="$hash"/>
      
      	$formString
      	Please wait while we transfer..................
       
      </form>
      <script>
      document.payuForm.submit();
      </script>
EOF;
	
	echo $form;
      exit;

		  
	}
	
	/**
	 * @param $params
	 * @param $post
	 * @return $this|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Exception
	 */
	public static function paymentConfirmation(Request $request,PaymentModel $payment)
	{
	 

		 
		$status=$request->input('status');
		$firstname=$request->input('firstname');
		$amount=$request->input('amount');
		$txnid=$request->input('txnid');
		$posted_hash=$request->input('hash');
		$key=$request->input('key');
		$status=$request->input('status');
		$productinfo=$request->input('productinfo');
		$email=$request->input('email');

		 
		$salt=config('payment.payu.merchant_key');
		 
		// Salt should be same Post Request 


		if ($request->input('additionalCharges')!=null) {
		    $additionalCharges=$request->input('additionalCharges');
		    $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
		} else {
		    $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
		}
	 	
	    /*dump($retHashSeq);

		$hash = hash("sha512", $retHashSeq);
		dump($hash);
		dd($posted_hash);
		if ($hash != $posted_hash) {
			echo "Invalid Transaction. Please try again";
		} else {
			echo "<h3>Thank You. Your order status is ". $status .".</h3>";
			echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
			echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
		}

		exit;*/

		return parent::paymentConfirmationActions($status, $payment,$request->input('payuMoneyId'));

		return $status;
	}
	
	/**
	 * @return array
	 */
	public static function getOptions()
	{
		$options = [];
		
		$paymentMethod = PaymentMethod::active()->where('name', 'payu')->first();
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
		$paymentMethod = PaymentMethod::active()->where('name', 'payu')->first();
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
			'id'                => 1,
			'name'              => 'payu',
			'display_name'      => 'Payu',
			'description'       => 'Payment with Payu',
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
		$paymentMethod = PaymentMethod::where('name', 'payu')->first();
		if (!empty($paymentMethod)) {
			$deleted = $paymentMethod->delete();
			if ($deleted > 0) {
				return true;
			}
		}
		
		return false;
	}
}
