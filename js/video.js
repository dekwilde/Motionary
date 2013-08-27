

window.onload = function() {
	var params = { allowScriptAccess: "always" };
	var atts = { id: "myPlayer" };
	var videoId = $("#ytoutubeID").html();

	swfobject.embedSWF("http://www.youtube.com/v/"+videoId+"?enablejsapi=1&playerapiid=ytplayer&version=3",
		"video_sec", "300", "250", "8", null, null, params, atts);
}