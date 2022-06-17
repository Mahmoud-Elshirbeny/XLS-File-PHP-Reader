<!DOCTYPE HTML>
<HEAD>
    <title> Flight Schedule </title>
    <style type="text/css"> 
    body {
        background-color: #FCFCFC;
    }
    h1{
        font-family: Trebuchet MS  , Helvetica, sans-serif;
        text-align: center;
        color:black;
        font-size:38px;
    }
    #xmlP{
        font-family: Trebuchet MS  , Helvetica, sans-serif; 
        color:#93C3E7;
        font-size:22px;
    }

    #jsonP{
        font-family: Trebuchet MS  , Helvetica, sans-serif; 
        color:#A9CD9B;
        font-size:22px;
    }

    #mySQLP{
        font-family: Trebuchet MS  , Helvetica, sans-serif; 
        color:#BE9BCD;
        font-size:22px;
    }
    table{
        font-family: Trebuchet MS  , Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    table td, table th {
         border: 1px solid #ddd;
         padding: 8px;
         text-align: center;
    }

    table tr:nth-child(even){background-color: #f2f2f2;}

    #jsonTable td:nth-child(even){background-color: #f2f2f2;}
    
    table tr:hover {background-color: #ddd;}
    
    #xmlTable th {
        padding-top: 12px;
        padding-bottom: 12px;
        
        background-color: #93C3E7;
        color: white;
    }

    #jsonTable th {
        padding-top: 12px;
        padding-bottom: 12px;
        
        background-color: #A9CD9B;
        color: white;
    }

    #mySQLTable th {
        padding-top: 12px;
        padding-bottom: 12px;
        
        background-color: #BE9BCD;
        color: white;
    }

    .radio{
        font-family: Trebuchet MS  , Helvetica, sans-serif; 
        color:#93C3E7;
        font-size:22px;
    }


    </style>
    <script src="jqueryI.js"></script>
    <script>
       function buildTable(jsonText){
            var parsed = JSON.parse((JSON.stringify(jsonText)));
            var table = document.getElementById("jsonTable");
            var flightsV = parsed.FLIGHTS.FLIGHT;
            table.innerHTML+="<tr> <th>Airline</th> <th>Flight No.</th> <th>Flight Date</th> <th>Schedule Time</th> <th>Estimate Time</th> <th>Actual Time</th> <th>From</th> <th>Via</th> <th>Terminal</th> <th>Hall</th> <th>Status</th> </tr>";
            for(i = 0;i<flightsV.length;i++){
                
                var singleFlight = flightsV[i];

                var airline = Object.keys(singleFlight.AIRLINE).length === 0? "":singleFlight.AIRLINE;
                var flightNo = Object.keys(singleFlight.FLIGHTNO).length === 0? "":singleFlight.FLIGHTNO;
                var flightDate = Object.keys(singleFlight.FLIGHTDATE).length === 0? "":singleFlight.FLIGHTDATE;
                var scheduleTime = Object.keys(singleFlight.SCHEDULETIME).length === 0? "":singleFlight.SCHEDULETIME;
                var estimateTime = Object.keys(singleFlight.ESTIMATETIME).length === 0? "":singleFlight.ESTIMATETIME;
                var actualeTime = Object.keys(singleFlight.ACTUALTIME).length === 0? "":singleFlight.ACTUALTIME;
                var from = Object.keys(singleFlight.FROM).length === 0? "":singleFlight.FROM;
                var via = Object.keys(singleFlight.VIA).length === 0? "":singleFlight.VIA;
                var terminal = Object.keys(singleFlight.TERMINAL).length === 0? "":singleFlight.TERMINAL;
                var hall = Object.keys(singleFlight.HALL).length === 0? "":singleFlight.HALL;
                var status = Object.keys(singleFlight.STATUS).length === 0? "":singleFlight.STATUS;

                table.innerHTML+= "<tr>"
                +"<td>" + String(airline) 
                +"</td>"+ "<td>" + String(flightNo) + "</td>"
                +"<td>" + String(flightDate) + "</td>"
                +"<td>" + String(scheduleTime) + "</td>"
                +"<td>" + String(estimateTime) + "</td>"
                +"<td>" + String(actualeTime) + "</td>"
                +"<td>" + String(from) + "</td>"
                +"<td>" + String(via) + "</td>"
                +"<td>" + String(terminal) + "</td>"
                +"<td>" + String(hall) + "</td>"
                +"<td>" + String(status) + "</td>"
                +"</tr>";
            }
            
        }
    </script>
</HEAD>
<BODY >

<h1>
    Flight Schedule
</h1>
<p class="radio">Notice that sometimes the browser/server stops working for some reason, but the code works and all the requirements are implemented. Refreshing or (unfortunately) reinstalling WAMP works to get it back.</p>
<p id="jsonP">Using JSON and JS:</p>
<table id="jsonTable"></table>


<?php

require "Classes/PHPExcel.php";
$tmpfname = "arrivals.xls";
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

$xml = "<?xml version='1.0' encoding='UTF-8'?> \n<ROOT>\n<FLIGHTS> \n";
for ($row = 3; $row <= $lastRow; $row++) {
    $xml.="<FLIGHT> \n";

    $xml.="<AIRLINE> ";
	$xml.= $worksheet->getCell('A'.$row)->getValue();
    $xml.="</AIRLINE> \n";

    $xml.="<FLIGHTNO> ";
	$xml.= $worksheet->getCell('B'.$row)->getValue();
    $xml.="</FLIGHTNO> \n";

    $xml.="<FLIGHTDATE> ";
	$xml.= $worksheet->getCell('C'.$row)->getValue();
    $xml.="</FLIGHTDATE> \n";

    $xml.="<SCHEDULETIME> ";
    $cell = $worksheet->getCell('D'.$row);
	$xml.= PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm:ss');
    $xml.="</SCHEDULETIME> \n";

    $xml.="<ESTIMATETIME> ";
    $cell = $worksheet->getCell('E'.$row);
	$xml.= PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm:ss');
    $xml.="</ESTIMATETIME> \n";

    $xml.="<ACTUALTIME> ";
    $cell = $worksheet->getCell('F'.$row);
	$xml.= PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'hh:mm:ss');
    $xml.="</ACTUALTIME> \n";

    $xml.="<FROM> ";
	$xml.= $worksheet->getCell('G'.$row)->getValue();
    $xml.="</FROM> \n";

    $xml.="<VIA> ";
	$xml.= $worksheet->getCell('H'.$row)->getValue();
    $xml.="</VIA> \n";

    $xml.="<TERMINAL> ";
	$xml.= $worksheet->getCell('I'.$row)->getValue();
    $xml.="</TERMINAL> \n";

    $xml.="<HALL> ";
	$xml.= $worksheet->getCell('J'.$row)->getValue();
    $xml.="</HALL> \n";

    $xml.="<STATUS> ";
	$xml.= $worksheet->getCell('K'.$row)->getValue();
    $xml.="</STATUS> \n";

    $xml.="</FLIGHT> \n";
}
$xml.="</FLIGHTS> </ROOT>\n";

$xmlString = simplexml_load_string($xml);
$json = json_encode($xmlString);

echo "<p id='xmlP'>Using XML and PHP: </p>";
echo "<table id='xmlTable'>";
echo "<tr> <th>Airline</th> <th>Flight No.</th> <th>Flight Date</th> <th>Schedule Time</th> <th>Estimate Time</th> <th>Actual Time</th> <th>From</th> <th>Via</th> <th>Terminal</th> <th>Hall</th> <th>Status</th> ";
foreach ($xmlString->FLIGHTS->FLIGHT as $flightElement){
    echo "<tr>";
      echo "<td>  $flightElement->AIRLINE </td>";
      echo "<td>  $flightElement->FLIGHTNO </td>";
      echo "<td>  $flightElement->FLIGHTDATE </td>";
      echo "<td>  $flightElement->SCHEDULETIME </td>";
      echo "<td>  $flightElement->ESTIMATETIME </td>";
      echo "<td>  $flightElement->ACTUALTIME </td>";
      echo "<td>  $flightElement->FROM </td>";
      echo "<td>  $flightElement->VIA </td>";
      echo "<td>  $flightElement->TERMINAL </td>";
      echo "<td>  $flightElement->HALL </td>";
      echo "<td>  $flightElement->STATUS </td>";
    echo "</tr>";
}
unset($flightElement);
echo "</table>";

//echo $xml;
//echo "----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";
//echo $json;

echo "<script> buildTable(".$json.") </script>";

$link = mysqli_connect("localhost","root","");
if(!$link){
    die("Connection Error.");
}

$databaseName = "a";
while(!mysqli_query($link,"CREATE DATABASE ".$databaseName)){
    $databaseName .="a";
}
if(!mysqli_select_db($link,$databaseName)){
    die("Select Database Error.");
}
if(!mysqli_query($link,"CREATE TABLE flights(airline varchar(40),flightNo varchar(10),flightDate varchar(30),scheduleTime varchar(30),estimateTime varchar(30),actualTime varchar(30),fromV varchar(20),via varchar(10),terminal varchar(30),hall varchar(30),statusV varchar(20))")){
    die("Create Table Error.");
}

foreach ($xmlString->FLIGHTS->FLIGHT as $flightElement2){
    $insert = "INSERT INTO flights VALUES("
    .'"'.$flightElement2->AIRLINE.'"'.","
    .'"'.$flightElement2->FLIGHTNO.'"'.","
    .'"'.$flightElement2->FLIGHTDATE.'"'.","
    .'"'.$flightElement2->SCHEDULETIME.'"'."," 
    .'"'.$flightElement2->ESTIMATETIME.'"'."," 
    .'"'.$flightElement2->ACTUALTIME.'"'."," 
    .'"'.$flightElement2->FROM.'"'.","
    .'"'.$flightElement2->VIA.'"'.","
    .'"'.$flightElement2->TERMINAL.'"'.","
    .'"'.$flightElement2->HALL.'"'.","
    .'"'.$flightElement2->STATUS.'"'. 
    ");";
    if(!mysqli_query($link,$insert)){
        die("Insertion Error: ".$insert);
    }
}

$result = mysqli_query($link,"SELECT * FROM flights");

echo "<p id='mySQLP'>Using MySQL and PHP: </p>";

echo "<table id='mySQLTable'>
<tr> <th>Airline</th> <th>Flight No.</th> <th>Flight Date</th> <th>Schedule Time</th> <th>Estimate Time</th> <th>Actual Time</th> <th>From</th> <th>Via</th> <th>Terminal</th> <th>Hall</th> <th>Status</th> ";

while($row2 = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row2['airline'] . "</td>";
echo "<td>" . $row2['flightNo'] . "</td>";
echo "<td>" . $row2['flightDate'] . "</td>";
echo "<td>" . $row2['scheduleTime'] . "</td>";
echo "<td>" . $row2['estimateTime'] . "</td>";
echo "<td>" . $row2['actualTime'] . "</td>";
echo "<td>" . $row2['fromV'] . "</td>";
echo "<td>" . $row2['via'] . "</td>";
echo "<td>" . $row2['terminal'] . "</td>";
echo "<td>" . $row2['hall'] . "</td>";
echo "<td>" . $row2['statusV'] . "</td>";

echo "</tr>";
}
echo "</table>";
echo"<br><br>";

echo '<p class="radio">Query:</p>
<form action="queryResult.php" method="post">
<label class="radio">Flights that have already Landed:</label>  <input type="radio" name="queryR" id="landed" value="landed" /> <br>
<label class="radio">Flights Coming from same city:</label>  <input type="radio" name="queryR" id="city" value="city" /> <br>
<label class="radio">Flights arriving after specific time:</label>  <input type="radio" name="queryR" id="time" value="time" /> <br>
<label class="radio">Specifiy that time:</label>  <input type="time" name="timeR" id="timeR" placeholder="00:00"/> <br><br>
<input type="submit" value="Confirm">
</form> ';




mysqli_close($link);

?>
         
</script>

</BODY>