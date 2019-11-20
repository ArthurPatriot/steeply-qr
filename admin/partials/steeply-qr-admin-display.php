<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://allsteeply.com
 * @since      1.0.0
 *
 * @package    Steeply_Qr
 * @subpackage Steeply_Qr/admin/partials
 */

$post_types     = Steeply_Qr_Admin::get_qr_post_types();
$sqr_post_types = get_option( 'sqr_post_types' ) ?? null;

?>
<div class="wrap">

    <pre><?php print_r(''); ?></pre>

    <h1 class="sqr-title">STEEPLY QR v.<?= STEEPLY_QR_VERSION ?></h1>

    <div class="sqr-container">

        <div class="sqr-grid-box">

            <h3>You have <b><?= count($post_types) ?></b> post types</h3>

	        <?php foreach ( $post_types as $post_type ) : ?>
                <p class="sqr-alert sqr-alert--outline sqr-mb-15 <?php if ( $sqr_post_types and in_array( $post_type, $sqr_post_types ) ) { echo 'sqr-alert--active'; } ?>">
			        <?= ucfirst( $post_type ) ?>
                </p>
	        <?php endforeach; ?>

        </div>

        <div class="sqr-grid-box">

            <h3>Generate QR Codes <br>For All Post Types</h3>

            <button type="button" id="sqr_generate_all" class="sqr-btn sqr-mb-20">Generate QR Codes</button>

            <p class="sqr-alert sqr-alert--danger">
                Be careful! This takes a longer time.
            </p>
        </div>

        <div class="sqr-grid-box">

            <h3>Generate QR Codes <br>For Manual Post Type</h3>

            <select class="sqr-select sqr-mb-20" id="sqr_manual_post_type">
                <option disabled selected>Change Post Type</option>
		        <?php foreach ( $post_types as $post_type ) : ?>
                    <option value="<?= $post_type ?>"><?= ucfirst( $post_type ) ?></option>>
		        <?php endforeach; ?>
            </select>

            <button type="button" id="sqr_generate_manual" class="sqr-btn sqr-mb-20">Generate QR Codes</button>

        </div>

        <div class="sqr-grid-box sqr-options">

            <h3>General Options</h3>

            <form action="options.php" method="POST">
		        <?php
		        settings_fields( 'sqr_settings_section' );
		        do_settings_sections( 'steeply-qr' );
		        ?>
                <button type="submit" class="sqr-btn sqr-mb-20">Save Options</button>
            </form>

        </div>

        <div class="sqr-grid-box">
            <h3>Display QR Code</h3>

            <input readonly type="text" class="sqr-select sqr-shortcode sqr-mb-20" value="[sqr]">

            <p class="sqr-alert sqr-alert--info sqr-mb-20">
                Add this shortcode to anywhere on the site to show the QR code.
            </p>
        </div>


        <div class="sqr-grid-box sqr-grid-box--green sqr-sidebar">
            <p class="sqr-sidebar__contact sqr-mb-15">
                <span class="dashicons dashicons-editor-help"></span> Need Help?
            </p>
            <p class="sqr-sidebar__contact sqr-mb-15">
                <span class="dashicons dashicons-sos"></span> Found a Bug?
            </p>
            <p class="sqr-sidebar__contact sqr-mb-15">
                <span class="dashicons dashicons-heart" style="color: #fd77a4"></span> Want to donate?
            </p>
            <p class="sqr-sidebar__contact sqr-mb-15" style="background-color: unset; border: 1px solid #eee;">
                <span class="dashicons dashicons-email-alt"></span>
                <a class="sqr-link" href="mailto:info@allsteeply.com" style="margin-left: 12px;">info@allsteeply.com</a>
            </p>
            <p class="sqr-sidebar__contact" style="background-color: unset; border: 1px solid #eee;">
                <span class="dashicons dashicons-share"></span>
                <a class="sqr-link" href="https://t.me/ArthurPatriot" style="margin-left: 12px;">Telegram</a>
            </p>


        </div>

    </div>

</div>
