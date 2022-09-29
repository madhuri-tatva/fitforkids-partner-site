<?php
include("../../includes/config.php");

$puid = $_GET['puid'];
$type = $_GET['type'];
?>


<div class="modal-header">
	<div class="col-md-6">
		<h3>Block settings <?php echo $puid; ?></h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save" class="btn-cta btn-save"><?php echo _('Save'); ?></button>
	</div>
</div>

<?php if($type == 'settingsParentRow'){ ?>


<div class="section divide-bottom">

	<div class="row col-5">

		<div id="template-col-1" class="radio-container">
			<label class="radio-label" onclick="loadColumnTemplates(1,'<?php echo $puid; ?>')">
				<input type="radio" name="BlockColumn" checked="checked">
				<span class="checkmark"></span>
				<span>One column</span>
				<span class="btn btn-expand-columns"><i class="icpa-arrow-down"></i></span>
			</label>
		</div>


		<div id="template-col-2" class="radio-container" onclick="loadColumnTemplates(2,'<?php echo $puid; ?>')">
			<label class="radio-label">
				<input type="radio" name="BlockColumn">
				<span class="checkmark"></span>
				<span>Two columns</span>
				<span class="btn btn-expand-columns"><i class="icpa-arrow-down"></i></span>
			</label>
		</div>


		<div id="template-col-3" class="radio-container" onclick="loadColumnTemplates(3,'<?php echo $puid; ?>')">
			<label class="radio-label">
				<input type="radio" name="BlockColumn">
				<span class="checkmark"></span>
				<span>Three columns</span>
				<span class="btn btn-expand-columns"><i class="icpa-arrow-down"></i></span>
			</label>
		</div>


		<div id="template-col-4" class="radio-container" onclick="loadColumnTemplates(4,'<?php echo $puid; ?>')">
			<label class="radio-label">
				<input type="radio" name="BlockColumn">
				<span class="checkmark"></span>
				<span>Four columns</span>
				<span class="btn btn-expand-columns"><i class="icpa-arrow-down"></i></span>
			</label>
		</div>


		<div id="template-col-5" class="radio-container" onclick="loadColumnTemplates(5,'<?php echo $puid; ?>')">
			<label class="radio-label">
				<input type="radio" name="BlockColumn">
				<span class="checkmark"></span>
				<span>Five columns</span>
				<span class="btn btn-expand-columns"><i class="icpa-arrow-down"></i></span>
			</label>
		</div>

		<div id="opty-column-templates" class="opty-column-templates"></div>

	</div>


</div>

<?php } ?>


<!-- GLOBAL SETTINGS -->

<div class="section section-split">

	<div class="section-initial">

		<div id="opty-css-template" class="col-md-7 opty-css-template">
			<div class="inner-border">

				<span class="small">Border</span>

				<div class="inner-margin">

					<span class="small">Margin</span>

					<div class="inner-padding">

						<span class="small">Padding</span>

						<input onclick="this.select();" id="element-top-padding" type="text" class="input-top" />
						<input onclick="this.select();" id="element-right-padding" type="text" class="input-right" />
						<input onclick="this.select();" id="element-bottom-padding" type="text" class="input-bottom" />
						<input onclick="this.select();" id="element-left-padding" type="text" class="input-left" />

					</div>

					<input onclick="this.select();" id="element-top-margin" type="text" class="input-top" />
					<input onclick="this.select();" id="element-right-margin" type="text" class="input-right" />
					<input onclick="this.select();" id="element-bottom-margin" type="text" class="input-bottom" />
					<input onclick="this.select();" id="element-left-margin" type="text" class="input-left" />

				</div>

				<input onclick="this.select();" id="element-top-border" type="text" class="input-top" />
				<input onclick="this.select();" id="element-right-border" type="text" class="input-right" />
				<input onclick="this.select();" id="element-bottom-border" type="text" class="input-bottom" />
				<input onclick="this.select();" id="element-left-border" type="text" class="input-left" />

			</div>
		</div>

		<div class="col-md-5">
			<div class="row">
				<h4>Background color</h4>
				<div id="colorSelector" data-bg-color=""><div style="background-color: #0000ff"></div></div>
				<div class="bg-color-remove">
					<a id="btn-remove-bgcolor" class="btn-remove-image"><?php echo _('Remove background color'); ?></a>
				</div>
			</div>

			<div class="row file-uploader">
				<h4>Background image</h4>

				<div class="file-none">
					<button class="btn-modal-split-push btn-add-image"><?php echo _('Add image'); ?></button>
				</div>

				<div class="file-filled">
					<div class="image"></div>
					<div id="imageSettingPosition" class="image-settings">
						<select>
							<option value="center">Center</option>
							<option value="top">Top</option>
							<option value="bottom">Bottom</option>
							<option value="right">Right</option>
							<option value="left">Left</option>
						</select>
					</div>
					<button class="btn-modal-split-push btn-add-image"><?php echo _('Change image'); ?></button>
					<button id="remove-image" class="btn-remove-image"><?php echo _('Remove image'); ?></button>
				</div>

			</div>
			<div class="row">
				<h4>CSS class</h4>
				<input id="elementClass" type="text" class="std" />
			</div>
			<div class="row">
				<h4>ID</h4>
				<input id="elementId" type="text" class="std" />
			</div>
		</div>

	</div>

	<div class="section-push">

		<div class="col-md-12">

			<button id="" class="btn-modal-split-back btn-goback">Back</button>

			<h4><?php echo _('Add to library'); ?></h4>

			<form id="dropzone" action="/file-upload.php" class="dropzone files-container">
				<div class="fallback">
					<input name="file" type="file" />
   					<input name='fileThumbnail' type='hidden' value=''>
				</div>
			</form>

			<span class="small">Only JPG, PNG, MP4 are supported. Maximum file size is 1 mb.</span>

			<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
			<span class="no-files-uploaded">No files uploaded yet.</span>

			<div class="preview-container dz-preview uploaded-files">
				<div id="previews">
					<div id="onyx-dropzone-template">
						<div class="onyx-dropzone-info">
							<div class="thumb-container">
								<img data-dz-thumbnail />
							</div>
							<div class="details">
								<div>
									<span data-dz-name></span> <span data-dz-size></span>
								</div>
								<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
								<div class="dz-error-message"><span data-dz-errormessage></span></div>
								<div class="actions">
									<a href="#!" data-dz-remove><i class="fa fa-times"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="mediagallery" class="mediagallery">

				<h4><?php echo _('Choose from library'); ?></h4>

				<?php 
				$db->where('CustomerId',$_SESSION['CustomerId']);
				$db->where('DepartmentId',$_SESSION['DepartmentId']);
				$gallary = $db->get('media');

				foreach($gallary as $item){ ?>
				<div class="media col-md-3" data-media-id="<?php echo $item['Id']; ?>">
					<?php if(
					$item['Type'] == 'mp4' or
					$item['Type'] == 'avi'
					){ ?>
					<span class='video'>
						<i></i>
					</span>
					<div class="media-meta" data-media-type="video" data-media-src="<?php echo $item['URL']; ?>">
						<span>Video - <?php echo $item['Type']; ?></span>
						<span class="small"><?php echo $item['FileName']; ?></span>
					</div>
					<?php }else{ ?>

					<span class='image' style="background-image: url('<?php echo $item['URL']; ?>');">
						<i></i>
					</span>
					<div class="media-meta" data-media-type="image" data-media-src="<?php echo $item['URL']; ?>">
						<span>Image - <?php echo $item['Type']; ?></span>
						<span class="small"><?php echo $item['FileName']; ?></span>
					</div>

					<?php }?>
				</div>
				<?php } ?>
			</div>

		</div>

	</div>

</div>




<script>

$('select').niceSelect();

var currentBackgroundColor = $('div[data-id=<?php echo $puid; ?>]').css('backgroundColor');

if(currentBackgroundColor == 'rgba(0, 0, 0, 0.024)'){
 	currentBackgroundColor = '';
}

if(currentBackgroundColor != ''){
	$('.bg-color-remove').addClass('show');
}

$('#colorSelector div').css('backgroundColor', currentBackgroundColor);

$('#colorSelector').attr('data-bg-color', currentBackgroundColor);


$('#colorSelector').ColorPicker({
	//color: '#0000ff',
	color: $('div[data-id=<?php echo $puid; ?>]').css('backgroundColor'),
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(500);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#colorSelector div').css('backgroundColor', '#' + hex);
		$('#colorSelector').attr('data-bg-color', '#' + hex);
		//$('div[data-id=<?php echo $puid; ?>]').css('backgroundColor', '#' + hex);
	}
});


</script>

<script>


var parentuid = '<?php echo $puid; ?>';

$('#mediagallery .media').click(function(){
	$('#mediagallery .media').removeClass('active');
	$(this).addClass('active');
});


$('.btn-expand-columns').click(function(){
	$('.opty-column-templates').toggleClass('show');
});

$('.btn-modal-split-push').click(function(){

	$('.section-split .section-push').addClass('push');
	$('.section-split .section-initial').addClass('push');

});

$('.btn-modal-split-back').click(function(){

	$('.section-split .section-push').removeClass('push');
	$('.section-split .section-initial').removeClass('push');

});

$('#remove-image').click(function(){

	$('#mediagallery .media').removeClass('active');
	$('.file-none').addClass('show');
	$('.file-filled').removeClass('show');
	$('.file-filled .image').css("background-image", '');

});

$('#btn-remove-bgcolor').click(function(){

	$('.bg-color-remove').removeClass('show');
	$('#colorSelector').attr('data-bg-color','');
	$('#colorSelector div').css('background-color','');

});



// INIT
var initCols = $('div[data-id='+parentuid+']').attr("data-set-cols");
var initColSize = $('div[data-id='+parentuid+']').attr("data-set-col-size");

$('#template-col-'+initCols+' input').attr('checked','checked');

var elementBackgroundMedia = $('div[data-id=<?php echo $puid; ?>]').css('background-image');

if(elementBackgroundMedia == undefined || elementBackgroundMedia == 'none'){
	$('.file-none').addClass('show');
}else{
	$('.file-filled').addClass('show');
	$('.file-filled .image').css("background-image", elementBackgroundMedia);
}

var elementBackgroundMediaId = $('div[data-id=<?php echo $puid; ?>]').attr('data-media-id');

if(elementBackgroundMediaId != undefined){

	$('#mediagallery .media[data-media-id = '+elementBackgroundMediaId+']').addClass('active');

}


var bgColor = $('div[data-id=<?php echo $puid; ?>]').css('background-color');

var elementTopPadding = $('div[data-id=<?php echo $puid; ?>]').css('padding-top');
var elementRightPadding = $('div[data-id=<?php echo $puid; ?>]').css('padding-right');
var elementBottomPadding = $('div[data-id=<?php echo $puid; ?>]').css('padding-bottom');
var elementLeftPadding = $('div[data-id=<?php echo $puid; ?>]').css('padding-left');

var elementTopPadding = elementTopPadding.replace('px','');
var elementRightPadding = elementRightPadding.replace('px','');
var elementBottomPadding = elementBottomPadding.replace('px','');
var elementLeftPadding = elementLeftPadding.replace('px','');

var elementTopMargin = $('div[data-id=<?php echo $puid; ?>]').css('margin-top');
var elementRightMargin = $('div[data-id=<?php echo $puid; ?>]').css('margin-right');
var elementBottomMargin = $('div[data-id=<?php echo $puid; ?>]').css('margin-bottom');
var elementLeftMargin = $('div[data-id=<?php echo $puid; ?>]').css('margin-left');

var elementTopMargin = elementTopMargin.replace('px','');
var elementRightMargin = elementRightMargin.replace('px','');
var elementBottomMargin = elementBottomMargin.replace('px','');
var elementLeftMargin = elementLeftMargin.replace('px','');


var elementTopBorder = $('div[data-id=<?php echo $puid; ?>]').attr('border-top');
var elementRightBorder = $('div[data-id=<?php echo $puid; ?>]').attr('border-right');
var elementBottomBorder = $('div[data-id=<?php echo $puid; ?>]').attr('border-bottom');
var elementLeftBorder = $('div[data-id=<?php echo $puid; ?>]').attr('border-left');


if (elementTopBorder == undefined) {
	var elementTopBorder = 0;
}else{
	var elementTopBorder = elementTopBorder.replace('px','');
}

if (elementRightBorder == undefined) {
	var elementRightBorder = 0;
}else{
	var elementRightBorder = elementRightBorder.replace('px','');
}

if (elementBottomBorder == undefined) {
	var elementBottomBorder = 0;
}else{
	var elementBottomBorder = elementBottomBorder.replace('px','');
}

if ( elementLeftBorder == undefined) {
	var elementLeftBorder = 0;
}else{
	var elementLeftBorder = elementLeftBorder.replace('px','');
}







$('#element-top-padding').val(elementTopPadding);
$('#element-right-padding').val(elementRightPadding);
$('#element-bottom-padding').val(elementBottomPadding);
$('#element-left-padding').val(elementLeftPadding);

$('#element-top-margin').val(elementTopMargin);
$('#element-right-margin').val(elementRightMargin);
$('#element-bottom-margin').val(elementBottomMargin);
$('#element-left-margin').val(elementLeftMargin);

$('#element-top-border').val(elementTopBorder);
$('#element-right-border').val(elementRightBorder);
$('#element-bottom-border').val(elementBottomBorder);
$('#element-left-border').val(elementLeftBorder);



$('#modal-builder #btn-modal-save').click(function(){

	// Update background
	var elementBackgroundMedia = $('#mediagallery .active .media-meta').attr('data-media-src');
	var elementBackgroundType = $('#mediagallery .active .media-meta').attr('data-media-type');
	var elementBackgroundMediaId = $('#mediagallery .active').attr('data-media-id');

	var elementBackgroundMediaPosition = $('#imageSettingPosition .selected').attr('data-value');

	if(elementBackgroundMediaPosition == undefined){
		elementBackgroundMediaPosition = 'center';
	}

	//elementBackgroundMedia = elementBackgroundMedia.replace(/ /g, '%20');
	//console.log(elementBackgroundMedia);

	// Update columns
	var cols = $('#opty-column-templates .active').attr('data-cols');
	var colsize = $('#opty-column-templates .active').attr('data-col-size');
	var parentuid = '<?php echo $puid; ?>';

	if(cols == undefined || colsize == undefined){

		cols = $('div[data-id='+parentuid+']').attr("data-set-cols");
		colsize = $('div[data-id='+parentuid+']').attr("data-set-col-size");

	}


	updateColumnTemplate(parentuid,cols,colsize);

	// Update attributes
	var bgColor = $('#colorSelector').attr('data-bg-color');

	var elementTopPadding = $('#element-top-padding').val();
	var elementRightPadding = $('#element-right-padding').val();
	var elementBottomPadding = $('#element-bottom-padding').val();
	var elementLeftPadding = $('#element-left-padding').val();

	var elementTopMargin = $('#element-top-margin').val();
	var elementRightMargin = $('#element-right-margin').val();
	var elementBottomMargin = $('#element-bottom-margin').val();
	var elementLeftMargin = $('#element-left-margin').val();

	var elementTopBorder = $('#element-top-border').val();
	var elementRightBorder = $('#element-right-border').val();
	var elementBottomBorder = $('#element-bottom-border').val();
	var elementLeftBorder = $('#element-left-border').val();

	var elementClass = $('#elementClass').val();
	var elementId = $('#elementId').val();

	$('div[data-id='+parentuid+']').css("background-color", bgColor);

	if(elementBackgroundMedia != undefined){

		$('div[data-id='+parentuid+']').attr("data-media-id", elementBackgroundMediaId);
		$('div[data-id='+parentuid+']').css("background-image", 'url(' + elementBackgroundMedia + ')');
		$('div[data-id='+parentuid+']').css("background-size", 'cover');
		$('div[data-id='+parentuid+']').css("background-position", elementBackgroundMediaPosition);
		$('div[data-id='+parentuid+']').css("background-repeat", 'no-repeat');
	}else{
		$('div[data-id='+parentuid+']').css("background-image", '');
	}

	$('div[data-id='+parentuid+']').css("padding-top", elementTopPadding + "px");
	$('div[data-id='+parentuid+']').css("padding-right", elementRightPadding + "px");
	$('div[data-id='+parentuid+']').css("padding-bottom", elementBottomPadding + "px");
	$('div[data-id='+parentuid+']').css("padding-left", elementLeftPadding + "px");

	$('div[data-id='+parentuid+']').css("margin-top", elementTopMargin + "px");
	$('div[data-id='+parentuid+']').css("margin-right", elementRightMargin + "px");
	$('div[data-id='+parentuid+']').css("margin-bottom", elementBottomMargin + "px");
	$('div[data-id='+parentuid+']').css("margin-left", elementLeftMargin + "px");

	$('div[data-id='+parentuid+']').css("border-top-width", elementTopBorder + "px");
	$('div[data-id='+parentuid+']').css("border-right-width", elementRightBorder + "px");
	$('div[data-id='+parentuid+']').css("border-bottom-width", elementBottomBorder + "px");
	$('div[data-id='+parentuid+']').css("border-left-width", elementLeftBorder + "px");

	$('div[data-id='+parentuid+']').attr('id',elementId);
	$('div[data-id='+parentuid+']').addClass(elementClass);


});
</script>

