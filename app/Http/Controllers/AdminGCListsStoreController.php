<?php namespace App\Http\Controllers;

	use App\EgcValueType;
	use App\EmailTesting;
	use App\GCList;
	use App\Jobs\SendEmailJob;
	use App\Mail\QrEmail;
	use Session;
	use Illuminate\Http\Request;
	use DB;
	use CRUDBooster;
	use Spatie\ImageOptimizer\OptimizerChainFactory;
	use Intervention\Image\Facades\Image;
	use Illuminate\Support\Str;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\File;
	use App\EmailTemplateImg;
use App\g_c_lists_devp;
use App\GCListsDevpsCustomer;
use App\StoreConcept;
use App\StoreHistory;
use Illuminate\Support\Facades\Mail;

	class AdminGCListsStoreController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {

			date_default_timezone_set("Asia/Manila");
			date_default_timezone_get();
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");

		}

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "first_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "g_c_lists_devps";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Reference Number","name"=>"reference_number"];
			$this->col[] = ["label"=>"Status","name"=>"store_status","join"=>"store_statuses,name"];
			$this->col[] = ["label"=>"Name","name"=>"g_c_lists_devps_customer_id","join"=>"g_c_lists_devps_customers,name"];
			$this->col[] = ["label"=>"Email","name"=>"g_c_lists_devps_customer_id","join"=>"g_c_lists_devps_customers,email"];
			$this->col[] = ["label"=>"Phone","name"=>"g_c_lists_devps_customer_id","join"=>"g_c_lists_devps_customers,phone"];
			$this->col[] = ["label"=>"Invoice#","name"=>"store_invoice_number"];
			$this->col[] = ["label"=>"Branch","name"=>"g_c_lists_devps.store_concepts_id","join"=>"store_concepts,name"];
			$this->col[] = ["label"=>"Concept","name"=>"store_concept"];
			$this->col[] = ["label"=>"Created at","name"=>"g_c_lists_devps_customer_id","join"=>"g_c_lists_devps_customers,created_at"];
			// $this->col[] = ["label"=>"Updated By","name"=>"updated_by","join"=>"cms_users,name"];
			// $this->col[] = ["label"=>"Last Update","name"=>"created_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Phone','name'=>'phone','type'=>'number','validation'=>'required|numeric','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email|unique:g_c_lists','width'=>'col-sm-10'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'First Name','name'=>'first_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Last Name','name'=>'last_name','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Phone','name'=>'phone','type'=>'number','validation'=>'required|numeric','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email|unique:g_c_lists','width'=>'col-sm-10'];
			# OLD END FORM

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
			$this->addaction[] = ['title'=>'Edit','url'=>'[qr_link]','icon'=>'fa fa-qrcode', 'showIf' => 'in_array([store_status], [1]) && [qr_link]','target'=>'_blank'];
			if(CRUDBooster::isSuperAdmin()){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', 'showIf' => 'in_array([store_status], [2, 3 ,4 ,5 ,6, 7])'];
			}
			// else if(CRUDBooster::myPrivilegeId() == 7 ){
			// 	$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', 'showIf' => 'in_array([store_status], [2, 3])'];
			// }else if(CRUDBooster::myPrivilegeId() == 3){
			// 	$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', 'showIf' => 'in_array([store_status], [2])'];
			// }


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
				$cms_user = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
				$store_concept = DB::table('store_concepts')->where('id', $cms_user->id_store_concept)->first();
				$store_logos = DB::table('store_logos')->where('concept', $store_concept->concept)->first();
				
				if($store_logos->name == 'Digital Walker and Beyond the Box'){
					$user_store_logo = 'dw_and_btb';
				}else{
					$user_store_logo = strtolower(str_replace(' ', '_', $store_logos->name));
				}
				
				$this->index_button[] = ['label'=>'Create GC','url'=>url("qr_link/$user_store_logo/$store_concept->name"),"icon"=>"fa fa-qrcode", 'color'=>'success'];
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
			$admin_path = CRUDBooster::adminPath();
	        $this->script_js = "
		
			";

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
	        $this->post_index_html = view('module_modal.store_create_qr')->render();
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        // $this->load_js[] = '//cdn.jsdelivr.net/npm/sweetalert2@11';
	        
	        
	        
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
	    	$this->load_css[] = asset('css/store_modal.css');
	        
	        
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
	        //Your code here

			$cms_user = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();

			$query->addSelect('g_c_lists_devps.qr_link');
	        
			if(CRUDBooster::isSuperAdmin()){
				$query->where('g_c_lists_devps.store_concept', '!=', null)
					->orderByRaw(
						"CASE
							WHEN store_status = 1 THEN 1
							WHEN store_status = 2 THEN 2
							WHEN store_status = 3 THEN 3
							WHEN store_status = 4 THEN 4
							WHEN store_status = 5 THEN 5
							WHEN store_status = 6 THEN 6
							ELSE 7
						END"
					);
			}else{
				$query->where('g_c_lists_devps.store_concept', '!=', null)
					->where('g_c_lists_devps.store_concepts_id', $cms_user->id_store_concept)
					->orderByRaw(
						"CASE
							WHEN store_status = 1 THEN 3
							WHEN store_status = 2 THEN 1
							WHEN store_status = 3 THEN 2
							WHEN store_status = 4 THEN 5
							WHEN store_status = 5 THEN 4
							WHEN store_status = 6 THEN 6
							ELSE 7
						END"
					);
			}

	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {
			
			if($column_index == 3){
				if($column_value == 'Pending Customer'){
					$column_value = '<span class="label" style="background-color: #233329; color: white; font-size: 12px;">Pending Customer</span>';
				}
				if($column_value == 'Verify OTP'){
					$column_value = '<span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">Verify OTP</span>';
				}
				else if($column_value == 'Send EGC Recipient'){
					$column_value = '<span class="label" style="background-color: #77BFA3; color: white; font-size: 12px;">Send EGC Recipient</span>';
				}
				else if($column_value == 'Verified'){
					$column_value = '<span class="label" style="background-color: rgb(255, 179, 102); color: white; font-size: 12px;">Verified</span>';
				}
				else if($column_value == 'Email Sent'){
					$column_value = '<span class="label" style="background-color: rgb(74 222 128); color: white; font-size: 12px;">Email Sent</span>';
				}
				else if($column_value == 'Email Failed'){
					$column_value = '<span class="label" style="background-color: rgb(239 68 68); color: white; font-size: 12px;">Email Failed</span>';
				}
				else if($column_value == 'Voided'){
					$column_value = '<span class="label" style="background-color: rgb(239 68 68); color: white; font-size: 12px;">Voided</span>';
				}
			}
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

		public function getEdit($id) {
			
			$gc_list = DB::table('g_c_lists_devps')->where('g_c_lists_devps.id', $id)
				->leftJoin('store_concepts', 'store_concepts.id', 'g_c_lists_devps.store_concepts_id')
				->leftJoin('g_c_lists_devps_customers', 'g_c_lists_devps_customers.id', 'g_c_lists_devps.id')
				->select('g_c_lists_devps.*',
				'store_concepts.name as store_branch',
				'g_c_lists_devps_customers.name as cus_name',
				'g_c_lists_devps_customers.first_name as cus_first_name',
				'g_c_lists_devps_customers.last_name as cus_last_name',
				'g_c_lists_devps_customers.email as cus_email',
				'g_c_lists_devps_customers.phone as cus_phone',
				)
				->get()
				->first();
			
			$egc_value = DB::table('egc_value_types')->get();
			$email_testings = new EmailTesting();

			$store_logos_id = 5;

			// if($gc_list->store_concept == 'Digital Walker'){
			// 	$store_logos_id = 1;
			// }else if($gc_list->store_concept == 'Beyond the Box'){
			// 	$store_logos_id = 2;
			// }else if($gc_list->store_concept == 'Digital Walker and Beyond the Box'){
			// 	$store_logos_id = 3;
			// }else if($gc_list->store_concept == 'Open Source'){
			// 	$store_logos_id = 4;
			// }

			$data = [];
			$data['page_title'] = $gc_list->store_status == 1 ? 'Pending Invoice' : 'OIC Approval';
			$data['customer'] = $gc_list;
			$data['egcs'] = $egc_value;
			$data['email_testing'] = $email_testings->where('store_logos_id', $store_logos_id)->where('status','ACTIVE')->first();

			$data['original_history'] = StoreHistory::where('g_c_lists_devps_id', $id)
				->leftJoin('egc_value_types as egc_value', 'egc_value.id', 'store_histories.egc_value_id')
				->select('store_histories.*',
					'egc_value.name as egc_value_id')
				->first();
			$data['history'] = StoreHistory::where('g_c_lists_devps_id', $id)
				->leftJoin('egc_value_types as egc_value', 'egc_value.id', 'store_histories.egc_value_id')
				->leftJoin('cms_users as user', 'user.id', 'store_histories.created_by')
				->select('store_histories.*',
					'egc_value.name as egc_value_id',
					'user.name as created_by')
				->skip(1) // Skip the first row
				->take(PHP_INT_MAX) // Take all remaining records
				->get()
				->toArray();
			
			$data['customer_information'] = []; // Initialize the array outside the loop

			foreach ($data['history'] as $i => $history) {
				$history = array_slice($history, 2, -4);
			
				$filteredData = array_filter($history, function ($value) {
					return $value !== null;
				});
			
				$customerInfo = []; // Initialize the inner array for each iteration
			
				foreach ($filteredData as $key => $value) {
					if ($key == 'egc_value_id') {
						$key = 'egc_value';
					}
				
					// Check if $key is either 'customer_name' or 'egc_name'
					if ($key !== 'customer_name' && $key !== 'egc_name') {
						$customerInfo[$key] = $value;
					}
				}
				
				$data['customer_information'][$i] = $customerInfo;
				
			}

			return $this->view('customer.customer_edit',$data);
			
		}

		public function getDetail($id) {
			
			$gc_list = DB::table('g_c_lists_devps')->where('g_c_lists_devps.id', $id)
				->leftJoin('store_concepts', 'store_concepts.id', 'g_c_lists_devps.store_concepts_id')
				->leftJoin('g_c_lists_devps_customers', 'g_c_lists_devps_customers.id', 'g_c_lists_devps.id')
				->select('g_c_lists_devps.*',
				'store_concepts.name as store_branch',
				'g_c_lists_devps_customers.name as cus_name',
				'g_c_lists_devps_customers.first_name as cus_first_name',
				'g_c_lists_devps_customers.last_name as cus_last_name',
				'g_c_lists_devps_customers.email as cus_email',
				'g_c_lists_devps_customers.phone as cus_phone',
				)
				->get()
				->first();
			
			$egc_value = DB::table('egc_value_types')->get();

			$data = [];
			$data['page_title'] = $gc_list->store_status == 1 ? 'Pending Invoice' : 'OIC Approval';
			$data['customer'] = $gc_list;
			$data['egcs'] = $egc_value;
			$data['history'] = StoreHistory::where('g_c_lists_devps_id', $id)->get();

						
			$data['original_history'] = StoreHistory::where('g_c_lists_devps_id', $id)
				->leftJoin('egc_value_types as egc_value', 'egc_value.id', 'store_histories.egc_value_id')
				->select('store_histories.*',
					'egc_value.name as egc_value_id')
				->first();
			$data['history'] = StoreHistory::where('g_c_lists_devps_id', $id)
				->leftJoin('egc_value_types as egc_value', 'egc_value.id', 'store_histories.egc_value_id')
				->leftJoin('cms_users as user', 'user.id', 'store_histories.created_by')
				->select('store_histories.*',
					'egc_value.name as egc_value_id',
					'user.name as created_by')
				->skip(1) // Skip the first row
				->take(PHP_INT_MAX) // Take all remaining records
				->get()
				->toArray();
			
			$data['customer_information'] = []; // Initialize the array outside the loop

			foreach ($data['history'] as $i => $history) {
				$history = array_slice($history, 2, -4);
			
				$filteredData = array_filter($history, function ($value) {
					return $value !== null;
				});
			
				$customerInfo = []; // Initialize the inner array for each iteration
			
				foreach ($filteredData as $key => $value) {
					if ($key == 'egc_value_id') {
						$key = 'egc_value';
					}
				
					// Check if $key is either 'customer_name' or 'egc_name'
					if ($key !== 'customer_name' && $key !== 'egc_name') {
						$customerInfo[$key] = $value;
					}
				}
				
				$data['customer_information'][$i] = $customerInfo;
			}

			// dd($data['history']);

			return $this->view('customer.customer_detail',$data);
			
		}

		public function pendingInvoice(Request $request){

			$customer = $request->all();
			$customer_id = $customer['id'];
			$crudbooster_my_id = CRUDBooster::myId();
			$cms_user = DB::table('cms_users')->where('id', $crudbooster_my_id)->first();
			$store_name = StoreConcept::find($cms_user->id_store_concept);
			$store_invoice_number = $customer['store_invoice_number'];

			$egc_value = EgcValueType::where('value',(int) $customer['egc_value'])->first();

			$gclists_devps = DB::table('g_c_lists_devps')->where('id', $customer['id']);
			$gclists_devps_customer = DB::table('g_c_lists_devps_customers')->where('id', $gclists_devps->first()->g_c_lists_devps_customer_id);
			
			// $previous_entry = DB::table('store_histories')->where('g_c_lists_devps_id', $customer['id'])
			// ->latest('id')
			// ->value('egc_value_id');

			// dd($customer,$egc_value->id);

			// For Testing
			// $invoice_number_exists = DB::table('g_c_lists_devps')->where('id', $store_invoice_number)->exists();
			
			// $invoice_number_exists = DB::connection('mysql_tunnel')
			// ->table('pos_sale')
			// ->where('fcompanyid',$store_name->fcompanyid)
			// ->where('fofficeid',$store_name->branch_id)
			// ->where('fdocument_no',$invoice_number)
			// ->where('ftermid', $store_name->ftermid)
			// ->where('fdoctype',6000)
			// ->exists();

			// if(!$invoice_number_exists){
			// 	return CRUDBooster::redirect(
			// 		CRUDBooster::mainpath('edit'."/$customer_id"),
			// 		"Invoice number does not match to the system, please try again or contact BPG for assistance.",
			// 		'danger'
			// 	)->send();
			// }

			// $gclists_devps->update([
			// 	'first_name' => $customer['first_name'],
			// 	'last_name' => $customer['last_name'],
			// 	'name' => $customer['first_name'].' '.$customer['last_name'],
			// 	'email' => $customer['email'],
			// 	'phone' => $customer['contact_number'],
			// 	'store_status' => 3,
			// 	'egc_value_id' => $egc_value->id,
			// 	'st_cashier_id' => CRUDBooster::myId(),
			// 	'st_cashier_date_transact' => date('Y-m-d H:i:s')
			// ]);

			// $gclists_devps_customer->update([
			// 	'first_name' => $customer['cus_first_name'],
			// 	'last_name' => $customer['cus_last_name'],
			// 	'name' => $customer['cus_first_name'].' '.$customer['cus_last_name'],
			// 	'email' => $customer['cus_email'],
			// 	'phone' => $customer['cus_contact_number'],
			// 	'updated_by' => CRUDBooster::myId(),
			// 	'updated_at' => date('Y-m-d H:i:s')
			// ]);

			// if($egc_value->id != $previous_entry){

			// 	$store_history = DB::table('store_histories')->insert([
			// 		'g_c_lists_devps_id' => $customer['id'],
			// 		'egc_value_id' => $egc_value->id,
			// 		'created_by' => CRUDBooster::myId(),
			// 		'created_at' => date('Y-m-d H:i:s')
			// 	]);
			// }
			$customer_previous_entry = DB::table('g_c_lists_devps_customers')->where('id', $customer['id'])
				->latest('id')
				->first();
			$egc_previous_entry = DB:: table('g_c_lists_devps')->where('g_c_lists_devps_customer_id', $customer['id'] )
				->latest('id')
				->first();
			
			$egc_previous_value = DB::table('store_histories')->where('g_c_lists_devps_id', $customer['id'])
				->latest('id')
				->first();

			$egc_cus_previous_value = EgcValueType::where('id',$egc_previous_entry->egc_value_id)->first();


			$combined_previous_entry = [
				'cus_first_name' => $customer_previous_entry->first_name,
				'cus_last_name' => $customer_previous_entry->last_name,
				'cus_email' => $customer_previous_entry->email,
				'cus_contact_number' => $customer_previous_entry->phone,
				'first_name'=> $egc_previous_entry->first_name,
				'last_name' => $egc_previous_entry->last_name,
				'email' => $egc_previous_entry->email,
				'contact_number' => $egc_previous_entry->phone,
				'egc_value' => strval($egc_cus_previous_value->value),
				// Add more fields if needed
			];
			
			$result = [];
			$is_to_insert = false;

			foreach ($combined_previous_entry as $key => $value) {
				// Check if the key exists in both arrays
				// if (isset($customer[$key]) && isset($combined_previous_entry[$key])) {
				// 	// Compare the values
				// 	$result[$key] = ($customer[$key] == $combined_previous_entry[$key]) ? null : $customer[$key];
				// }
				if ($customer[$key] != $combined_previous_entry[$key]) {
					$is_to_insert = true;
					$result[$key] = $customer[$key];
				} else {
					$result[$key] = null;
				}
			}

			
			if($customer['egc_value'] == $combined_previous_entry['egc_value']){
				$result['egc_value'] = null;
			} else {
				$result['egc_value'] = EgcValueType::where('value',(int) $customer['egc_value'])->first()->id;
				$is_to_insert = true;
			}
			if ($is_to_insert) {
				$store_history = DB::table('store_histories')->insert([
					'g_c_lists_devps_id' => $customer['id'],
					'customer_first_name' => $result['cus_first_name'],
					'customer_last_name' => $result['cus_last_name'],
					'customer_name' => $result['cus_first_name'] || $result['cus_last_name'] ? $result['cus_first_name'] . ' ' . $result['cus_last_name'] : null,
					'customer_phone' => $result['cus_contact_number'],
					'customer_email' => $result['cus_email'],
					'egc_first_name' => $result['first_name'],
					'egc_last_name' => $result['last_name'],
					'egc_name' => $result['first_name'] || $result['last_name'] ? $result['first_name'] . ' ' . $result['last_name'] : null,
					'egc_phone' => $result['contact_number'],
					'egc_email' => $result['email'],
					'egc_value_id' => $result['egc_value'],
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				$gclists_devps->update([
					'updated_by' => CRUDBooster::myId(),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}

			$gclists_devps->update([
				'first_name' => $customer['first_name'],
				'last_name' => $customer['last_name'],
				'name' => $customer['first_name'].' '.$customer['last_name'],
				'email' => $customer['email'],
				'phone' => $customer['contact_number'],
				'store_status' => 3,
				'egc_value_id' => $egc_value->id,
				'st_oic_id' => CRUDBooster::myId(),
				'st_oic_date_transact' => date('Y-m-d H:i:s')
			]);

			$gclists_devps_customer->update([
				'first_name' => $customer['cus_first_name'],
				'last_name' => $customer['cus_last_name'],
				'name' => $customer['cus_first_name'].' '.$customer['cus_last_name'],
				'email' => $customer['cus_email'],
				'phone' => $customer['cus_contact_number'],
				'updated_by' => CRUDBooster::myId(),
				'updated_at' => date('Y-m-d H:i:s')
			]);

			return CRUDBooster::redirect(
				CRUDBooster::mainpath(),
				"Your transaction edited successfully. Where E-gift card value: {$customer['egc_value']} and Invoice number: {$customer['store_invoice_number']}.",
				'success'
			)->send();
		}

		public function pendingOIC(Request $request){

			$customer = $request->all();
			
			$egc_value = EgcValueType::where('value',(int) $customer['egc_value'])->first();
			
			$gclists_devps = DB::table('g_c_lists_devps')->where('id', $customer['id']);
			$gclists_devps_customer = DB::table('g_c_lists_devps_customers')->where('id', $gclists_devps->first()->g_c_lists_devps_customer_id);
			
			$customer_previous_entry = DB::table('g_c_lists_devps_customers')->where('id', $customer['id'])
				->latest('id')
				->first();
			$egc_previous_entry = DB:: table('g_c_lists_devps')->where('g_c_lists_devps_customer_id', $customer['id'] )
				->latest('id')
				->first();
			
			$egc_previous_value = DB::table('store_histories')->where('g_c_lists_devps_id', $customer['id'])
				->latest('id')
				->first();
			$egc_cus_previous_value = EgcValueType::where('id',$egc_previous_entry->egc_value_id)->first();
			$combined_previous_entry = [
				'cus_first_name' => $customer_previous_entry->first_name,
				'cus_last_name' => $customer_previous_entry->last_name,
				'cus_email' => $customer_previous_entry->email,
				'cus_contact_number' => $customer_previous_entry->phone,
				'first_name'=> $egc_previous_entry->first_name,
				'last_name' => $egc_previous_entry->last_name,
				'email' => $egc_previous_entry->email,
				'contact_number' => $egc_previous_entry->phone,
				'egc_value' => strval($egc_cus_previous_value->value),
				// Add more fields if needed
			];
			
			$result = [];
			$is_to_insert = false;

			foreach ($combined_previous_entry as $key => $value) {
				// Check if the key exists in both arrays
				// if (isset($customer[$key]) && isset($combined_previous_entry[$key])) {
				// 	// Compare the values
				// 	$result[$key] = ($customer[$key] == $combined_previous_entry[$key]) ? null : $customer[$key];
				// }
				if ($customer[$key] != $combined_previous_entry[$key]) {
					$is_to_insert = true;
					$result[$key] = $customer[$key];
				} else {
					$result[$key] = null;
				}
			}

			
			if($customer['egc_value'] == $combined_previous_entry['egc_value']){
				$result['egc_value'] = null;
			} else {
				$result['egc_value'] = EgcValueType::where('value',(int) $customer['egc_value'])->first()->id;
				$is_to_insert = true;

			}
			
			if ($is_to_insert) {

				$store_history = DB::table('store_histories')->insert([
					'g_c_lists_devps_id' => $customer['id'],
					'customer_first_name' => $result['cus_first_name'],
					'customer_last_name' => $result['cus_last_name'],
					'customer_name' => $result['cus_first_name'] || $result['cus_last_name'] ? $result['cus_first_name'] . ' ' . $result['cus_last_name'] : null,
					'customer_phone' => $result['cus_contact_number'],
					'egc_first_name' => $result['first_name'],
					'egc_last_name' => $result['last_name'],
					'egc_name' => $result['first_name'] || $result['last_name'] ? $result['first_name'] . ' ' . $result['last_name'] : null,
					'egc_phone' => $result['contact_number'],
					'egc_email' => $result['email'],
					'egc_value_id' => $result['egc_value'],
					'created_by' => CRUDBooster::myId(),
					'created_at' => date('Y-m-d H:i:s')
				]);

				$gclists_devps->update([
					'updated_by' => CRUDBooster::myId(),
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}

			if($gclists_devps->first()->store_status > 3 && !CRUDBooster::isSuperAdmin()){
				return CRUDBooster::redirect(CRUDBooster::mainpath(), sprintf("You don't have privilege to access this area."),"danger");
			}
			else if($gclists_devps->first()->email_is_sent == 1){
				return CRUDBooster::redirect(CRUDBooster::mainpath(), sprintf("Email is already sent"),"danger");
			}

			$gclists_devps->update([
				'first_name' => $customer['first_name'],
				'last_name' => $customer['last_name'],
				'name' => $customer['first_name'].' '.$customer['last_name'],
				'email' => $customer['email'],
				'phone' => $customer['contact_number'],
				'store_status' => 5,
				'egc_value_id' => $egc_value->id,
				'st_oic_id' => CRUDBooster::myId(),
				'st_oic_date_transact' => date('Y-m-d H:i:s')
			]);

			$gclists_devps_customer->update([
				'first_name' => $customer['cus_first_name'],
				'last_name' => $customer['cus_last_name'],
				'name' => $customer['cus_first_name'].' '.$customer['cus_last_name'],
				'email' => $customer['cus_email'],
				'phone' => $customer['cus_contact_number'],
				'updated_by' => CRUDBooster::myId(),
				'updated_at' => date('Y-m-d H:i:s')
			]);



			$email_testings = new EmailTesting();
			$customer_data = DB::table('g_c_lists_devps')->where('g_c_lists_devps.id', $customer['id'])
			->get()
			->first();

			$url = "/g_c_lists/edit/$customer_data->id?value=$customer_data->qr_reference_number&campaign_id=3";
			$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=' . urlencode($url);
			$qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
			
			$store_logos_id = 5;

			$emailTesting = $email_testings->where('store_logos_id', $store_logos_id)
			->where('status','ACTIVE')
			->first();
			$emailTestingImg = EmailTemplateImg::where('header_id', $emailTesting->id)
				->get();

			$html_email_img = [];

			foreach($emailTestingImg as $file){
				$filename = $file->file_name;
				$html_email_img[]= $filename;
			}

			$html_email = str_replace(
				['[name]'],
				[$customer_data->name],
				$emailTesting->html_email
			);

			$data = array(
				'id' => $customer_data->id,
				'html_email' => $html_email,
				'email_subject' => $emailTesting->subject_of_the_email,
				'html_email_img' => $html_email_img,
				'email' => $customer_data->email,
				'qrCodeApiUrl' => $qrCodeApiUrl,
				'qr_code' => $qr_code,
				'gc_value' => $egc_value->value,
				'store_logo' => $store_logos_id,
				'qr_reference_number'=> $customer_data->qr_reference_number,
			);

			SendEmailJob::dispatch($data);

			return CRUDBooster::redirect(
				CRUDBooster::mainpath(),
				"The e-gift card is being sent to their email.",
				'success'
			)->send();

		}

		public function voidRequest(int $id){

			$gclists_devps = DB::table('g_c_lists_devps')->where('id', $id);
			$reference_number = $gclists_devps->first()->reference_number;

			$gclists_devps->update([
				'store_status' => 7
			]);


			return CRUDBooster::redirect(
				CRUDBooster::mainpath(),
				"Reference#: $reference_number successfully voided",
				'success'
			)->send();
		}

		public function createEGC(Request $request){

			$egc = $request->all();

			$cms_user = DB::table('cms_users')->where('id', CRUDBooster::myId())->first();
			$store_concept = DB::table('store_concepts')->where('id', $cms_user->id_store_concept)->first();
			$store_logos = DB::table('store_logos')->where('concept', $store_concept->concept)->first();
			$store_name = StoreConcept::find($cms_user->id_store_concept);

			if($store_logos->name == 'Digital Walker and Beyond the Box'){
				$user_store_logo = 'dw_and_btb';
			}else{
				$user_store_logo = strtolower(str_replace(' ', '_', $store_logos->name));
			}

			$inv_num = (int) $egc['invoice_number'];
			if($inv_num === 0){
				return CRUDBooster::redirect(
					CRUDBooster::mainpath(),
					"Invoice number does not match to the system, please try again or contact BPG for assistance.",
					'danger'
				)->send();
			}

			// $invoice_number_exists = true;
			
			$invoice_number_exists = DB::connection('mysql_tunnel')
			->table('pos_sale')
			->where('fcompanyid',$store_name->fcompanyid)
			->where('fofficeid',$store_name->branch_id)
			->where('fdocument_no', $inv_num)
			->where('ftermid', (int) $store_name->ftermid)
			->where('fdoctype',6000)
			->exists();

			if(!$invoice_number_exists){
				return CRUDBooster::redirect(
					CRUDBooster::mainpath(),
					"Invoice number does not match to the system, please try again or contact BPG for assistance.",
					'danger'
				)->send();
			}

			do {
				$generated_qr_code = Str::random(10);
			} while (GCList::where('qr_reference_number', $generated_qr_code)->exists());

			$gclist_devp_customer = new GCListsDevpsCustomer([
				'created_by' => CRUDBooster::myId(),
				'store_concept' => $store_logos->name
			]);
			$gclist_devp_customer->save();

			$gclist_devp = new g_c_lists_devp([
				'store_status' => 1,
				'store_concept' => $store_logos->name,
				'store_concepts_id' => $store_concept->id,
				'reference_number' => sprintf("CUS-%06d", $gclist_devp_customer->id),
				'g_c_lists_devps_customer_id' => $gclist_devp_customer->id,
				'egc_value_id' => $egc['egc_value'],
				'store_invoice_number' => $egc['invoice_number'],
				'qr_reference_number' => $generated_qr_code,
				'created_by' => CRUDBooster::myId()
			]);
			$gclist_devp->save();

			$store_history = new StoreHistory([
				'g_c_lists_devps_id' => $gclist_devp->id,
				'egc_value_id' => $egc['egc_value'],
				
			]);
			$store_history->save();

			$sc_name = str_replace(' ', '_', $store_concept->name);
			$url = url("qr_link/$user_store_logo/$sc_name/$gclist_devp->qr_reference_number");

			$gclist_devp->qr_link = $url;
			$gclist_devp->save();

			return redirect()->back()->with('success', $url);
		}

		public function egcValue(){

			$data = [];
			$data['egc_value'] = EgcValueType::orderBy('value', 'asc')->get();

			return $data;
		}

	}