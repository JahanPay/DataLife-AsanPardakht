<?php

/**
 * DLEPERSIAN DEVELOPMENT TEAM
 * ------------------------------------------------------
 * @author Hamid Yousefi <mails.hamidyousefi@gmail.com>
 * @link http://dlepersian.ir/10-jahanpay.html
 * @version 1.1 Farsi
 * ------------------------------------------------------
 * @date 2015/02/24
 */

defined("DATALIFEENGINE") || exit;

require_once ROOT_DIR . '/language/' . $config['langs'] . '/hypayment.lng';
$sqlconf['jahanpay_api'] = ""; // ENTER YOUR JAHANPAY API HERE

if (filter_input(INPUT_GET, "action", FILTER_DEFAULT) === "verify") {
    $trans_id = filter_input(INPUT_GET, 'au', FILTER_DEFAULT);
    $order_id = $_GET["orderid"];

    $transaction = $db->super_query("SELECT * FROM " . PREFIX . "_hypayments WHERE id = '$order_id' AND verified = '0' LIMIT 1");
     if (is_array($transaction) && count($transaction)) {
   $transid=$transaction['transid'] ;
            $client = new SoapClient("http://www.jpws.me/directservice?wsdl");
			$res = $client->verification($sqlconf['jahanpay_api'] , $transaction['hypay_price'] , $transid , $order_id, $_POST + $_GET );
       
       if( ! empty($res['result']) and $res['result'] == 1) {
            $db->query("UPDATE " . PREFIX . "_hypayments SET verified = '1', transid = '$transid' WHERE id = '$order_id'");
            msgbox($lang['verified_msg'], $lang['verified_complete'] . $transid );
        } else msgbox($lang['verification_error'], $lang['jahanpay_err_'.($res['result']*-1)]);
    }
}
else {
    if (getenv("REQUEST_METHOD") === "POST") {
        $data_pack = filter_input(INPUT_POST, "datapack", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (is_array($data_pack) && count($data_pack)) {
            $data = array();
            $msgerror = "";

            foreach (array('hypay_name', 'hypay_email', 'hypay_price', 'hypay_info') as $fieldname) {
                if ($fieldname === 'hypay_price') $data_pack[$fieldname] = intval($data_pack[$fieldname]);
                else $data_pack[$fieldname] = trim($db->safesql($data_pack[$fieldname]));

                if ($fieldname === "hypay_email" && !filter_var($data_pack[$fieldname], FILTER_VALIDATE_EMAIL)) {
                    $msgerror = $lang['address_required'];
                    break;
                }
                if ($fieldname === "hypay_price" && $data_pack[$fieldname] === 0) {
                    $msgerror = $lang['amount_required'];
                    break;
                }
                if ($fieldname === "hypay_name" && $data_pack[$fieldname] === "") {
                    $msgerror = $lang['name_required'];
                    break;
                }

                $data['index'][$fieldname] = "`" . $fieldname . "`";
                $data['value'][$fieldname] = "'" . $data_pack[$fieldname] . "'";
            }

            if ($msgerror === "") {
                $data['index']['date'] = "`date`";
                $data['value']['date'] = "'$_TIME'";
                $data['index']['gateway'] = "`gateway`";
                $data['value']['gateway'] = "'jahanpay'";

                $db->query("INSERT INTO " . PREFIX . "_hypayments (" . implode(", ", $data['index']) . ") VALUES (" . implode(", ", $data['value']) . ")");
                $insert_id = $db->insert_id();

  
                    $client = new SoapClient("http://www.jpws.me/directservice?wsdl");
                    $res = $client->requestpayment($sqlconf['jahanpay_api'], intval($data_pack['hypay_price']), $config['http_home_url'] . 'index.php?do=hypayment&action=verify&orderid='.$insert_id, $insert_id);

                if($res['result']==1){
                    $db->query("UPDATE " . PREFIX . "_hypayments SET transid = '" . $res['au']. "' WHERE id = '" . $insert_id . "' AND verified = '0'");
                    echo ('<div style="display:none;">'.$res['form'].'</div><script>document.forms["jahanpay"].submit();</script>');
                } else {
                    $db->query("DELETE FROM " . PREFIX . "_hypayments WHERE id = '".$insert_id."' AND verified = '0'");
              
                    $msgerror = $lang['jahanpay_err_'.($res['result']*-1)];
                }
            }
        } else $msgerror = $lang['fields_required'];
        msgbox($lang['payment_error'], $msgerror);
    }
    if (file_exists(ROOT_DIR . '/templates/' . $config['skin'] . '/hypayment.tpl')) {
        $tpl->load_template("hypayment.tpl");
        $tpl->compile('content');
        $tpl->clear();
    }
}