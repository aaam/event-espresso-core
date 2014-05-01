<?php
/**
 * Contains class for EE Admin Menu objects
 *
 * @since 		4.4.0
 * @package 		Event Espresso
 * @subpackage 	admin
 */


/**
 * Abstract class for defining EE Admin Page Menu Map objects
 *
 * @since 		4.4.0
 * @package 		Event Espresso
 * @subpackage 	admin
 */
abstract class EE_Admin_Page_Menu_Map  {


	/**
	 * The title for the menu page. (the page the menu links to)
	 *
	 * @since  4.4.0
	 * @var string
	 */
	public $title;



	/**
	 * The label for the menu item. (What shows up in the actual menu).
	 *
	 * @since 4.4.0
	 * @var string
	 */
	public $menu_label;




	/**
	 * What menu item is the parent of this menu item.
	 *
	 * @since 4.4.0
	 * @var string
	 */
	public $parent_slug;




	/**
	 * What capability is required to access this page.
	 *
	 * @since 4.4.0
	 * @var string
	 */
	public $capability = 'administrator';




	/**
	 * What slug should be used to reference this menu item.
	 *
	 * @since 4.4.0
	 * @var string
	 */
	public $menu_slug;



	/**
	 * The callback for displaying the page that the menu references.
	 *
	 * @since 4.4.0
	 * @var string
	 */
	public $menu_callback;




	/**
	 * The EE_Admin_Page_Init attached to this map.
	 * @var EE_Admin_Page_Init
	 */
	public $admin_init_page;




	/**
	 * The EE specific group this menu item belongs in (group slug).
	 *
	 * @since 4.4.0
	 * @var string
	 */
	public $menu_group;




	/**
	 * What order this item should be in the menu.
	 *
	 * @since 4.4.0
	 * @var int
	 */
	public $menu_order;




	/**
	 * Whether this item is displayed in the menu or not.
	 * Sometimes an EE Admin Page needs to register itself but is not accessible via the WordPress
	 * admin menu.
	 *
	 * @since 4.4.0
	 * @var boolean
	 */
	public $show_on_menu = TRUE;






	/**
	 * Constructor.
	 *
	 * @since 4.4.0
	 *
	 * @param  array $menu_args  An array of arguments used to setup the menu
	 *                           		properties on construct.
	 * @param  array $required   	An array of keys that should be in the $menu_args, this
	 *                            		is used to validate that the items that should be defined
	 *                            		are present.
	 * @return void
	 */
	public function __construct( $menu_args, $required ) {
		//verify that required keys are present in the incoming array.
		$missing = array_diff( (array) $required, array_keys( (array) $menu_args ) );

		if ( !empty( $missing ) ) {
			throw new EE_Error( sprintf( __('%s is missing some expected keys in the argument array.  The following keys are missing: %s', 'event_espresso'), get_class( $this ), implode(', ', $missing ) ) );
		}

		//made it here okay, so let's set the properties!
		foreach ( $menu_args as $prop => $value ) {
			if ( $prop == 'show_on_menu'  ) {
				$value = (bool) $value;
			} else if ( $prop == 'admin_init_page' && in_array( 'admin_init_page', $required['admin_init_page'] ) && ! $value instanceof EE_Admin_Page_Init ) {
				throw new EE_Error( sprintf( __('The value for the "admin_init_page" argument must be an instance of an EE_Admin_Page_Init object.  Instead %s was given as the value.', 'event_espresso'), $value ) );
			} else {
				$value = (string) $value;
			}

			$this->{$prop} = $value;

		}
	}


	/**
	 * This method should define how the menu page gets added for this particular item
	 * and go ahead and define it.  Note that child classes MUST also return the result of
	 * the function used to register the WordPress admin page (the wp_page_slug string)
	 *
	 * @since  4.4.0
	 * @return string wp_page_slug.
	 */
	abstract protected function _add_menu_page();


	/**
	 * Called by client code to use this menu map for registering a WordPress admin page
	 *
	 * @since  4.4.0
	 */
	public function add_menu_page() {
		$wp_page_slug = $this->_add_menu_page();
		if ( !empty( $wp_page_slug ) && $this->admin_init_page instanceof EE_Admin_Page_Init ) {
			try {
				$this->admin_init_page->set_page_dependencies( $wp_page_slug );
			} catch( EE_Error $e ) {
				$e->get_error();
			}
		}
	}

} //end EE_Admin_Page_Menu_Map




/**
 * This defines the menu map structure for a main menu item.
 *
 * @since  4.4.0
 * @package  Event Espresso
 * @subpackage  admin
 */
class EE_Admin_Page_Main_Menu extends EE_Admin_Page_Menu_Map {


	/**
	 * The page to a icon used for this menu.
	 *
	 * @since  4.4.0
	 * @see http://codex.wordpress.org/Function_Reference/add_menu_page#Parameters
	 *      	for what can be set for this property.
	 * @var string
	 */
	public $icon_url;



	/**
	 * What position in the main menu order for the WP admin menu this menu item
	 * should show.
	 *
	 * @since  4.4.0
	 * @see http://codex.wordpress.org/Function_Reference/add_menu_page#Parameters
	 *      	for what can be set for this property.
	 * @var integer
	 */
	public $position;


	public function __construct( $menu_args ) {
		$required = array( 'menu_label', 'parent_slug', 'menu_slug', 'menu_callback', 'menu_group', 'menu_order', 'admin_init_page');

		parent::__construct( $menu_args, $required );
	}


	/**
	 * Uses the proper WP utility for registering a menu page for the main WP pages.
	 */
	protected function _add_menu_page() {
		return $this->show_on_menu ? $this->add_menu_page( $this->title, $this->menu_label, $this->capability, $this->parent_slug, $this->menu_callback ) : '';
	}
} //end EE_Admin_Page_Main_Menu



/**
 * Defines the menu map structure for sub menu pages.
 *
 * @since 4.4.0
 * @package Event Espresso
 * @subpackage admin
 */
class EE_Admin_Page_Sub_Menu extends EE_Admin_Page_Main_Menu {

	public function __construct( $menu_args ) {
		parent::__construct( $menu_args );
	}


	protected function _add_menu_page() {
		return $this->show_on_menu ? add_submenu_page( $this->parent_slug, $this->title, $this->menu_label, $this->capability, $this->menu_slug, $this->menu_callback ) : '';
	}

} //end class EE_Admin_Page_Menu_Map


/**
 * Defines the EE_Admin page menu group object used in EE_Admin_Page Loader for setting up EE
 * Admin menu groups.
 *
 * A menu group is a special heading that does not link to anything but allows for logical separate of
 * submenu elements.
 *
 * @since  		4.4.0
 * @package 		Event Espresso
 * @subpackage 	admin
 */
class EE_Admin_Page_Menu_Group extends EE_Admin_Page_Menu_Map {



	public function __construct( $menu_args = array() ) {
		$required = array( 'menu_label', 'menu_slug', 'menu_order' );
		parent::__construct( $menu_args, $required );
	}


	protected function _add_menu_page() {
		return $this->show_on_menu ? add_submenu_page( $this->parent_slug, $this->menu_label, $this->_group_link(), $this->capability, $this->menu_slug, $this->_default_header_link(), '__return_false' ): '';
	}


	private function _group_link() {
		return '<span class="ee_menu_group"  onclick="return false;">' . $this->label . '</span>';
	}
} //end EE_Admin_Page_Menu_Group
