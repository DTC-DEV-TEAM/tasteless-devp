<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;

		class ApiRedemptionCodeController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {    
				$this->table       = "g_c_lists";        
				$this->permalink   = "redemption_code";    
				$this->method_type = "get";    
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query
				$query->where('is_fetch', 1)->orderBy('id','asc');
		    }

		    public function hook_after($postdata,&$result) {
		        //This method will be execute after run the main process

		    }

		}