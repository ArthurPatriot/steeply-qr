<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://allsteeply.com
 * @since      1.0.0
 *
 * @package    Steeply_Qr
 * @subpackage Steeply_Qr/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Steeply_Qr
 * @subpackage Steeply_Qr/admin
 * @author     Artur Khylskyi <arthur.patriot@gmail.com>
 */
class Steeply_Qr_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook_suffix
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {

		if ( $hook_suffix === 'toplevel_page_steeply-qr' ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steeply-qr-admin.css', array(), $this->version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/steeply-qr-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Plugin Menu Page - SteeplyQR
	 *
	 * @since 1.0.1
	 */
	public function add_menu_page() {

		add_menu_page( 'SteeplyQR', 'SteeplyQR', 'manage_options', 'steeply-qr', array( $this, 'display_menu_page' ), 'dashicons-screenoptions', '55.5' );

	}

	/**
	 * Display Menu Page - SteeplyQR
	 *
	 * @since 1.0.1
	 */
	public function display_menu_page() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/steeply-qr-admin-display.php';

	}

	/**
	 * Register Options for Plugin
	 *
	 * @since 1.0.2
	 */
	public function options_menu_page() {

		register_setting( 'sqr_settings_section', 'sqr_post_types' );

		add_settings_section( 'sqr_settings_section', '', '', 'steeply-qr' );

		add_settings_field( 'sqr_post_types', 'Active For Post Types', 'sqr_post_types_field', 'steeply-qr', 'sqr_settings_section' );

		function sqr_post_types_field() {
			$post_types = Steeply_Qr_Admin::get_qr_post_types();
			$val        = get_option( 'sqr_post_types' ) ?? null; ?>
            <select required name="sqr_post_types[]" class="sqr-select" size="<?= count( $post_types ) ?>" multiple>
				<?php if ( ! $val ) : ?>
                    <option selected disabled>Change Post Types</option>
				<?php endif; ?>
				<?php foreach ( $post_types as $post_type ) : ?>
                    <option <?php if ( $val and in_array( $post_type, $val ) ) {
						echo 'selected';
					} ?> value="<?= $post_type ?>"><?= ucfirst( $post_type ) ?></option>
				<?php endforeach; ?>
            </select>
			<?php
		}


	}

	/**
	 * Register MetaBox with QR for Posts
	 *
	 * @since 1.0.1
	 */
	public function add_meta_box_qr() {

		$generator_post_types = get_option( 'sqr_post_types' ) ?? array( 'post', 'page' );

		add_meta_box( 'sqr-generator', 'Generate QR Code', array( $this, 'display_meta_box_qr' ), $generator_post_types, 'side' );

	}

	/**
	 * Display QR MetaBox
	 *
	 * @param WP_Post $post
	 * @param array $meta
	 *
	 * @since 1.0.1
	 */
	public function display_meta_box_qr( $post, $meta ) {

		$is_generate_qr = get_post_meta( $post->ID, 'sqr-generate', true );

		wp_nonce_field( plugin_basename( __FILE__ ), 'sqr_nonce' );

		?>
        <div style="margin-top: 15px;">
            <p>If the QR is not displayed, make sure that code generation is turned on and refresh the page.</p>
			<?php if ( $is_generate_qr == 'checked' ) : ?>
                <img style="width: auto; height: auto;" src="<?= get_post_meta( $post->ID, 'sqr-image-url', true ) ?>">
			<?php endif; ?>
            <span>
				<input <?= $is_generate_qr ?? '' ?> id="sqr-generate" type="checkbox" name="sqr-generate" value="checked">
			</span>
            <label for="sqr-generate" style="vertical-align: baseline">Generate QR Code for this post</label>
            <button type="button" id="sqr_regenerate_one" data-sqr_post_id="<?= $post->ID ?>" style="display:block;width:90%;margin-top:10px;padding:7px;border-radius:3px;"
                    class="components-button is-primary">Regenerate QR
            </button>
        </div>
		<?php

	}

	/**
	 * Save MetaBox Data
	 *
	 * @param int $post_id
	 *
	 * @since 1.0.1
	 */
	public function save_meta_box_qr( $post_id ) {

		if ( ! wp_verify_nonce( $_POST['sqr_nonce'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( ! isset( $_POST['sqr-generate'] ) ) {
			update_post_meta( $post_id, 'sqr-generate', '' );

			return;
		}

		//TODO: Verification change post url slug

		if ( ! file_exists( WP_CONTENT_DIR . "/uploads/sqr/$post_id.png" ) ) {

			Steeply_Qr_Admin::generate_qr_code( $post_id );

		}

		update_post_meta( $post_id, 'sqr-generate', htmlspecialchars( $_POST['sqr-generate'] ) );

	}

	/**
	 * Generate QR Code using Google Charts API
	 *
	 * @link https://developers.google.com/chart/infographics/docs/qr_codes
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 *
	 * @return WP_Error|int
	 */
	public static function generate_qr_code( $post_id ) {

		$chart_api_uri = 'https://chart.googleapis.com/chart?';

		$generator_args = array(
			'cht'  => 'qr',
			'chs'  => '512x512',
			'chl'  => get_permalink( $post_id ),
			'chld' => 'L|1',
		);

		$qr = wp_remote_get( $chart_api_uri . http_build_query( $generator_args ) );

		if ( is_wp_error( $qr ) or $qr['response']['code'] != 200 ) {
			return new WP_Error( 'sqr-generate-error', 'QR Generation Error' );
		}

		if ( ! Steeply_Qr_Admin::qr_folder_check() ) {
			return new WP_Error( 'sqr-folder-error', 'QR Generation Folder Error. Create folder /wp-content/uploads/sqr and set write access' );
		}

		$qr_file = file_put_contents( WP_CONTENT_DIR . "/uploads/sqr/$post_id.png", $qr['body'] );

		if ( ! $qr_file ) {
			return new WP_Error( 'sqr-generate-error', 'QR File Creation Error' );
		}

		update_post_meta( $post_id, 'sqr-image-url', wp_get_upload_dir()['baseurl'] . "/sqr/$post_id.png" );

		return $post_id;

	}

	/**
	 * Check Exiting QRS Folder and is writable
	 *
	 * @link /wp-content/uploads/qrs/
	 *
	 * @since 1.0.1
	 * @return bool
	 */
	public static function qr_folder_check() {

		$qr_dir = WP_CONTENT_DIR . '/uploads/sqr/';

		if ( ! file_exists( $qr_dir ) ) {
			mkdir( $qr_dir );
		}

		return is_writable( $qr_dir );

	}

	/**
	 * Get Post Types for QR Generation
	 *
	 * @return array $post_types
	 * @since 1.0.1
	 */
	public static function get_qr_post_types() {

		$post_types         = get_post_types( [ 'publicly_queryable' => 1 ] );
		$post_types['page'] = 'page';
		unset( $post_types['attachment'] );

		return $post_types;

	}

	/**
	 * AJAX Generate QR Codes for all Post Types
	 *
	 * @since 1.0.2
	 */
	public function sqr_generate_all() {

		if ( isset( $_GET ) and $_GET['sqr_generate_all'] ) {

			$post_types = Steeply_Qr_Admin::get_qr_post_types();

			$posts = get_posts( array(
				'post_type'   => $post_types,
				'numberposts' => - 1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			) );

			foreach ( $posts as $post_id ) {

				Steeply_Qr_Admin::generate_qr_code( $post_id );

				update_post_meta( $post_id, 'sqr-generate', 'checked' );

			}

			wp_send_json_success();


		}

		wp_die();
	}

	/**
	 * AJAX Generate QR Codes for manual Post Types
	 *
	 * @since 1.0.2
	 */
	public function sqr_generate_manual() {

		if ( isset( $_GET ) and $_GET['sqr_generate_manual'] ) {

			$posts = get_posts( array(
				'post_type'   => htmlspecialchars( $_GET['post_type'] ),
				'numberposts' => - 1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			) );

			foreach ( $posts as $post_id ) {

				Steeply_Qr_Admin::generate_qr_code( $post_id );

				update_post_meta( $post_id, 'sqr-generate', 'checked' );

			}

			wp_send_json_success();

		}

		wp_die();
	}

	/**
	 * AJAX Generate QR Code for some post
	 *
	 * @since 1.0.2
	 */
	public function sqr_regenerate_one() {

		if ( isset( $_GET ) and $_GET['sqr_post_id'] ) {

		    $sqr_post_id = htmlspecialchars($_GET['sqr_post_id']);

		    Steeply_Qr_Admin::generate_qr_code( $sqr_post_id );

		    update_post_meta( $sqr_post_id, 'sqr-generate', 'checked' );

			wp_send_json_success();

		}

		wp_die();
	}

}
