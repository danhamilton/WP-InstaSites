<?php	
	// handle if this is a post
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		update_option('bapi_slideshow_image1', $_POST['bapi_slideshow_image1']);
		update_option('bapi_slideshow_image2', $_POST['bapi_slideshow_image2']);
		update_option('bapi_slideshow_image3', $_POST['bapi_slideshow_image3']);
        update_option('bapi_slideshow_image5', $_POST['bapi_slideshow_image5']);
		update_option('bapi_slideshow_image6', $_POST['bapi_slideshow_image6']);
		
		update_option('bapi_slideshow_caption1', $_POST['bapi_slideshow_caption1']);
		update_option('bapi_slideshow_caption2', $_POST['bapi_slideshow_caption2']);
		update_option('bapi_slideshow_caption3', $_POST['bapi_slideshow_caption3']);
		update_option('bapi_slideshow_caption4', $_POST['bapi_slideshow_caption4']);
		update_option('bapi_slideshow_caption5', $_POST['bapi_slideshow_caption5']);
		update_option('bapi_slideshow_caption6', $_POST['bapi_slideshow_caption6']);
		
		echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
	}
	 
	function saveFrame($frameNum){
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					update_option('bapi_slideshow_image'.$frameNum, $_POST['bapi_slideshow_image'.$frameNum]);
					echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
					
			}
	}
	
	
?> 
<script type="text/javascript">
	jQuery(document).ready(function($){
		/* Clear the Favicon input field */
		$('.clear-btn').click(function() {
				$('#'+$(this).attr('data-for')).val('');
			return false;
		});
		//sort
		$('.up-btn').click(function() {
			var thisVal = $('#bapi_slideshow_image'+$(this).attr('data-for')).val();
			var aboveFieldNumber = parseInt($(this).attr('data-for'))-1;
			var aboveField = '#bapi_slideshow_image'+aboveFieldNumber;
			var aboveFieldVal = $(aboveField).val();
			if(thisVal !== ''){
				$(aboveField).val(thisVal);
				$('#bapi_slideshow_image'+$(this).attr('data-for')).val(aboveFieldVal);
			}
			return false;
		});
		$('.down-btn').click(function() {
			var thisVal = $('#bapi_slideshow_image'+$(this).attr('data-for')).val();
			var belowFieldNumber = parseInt($(this).attr('data-for'))+1;
			var belowField = '#bapi_slideshow_image'+belowFieldNumber;
			var belowFieldVal = $(belowField).val();
			if(thisVal !== ''){
				$(belowField).val(thisVal);
				$('#bapi_slideshow_image'+$(this).attr('data-for')).val(belowFieldVal);
			}
			return false;
		});
			<?php

			for ($i=0; $i <=12 ; $i++) {
				$getOpt= get_option('bapi_slideshow_image'.$i); 
					if($getOpt){
					$num = $i+1;
					}
						
				}
			?>
		  	var img = <?php echo $num;?>;
			$("#createNewFrame").click(function(){
			
				alert(img);
			
			var newFrame = {
				newList:$("<li>", {class: "slideframe"}),
				newLeft:$("<div>", {class: "frameLeft"}),
				newRight:$("<div>", {class:"frameRight"}),
				newImage:$("<img>", {alt:"clear", title:"Clear Field", src:"", width:"250", height:"150"}),
				newButton:$("<div>", {class:"greyButton" }).click(function(){
						wp.media.editor.send.attachment = function(props, attachment){
									$(newFrame.input2).val(attachment.url);
									$(newFrame.newImage).attr("src", attachment.url);
										}
									wp.media.editor.open(this);
									return false;
				}),
				buttonText:"Add or Edit Image",
				slideRow1:$("<div>", {class:"slideRows1"}),
				caption:$("<div>", {class:"caption"}),
				captext:"Caption:",
				capInput:$("<div>", {class:"capInput"}),
				input1:$("<input>", {class:"inputText",type:"text" }),
				
				slideRow2:$("<div>", {class:"slideRows2"}),
				urldiv:$("<div>", {class:"urldiv"}),
				urlText:"URL:",
				input2Div:$("<div>", {class:"input2div"}),
				input2:$("<input>", {class:"inputText2", type:"text", id:"bapi_slideshow_image"+img, name:"bapi_slideshow_image"+img}),
				selectImgDiv:$("<div>", {class:"selectImg"}),
				selectImg:$("<div>", {class:"leftFlatButton"}),
				selectImgText:"Select Image",
				
				slideRow3:$("<div>", {class:"slideRows3"}),
				deletButton:$("<div>", {class:"greyButton deleteThisFrame"}),
				deleteText:"Delete This Frame"
	
			};
			
			newFrame.newList.appendTo("#sortable");
			newFrame.newLeft.appendTo(newFrame.newList);
			newFrame.newRight.appendTo(newFrame.newList);
			newFrame.newImage.appendTo(newFrame.newLeft);
			newFrame.newButton.appendTo(newFrame.newLeft);
			newFrame.newButton.text(newFrame.buttonText);
			newFrame.slideRow1.appendTo(newFrame.newRight);
			newFrame.caption.appendTo(newFrame.slideRow1);
			newFrame.caption.text(newFrame.captext);
			newFrame.capInput.appendTo(newFrame.slideRow1);
			newFrame.input1.appendTo(newFrame.capInput);
			newFrame.slideRow2.appendTo(newFrame.newRight);
			newFrame.urldiv.appendTo(newFrame.slideRow2);
			newFrame.urldiv.text(newFrame.urlText);
			newFrame.input2Div.appendTo(newFrame.slideRow2);
			newFrame.input2.appendTo(newFrame.input2Div);
			newFrame.selectImgDiv.appendTo(newFrame.slideRow2);
			newFrame.selectImg.appendTo(newFrame.selectImgDiv);
			newFrame.selectImg.text(newFrame.selectImgText);
			newFrame.slideRow3.appendTo(newFrame.newRight);
			newFrame.deletButton.appendTo(newFrame.slideRow3);
			newFrame.deletButton.text(newFrame.deleteText);
			
			img++;
			
		});
		
		
		 $( "#sortable" ).sortable();
    	 $( "#sortable" ).disableSelection();
    	 $( "#slider" ).slider();
	});	
</script>
<style>.button.clear-btn,.button.down-btn,.button.up-btn{padding-top:4px;}</style>
<style>
.slideframe{position:relative;float:left;width:80%;height:100%;background-color: white;padding:10px;margin-bottom:40px;}
.createNewFrame{position:relative;float:left:width:100%;height:100%;margin-top:30px;}
#slider{position:relative;float:left;width:300px;height:15px;background-color:white;margin-top:7px;line-height:7px;margin-right:10px;-webkit-border-top-left-radius:23px;
	-moz-border-radius-topleft:23px;border-top-left-radius:23px;-webkit-border-top-right-radius:23px;-moz-border-radius-topright:23px;border-top-right-radius:23px;
	-webkit-border-bottom-right-radius:23px;-moz-border-radius-bottomright:23px;border-bottom-right-radius:23px;-webkit-border-bottom-left-radius:23px;-moz-border-radius-bottomleft:23px;
	border-bottom-left-radius:23px;border:1px solid #CCCCCC;
	
}
.square{width:20px;height:20px;}
.slider_continer{width:80%;height:30px;position:float:left;margin-top:30px;
	
}
.font_green{color:green;}
.slider_text{height:15px;width:150px;position:relative;float:left;line-height: 15px;margin-top:7px;font-weight: bold;}
.frameLeft{position:relative;float:left;width:250px;height:100%;margin-right:25px;}
.frameRight{position:relative;float:left;width:66.66666666666667%;height:100%;}

.greyButton, .leftFlatButton{
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;box-shadow:inset 0px 1px 0px 0px #ffffff;background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9));
	background:-moz-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);background:-webkit-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);background:-o-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);
	background:-ms-linear-gradient(top, #f9f9f9 5%, #e9e9e9 100%);background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f9f9', endColorstr='#e9e9e9',GradientType=0);background-color:#f9f9f9;-moz-border-radius:6px;
	-webkit-border-radius:6px;border-radius:0px 6px 6px 0px;border:1px solid #dcdcdc;display:inline-block;cursor:pointer;color:#666666;font-family:arial;
	font-size:15px;padding:6px 24px;text-decoration:none;text-shadow:0px 1px 0px #ffffff;
}
.greyButton {border-radius:6px;margin-top:10px;}
.greyButton:hover, .leftFlatButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9));background:-moz-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);background:-webkit-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);
	background:-o-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);background:-ms-linear-gradient(top, #e9e9e9 5%, #f9f9f9 100%);background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e9e9e9', endColorstr='#f9f9f9',GradientType=0);background-color:#e9e9e9;
}
.greyButton:active, .leftFlatButton:active {position:relative;top:1px;}
.moveRight, .deleteThisFrame {position:relative;float:right;}
.leftFlatButton{width:95px;}
input.inputText{height:31px;width:100%;}
input.inputText2{height:31px;width:100%;}
.slideRows1, .slideRows2{position:relative;	height:50px;}
.slideRows1{margin-top:25px;}
.slideRows2{margin-top:10px;}	
.slideRows3{position:relative;height:40px;}
.caption, .urldiv{position:relative;float:left;width:50px;height:20px;padding-top:15px;margin-right:20px;}
.capInput{position:relative;float:left;width:86%;height:100%;}
.sortable{width:80%;height:100%;}
.slider_continer{width:81.5%;}
.newframeButton{margin-top:5px;}
.input2div{position:relative;float:left;width:61%;height:31px;}
.selectImg{position:relative;float:left;width:145px;height:31px;}
</style>
<div class="wrap">
<h1><a href="http://www.bookt.com" target="_blank"><img src="<?= plugins_url('/img/logo-im.png', __FILE__) ?>" /></a></h1>
<h2>InstaSite Plugin - Slideshow Setup</h2>
<p ><span class="font">Here you can add, edit and remove items from the slide show that appears on you home page. Drag each item into the order you prefer.
<br /><br> Pleae remember to have identical dimensions for each photo so as not to distort the slideshow. <b><i>Ideal size:</i></b><span class="font_green"><b> 1100 x 400 px.</b></span>
</h3></p>
<div class="createNewFrame">

<button type="submit" name="createNewFrame" id="createNewFrame" class="button button-primary">Create New Frame</button>
&nbsp;&nbsp;<span style="line-height:10px;">*There is a limit of 10 images, howeveer we are recomend using 3 to 5 images in the slide show.</span>
</div>
<?php  get_option('bapi_slideshow_image1');?> 
<form method="post">
	<div class="slider_continer">
 	<div class="slider_text">Slide Show speed:</div>
 	<div id="slider">
 		 <a style="line-height:15px;"href="#" style="left: 21%;">test</a> 	
 	</div>
 	<div class="slider_text">5 seconds</div>
 	<div style="position:relative;float:right;width:104px;height:30px;">
 		<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
 	</div>
	</div>
	<ul id="sortable">
		  
  	
  
	<?php
	for ($i=0; $i <=12 ; $i++) { 
		$getOpt= get_option('bapi_slideshow_image'.$i);
		if($getOpt){
			?>
			<li class="slideframe">
  	
  		<div class="frameLeft">
  		<a href="#" data-for="bapi_slideshow_image<?php echo $i;?>">
  			<img alt="Clear" title="Clear Field" src="<?php echo get_option('bapi_slideshow_image'.$i); ?>" width="250" height="150" />
  		</a>
  			
  		<div  id="image-pick<?php echo $i;?>" name="image-pick<?php echo $i;?>" class="greyButton">Add or Edit Image</div>
  			
  		</div>
  		<div class="frameRight">
  			<div class="slideRows1">
  				<div>Caption:</div><input  type="text" class="inputText" id='bapi_slideshow_caption<?php echo $i;?>' name="bapi_slideshow_caption<?php echo $i;?>" />
  			</div>
  			<div class="slideRows2">
  				URL:<input class="inputText2" type="text" id='bapi_slideshow_image<?php echo $i;?>' name="bapi_slideshow_image<?php echo $i;?>"/>
  				<div class="leftFlatButton" style="margin-left:-5px;">Select Image</div>
  			</div>
  			<div class="slideRows3">
  				<div class="greyButton deleteThisFrame" >Delete This Frame</div>
  			</div>
  		</div>
  	
  	</li>
			<?php
		}
	}

	?>	
  

 
</ul>
<div style="position:relative;clear:left;width:100%;height:40px;margin-top:30px;">
<div type="submit" name="createNewFrame" id="createNewFrame" class="button button-primary" value="Create New Frame">Create New Frame</div>
&nbsp;&nbsp;<span style="line-height:10px;">*There is a limit of 10 images, howeveer we are recomend using 3 to 5 images in the slide show.</span>
</div> 

<?php submit_button();?> 
</form>
<?php
	
?>