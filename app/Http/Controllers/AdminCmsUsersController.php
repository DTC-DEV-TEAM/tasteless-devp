<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDbooster;
use crocodicstudio\crudbooster\controllers\CBController;

class AdminCmsUsersController extends CBController {

	public function __construct() {

		DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
	}

	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->limit				= "20";
		$this->orderby				= "id_cms_privileges,asc";
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';	
		$this->button_import 	   = FALSE;	
		$this->button_export 	   = FALSE;	
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Store","name"=>"id_store_concept", "join"=>'store_concepts,name' );
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);		
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		$this->form[] = array("label"=>"Name","name"=>"name",'required'=>true,'validation'=>'required|min:3','width'=>'col-sm-6');
		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'width'=>'col-sm-6');		
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'required'=>true,'validation'=>'required|image|max:1000','resize_width'=>90,'resize_height'=>90,'width'=>'col-sm-6');											
		if(CRUDBooster::isSuperAdmin()){
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true,'width'=>'col-sm-6');			
		}else{
			$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'required'=>true,'width'=>'col-sm-6','datatable_where'=>'name="Store QR"');			
		}
		$this->form[] = array('label'=>'Store Concept','name'=>'id_store_concept','type'=>'select','validation'=>'required|min:1|max:255',"datatable"=>"store_concepts,name",'width'=>'col-sm-6');			
		// $this->form[] = array("label"=>"Company Name","name"=>"company_id","type"=>"select","datatable"=>"company_ids,company_name",'required'=>true,'width'=>'col-sm-6');						
		$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change",'width'=>'col-sm-6');
		$this->form[] = array("label"=>"Password Confirmation","name"=>"password_confirmation","type"=>"password","help"=>"Please leave empty if not change",'width'=>'col-sm-6');
		# END FORM DO NOT REMOVE THIS LINE
		
		$this->script_js = "

			$(document).ready(function(){

				const check_privilege_id = $('#id_cms_privileges').val();

				$('#form-group-company_id').hide();
				$('#form-group-id_store_concept').hide();
				$('#company_id').attr('required', false);
				$('#id_store_concept').attr('required', false);
				
				if(check_privilege_id == 3){
					$('#form-group-company_id').show();
					$('#form-group-id_store_concept').show();
					$('#company_id').attr('required', true);
					$('#id_store_concept').attr('required', true);
				}

				$('#id_cms_privileges').on('change', function(){

					const company_id = $(this).val();
					if(company_id == 3){
						$('#form-group-company_id').show();
						$('#form-group-id_store_concept').show();
						$('#company_id').attr('required', true);
						$('#id_store_concept').attr('required', true);
					}else{
						$('#form-group-company_id').hide();
						$('#form-group-id_store_concept').hide();
						$('#company_id').attr('required', false);
						$('#id_store_concept').attr('required', false);
					}
				});
			});
		";

		
	}

	public function getProfile() {			

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;			
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;	
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = cbLang("label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());

        return $this->view('crudbooster::default.form',$data);
	}
	public function hook_before_edit(&$postdata,$id) { 
		unset($postdata['password_confirmation']);
	}
	public function hook_before_add(&$postdata) {      
	    unset($postdata['password_confirmation']);
	}

	public function hook_query_index(&$query) {
		
		$query->orderBy('id', 'asc');

		if(CRUDBooster::myPrivilegeName() == 'Admin'){
			$query
				->leftJoin('cms_privileges as priv', 'cms_users.id_cms_privileges', '=', 'priv.id')
				->where('priv.name', 'Store QR');
		}
	}
}
