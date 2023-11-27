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

namespace App\Http\Controllers\Admin;

use App\Helpers\Arr;
use Larapen\Admin\app\Http\Controllers\Controller;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\City;
use App\Models\Category;
use App\Models\SubAdmin1;
use App\Models\SubAdmin2;

use DB;
use Response;

class ImportController extends Controller
{
	public function reset()
	{
		return view('admin::reset');
	}
	public function reset_password(Request $request)
	{
	}
	public function import_category(Request $request)
	{
		$file = $request->file("csv_file");
		$csvData = file_get_contents($file);

		$rows = array_map("str_getcsv", explode("\n", $csvData));
		$header = array_shift($rows);
		$check = 0;
		foreach ($rows as $row) {
			if (isset($row[0])) {
				if ($row[0] != "") {
					$row = array_combine($header, $row);
					//print_r($row);
					//echo $row['language'];
					//exit;
					if ($row['parent_category'] == '') {
						echo 'Parent Category is required';
						$check = 1;
					}
					if ($row['name'] == '') {
						$check = 1;
						return 'Name is required';
					}
					if ($row['meta_title'] == '') {
						$check = 1;
						return 'meta_title is required';
					}
					if ($row['keywords'] == '') {
						$check = 1;
						return 'Keywords is required';
					}
					if ($row['slug'] == '') {
						$check = 1;
						return 'Slug is required';
					}
					if ($row['description'] == '') {
						$check = 1;
						return 'description is required';
					}
					if ($row['active'] == '') {
						$check = 1;
						return 'active is required';
					}
					if ($row['is_hidden'] == '') {
						$check = 1;
						return 'Hidden is required';
					}

					if ($row['exclude_location'] == '') {
						$check = 1;
						return 'Exclude location is required';
					}
					if ($row['featured'] == '') {
						$check = 1;
						return 'featured is required';
					}

					if ($row['in_footer'] == '') {
						$check = 1;
						return 'Show in footer is required';
					}
					if ($check == 0) {
						if ($row['picture'] == '') {
							$picture = '';
						} else {
							$length = 30;
							$str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
							$picturename = substr(str_shuffle($str), 0, $length);
							$path = 'storage/app/categories/custom/';
							$returnpath = 'app/categories/custom/';
							$picture = $this->downloadimg($row['picture'], $picturename, $path, $returnpath);
						}
						$categoryid = $this->getcategory($row['parent_category']);
						if ($row['is_hidden'] == "No") {
							$is_hidden = 0;
						} else {
							$is_hidden = 1;
						}
						if ($row['exclude_location'] == 'No') {
							$exclude_location = 0;
						} else {
							$exclude_location = 1;
						}
						if ($row['featured'] == "No") {
							$featured = 0;
						} else {
							$featured = 1;
						}
						if ($row['in_footer'] == "No") {
							$in_footer = 0;
						} else {
							$in_footer = 1;
						}
						if ($row['active'] == "active") {
							$active = 1;
						} else {
							$active = 0;
						}
						$array = array(

							'parent_id' => $categoryid,
							'name' => $row['name'],
							'title' => $row['meta_title'],
							'keywords' => $row['keywords'],
							'slug' => $row['slug'],
							'description' => $row['description'],
							'picture' => $picture,
							'active' => $active,
							'is_hidden' => $is_hidden,
							'exclude_location' => $exclude_location,
							'bottom_text' => $row['bottom_text'],
							'featured' => $featured,
							'in_footer' => $in_footer,

						);
						$checkifexits = DB::table('categories')->where(['name' => $row['name']])->count();
						if ($checkifexits == 0) {
							DB::table('categories')->insert($array);
						} else {
							DB::table('categories')->where(['name' => $row['name']])->update($array);
						}
					}
				} else {
					return 'Parent name is required';
				}
			}
		}
		if ($check == 0) {
			return 'File has been uploaded successfully';
		}
	}

	public function import_users(Request $request)
	{
		$file = $request->file("csv_file");
		$csvData = file_get_contents($file);

		$rows = array_map("str_getcsv", explode("\n", $csvData));
		$header = array_shift($rows);
		$check = 0;
		//dd($rows);
		foreach ($rows as $row) {

			if (isset($row[0])) {
				if ($row[0] != "") {
					//print_r($row);
					//print_r($header);
					//dd(count($row).' --- '.count($header));


					$row = array_combine($header, $row);

					if ($row['email'] == '') {
						$check = 1;
						return 'email is required';
					}
					/* if ($row['domain'] == '') {
						$check = 1;
						return 'domain is required';
					}
					if ($row['package'] == '') {
						$check = 1;
						return 'package is required';
					}*/
					/* if ($row['package_start_date'] == '') {
						$check = 1;
						return 'package_start_date is required';
					}
					if ($row['package_end_date'] == '') {
						$check = 1;
						return 'package_end_date is required';
					} */
					/*if ($row['gender'] == '') {
						$check = 1;
						return 'gender is required';
					}*/
					/* if ($row['first_name'] == '') {
						$check = 1;
						return 'first_name is required';
					} */
					/* if ($row['last_name'] == '') {
						$check = 1;
						return 'last_name is required';
					} */
					if ($row['phone'] == '') {
						//$check = 1;
						$row['phone'] = '0000000000';
						//return 'phone is required';
					}
					/*if ($row['country_code'] == '') {
						$check = 1;
						return 'country_code is required';
					}
					if ($row['notification_email'] == '') {
						$check = 1;
						return 'notification_email is required';
					}
					if ($row['SMS_Mobile_Number'] == '') {
						$check = 1;
						return 'SMS_Mobile_Number is required';
					}
					if ($row['template'] == '') {
						$check = 1;
						return 'template is required';
					}
					if ($row['color'] == '') {
						$check = 1;
						return 'color is required';
					}*/
					/*if ($row['buy_leads_alerts'] == '') {
						$check = 1;
						return 'buy_leads_alerts is required';
					} */
					if ($row['phone_hidden'] == '') {
						$check = 1;
						return 'phone_hidden is required';
					}
					if ($row['verified_phone'] == '') {
						$check = 1;
						return 'verified_phone is required';
					}
					if ($row['verified_email'] == '') {
						$check = 1;
						return 'verified_email is required';
					}
					if ($row['blocked'] == '') {
						$check = 1;
						return 'blocked is required';
					}
					if ($check == 0) {
						$package = DB::table('packages')->where(['name' => $row['package']])->first();
						$gender = DB::table('gender')->where(['name' => $row['gender']])->first();
						if ($row['phone_hidden'] == 'No') {
							$phone_hidden = 0;
						} else {
							$phone_hidden = 1;
						}
						if ($row['verified_phone'] == 'No') {
							$verified_phone = 0;
						} else {
							$verified_phone = 1;
						}
						if ($row['verified_email'] == 'No') {
							$verified_email = 0;
						} else {
							$verified_email = 1;
						}
						$country = DB::table('countries')->where(['name' => empty($row['country_code']) ? $row['country'] : $row['country']  ])->first();
						$array = array(
							'name' => $row['company_name'],
							'email' => $row['email'],
							'domain' => '', //$row['domain'],
							'package_id' => @$package->id,
							'package_start_date' => date("Y-m-d", strtotime($row['package_start_date'])),
							'package_end_date' => date("Y-m-d", strtotime($row['package_end_date'])),
							'gender_id' => @$gender->id,
							'first_name' => empty($row['first_name']) ? ' ' : $row['first_name'] ,
							'last_name' => empty($row['last_name']) ? ' ' : $row['last_name'] ,
							'phone' => $row['phone'],
							'address1' => empty($row['address1']) ? ' ' : $row['address1'] ,
							'address2' => empty($row['address2']) ? ' ' : $row['address2'] ,
							'photo' => empty($row['photo']) ? ' ' : $row['photo'] ,
							'city_id' => empty($row['city_id']) ? ' ' : $row['city_id'] ,
							'pincode' => empty($row['pincode']) ? ' ' : $row['pincode'] ,
							'country_code' => @$country->name,
							'email_to_send' => !empty($row['notification_email']) ? $row['notification_email'] : '' ,
							'sms_to_send' => !empty($row['SMS_Mobile_Number']) ? $row['SMS_Mobile_Number'] : '',
							'template' => !empty($row['template']) ? $row['template'] : 'template2',
							'color' => !empty($row['color']) ? $row['color'] : 'colourv2',
							'buy_leads_alerts' => !empty($row['buy_leads_alerts']) ? $row['buy_leads_alerts'] : 'Yes',
							'phone_hidden' => $phone_hidden,
							'verified_phone' => $verified_phone,
							'verified_email' => $verified_email,
							'blocked' => $row['blocked'],
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						);

						//dd($array);
						$checkifexits = DB::table('users')->where(['email' => $row['email']])->count();
						if ($checkifexits == 0) {
							DB::table('users')->insert($array);
						} else {
							DB::table('users')->where(['email' => $row['email']])->update($array);
						}
					}
				} else {
					return 'Company Name is required';
				}
			}
		}
		if ($check == 0) {
			return 'File has been uploaded successfully';
		}
	}

	private function downloadimg($url, $name, $path, $returnpath)
	{
		$output_filename = $path . $name . '.png';
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
		return $picture = $returnpath . $name . '.png';;
	}
	private function getcategory($name)
	{
		$categoryid = DB::table('categories')->where(['name' => $name])->first();
		$checkparent = DB::table('categories')->where(['parent_id' => $categoryid->id])->count();
		if ($checkparent == 0) {
			return $categoryid->id;
		} else {
			$childcategoryid = DB::table('categories')->where(['parent_id' => $categoryid->id])->orderby('id', 'desc')->first();
			return $childcategoryid->id;
		}
	}
	public function download(Request $request)
	{
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
		//exit;

		///$count=count(@$request->entryId);
		if (@$request->entryId == '') {
			$data = json_decode(json_encode(DB::table('categories')
				->select('id', 'parent_id', 'name', 'title as title', 'keywords', 'slug', 'description', 'bottom_text', DB::raw('(CASE WHEN featured = 0 THEN "No" ELSE "Yes" END) as featured'), DB::raw('(CASE WHEN in_footer = 0 THEN "No" ELSE "Yes" END) as in_footer'), DB::raw('(CASE WHEN is_hidden = 0 THEN "No" ELSE "Yes" END) as is_hidden'), DB::raw('(CASE WHEN exclude_location = 0 THEN "No" ELSE "Yes" END) as exclude_location'), DB::raw('(CASE WHEN active = 0 THEN "Deactive" ELSE "Active" END) as active'))
				->where(['parent_id' => 0])->get()), True);


			function cleanData(&$str)
			{
				if ($str == 't') $str = 'TRUE';
				if ($str == 'f') $str = 'FALSE';
				if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $str)) {
					$str = " $str";
				}
				if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
			}

			// filename for download
			$filename = "category_" . date('Ymd') . ".csv";

			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: text/csv");

			$out = fopen("php://output", 'w');

			$flag = false;
			$itemarray = array();
			foreach ($data as $row) {


				$parent = DB::table('categories')
					->select('id', 'parent_id', 'name', 'title as title', 'keywords', 'slug', 'description', 'bottom_text', DB::raw('(CASE WHEN featured = 0 THEN "No" ELSE "Yes" END) as featured'), DB::raw('(CASE WHEN in_footer = 0 THEN "No" ELSE "Yes" END) as in_footer'), DB::raw('(CASE WHEN is_hidden = 0 THEN "No" ELSE "Yes" END) as is_hidden'), DB::raw('(CASE WHEN exclude_location = 0 THEN "No" ELSE "Yes" END) as exclude_location'), DB::raw('(CASE WHEN active = 0 THEN "Deactive" ELSE "Active" END) as active'))
					->where(['parent_id' => $row['id']])->get();
				foreach ($parent as $sub) {

					$subcate = DB::table('categories')
						->select('id', 'parent_id', 'name', 'title as title', 'keywords', 'slug', 'description', 'bottom_text', DB::raw('(CASE WHEN featured = 0 THEN "No" ELSE "Yes" END) as featured'), DB::raw('(CASE WHEN in_footer = 0 THEN "No" ELSE "Yes" END) as in_footer'), DB::raw('(CASE WHEN is_hidden = 0 THEN "No" ELSE "Yes" END) as is_hidden'), DB::raw('(CASE WHEN exclude_location = 0 THEN "No" ELSE "Yes" END) as exclude_location'), DB::raw('(CASE WHEN active = 0 THEN "Deactive" ELSE "Active" END) as active'))
						->where(['parent_id' => $sub->id])->get();
					foreach ($subcate as $micro) {

						$itemarray[] = array( //this array must be created dynamic
							'ParentCategory' => $row['name'],
							'ChildCategory' => $sub->name,
							'MicroChildCategory' => $micro->name,
							'name' => $micro->name,
							'Meta_title' => $micro->title,
							'Keywords' => $micro->keywords,
							'Slug' => $micro->slug,
							'Description' => $micro->description,
							'Bottom_text' => $micro->bottom_text,
							'Active' => $micro->active,
							'Featured' => $micro->featured,
							'Show_in_Footer' => $micro->in_footer,
							'Is_hidden' => $micro->is_hidden,
							'Exclude_Location' => $micro->exclude_location,
						);
					}

					$itemarray[] = array( //this array must be created dynamic
						'ParentCategory' => $row['name'],
						'ChildCategory' => $sub->name,
						'MicroChildCategory' => '',
						'name' => $sub->name,
						'Meta_title' => $sub->title,
						'Keywords' => $sub->keywords,
						'Slug' => $sub->slug,
						'Description' => $sub->description,
						'Bottom_text' => $sub->bottom_text,
						'Active' => $sub->active,
						'Featured' => $sub->featured,
						'Show_in_Footer' => $sub->in_footer,
						'Is_hidden' => $sub->is_hidden,
						'Exclude_Location' => $sub->exclude_location,
					);
				}

				$itemarray[] = array( //this array must be created dynamic
					'ParentCategory' => $row['name'],
					'ChildCategory' => '',
					'MicroChildCategory' => '',
					'name' => $row['name'],
					'Meta_title' => $row['title'],
					'Keywords' => $row['keywords'],
					'Slug' => $row['slug'],
					'Description' => $row['description'],
					'Bottom_text' => $row['bottom_text'],
					'Active' => $row['active'],
					'Featured' => $row['featured'],
					'Show_in_Footer' => $row['in_footer'],
					'Is_hidden' => $row['is_hidden'],
					'Exclude_Location' => $row['exclude_location'],
				);
			}
		} else {
			//foreach($request->entryId as $mm)
			//{
			//echo $mm;
			//exit;
			$emp = implode(',', $request->entryId);
			$data = json_decode(json_encode(DB::table('categories')
				->select('id', 'parent_id', 'name', 'title as title', 'keywords', 'slug', 'description', 'bottom_text', DB::raw('(CASE WHEN featured = 0 THEN "No" ELSE "Yes" END) as featured'), DB::raw('(CASE WHEN in_footer = 0 THEN "No" ELSE "Yes" END) as in_footer'), DB::raw('(CASE WHEN is_hidden = 0 THEN "No" ELSE "Yes" END) as is_hidden'), DB::raw('(CASE WHEN exclude_location = 0 THEN "No" ELSE "Yes" END) as exclude_location'), DB::raw('(CASE WHEN active = 0 THEN "Deactive" ELSE "Active" END) as active'))
				->whereIn('categories.id', explode(",", $emp))->get()), True);


			function cleanData(&$str)
			{
				if ($str == 't') $str = 'TRUE';
				if ($str == 'f') $str = 'FALSE';
				if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $str)) {
					$str = " $str";
				}
				if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
			}

			// filename for download
			$filename = 'category' . "_" . date('Ymd') . ".csv";

			header("Content-Disposition: attachment; filename=\"$filename\"");
			header("Content-Type: text/csv");

			$out = fopen("php://output", 'w');

			$flag = false;
			$itemarray = array();
			foreach ($data as $row) {


				$dd = DB::table('categories')->where(['id' => $row['parent_id']])->first();
				$sub = DB::table('categories')->where(['parent_id' => @$dd->id])->first();
				$micro = DB::table('categories')->where(['parent_id' => @$sub->id])->first();
				$itemarray[] = array( //this array must be created dynamic
					'ParentCategory' => $row['name'],
					'ChildCategory' => @$sub->name,
					'MicroChildCategory' => @$micro->name,
					'name' => $row['name'],
					'Meta_title' => $row['title'],
					'Keywords' => $row['keywords'],
					'Slug' => $row['slug'],
					'Description' => $row['description'],
					'Bottom_text' => $row['bottom_text'],
					'Active' => $row['active'],
					'Featured' => $row['featured'],
					'Show_in_Footer' => $row['in_footer'],
					'Is_hidden' => $row['is_hidden'],
					'Exclude_Location' => $row['exclude_location'],
				);
			}
			//}
		}
		//exit;
		foreach ($itemarray as $row) {

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

	public function userexcelexport(Request $request)
	{
		//print_r($_POST);
		//exit;
		if ($request->usertype == "customer") {
			$type = 2;
			$sheet = 'customer';
		} else {
			$type = 1;
			$sheet = "seller";
		}
		if (@$request->entryId == '') {
			$data = json_decode(json_encode(DB::table('users')->select(
				'users.name as company_name',
				'users.email',
				'packages.name as package',
				'users.package_start_date',
				'users.package_end_date',
				'users.gender_id as gender',
				'users.first_name',
				'users.last_name',
				'users.phone',
				'users.photo',
				'users.address1',
				'users.address2',
				'users.pincode',
				'users.city_id',
				'users.country_code as country',
				'users.address2 as State',
				'users.city_id as City',
				'users.address1 as address',
				'users.about_us as about',
				'users.domain as domain',
				'users.email_to_send as notification_email',
				'users.sms_to_send as SMS_Mobile_Number',
				'users.username as company_url',
				'users.template',
				'users.color',
				'users.buy_leads_alerts as lead_alert',
				DB::raw('(CASE WHEN users.phone_hidden = 0 THEN "No" ELSE "Yes" END) as phone_hidden'),
				DB::raw('(CASE WHEN users.verified_phone = 0 THEN "No" ELSE "Yes" END) as verified_phone'),
				DB::raw('(CASE WHEN users.verified_email = 0 THEN "No" ELSE "Yes" END) as verified_email'),
				DB::raw('(CASE WHEN users.blocked = 0 THEN "No" ELSE "Yes" END) as blocked'),
				'users.user_type_id as user_type',
				'users.is_admin as Role'
			)
				->join('packages', 'packages.id', '=', 'users.package_id')
				//->join('gender','gender.id' ,'=', 'users.gender_id')
				// ->join('countries','countries.code' ,'=', 'users.country_code')
				// ->join('cities','cities.id', '=', 'users.city_id')
				// ->join('user_types','user_types.id','=','users.user_type_id')
				//  ->join('roles','roles.id','=','users.is_admin')
				->where(['users.user_type_id' => $type])
				->orderby('packages.price', 'DESC')
				->get()), True);
		} else {
			$emp = implode(',', $request->entryId);
			$data = json_decode(json_encode(DB::table('users')->select(
				'users.name as company_name',
				'users.email',
				'packages.name as package',
				'users.package_start_date',
				'users.package_end_date',
				'users.gender_id as gender',
				'users.first_name',
				'users.photo',
				'users.address1',
				'users.address2',
				'users.pincode',
				'users.city_id',
				'users.last_name',
				'users.phone',
				'users.country_code as country',
				'users.address2 as State',
				'users.city_id as City',
				'users.address1 as address',
				'users.about_us as about',
				'users.domain as domain',
				'users.email_to_send as notification_email',
				'users.sms_to_send as SMS_Mobile_Number',
				'users.username as company_url',
				'users.template',
				'users.color',
				'users.buy_leads_alerts as lead_alert',
				DB::raw('(CASE WHEN users.phone_hidden = 0 THEN "No" ELSE "Yes" END) as phone_hidden'),
				DB::raw('(CASE WHEN users.verified_phone = 0 THEN "No" ELSE "Yes" END) as verified_phone'),
				DB::raw('(CASE WHEN users.verified_email = 0 THEN "No" ELSE "Yes" END) as verified_email'),
				DB::raw('(CASE WHEN users.blocked = 0 THEN "No" ELSE "Yes" END) as blocked'),
				'users.user_type_id as user_type',
				'users.is_admin as Role',
				'users.id'
			)
				->join('packages', 'packages.id', '=', 'users.package_id')
				//->join('gender','gender.id' ,'=', 'users.gender_id')
				// ->join('countries','countries.code' ,'=', 'users.country_code')
				// ->join('cities','cities.id', '=', 'users.city_id')
				// ->join('user_types','user_types.id','=','users.user_type_id')
				//  ->join('roles','roles.id','=','users.is_admin')
				->where(['users.user_type_id' => $type])
				->whereIn('users.id', explode(",", $emp))
				->orderby('packages.price', 'DESC')
				->get()), True);
		}
		function cleanData(&$str)
		{
			if ($str == 't') $str = 'TRUE';
			if ($str == 'f') $str = 'FALSE';
			if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $str)) {
				$str = " $str";
			}
			if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
		}

		// filename for download
		$filename = $sheet . "_" . date('Ymd') . ".csv";

		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: text/csv");

		$out = fopen("php://output", 'w');

		$flag = false;
		$itemarray = array();
		foreach ($data as $row) {
			$row['company_url'] = "https://www.pharmafranchisemart.com/" . $row['company_url'];

			$country = DB::table('countries')->where(['code' => $row['country']])->first();
			$row['country'] = @$country->name;
			$role = DB::table('roles')->where(['id' => $row['Role']])->first();
			$row['Role'] = @$role->name;
			//$package=DB::table('packages')->where(['id'=>$row['package']])->first();
			//$row['package']=@$package->name;
			$usertype = DB::table('user_types')->where(['id' => $row['user_type']])->first();
			$row['user_type'] = $usertype->name;
			$gender = DB::table('gender')->where(['id' => $row['gender']])->first();
			$row['gender'] = @$gender->name;
			$city = DB::table('cities')->where(['id' => $row['City']])->first();
			$row['City'] = @$city->name;
			$state = DB::table('subadmin1')->where(['code' => @$city->subadmin1_code])->first();
			$row['State'] = @$state->name;
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

	public function import_product(Request $request)
	{
		$file = $request->file("csv_file");
		$csvData = file_get_contents($file);

		$rows = array_map("str_getcsv", explode("\n", $csvData));
		$header = array_shift($rows);
		$check = 0;
		foreach ($rows as $row) {
			if (isset($row[0])) {
				if ($row[0] != "") {
					$row = array_combine($header, $row);
					//print_r($row);
					//echo $row['language'];
					//exit;
					if ($row['category'] == '') {
						echo 'category is required';
						$check = 1;
					}
					if ($row['subcategory'] == '') {
						$check = 1;
						return 'subcategory is required';
					}
					if ($row['product_group'] == '') {
						$check = 1;
						return 'product_group is required';
					}
					if ($row['title'] == '') {
						$check = 1;
						return 'title is required';
					}
					if ($row['short_description'] == '') {
						$check = 1;
						return 'short_description is required';
					}
					if ($row['description'] == '') {
						$check = 1;
						return 'description is required';
					}

					if ($check == 0) {
						if ($row['photo'] == '') {
							$picture = '';
						} else {
							$length = 30;
							$str = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
							$picturename = substr(str_shuffle($str), 0, $length);
							$path = 'storage/app/categories/custom/';
							$returnpath = 'app/categories/custom/';
							$picture = $this->downloadimg($row['picture'], $picturename, $path, $returnpath);
						}
						$categoryid = DB::table('categories')->where(['name' => $row['subcategory']])->first();
						$groupid = DB::table('product_groups')->where(['name' => $row['product_group']])->first();
						$array = array(
							'category_id' => $categoryid->id,
							'group_id' => $groupid->id,
							'title' => $row['title'],
							'short_description' => $row['short_description'],
							'description' => $row['description'],
							'brochure' => $picture,
							'created_at' => date('Y-m-d H:i:s'),
						);
						$checkifexits = DB::table('posts')->where(['title' => $row['title']])->count();
						if ($checkifexits == 0) {
							DB::table('posts')->insert($array);
						} else {
							DB::table('posts')->where(['title' => $row['title']])->update($array);
						}
					}
				} else {
					return 'Parent name is required';
				}
			}
		}
		if ($check == 0) {
			return 'File has been uploaded successfully';
		}
	}

	public function productexcelexport(Request $request)
	{
		$data = json_decode(json_encode(DB::table('posts')->select('posts.category_id as category', 'categories.name as subcategory', 'product_groups.name as product group', 'posts.title', 'posts.short_description', 'posts.description')
			->join('categories', 'categories.id', '=', 'posts.category_id')
			->join('product_groups', 'product_groups.id', '=', 'posts.group_id')
			->orderby('posts.id', 'desc')
			->get()), True);

		function cleanData(&$str)
		{
			if ($str == 't') $str = 'TRUE';
			if ($str == 'f') $str = 'FALSE';
			if (preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $str)) {
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
		$itemarray = array();
		foreach ($data as $row) {
			$subcategory = DB::table('categories')->where(['id' => @$row['category']])->first();

			$category = DB::table('categories')->where(['id' => @$subcategory->parent_id])->first();
			$row['category'] = @$category->name;
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

	public function downloadcategorycsv()
	{
		$filepath = public_path('csv-import-format/categoryimport.csv');

		// return Response::download($filepath,'categoryimport.csv',$headers);
		return response()->download($filepath);
	}
	public function downloadcitycsv()
	{
		$filepath = public_path('csv-import-format/cityimport.csv');

		// return Response::download($filepath,'categoryimport.csv',$headers);
		return response()->download($filepath);
	}

	public function downloadusercsv()
	{
		$filepath = public_path('csv-import-format/userimport.csv');
		return Response::download($filepath);
	}
	public function cityexport(Request $request, $countrycode)
	{

		$cityAll =	City::with('subAdmin2', 'subAdmin1')->where(['country_code' => $countrycode])->orderBy('name', 'ASC')->get();
		//echo "<pre>";
		//print_r($cityAll);

		//$cityAll = json_decode(json_encode($cityAll ), True);



		// filename for download
		$filename = "cities_" . date('Ymd') . ".csv";

		header("Content-Type: text/csv; charset=UTF-8");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: text/csv");
		header("Content-Transfer-Encoding: binary");
		$out = fopen("php://output", 'w');

		$flag = false;
		$itemarray = array();
		$row = array(
			'Country Code',
 			'Name',
			'State',
			'District',
			'latitude',
			'longitude',
			'population',
			'Active',
                         'seo'
		);

		// display field/column names as first row
		fputcsv($out, $row);
		foreach ($cityAll as $row => $val) {
			$state = '';
			if (isset($val->subAdmin2)) {
				$state = $val->subAdmin2->name;
			}
			$dist = '';
			if (isset($val->subAdmin1)) {
				$dist = $val->subAdmin1->name;
			}
			$row = array(
				$val->country_code,
 				$val->name,
				$dist,
				$state,
				$val->latitude,
				$val->longitude,
				$val->population,
				$val->active ,
                                $val->seo
			);

			fputcsv($out, $row);
		}
		fclose($out);
	}






	public function import_city(Request $request)
	{

		$file = $request->file("csv_file");
		$csvData = file_get_contents($file);

		$rows = array_map("str_getcsv", explode("\n", $csvData));

		 $header = array_shift($rows);
 		$check = 0;
		foreach ($rows as $row) {
                 			if (isset($row[0])) {
				if ($row[0] != "") {
					$row = array_combine($header, $row);
					if ($row['country_code'] == '') {
						$check = 1;
						return 'Country Code';
					}
					if ($row['name'] == '') {
						$check = 1;
						return 'Local Name is required';
					}
					if ($row['distruct'] == '') {
						$check = 1;
						return 'District is required';
					}
					if ($row['state'] == '') {
						$check = 1;
						return 'State is required';
					}
					if ($row['latitude'] == '') {
						$check = 1;
						return 'City latitude is required';
					}
					if ($row['longitude'] == '') {
						$check = 1;
						return 'City longitude is required';
					}


					if ($check == 0) {
						$state = DB::table('subadmin1')->where(['name' => $row['state']])->first();
						$dist = DB::table('subadmin2')->where(['name' => $row['distruct']])->first();
						if(!isset($dist->code)){
							$distLatest = SubAdmin2::latest('id')->first();
							$distcode = explode(".",$distLatest['code']);
							$distcodeCounter = $distcode[1]+1;
							$distcode = $row['country_code'].".".$distcodeCounter;

						}	else {
							$distcode = 	$dist->code;
						}
						if(!isset($state->code)){
							$stateLatest = SubAdmin1::latest('id')->first();
							$statcode =explode(".",$stateLatest['code']);
							$statCounter = $statcode[1]+1;
							$statCode = $row['country_code'].".".$statCounter;

						} else {
							$statCode = $state->code;
						}


 						$array = array(
							'country_code' => $row['country_code'],
							'asciiname' => $row['name'],
							'name' => $row['name'],
							'latitude' => $row['latitude'],
							'longitude' => $row['longitude'],
							'subadmin1_code' => $statCode,
							'subadmin2_code' => $distcode,
							'active' => $row['active'],
                                                         'seo' => $row['seo'],
							'population' => $row['population'],
							'time_zone' => 'Asia/Kolkata',
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						);
						$checkifexits = DB::table('cities')->where(['name' => $row['name']])->count();
						if ($checkifexits == 0) {
							DB::table('cities')->insert($array);
						} else {
							DB::table('cities')->where(['name' => $row['name']])->update($array);
						}
					}
				} else {
					return 'Company Name is required';
				}
			}
		}
		if ($check == 0) {
			return 'File has been uploaded successfully';
		}
	}
}
