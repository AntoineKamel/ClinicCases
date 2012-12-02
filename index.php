<?php
include 'lib/php/mobile_detect.php';
include 'db.php';
include 'lib/php/load.php';
include 'html/templates/Header.php';
include 'lib/php/html/tabs.php';
include 'lib/php/data/cases_columns_array.php';



//Check to see which template is needed

	if (isset($_GET['i']))
		{
			//load session checker to ensure user is authorized to see page.
			include 'lib/php/auth/session_check.php';

			$pg = load($_GET['i']);

		}

		else

		{
			$pg = load('Login.php');
		}



//Include the template

	if ($pg === false)
		{echo "Invalid File Request";}
		else
		{include($pg);}


//Check to see if the user has been logged out for inactivity and notify them

	if (isset($_GET['force_close']))
		{
		echo <<<FC
		<script type = 'text/javascript'>
		$('#idletimeout').css('display','block');
	</script>

FC;
		}




//include 'html/templates/Footer.php';


