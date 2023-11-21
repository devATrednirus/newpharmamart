<?php echo '<?xml version="1.0" encoding="UTF-8"?> <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; 

$userslug=$companyname;

$users = DB::table('users')->select('users.username','users.id','posts.group_id','posts.updated_at','users.created_at','posts.title')->join('posts','posts.user_id','=','users.id')->where('user_type_id','1')->where('users.username','=',$userslug)->get();
	
			$users_created=DB::table('users')->where(['username'=>$userslug])->first();
                          
			$date = date_create($users_created->created_at, timezone_open('asia/kolkata'));
		echo "<url><loc>".url("/").'/'.$userslug."</loc> <lastmod>".date_format($date, 'Y-m-d\TH:i:sP')."</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>";
		echo "<url><loc>".url("/").'/'.$userslug."/about-us</loc><lastmod>".date_format($date, 'Y-m-d\TH:i:sP')."</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>";
		echo "<url><loc>".url("/").'/'.$userslug."/contact-us</loc><lastmod>".date_format($date, 'Y-m-d\TH:i:sP')."</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>";
	       if ($users->count() > 0) {
			foreach ($users as $user) {
         
           $productgroup=DB::table('product_groups')->where(['id'=>$user->group_id])->first();
             
           $date = date_create($user->updated_at, timezone_open('asia/kolkata'));
           echo "<url><loc>".url("/").'/'.$userslug.'/'.@$productgroup->slug.'#'.slugify(@$user->title)."</loc><lastmod>".date_format($date, 'Y-m-d\TH:i:sP')."</lastmod><changefreq>daily</changefreq><priority>0.9</priority></url>";
			}
		}
		echo "</urlset>";