<?php 
//This header will be used for all pages.  Set the page title here
$pagetitle = 'Regenerative Health';
include('includes/pagetop.php'); 
?>
<link rel="stylesheet" href="css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script src="js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>

</head>
<body>

<div id="pagewrap">
	<div id="header">
		<?php include('includes/header.php'); ?>	
	</div><!-- end #header -->
	<div style="clear: both;"></div>
	
	<div id="maincontent">
		<div id="maininterior-black2">	
         
          <div id="leftwrap">
				<div class="left-leftwrap">
					<h2>Test Header</h2>
				</div>
				<div class="right-leftwrap">
					<h3>Test Header</h3>
					<p>Text</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>					
				</div>
				
		  </div><!-- end #leftwrap -->
		  
		 
		  <div id="rightwrap">
				<div class="left-rightwrap">
					<p>&nbsp;</p>
					<p>&nbsp;</p>
				</div>
				<div class="right-rightwrap">
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					
				</div>
		  </div><!-- end #rightwrap -->
		<div style="clear: both;"></div>	
          
		</div><!-- end #maininterior -->
		    
	</div><!-- end #maincontent -->
	<div style="clear:both;"></div>
	<div id="footer">
		<?php include('includes/footer2.php'); ?>
	</div><!-- end #footer -->
	<div style="clear: both;"></div>
</div><!-- end #pagewrap -->
<?php include('includes/pagebottom.php'); ?>