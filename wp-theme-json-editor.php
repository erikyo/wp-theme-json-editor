<?php
/*
Plugin Name: Theme json editor
Description: A simple WordPress plugin to edito the theme json file
Version: 1.0
Author: Erik Yo
*/

/**
 * options_changer_menu function
 */
function theme_json_editor() {
	$submenu = add_submenu_page( 'tools.php', 'Theme Json Edotor', 'Theme Json Editor', 'manage_options', 'theme-json-editor', 'theme_json_editor_page' );

	add_action( 'admin_print_styles-' . $submenu, 'theme_json_editor_style', 99 );
	add_action( 'admin_print_scripts-' . $submenu, 'theme_json_editor_script', 99 );
}

add_action( 'admin_menu', 'theme_json_editor' );

// Enqueue admin style
/**
 * Enqueues the admin style for the theme json editor.
 *
 * @return void
 */
function theme_json_editor_style() {
	// Enqueue the admin style
	wp_enqueue_style( 'options-changer-admin-bs', 'https://unpkg.com/spectre.css/dist/spectre.min.css' );
	wp_enqueue_style( 'options-changer-admin', plugin_dir_url( __FILE__ ) . 'build/style-theme-json-editor.css' );
}

function theme_json_editor_script() {
	$asset = include __DIR__ . '/build/theme-json-editor.asset.php';
	wp_enqueue_script( 'jsoneditor', plugin_dir_url( __FILE__ ) . 'node_modules/@json-editor/json-editor/dist/jsoneditor.js' );
	wp_enqueue_script( 'vanilla-picker', 'https://cdn.jsdelivr.net/npm/vanilla-picker@2.10.1/dist/vanilla-picker.min.js' );
	wp_enqueue_script( 'options-changer-admin', plugin_dir_url( __FILE__ ) . 'build/theme-json-editor.js', array_merge( $asset['dependencies'], array( 'jsoneditor' ) ), $asset['version'], array(
		'in_footer' => false
	) );
}

function theme_json_editor_page() {
	// Check if the user has the required capability
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// Check if the form has been submitted and the nonce is valid
	if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( $_POST['nonce'], 'options-changer-nonce' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	// Fetch the content of the post
	$theme_json_post   = wp_get_global_styles();
	$theme_jon_content = json_decode( file_get_contents( VSGE_THEME_DIR . '/theme.json' ), ARRAY_A );
	$theme_json        = array_merge( $theme_jon_content, $theme_json_post );

	// Save the updated content when the form is submitted
	if ( isset( $_POST['save_theme_json'] ) ) {

		$updated_content = sanitize_text_field( $_POST['theme_json_content'] );

		// Update the post content
		wp_update_post( array(
			'ID'           => $theme_json_post->ID,
			'post_content' => $updated_content,
		) );
	}
	?>
	<div class="wrap theme-json-editor">
		<h2>Theme JSON</h2>
		<form method="post" action="" class="p2">
			<label for="json-depth">JSON Depth:</label>
			<input type="number" value="3" id="json-depth"/>

			<label for="json-depth">JSON Depth:</label>
			<input type="checkbox" value="0" id="required-only"/>

			<textarea name="json-dataset" id="json-dataset" class="hidden"><?php echo wp_json_encode( $theme_json, JSON_PRETTY_PRINT ) ?></textarea>

			<div id="root" data-schema="https://schemas.wp.org/trunk/theme.json" data-theme="spectre" data-icon="materialize"></div>
			<input type="submit" name="save_theme_json" class="button-primary" value="Save Theme JSON">
		</form>
	</div>
	<?php
}
