<?php
/**
 * Admin functions for the plugin.
 *
 * @package    CustomContentPortfolioExtras
 * @subpackage Admin
 * @since      0.1.0
 * @author     David Cavins
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Set up the admin functionality. */
add_action( 'admin_menu', 'ccpe_admin_setup' );

/**
 * Adds actions where needed for setting up the plugin's admin functionality.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccpe_admin_setup() {

	/* Custom columns on the edit portfolio items screen. */
	add_filter( 'manage_edit-' . ccp_get_project_post_type() . '_columns', 'ccpe_edit_portfolio_item_columns', 9 );
	add_action( 'manage_' . ccp_get_project_post_type() . '_posts_custom_column', 'ccpe_manage_portfolio_item_columns', 10, 2 );

	// Registers project details box sections, controls, and settings.
	add_action( 'butterbean_register', 'ccpe_extra_project_details_register', 10, 2 );

}

/**
 * Sets up custom columns on the portfolio items admin list screen.
 *
 * @since  0.1.0
 * @access public
 * @param  array  $columns
 * @return array
 */
function ccpe_edit_portfolio_item_columns( $columns ) {

	$new_columns = array(
		'feature' => __( 'Featured', 'custom-content-portfolio-extras' ),
	);

	return array_merge( $new_columns, $columns );
}

/**
 * Displays the content of custom portfolio item columns on the admin list screen.
 *
 * @since  0.1.0
 * @access public
 * @param  string  $column
 * @param  int     $post_id
 * @return void
 */
function ccpe_manage_portfolio_item_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		case 'feature' :
			if ( 'yes' == get_post_meta( $post_id, 'feature', true ) ) {
				echo 'Yes';
			} else {
				echo '&mdash;';
			}
			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

/**
 * Add custom fields to the Meta box provided by the portfolio plugin
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
function ccpe_extra_project_details_register( $butterbean, $post_type ) {

	if ( $post_type !== ccp_get_project_post_type() )
		return;

	$manager = $butterbean->get_manager( 'ccp-project' );

	$manager->register_setting(
        'feature', // Same as control name.
        array(
        	'sanitize_callback' => 'sanitize_key'
        )
	);

	// Single boolean checkbox.
	$manager->register_control(
		'feature',
		array(
			'type'        => 'radio',
			'section'     => 'general',
			'label'       => 'Featured',
			'description' => 'Show on the top of the home page.',
			'choices'     => array(
				'yes' => 'Yes',
				'no'  => 'No',
			),
		)
	);

}