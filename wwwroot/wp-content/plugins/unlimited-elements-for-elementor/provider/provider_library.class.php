<?php

class UniteCreatorLibrary extends UniteCreatorLibraryWork{
	
	/**
	 * get platform include by handle
	 */
	protected function getUrlPlatformInclude($handle){
		
		$urlInclude = null;
		
		switch($handle){
			case "jquery":
				$urlInclude = UniteProviderFunctionsUC::getUrlJQueryInclude();
			break;
			case "jquery-migrate":
				$urlInclude = UniteProviderFunctionsUC::getUrlJQueryMigrateInclude();
			break;
		}
		
		return($urlInclude);
	}
	
	
	/**
	 * function for override, process provide library
	 * return true if library found and processed, and false if not
	 */
	public function processProviderLibrary($name){
				
		switch($name){
			case "jquery":
				UniteProviderFunctionsUC::addjQueryInclude();
				
			break;
			default:
				return(false);
			break;
		}
		
		return(true);
	}
	
	
}