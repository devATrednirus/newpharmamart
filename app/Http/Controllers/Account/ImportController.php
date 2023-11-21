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

namespace App\Http\Controllers\Account;
use App\Http\Controllers\FrontController;
use App\Helpers\Arr;
use Illuminate\Support\Facades\Auth;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
class ImportController extends FrontController
{

	private function downloadimg($url,$name,$path,$returnpath)
	{
		$output_filename = $path.$name.'.png';
$host = $url; // <-- Source image url (FIX THIS)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $host);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, false);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // <-- don't forget this
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // <-- and this
$result = curl_exec($ch);
curl_close($ch);
$fp = fopen($output_filename, 'wb');
fwrite($fp, $result);
fclose($fp);
return $picture=$returnpath.$name.'.png';;
	}
	

	public function import_product(Request $request)
	{
		$file = $request->file("csv_file");
        $csvData = file_get_contents($file);

        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);
      $check=0;
        foreach ($rows as $row) {
            if (isset($row[0])) {
                if ($row[0] != "") {
                    $row = array_combine($header, $row);
					//print_r($row);
                    //echo $row['language'];
					//exit;
					if($row['category']=='')
					{
						echo 'category is required';
						$check=1;
					}
					if($row['subcategory']=='')
					{
						$check=1;
						return 'subcategory is required';
					}
					if($row['product_group']=='')
					{
						$check=1;
						return 'product_group is required';
					}
					if($row['title']=='')
					{
						$check=1;
						return 'title is required';
					}
					if($row['short_description']=='')
					{
						$check=1;
						return 'short_description is required';
					}
					if($row['description']=='')
					{
						$check=1;
						return 'description is required';
					}
					
					if($check==0)
					{
					if($row['photo']=='')
					{
						$picture='';
					}
					else
					{
                    $length = 30;
$str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
 $picturename= substr(str_shuffle($str), 0, $length);
 $path='storage/app/categories/custom/';
 $returnpath='app/categories/custom/';
               $picture=$this->downloadimg($row['picture'],$picturename,$path,$returnpath);
					}
					$categoryid=DB::table('categories')->where(['name'=>$row['subcategory']])->first();
					$groupid=DB::table('product_groups')->where(['name'=>$row['product_group']])->first();
                   $array=array(
			   'category_id' => $categoryid->id,
			   'group_id' => $groupid->id,
			   'user_id'=>Auth::user()->id,
			   'title' => $row['title'],
			   'short_description' => $row['short_description'],
			   'description' => $row['description'],
			   'brochure' => $picture,
			   'created_at' =>date('Y-m-d H:i:s'),
			   );
			   $checkifexits=DB::table('posts')->where(['title'=>$row['title'],'user_id'=>Auth::user()->id])->count();
			   if($checkifexits==0)
			   {
             DB::table('posts')->insert($array);
			   }
			   else
			   {
				   DB::table('posts')->where(['title'=>$row['title'],'user_id'=>Auth::user()->id])->update($array);
			   }
                }
				}
				else
				{
					return 'Parent name is required';
				}
            }
        }
		if($check==0)
		{
       return 'File has been uploaded successfully';
		}
	}
	
	public function productexcelexport(Request $request)
	{
		$data = json_decode(json_encode(DB::table('posts')->select('posts.category_id as category','categories.name as subcategory','posts.id as microchild','product_groups.name as product group','posts.title','posts.short_description','posts.description')
		  ->join('categories','categories.id','=','posts.category_id')
		  ->join('product_groups','product_groups.id' ,'=', 'posts.group_id')
		  ->where(['posts.user_id'=>Auth::user()->id])
		  ->orderby('posts.id','desc')
		  ->get()), True); 
	   
        function cleanData(&$str)
        {
            if ($str == 't') $str = 'TRUE';
            if ($str == 'f') $str = 'FALSE';
            if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$str)) {
                $str = " $str";
            }
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        // filename for download
        $filename = "products_" . date('Ymd') . ".csv";

      header("Content-Disposition: attachment; filename=\"$filename\"");
       header("Content-Type: text/csv");
		
        $out = fopen("php://output", 'w');

        $flag = false;
		$itemarray=array();
        foreach ($data as $row) {
			$subcategory=DB::table('categories')->where(['id'=>@$row['category']])->first();
		
			$category=DB::table('categories')->where(['id'=>@$subcategory->parent_id])->first();
			$row['category']=@$category->name;
			
			$microchild=DB::table('categories')->where(['parent_id'=>@$subcategory->id])->first();
			$row['microchild']=@$microchild->name;
			if (!$flag) {
                // display field/column names as first row
                fputcsv($out, array_keys($row), ',', '"');
                $flag = true;
            }
            array_walk($row, __NAMESPACE__ . '\cleanData');
            fputcsv($out, array_values($row), ',', '"');
			
			
        } 
		
        fclose($out);
	}
	
}
