<?php
session_start();
$id = $_SESSION["id"];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dnc_db";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM admin_scrub_uploads
                          INNER JOIN temp_scrub_admins ON admin_scrub_uploads.id = temp_scrub_admins.admin_scrub_id";
            $result = mysqli_query($con, $sql);
            $count=mysqli_num_rows($result);
            $i = 0;
          //do it for each value          
            while($row = $result->fetch_assoc()) { 

               // print_r($row["dnc_list_id"]);
                if($row["dnc_list_id"] == 0 )
                {
                    $name = $row["upload_name"];
                    $i++;
                }
                
            }
            $non_dnc = $i;
            $dnc =  $count - $non_dnc;

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
require_once('tcpdf_include.php');

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
        <div>       <center><img src="http://dncscrubbing.com/webtheme/img/logo.png" alt="" style="width:153; height:27;"/></center>
<br><br><br>
        <span class="f-f-arial">Generated Details</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div class="pt-3">';
 
        $row1 = '<table class="table">
            
                <tr>
                    <td colspan="4"><br>
                    <p>Dear '.$name.'</p>
                    </td>
                </tr>
            
        <tr><br>
        <td><p>Total Dnc Number:</p></td>
        <td><p>'.$dnc.'</p></td><br>&nbsp;&nbsp;&nbsp;

        
        </tr>
        <tr>
        <td><p>Total Non-Dnc Number:</p></td>
        <td><p>'.$non_dnc.'</p></td><br>
        </tr>  <br>';
        $row2='';
          $row2 .='<tr>
          <td colspan="4"><p>DETAILS:- </p></td><br>
          </tr><br>
          <tr>
          <td><p>Total Contacts:</p></td>
          <td><p>'.$count.'</p></td>
          </tr>';
      $row3='</table>
      </div>
      <div class="pt-3">
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
	

// reset pointer to the last page
$pdf->lastPage();
$ftname=rand(11111,99999).time().".pdf";
$fname=__DIR__."/pf/".$ftname;
// ---------------------------------------------------------

$pdf->Output($fname,'F');

//$pdf->Output('TCPDF/examples/pf'.$ordernumber.'.pdf', 'F');

//$pdf->Output('example_061.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
echo $fname;exit;
