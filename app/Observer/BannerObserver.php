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

use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BannerObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  Banner $banner
     * @return void
     */
    public function deleting(Banner $banner)
    {
        // Delete all banners files
        if (!empty($banner->filename)) {
            $filePath = str_replace('uploads/', '', $banner->filename);
            
             
            // Delete the banner with its thumbs
            $filename = last(explode('/', $filePath));
            $files = Storage::files(dirname($filePath));
            if (!empty($files)) {
                foreach($files as $file) {
                    // Don't delete the default banner
                    if (str_contains($file, config('larapen.core.banner.default'))) {
                        continue;
                    }
                    if (str_contains($file, $filename)) {
                        Storage::delete($file);
                    }
                }
            }
        }
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  banner $banner
     * @return void
     */
    public function saved(banner $banner)
    {
        // Removing Entries from the Cache
        //$this->clearCache($banner);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  banner $banner
     * @return void
     */
    public function deleted(banner $banner)
    {
        // Removing Entries from the Cache
        //$this->clearCache($banner);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $banner
     */
  /*  private function clearCache($banner)
    {
        Cache::forget('post.withoutGlobalScopes.with.city.banners.' . $banner->post_id);
        Cache::forget('post.with.city.banners.' . $banner->post_id);
        Cache::forget('post.withoutGlobalScopes.with.city.banners.' . $banner->post_id . '.' . config('app.locale'));
        Cache::forget('post.with.city.banners.' . $banner->post_id . '.' . config('app.locale'));
    }*/
}
