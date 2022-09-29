
!function ($) {

	"use strict";

	// Global Onyx object
	var Onyx = Onyx || {};


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
			selector: '#dropzone',
			init: function() {
				var base = this,
					container = $(base.selector);

				base.initFileUploader(base, '#dropzone');


			},
			initFileUploader: function(base, target) {
				var previewNode = document.querySelector("#onyx-dropzone-template");

				previewNode.id = "";

				//console.log(previewNode);

				var previewTemplate = previewNode.parentNode.innerHTML;
				//previewNode.parentNode.removeChild(previewNode);

				var onyxDropzone = new Dropzone(target, {
					url: ($(target).attr("action")) ? $(target).attr("action") : "/file-upload-profile.php", // Check that our form has an action attr and if not, set one here
	                maxFiles: 1,
					maxFilesize: 20,
					acceptedFiles: "image/*",
					previewTemplate: previewTemplate,
					previewsContainer: "#previews",
					clickable: true,
					uploadMultiple: false,
					parallelUploads: 1,
					createImageThumbnails: true,
						transformFile: function(file, done) {

							var myDropZone = this;

							// Create the image editor overlay
							var editor = document.createElement('div');
							editor.style.position = 'fixed';
							editor.style.left = 0;
							editor.style.right = 0;
							editor.style.top = 0;
							editor.style.bottom = 0;
							editor.style.zIndex = 9999;

							// Create the confirm button
							var confirm = document.createElement('button');
							confirm.style.position = 'absolute';
							confirm.style.left = '196px';
							confirm.style.top = '10px';
							confirm.style.zIndex = 9999;
							confirm.textContent = 'Confirm';
							confirm.addEventListener('click', function() {

								// Get the canvas with image data from Cropper.js
								var canvas = cropper.getCroppedCanvas({
									width: 256,
									height: 256
								});

								// Turn the canvas into a Blob (file object without a name)
								canvas.toBlob(function(blob) {

									// Update the image thumbnail with the new image data
									myDropZone.createThumbnail(
										blob,
										myDropZone.options.thumbnailWidth,
										myDropZone.options.thumbnailHeight,
										myDropZone.options.thumbnailMethod,
										false, 
										function(dataURL) {

											// Update the Dropzone file thumbnail
											myDropZone.emit('thumbnail', file, dataURL);

											// Return modified file to dropzone
											done(blob);
										}
									);

								});

								// Remove the editor from view
								editor.parentNode.removeChild(editor);

							});
							editor.appendChild(confirm);

							// Load the image
							var image = new Image();
							image.src = URL.createObjectURL(file);
							editor.appendChild(image);

							// Append the editor to the page
							document.body.appendChild(editor);

							// Create Cropper.js and pass image
							var cropper = new Cropper(image, {
						        dragMode: 'move',
						        aspectRatio: 16 / 9,
						        autoCropArea: 0.65,
						        restore: false,
						        guides: false,
						        center: false,
						        highlight: false,
						        cropBoxMovable: true,
						        cropBoxResizable: true,
						        toggleDragModeOnDblclick: false,
					      	});

						},

					/**
					 * The text used before any files are dropped.
					 */
					dictDefaultMessage: "Upload profile picture", // Default: Drop files here to upload

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

					console.log('Add file');

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

				onyxDropzone.on('thumbnail', function(file, thumb) {

					console.log('Thumbnail');

				    file.thumbnail = thumb;

				    onyxDropzone.processQueue();

				    //console.log(file.thumbnail);

				});

				onyxDropzone.on("sending", function(file, xhr, formData) {

					console.log('Sending');

				    //file.newname = "my-new-name" + file.name;
					//formData.append('fileThumbnail', file.thumbnail);


					console.log(file);
					//console.log(file.thumbnail);

				    /*console.log(file.thumbnail);
				    console.log(formData);
				    console.log(file);*/

				});

				onyxDropzone.on('addedfile', function () {

					console.log('Added file');
					// Remove no files notice
					$(".no-files-uploaded").slideUp("easeInExpo");

				});

				onyxDropzone.on("complete", function(file) {
					console.log('Complete');
				});

				onyxDropzone.on('removedfile', function (file) {

					console.log('Removed');

					$.ajax({
						type: "POST",
						url: ($(target).attr("action")) ? $(target).attr("action") : "/file-upload-profile.php",
						data: {
							target_file: file.upload_ticket,
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

					console.log('Success');
					let parsedResponse = JSON.parse(response);
					file.upload_ticket = parsedResponse.file_link;

					// Make it wait a little bit to take the new element
					setTimeout(function(){
						$(".uploaded-files-count").html(base.dropzoneCount());
					}, 350);

					// Something to happen when file is uploaded
				});



			},
			dropzoneCount: function() {
				var filesCount = $("#previews > .dz-success.dz-complete").length;
				return filesCount;
			},
			fileType: function(fileName) {
				var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
				return fileType[0];
			}
		}
	}


	
	Onyx.init();

}(jQuery);

