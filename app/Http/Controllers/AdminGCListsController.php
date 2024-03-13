<?php namespace App\Http\Controllers;

use App\CompanyId;
use App\GCList;
use App\IdType;
use Session;
use Request;
use DB;
use CRUDBooster;
use Faker\Factory;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request as IlluminateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GcListImport;
use App\Exports\GCListTemplateExport;
use App\QrCreation;
use Mail;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use App\EmailTesting;
use App\g_c_lists_devp;
use App\Jobs\GCListFetchJob;
use App\RedemptionSetting;
use App\StoreConcept;
use App\StoreLogo;
use DateTime;
use Illuminate\Support\Facades\Log;


	class AdminGCListsController extends \crocodicstudio\crudbooster\controllers\CBController {

		function __construct(){
			
			GCListFetchJob::dispatch();
			date_default_timezone_set("Asia/Manila");
			date_default_timezone_get();	
		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "g_c_lists";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"ID","name"=>"id"];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Phone","name"=>"phone"];
			$this->col[] = ["label"=>"Email","name"=>"email"];
			$this->col[] = ["label"=>"Campaign ID", "name"=>"campaign_id", "join"=>"qr_creations,campaign_id"];
			$this->col[] = ["label"=>"GC Description", "name"=>"campaign_id", "join"=>"qr_creations,gc_description"];
			$this->col[] = ["label"=>"Batch Group","name"=>"campaign_id","join"=>"qr_creations,batch_group"];
			$this->col[] = ["label"=>"Batch Number","name"=>"campaign_id","join"=>"qr_creations,batch_number"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			$this->form[] = ['label'=>'Phone','name'=>'phone','type'=>'number','validation'=>'required|numeric','width'=>'col-sm-10','placeholder'=>'You can only enter the number only'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email|unique:g_c_lists','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			# END FORM DO NOT REMOVE THIS LINE

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){

				$this->index_button[] = ['label'=>'Scan QR','url'=>CRUDBooster::mainpath("scan_qr"),"icon"=>"fa fa-search", 'color'=>'primary'];
				// $this->index_button[] = ['label'=>'Upload GC List','url'=>CRUDBooster::mainpath("upload_gc_list"),"icon"=>"fa fa-plus", 'color'=>'primary'];
			}



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {

			// if(CRUDBooster::isSuperAdmin()){
			// 	$query->where('uploaded_img', null);
			// }else{
			// 	$query->whereRaw('1 = 0'); // Return an empty result
			// }

			// $faker = Factory::create();

			// for($i=0; $i<5; $i++){
			// 	GCList::create([
			// 		'name' => $faker->name,
			// 		'phone' => $faker->phoneNumber,
			// 		'email' => $faker->email,
			// 		'campaign_id' => 1,
			// 		'qr_reference_number' => Str::random(10)
			// 	]);
			// }
	    }
		
	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }



	    //By the way, you can still create your own method in here... :) 

		public function getScanQR(IlluminateRequest $request) {
			
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Scan QR';
			$data['scannedData'] = $request->input('data'); 

			return $this->view('redeem_qr.scan_qr',$data);
		}

		public function getIndex(){
			return redirect(CRUDBooster::mainpath('scan_qr'));
		}

		public function getEdit($id) {
			
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$url = Request::all();
			$slug = $url['value'];
			$user_store = DB::table('cms_users')->where('id', CRUDBooster::myId())->get()->first();

			$store_concept = new StoreConcept();
			$store_logo = new StoreLogo();

			// Store
			if($url['campaign_id'] == 3){
				$user = DB::table('g_c_lists_devps')->where('id', $id)->first();
				$gc_list_devp = DB::table('g_c_lists_devps')->where('id', $id)->first();
				$gc_list_devp_store = $store_logo->where('name', $gc_list_devp->store_concept)->first();
				$user_concept = $user_store->id_store_concept;
				$validate_user_store_privilege = $gc_list_devp_store->concept == $store_concept->where('id', $user_concept)->first()->concept;
			}
			// EGC
			else{
				$user = GCList::find($id);
				$campaign_id = QrCreation::find($user->campaign_id);
				$participating_stores = explode(",",$campaign_id->number_of_gcs);
				$validate_user_store = in_array($user_store->id_store_concept, $participating_stores);
			}

			if(($campaign_id->campaign_type_id == 2)){
				// if((new \DateTime())->format('Y m d') >= (new \DateTime($campaign_id->start_date))->format('Y m d') && ((new \DateTime())->format('Y m d') <= (new \DateTime($campaign_id->expiry_date))->format('Y m d'))){
					if ($user->qr_reference_number == $slug && $slug && $validate_user_store || CRUDbooster::myPrivilegeName() == 'Super Administrator'){
						$data = [];
						$data['page_title'] = 'Redeem QR';
						$data['row'] = DB::table('g_c_lists')
						->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists.id_type')
						->leftJoin('qr_creations as qr', 'qr.id', '=', 'g_c_lists.campaign_id')
						->select('g_c_lists.*',
							'qr.campaign_id',
							'qr.gc_description',
							'qr.gc_value',
							'qr.number_of_gcs',
							'qr.batch_group',
							'qr.batch_number',
							'qr.campaign_type_id',
							'id_name.valid_ids',
						)
						->where('g_c_lists.id',$id)
						->first();

						$data['sc'] = DB::table('cms_users')->where('cms_users.id', CRUDBooster::myId())
							->leftJoin('store_concepts as sc', 'sc.id', 'cms_users.id_store_concept')
							->select('sc.name as store_concept_name')
							->first();
						
						$data['valid_ids'] = IdType::orderBy('valid_ids', 'asc')->get();
						
						return $this->view('redeem_qr.qr_redeem_section',$data);
					}else{
		
						CRUDBooster::redirect(CRUDBooster::mainpath('scan_qr'), sprintf("You don't have privilege to access this area or try again scanning."),"danger");
					}
				// }else{
				// 	CRUDBooster::redirect(CRUDBooster::mainpath('scan_qr'), sprintf("QR Code expired, I'm sorry."),"danger");
				// }
			}else{
				
				if(in_array($gc_list_devp->store_status, [2,6,7])){
					CRUDBooster::redirect(CRUDBooster::mainpath('scan_qr'), sprintf("E-Gift Card is not activated."), "danger");
				}
				
				if (($user->qr_reference_number == $slug) && ($slug)|| CRUDbooster::myPrivilegeName() == 'Super Administrator'){
					$data = [];
					$data['page_title'] = 'Redeem QR';
					$data['row'] = DB::table('g_c_lists_devps')
					->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists_devps.id_type')
					->leftJoin('egc_value_types as egc', 'egc.id' ,'=', 'g_c_lists_devps.egc_value_id')
					->leftJoin('store_concepts as sc', 'sc.id' ,'=', 'g_c_lists_devps.store_concepts_id')
					->select('g_c_lists_devps.*',
						'id_name.valid_ids',
						'egc.value as gc_value',
						'sc.name as store_concept_name'
					)
					->where('g_c_lists_devps.id',$id)
					->first();

					$data['valid_ids'] = IdType::orderBy('valid_ids', 'asc')->get();
					
					return $this->view('redeem_qr.qr_redeem_section_store',$data);
					
				}else{
	
					CRUDBooster::redirect(CRUDBooster::mainpath('scan_qr'), sprintf("Invalid EGC or try again scanning."),"danger");
				}
			}
		}

		public function getBdo(IlluminateRequest $request)
		{
			$bdo_code = $request['bdo_code'];

			$devp = g_c_lists_devp::where('qr_reference_number', $bdo_code)->first();

			if (!$bdo_code || !$devp){
				CRUDBooster::redirect(CRUDBooster::mainpath('scan_qr'), sprintf("Incorrect BDO Code."),"danger");
			}

			$url = "admin/g_c_lists/edit/$devp->id?value=$devp->qr_reference_number&campaign_id=3";

			return redirect($url);
		}

		public function redemptionSetting(IlluminateRequest $request)
		{
			dd($request->all());
			$redemption_type = $request['redemption_id'];

			RedemptionSetting::first()
				->update([
					'redemption_type_id' => $redemption_type,
					'updated_by' => CRUDBooster::myId(),
					'updated_at' => date('Y-m-d H:i:s')
				]);

			CRUDBooster::redirect(CRUDBooster::mainpath('scan_qr'), sprintf("Redemption settings updated successfully."),"success");
		}

		public function redeemCode(IlluminateRequest $request) {
			
			$return_inputs = $request->all();
			$id = $return_inputs['user_id'];
			$id_number = $return_inputs['id_number'];
			$id_type = $return_inputs['id_type'];
			$my_id = $return_inputs['my_id'];
			$campaign_type_id = $return_inputs['campaign_type_id'];
			$claimed_by = $return_inputs['claimed_by'];
			$claimed_email = $return_inputs['claimed_email'];

			if($campaign_type_id){

				GCList::where('id', $id)->update([
					'redeem' => 1,
					'cashier_name' => CRUDBooster::myId(),
					'cashier_date_transact' => date('Y-m-d H:i:s'),
					'id_number' => $id_number,
					'id_type' => $id_type,
					'status' => 'CLAIMED',
					'pos_terminal' => StoreConcept::find((DB::table('cms_users')->where('id', CRUDBooster::myId())->first()->id_store_concept))->ftermid
				]);
	
				$user_information = DB::table('g_c_lists')
				->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists.id_type')
				->leftJoin('qr_creations as qr', 'qr.id', '=', 'g_c_lists.campaign_id')
				->select('g_c_lists.*',
					'qr.campaign_id',
					'qr.gc_description',
					'qr.gc_value',
					'qr.number_of_gcs',
					'qr.batch_group',
					'qr.batch_number',
					'id_name.valid_ids')
				->where('g_c_lists.id',$id)
				->first();
			}
			else{

				DB::table('g_c_lists_devps')->where('id', $id)->update([
					'redeem' => 1,
					'claimed_by' => $claimed_by,
					'claimed_email' => $claimed_email,
					'cashier_name' => CRUDBooster::myId(),
					'cashier_date_transact' => date('Y-m-d H:i:s'),
					'id_number' => $id_number,
					'id_type' => $id_type,
					'store_status' => 8,
					'status' => 'CLAIMED',
					'pos_terminal' => StoreConcept::find((DB::table('cms_users')->where('id', CRUDBooster::myId())->first()->id_store_concept))->ftermid
				]);
	
				$user_information = DB::table('g_c_lists_devps')
				->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists_devps.id_type')
				->select('g_c_lists_devps.*',
					'id_name.valid_ids')
				->where('g_c_lists_devps.id',$id)
				->first();
			}


			return response()->json(['test'=>$user_information]);
		}

		public function inputInvoice(IlluminateRequest $request) {

			$return_inputs = $request->all();
			$crudbooster_my_id = CRUDBooster::myId();
			$campaign_type_id = $return_inputs['campaign_type_id'];

			$cms_user = DB::table('cms_users')->where('id', $crudbooster_my_id)->first();

			$id = $return_inputs['userId'];
			$invoice_number = $return_inputs['posInvoiceNumber'];

			$user_information = $campaign_type_id ? GCList::find($id) : DB::table('g_c_lists_devps')->where('id', $id);
			
			// For testing 
			// $user_information->update(
			// 	['invoice_number'=>$invoice_number]
			// );
			// $invoice_number_exists = $campaign_type_id ? GCList::where('invoice_number', $invoice_number)->exists() : DB::table('g_c_lists_devps')->where('invoice_number', $invoice_number)->exists();
			
			$store_name = StoreConcept::find($cms_user->id_store_concept);
			
			// $fcompanyid = $store_name->fcompanyid;
			// $ftermid = $store_information->ftermid;
			// $fofficeid = $store_information->fofficeid;
			
			$invoice_number_exists = DB::connection('mysql_tunnel')
			->table('pos_sale')
			->where('fcompanyid',$store_name->fcompanyid) //need setup store - DONE
			->where('fofficeid',$store_name->branch_id) //need setup user management (TAG USER TO STORE BRANCH)
			->where('fdocument_no',$invoice_number)
			->where('ftermid', (int) $store_name->ftermid) //need setup user management
			->where('fdoctype',6000)
			->exists();


// 			$invoice_number_exists = DB::connection('mysql_tunnel')
// 			->table('pos_sale')
// 			->where('fcompanyid','BC-17020882') //need setup store - DONE
// 			->where('fofficeid','SAMPLE') //need setup user management (TAG USER TO STORE BRANCH)
// 			->where('fdocument_no',$invoice_number)
// 			->where('ftermid', '0011') //need setup user management
// 			->where('fdoctype',6000)
// 			->exists();

			// SELECT fdocument_no,fsale_date FROM bc_webpos.pos_sale where fdocument_no='12' and ftermid='0011' and fcompanyid='BC-17020882' and fofficeid='SAMPLE' and fdoctype=6000;

			if($invoice_number_exists){

				$user_information->update(
					['invoice_number'=>$invoice_number]
				);
			}else{
				$invoice_number_exists;
			}

			return response()->json(['success'=>$invoice_number_exists]);
		}

		public function closeTransaction(IlluminateRequest $request) {
			
			$validate = $request->validate([
				'item_image' => 'required|image'
			]);

			$img_file = $request->all()['item_image'];
			$id = $request->all()['user_id'];
			$campaign_type_id = $request->all()['campaign_type_id'];

			$data = [];

			if($campaign_type_id){

				$data['row'] = DB::table('g_c_lists')
				->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists.id_type')
				->leftJoin('qr_creations as qr', 'qr.id', '=', 'g_c_lists.campaign_id')
				->select('g_c_lists.*',
					'qr.campaign_id',
					'qr.gc_description',
					'qr.gc_value',
					'qr.number_of_gcs',
					'qr.batch_group',
					'qr.batch_number',
					'id_name.valid_ids')
				->where('g_c_lists.id',$id)
				->first();

				$gc_list = DB::table('g_c_lists');
			}else{

				$data['row'] = DB::table('g_c_lists_devps')
				->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists_devps.id_type')
				->select('g_c_lists_devps.*',
					'id_name.valid_ids')
				->where('g_c_lists_devps.id',$id)
				->first();

				$gc_list = DB::table('g_c_lists_devps');
			}

			$filename = 'redeemed_item_'.'id'."$id".'_'.bin2hex(random_bytes(3)).'.'.$img_file->getClientOriginalExtension();
			$image = Image::make($img_file);
			
			$image->resize(1024, 768, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

			// Save the resized image to the public folder
			$image->save(public_path('uploaded_item/img/' . $filename));
			// Optimize the uploaded image
			$optimizerChain = OptimizerChainFactory::create();
			$optimizerChain->optimize(public_path('uploaded_item/img/' . $filename));

			$gc_list->where('id', $id)->update([
				'uploaded_img'=>$filename,
				'cashier_date_transact' => date('Y-m-d H:i:s'),
				'cashier_name' => CRUDBooster::myId()
			]);
			
			// Send Mail
			$email = $data['row']->claimed_email;

			try {

				Mail::send(['html' => 'redeem_qr.redeemedemail'], $data, function($message) use ($email) {
					$message->to($email)->subject('Qr Code Redemption!');
					$message->from(config('send_email.username'), config('send_email.name'));
				});
			} catch (\Exception $e) {

				dd($e);
			}
			
			CRUDBooster::redirect(CRUDBooster::adminPath('g_c_lists/scan_qr'), sprintf('Code redemption succesful'),"success")->send();
		}

		public function getDetail($id) {
			//Create an Auth
			if(!CRUDBooster::isRead() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Detail Data';
			$data['row'] = DB::table('g_c_lists')
				->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists.id_type')
				->leftJoin('qr_creations as qr', 'qr.id', '=', 'g_c_lists.campaign_id')
				->select('g_c_lists.*',
					'qr.campaign_id',
					'qr.gc_description',
					'qr.gc_value',
					'qr.number_of_gcs',
					'qr.batch_group',
					'qr.batch_number',
					'id_name.valid_ids')
				->where('g_c_lists.id',$id)
				->first();
			
			return $this->view('redeem_qr.qr_redeem_section_view',$data);
		}

	}