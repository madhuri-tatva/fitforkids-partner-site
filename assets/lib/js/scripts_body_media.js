/************************************************************

	Main Scripts

*************************************************************/

!function ($) {

	"use strict";

	// Global Onyx object
	var Onyx = Onyx || {};
	if(page_name_type==12){
		var selector_name = 'form.dropzone12'; // nutrition page user-module-form
		var preview_container = '.previews12';
	}else if(page_name_type==2){
		var selector_name = 'form.dropzone2'; // training videos, nutrition learning video section
		var preview_container = '.previews2';
	}else{
		var selector_name = 'form.dropzone';
		var preview_container = '.previews';
	}
	// alert(selector_name);
	// alert(page_name_type);
	Onyx = {

	    /**
		 * Fire all functions
		 */
		init: function() {
			var self = this,
				obj;
			for (obj in self) {
				if ( self.hasOwnProperty(obj)) {
					var _method =  self[obj];
					if ( _method.selector !== undefined && _method.init !== undefined ) {
						if ( $(_method.selector).length > 0 ) {
							_method.init();
						}
					}
				}
			}
		},


		/**
		 * Files upload
		 */

		userFilesDropzone: {

			selector: selector_name,
			init: function() {
				var base = this,
					container = $(base.selector);

				base.initFileUploader(base, selector_name);
			},
			initFileUploader: function(base, target) {
				var previewNode = document.querySelector("#onyx-dropzone-template"), // Dropzone template holder
					warningsHolder = $("#warnings"); // Warning messages' holder

				previewNode.id = "";

				var previewTemplate = previewNode.parentNode.innerHTML;
				previewNode.parentNode.removeChild(previewNode);
				Dropzone.autoDiscover = false;
				if(page_name_type==2){ //,application/pdf
					var max_file_size = 2000;
					var allowed_file_ext = 'image/*,.mp4,.avi,.mkv,.mp3,application/pdf,.doc,.docx,.ppt,.pptx';
				}else{
					var max_file_size = 2000;
					var allowed_file_ext = 'image/*,.mp4,.avi,.mkv,.mp3,application/pdf,.doc,.docx,.ppt,.pptx';
				}
				var onyxDropzone = new Dropzone(target, {
					url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php", // Check that our form has an action attr and if not, set one here
	                maxFiles:1,
					maxFilesize: max_file_size,
					acceptedFiles: allowed_file_ext,
					previewTemplate: previewTemplate,
					previewsContainer: preview_container,
					clickable: true,
					uploadMultiple: false,
					addRemoveLinks: false,
					createImageThumbnails: true,
					timeout: 0,

					/**
					 * The text used before any files are dropped.
					 */
					dictDefaultMessage: "Drop & Choose file here to upload.", // Default: Drop files here to upload

					/**
					 * The text that replaces the default message text it the browser is not supported.
					 */
					dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.", // Default: Your browser does not support drag'n'drop file uploads.

					/**
					 * If the filesize is too big.
					 * `{{filesize}}` and `{{maxFilesize}}` will be replaced with the respective configuration values.
					 */
					dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", // Default: File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.

					/**
					 * If the file doesn't match the file type.
					 */
					dictInvalidFileType: "You can't upload files of this type.", // Default: You can't upload files of this type.

					/**
					 * If the server response was invalid.
					 * `{{statusCode}}` will be replaced with the servers status code.
					 */
					dictResponseError: "Server responded with {{statusCode}} code.", // Default: Server responded with {{statusCode}} code.

					/**
					 * If `addRemoveLinks` is true, the text to be used for the cancel upload link.
					 */
					dictCancelUpload: "Cancel upload.", // Default: Cancel upload

					/**
					 * The text that is displayed if an upload was manually canceled
					 */
					dictUploadCanceled: "Upload canceled.", // Default: Upload canceled.

					/**
					 * If `addRemoveLinks` is true, the text to be used for confirmation when cancelling upload.
					 */
					dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?", // Default: Are you sure you want to cancel this upload?

					/**
					 * If `addRemoveLinks` is true, the text to be used to remove a file.
					 */
					dictRemoveFile: "Remove file", // Default: Remove file

					/**
					 * If this is not null, then the user will be prompted before removing a file.
					 */
					dictRemoveFileConfirmation: null, // Default: null

					/**
					 * Displayed if `maxFiles` is st and exceeded.
					 * The string `{{maxFiles}}` will be replaced by the configuration value.
					 */
					dictMaxFilesExceeded: "You can not upload any more files.", // Default: You can not upload any more files.

					/**
					 * Allows you to translate the different units. Starting with `tb` for terabytes and going down to
					 * `b` for bytes.
					 */
					dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},

				});

				Dropzone.autoDiscover = false;

				onyxDropzone.on("addedfile", function(file) {
					$('.preview-container').css('visibility', 'visible');
					file.previewElement.classList.add('type-' + base.fileType(file.name)); // Add type class for this element's preview
				});

				onyxDropzone.on("totaluploadprogress", function (progress) {

					var progr = document.querySelector(".progress .determinate");

					if (progr === undefined || progr === null) return;

					progr.style.width = progress + "%";
				});

				onyxDropzone.on('dragenter', function () {
					$(target).addClass("hover");
				});

				onyxDropzone.on('dragleave', function () {
					$(target).removeClass("hover");
				});

				onyxDropzone.on('drop', function () {
					$(target).removeClass("hover");
				});

				onyxDropzone.on('addedfile', function () {

					// Remove no files notice
					$(".no-files-uploaded").slideUp("easeInExpo");

				});

				onyxDropzone.on('removedfile', function (file) {

					$.ajax({
						type: "POST",
						url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php",
						data: {
							target_file: file.upload.filename,
							// target_file: file.upload_ticket,
							delete_file: 1
						}
					});

					// Show no files notice
					if ( base.dropzoneCount() == 0 ) {
						$(".no-files-uploaded").slideDown("easeInExpo");
						$(".uploaded-files-count").html(base.dropzoneCount());
					}

				});

				onyxDropzone.on("success", function(file, response) {
					let parsedResponse = JSON.parse(response);
						let idArr = [];
						// file.upload_ticket = parsedResponse.file_link;
						for (var i = 0; i < parsedResponse.data.length; i++) {
							idArr.push(parsedResponse.data[i]);
						}
						// $(".preview-container > #extra-fields").css('display','block');

					// Make it wait a little bit to take the new element
					setTimeout(function(){
						$(".uploaded-files-count").html(base.dropzoneCount());
						console.log('Files count: ' + base.dropzoneCount());
					}, 350);


					// Something to happen when file is uploaded, like showing a message
					if ( typeof parsedResponse.info !== 'undefined' ) {
						console.log(parsedResponse.info);
						// $(".preview-container > #extra-fields").css('display','block');
						warningsHolder.children('span').html(parsedResponse.info);
						warningsHolder.slideDown("easeInExpo");
					}
				});
			},
			dropzoneCount: function() {
				var filesCount = $(preview_container+" > .dz-success.dz-complete").length;
				return filesCount;
			},
			fileType: function(fileName) {
				var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
				return fileType[0];
			}
		}
	}

	$(document).ready(function() {
		Onyx.init();
	});

}(jQuery);


!function ($) {

	"use strict";

	// Global Onyx object
	var Onyx_Thumbnail = Onyx_Thumbnail || {};
	Onyx_Thumbnail = {

	    /**
		 * Fire all functions
		 */
		init: function() {
			var self = this,
				obj;
			for (obj in self) {
				if ( self.hasOwnProperty(obj)) {
					var _method =  self[obj];
					if ( _method.selector !== undefined && _method.init !== undefined ) {
						if ( $(_method.selector).length > 0 ) {
							_method.init();
						}
					}
				}
			}
		},


		/**
		 * Files upload
		 */

		userFilesThumbnailDropzone: {

			selector: 'form.dropzone3',
			init: function() {
				var base = this,
					container = $(base.selector);

				base.initFileUploader(base, 'form.dropzone3');
			},
			initFileUploader: function(base, target) {
				var previewNode = document.querySelector("#onyx-dropzone-template"), // Dropzone template holder
					warningsHolder = $("#warnings"); // Warning messages' holder

				previewNode.id = "";

				var previewTemplate = previewNode.parentNode.innerHTML;
				previewNode.parentNode.removeChild(previewNode);
				Dropzone.autoDiscover = false;
					var max_file_size = 2000;
					var allowed_file_ext = 'application/pdf';
				var onyxThumbDropzone = new Dropzone(target, {
					url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php", // Check that our form has an action attr and if not, set one here
	                maxFiles:1,
					maxFilesize: max_file_size,
					acceptedFiles: allowed_file_ext,
					previewTemplate: previewTemplate,
					previewsContainer: ".previews-thumbnail",
					clickable: true,
					uploadMultiple: false,
					addRemoveLinks: false,
					createImageThumbnails: true,
					timeout: 0,

					/**
					 * The text used before any files are dropped.
					 */
					dictDefaultMessage: "Drop & Choose file here to upload.", // Default: Drop files here to upload

					/**
					 * The text that replaces the default message text it the browser is not supported.
					 */
					dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.", // Default: Your browser does not support drag'n'drop file uploads.

					/**
					 * If the filesize is too big.
					 * `{{filesize}}` and `{{maxFilesize}}` will be replaced with the respective configuration values.
					 */
					dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", // Default: File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.

					/**
					 * If the file doesn't match the file type.
					 */
					dictInvalidFileType: "You can't upload files of this type.", // Default: You can't upload files of this type.

					/**
					 * If the server response was invalid.
					 * `{{statusCode}}` will be replaced with the servers status code.
					 */
					dictResponseError: "Server responded with {{statusCode}} code.", // Default: Server responded with {{statusCode}} code.

					/**
					 * If `addRemoveLinks` is true, the text to be used for the cancel upload link.
					 */
					dictCancelUpload: "Cancel upload.", // Default: Cancel upload

					/**
					 * The text that is displayed if an upload was manually canceled
					 */
					dictUploadCanceled: "Upload canceled.", // Default: Upload canceled.

					/**
					 * If `addRemoveLinks` is true, the text to be used for confirmation when cancelling upload.
					 */
					dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?", // Default: Are you sure you want to cancel this upload?

					/**
					 * If `addRemoveLinks` is true, the text to be used to remove a file.
					 */
					dictRemoveFile: "Remove file", // Default: Remove file

					/**
					 * If this is not null, then the user will be prompted before removing a file.
					 */
					dictRemoveFileConfirmation: null, // Default: null

					/**
					 * Displayed if `maxFiles` is st and exceeded.
					 * The string `{{maxFiles}}` will be replaced by the configuration value.
					 */
					dictMaxFilesExceeded: "You can not upload any more files.", // Default: You can not upload any more files.

					/**
					 * Allows you to translate the different units. Starting with `tb` for terabytes and going down to
					 * `b` for bytes.
					 */
					dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},

				});

				Dropzone.autoDiscover = false;

				onyxThumbDropzone.on("addedfile", function(file) {
					$('.preview-container').css('visibility', 'visible');
					file.previewElement.classList.add('type-' + base.fileType(file.name)); // Add type class for this element's preview
				});

				onyxThumbDropzone.on("totaluploadprogress", function (progress) {

					var progr = document.querySelector(".progress .determinate");

					if (progr === undefined || progr === null) return;

					progr.style.width = progress + "%";
				});

				onyxThumbDropzone.on('dragenter', function () {
					$(target).addClass("hover");
				});

				onyxThumbDropzone.on('dragleave', function () {
					$(target).removeClass("hover");
				});

				onyxThumbDropzone.on('drop', function () {
					$(target).removeClass("hover");
				});

				onyxThumbDropzone.on('addedfile', function () {

					// Remove no files notice
					$(".no-files-uploaded").slideUp("easeInExpo");

				});

				onyxThumbDropzone.on('removedfile', function (file) {

					$.ajax({
						type: "POST",
						url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php",
						data: {
							target_file: file.upload.filename,
							// target_file: file.upload_ticket,
							delete_file: 1
						}
					});

					// Show no files notice
					if ( base.dropzoneCount() == 0 ) {
						$(".no-files-uploaded").slideDown("easeInExpo");
						$(".uploaded-files-count").html(base.dropzoneCount());
					}

				});

				onyxThumbDropzone.on("success", function(file, response) {
					let parsedResponse = JSON.parse(response);
						let idArr = [];
						// file.upload_ticket = parsedResponse.file_link;
						for (var i = 0; i < parsedResponse.data.length; i++) {
							idArr.push(parsedResponse.data[i]);
						}
						$(".preview-container > #extra-fields").css('display','block');

					// Make it wait a little bit to take the new element
					setTimeout(function(){
						$(".uploaded-files-count").html(base.dropzoneCount());
						console.log('Files count: ' + base.dropzoneCount());
					}, 350);


					// Something to happen when file is uploaded, like showing a message
					if ( typeof parsedResponse.info !== 'undefined' ) {
						console.log(parsedResponse.info);
						$(".preview-container > #extra-fields").css('display','block');
						warningsHolder.children('span').html(parsedResponse.info);
						warningsHolder.slideDown("easeInExpo");
					}
				});
			},
			dropzoneCount: function() {
				var filesCount = $(".previews-thumbnail > .dz-success.dz-complete").length;
				return filesCount;
			},
			fileType: function(fileName) {
				var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
				return fileType[0];
			}
		},

		/**
		 * Files upload
		 */

		 userFilesThumbnailDropzone: {

			selector: 'form.dropzone99',
			init: function() {
				var base = this,
					container = $(base.selector);

				base.initFileUploader(base, 'form.dropzone99');
			},
			initFileUploader: function(base, target) {
				var previewNode = document.querySelector("#onyx-dropzone-template"), // Dropzone template holder
					warningsHolder = $("#warnings"); // Warning messages' holder

				previewNode.id = "";

				var previewTemplate = previewNode.parentNode.innerHTML;
				previewNode.parentNode.removeChild(previewNode);
				Dropzone.autoDiscover = false;
					var max_file_size = 2000;
					var allowed_file_ext = '.jpg,.png,.jpeg';
				var onyxThumbDropzone = new Dropzone(target, {
					url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php", // Check that our form has an action attr and if not, set one here
	                maxFiles:1,
					maxFilesize: max_file_size,
					acceptedFiles: allowed_file_ext,
					previewTemplate: previewTemplate,
					previewsContainer: ".previews-thumbnail",
					clickable: true,
					uploadMultiple: false,
					addRemoveLinks: false,
					createImageThumbnails: true,
					timeout: 0,

					/**
					 * The text used before any files are dropped.
					 */
					dictDefaultMessage: "Drop & Choose file here to upload.", // Default: Drop files here to upload

					/**
					 * The text that replaces the default message text it the browser is not supported.
					 */
					dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.", // Default: Your browser does not support drag'n'drop file uploads.

					/**
					 * If the filesize is too big.
					 * `{{filesize}}` and `{{maxFilesize}}` will be replaced with the respective configuration values.
					 */
					dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", // Default: File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.

					/**
					 * If the file doesn't match the file type.
					 */
					dictInvalidFileType: "You can't upload files of this type.", // Default: You can't upload files of this type.

					/**
					 * If the server response was invalid.
					 * `{{statusCode}}` will be replaced with the servers status code.
					 */
					dictResponseError: "Server responded with {{statusCode}} code.", // Default: Server responded with {{statusCode}} code.

					/**
					 * If `addRemoveLinks` is true, the text to be used for the cancel upload link.
					 */
					dictCancelUpload: "Cancel upload.", // Default: Cancel upload

					/**
					 * The text that is displayed if an upload was manually canceled
					 */
					dictUploadCanceled: "Upload canceled.", // Default: Upload canceled.

					/**
					 * If `addRemoveLinks` is true, the text to be used for confirmation when cancelling upload.
					 */
					dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?", // Default: Are you sure you want to cancel this upload?

					/**
					 * If `addRemoveLinks` is true, the text to be used to remove a file.
					 */
					dictRemoveFile: "Remove file", // Default: Remove file

					/**
					 * If this is not null, then the user will be prompted before removing a file.
					 */
					dictRemoveFileConfirmation: null, // Default: null

					/**
					 * Displayed if `maxFiles` is st and exceeded.
					 * The string `{{maxFiles}}` will be replaced by the configuration value.
					 */
					dictMaxFilesExceeded: "You can not upload any more files.", // Default: You can not upload any more files.

					/**
					 * Allows you to translate the different units. Starting with `tb` for terabytes and going down to
					 * `b` for bytes.
					 */
					dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},

				});

				Dropzone.autoDiscover = false;

				onyxThumbDropzone.on("addedfile", function(file) {
					$('.preview-container').css('visibility', 'visible');
					file.previewElement.classList.add('type-' + base.fileType(file.name)); // Add type class for this element's preview
				});

				onyxThumbDropzone.on("totaluploadprogress", function (progress) {

					var progr = document.querySelector(".progress .determinate");

					if (progr === undefined || progr === null) return;

					progr.style.width = progress + "%";
				});

				onyxThumbDropzone.on('dragenter', function () {
					$(target).addClass("hover");
				});

				onyxThumbDropzone.on('dragleave', function () {
					$(target).removeClass("hover");
				});

				onyxThumbDropzone.on('drop', function () {
					$(target).removeClass("hover");
				});

				onyxThumbDropzone.on('addedfile', function () {

					// Remove no files notice
					$(".no-files-uploaded").slideUp("easeInExpo");

				});

				onyxThumbDropzone.on('removedfile', function (file) {

					$.ajax({
						type: "POST",
						url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php",
						data: {
							target_file: file.upload.filename,
							// target_file: file.upload_ticket,
							delete_file: 1
						}
					});

					// Show no files notice
					if ( base.dropzoneCount() == 0 ) {
						$(".no-files-uploaded").slideDown("easeInExpo");
						$(".uploaded-files-count").html(base.dropzoneCount());
					}

				});

				onyxThumbDropzone.on("success", function(file, response) {
					let parsedResponse = JSON.parse(response);
						let idArr = [];
						// file.upload_ticket = parsedResponse.file_link;
						for (var i = 0; i < parsedResponse.data.length; i++) {
							idArr.push(parsedResponse.data[i]);
						}
						$(".preview-container > #extra-fields").css('display','block');

					// Make it wait a little bit to take the new element
					setTimeout(function(){
						$(".uploaded-files-count").html(base.dropzoneCount());
						console.log('Files count: ' + base.dropzoneCount());
					}, 350);


					// Something to happen when file is uploaded, like showing a message
					if ( typeof parsedResponse.info !== 'undefined' ) {
						console.log(parsedResponse.info);
						$(".preview-container > #extra-fields").css('display','block');
						warningsHolder.children('span').html(parsedResponse.info);
						warningsHolder.slideDown("easeInExpo");
					}
				});
			},
			dropzoneCount: function() {
				var filesCount = $(".previews-thumbnail > .dz-success.dz-complete").length;
				return filesCount;
			},
			fileType: function(fileName) {
				var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
				return fileType[0];
			}
		},
		/**
		 * Files upload
		 */

		 userFilesThumbnailDropzone: {

			selector: 'form.dropzone99',
			init: function() {
				var base = this,
					container = $(base.selector);

				base.initFileUploader(base, 'form.dropzone99');
			},
			initFileUploader: function(base, target) {
				var previewNode = document.querySelector("#onyx-dropzone-template"), // Dropzone template holder
					warningsHolder = $("#warnings"); // Warning messages' holder

				previewNode.id = "";

				var previewTemplate = previewNode.parentNode.innerHTML;
				previewNode.parentNode.removeChild(previewNode);
				Dropzone.autoDiscover = false;
					var max_file_size = 2000;
					var allowed_file_ext = '.jpg,.png,.jpeg';
				var onyxThumbDropzone = new Dropzone(target, {
					url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php", // Check that our form has an action attr and if not, set one here
	                maxFiles:1,
					maxFilesize: max_file_size,
					acceptedFiles: allowed_file_ext,
					previewTemplate: previewTemplate,
					previewsContainer: ".previews-thumbnail",
					clickable: true,
					uploadMultiple: false,
					addRemoveLinks: false,
					createImageThumbnails: true,
					timeout: 0,

					/**
					 * The text used before any files are dropped.
					 */
					dictDefaultMessage: "Drop & Choose file here to upload.", // Default: Drop files here to upload

					/**
					 * The text that replaces the default message text it the browser is not supported.
					 */
					dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.", // Default: Your browser does not support drag'n'drop file uploads.

					/**
					 * If the filesize is too big.
					 * `{{filesize}}` and `{{maxFilesize}}` will be replaced with the respective configuration values.
					 */
					dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", // Default: File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.

					/**
					 * If the file doesn't match the file type.
					 */
					dictInvalidFileType: "You can't upload files of this type.", // Default: You can't upload files of this type.

					/**
					 * If the server response was invalid.
					 * `{{statusCode}}` will be replaced with the servers status code.
					 */
					dictResponseError: "Server responded with {{statusCode}} code.", // Default: Server responded with {{statusCode}} code.

					/**
					 * If `addRemoveLinks` is true, the text to be used for the cancel upload link.
					 */
					dictCancelUpload: "Cancel upload.", // Default: Cancel upload

					/**
					 * The text that is displayed if an upload was manually canceled
					 */
					dictUploadCanceled: "Upload canceled.", // Default: Upload canceled.

					/**
					 * If `addRemoveLinks` is true, the text to be used for confirmation when cancelling upload.
					 */
					dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?", // Default: Are you sure you want to cancel this upload?

					/**
					 * If `addRemoveLinks` is true, the text to be used to remove a file.
					 */
					dictRemoveFile: "Remove file", // Default: Remove file

					/**
					 * If this is not null, then the user will be prompted before removing a file.
					 */
					dictRemoveFileConfirmation: null, // Default: null

					/**
					 * Displayed if `maxFiles` is st and exceeded.
					 * The string `{{maxFiles}}` will be replaced by the configuration value.
					 */
					dictMaxFilesExceeded: "You can not upload any more files.", // Default: You can not upload any more files.

					/**
					 * Allows you to translate the different units. Starting with `tb` for terabytes and going down to
					 * `b` for bytes.
					 */
					dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},

				});

				Dropzone.autoDiscover = false;

				onyxThumbDropzone.on("addedfile", function(file) {
					$('.preview-container').css('visibility', 'visible');
					file.previewElement.classList.add('type-' + base.fileType(file.name)); // Add type class for this element's preview
				});

				onyxThumbDropzone.on("totaluploadprogress", function (progress) {

					var progr = document.querySelector(".progress .determinate");

					if (progr === undefined || progr === null) return;

					progr.style.width = progress + "%";
				});

				onyxThumbDropzone.on('dragenter', function () {
					$(target).addClass("hover");
				});

				onyxThumbDropzone.on('dragleave', function () {
					$(target).removeClass("hover");
				});

				onyxThumbDropzone.on('drop', function () {
					$(target).removeClass("hover");
				});

				onyxThumbDropzone.on('addedfile', function () {

					// Remove no files notice
					$(".no-files-uploaded").slideUp("easeInExpo");

				});

				onyxThumbDropzone.on('removedfile', function (file) {

					$.ajax({
						type: "POST",
						url: ($(target).attr("action")) ? $(target).attr("action") : "../../file-upload.php",
						data: {
							target_file: file.upload.filename,
							// target_file: file.upload_ticket,
							delete_file: 1
						}
					});

					// Show no files notice
					if ( base.dropzoneCount() == 0 ) {
						$(".no-files-uploaded").slideDown("easeInExpo");
						$(".uploaded-files-count").html(base.dropzoneCount());
					}

				});

				onyxThumbDropzone.on("success", function(file, response) {
					let parsedResponse = JSON.parse(response);
						let idArr = [];
						// file.upload_ticket = parsedResponse.file_link;
						for (var i = 0; i < parsedResponse.data.length; i++) {
							idArr.push(parsedResponse.data[i]);
						}
						$(".preview-container > #extra-fields").css('display','block');

					// Make it wait a little bit to take the new element
					setTimeout(function(){
						$(".uploaded-files-count").html(base.dropzoneCount());
						console.log('Files count: ' + base.dropzoneCount());
					}, 350);


					// Something to happen when file is uploaded, like showing a message
					if ( typeof parsedResponse.info !== 'undefined' ) {
						console.log(parsedResponse.info);
						$(".preview-container > #extra-fields").css('display','block');
						warningsHolder.children('span').html(parsedResponse.info);
						warningsHolder.slideDown("easeInExpo");
					}
				});
			},
			dropzoneCount: function() {
				var filesCount = $(".previews-thumbnail > .dz-success.dz-complete").length;
				return filesCount;
			},
			fileType: function(fileName) {
				var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
				return fileType[0];
			}
		}
	}
	$(document).ready(function() {
		Onyx_Thumbnail.init();
	});

}(jQuery);


