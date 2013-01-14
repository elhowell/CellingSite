<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset="us-ascii" />
<title><?php echo $pagetitle; ?></title>



<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/superfish.css" media="screen" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/hoverIntent.js"></script>
<script type="text/javascript" src="js/superfish.js"></script>
<script type="text/javascript">
	// initialise plugins
  $(document).ready(function(){
    $('.blueboxbottom a').click(function(){
			$('#bluebox1').fadeOut("slow");
		});
  });
</script>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    $("a[rel^='prettyPhoto']").prettyPhoto({
		horiztontal_padding: 0,
		theme: 'dark_rounded',
		default_height: 200
	});
  });
</script>
<script type="text/javascript">
	// initialise plugins
  $(document).ready(function(){
    $('ul.sf-menu').superfish({
			pathClass: current
		});
	});
</script>

		