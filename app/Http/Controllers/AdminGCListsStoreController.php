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
			$this->col[] = ["label"=>"Status","name"=>"store_status","join"=>"store_statuses,name"];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Email","name"=>"email"];
			$this->col[] = ["label"=>"Phone","name"=>"phone"];
			$this->col[] = ["label"=>"Concept","name"=>"store_concept"];
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
			if(CRUDBooster::isSuperAdmin()){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil'];
			}else{
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', 'showIf' => 'in_array([store_status], [1, 2, 5])'];

			}


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
	        //Your code here
	        
			$query->where('store_concept', '!=', null);
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {
			
			if($column_index == 2){
				if($column_value == 'Pending Invoice'){
					$column_value = '<span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">Pending Invoice</span>';
				}
				else if($column_value == 'OIC Approval'){
					$column_value = '<span class="label" style="background-color: #77BFA3; color: white; font-size: 12px;">OIC Approval</span>';
				}
				else if($column_value == 'Email Sent'){
					$column_value = '<span class="label" style="background-color: rgb(74 222 128); color: white; font-size: 12px;">Email Sent</span>';
				}
				else if($column_value == 'Email Failed'){
					$column_value = '<span class="label" style="background-color: rgb(239 68 68); color: white; font-size: 12px;">Email Failed</span>';
				}
				else if($column_value == 'Email Dispatched'){
					$column_value = '<span class="label" style="background-color: rgb(255, 179, 102); color: white; font-size: 12px;">Email Dispatched</span>';
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
				->get()
				->first();
			
			$egc_value = DB::table('egc_value_types')->get();
			$email_testings = new EmailTesting();

			$store_logos_id = 0;

			if($gc_list->store_concept == 'Digital Walker'){
				$store_logos_id = 1;
			}else if($gc_list->store_concept == 'Beyond the Box'){
				$store_logos_id = 2;
			}else if($gc_list->store_concept == 'Digital Walker and Beyond the Box'){
				$store_logos_id = 3;
			}else if($gc_list->store_concept == 'Open Source'){
				$store_logos_id = 4;
			}

			$data = [];
			$data['page_title'] = $gc_list->store_status == 1 ? 'Pending Invoice' : 'OIC Approval';
			$data['customer'] = $gc_list;
			$data['egcs'] = $egc_value;
			$data['email_testing'] = $email_testings->where('store_logos_id', $store_logos_id)->where('status','ACTIVE')->first();
			
			return $this->view('customer.customer_edit',$data);
			
		}

		public function getDetail($id) {
			
			$gc_list = DB::table('g_c_lists_devps')->where('g_c_lists_devps.id', $id)
				->get()
				->first();
			
			$egc_value = DB::table('egc_value_types')->get();

			$data = [];
			$data['page_title'] = $gc_list->store_status == 1 ? 'Pending Invoice' : 'OIC Approval';
			$data['customer'] = $gc_list;
			$data['egcs'] = $egc_value;

			return $this->view('customer.customer_detail',$data);
			
		}

		public function pendingInvoice(Request $request){

			$customer = $request->all();

			$egc_value = EgcValueType::where('value',(int) $customer['egc_value'])->first();

			$gc_list = DB::table('g_c_lists_devps')->where('id', $customer['id'])
				->update([
					'store_status' => 2,
					'egc_value_id' => $egc_value->id,
					'invoice_number' => $customer['invoice_number'],
					'st_cashier_id' => CRUDBooster::myId(),
					'st_cashier_date_transact' => date('Y-m-d H:i:s')
				]);

			return CRUDBooster::redirect(
				CRUDBooster::mainpath(),
				"Your transaction edited successfully. Where E-gift card value: {$customer['egc_value']} and Invoice number: {$customer['invoice_number']}.",
				'success'
			)->send();
		}

		public function pendingOIC(Request $request){

			$customer = $request->all();

			$egc_value = EgcValueType::where('value',(int) $customer['egc_value'])->first();

			DB::table('g_c_lists_devps')->where('id', $customer['id'])
			->update([
				'store_status' => 4,
				'egc_value_id' => $egc_value->id,
				'invoice_number' => $customer['invoice_number'],
				'st_oic_id' => CRUDBooster::myId(),
				'st_oic_date_transact' => date('Y-m-d H:i:s')
			]);

			$email_testings = new EmailTesting();
			$customer_data = DB::table('g_c_lists_devps')->where('g_c_lists_devps.id', $customer['id'])
			->get()
			->first();

			$url = "/g_c_lists/edit/$customer_data->id?value=$customer_data->qr_reference_number&campaign_id=3";
			$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
			$qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
			
			$store_logos_id = 0;

			if($customer_data->store_concept == 'Digital Walker'){
				$store_logos_id = 1;
			}else if($customer_data->store_concept == 'Beyond the Box'){
				$store_logos_id = 2;
			}else if($customer_data->store_concept == 'BTB x open_source'){
				$store_logos_id = 3;
			}else if($customer_data->store_concept == 'open_source'){
				$store_logos_id = 4;
			}

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

	}