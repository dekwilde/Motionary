var k_camera, k_scene, k_renderer;
var skeletonPoints = [];
var start;
var index = 0;
var mtnArr;
var isReplay = 0;

window.onload = function() {
	var params = { allowScriptAccess: "always" };
	var atts = { id: "myPlayer" };
	var videoId = $("#ytoutubeID").html();

	swfobject.embedSWF("http://www.youtube.com/v/"+videoId+"?enablejsapi=1&playerapiid=ytplayer&version=3",
		"video_sec", "300", "250", "8", null, null, params, atts);
	k_init();
	k_animate();

}


function getReplayPage(id){
    var working = true;
    $('#replay-btn').html('Loading motion data...');
    $('#replay-btn').addClass('disabled');
    $.post("/kinect/mtnDataRetriver.php/retrieve", {mid: id}, function(reMsg){
            working = false;
            // console.log(reMsg);
            // mtnArr = new Array();
            $('#replay-btn').html('Replay this motion now!');
            $('#replay-btn').removeClass('disabled');
            mtnArr = reMsg.mtnArr.split(':'); 
    },'json');
    $('#replay-btn').click(function(){
        if(!working){    
            index = 0;
            isReplay = 1;
        }

    });
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
    if(isReplay)    
        replayMtn();
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
                            object.position.x = tempArr[2] / 5;
                            object.position.y = tempArr[3] / 5;
                            object.position.z = - (tempArr[4] / 5);
                            
                        }    
                        isReplay = 0;
						continue;
					}

	                // console.log((index*24+i)+': '+tempArr[2]+', '+tempArr[3]+', '+tempArr[4]);
	                object.position.x = tempArr[2] / 5;
	                object.position.y = tempArr[3] / 5;
	                object.position.z = - (tempArr[4] / 5);
	                // console.log(i+"=>"+object.position.x+", "+object.position.y+", "+object.position.z);
	}
	index++;
	// console.log(index);
}