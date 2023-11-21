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

namespace App\Observer;

use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Notifications\SellerContacted;
use App\Notifications\CompanyContacted;
use App\Notifications\QuickQueryContacted;

class MessageObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Message $message
     * @return void
     */

    public function saved(Message $message)
    {

        
         

        // Send a message to publisher
             

          // dd([$message->deleted_at,$message->email_sent,$message->profession]);
        if($message->deleted_at==null && $message->email_sent!="1"){
            $notifyType="none";
        	if($message->profession!="Student"){
        		
                if($message->type=="quick" && $message->to_user_id=="1"){

                    $receiver = User::findOrFail($message->to_user_id);
                    $message->notify(new QuickQueryContacted($receiver, $message));
                    $notifyType="QuickQueryContacted";

                }
                else if(($message->post_id!=null && $message->post_id!="0")){

                    
                    $post = Post::with('user')->with('category')->unarchived()->findOrFail($message->post_id);

                    $message->notify(new SellerContacted($post, $message));
                    $notifyType="SellerContacted";
 

                }
                else if(($message->post_id=="0" || $message->post_id==null)){

                    $receiver = User::findOrFail($message->to_user_id);
                    $message->notify(new CompanyContacted($receiver, $message));
                    $notifyType="CompanyContacted";
     

                }
                
                $message->email_sent='1';
                
        	}
        	else{
                
                $message->email_sent='1';
        		$message->blocked='1';
        	}
            
            $message->error_log=$notifyType;
           	$message->sent_at = \Carbon\Carbon::now();
            $message->save();

          //  dump($message);
  
        }

         
    }

    public function deleting(Message $message)
    {
        // Delete all files
        if (!empty($message->filename)) {
            $filename = str_replace('uploads/', '', $message->filename);
            Storage::delete($filename);
        }
        
        // If it is a Conversation, Delete it and its Messages if exist
		if ($message->parent_id == 0) {
        	$conversationMessages = Message::where('parent_id', $message->id)->get();
        	if ($conversationMessages->count() > 0) {
        		foreach ($conversationMessages as $conversationMessage) {
					$conversationMessage->delete();
				}
			}
		}
    }
}
