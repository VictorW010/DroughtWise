<?php

defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteProviderFrontUC{
	
	private $t;
	const ACTION_FOOTER_SCRIPTS = "wp_print_footer_scripts";
	const ACTION_AFTER_SETUP_THEME = "after_setup_theme";
	
	
	/**
	 *
	 * add some wordpress action
	 */
	protected function addAction($action,$eventFunction,$priority=10){
		
		add_action( $action, array($this, $eventFunction), $priority );
	}
		
	/**
	 * add filter
	 */
	protected function addFilter($tag, $func, $priority = 10, $accepted_args = 1){
		
		add_filter($tag, array($this, $func), $priority, $accepted_args);
	}

	
	/**
	 * on script tag output. modify the output by options
	 */
	public function onScriptTagOutput($tag, $handle, $src){
		
		if(isset(GlobalsProviderUC::$arrJSHandlesModules[$handle])){
			
			//modify tag, change to module if needed
			
			$search = "type='text/javascript'";
			$replace = "type='module'";
			
			$tag = str_replace($search, $replace, $tag);
		}
				
		return($tag);
	}
	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		
		$this->t = $this;
		
		do_action("addon_library_before_front_init");
		
		HelperProviderUC::globalInit();
	   
		$this->addAction(self::ACTION_FOOTER_SCRIPTS, "onPrintFooterStyles", 1);
		$this->addAction(self::ACTION_FOOTER_SCRIPTS, "onPrintFooterScripts");
		
		$this->addAction( 'wp_head', 'onPrintHeadStyles' );
		
		//modify output <script> tag, add module to it
		$this->addFilter("script_loader_tag", 'onScriptTagOutput',10,3);
		
	}
	
	
	/**
	 * on print head styles
	 */
	public function onPrintHeadStyles(){
	    
	  HelperProviderUC::outputCustomStyles();	  
	}
	
	
	/**
	 * print footer scripts
	 */
	public function onPrintFooterStyles(){
		
		HelperProviderUC::onPrintFooterScripts(true, "css");
		
	}

	/**
	 * print footer scripts
	 */
	public function onPrintFooterScripts(){
		
		HelperProviderUC::onPrintFooterScripts(true, "js");
		
	}
	
	
		
}

?>