<?php
        //File CSV upload
		// PHP End Branch --- For PHPEnd branch
		// PHP updated
		$file_handle 		= fopen("locations.csv", "r");
		$counter 			= 0;

	while (!feof($file_handle) )
    {
		$line_of_text 		= fgetcsv($file_handle, 1024);

		//print $line_of_text[0] . $line_of_text[1]. $line_of_text[2] . "<BR>";
		$department 		= $line_of_text[0];
		$name 				= $line_of_text[1];
		$address 			= $line_of_text[2];
		$city 				= $line_of_text[3];
		$zip 				= $line_of_text[4];
		$state_province_id 	= $line_of_text[5];
		$country_id 		= $line_of_text[6];
		$phone 				= $line_of_text[7];
		$website 			= $line_of_text[8];
		$last_updated 		= $line_of_text[9];
		$state 				= $this->core->db->get_var('SELECT abbrev FROM pl_dealer_states_provinces WHERE id="'. $state_province_id . '"');
		$address 			= stripslashes($address . ' ' . $city . ',' . $zip. ' ' . $state);
		$url 				= str_replace(" ", "%20", "http://maps.googleapis.com/maps/api/geocode/json?address=$address&sensor=true");
		sleep(1);
		$results 			= file_get_contents($url);
		$results 			= json_decode($results,1);
		$lng 				= '0';
		$lat 				= '0';

		if ($results)
		{
			foreach ($results['results'] as $r)
			{
				if (isset($r['geometry']['location_type']) && $r['geometry']['location_type'] == 'GEOMETRIC_CENTER')
				{
					$lng = $r['geometry']['location']['lng'];
					$lat = $r['geometry']['location']['lat'];
					break;
				}
			}

			if ($lng == '0' && $lat == '0')
			{
				$lng = $results['results'][0]['geometry']['location']['lng'];
				$lat = $results['results'][0]['geometry']['location']['lat'];
			}
		}

		echo "<pre>";
		print_r($line_of_text);
		echo "</pre><br> ".$counter." <br><br>";
		$counter++;
	}
	fclose($file_handle);

?>