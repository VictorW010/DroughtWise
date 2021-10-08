<?php
/**
 * General action, hooks loader
*/
class WTN_Loader
{
	protected $wtn_actions;
	protected $wtn_filters;

	public function __construct() {
		$this->wtn_actions = array();
		$this->wtn_filters = array();
	}

	public function add_action( $hook, $component, $callback ) {
		$this->wtn_actions = $this->add( $this->wtn_actions, $hook, $component, $callback );
	}

	public function add_filter( $hook, $component, $callback ) {
		$this->wtn_filters = $this->add( $this->wtn_filters, $hook, $component, $callback );
	}

	private function add( $hooks, $hook, $component, $callback ) {
		$hooks[] = array( 'hook' => $hook, 'component' => $component, 'callback' => $callback );
		return $hooks;
	}

	public function wtn_run() {
		 foreach ( $this->wtn_filters as $hook ) {
			 add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		 }

		 foreach ( $this->wtn_actions as $hook ) {
			 add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
		 }
	}
	
}
?>