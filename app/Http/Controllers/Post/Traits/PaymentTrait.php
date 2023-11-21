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

namespace App\Http\Controllers\Post\Traits;

use App\Models\PaymentMethod;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Payment as PaymentModel;


trait PaymentTrait
{
    /**
     * Send Payment
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendPayment(Request $request, Post $post)
    {
        // Set URLs
        $this->uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], $this->uri['previousUrl']);
        
        // Get Payment Method
        $paymentMethod = PaymentMethod::find($request->input('payment_method_id'));

        if (!empty($paymentMethod)) {
            // Load Payment Plugin
            $plugin = load_installed_plugin(strtolower($paymentMethod->name));

            // Payment using the selected Payment Method
            if (!empty($plugin)) {
                // Send the Payment
                try {
                    return call_user_func($plugin->class . '::sendPayment', $request, $post);
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                    return redirect($this->uri['previousUrl'] . '?error=pluginLoading')->withInput();
                }
            }
        }
    
        return redirect($this->uri['previousUrl'] . '?error=paymentMethodNotFound')->withInput();
    }

    public function sendUserPayment(Request $request,User $user,$type="Subscription")
    {


        // Set URLs
        //$this->uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], $this->uri['previousUrl']);
        
        if($type=="Buy-Leads"){

            $this->uri['previousUrl'] = '/user/buy-leads'; 
        }
        else{

            $this->uri['previousUrl'] = '/user/payment'; 
        }
        
 

        // Get Payment Method
        $paymentMethod = PaymentMethod::find($request->input('payment_method_id'));

        
        if (!empty($paymentMethod)) {
            // Load Payment Plugin
            $plugin = load_installed_plugin(strtolower($paymentMethod->name));

            // Payment using the selected Payment Method
            if (!empty($plugin)) {
                // Send the Payment
                try {

                    return call_user_func($plugin->class . '::sendPayment', $request,$user,$type);
                } catch (\Exception $e) {

                 
                    flash($e->getMessage())->error();
                    return redirect($this->uri['previousUrl'] . '?error=pluginLoading')->withInput();
                }
            }
        }
    
        return redirect($this->uri['previousUrl'] . '?error=paymentMethodNotFound')->withInput();
    }

    /**
     * Payment Confirmation
     * URL: /posts/create/{postIdOrToken}/payment/success
     * - Success URL when Credit Card is used
     * - Payment Process URL when no Credit Card is used
     *
     * @param $postIdOrToken
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function paymentUserConfirmation(Request $request)
    {

        $txnid = (int)str_replace("PHK","",$request->input('txnid'));
        
        $payment = PaymentModel::find($txnid);
        

        if (empty($payment)) {
            flash($this->msg['checkout']['error'])->error();
            return redirect('/?error=paymentSessionNotFound');
        }

        
        // Get Payment Method
        $paymentMethod = PaymentMethod::find($payment->payment_method_id);


        if (empty($paymentMethod)) {
            flash($this->msg['checkout']['error'])->error();
            return redirect('/?error=paymentMethodEntryNotFound');
        }

        // Load Payment Plugin
        $plugin = load_installed_plugin(strtolower($paymentMethod->name));

       
        // Check if the Payment Method exists
        if (empty($plugin)) {
            flash($this->msg['checkout']['error'])->error();
            return redirect('/?error=paymentMethodPluginNotFound');
        }

        // Payment using the selected Payment Method
        try {
            return call_user_func($plugin->class . '::paymentConfirmation', $request,$payment);
        } catch (\Exception $e) {
            dd($e->getMessage())->error();
            flash($e->getMessage())->error();
            return redirect('/?error=paymentMethodPluginError');
        }
    }

    /**
     * Payment Cancel
     * URL: /posts/create/{postIdOrToken}/payment/cancel
     *
     * @param $postIdOrToken
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paymentUserCancel(Request $request)
    {   

        

        $txnid = (int)str_replace("PHK","",$request->input('txnid'));
        
        $payment = PaymentModel::find($txnid);

        if($payment){

            if($request->input('status')=="failure"){
                $payment->active = '3';
                $payment->message = ($request->input('error_Message')!=null?$request->input('error_Message'):$request->input('field9'));

            }
            else{

                $payment->active = '2';
                $payment->message = "Cancelled by user";
            }

            $payment->save();
            
            if($payment->active == '3'){
                flash("Payment failed: ".$payment->message)->error();
            }
            else{

                flash($this->msg['checkout']['cancel'])->error();
            }
        } 


 
        return redirect('/user/payment?error=paymentCancelled');
         
    }
}
