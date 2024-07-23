<?php
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
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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

$fname ="Sumit";
$lname ="vaishnav";
$phone = '9876543210';
$email="demo@demo.com";
$addresess = "secret street";
$_SERVER['REMOTE_ADDR'] ="newaddress";
$citieslist="udaipur";
$zipcodes ="876543";
$states= "Rajasthan"; 


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
        <div>       <center><img src="http://energyrewards.com/wp-content/uploads/2020/09/energy-logo__1_-removebg-preview.png" alt="" style="width:200; height:93;"/></center>
<br/>
        <span class="f-f-arial">Certificate of Authenticity</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <div>
        <p style="text-align:justify;">TCPA Disclosure: By clicking on the "submit" button, I provide my consent to be contacted regarding promotional opportunities to receive autodialed and live calls, emails, text messages, and/or pre-recorded messages each month using automated dialing technology by someone authorized by (EAG,) EnergyRewards.com and or its affiliates via any information that I have shared to do so including my mobile or wireless/cellular number, if applicable. This consent is to dissolve any potential violation of the TCPA and or any violation of DNC rules and regulations. As this disclaimer is to provide compliance to state and federal policy along with any corporation Do Not Call List. I agree and consent to submission of my contact information which intention waives the enforcement of the TCPA and DNC rules and regulations of contact. I am not required to purchase any goods or services and recognize that message and data rates may apply. I can revoke consent at any time by sending an email with the subject Opt-Out to support@energyrewards.com and supply full contact information that I wish to have removed writing. I also agree to receive electronic communications and confirm that I have read the Privacy Policy before submitting this form.</p>
        </div>
        <br>
        <div>
        <h4>Verified Information...</h4>
        </div>
        <div class="pt-3">';
 
        $row1 = '<table class="table">
            
                <tr>
                    <td colspan="4">
                    <h6> Dear '.$fname.' '.$lname.' </h6>
                    </td>
                </tr>
            
        <tr>
        <td>Phone Number:</td>
        <td>'.$phone.'</td>
        <td>Email Id:</td>
        <td>'.$email.'</td>
        
        </tr>';
        $row2='';
          $row2 .='<tr>
          <td colspan="4">ADDRESS DETAILS:- </td>
          </tr>
          <tr>
          <td>Address:</td>
          <td>'.$addresess.'</td>
          <td>City:</td>
          <td>'.$citieslist.'</td>
          </tr>
          <tr>
          <td>Zip Code:</td>
          <td>'.$zipcodes.'</td>
          <td>State:</td>
          <td>'.$states.'</td>
          </tr>';
      $row3='</table>
      </div>
      <div class="pt-3">
      <h6>Your IP ADDRESS:-  '.$_SERVER['REMOTE_ADDR'].'</h6>
      <h6>Time stamp:- '.date("d F Y, H:i:s A").'</span></h6>
      </div>
      <hr>
      <div>
      <img src="http://energyrewards.com/wp-content/uploads/2020/09/imgpsh_mobile_save-removebg-preview-2.png" style="width:200; height:92;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span class="m-l-80"><a href="https://energyrewards.com"> energyrewards.com</a></span>
      </div>
      
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

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_061.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
