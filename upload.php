<?php
//importing dbDetails file
require_once 'dbDetails.php';

//this is our upload folder
$upload_path = 'uploads/';

//Getting the server ip
$server_ip = gethostbyname(gethostname());

//creating the upload url
//$upload_url = 'http://'.$server_ip.'/laporin/'.$upload_path;
$upload_url = 'http://192.168.43.7/laporin/'.$upload_path;

//response array
$response = array();


if($_SERVER['REQUEST_METHOD']=='POST'){

  //checking the required parameters from the request
  if(isset($_POST['name_img']) and isset($_FILES['image']['name'])){

    //connecting to the database
    $con = mysqli_connect(HOST,USER,PASS) or die('Unable to Connect...');
    mysqli_select_db($con,DB);

    //getting annotation
    $annotation = $_POST['annotation'];

    //getting coordinate
    $coordinate = $_POST['coordinate'];

    //getting date
    $date = $_POST['date'];

    //getting name from the request
    $name = "Laporin ". getFileName(). " || ". $date;

    //getting file info from the request
    $fileinfo = pathinfo($_FILES['image']['name']);

    //getting the file extension
    $extension = $fileinfo['extension'];

    //file url to store in the database
    $file_url = $upload_url . getFileName() . '.' . $extension;

    //file path to upload in the server
    $file_path = $upload_path . getFileName() . '.'. $extension;

    //trying to save the file in the directory
    try{
      //saving the file
      move_uploaded_file($_FILES['image']['tmp_name'],$file_path);
      //$sql = "INSERT INTO 'db_laporin'.'data_laporan' ('id', 'url', 'name_img', 'annotation', 'coordinate') VALUES (NULL, '$file_url', '$name', '$annotation', '$coordinate');";
      $sql = "INSERT INTO data_laporan (url, name_img, annotation, coordinate, date) VALUES ('".$file_url."', '".$name."', '".$annotation."', '".$coordinate."', '".$date."');";
      //adding the path and name to database
      if(mysqli_query($con,$sql)){

        //filling response array with values
        $response['error'] = false;
        $response['url'] = $file_url;
        $response['name_img'] = $name;
        $response['annotation'] = $annotation;
        $response['coordinate'] = $coordinate;
        $response['date'] = $date;
      }
      //if some error occurred
    }catch(Exception $e){
      $response['error']=true;
      $response['message']=$e->getMessage();
    }
    //displaying the response
    echo json_encode($response);

    //closing the connection
    mysqli_close($con);
  }else{
    $response['error']=true;
    $response['message']='Please choose a file';
  }
}

/*
We are generating the file name
so this method will return a file name for the image to be upload
*/
function getFileName(){
  $con = mysqli_connect(HOST,USER,PASS) or die('Unable to Connect...');
  mysqli_select_db($con,DB);

  $sql = "SELECT max(id) as id FROM data_laporan";
  $result = mysqli_fetch_array(mysqli_query($con,$sql));

  mysqli_close($con);
  if($result['id']==null)
  return 1;
  else
  return ++$result['id'];
}
