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
        color:#EFB152;
        font-size:22px;
    }

    #queryP{
        font-family: Trebuchet MS  , Helvetica, sans-serif; 
        color:#F65A5D;
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
        
        background-color: #EFB152;
        color: white;
    }

    #queryTable th {
        padding-top: 12px;
        padding-bottom: 12px;
        
        background-color: #F65A5D;
        color: white;
    }

    .radio{
        font-family: Trebuchet MS  , Helvetica, sans-serif; 
        color:#93C3E7;
        font-size:22px;
    }


    </style>
    </head>

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
echo "<p id='mySQLP'>Original Table: </p>";

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

if(filter_has_var(INPUT_POST, 'queryR')){
    if($_POST['queryR']=="landed"){
        $theQuery = mysqli_query($link,'SELECT * FROM flights WHERE statusV = " Landed"');
        if(!$theQuery) die("dead");
    }
    else if($_POST['queryR']=="city"){
        $theQuery = mysqli_query($link,"SELECT a.airline, a.flightNo, a.flightDate, a.scheduleTime, a.estimateTime, a.actualTime, a.fromV, a.via, a.terminal, a.hall, a.statusV
                FROM flights a WHERE EXISTS (
                SELECT b.airline FROM flights b
                WHERE a.fromV = b.fromV
                AND a.flightNo != b.flightNo)
                ");
        if(!$theQuery) die("dead");
    }
    else{
        $requestedTime =$_POST['timeR'];
        $commandQ="SELECT * FROM FLIGHTS WHERE CAST(scheduleTime as TIME) > CAST('".$requestedTime.":00' as TIME)";
        $theQuery = mysqli_query($link,$commandQ);
        if(!$theQuery) die("dead $requestedTime $commandQ");
    }

    echo "<p id='queryP'>Query Result: </p>";

    echo "<table id='queryTable'>
    <tr> <th>Airline</th> <th>Flight No.</th> <th>Flight Date</th> <th>Schedule Time</th> <th>Estimate Time</th> <th>Actual Time</th> <th>From</th> <th>Via</th> <th>Terminal</th> <th>Hall</th> <th>Status</th> ";

    while($row3 = mysqli_fetch_array($theQuery))
    {
    echo "<tr>";
    echo "<td>" . $row3['airline'] . "</td>";
    echo "<td>" . $row3['flightNo'] . "</td>";
    echo "<td>" . $row3['flightDate'] . "</td>";
    echo "<td>" . $row3['scheduleTime'] . "</td>";
    echo "<td>" . $row3['estimateTime'] . "</td>";
    echo "<td>" . $row3['actualTime'] . "</td>";
    echo "<td>" . $row3['fromV'] . "</td>";
    echo "<td>" . $row3['via'] . "</td>";
    echo "<td>" . $row3['terminal'] . "</td>";
    echo "<td>" . $row3['hall'] . "</td>";
    echo "<td>" . $row3['statusV'] . "</td>";

    echo "</tr>";
    }
    echo "</table>";
    echo"<br><br>";
}

mysqli_close($link);

?>