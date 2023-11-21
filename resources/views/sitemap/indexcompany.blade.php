<?php echo '<?xml version="1.0" encoding="UTF-8"?> <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; 

$users = DB::table('users')->select('username')->where('user_type_id','=','1')->where('is_admin','=','0')->where('package_id','!=','')->orWhereNull('is_admin')->where('id','!=','1')->orderBy('created_at', 'DESC')->get();
		if ($users->count() > 0) {
			foreach ($users as $user) {
echo "<sitemap><loc>".url("/").'/en/in/company/'.$user->username.".xml</loc></sitemap>";
			}
		}
		echo "</sitemapindex>";