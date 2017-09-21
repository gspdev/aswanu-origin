<?php
require 'config.php';

if ($_GET) {
    $_orderId = trim($_GET['order_id']);
    $tracking = $_GET['tracking'];
    $method = $_GET['method'];

    if ($order = Mage::getModel('sales/order')->loadByIncrementId($_orderId)) {
        $method = !empty($method) ? $method : $order->getData('shipping_method');
        $_queryStatus = $shipCarrierQuery;

        $data = array(
            'customer_name'	=> $order->getData('customer_firstname') . ' ' . $order->getData('customer_lastname'),
            'customer_email'	=> $order->getData('customer_email'),
            'shipping_date'	=> date('Y-m-d') . ' (HKT)',
            'shipping_method'	=> $shipMethod[$method],
            'track_no' => $tracking,
            'query'     => $_queryStatus[$method]
        );

        //type:complete
        $trackTable = '
        <table width="100%" border="0" cellspacing="1" cellpadding="0" style="background:#ccc">
            <tr style="background:#ccc">
                <td width="13%" height="30" bgcolor="#FFFFFF">Shipping Date</td>
                <td width="13%" bgcolor="#FFFFFF">Tracking NO.</td>
                <td width="15%" bgcolor="#FFFFFF">Shipping Carrier</td>
                <td width="32%" bgcolor="#FFFFFF">Query Status</td>
            </tr>
        ';

        $trackTable .='
        <tr>
            <td height="25" bgcolor="#FFFFFF">'.$data['shipping_date'].'</td>
            <td height="25" bgcolor="#FFFFFF">'.$data['track_no'].'</td>
            <td bgcolor="#FFFFFF">'.$data['shipping_method'].'</td>
            <td bgcolor="#FFFFFF"><a href="'.$data['query'].'">'. $data['query'].'</a></td>
        </tr>
        ';
        $trackTable.='</table>';
        //echo $trackTable;exit;

        $comment = $trackTable;
        //echo $comment;exit;
        $status = "complete";
        $order->addStatusHistoryComment($comment , $status)
            ->setIsVisibleOnFront(1)
            ->setIsCustomerNotified(1);
        $order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);

        if ($order->save()) {
            echo $id . ' update succeed<br>';
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<style>
    body{margin:10px;text-align:left}
    table td {padding:5px;}
</style>
<form action="?" method="get">
    <table width="50%" border="0" cellspacing="1" cellpadding="0" style="background:#e8e8e8">
        <tr>
            <td height="25" bgcolor="#F3F3F3">更新订单状态</td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF">
                <p>订单编号:
                    <input type="text" name="order_id" id="order_id" />
                </p>
                <p>
                    <label for="method">
                    运输方式 :
                        <?php echo formSelect("method" , $shipMethod)?>
                    </label>
                </p>
                    <label for="tracking">
                        追踪号:
                        <input type="text" name="tracking" id="tracking" />
                    </label>
                </td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF">
                <input type="submit" value="提交更新" />
                <input type="reset" /></td>
        </tr>
    </table>
</form>
</body>
</html>
