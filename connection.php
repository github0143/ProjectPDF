<?php

	       $connection =mysqli_connect('localhost','root','');
			if (!$connection) {
				die("Database connection failed: " .mysqli_error($connection));
			}
			
			$db_select = mysqli_select_db($connection,'project_db');
			if (!$db_select) 
			{
				die("Database selection failed: " .mysqli_error($db_select));
			}


?>