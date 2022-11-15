<?php
/**
 * Plugin Name:  Forminator removal files downloads
 * Plugin URI:   https://github.com/david-bzh/forminator-removal-files-downloads
 * Description:  This plugin deletes files uploaded by Forminator if the `submission-file` value is set to `delete`.
 * Version:      1.0.0
 * Author:       David
 * Text Domain:  forminator-removal-files-downloads
 *
 * @package david-bzh
 * @since 1.0.0
 */

/**
 * Change the default directory for Forminator uploads
 */
add_filter(
	'forminator_cform_form_is_submittable',
	function( $can_show, $form_id, $form_settings ) {

		if ( 'delete' === $form_settings['submission-file'] ) {

			$GLOBALS['wp_upload_dir'] = wp_upload_dir();

			add_filter(
				'upload_dir',
				function() {

					global $wp_upload_dir;

					return array(
						'path'    => $wp_upload_dir['basedir'] . '/forminator/files/',
						'url'     => $wp_upload_dir['baseurl'] . '/forminator/files/',
						'subdir'  => '/',
						'basedir' => $wp_upload_dir['basedir'] . '/forminator/files',
						'baseurl' => $wp_upload_dir['baseurl'] . '/forminator/files',
						'error'   => false,
					);
				}
			);
		}
		return $can_show;

	},
	10,
	4
);

/**
 * Remove files uploaded from Forminator
 */
add_action(
	'forminator_custom_form_mail_after_send_mail',
	function( Forminator_CForm_Front_Mail $form, Forminator_Form_Model $custom_form ) {

		$settings = $custom_form->get_form_settings();

		if ( 'delete' === $settings['submission-file'] ) {

			$upload_dir = wp_upload_dir();
			$folder     = $upload_dir['basedir'];
			$recursive  = true;
			if ( ! class_exists( 'WP_Filesystem_Direct', false ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
			}
			$filesystem = new WP_Filesystem_Direct( null );
			$filesystem->rmdir( $folder, $recursive );
		}

	},
	10,
	2
);
