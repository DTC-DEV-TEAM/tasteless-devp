<?php namespace App\Http\Controllers;

use App\ChargeTo;
use App\CompanyId;
use App\DateToSendCampaigns;
use DB;
use Session;
use Request;
use CRUDBooster;
use Mail;
use Exception;
use App\GCList;
use App\IdType;
use App\QrCreation;
use App\EmailTesting;
use App\Mail\QrEmail;
use App\Jobs\SendEmailJob;
use App\Jobs\GCListFetchJob;
use App\Jobs\StoreConceptFetchApi;
use App\Imports\GcListImport;
use App\Exports\GCListTemplateExport;
use App\Jobs\CampaignCreationFetchApi;
use App\Jobs\EmailScheduler;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session as UserSession;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\EmailTemplateImg;
use App\QrType;
use App\StoreConcept;
use App\StoreLogo;
use App\Stores;
use Illuminate\Support\Arr;

	class AdminQrCreationsController extends \crocodicstudio\crudbooster\controllers\CBController {

		// Campaign Status
		public $forApprovalStatus = 1;
		public $forAccountingApproval = 2;
		public $approved = 3;
		public $rejected = 4;

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
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = true;
			$this->table = "qr_creations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			// $this->col[] = ["label"=>"ID","name"=>"id"];
			$this->col[] = ["label"=>"Campaign Type","name"=>"campaign_type_id"];
			$this->col[] = ["label"=>"Campaign Id","name"=>"campaign_id"];
			$this->col[] = ["label"=>"Gc Description","name"=>"gc_description"];
			$this->col[] = ["label"=>"Gc Value","name"=>"gc_value"];
			$this->col[] = ["label"=>"Number Of Gcs","name"=>"batch_number"];
			// $this->col[] = ["label"=>"Batch Group","name"=>"batch_group"];
			// $this->col[] = ["label"=>"Batch Number","name"=>"batch_number"];
			// $this->col[] = ["label"=>"Company Name","name"=>"company_id","join"=>"company_ids,company_name"];
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

			if (CRUDBooster::myPrivilegeName() == 'Marketing'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[campaign_status] == $this->forApprovalStatus"];
			}elseif (CRUDBooster::myPrivilegeName() == 'Marketing Head'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[campaign_status] == $this->forApprovalStatus"];
			}elseif (CRUDBooster::myPrivilegeName() == 'Accounting Head'){
				$this->addaction[] = ['title'=>'Edit','url'=>CRUDBooster::mainpath('edit/[id]'),'icon'=>'fa fa-pencil', "showIf"=>"[campaign_status] == $this->forAccountingApproval"];
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
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				if(CRUDBooster::myPrivilegeName() == 'Marketing' || CRUDBooster::myPrivilegeName() == 'Super Administrator'){
					// $this->index_button[] = ['label'=>'Third Party Campaign','url'=>CRUDBooster::mainpath("add"),"icon"=>"fa fa-plus-circle", 'color'=>'success'];
					$this->index_button[] = ['label'=>'In House Campaign','url'=>CRUDBooster::mainpath("getAddIhc"),"icon"=>"fa fa-plus-circle", 'color'=>'success'];
				}
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
		public function actionButtonSelected($id_selected,$button_name) 
		{
			//Your code here
				
		}


		/*
		| ---------------------------------------------------------------------- 
		| Hook for manipulate query of index result 
		| ---------------------------------------------------------------------- 
		| @query = current sql query 
		|
		*/
		public function hook_query_index(&$query) 
		{
			$query->orderByRaw(
				"CASE
					WHEN campaign_status = 1 THEN 1
					WHEN campaign_status = 2 THEN 2
					WHEN campaign_status = 3 THEN 3
					WHEN campaign_status = 4 THEN 5
					ELSE 6
				END"
			);
		
		}

		/*
		| ---------------------------------------------------------------------- 
		| Hook for manipulate row of index table html 
		| ---------------------------------------------------------------------- 
		|
		*/    
		public function hook_row_index($column_index,&$column_value) {	        
			//Your code here
			if($column_index == '9'){
				if($column_value == '1'){
					$column_value = '<span class="label" style="background-color: rgb(31,114,183); color: white; font-size: 12px;">FOR APPROVAL</span>';
				}else if($column_value == '2'){
					$column_value = '<span class="label" style="background-color: rgb(251 146 60);
					color: white; font-size: 12px;">FOR ACCOUNTING APPROVAL</span>';
				}
				else if($column_value == '3'){
					$column_value = '<span class="label" style="background-color: rgb(74 222 128);
					color: white; font-size: 12px;">APPROVED</span>';
				}else{
					$column_value = '<span class="label" style="background-color: rgb(239 68 68);
					color: white; font-size: 12px;">REJECTED</span>';
				}
			}else if($column_index == '2'){
				if($column_value == '1'){
					$column_value = '<span class="label" style="background-color: #297373; color: white; font-size: 12px;">Third Party Campaign</span>';
				}else if($column_value == '2'){
					$column_value = '<span class="label" style="background-color: #584D3D; color: white; font-size: 12px;">In House Campaign</span>';
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
		public function hook_after_add($id) 
		{   
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
		public function hook_before_edit(&$postdata,$id) 
		{        
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
		public function hook_after_edit($id) 
		{
		}

		/* 
		| ---------------------------------------------------------------------- 
		| Hook for execute command before delete public static function called
		| ----------------------------------------------------------------------     
		| @id       = current id 
		| 
		*/
		public function hook_before_delete($id) 
		{
		}

		/* 
		| ---------------------------------------------------------------------- 
		| Hook for execute command after delete public static function called
		| ----------------------------------------------------------------------     
		| @id       = current id 
		| 
		*/
		public function hook_after_delete($id) 
		{
		}

		public function exportGCListTemplate()
		{
			return Excel::download(new GCListTemplateExport, 'gc_list_template.xlsx');
		}

		public function getAdd() 
		{
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
			
			return $this->view('redeem_qr.add_campaign_tpc',$data);
		}

		public function getEdit($id) {
			
			$qr_creation = DB::table('qr_creations')->where('qr_creations.id', $id)
				->leftJoin('company_ids', 'qr_creations.company_id', 'company_ids.id')
				->leftJoin('campaign_statuses', 'qr_creations.campaign_status', 'campaign_statuses.id')
				->leftJoin('cms_users as manager', 'qr_creations.manager_approval', 'manager.id')
				->leftJoin('cms_users as accounting', 'qr_creations.accounting_approval', 'accounting.id')
				->select('qr_creations.*','company_ids.company_name', 'manager.name as manager_name', 'accounting.name as accounting_name', 'campaign_statuses.name as campaign_status_name', 'qr_creations.id as qr_creations_id')
				->get()
				->first();

			$data = [];
			$data['page_title'] = 'Edit Campaign Creation';
			$data['company_id'] = CompanyId::get();
			$data['stores'] = StoreConcept::get();
			$data['qr_creation'] = $qr_creation;
			$data['store_logo'] = StoreLogo::get();
			$data['stores1'] = StoreConcept::whereNotIn('id',explode(',',$qr_creation->number_of_gcs))->get();
			$data['charge_to'] = ChargeTo::where('status','ACTIVE')->whereNull('deleted_at')->where('name','!=','Store')->get();
			$data['excluded_concept'] = Stores::get();
			$data['qr_types'] = QrType::get();

			if($qr_creation->campaign_type_id == 1){
				return $this->view('redeem_qr.add_campaign_tpc',$data);
			}else{
				return $this->view('redeem_qr.add_campaign_ihc',$data);
			}
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

			$data = [];
			$data['page_title'] = 'Detail Campaign Creation';
			$data['company_id'] = CompanyId::get();
			$data['stores'] = StoreConcept::get();
			$data['qr_creation'] = $qr_creation;
			$data['store_logo'] = StoreLogo::get();
			$data['stores1'] = StoreConcept::whereNotIn('id',explode(',',$qr_creation->number_of_gcs))->get();
			$data['charge_to'] = ChargeTo::where('status','ACTIVE')->whereNull('deleted_at')->where('name','!=','Store')->get();
			$data['excluded_concept'] = Stores::get();
			$data['qr_types'] = QrType::get();

			if($qr_creation->campaign_type_id == 1){
				return $this->view('redeem_qr.add_campaign_tpc',$data);
			}else{
				return $this->view('redeem_qr.add_campaign_ihc',$data);
			}
		}

		public function getAddIhc() 
		{
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Add Data';
			$data['company_id'] = CompanyId::get();
			$data['stores'] = StoreConcept::get();
			$data['store_logo'] = StoreLogo::get();
			$data['charge_to'] = ChargeTo::where('status','ACTIVE')->whereNull('deleted_at')->where('name','!=','Store')->get();
			$data['excluded_concept'] = Stores::get();
			$data['qr_types'] = QrType::get();

			return $this->view('redeem_qr.add_campaign_ihc',$data);
		}

		public function addCampaign(IlluminateRequest $request)
		{
			$campaign = $request->all();
			$campaign['campaign_type_id'] = "1";
			$excel_file = $campaign['po_attachment'];
			return;
			
			$cms_users = DB::table('cms_users')->where('id_cms_privileges', 4)->get()->first();
			
			$request->validate([
				'campaign_id' => 'required|unique:qr_creations'
			]);

			$campaign_stores = $campaign['stores'] == null ? $campaign['stores'] = implode(",",StoreConcept::get()->pluck('id')->toArray()) : implode(',',StoreConcept::whereNotIn('id', $campaign['stores'])->get()->pluck('id')->toArray());
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

			// $email = $cms_users->email;
			$email = 'ptice.0318@gmail.com';
			
			// Mail::send(['html' => 'email_testing.campaign_email'], $campaign, function($message) use ($email, $campaign) {
			// 	$message->to('ptice.0318@gmail.com')->subject('DEVP System Update on'." ".$campaign['campaign_id']);
			// 	$message->from(config('send_email.username'), config('send_email.name'));
			// });

			// Mail::send(['html' => 'email_testing.campaign_email'], $data, function($message) use ($test_email, $data) {
			// 	$message->to($test_email)->subject($data['subject_of_the_email']);
			// 	$message->from(env('MAIL_USERNAME'), env('APP_NAME'));
			// });

			return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Campaign Creation added succesfully', 'success')->send();
		}

		public function addCampaignIhc(IlluminateRequest $request)
		{
			$campaign = $request->all();
			$campaign['campaign_type_id'] = "2";
			
			$memo_pdf = $campaign['memo_attachment'];
			$campaign['store'] = $campaign['store'] ? implode(',',$campaign['store']) : null;
			$campaign_stores = $campaign['stores'] == null ? $campaign['stores'] = implode(",",StoreConcept::get()->pluck('id')->toArray()) : implode(',',StoreConcept::whereNotIn('id', $campaign['stores'])->get()->pluck('id')->toArray());
			
			$cms_users = DB::table('cms_users')->where('id_cms_privileges', 4)->get()->first();
			
			$request->validate([
				'campaign_id' => 'required|unique:qr_creations'
			]);

			$campaign['number_of_gcs'] = $campaign_stores;
			$campaign['created_by'] = CRUDBooster::myId();
			$campaign['created_at'] = date('Y-m-d H:i:s');
			
			$campaign_information = new QrCreation(Arr::except($campaign , ['_token','stores','store','po_attachment','memo_attachment','id','qr_creations_id']));
			$campaign_information->save();
			$campaign_information->campaign_status = 1;
			$campaign_information->save();
			
			if($memo_pdf){
				$campaign_information->memo_attachment = $campaign_information->campaign_id.'.'.$memo_pdf->getClientOriginalExtension();
				$campaign_information->save();
				$memo_pdf->move(public_path('uploaded_memo/file/'),$campaign_information->memo_attachment);
			}

			// $email = $cms_users->email;
			// $email = 'ptice.0318@gmail.com';
			
			// Mail::send(['html' => 'email_testing.campaign_email'], $campaign, function($message) use ($email, $campaign) {
			// 	$message->to('ptice.0318@gmail.com')->subject('DEVP System Update on'." ".$campaign['campaign_id']);
			// 	$message->from(config('send_email.username'), config('send_email.name'));
			// });

			// Mail::send(['html' => 'email_testing.campaign_email'], $data, function($message) use ($test_email, $data) {
			// 	$message->to($test_email)->subject($data['subject_of_the_email']);
			// 	$message->from(env('MAIL_USERNAME'), env('APP_NAME'));
			// });

			return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Campaign Creation added succesfully', 'success')->send();
		}

		public function saveCampaignIhc(IlluminateRequest $request)
		{
			$campaign = $request->all();
			$campaign['store'] = $campaign['store'] ? implode(',',$campaign['store']) : null;
			$campaign['number_of_gcs'] = $campaign['stores'] == null ? $campaign['stores'] = implode(",",StoreConcept::get()->pluck('id')->toArray()) : implode(',',StoreConcept::whereNotIn('id', $campaign['stores'])->get()->pluck('id')->toArray());
			$id = $campaign['qr_creations_id'];

			$current = QrCreation::find($id);
			$current_campaign_id = $current->campaign_id;
			
			$campaign_exists = QrCreation::where('campaign_id', $campaign['campaign_id'])->exists();

			if ($campaign_exists && $campaign['campaign_id'] != $current_campaign_id) {
				return redirect()->back()->withErrors(['campaign_id' => 'Campaign ID exists']);
			}else{
				QrCreation::find($campaign['id'])->update(
					Arr::except($campaign, ['stores', 'approve', '_token', 'id', 'qr_creations_id'])
				);

				return CRUDBooster::redirect(CRUDBooster::mainpath(), 'Campaign edited succesfully', 'success')->send();
			}

		}

		public function campaignApproval(IlluminateRequest $request)
		{
			$qr_creation = QrCreation::find($request->get('id'));
			
			$campaign = array(
				'campaign_status' => $qr_creation->campaign_status,
				'campaign_id' => $qr_creation->campaign_id,
				'gc_description' => $qr_creation->gc_description,
				'gc_value' => $qr_creation->gc_value
			);

			if($qr_creation->campaign_status == 1){
				if($request->get('approve')){
					if($qr_creation->campaign_status == 1){
						$qr_creation->update([
							'manager_approval' => CRUDBooster::myId(),
							'campaign_status' => 2,
							'manager_approval_date' => date('Y-m-d H:i:s')
						]);
					}else if($qr_creation->campaign_status == 2){
						$qr_creation->update([
							'accounting_approval' => CRUDBooster::myId(),
							'campaign_status' => 3,
							'accounting_approval_date' => date('Y-m-d H:i:s')
						]);
					}
					// $email = 'ptice.0318@gmail.com';
			
					// Mail::send(['html' => 'email_testing.campaign_email'], $campaign, function($message) use ($email, $campaign) {
					// 	$message->to('ptice.0318@gmail.com')->subject('DEVP System Update on'." ".$campaign['campaign_id']);
					// 	$message->from(config('send_email.username'), config('send_email.name'));
					// });
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

					// $email = 'ptice.0318@gmail.com';
			
					// Mail::send(['html' => 'email_testing.campaign_email'], $campaign, function($message) use ($email, $campaign) {
					// 	$message->to('ptice.0318@gmail.com')->subject('DEVP System Update on'." ".$campaign['campaign_id']);
					// 	$message->from(config('send_email.username'), config('send_email.name'));
					// });
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

		public function Stores(IlluminateRequest $request)
		{
			$return_inputs = $request->all();

			$results = StoreConcept::
				select('id', 'name')
				->where('status', 'ACTIVE')
				->where('name', 'LIKE', '%'. $return_inputs['term']. '%')
				// ->orWhere('id', 'LIKE', '%'. $request->input('q'). '%')
				->orderBy('id')
				->get();

						
			return response()->json($results);

		}

		public function manipulate_image($amount, $qr_api, $store_logo, $qr_reference_number)
		{
			$dw_path = 'store_logo/img/digital_walker';
			$btb_path = 'store_logo/img/beyond_the_box';
			$dw_btb_path = 'store_logo/img/btb_and_dw';
			$os_path = 'store_logo/img/os';
			$store_path = 'store_logo/img/store';
			$dyanamic_img_path = 'email_template_img/img/';
			
			$dw_image = Image::make(public_path($dw_path.'.jpg'));
			$btb_image = Image::make(public_path($btb_path.'.jpg'));
			$dw_btb_image = Image::make(public_path($dw_btb_path.'.png'));
			$os_image = Image::make(public_path($os_path.'.jpg'));
			$store_image = Image::make(public_path($store_path.'.jpg'));
			// $dynamic_image = Image::make(public_path($dyanamic_img_path.$qr_img));
			$save_path = 'e_gift_card/img/';

			if($store_logo == 1){

				$logo_path = $dw_image;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 85;
				$qr_y_position = 35;
				$color = '#d85a5f';
				$shadow = '#000000';

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow);
			}

			elseif($store_logo == 2){

				$logo_path = $btb_image;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow);
			}

			elseif($store_logo == 3){

				$logo_path = $dw_btb_image;
				$filename = $save_path.Str::random(10).'.png';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow);
			}

			if($store_logo == 4){

				$logo_path = $os_image;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow);
			}

			if($store_logo == 5){

				if(strlen((string) $amount) == 4){
					$qr_x_position = 155;
				}else{
					$qr_x_position = 190;
				};

				$logo_path = $store_image;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 800;
				$qr_y_position = 90;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow, $qr_reference_number);
			}

			return $filename;
		}

		public function saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow, $qr_reference_number)
		{
			$text_width = 0; // Initialize outside the closure

			
			$logo_path->text('GIFT CODE: '.$qr_reference_number, 0, -10, function($font) use (&$text_width, $value_width){
				$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
				$font->size(19);

				$textSize = $font->getBoxSize()['width'];
				
				$text_width = ($value_width - $textSize) / 2;
			});

			$logo_path->text('GIFT CODE: '.$qr_reference_number, $text_width, 215, function($font) use ($color, $shadow){
				$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
				$font->size(19);
				$font->color('#6C8692');
			});
			
	
			$logo_path->text('P'.$amount, 0, -10, function($font) use (&$text_width, $value_width, $amount) {
				$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
				$font->size(110);
				
				$textSize = $font->getBoxSize()['width'];
				
				$text_width = ($value_width - $textSize) / 2;
			});

			if($shadow){
				$real_image = $logo_path->text('P'.$amount, $text_width, 190, function($font) use ($color, $shadow){
					$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
					$font->size(110);
					$font->color($shadow);
				});
			}

			$real_image = $logo_path->text('P'.$amount, $text_width, 170, function($font) use ($color, $shadow){
				$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
				$font->size(110);
				$font->color($color);
			});
		
			

			// $rectangleImage = Image::canvas(130, 130, 'rgba(255, 255, 255, 1)');
			// $rectangleImage->rectangle(0, 0, 209, 209, function ($draw) {
			// 	$draw->border(1, '#000');
			// });

			// $real_image->insert($rectangleImage, 'bottom-right', $qr_x_position-5, $qr_y_position-5);

			// $qrCodeApiLink = $qr_api;
			// $content = file_get_contents($qrCodeApiLink);
			// $qrCodeApiLink = $qr_api;

			// $arrContextOptions = [
			// 	"ssl" => [
			// 		"verify_peer" => false,
			// 		"verify_peer_name" => false,
			// 	],
			// ];
			// $content = file_get_contents($qrCodeApiLink, false, stream_context_create($arrContextOptions));

			// $qrCodeApiLink = $qr_api;

			// $ch = curl_init($qrCodeApiLink);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// $content = curl_exec($ch);
			// curl_close($ch);

			// $qrCodeImage = Image::make($content);

			// Overlay the QR code onto the main image as a watermark
			$real_image
				// ->insert($qrCodeImage, 'bottom-right', $qr_x_position, $qr_y_position)
				->save(public_path($filename));
		}

	}