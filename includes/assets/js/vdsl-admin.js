jQuery(document).ready(function($){
	
	// Tabs
	$('.vdsl-tabs-nav div').click(function() {
		if($(this).hasClass('active')) {
			// Do nothing
		} else {
			$('.vdsl-tabs-nav').find('div').removeClass('active');
			var currentTab = $(this).attr('class');
			$(this).addClass('active');
			$('.tab').hide();
			$('.' + currentTab).show();
		}
	});
	
	
	// Upload Map Icons
	$('.vdsl_upload_button').click(function(e) {
		var mediaUploader;
		
	    e.preventDefault();
	    
	    var currentBtn = this;
		console.log($(currentBtn).attr('id'));
	    
	    if (mediaUploader) {
	    	mediaUploader.open();
			return;
	    }
	    
	    // Media Upload Window
	    mediaUploader = wp.media.frames.file_frame = wp.media({
	    	title: vdslMapScript.mediaTitle,
			button: {
				text: vdslMapScript.mediaBtn
	    	}, 
	    	multiple: false 
	    });
		
		// Feed Image URL to text Field & Image Preview
		mediaUploader.on('select', function() {
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			$(currentBtn).siblings('.vdsl_upload_text').val(attachment.url);
			$(currentBtn).siblings('.vdsl_upload_wrap').find('img').attr({'src': attachment.url});
    	});
	    mediaUploader.open();
	});
	
});