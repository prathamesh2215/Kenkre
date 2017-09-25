<?php
ini_set('display_errors',1);

if(isset($_POST['API'])){
  if ($_POST['API']=='check Status'){
   $PayuMoney_BASE_URL = "https://www.payumoney.com/payment/payment/chkMerchantTxnStatus?";
  } elseif ($_POST['API']=='Get response'){
    $PayuMoney_BASE_URL = "https://www.payumoney.com/payment/op/getPaymentResponse?";
  }
}

$action = '';
$posted = array();
$MERCHANT_KEY = 'lpBnWPld';//PUT YOUR MERCHANT KEY HERE
if(!empty($_POST)) {

  foreach($_POST as $key => $value) {
     
   $posted[$key] = htmlentities($value, ENT_QUOTES);
  }
  //var_dump($posted);
  
  $postData = array();
  $postData['merchantKey']= $MERCHANT_KEY;
  unset($posted['merchantKey']);
  $postData['merchantTransactionIds']=$posted['merchantTransactionIds'];
  unset($posted['merchantTransactionIds']);
  $postNow = http_build_query($postData);

  //$postNow = $postNow .'&jsonSplits=['.$postNow.']';
  var_dump($postNow);
  $response = curlCall($PayuMoney_BASE_URL.$postNow,TRUE);
 // var_dump($response);
  
}
$formError = 0;



function curlCall($postUrl, $toSend) {
   
   
  $ch = curl_init();
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
  curl_setopt($ch, CURLOPT_URL, $postUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $header = array(
     'Authorization: nv9Hf4IBjxTtcIU23kKZV9RhOyy0+/mZim2oAzaCHD8=' //PUT YOUR Authorization HERE
  );
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
   
  $out = curl_exec($ch);
  //if got error
  if (curl_errno($ch)) {
    $c_error = curl_error($ch);
    if (empty($c_error)) {
      $c_error = 'Some server error';
    }
    return array('curl_status' => 'FAILURE', 'error' => $c_error);
  }
  $out = trim($out);
  return array('curl_status' => 'SUCCESS', 'result' => $out);
}


?>
<html>
  <body > 
    <h2>PayUmoney - Check Transaction Status and Get Response</h2>
    <br/>
	<form action="<?php echo $action; ?>" method="post" name="PayuMoneyForm">
	<p><label for="API">API : </label>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="API" value="check Status" checked="checked" />check Status
                  &nbsp;&nbsp;<input type="radio" name="API" value="Get response" />Get response
                </p>  
	<input name ="merchantKey" value="<?php echo $MERCHANT_KEY ?>"  type="hidden"/> <!-- //ReadOnly="true" --> 
      <table>
        <tr>
          <td>Merchant Transaction ID: </td>
          <td><input name="merchantTransactionIds" id="merchantTransactionIds" value="<?php echo (empty($posted['merchantTransactionId'])) ? '' : $posted['merchantTransactionIds']; ?>" /></td>
        </tr>
    </table>
    <input type="submit" value="submit"/>
	
	<p><label for="result"  ><?php if(isset($response)) 
		echo implode("", $response )?></label></p> 
	
    </form>
   
  </body>
</html>