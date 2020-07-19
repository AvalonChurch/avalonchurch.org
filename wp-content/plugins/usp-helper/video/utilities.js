// USP Pro Helper Plugin for HTML5 Video

jQuery(function($) {
	
	// auto width & height for video tag
    $("#usp-video").bind("loadedmetadata", function() {
        var width  = this.videoWidth;
        var height = this.videoHeight;
        
        $(this).attr("width",  width);
        $(this).attr("height", height);
    });
	
});
