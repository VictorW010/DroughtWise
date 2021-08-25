<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use \Elementor\Utils;

defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');


class UniteCreatorElementorControls{

	
	/**
	 * add repeater control
	 */
	public function addGalleryImageVideoRepeater($objControls, $textPrefix, $name){
		 
		$repeater = new Repeater();
		
	        $objControls->start_controls_section(
	                'uc_section_listing_gallery_repeater', array(
	                'label' => $textPrefix.__(" Items", "unlimited-elements-for-elementor"),
	        		'condition'=>array($name."_source"=>"image_video_repeater")
	              )
	        );

	        // ----  item type ---------  
	        
			$repeater->add_control(
				'item_type',
					array(
						'label' => __( 'Item Type', 'plugin-domain' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'image',
						'options' => array(
							'image'  => __( 'Image', 'unlimited-elements-for-elementor' ),
							'youtube' => __( 'Youtube', 'unlimited-elements-for-elementor' ),
							'vimeo' => __( 'Vimeo', 'unlimited-elements-for-elementor' ),
							'wistia' => __( 'Wistia', 'unlimited-elements-for-elementor' ),
							'html5' => __( 'HTML5 Video', 'unlimited-elements-for-elementor' )
						)
					)
			);	    

			//--------- image --------
			
			$repeater->add_control(
				'image',
				array(
					'label' => __( 'Choose Image', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::MEDIA,
					'default' => array(
						'url' => Utils::get_placeholder_image_src(),
					),
					'condition'=>array('item_type'=>'image')
				)
			);			
			
			//--------- youtube url --------
			
			$repeater->add_control(
				'url_youtube',
				array(
					'label' => __( 'Youtube Url or ID', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( '', 'unlimited-elements-for-elementor' ),
					'description'=>'For example: https://www.youtube.com/watch?v=9bZkp7q19f0 or 9bZkp7q19f0',
					'label_block'=>true,
					'condition'=>array('item_type'=>'youtube')
				)
			);

			//--------- vimeo id --------
			
			$repeater->add_control(
				'vimeo_id',
				array(
					'label' => __( 'Vimeo Video ID or Url', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( '', 'unlimited-elements-for-elementor' ),
					'description'=>__('For example: 581014653, or https://vimeo.com/581014653','unlimited-elements-for-elementor'),
					'label_block'=>true,
					'condition'=>array('item_type'=>'vimeo')
				)
			);
			
			//--------- wistia --------
			
			$repeater->add_control(
				'wistia_id',
				array(
					'label' => __( 'Wistia Video ID', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( '', 'unlimited-elements-for-elementor' ),
					'description'=>__('For example: 9oedgxuciv','unlimited-elements-for-elementor'),
					'label_block'=>true,
					'condition'=>array('item_type'=>'wistia')
				)
			);
			
			//--------- html5 video --------
			
			$repeater->add_control(
				'url_html5',
				array(
					'label' => __( 'MP4 Video Url', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( '', 'unlimited-elements-for-elementor' ),
					'description'=>__('Enter url of the mp4 video in current or external site','unlimited-elements-for-elementor'),
					'label_block'=>true,
					'condition'=>array('item_type'=>'html5')
				)
			);
			
			//--------- title --------
			
			$repeater->add_control(
				'title',
				array(
					'label' => __( 'Item Title', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( '', 'unlimited-elements-for-elementor' ),
					'label_block'=>true,
					'separator'=>'before'
				)
			);

			//--------- description --------
			
			$repeater->add_control(
				'description',
				array(
					'label' => __( 'Item Description', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::WYSIWYG,
					'default' => __( '', 'unlimited-elements-for-elementor' ),
					'label_block'=>true
				)
			);
			
			
			$objControls->add_control(
				$name.'_items',
				array(
					'label' => __( 'Gallery Items', 'unlimited-elements-for-elementor' ),
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'default' => array(),
					'title_field' => '{{{ title }}}',
				)
			);		
	        
	        $objControls->end_controls_section();
		
	}

}