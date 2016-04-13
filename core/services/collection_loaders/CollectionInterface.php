<?php
namespace EventEspresso\core\services\collection_loaders;

if ( ! defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}



/**
 * Interface CollectionInterface
 *
 * @package EventEspresso\core\services\collection_loaders
 */
interface CollectionInterface {

	/**
	 * add
	 * attaches an object to the Collection
	 * and sets any supplied data associated with the current iterator entry
	 * by calling EEI_Collection::set_info()
	 *
	 * @access public
	 * @param object $object
	 * @param mixed  $info
	 * @return bool
	 */
	public function add( $object, $info = null );



	/**
	 * set_info
	 * Sets the info associated with an object in the Collection
	 *
	 * @access public
	 * @param object $object
	 * @param mixed  $info
	 * @return bool
	 */
	public function set_info( $object, $info = null );



	/**
	 * get_by_info
	 * finds and returns an object in the Collection based on the info that was set using set_info() or add()
	 *
	 * @access public
	 * @param mixed
	 * @return null | object
	 */
	public function get_by_info( $info );



	/**
	 * has
	 * returns TRUE or FALSE depending on whether the supplied object is within the Collection
	 *
	 * @access public
	 * @param object $object
	 * @return bool
	 */
	public function has( $object );



	/**
	 * remove
	 * detaches an object from the Collection
	 *
	 * @access public
	 * @param $object
	 * @return void
	 */
	public function remove( $object );

}
// End of file CollectionInterface.php
// Location: /CollectionInterface.php