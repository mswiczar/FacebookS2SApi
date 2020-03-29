<?php
	//ini_set('display_errors',1);
	//error_reporting(E_ALL);
	date_default_timezone_set('US/Eastern');
	
/*
curl -X POST \
  -F 'data=[
       {
         "event_name": "Purchase",
         "event_time": 1581946643,
         "user_data": {
           "external_id": "202002170928474119"
         },
         "custom_data": {
            "value": 10,
            "currency": "USD",
            "source": "server"
         }
       }
     ]' \
  -F 'access_token=%token%' \
  https://graph.facebook.com/v6.0/%pixelid%/events
*/	


	
	
	

function  impactRowToFB($elarray)
{
		echo "<br> START ROW<br><br>";
		print_r($elarray);
		$accessToken = "%TOKEN%";
		$pixelID = "%PIXELID%";

		$eventTime = $elarray[0];
			
		$eldate=date_create($elarray[0],timezone_open("US/Eastern"));
		$eventTime =  date_format($eldate,"U");			
		$parseado = date_format($eldate,"Y/m/d H:i:s");
			
			
			
			echo "<br><br>";
			echo "<table border=1>";
			echo "<tr>";
			echo "<td>event_name</td><td>Purchase</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>event_time</td><td>".$elarray[0]."</td><td>".$eventTime."</td><td>".$parseado."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>ExternalID</td><td>".$elarray[1]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>currency</td><td>".$elarray[2]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>value</td><td>".$elarray[3]."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td>source</td><td>server</td>";
			echo "</tr>";
			echo "</table>";
			
			$url="https://graph.facebook.com/v6.0/".$pixelID."/events";
			$fields = array(
				'access_token' => urlencode($accessToken),
				'data' => urlencode('[{"event_name": "Purchase","event_time": '.$eventTime.',"user_data": {"external_id": "'.$elarray[1].'"},"custom_data": {"value": '.$elarray[3].',"currency": "'.$elarray[2].'","source": "server"}}]')
			);

			//url-ify the data for the POST
			echo "<br><br>";
			print_r($fields);
			echo "<br><br>";
			
			
			$fields_string="";
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');

			echo "<br>Encoded POST to FB: <br>".$fields_string."<br><br>";

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		//execute post
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);


		echo $result . "<br><br><br><br>";
 
		$car = json_decode($result, true);	
		echo "<br><br>";
		print_r($car);


		echo "<br>END ROW<br>";
		echo "<br>----------------<br>";
		echo "<br>----------------<br>";
		
	
	}



	function readPerRow($theFileName)
	{
	
		$file = fopen($theFileName, "r");
        if ($file)
        {
            
        }
        else
        {
            print ("Unable to open file: ". $theFile ."\n");
            return false;
            
        }
        $row_number=0;
        while(!feof($file))
        {
            $lafila = fgets($file);
            $elarray = str_getcsv($lafila, ",", '"');
            if ($row_number==0)
            {
				//header
				echo "Header File<br>";
            }
            else
            {
				//rows
				impactRowToFB( $elarray);

            }
            $row_number++;
        }
        fclose($file);
        return true;
	}




   if(isset($_FILES['csv']))
   {
      $errors= array();
      $file_name = $_FILES['csv']['name'];
      $file_size =$_FILES['csv']['size'];
      $file_tmp =$_FILES['csv']['tmp_name'];
      $file_type=$_FILES['csv']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['csv']['name'])));
      $extensions= array("csv","txt");
      if(in_array($file_ext,$extensions)=== false)
      {
         $errors[]="extension not allowed, please choose a csv or txt file.";
      }
      if($file_size > 2097152)
      {
         $errors[]='File size must be lower 2 MB';
      }
      
      if(empty($errors)==true)
      {
		  readPerRow($file_tmp);
		  echo "Successfully uploaded<br>"; 
      }
      else
      {
         print_r($errors);
      }
   }
?>
<html>
   <body>
      <h1> Upload csv 2 send  facebook S2S conversions</h1>
      <br>
      <p>File must contains, External_id, Date, Currency, Amount </p>
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="csv" />
         <input type="submit"/>
      </form>
   </body>
</html>






