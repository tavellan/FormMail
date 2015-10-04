<?php
header('Content-Type: application/json');
$jsonArray = json_decode(file_get_contents("php://input"),true);

function mail_utf8($to, $subject = '(No subject)', $message = '', $header = '') {
  
  if ($header != '') { 
    /* Get the MIME type and character set */
    preg_match( '@Content-Type:\s+([\w/+]+)(;\s+charset=(\S+))?@i', $header, $matches );
    if (isset($matches[1]) && $matches[1] ==='text/html') {
        $header_ = '';  
    }
  } else {
      $header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
    }
    $mailto = mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header);
    return $mailto;
} 

$headers = '';
$message = '';
if (count($jsonArray)>0){

  if(isset($jsonArray['mailconf']['To'])) {
      
    if ($jsonArray['mailconf']['To']!='') $to=$jsonArray['mailconf']['To'];
    else $to='tuki@aumanet.fi';

    if (isset($jsonArray['mailconf']['Subject'])) $subject=$jsonArray['mailconf']['Subject'];
    else $subject='FormMail(v.0.1 Aumanet): Yhteydenottolomake';

    foreach ($jsonArray['fields'] as $key => $value) {
      $message .= $key.": ".$value."\n";
    }

    $message = str_replace("\n.", "\n..", $message);

    // In case any of our lines are larger than 70 characters, we should use wordwrap()
    $message = wordwrap($message, 70);

    // HTML message
    if (isset($jsonArray['mailconf']['Content-type']) && $jsonArray['mailconf']['Content-type']==='text/html') {
      $message = str_replace("\n", "<br>", $message);
      $message = '
      <html>
      <head>
        <title>'.$subject.'</title>
      </head>
      <body>
        <p>'.$message.'</p>
      </body>
      </html>
      ';

      // To send HTML mail, the Content-type header must be set
      $headers .= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n";
    }

    if (isset($jsonArray['mailconf']['From']) && isset($jsonArray['mailconf']['Reply-To'])) {
      $headers .= 'From: '.$jsonArray['mailconf']['From']. "\r\n" .
                          'Reply-To: '.$jsonArray['mailconf']['Reply-To']. "\r\n" .
                          'X-Mailer: PHP/' . phpversion();
    }

    if ($headers!='') {
      $mailto = mail_utf8($to,$subject,$message,$headers);                  
    } else {
      $mailto = mail_utf8($to,$subject,$message);
    }
    
    if($mailto) {
      $response = array("response" => array("status" => 200,"message" => "OK"));
    } else {
      $response = array("response" => array("status" => 500,"message" => "Internal Server Error"));
    }
  }

} else {
    $response = array("response" => array("status" => 500,"message" => "Internal Server Error"));
}

// Luodaan JSON-objekti
echo json_encode($response);

?>

