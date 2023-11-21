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

use App\Helpers\Ip;
use App\Http\Requests\GroupRequest;
use App\Models\ProductGroup;
 
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
trait EditGroupTrait
{
    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGroupUpdateForm($groupId)
    {
        $data = [];
        
        // Get Post
         
        $group = ProductGroup::where('user_id', auth()->user()->id)
			->where('id', $groupId)
			->first();
         
        if (empty($group)) {
            abort(404);
        }
        
        view()->share('group', $group);
        
        
        
        // Meta Tags
        MetaTag::set('title', 'Update Group');
        MetaTag::set('description', 'Update Group');
        
        return view('group.edit', $data);
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getGroupCreateForm()
    {


        $data = [];
        
        // Get Post
         
        /*$group = ProductGroup::where('user_id', auth()->user()->id)
            ->where('id', $groupId)
            ->first();
         
        if (empty($group)) {
            abort(404);
        }
        
        view()->share('group', $group);*/
        
        
        
        // Meta Tags
        MetaTag::set('title', 'Update Group');
        MetaTag::set('description', 'Update Group');
        
        return view('group.create');
    }
    
    /**
     * Update the Post
     *
     * @param $postIdOrToken
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postGroupUpdateForm($groupId, GroupRequest $request)
    {



        if($groupId){

            $group = ProductGroup::with('user')->where('user_id', auth()->user()->id)
                ->where('id', $groupId)
                ->first();
             
            
            if (empty($group)) {
                abort(404);
            }
        }
        else{
            $group = new ProductGroup;
            $group->user_id = auth()->user()->id;
        }

        
         


       
       
        
        // Update Post
		$input = $request->only($group->getFillable());
		foreach ($input as $key => $value) {
			$group->{$key} = $value;
		}

        if(app('impersonate')->isImpersonating()){

            if(!$group->slug){

                $group->slug = $group->name;                
            }
        }
        else{

            $group->slug = $group->name;
        }
       

        $group->slug = slugify($group->slug);

        
        $savedGroup = ProductGroup::where('user_id', auth()->user()->id)->where(function($query)use($groupId){
            if($groupId){
                $query->where('id','<>' ,$groupId);
            }
        })->where('slug', $group->slug);

          
        if($savedGroup->count()>0){
            flash("Group slug already exists.")->error();
            if($groupId){
                $nextStepUrl = config('app.locale') . '/groups/'.$groupId.'/edit';
            }
            else{
                $nextStepUrl = config('app.locale') . '/groups/create';   
            }

             return redirect($nextStepUrl);

        }

        $file = $request->file('filename');

         
        if ($file && $file->isValid()) {

            $group->image = substr(str_replace(lurl('/storage/'),"",$group->image),1);
            
            if($group->image){
                $oldfileexists = Storage::exists( $group->image );

                if($oldfileexists){
                     Storage::delete( $group->image);
                }

                

                 
     
            }
              
            $destinationPath = 'files/groups/' . $group->user->username;

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newFilename =  md5($filename . time()). '.' . $extension;
            $filePath = $destinationPath . '/' . $newFilename;

            Storage::put($filePath, File::get($file->getrealpath()));
            $group->image = resize($filePath , 'square');
//
            Storage::delete( $filePath);

           
             
        }

        // Save
        $group->save();
        if($groupId){
            flash("Group has been updated.")->success();
        }
        else{
            flash("Group has been created.")->success();
        }
     //    dd($group->image);
        
        $nextStepUrl = config('app.locale') . '/account/my-groups';
        // Redirection
        return redirect($nextStepUrl);
    }
}
