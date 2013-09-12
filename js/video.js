var k_camera, k_scene, k_renderer;
var skeletonPoints = [];
var start;
var index = 0;
var mtnArr;
var isReplay = 0;
var startTime = 0;
var rplayerObj;
var playerObj;

window.onload = function() {
	var params = { allowScriptAccess: "always" };
	var atts = { id: "myPlayer" };
	var videoId = $("#ytoutubeID").html();
    // var playTimer;

	swfobject.embedSWF("http://swf.tubechop.com/tubechop.swf?vurl="+videoId+"&start="+$('#start-time').html()+"&end="+$("#end-time").html(),
		"video_sec", "289", "250", "8", null, null, params, atts);

    $('#play-video-btn').click(function(){
        // clearTimeout(playTimer);
        // playerObj.seekTo($('#start-time').html(), true);
        // playTimer = setTimeout(function(){playerObj.stopVideo()}, parseInt(($('#period').html()))*1000);
    });

	k_init();
	k_animate();

}


function getReplayPage(id){
    // this function is called to initialize the replay page when users click the replay this motion btn.
    var working = true;
    var params = { allowScriptAccess: "always" };
    var atts = { id: "myRePlayer" };
    var videoId = $("#ytoutubeID").html();

    //stop playing the video in video.php
    // playerObj.stopVideo();

    //load the video
    swfobject.embedSWF("http://www.youtube.com/v/"+videoId+"?enablejsapi=1&playerapiid=replayplayer&version=3",
        "video_replay_sec", "300", "300", "8", null, null, params, atts);

    $('#replay-btn').html('Loading motion data...');
    $('#replay-btn').addClass('disabled');

    $.post("/kinect/mtnDataRetriver.php/retrieve", {mid: id}, function(reMsg){
            working = false;
            // console.log(reMsg);
            // mtnArr = new Array();
            $('#contributor').html(reMsg.contributor);
            $('#replay-btn').html('Replay this motion now!');
            $('#replay-btn').removeClass('disabled');
            $('#star').raty({ path: 'http://lockys.hopto.org/kinect/js/rat-lib/img/', size     : 24,
                              starHalf : 'star-half-big.png',
                              starOff  : 'star-off-big.png',
                              starOn   : 'star-on-big.png', 
                              click: function(score, evt) {
                                alert('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt);
                              } 
                            });
            mtnArr = reMsg.mtnArr.split(':'); 
    },'json');

    $('#replay-btn').click(function(){
        if(!working){    
            index = 0;
            isReplay = 1;
            // alert($('#start-time').html());
            rplayerObj.seekTo($('#start-time').html(), true);
        }

    });

    $('#myModal').on('hidden.bs.modal', function () {
        index = 0;
        isReplay = 0;
    })
}


function onYouTubePlayerReady(playerId) {
    rplayerObj = document.getElementById("myRePlayer");   
    playerObj = document.getElementById("myPlayer");    

}



function k_init() {
    var k_container = document.getElementById('area_motion');
    var width = 400;
    var height = 500;

    k_camera = new THREE.PerspectiveCamera(75, width / height, 1, 1000);
    
    k_camera.position.z = 250;
    k_scene = new THREE.Scene();
    k_renderer = new THREE.CanvasRenderer();
    k_renderer.setSize(width, height);
    k_container.appendChild(k_renderer.domElement);

   

    // skeleteon points initializations
    var geometry = new THREE.Geometry();

    for(var i = 0; i < 24; i++) {
        //Make 24 white circles for each of the joints we are going to recieve from the kinect feed.
        var sphere = null;
        if(i == 1){
            sphere = new THREE.Mesh(new THREE.SphereGeometry(10, 6, 5), new THREE.MeshBasicMaterial({color: 0xCC0000, opacity:0.5, transparent:true}));
        }
        else{
            sphere = new THREE.Mesh(new THREE.SphereGeometry(10, 6, 5), new THREE.MeshBasicMaterial({color: 0x6666FF, opacity:0.5, transparent:true}));
        }
        sphere.overdraw = true;
        sphere.position.x = 0;   
        sphere.position.y = 0;
        sphere.position.z = 0;
        k_scene.add(sphere);
        skeletonPoints.push(sphere);
    }


}
///Animating and rendering for three.js scene

function k_animate() {
    requestAnimationFrame(k_animate);
    if(isReplay){
        if(rplayerObj.getPlayerState()==1)
            replayMtn();
    }
    k_render();
    // console.log(new Date().getTime());
}

function k_render() {
    k_camera.position.x += (0 - k_camera.position.x ) * .05;
    k_camera.position.y += (200 - k_camera.position.y ) * .05;
    // console.log(k_camera);
    k_camera.lookAt(k_scene.position);
    // console.log(k_scene.position);
    k_renderer.render(k_scene, k_camera);
}



//replay function
function replayMtn(){
	k_camera.position.z = 800;
	k_camera.position.y = -350;

	for(var i = 0; i < skeletonPoints.length; i++) {
					var tempArr;
					// console.log(mtnArr[index*24+i]);
                    var object = skeletonPoints[i];

					if(typeof mtnArr[index*24+i]!='undefined'){
						tempArr = mtnArr[index*24+i].split(', ');
					}else{
                        for(var i = 0; i < skeletonPoints.length; i++) {
                            var object = skeletonPoints[i];
                            object.position.x = 5000;
                            object.position.y = 5000;
                            object.position.z = 5000;
                            
                        }    
                        
                        isReplay = 0;

                        rplayerObj.stopVideo();

                        $('#replay-btn').html('Replay this motion now!');
						continue;
					}
                    if(index==1){
                        // alert('fuck');
                        startTime = tempArr[1];
                    }
                    $('#replay-btn').html(((tempArr[1]-startTime)/1000).toFixed(3)+' s');
	                // console.log((index*24+i)+': '+tempArr[2]+', '+tempArr[3]+', '+tempArr[4]);
	                object.position.x = tempArr[2] / 5;
	                object.position.y = tempArr[3] / 5;
	                object.position.z = - (tempArr[4] / 5);
	                // console.log(i+"=>"+object.position.x+", "+object.position.y+", "+object.position.z);
	}

	index++;
	// console.log(index);
}