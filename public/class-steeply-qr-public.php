<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://allsteeply.com
 * @since      1.0.0
 *
 * @package    Steeply_Qr
 * @subpackage Steeply_Qr/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Steeply_Qr
 * @subpackage Steeply_Qr/public
 * @author     Artur Khylskyi <arthur.patriot@gmail.com>
 */
class Steeply_Qr_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steeply-qr-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/steeply-qr-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
     * Display Shortcode [sqr]
     *
     * @since 1.0.2
	 * @return false|string
	 */
	public function sqr_shortcode_display() {

		global $post;

		$sqr_post_types = get_option( 'sqr_post_types' );

		if (!$sqr_post_types or !in_array($post->post_type, $sqr_post_types)) {
			return '';
		}

		$sqr_img_url = get_post_meta($post->ID, 'sqr-image-url', true);

		if (empty($sqr_img_url)) {
			return '';
		}

		ob_start();
		?>
		<img class="sqr-img" src="<?= $sqr_img_url ?>" alt="QR Code for <?= $post->post_title ?>">
		<?php
		return ob_get_clean();

	}

}
