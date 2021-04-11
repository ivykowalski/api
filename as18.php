<DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="hutf-8" />
    </head>
    
    <body>
        <div class = "container">
        <?php
        echo "<div class='page-header'>
                <h1>CIS 355 as18 Covid-19 api</h1>
                <a href='https://github.com/ivykowalski/api'>Github code</a>
            </div>";
        ?>
                         
<?php

// Calls main to display the json object and table
main();

function main () {
    // Covid19api.com deaths data
	$apiCall = 'https://api.covid19api.com/summary';
    // Reads the data puts it into a JSON string
	$json_string = curl_get_contents($apiCall);
    // Stores the JSON string into an object
	$obj = json_decode($json_string);
	//array to hold countries
    $arr1 = Array();
	//array to hold deaths 
    $arr2 = Array();
	
	foreach($obj->Countries as $i)
	{
		array_push($arr1, $i->Country );
		array_push($arr2, $i->TotalDeaths );
	}
	//sort the arrays in descending order (highest to lowest deaths)
	array_multisort($arr2, SORT_DESC, $arr1);
    
    //generate json object with the top 10 countries with highest deaths 
    $arr1 = array_slice($arr1, 0, 10);
    $arr1 = json_encode($arr1);
    $arr1 = json_decode($arr1);
    echo "<h5><b>JSON object</b></h5>";
    //print it to the page
    print_r($arr1);
    
    // Create the table to hold top ten 
	echo "<div><h3><b>10 Countries with highest covid-19 deaths</b></h3>";
	echo "<table class='table'>";
        echo "<tr>";
            // Create the two column headers
            echo "<th>Country Name</th>";
            echo "<th>Number of Deaths</th>";
		echo "</tr>";

        // For loop which adds top ten countries and their corresponding deaths to the table 
		for ($i = 0; $i < 10 ; $i++) {
			echo "<tr>";
			echo "<td>{$arr1[$i]}</td>";
			echo "<td>{$arr2[$i]}</td>";
			echo "</tr>";
		 }

	echo "</table>";
	echo '</div>';
}

// read data from a URL into a string
function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}