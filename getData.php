<?php

 //Importing dbdetails file
 require_once 'dbDetails.php';

 //connection to database
 $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');

 //sql query to fetch all images
 $sql = "SELECT * FROM data_laporan";

 //getting images
 $result = mysqli_query($con,$sql);

 //response array
 $response = array();
 //$response['error'] = false;
 //$response['laporan'] = array();

 //traversing through all the rows
 while($row = mysqli_fetch_array($result)){
 $temp = array();
 $temp['id']=$row['id'];
 $temp['url']=$row['url'];
 $temp['name_img']=$row['name_img'];
 $temp['annotation']=$row['annotation'];
 $temp['coordinate']=$row['coordinate'];
 $temp['date']=$row['date'];
 array_push($response ,$temp);
 }
 //displaying the response
 echo json_encode($response);
