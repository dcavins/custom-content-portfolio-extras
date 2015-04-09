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

	// Waiting on @link http://core.trac.wordpress.org/ticket/9296
	//add_action( 'admin_init', 'ccp_admin_setup' );

	/* Custom columns on the edit portfolio items screen. */
	add_filter( 'manage_edit-portfolio_item_columns', 'ccpe_edit_portfolio_item_columns', 9 );
	add_action( 'manage_portfolio_item_posts_custom_column', 'ccpe_manage_portfolio_item_columns', 10, 2 );

	/* Add meta boxes and save metadata. */
	// Uses hook provided by Portfolio plugin to add to meta box
	add_action( 'ccp_item_info_meta_box', 'ccpe_render_meta_box_extras', 4, 2 );
	add_action( 'save_post', 'ccpe_portfolio_item_info_meta_box_save', 12, 2 );

	/* Add 32px screen icon. */
	// add_action( 'admin_head', 'ccp_admin_head_style' );

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
		'featured' => __( 'Featured', 'custom-content-portfolio-extras' ),
		'sticky' => __( 'Sticky', 'custom-content-portfolio-extras' )
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

		case 'featured' :
			echo get_post_meta( $post_id, 'portfolio_item_feature', true );
			break;
			
		case 'sticky' :
			echo get_post_meta( $post_id, 'portfolio_item_sticky', true );
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
function ccpe_render_meta_box_extras( $post, $metabox ) {
	?>
		<label for="ccp-portfolio-client"><?php _e( 'Client', 'custom-content-portfolio-extras' ); ?></label>
		<br />
		<input type="text" name="ccp-portfolio-client" id="ccp-portfolio-client" value="<?php echo get_post_meta( $post->ID, 'portfolio_item_client', true ); ?>" size="30" tabindex="30" style="width: 99%;" />

		<div style="margin-top:1em;">
			<input type="checkbox" name="ccp-portfolio-featured" id="ccp-portfolio-featured" value="1" <?php checked( get_post_meta( $post->ID, 'portfolio_item_feature', true ), 'yes' ); ?> />
			<label for="ccp-portfolio-featured" ><?php _e( 'Featured - top of home page', 'custom-content-portfolio-extras' ); ?></label>
		</div>
		
		<div style="margin-top:1em;">
			<input type="checkbox" name="ccp-portfolio-sticky" id="ccp-portfolio-sticky" value="1" <?php checked( get_post_meta( $post->ID, 'portfolio_item_sticky', true ), 'yes' ); ?> />
			<label for="ccp-portfolio-sticky" ><?php _e( 'Sticky - stays at top of Recent Posts on Homepage', 'custom-content-portfolio-extras' ); ?></label>
		</div>

	<?php
}

/**
 * Saves the metadata for the portfolio item info meta box.
 *
 * @since  0.1.0
 * @access public
 * @param  int     $post_id
 * @param  object  $post
 * @return void
 */
function ccpe_portfolio_item_info_meta_box_save( $post_id, $post ) {

	if ( !isset( $_POST['ccp-portfolio-item-info-nonce'] ) || !wp_verify_nonce( $_POST['ccp-portfolio-item-info-nonce'], basename( __FILE__ ) ) )
		return;

	$meta = array(
		'portfolio_item_client' => $_POST['ccp-portfolio-client'],
		'portfolio_item_feature' => isset( $_POST['ccp-portfolio-featured'] ) ? 'yes' : 'no', // We can't use true/false for this value because we want to be able to sort by it, and WP won't include value=false in an orderby
		'portfolio_item_sticky' => isset( $_POST['ccp-portfolio-sticky'] ) ? 'yes' : 'no', // We can't use true/false for this value because we want to be able to sort by it, and WP won't include value=false in an orderby
	);

	foreach ( $meta as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If there is no new meta value but an old value exists, delete it. */
		if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

		/* If a new meta value was added and there was no previous value, add it. */
		elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );
	}

}