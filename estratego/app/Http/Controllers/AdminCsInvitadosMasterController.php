<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;

	class AdminCsInvitadosMasterController extends \crocodicstudio\crudbooster\controllers\CBController {

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
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = true;
			$this->button_export = true;
			$this->table = "cs_invitados_master";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Nit","name"=>"nit"];
			$this->col[] = ["label"=>"Empresa","name"=>"cs_empresa_id","join"=>"cs_empresa,nombre"];
			$this->col[] = ["label"=>"Titulo","name"=>"titulo"];
			$this->col[] = ["label"=>"Nombre","name"=>"nombre"];
			$this->col[] = ["label"=>"Apellido","name"=>"apellido"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Nit','name'=>'nit','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Nombre','name'=>'nombre','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Apellido','name'=>'apellido','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Titulo','name'=>'titulo','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tipo Empresa','name'=>'cs_tipo_empresa_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_tipo_empresa,tipo'];
			$this->form[] = ['label'=>'Puesto','name'=>'cs_puesto_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_puesto,puesto'];
			$this->form[] = ['label'=>'Cargo Genérico','name'=>'cs_cargo_generico_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_cargo_generico,cargo'];
			$this->form[] = ['label'=>'Código Empresarial','name'=>'codigo_empresarial','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Empresa','name'=>'cs_empresa_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_empresa,nombre'];
			$this->form[] = ['label'=>'Código Contacto','name'=>'codigo_contacto','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Departamento Genérico','name'=>'cs_departamento_generico_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_departamento_generico,departamento'];
			$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Email 2','name'=>'email2','type'=>'email','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Telefono','name'=>'telefono','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Teléfono 2','name'=>'telefono2','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Celular','name'=>'celular','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Direccion','name'=>'direccion','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Direccion2','name'=>'direccion2','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'País','name'=>'cs_pais_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_pais,pais'];
			$this->form[] = ['label'=>'Departamento','name'=>'cs_departamento_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_departamento,departamento'];
			$this->form[] = ['label'=>'Ciudad','name'=>'cs_ciudad_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_ciudad,ciudad'];
			$this->form[] = ['label'=>'Zona','name'=>'cs_zona_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_zona,zona'];
			$this->form[] = ['label'=>'Pagina Web','name'=>'pagina_web','type'=>'text','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Tamaño de Empresa','name'=>'cs_tamano_id','type'=>'select2','width'=>'col-sm-10'];
			$this->form[] = ['label'=>'Sector Empresa','name'=>'cs_sector_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_sector,sector'];
			$this->form[] = ['label'=>'Segmento Cliente','name'=>'cs_segmento_cliente_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_segmento_cliente,upper_gb'];
			$this->form[] = ['label'=>'Password','name'=>'password','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Nit','name'=>'nit','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Nombre','name'=>'nombre','type'=>'text','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Apellido','name'=>'apellido','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Titulo','name'=>'titulo','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Tipo Empresa','name'=>'cs_tipo_empresa_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_tipo_empresa,tipo'];
			//$this->form[] = ['label'=>'Puesto','name'=>'cs_puesto_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_puesto,puesto'];
			//$this->form[] = ['label'=>'Cargo Genérico','name'=>'cs_cargo_generico_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_cargo_generico,cargo'];
			//$this->form[] = ['label'=>'Código Empresarial','name'=>'codigo_empresarial','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Empresa','name'=>'cs_empresa_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_empresa,nombre'];
			//$this->form[] = ['label'=>'Código Contacto','name'=>'codigo_contacto','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Departamento Genérico','name'=>'cs_departamento_generico_id','type'=>'select2','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email','name'=>'email','type'=>'email','validation'=>'required','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Email 2','name'=>'email2','type'=>'email','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Telefono','name'=>'telefono','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Teléfono 2','name'=>'telefono2','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Celular','name'=>'celular','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Direccion','name'=>'direccion','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Direccion2','name'=>'direccion2','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'País','name'=>'cs_pais_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_pais,pais'];
			//$this->form[] = ['label'=>'Departamento','name'=>'cs_departamento_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_departamento,departamento'];
			//$this->form[] = ['label'=>'Ciudad','name'=>'cs_ciudad_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_ciudad,ciudad'];
			//$this->form[] = ['label'=>'Zona','name'=>'cs_zona_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_zona,zona'];
			//$this->form[] = ['label'=>'Pagina Web','name'=>'pagina_web','type'=>'text','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Tamaño de Empresa','name'=>'cs_tamano_id','type'=>'select2','width'=>'col-sm-10'];
			//$this->form[] = ['label'=>'Sector Empresa','name'=>'cs_sector_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_sector,sector'];
			//$this->form[] = ['label'=>'Segmento Cliente','name'=>'cs_segmento_cliente_id','type'=>'select2','width'=>'col-sm-10','datatable'=>'cs_segmento_cliente,upper_gb'];
			//$this->form[] = ['label'=>'Password','name'=>'password','type'=>'text','validation'=>'required','width'=>'col-sm-9'];
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


	}