<?php
/**
 * Various functions, filters, and actions used by the plugin.
 *
 * @package    CustomContentPortfolioExtras
 * @subpackage Includes
 * @since      0.1.0
 * @author     David Cavins
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
// TEMPLATE TAGS
/**
 * Prints HTML with meta information for the project client.
 */
if ( !( function_exists('cares_project_client') ) ) {
	function cares_project_client( $post_id = null ) {
		$post_id = ( $post_id ) ? $post_id : get_the_ID();
		$client = get_post_meta(  $post_id, 'portfolio_item_client', true );
		return $client;
		//return apply_filters( 'the_title', $client );
	}
}

if ( !( function_exists('cares_project_url') ) ) {
	function cares_project_url( $post_id = null ) {
		$post_id = ( $post_id ) ? $post_id : get_the_ID();
		$url = get_post_meta(  $post_id, 'portfolio_item_url', true );

		return apply_filters( 'the_title', $url );
	}
}