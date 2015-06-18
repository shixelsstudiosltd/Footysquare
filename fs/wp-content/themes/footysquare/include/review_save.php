<?php
	require_once '../../../../wp-load.php';

	sleep(1);
		$rating_vote_counter = get_post_meta( $_REQUEST['id'], "rating_vote_counter", true);
		$rating_value = get_post_meta($_REQUEST['id'], "rating_value", true);
			if ( !isset($_COOKIE["rating_vote_counter".$_REQUEST['id']]) ){
				setcookie("rating_vote_counter".$_REQUEST['id'], 'true', time()+1, '/');
				update_post_meta( $_REQUEST['id'], 'rating_vote_counter', $rating_vote_counter + 1 );
				$rating = $_POST['rate'];
				update_post_meta( $_REQUEST['id'], 'rating_value', $rating_value + $rating );

				$qry_str = number_format( ($rating_value + $rating) / ($rating_vote_counter + 1), 2 ) . ' ( '. ($rating_vote_counter + 1) . " Votes ) ";
		   }
	
	$aResponse['error'] = false;
	$aResponse['message'] = '';
	// ONLY FOR THE DEMO, YOU CAN REMOVE THIS VAR
		$aResponse['server'] = ''; 
	// END ONLY FOR DEMO
	
	if(isset($_POST['action']))
	{
		if(htmlentities($_POST['action'], ENT_QUOTES, 'UTF-8') == 'rating')
		{
			/*
			* vars
			*/
			$id = intval($_POST['idBox']);
			$rate = floatval($_POST['rate']);
			
			// YOUR MYSQL REQUEST HERE or other thing :)
			/*
			*
			*/
			// if request successful
			$success = true;
			// else $success = false;
			// json datas send to the js file
			if($success)
			{
				$aResponse['message'] = 'Thanks for your rate';
				
				// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
					$aResponse['server'] = $qry_str . '<br />Thanks for your rate';
					//$aResponse['server'] .= '<strong>Rate received :</strong> '.$rate.'<br />';
					//$aResponse['server'] .= '<strong>ID to update :</strong> '.$id;
				// END ONLY FOR DEMO
				echo json_encode($aResponse);
			}
			else
			{
				$aResponse['error'] = true;
				$aResponse['message'] = 'An error occured during the request. Please retry';
				
				// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
					$aResponse['server'] = '<strong>ERROR :</strong> Your error if the request crash !';
				// END ONLY FOR DEMO
				echo json_encode($aResponse);
			}
		}
		else
		{
			$aResponse['error'] = true;
			$aResponse['message'] = '"action" post data not equal to \'rating\'';
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
				$aResponse['server'] = '<strong>ERROR :</strong> "action" post data not equal to \'rating\'';
			// END ONLY FOR DEMO
			echo json_encode($aResponse);
		}
	}
	else
	{
		$aResponse['error'] = true;
		$aResponse['message'] = '$_POST[\'action\'] not found';
		
		// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
			$aResponse['server'] = '<strong>ERROR :</strong> $_POST[\'action\'] not found';
		// END ONLY FOR DEMO
		
		
		echo json_encode($aResponse);
}