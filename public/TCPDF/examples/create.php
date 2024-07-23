<?php



$localIP = getHostByName(getHostName());

// database connection code
// $con = mysqli_connect('localhost', 'database_user', 'database_password','database');

$con = mysqli_connect('localhost', 'root', '','consentform');

//checking tpken

$token = $_POST['token'];

if($token !== 'd373ed50298c11ebadc10242ac120002')
{
  $response = null ;
$response['status']="AUTHENTICATION FAILED";
$response['code']=101;
echo json_encode($response);
  exit;
}



// get the post record
if(isset($_POST)){
//contact form-1
$firstname = $_POST['firstname'];
$lastame = $_POST['lastname'];
$emailaddress = $_POST['emailaddress'];
$ext = $_POST['ext'];
$wnumber = $_POST['wnumber'];
$ext2 = $_POST['ext2'];
$hnumber = $_POST['hnumber'];
$address = $_POST['address'];
$zipcode = $_POST['zipcode'];
$city = $_POST['city'];
$store = $_POST['store'];
$otp = rand(100000, 999999);


//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

// Include the main TCPDF library (search for installation path).
require_once('C:\xampp\htdocs\consent\TCPDF\examples\tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 061');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */
/*
$fname ="Sumit";
$lname ="vaishnav";
$phone = '9876543210';
$email="demo@demo.com";
$addresess = "secret street";
$_SERVER['REMOTE_ADDR'] ="newaddress";
$citieslist="udaipur";
$zipcodes ="876543";
$states= "Rajasthan"; 
*/

// define some HTML content with style
$html2 ='<!doctype html>
        <html lang="en">
        <head>
        <title>Title</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
        body
        {
            font-family: times new roman;
        }
        .imagenew{
            margin-left:25% !important;
        }
        .m-l-50{
            margin-left:100% !important; 
        }
        .f-w-300{
            font-weight:300 !important;
        }
        .f-f-arial{
            font-family:monospace !important;   
            font-size:26px;
        }
        .m-l-80{
            margin-left:80% !important;
        }
 
        body{
            background-color: #ffffff;
        }
 
        </style>
 
        </head>
 
        <body>
 
        <section style="width: 718px;margin: auto;">
        <div>       <center><img src="https://consentform.com/img/logo.png" alt="" style="width:153; height:27;"/></center>
<br/>
<br></br>
<br></br>
        <span class="f-f-arial">Certificate of Authenticity</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div>
        <p style="text-align:justify;">TCPA Disclosure: By clicking to submit you are accepting, I provide my consent to be contacted regarding promotional opportunities to receive autodialed and live calls, emails, standard mail such as USPS and or any other third party carrier, text messages, and/or pre-recorded messages, as well as ringless voicemail each month using automated dialing technology by someone utilizing this authentication tool provided by ConsentForm.com and or its affiliates via any information that you have shared to do so including your mobile or wireless/cellular number, if applicable. This consent is to dissolve any potential violation of the TCPA and or any violation of DNC rules and regulations. As this disclaimer is to provide compliance to state and federal policy along with any corporation Do Not Call List. I agree and consent to submission of my contact information which intention waives the enforcement of the TCPA and DNC rules and regulations of contact. I am not required to purchase any goods or services and recognize that message and data rates may apply. I can revoke consent at any time by sending an email with the subject Opt-Out to Support@Consentform.com and supply full contact information that I wish to have removed writing. You also agree to receive electronic communications and confirm that I have read the Privacy Policy before submitting this form. You also agree to hold harmless from liability ConsentForm.com and or its affiliates from any products or services that you may purchase or enroll with. ConsentForm.com is a customer validation tool to verify your contact information to assist with TCPA and DNC compliance..</p>
        </div>
        <br>
        <div>
        <h4>Verified Information...</h4>
        </div>
        <div class="pt-3">';
 
        $row1 = '<table class="table">
            
                <tr>
                    <td colspan="4"><br>
                    <p>Dear '.$firstname.' '.$lastame.' </p>
                    </td>
                </tr>
            
        <tr><br>
        <td><p>Phone Number:</p></td>
        <td><p>'.$wnumber.'</p></td>
        <td><p>Email Id:</p></td>
        <td><p>'.$emailaddress.'</p></td><br>
        
        </tr><br>';
        $row2='';
          $row2 .='<tr>
          <td colspan="4"><p>ADDRESS DETAILS:- </p></td><br>
          </tr><br>
          <tr>
          <td><p>Address:</p></td>
          <td><p>'.$address.'</p></td>
          <td><p>City:</p></td>
          <td><p>'.$city.'</p></td>
          </tr><br>
          <tr>
          <td><p>Zip Code:</p></td>
          <td><p>'.$zipcode.'</p></td>
          <td><p>State:</p></td>
          <td><p>'.$store.'</p></td>
          </tr>';
      $row3='</table>
      </div>
      <div class="pt-3">
      <p>Your IP ADDRESS:-  '.$localIP.'</p>
      <p>Time stamp:- '.date("d F Y, H:i:s A").'</span></p>
      </div>
      <hr>
    
      
      </section>
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js " 
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo " crossorigin="anonymous "></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js " integrity="sha384-
      UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1 " crossorigin="anonymous "></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js " integrity="sha384-
      JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM " crossorigin="anonymous "></script>
      </body>
      </html>';
 
      $html = $html2.$row1.$row2.$row3;;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
session_start();
$_SESSION["content"] = $html;	

// reset pointer to the last page
$pdf->lastPage();
$ftname=rand(11111,99999).time().".pdf";
$fname=__DIR__."/pf/".$ftname;
$newfname="TCPDF/examples/pf/".$ftname;
// ---------------------------------------------------------
$_SESSION["fname"]	= $ftname;
$pdf->Output($fname,'F');

//$pdf->Output('TCPDF/examples/pf'.$ordernumber.'.pdf', 'F');

//$pdf->Output('example_061.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+

// database connection code
// $con = mysqli_connect('localhost', 'database_user', 'database_password','database');

$con = mysqli_connect('localhost', 'root', '','consentform');

// get the post record

//contact form-1
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$emailaddress = $_POST['emailaddress'];
$ext = $_POST['ext'];
$wnumber = $_POST['wnumber'];
$ext2 = $_POST['ext2'];
$hnumber = $_POST['hnumber'];
$address = $_POST['address'];
$zipcode = $_POST['zipcode'];
$city = $_POST['city'];
$store = $_POST['store'];
$sid = $_POST['sid'];
$source = $_POST['source'];
$destination = $_POST['destination'];
$token = $_POST['token'];
$otp = rand(100000, 999999);

// Set session variables
$_SESSION["save_number"] = $wnumber;
$_SESSION["save_email"] = $emailaddress;	
$_SESSION["firstname"] = $firstname;
$_SESSION["ext"] = $ext;
$_SESSION["otp"] = $otp;	
	
if($token !== 'xyz')
{
  exit;
}

$curl = curl_init();
 
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://ipqualityscore.com/api/json/email/AYB8eFcYXqiYIn8uEvfQeoj38aUDC0HE/$emailaddress",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 069612f0-29a8-16a8-3945-2f62668dabf7"
  ),
));
 
$response = curl_exec($curl);
//print_r(json_decode($response));exit;
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  //echo "cURL Error #:" . $err;
} else {
  //echo $response;
}
/*
$curl = curl_init();
 
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://ipqualityscore.com/api/json/ip/AYB8eFcYXqiYIn8uEvfQeoj38aUDC0HE/2401:4900:463a:9ac1:989a:8c2f:1e81:b278?billing_phone=$wnumber",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 11958763-a265-9176-137d-b323c3cbe3eb"
  ),
));
 
$response = curl_exec($curl);
print_r(json_decode($response));exit;
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
*/

// database insert SQL code
// Start the session
// Set session variables
$_SESSION["save_number"] = $wnumber;
$_SESSION["email"] = $emailaddress;
$_SESSION["name"] = $firstname;	
$sql = "INSERT INTO `homeinfo` (`firstname`, `lastname`, `emailaddress`, `ext`, `wnumber`, `ext2`, `hnumber`, `address`, `zipcode`, `city`, `store`, `otp`, `otp_status`, `sid`, `ip`, `source`, `destination`, `path`, `token`) VALUES ('$firstname', '$lastname', '$emailaddress', '$ext', '$wnumber', '$ext2', '$hnumber', '$address', '$zipcode', '$city', '$store', '$otp', 'verified', '$sid', '$localIP', '$source', '$destination', '$newfname', '$token')";

$rs = mysqli_query($con, $sql);
//message sending-------------------------------------------------------------------------

/*
require_once 'C:\xampp\htdocs\consent\vendor\autoload.php';
 
\Telnyx\Telnyx::setApiKey('KEY0175748972C8344C78F37AAECD90A01B_OkU2tq0iZoKLKqSSBU4iz2');

$your_telnyx_number = '+12702137448';
$destination_number = $ext."".$wnumber;	


$new_message = \Telnyx\Message::Create(['from' => $your_telnyx_number, 'to' => $destination_number, 'text' => 'Hello, Your OTP for ContactForm is: '. $otp]);



*/
// insert in database 

//fetch form id


$sqly = 'SELECT formid FROM homeinfo WHERE wnumber='.$wnumber .'';

$rsy = mysqli_query($con, $sqly);
while ($row = mysqli_fetch_assoc($rsy))
{
   $formid = $row['formid'];
}

$response = null ;

$response['status']="SUCCESS";
$response['code']=100;
$response['formid']=$formid;

// insert in database 

if($rs)
{
  echo json_encode($response);

   // header("location: verificationinfo.php");
}
}
//phpmailer
use PHPMailer\PHPMailer\PHPMailer;
function mailsend(){
	
$mailit = $_SESSION["email"];
$nameit = $_SESSION["name"];
$html = $_SESSION["content"];	
    $localIP = getHostByName(getHostName());    
    require 'C:\xampp\htdocs\consent\vendor\autoload.php';

    $mail = new PHPMailer();
	
    $mail->isSMTP();
    $mail->Host = 'email-smtp.us-east-2.amazonaws.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'AKIATWDPTHIGJV4FEBXT'; //paste one generated by Mailtrap
    $mail->Password = 'BB/J+ZOcOZgbqFG01TS28jNd2dDXOkn/3aOlLj8t+2Ey'; //paste one generated by Mailtrap
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
 	
    $mail->setFrom('noreply@consentform.com', 'ConsentForm');
    $mail->addReplyTo('noreply@consentform.com', 'ConsentForm');
    $mail->addAddress($mailit, $nameit); 

    $mail->Subject = 'Consent Form details from ContactConsent';
    $mail->isHTML(true);
    $mailContent = $html;
    $mail->Body = $mailContent;

	
	
    if($mail->send()){
     // echo 'Message has been sent';
     }else{
     // echo 'Message could not be sent.';
    //  echo 'Mailer Error: ' . $mail->ErrorInfo;
		
    }
}

// dncblocker delte api

$sqlc = 'SELECT wnumber FROM homeinfo WHERE wnumber='.$_SESSION["save_number"] .'';
$rst = mysqli_query($con, $sqlc);
$rsc = mysqli_num_rows($rst);

if($rsc > 1)
{

    //delete api
$curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://dncblocker.com/deletenumber/$wnumber/5778195c3c38e8248e62f20a5d695681",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Cookie: XSRF-TOKEN=eyJpdiI6ImVpODA2OFZWVUVXS1ZObnI3bkFoeFE9PSIsInZhbHVlIjoiYWtDdkZNZ2M5a1lJSDBEU2FuSFpQd2R4QVhra1RkWi8yV3g1dG5HTkZwK2NuUkR4cFJJeE82SWFpRjVubTJndlRiWEQ2RVB5ZE50MHNJOGdpU2pkY1lXNCtIVjgxK2ZIbFhEeFZIOFNtNTBNQUF5L1ZyNWo1Z1JQaEo5a09DeDgiLCJtYWMiOiIzYjY4NTY5ZGZhNWZjMzk5YjkzMjYwOWY3MDFjOGQ5MjljYjcxMDQzODhhYjU2MzBlNjQ2YjU4NTJjZjkwM2FmIn0%3D; laravel_session=eyJpdiI6Ii9PcDFYS1VHRmFSbjVhSDhKeXlnaFE9PSIsInZhbHVlIjoiY2dYYnNZYkQyZlJYdDlUSlJkMUpVSHlJU0w3UWV5VGRpVi95NEVKb2RoRDlQdlR0Q1FMZG8rakRPSGMydlJtR3lucy9oMWhrNTBHbG1EU3M3REhPa3lFRW52TWJxL0VMZFFwOXF4aVpjYzBHaEtkWUJVbmhjamJpTXdjZWhCd1giLCJtYWMiOiIyNzVmM2UwNGUyZjJjZDU4MzBhMDk1OGM0NDM5ODlmNmJlOWU4OWQyZDQyMzg4NTAwMTliMDY4NTJjZWFhMmMxIn0%3D"
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    
//echo $response;
//$sqlt = 'UPDATE optout SET response= '.$response.' WHERE phone_no='.$_SESSION["save_number"] .'';
//$rst = mysqli_query($con, $sqlt);
}
// mail send
// 
mailsend();   

?>