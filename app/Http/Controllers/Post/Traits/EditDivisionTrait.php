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
use App\Http\Requests\DivisionRequest;
use App\Models\Division;
 
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
trait EditDivisionTrait
{
    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDivisionUpdateForm($divisionId)
    {
        $data = [];
        
        // Get Post
         
        $division = Division::where('user_id', auth()->user()->id)
			->where('id', $divisionId)
			->first();
         
        if (empty($division)) {
            abort(404);
        }
        
        view()->share('division', $division);
        
        
        
        // Meta Tags
        MetaTag::set('title', 'Update Division');
        MetaTag::set('description', 'Update Division');
        
        return view('division.edit', $data);
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $postIdOrToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDivisionCreateForm()
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
        MetaTag::set('title', 'Create Division');
        MetaTag::set('description', 'Create Division');
        
        return view('division.create');
    }
    
    /**
     * Update the Post
     *
     * @param $postIdOrToken
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDivisionUpdateForm($divisionId, DivisionRequest $request)
    {



        if($divisionId){

            $division = Division::with('user')->where('user_id', auth()->user()->id)
                ->where('id', $divisionId)
                ->first();
             
            
            if (empty($division)) {
                abort(404);
            }
        }
        else{
            $division = new Division;
            $division->user_id = auth()->user()->id;
        }


        // Update Post
		$input = $request->only($division->getFillable());
		foreach ($input as $key => $value) {
			$division->{$key} = $value;
		}


        
        $savedDivision = Division::where('user_id', auth()->user()->id)->where(function($query)use($divisionId){
            if($divisionId){
                $query->where('id','<>' ,$divisionId);
            }
        })->where('name', $division->name);

         
        if($savedDivision->count()>0){
            flash("Division already exists.")->error();
            if($divisionId){
                $nextStepUrl = config('app.locale') . '/division/'.$divisionId.'/edit';
            }
            else{
                $nextStepUrl = config('app.locale') . '/division/create';   
            }

             return redirect($nextStepUrl);

        }

        $file = $request->file('filename');

                 
        if ($file && $file->isValid()) {

            $division->image = substr(str_replace(lurl('/storage/'),"",$division->image),1);
            
            if($division->image){
                $oldfileexists = Storage::exists( $division->image );

                if($oldfileexists){
                     Storage::delete( $division->image);
                }

                

                 
     
            }
              
            $destinationPath = 'files/division/' . $division->user->username;

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newFilename =  md5($filename . time()). '.' . $extension;
            $filePath = $destinationPath . '/' . $newFilename;

            Storage::put($filePath, File::get($file->getrealpath()));
            //$division->image = resize($filePath , 'square');
//      
            //Storage::delete( $filePath);
            $division->image = $filePath;

           
             
        }

      

        $file = $request->file('pdf_filename');

        
         
        if ($file && $file->isValid()) {

            $division->pdf = substr(str_replace(lurl('/storage/'),"",$division->pdf),1);
            
            if($division->pdf){
                $oldfileexists = Storage::exists( $division->pdf );

                if($oldfileexists){
                     Storage::delete( $division->pdf);
                }

                

                 
     
            }
              
            $destinationPath = 'files/division/' . $division->user->username;

            $pdf_filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newpdf_Filename =  md5($pdf_filename . time()). '.' . $extension;
            $filePath = $destinationPath . '/' . $newpdf_Filename;

            Storage::put($filePath, File::get($file->getrealpath()));

            $division->pdf = $filePath;
            //Storage::delete( $filePath);

           
             
        }

        // Save
        $division->save();
        if($divisionId){
            flash("Division has been updated.")->success();
        }
        else{
            flash("Division has been created.")->success();
        }
        
        $nextStepUrl = config('app.locale') . '/account/divisions';
        // Redirection
        return redirect($nextStepUrl);
    }
}
