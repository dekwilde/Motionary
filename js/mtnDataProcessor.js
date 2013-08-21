var isRecord = 0;
var isReplay = 0;
var index = 0;
var mtnArr = new Array();

$(function allowRecordData(){
	$('#record-btn').click(function(e){
		console.log('Start Record...');
		var count = 5, countdown;
		countdown = setInterval(function(){
			var seconds = count;
			$("#record-btn").html(seconds + " seconds to start");
			if (count == 0) {
				console.log('count = 0');
				$('#record-btn').html('Recording...');
				isRecord = 1;
				clearInterval(countdown);
			}
			if(count>0)
				count--;
		}, 1000);
		mtnArr = new Array();
		start = new Date().getTime();
	});

	$('#replay-btn').click(function(e){
		isReplay = 1;
		index = 0;
	});

	$('#store-btn').click(function(){
		sendData();
	});
})

function addData(i ,t, msg){
	mtnArr.push(i + ', ' +t + ', ' + msg[0] + ', ' + msg[1] + ', ' + -msg[2]);
}

function sendData(){
	 var mtnStr = '';
     isRecord  = 0;
     // console.log(mtnArr);
     if(mtnArr.length>0){	
     	mtnStr = mtnArr.join(':');
     	console.log(mtnStr);
     }	
     
     if(mtnStr!=''){
     	 $('#store-btn').html('storing...');
	     $.post("/kinect/mtnDataRetriver.php", {mtnData: mtnStr}, function(reMsg){
	     	// console.log(reMsg);
	     	// mtnArr = new Array();
	     	$('#store-btn').html('store');

	     });
	 }else{
	 	return;
	 }
}

function replayMtn(){
	k_camera.position.z = 800;
	if(index>=(mtnArr.length/24)){
		isReplay = 0;
		k_camera.position.z = 250;
	}
	for(var i = 0; i < skeletonPoints.length; i++) {
					var tempArr = mtnArr[index*24+i].split(', ');
	                var object = skeletonPoints[i];
	                console.log((index*24+i)+': '+tempArr[2]+', '+tempArr[3]+', '+tempArr[4]);
	                object.position.x = tempArr[2] / 5;
	                object.position.y = tempArr[3] / 5;
	                object.position.z = - (tempArr[4] / 5);
	                console.log(i+"=>"+object.position.x+", "+object.position.y+", "+object.position.z);
	}
	index++;
	// console.log(index);
}