<?php namespace App\Http\Controllers;

	use App\GCList;
	use App\GcListSummaryView;
	use App\IdType;
	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use Yajra\DataTables\Facades\DataTables;
	use App\Exports\GcListExport;
	use App\Exports\CampaignExport;
	use App\Exports\StoreExport;
	use App\Exports\SubscriberExport;
	use Maatwebsite\Excel\Facades\Excel;
	use App\Http\Controllers\Controller;

	class AdminGCListsHistoryController extends \crocodicstudio\crudbooster\controllers\CBController {

		function __construct(){
			
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
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "g_c_lists";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Item Receipt","name"=>"uploaded_img"];
			$this->col[] = ["label"=>"Redeemed Date","name"=>"cashier_date_transact"];
			$this->col[] = ["label"=>"Name","name"=>"name"];
			$this->col[] = ["label"=>"Phone","name"=>"phone"];
			$this->col[] = ["label"=>"Customer Email","name"=>"email"];
			$this->col[] = ["label"=>"Cashier Email","name"=>"cashier_name","join"=>"cms_users,email"];
			$this->col[] = ["label"=>"Campaign ID", "name"=>"campaign_id", "join"=>"qr_creations,campaign_id"];
			$this->col[] = ["label"=>"GC Description","name"=>"campaign_id", "join"=>"qr_creations,gc_description"];
			$this->col[] = ["label"=>"GC Value","name"=>"campaign_id", "join"=>"qr_creations,gc_value"];
			$this->col[] = ["label"=>"GC Reference Number","name"=>"id", "join"=>"g_c_lists_view,gclists", "join_id"=>"id"];
			$this->col[] = ["label"=>"POS Invoice Number","name"=>"invoice_number"];
			$this->col[] = ["label"=>"POS Terminal","name"=>"pos_terminal"];
			$this->col[] = ["label"=>"Status","name"=>"accounting_is_audit"];
			// $this->col[] = ["label"=>"Number of Gcs", "name"=>"campaign_id", "join"=>"qr_creations,number_of_gcs"];
			// $this->col[] = ["label"=>"Batch Group","name"=>"campaign_id","join"=>"qr_creations,batch_group"];
			// $this->col[] = ["label"=>"Batch Number","name"=>"campaign_id","join"=>"qr_creations,batch_number"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			// $this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|min:3|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
			// $this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required|min:1|max:255|email|unique:g_c_lists','width'=>'col-sm-10','placeholder'=>'Please enter a valid email address'];
			// $this->form[] = ['label'=>'Number Of Gcs','name'=>'number_of_gcs','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
			// $this->form[] = ['label'=>'Gc Description','name'=>'gc_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
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
			if(CRUDBooster::myPrivilegeName() == 'Marketing Head' || CRUDBooster::myPrivilegeName() == 'Marketing'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('detail/[id]'),'icon'=>'fa fa-eye'];
			}else if(CRUDBooster::myPrivilegeName() == 'Accounting Head' || CRUDBooster::myPrivilegeName() == 'Accounting'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil'];
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('detail/[id]'),'icon'=>'fa fa-eye','color'=>'warning'];
			}else if(CRUDBooster::myPrivilegeName() == 'Super Administrator'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil'];
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('detail/[id]'),'icon'=>'fa fa-eye','color'=>'warning'];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){

				$this->index_button[] = ['label'=>'Export','url'=>CRUDBooster::mainpath("gclist_export"),"icon"=>"fa fa-download", 'color'=>'primary'];
				$this->index_button[] = ['label'=>'Export Campaign e-GC','url'=>CRUDBooster::mainpath("campaign_export"),"icon"=>"fa fa-download", 'color'=>'primary'];
				$this->index_button[] = ['label'=>'Export Store e-GC','url'=>CRUDBooster::mainpath("store_export"),"icon"=>"fa fa-download", 'color'=>'primary'];
				if (in_array(CRUDBooster::myPrivilegeId(), [1, 2, 4, 6])){
					$this->index_button[] = ['label'=>'Export Subscriber','url'=>CRUDBooster::mainpath("subscriber_export"),"icon"=>"fa fa-download", 'color'=>'primary'];
				}
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
	        $this->script_js = null;


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
	        // $query
			// 	->where(function($sub_query){
			// 		$sub_query->where('invoice_number', '!=', null)->where('status', '!=', 'EXPIRED');
			// 	});
			// $query->where('g_c_lists.uploaded_img', '!=', null);
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
			if($column_index == '2'){
				$url = asset('uploaded_item/img/');
				$column_value = "<img src='$url"."/$column_value' style='max-height: 100px; max-width: 120px;'>";
			}else if($column_index == '14'){
				if($column_value == '1'){
					$column_value = '<span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">CLOSED</span>';
				}else{
					$column_value = '<span class="label" style="background-color: rgb(74,222,128); color: white; font-size: 12px;">CLAIMED</span>';
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

			$return_input = Request::all();

			$campaign_id = $return_input['campaign_id'];
			$uploaded_img = $return_input['uploaded_img'];

			if($campaign_id){
				DB::table('g_c_lists')->where('uploaded_img', $uploaded_img)->update([
					'accounting_id_transact'=>CRUDBooster::myId(),
					'accounting_date_transact'=>date('Y-m-d H:i:s'),
					'accounting_is_audit'=>1
				]);
			}
			else{
				DB::table('g_c_lists_devps')->where('uploaded_img', $uploaded_img)->update([
					'accounting_id_transact'=>CRUDBooster::myId(),
					'accounting_date_transact'=>date('Y-m-d H:i:s'),
					'accounting_is_audit'=>1
				]);
			}
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

		public function getIndex() {
			//First, Add an auth
			// if(!CRUDBooster::isView()) CRUDBooster::redirect(CRUDBooster::adminPath(),trans('crudbooster.denied_access'));
			
			//Create your own query 
			$data = [];
			$data['page_title'] = ' Redemption Code History';
			$data['customers'] = DB::table('g_c_lists_summary_view')
				->where('uploaded_img', '!=', null)
				// ->orderBy('id', 'asc')->
				->get();
			// dd($data['customers']);
			//Create a view. Please use `cbView` method instead of view method from laravel.
			return $this->view('redeem_qr.custom_egc_list',$data);
		}

		public function getDetail($id) {
			//Create an Auth
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data['page_title'] = 'Redeem QR Details';
			$data = [];

			$campaign_types_id = Request::all()['campaign_types_id'];
			$uploaded_img = Request::all()['uploaded_img'];


			if((int) $campaign_types_id == 3){

				$data['row'] = DB::table('g_c_lists_devps')
					->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists_devps.id_type')
					->leftJoin('egc_value_types as egc', 'egc.id' ,'=', 'g_c_lists_devps.egc_value_id')
					->leftJoin('store_concepts as sc', 'sc.id' ,'=', 'g_c_lists_devps.store_concepts_id')
					->select('g_c_lists_devps.*',
						'id_name.valid_ids',
						'egc.value as gc_value',
						'sc.name as store_concept_name'
					)
					->where('g_c_lists_devps.uploaded_img',$uploaded_img)
					->first();
			}else{

				$data['row'] = DB::table('g_c_lists')
				->leftJoin('id_types as id_name', 'id_name.id' , 'g_c_lists.id_type')
				->leftJoin('qr_creations as qr', 'qr.id', 'g_c_lists.campaign_id')
				->leftJoin('cms_users', 'cms_users.id', 'g_c_lists.cashier_name')
				->leftJoin('store_concepts as sc', 'sc.id', 'cms_users.id_store_concept')
				->select('g_c_lists.*',
					'qr.campaign_id',
					'qr.gc_description',
					'qr.gc_value',
					'qr.number_of_gcs',
					'qr.batch_group',
					'qr.batch_number',
					'id_name.valid_ids',
					'sc.name as store_concept_name',
				)
				->where('g_c_lists.uploaded_img',$uploaded_img)
				->first();
			}

			if(!$data['row']->uploaded_img){
				CRUDBooster::redirect(CRUDBooster::mainpath(), "You don't have access to this area", 'warning');

			}

			$data['valid_ids'] = IdType::get();


			// Generate QR Code
			$qrCodeData = $data['row']->email.'|'.$data['row']->id;

			return $this->view('redeem_qr.qr_redeem_section_view',$data);

		}

		public function getEdit($id) {
			//Create an Auth
			if(!CRUDBooster::isUpdate() && $this->global_privilege==FALSE || $this->button_edit==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data['page_title'] = 'Redeem QR Edit';
			$data = [];

			$campaign_types_id = Request::all()['campaign_types_id'];
			$uploaded_img = Request::all()['uploaded_img'];

			if((int) $campaign_types_id == 3){

				$data['row'] = DB::table('g_c_lists_devps')
					->leftJoin('id_types as id_name', 'id_name.id' ,'=', 'g_c_lists_devps.id_type')
					->leftJoin('egc_value_types as egc', 'egc.id' ,'=', 'g_c_lists_devps.egc_value_id')
					->select('g_c_lists_devps.*',
						'id_name.valid_ids',
						'egc.value as gc_value')
					->where('g_c_lists_devps.uploaded_img',$uploaded_img)
					->first();
			}else{

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
				->where('g_c_lists.uploaded_img',$uploaded_img)
				->first();
			}

			if(!$data['row']->uploaded_img){
				CRUDBooster::redirect(CRUDBooster::mainpath(), "You don't have access to this area", 'warning');

			}

			$data['valid_ids'] = IdType::get();
	
			// Generate QR Code
			$qrCodeData = $data['row']->email.'|'.$data['row']->id;

			//Please use view method instead view method from laravel
			return $this->view('redeem_qr.qr_redeem_section_history_edit',$data);

		}

		public function getGCList(){
			return DataTables::of(GcListSummaryView::query()
			->select('uploaded_img', 
				'name', 
				'phone', 
				'email', 
				'campaign_id', 
				'gc_description', 
				'gc_value', 
				'gclists', 
				'invoice_number', 
				'pos_terminal', 
				DB::raw('CASE WHEN accounting_is_audit = 1 THEN "CLOSED" ELSE "CLAIMED" END AS accounting_is_audit'))
			->where('uploaded_img', '!=', null)
		)
		->filterColumn('accounting_is_audit', function($query, $keyword) {
			$query->whereRaw("CASE WHEN accounting_is_audit = 1 THEN 'CLOSED' ELSE 'CLAIMED' END like ?", ["%{$keyword}%"]);
		})
		->make(true);
		}

		public function export() {
			return Excel::download(new GcListExport, 'gclist.xlsx');
		}

		public function exportCGc() {
			return Excel::download(new CampaignExport, 'campaign_gclist.xlsx');
		}

		public function exportSGc() {
			return Excel::download(new StoreExport, 'store_gclist.xlsx');
		}
		
		public function exportSubscriber(){
			return Excel::download(new SubscriberExport, 'subscriber.xlsx');
		}

	}