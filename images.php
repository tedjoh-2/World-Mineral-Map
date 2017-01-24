<?php
	ini_set('mysql.connect_timeout',300);
	ini_set('default_socket_timeout',300);
?>
<html>
<head>
    <meta content = "text/html;charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    
    <title> Mineral world map </title>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <link rel = "stylesheet" type = "text/css" href = "css.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    
<script src="http://www.w3schools.com/lib/w3data.js"></script>

<script>
	function display(){
		var request = new XMLHttpRequest();
		request.onreadystatechange = function(){
			if(request.readyState == 4 && request.status == 200){
				document.getElementById("imgGallery").innerHTML = request.responseText;
			}
		}
		request.open("GET","/api/displayImages/", true);
		request.send(null);
	}
</script>
</head>
<body>
<div w3-include-html="overhead.html"></div>

<script>
w3IncludeHTML();
</script>
<div class="container-fluid" id = "form">
	<center>
	<hr id="line1">
	<form id="data" action="" method="post" enctype="multipart/form-data">
	<div class="img-div"></div>
	<div class="img-div" id="image_preview"><img class="dynImg" id="previewing" src="img/no-preview.jpg" /></div>
	<div class="img-div"></div>
	<hr id="line">
	<label>Select Your Image</label><br/>
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />	
	<input type="file" name="file" id="file" required />
	<input type="submit" value="Upload"  class="btn btn-success">
	</form>
	<hr>


	<P> Gallery </P>
	</center>
	<div id="imgGallery">
		<script type="text/javascript">
			display();
		</script>	

	</div>
</div>
</body>



<script>
	
	 $("#data").submit(function(e) {
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: "/api/uploadImage",
                type: "POST",
                data: formData,
                success: function (data) {
                    alert(data)
                    $('#loading').hide();
					$("#message").html(data);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            e.preventDefault();
        });
	 
	$(function() {
	$("#file").change(function() {
	$("#message").empty(); // To remove the previous error message
	var file = this.files[0];
	var imagefile = file.type;
	var match= ["image/jpeg","image/png","image/jpg"];
	if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2])))
		{
		$('#previewing').attr('src','img/no-preview.jpg');
		$("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
		return false;
	}
	else{
		var reader = new FileReader();
		reader.onload = imageIsLoaded;
		reader.readAsDataURL(this.files[0]);
		}
		});
		});
		function imageIsLoaded(e) {
		$("#file").css("color","green");
		$('#image_preview').css("display", "block");
		$('#previewing').attr('src', e.target.result);
		$('#previewing').attr('width', '250px');
		$('#previewing').attr('height', '230px');
	};
	
</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>


</html>

