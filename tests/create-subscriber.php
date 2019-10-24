<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://localhost/mailerlite-sandbox/api/subscribers-create.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\n\t\"email\":\"hello@mailerlite.com\",\n\t\"name\":\"Saurabh Kataria\",\n\t\"state\": \"active\",\n\t\"fields\": {\"Age\":\"30\",\"DOB\":\"1989-01-09\"}\n}",
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;