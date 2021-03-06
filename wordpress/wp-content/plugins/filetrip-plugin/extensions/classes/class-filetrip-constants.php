<?php

class Filetrip_Constants 
{
	
	// General
	const PLUGIN_NAME = 'Filetrip';
	const NONCE =  'itech_arfaly_plugin'; 
	const FIX_CONTEXT = "itf_filetrip";
	const ERROR_TRANSIENT = 'filetrip_error_transient'; 
	const VERSION = '2.0.0'; 
	const TEXT_DOMAIN = 'filetrip-uploader'; 
	const TMP_FOLDER_PATH = ITECHFILETRIPPLGUINURI.'uploads'; 
	const POST_STATUS = 'filetrip'; 
	const POST_TYPE = 'filetrip'; 
    const METABOX_PREFIX = '_filetrip_'; 
	const MEDIA_COLUMN_SLUG = 'filetrip';

	const ITF_CORE_EXTENSION_VER = '1.0.3';
	
	const ITF_WEBSITE_LINK = 'https://www.itechflare.com';

	/*
	* Filetrip Menu slugs
	*/
	const FILETRIP_MAIN_MENU = 'edit.php?post_type=filetrip';
	const MAIN_MENU_PARENT_SLUG = 'edit.php?post_type=filetrip';
	const FILETRIP_DISTRIBUTOR_PAGE = 'edit.php?page=filetrip_files_distributor';
	const MEDIA_LIBRARY_PAGE = 'upload.php';

	/*
	* Filetrip Distributor source types
	*/
	const TRANSFER_REQUEST_SOURCE = array(
		'media' => 'media-library',
		'backup' => 'filetrip-backup',
		'forward' => 'upload-forwarder'
	);

	const MAIN_MENU_SLUG = 'filetrip';
	const REVIEW_APPROVE_MENU_PAGE = 'filetrip_manage_list';

	const RECORD_TABLE_NAME = 'itf_filetrip_record_tbl';
	const METADATA_TABLE_NAME = 'itf_filetrip_metadata_tbl';

	const ERROR_MESSAGE_MAX_LENGHT = 155;

	const ACTIVATE_BY_DEFAULT = array(
		'SupportCenter'
	);
	
	// To enable demo
	const DEMO_MODE = false;

} // end class.