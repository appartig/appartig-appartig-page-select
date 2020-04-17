<?php
	
	/*
		Plugin Name: AppArtig Page Select
		Description: Plugin for defining a set of pages (e.g. quicklinks on the front page)
		Version:     1.0.2
		Author:      AppArtig e.U.
		Author URI:  https://www.appartig.at
		License:     APPARTIG/AGB
		License URI: https://www.appartig.at/agb
		Text Domain: aaps
	*/

	/******************************************************
	** Install
	******************************************************/

	register_activation_hook(__FILE__, function(){ });


	/******************************************************
	** Uninstall
	******************************************************/

	register_deactivation_hook(__FILE__, function(){ });
	

	/******************************************************
	** Styles ans Scripts
	******************************************************/

	add_action('admin_enqueue_scripts', function() {
		wp_enqueue_style('aaps_style_css', plugins_url('/css/style.css', __FILE__ ), null, '1.0.0');
		
		wp_enqueue_script( array("jquery", "jquery-ui-sortable") );
        wp_enqueue_script('aaps_scripts_app', plugin_dir_url(__FILE__) . 'js/app.js', array(), '1.0.0', true);
	});
	

	/******************************************************
	** Register Option/Setting
	******************************************************/

	add_action('admin_init', function () {
		add_option('appartig_page_select_value', '[]');
		register_setting('appartig_page_select', 'appartig_page_select_value', function($input) {
			return $input;
		});
	});


	/******************************************************
	** Menu
	******************************************************/

    add_action('admin_menu', function() {

		add_menu_page(
			__("Page Select", "aaps"),
			__("Page Select", "aaps"),
			'manage_options',
			'appartig_page_select',
			'appartig_page_select__html',
			'dashicons-list-view',
			80
		);
	});


	/******************************************************
	** Admin Page
	******************************************************/

	function appartig_page_select__html(){

		$json = get_option('appartig_page_select_value');
		$pages = get_pages();

		if (!isset($_REQUEST['settings-updated']))$_REQUEST['settings-updated'] = false;	
		if ( false !== $_REQUEST['settings-updated'] ) : 
	?> 
		<div class="updated fade">
			<p><strong>Einstellungen gespeichert!</strong></p>
		</div>
		
	<?php endif; ?>

		<div class="aaps wrap">
	
			<form method="post" action="options.php">

				<?php 
					settings_fields( 'appartig_page_select' );
				?>
			
				<input type="hidden" id="appartig_page_select_value" name="appartig_page_select_value" value='<?php echo $json; ?>'>
				<h1 class="wp-heading-inline">Page Select</h1>	
				<button type="submit" class="page-title-action">Speichern</button>
			</form>

			<hr class="wp-header-end">

			<ol class="aaps-list aaps-list--not-ready">

				<?php
					foreach($pages as $page){

						echo "<li class='aaps-item' data-id='{$page->ID}'>";
						echo "{$page->post_title}";
						echo "<div class='aaps-item__show'><label  for='aaps-item__show--{$page->ID}'>Auf Startseite zeigen</label><input id='aaps-item__show--{$page->ID}' type='checkbox' /></div>";
						echo "</li>";

					}
				?>

			</ol>
		</div>

		<?php

	}
	

	/******************************************************
	** receive selected Posts
	******************************************************/

	function appartigPageSelect (){
		
		$allPages = get_pages();
		$pages = [];
		$selectedObject = json_decode(get_option('appartig_page_select_value'));
		$selcted = [];

		foreach($selectedObject as $orderdPage){
			if($orderdPage->checked) {
				foreach($allPages as $page){
					if ($page->ID == $orderdPage->id) $pages[] = $page;
				}
			}
		}
		
		return $pages;
	}