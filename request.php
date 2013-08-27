<?php

$out['self'] = 'request';

require 'header.php';
include "connectSql.php";

$library = '
  <script type="text/javascript" src="/kinect/js/request.js"></script>
  <script src="/kinect/js/lib/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
  <link href="/kinect/css/jquery.tagit.css" rel="stylesheet" type="text/css">
  <link href="/kinect/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">';

if(isGET('do')){
  if(!isLogin()){
      $out['content'] = 'You need to login in.';
  }else{
        	$out['content'] = $library.'
        		<div class="row">
        <div class="col-lg-4 video_container">
          <div id="video_sec">
            <img src="http://thecogentcoach.com/wordpress/wp-content/uploads/2012/11/video-play-2.gif" style="width:350px;">
        </div>
        <br/><br/>
        <div id="slider-range">
        </div>
      </div>
        <div class="col-lg-5">
          <form id="form-request-video">
            <fieldset>
              <legend>Request your desired video</legend>
              <div class="form-group">
              <label for="vid">Your video </label>
              <div class="input-group">
                <span class="input-group-addon">http://www.youtube.com/watch?v=</span>
                <input type="text" name="vid" class="form-control" id="videoInput" placeholder="ID">
              </div>
              </div>
              <div class="form-group">
                  <label for="period">Period </label>
                <div class="input-group">
                  <span class="input-group-addon">Start From</span>
                  <input type="text" name="start" id="start_input" class="form-control">
                  <input type="hidden" name="start_value" id="start_input_value" class="form-control">
                  <span class="input-group-addon">to</span>
                  <input type="text" name="end" id="end_input" class="form-control">
                  <input type="hidden" name="end_value" id="end_input_value" class="form-control">

                </div>
              </div>
              <div class="form-group">
                <label for="TimeNeeded">Time needed </label>
                <div class="input-group">
                  <span class="input-group-addon">You need</span>
                  <select name="TimeNeeded" class="form-control">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                  </select>                  
                  <span class="input-group-addon">day(s) to digitalize this video.</span>
                </div>
              </div>
              <div class="form-group">
                <label for="tag">Motion Tag(s) </label>
                  <input name="tags" id="mySingleField" value="" disabled="true" style="display:none;">
                  <ul id="singleFieldTags"></ul>
              </div>
              <button type="submit" id="btn-request-vid" class="btn btn-default">Request</button>
            </fieldset>
          </form>

        </div>
        <div class="col-lg-3">
          <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Some sugar!</h4>
            <p>...</p>
          </div>
        </div>
    </div>';
  }
}else{
	redirect('request.php/do');
}
require 'footer.php';

?>
