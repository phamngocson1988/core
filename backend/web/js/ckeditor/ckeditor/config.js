/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	var _browserUrl = 'http://admin.phamngocson.com:8080/js/ckeditor';
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = _browserUrl + '/ckfinder/ckfinder.html';

	config.filebrowserImageBrowseUrl = _browserUrl + '/ckfinder/ckfinder.html?type=Images';

	config.filebrowserFlashBrowseUrl = _browserUrl + '/ckfinder/ckfinder.html?type=Flash';

	config.filebrowserUploadUrl = _browserUrl + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';

	config.filebrowserImageUploadUrl = _browserUrl + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

	config.filebrowserFlashUploadUrl = _browserUrl + '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
