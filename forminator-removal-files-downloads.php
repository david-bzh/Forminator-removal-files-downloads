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
