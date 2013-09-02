var k_camera, k_scene, k_renderer;
var skeletonPoints = [];
var start;
var index = 0;

window.onload = function() {
	var params = { allowScriptAccess: "always" };
	var atts = { id: "myPlayer" };
	var videoId = $("#ytoutubeID").html();

	swfobject.embedSWF("http://www.youtube.com/v/"+videoId+"?enablejsapi=1&playerapiid=ytplayer&version=3",
		"video_sec", "300", "250", "8", null, null, params, atts);
}


function getReplayPage(id){
	 k_init();
     k_animate();
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

function moveDots(user){
    for(var i = 0; i < skeletonPoints.length; i++) {
            //Loop through each of the dots
            var kinectFeedPart = user.skeleton[i+1];
                // console.log(zig.Joint);
                //Get data information for each joint.
                if( typeof kinectFeedPart == 'undefined') { //If joint data isnt avaiable place dot offscreen and continue on.
                    var object = skeletonPoints[i];
                    object.position.x = 5000;
                    object.position.y = 5000;
                    if(isRecord)
                        pushSkeletonData(i ,t, [5000,5000,5000]);
                    continue;
                }
                var kinectFeedPosition = kinectFeedPart.position;
                var object = skeletonPoints[i];
                object.position.x = kinectFeedPosition[0] / 5;
                object.position.y = kinectFeedPosition[1] / 5;
                object.position.z = -kinectFeedPosition[2] / 5;
                var t = new Date().getTime() - start;
                if(isRecord){
                    // console.log(i+"=>"+object.position.x+", "+object.position.y+", "+object.position.z);
                    pushSkeletonData(i ,t, kinectFeedPart.position);
                }
    }
}

///Animating and rendering for three.js scene

function k_animate() {
    requestAnimationFrame(k_animate);
    replayMtn('');
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




function replayMtn(mtnArr){
	$('#record-btn').html('Record');
	k_camera.position.z = 800;
	k_camera.position.y = -350;

	if(index>=(mtnArr.length/24)){
		isReplay = 0;
		k_camera.position.z = 250;
		k_camera.position.y += (200 - k_camera.position.y ) * .05;

	}
	for(var i = 0; i < skeletonPoints.length; i++) {
					var tempArr;
					// console.log(mtnArr[index*24+i]);
					var object = skeletonPoints[i];

					if(typeof mtnArr[index*24+i]!='undefined'){
						tempArr = mtnArr[index*24+i].split(', ');
					}else{
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