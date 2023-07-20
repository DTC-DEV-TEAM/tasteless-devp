<?php namespace App\Http\Controllers;

use App\CampaignStatus;
use App\CompanyId;
use App\IdType;
use Session;
use Request;
use DB;
use CRUDBooster;
use Illuminate\Http\Request as IlluminateRequest;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\GcListImport;
use App\Exports\GCListTemplateExport;
use App\GCList;
use App\QrCreation;
use App\EmailTesting;
use Mail;
use Illuminate\Support\Facades\Session as UserSession;
use App\Mail\QrEmail;
use App\Jobs\SendEmailJob;
use App\StoreConcept;
use App\StoreLogo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;


	class AdminQrCreationsController extends \crocodicstudio\crudbooster\controllers\CBController {

		public function __construct() {

			date_default_timezone_set("Asia/Manila");
			date_default_timezone_get();
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

		public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = false;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "qr_creations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			// $this->col[] = ["label"=>"ID","name"=>"id"];
			$this->col[] = ["label"=>"Campaign Id","name"=>"campaign_id"];
			$this->col[] = ["label"=>"Gc Description","name"=>"gc_description"];
			$this->col[] = ["label"=>"Gc Value","name"=>"gc_value"];
			$this->col[] = ["label"=>"Number Of Gcs","name"=>"batch_number"];
			$this->col[] = ["label"=>"Batch Group","name"=>"batch_group"];
			// $this->col[] = ["label"=>"Batch Number","name"=>"batch_number"];
			$this->col[] = ["label"=>"Company Name","name"=>"company_id","join"=>"company_ids,company_name"];
			$this->col[] = ["label"=>"Created By","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created At","name"=>"created_at"];
			$this->col[] = ["label"=>"Campaign Status","name"=>"campaign_status"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Campaign Id','name'=>'campaign_id','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Gc Description','name'=>'gc_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Gc Value','name'=>'gc_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Number Of Gcs','name'=>'batch_number','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Batch Group','name'=>'batch_group','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'PO Number','name'=>'po_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Company Name','name'=>'company_id','type'=>'select','validation'=>'required|min:1|max:255',"datatable"=>"company_ids,company_name",'width'=>'col-sm-6'];
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

			if(CRUDBooster::myPrivilegeName() == 'Head'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[campaign_status] == 1"];
			}elseif(CRUDBooster::myPrivilegeName() == 'Accounting'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[campaign_status] == 2"];
			}elseif(CRUDBooster::myPrivilegeName() == 'Admin'){
				return;
			}else{
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil'];
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
			$this->script_js = '';


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
			$query->orderByRaw("
			CASE
				WHEN campaign_status = 'campaign_status' THEN 1
				WHEN campaign_status = 3 THEN 2
				WHEN campaign_status = 1 THEN 3
				WHEN campaign_status = 2 THEN 4
				WHEN campaign_status = 4 THEN 5
				ELSE 6
			END
		");
		
		}

		/*
		| ---------------------------------------------------------------------- 
		| Hook for manipulate row of index table html 
		| ---------------------------------------------------------------------- 
		|
		*/    
		public function hook_row_index($column_index,&$column_value) {	        
			//Your code here
			if($column_index == '10'){
				if($column_value == '1'){
					$column_value = '<span class="label" style="background-color: rgb(34 211 238); color: white; font-size: 12px;">FOR APPROVAL</span>';
				}else if($column_value == '2'){
					$column_value = '<span class="label" style="background-color: rgb(251 146 60);
					; color: white; font-size: 12px;">FOR ACCOUNTING APPROVAL</span>';
				}
				else if($column_value == '3'){
					$column_value = '<span class="label" style="background-color: rgb(74 222 128);
					; color: white; font-size: 12px;">APPROVED</span>';
				}else{
					$column_value = '<span class="label" style="background-color: rgb(239 68 68);
					; color: white; font-size: 12px;">REJECTED</span>';
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

			return CRUDBooster::redirect(CRUDBooster::mainpath(), sprintf("The Campaign ID has been added."),"success")->send();

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
			
			$postdata['updated_by'] = CRUDBooster::myId();
			$postdata['updated_at'] = date('Y-m-d H:i:s');
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
		// public function getEdit($id){

		// 	if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
		// 		CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
		// 	}
			
		// 	$data = [];
		// 	$data['page_title'] = 'Upload GC List';
		// 	$data['row'] = DB::table('qr_creations')->find($id);
		// 	$data['valid_ids'] = IdType::get();
		// 	$data['email_templates'] = EmailTesting::get();
	
		// 	return $this->view('redeem_qr.upload_gc_list',$data);

		// }

		public function exportGCListTemplate(){

			return Excel::download(new GCListTemplateExport, 'gc_list_template.xlsx');
		}

		public function uploadGCListPost(IlluminateRequest $request){

			$validatedData = $request->validate([
				'excel_file' => 'required|mimes:xls,xlsx',
			]);
		
			$campaign_id = $request->all()['campaign_id'];
			$email_template_id = $request->all()['email_template_id'];
			$uploaded_excel = $request->file('excel_file');
			
			$import = new GcListImport(compact('campaign_id', 'email_template_id'));
			$rows = Excel::import($import, $uploaded_excel);
			
			// Send Email
			$generated_qr_info = QrCreation::find($campaign_id);

			$gc_list_user = GCList::where('campaign_id', $campaign_id)
				->where('email_is_sent', 0)
				->pluck('id')
				->all();
			
			foreach($gc_list_user as $user){

				$gcList = GCList::find($user);
				
				$id = $gcList->id;
				$name = $gcList->name;
				$email = $gcList->email;
				$generated_qr_code = $gcList->qr_reference_number;
				$campaign_id_qr = $generated_qr_info->campaign_id;
				$gc_description = $generated_qr_info->gc_description;
				$email_template_id = $gcList->email_template_id;

				$email_testing = EmailTesting::find($email_template_id);
				$email_template = $email_testing->html_email;
				$email_subject = $email_testing->subject_of_the_email;

				$url = url('admin/g_c_lists/edit/' . $id.'?value='.$generated_qr_code);
				$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
				$qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
				
				$html_email = str_replace(
					['[name]', '[campaign_id]', '[gc_description]', '[qr_code]'],
					[$name, $campaign_id_qr, $gc_description, $qr_code ],
					$email_template
				);
				
				$data = array(
					'name'=> $name,
					'id' => $id,
					'qr_reference_number'=>$generated_qr_code,
					'campaign_id_qr' => $campaign_id_qr,
					'gc_description' => $gc_description,
					'email' => $email,
					'html_email' => $html_email,
					'email_subject' => $email_subject
				);

				dispatch(new SendEmailJob($data));

				// SendEmailJob::dispatch($data);
			}

			return redirect(route('qr_creations_edit', $campaign_id))->with('success', 'Excel file uploaded successfully. QR codes have been sent to the email addresses.')->send();

		}

		public function getAdd() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$company = CompanyId::get();
			$store = StoreConcept::get();
			$store_logo = StoreLogo::get();

			$data = [];
			$data['page_title'] = 'Add Data';
			$data['company_id'] = $company;
			$data['stores'] = $store;
			$data['store_logo'] = $store_logo;

			// dd($data['company_id']);
			//Please use view method instead view method from laravel
			return $this->view('redeem_qr.add_campaign',$data);
		}

		public function getEdit($id) {
			
			$qr_creation = DB::table('qr_creations')->where('qr_creations.id', $id)
				->leftJoin('company_ids', 'qr_creations.company_id', 'company_ids.id')
				->leftJoin('campaign_statuses', 'qr_creations.campaign_status', 'campaign_statuses.id')
				->leftJoin('cms_users as manager', 'qr_creations.manager_approval', 'manager.id')
				->leftJoin('cms_users as accounting', 'qr_creations.accounting_approval', 'accounting.id')
				->select('qr_creations.*','company_ids.company_name', 'manager.name as manager_name', 'accounting.name as accounting_name', 'campaign_statuses.name as campaign_status_name')
				->get()
				->first();

			$company = CompanyId::get();
			$store = StoreConcept::get();
			$store_logo = StoreLogo::get();

			$data = [];
			$data['page_title'] = 'Detail Campaign Creation';
			$data['company_id'] = $company;
			$data['stores'] = $store;
			$data['qr_creation'] = $qr_creation;
			$data['store_logo'] = $store_logo;

			return $this->view('redeem_qr.add_campaign',$data);
		}

		public function getDetail($id) {
			
			$qr_creation = DB::table('qr_creations')->where('qr_creations.id', $id)
				->leftJoin('company_ids', 'qr_creations.company_id', 'company_ids.id')
				->leftJoin('campaign_statuses', 'qr_creations.campaign_status', 'campaign_statuses.id')
				->leftJoin('cms_users as manager', 'qr_creations.manager_approval', 'manager.id')
				->leftJoin('cms_users as accounting', 'qr_creations.accounting_approval', 'accounting.id')
				->select('qr_creations.*','company_ids.company_name', 'manager.name as manager_name', 'accounting.name as accounting_name', 'campaign_statuses.name as campaign_status_name')
				->get()
				->first();

			// dd($qr_creation);
			$company = CompanyId::get();
			$store = StoreConcept::get();
			$store_logo = StoreLogo::get();

			$data = [];
			$data['page_title'] = 'Detail Campaign Creation';
			$data['company_id'] = $company;
			$data['stores'] = $store;
			$data['qr_creation'] = $qr_creation;
			$data['store_logo'] = $store_logo;

			return $this->view('redeem_qr.add_campaign',$data);
		}

		public function addCampaign(IlluminateRequest $request){
			
			$campaign = $request->all();
			$excel_file = $campaign['po_attachment'];
			
			$request->validate([
				'campaign_id' => 'required|unique:qr_creations'
			]);

			$campaign_stores = implode(",",$campaign['stores']);
			$campaign['number_of_gcs'] = $campaign_stores;
			$campaign['created_by'] = CRUDBooster::myId();
			$campaign['created_at'] = date('Y-m-d H:i:s');
			
			$campaign_information = new QrCreation(Arr::except($campaign , ['_token','stores','po_attachment','id']));
			$campaign_information->save();
			$campaign_information->campaign_status = 1;
			$campaign_information->save();

			if($excel_file){

				$campaign_information->po_attachment = $campaign_information->campaign_id.'.'.$excel_file->getClientOriginalExtension();
				$campaign_information->save();
				$excel_file->move(public_path('uploaded_po/file/'),$campaign_information->po_attachment);
			}

			return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Campaign Creation added succesfully', 'success')->send();
		}

		public function campaignApproval(IlluminateRequest $request){

			$qr_creation = QrCreation::find($request->get('id'));
			
			if($qr_creation->campaign_status == 1){
				if($request->get('approve')){

					$qr_creation->update([
						'manager_approval' => CRUDBooster::myId(),
						'campaign_status' => 2,
						'manager_approval_date' => date('Y-m-d H:i:s')
					]);
				}else{

					$qr_creation->update([
						'manager_approval' => CRUDBooster::myId(),
						'campaign_status' => 4,
						'accounting_approval_date' => date('Y-m-d H:i:s')
					]);
				}
			}elseif($qr_creation->campaign_status == 2){
				if($request->get('approve')){

					$qr_creation->update([
						'accounting_approval' => CRUDBooster::myId(),
						'campaign_status' => 3,
						'billing_number' => $request->get('billing_number'),
						'accounting_approval_date' => date('Y-m-d H:i:s')
					]);
				}else{

					$qr_creation->update([
						'accounting_approval' => CRUDBooster::myId(),
						'campaign_status' => 4,
						'accounting_approval_date' => date('Y-m-d H:i:s')
					]);
				}
			}

			return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Campaign Creation successfully updated', 'success')->send();

		}

	}