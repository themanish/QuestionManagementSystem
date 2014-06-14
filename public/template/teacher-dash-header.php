<!DOCTYPE html>
<head>
	<title>Teacher's Profile</title>
	<link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/table.css" />
    <script type="text/javascript" src="../js/jquery-2.0.2.min.js"></script>
    <script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../js/teacher.js"></script>
	<script type="text/javascript">
	   $(document).ready(function(){
		    //open popup
	        $("#pop").click(function(){
	            $("#modal").fadeIn(500);
	            $("body").addClass("dialogIsOpen");
	            positionPopup();
	        });
	    
	        //close popup
	        $("#close").click(function(){
	            $("#modal").fadeOut(500);
	            $("body").removeClass("dialogIsOpen");
	        });
	    });
	</script>
</head>

<body>
<div class="container" id="dashboard">
	<div class="row" id="dash-header">
        <div class="span4">
			<div id="dash-logo-box">
				<div id="dash-logo">
					<a href="index.php"><img src="../img/dash-qms-logo.png"></a>
				</div>
			</div>
		</div>
		<div class="span4 offset4 right">
			<div id="user-dash-logged-in" class="right">
				<div id="user-dash-info" class="right">
					<span id="user-name"><?php echo $session->name; ?></span>
                    <span id="edit"><a href="#" id="pop">[ Change Password ]</a></span>
					<span><a href="../index.php?logout=1" class="btn">Logout</a></span>
				</div>
			</div>
		</div>
	</div>