<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminGCStatisticsController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "g_c_statistics";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];

			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
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

		public function getIndex() {

			$gc_list_campaign = DB::table('g_c_lists')->get();
			$gc_list_store = DB::table('g_c_lists_devps');
			$store_concept = DB::table('store_concepts');

			// Highest Store Sales Claim
			$shs_data = $gc_list_store
				->leftJoin('egc_value_types', 'egc_value_types.id', 'g_c_lists_devps.egc_value_id')
				->select('store_concepts_id', 'egc_value_types.value')
				->where('store_status', '!=', 7)
				->get()
				->toArray();
		
			$store_occurrences = array_count_values(array_column($shs_data, 'store_concepts_id'));

			// Find the store_concepts_id with the highest occurrence
			arsort($store_occurrences); // Sort the array in descending order by value while maintaining index association
			$store_concept_id = key($store_occurrences);
			// Filter the array to get elements with the most common store_concepts_id
			$elements_with_most_common_id = array_filter($shs_data, function ($item) use ($store_concept_id) {
				return $item->store_concepts_id == $store_concept_id;
			});

			$store_highest_sales_value = array_map(function($item) {
				return $item->value;
			}, $elements_with_most_common_id);
			// End of Highest Store Sales Claim

			// Sold Per Concept and redeemed per concept
			$spc = DB::table('g_c_lists_devps')
			->leftJoin('egc_value_types', 'egc_value_types.id', 'g_c_lists_devps.egc_value_id')
			->leftJoin('store_concepts', 'store_concepts.id', 'g_c_lists_devps.store_concepts_id')
			->select('store_concepts.concept', DB::raw('SUM(egc_value_types.value) AS value'), DB::raw('SUM(COALESCE(g_c_lists_devps.redeem, 0)) as redeemed'))
			->groupBy('concept')
			->get();

			// Cancelled EGC
			$cancelled_egc = DB::table('g_c_lists_devps')->where('store_status', '7')->count();
			// Claimed EGC
			$claimed_egc = DB::table('g_c_lists_devps')
				->where('redeem', '!=', null)
				->where('store_status', '!=', '7')
				->count();
			// Unclaimed EGC
			$unclaimed_egc = DB::table('g_c_lists_devps')
				->where('redeem', null)
				->where('store_status', '!=', '7')
				->count();
			// Total
			$total = DB::table('g_c_lists_devps')->count();
			

			// Aging Chart
			$ac = DB::table('g_c_lists_devps')
			->leftJoin('egc_value_types', 'egc_value_types.id', 'g_c_lists_devps.egc_value_id')
			->leftJoin('store_concepts', 'store_concepts.id', 'g_c_lists_devps.store_concepts_id')
			->whereNotNull('g_c_lists_devps.cashier_date_transact')
			->select('store_concepts.concept', 
				DB::raw('AVG(DATEDIFF(g_c_lists_devps.cashier_date_transact, g_c_lists_devps.created_at)) AS average_duration'))
			->groupBy('store_concepts.concept')
			->get();
		
			$data = [];
			$data['page_title'] = 'GC Statistics';
			$data['highest_store_sales'] = [
				'store_name' => $store_concept->where('id',$store_concept_id)->first()->name,
				'value' => array_sum($store_highest_sales_value)
			];
			$data['sold_per_concepts'] = $spc;
			$data['cancelled'] = $cancelled_egc;
			$data['claimed'] = $claimed_egc;
			$data['unclaimed'] = $unclaimed_egc;
			$data['total'] = $total;
			$data['total_sold_egc'] = $spc->sum('value');
			$data['aging_chart'] = $ac;

			return $this->view('statistics.gc_statistics',$data);
		}
	}