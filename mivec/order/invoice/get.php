<?php
require 'config.php';
//general config
$_general["name"] = Mage::getStoreConfig("general/store_information/name");
$_general["email"] = Mage::getStoreConfig("contacts/email/recipient_email");
$_general["tel"] = Mage::getStoreConfig("general/store_information/phone");
$_general["business_time"] = Mage::getStoreConfig("general/store_information/hours");
$_general["country"] = "China";
$_general["address"] = Mage::getStoreConfig("general/store_information/address");
//print_r($_general);exit;

$token = $app->getRequest()->getParam("token");
$orderId = $app->getRequest()->getParam("order_id");
$_order = Mage::getModel("sales/order")->load($orderId , "increment_id");
$_orderData = getOrderDetail($_order);
$_orderData["shipping_address"] = $_orderData["shipping_address"]["firstname"]
    . " " . $_orderData["shipping_address"]["lastname"] . "<br>"
    . " " . $_orderData["shipping_address"]["street"]
    . " " . $_orderData["shipping_address"]["city"] . "<br>"
    . " " . $_orderData["shipping_address"]["region"]
    . " " . $_orderData["shipping_address"]["zip"] . "<br>"
    . " " . $_orderData["shipping_address"]["country"] . "<br>"
    . " T:" . $_orderData["shipping_address"]["telephone"];
//print_r($_orderData);exit;

//due date
$date = date("Y-m-d" , strtotime($_order->getData("created_at")));
$_duedate = date("Y-m-d" , strtotime($date) + (86400*15));
$_orderData["due_date"] = $_duedate;

//payment info
$_payment = $_order->getPayment();
$_payAdditional = $_payment->getAdditionalInformation()["instructions"];
$_orderData["payment_info"] = $_payment->getMethodInstance()->getTitle() . "</P>"
    .$_payAdditional;

//print_r(getOredrItems($_order));exit;

//invoice data
$_invoiceData = getOrderInvoice($_order);

function getOrderInvoice(Mage_Sales_Model_Order $order)
{
    $_invoiceData = array();
    if ($order->hasInvoices()) :
        $invoiceCollection = $order->getInvoiceCollection();
        foreach($invoiceCollection as $invoice):
            //var_dump($invoice);
            $_invoiceData = array(
                    "id"    => $invoice->getId(),
                    "increment_id" => $invoice->getIncrementId()
            );
        endforeach;
    endif;
    return $_invoiceData;
}
?>
<div style="background: #f6f6f6;">
    <table width="700" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" style="font-family: 'Open Sans', sans-serif">
        <tbody>
        <tr>
            <td>
                <table class="outer" width="650" style="" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff">
                    <!--header-->
                    <tr style="padding: 0 1px;height:100px;">
                        <td width="200" style="vertical-align: top;padding-top: 20px;">
                            <a href="<?php echo __WEB_BASE__?>" style="display: block;">
                                <img src="<?php echo __WEB_SKIN__;?>images/logo.png" alt="GSP"
                                     width="165" height="48">
                            </a>
                            <!--head-left-->
                            <div style="width:200px;padding:0 10px;font-size:10px;line-height: 14px;">
                                <h5 style="text-transform: uppercase;padding: 25px 0 0 0;margin:0">Shipping From</h5>
                                <p style="margin:20px 0 10px 0;">Call Us:<?php echo $_general["tel"]?></p>
                                <p style="margin:10px auto;">Business Time : <?php echo $_general["business_time"]?></p>
                                <p style="margin:10px auto;">Email : <?php echo $_general["email"];?></p>
                                <p style="margin:10px auto;"><a href="<?php echo __WEB_BASE__?>"><?php echo __WEB_BASE__;?></a></p>
                            </div>
                        </td>
                        <td width="100">
                        </td>
                        <!--head-right-->
                        <td width="350" style="padding-top: 20px;">
                            <table>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td width="150" style="vertical-align: top;">
                                                    <div style="font-size: 10px;">
                                                        <p style="margin: 0;font-weight:bold;text-transform: uppercase">Invoice Date</p>
                                                        <p style="margin: 0;"><?php echo $_orderData["general"]["order_date"]?></p>
                                                    </div>
                                                </td>
                                                <td width="200" style="vertical-align: top;">
                                                    <div style="font-size: 10px;">
                                                        <p style="margin: 0;"><span style="display: inline-block;width: 80px;font-weight: bold;">Invoice:</span><?php echo $_orderData["general"]["increment_id"]?></p>
                                                        <p style="margin: 0;"><span style="display: inline-block;width: 80px;font-weight: bold;">Order:</span><?php echo $_orderData["general"]["increment_id"]//$_invoiceData["increment_id"]?></p>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="vertical-align: top;">
                                                    <h5 style="text-transform: uppercase;padding: 25px 0 0 0;margin:0">Shipping To</h5>
                                                    <div style="font-size: 10px;padding:0 12px 0 0;">
                                                        <p><?php echo $_orderData["shipping_address"];?></p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 10px;"></td>
                    </tr>
                    <tr>
                        <td width="450" style="vertical-align: bottom">
                        </td>
                        <td></td>
                        <td width="450">
                            <div style="border: 2px solid #000;float:right;width: 200px;font-size:12px;">
                                <p style="font-weight: bold;margin: 0;padding: 0 10px;text-transform: uppercase">
                                    Total: <span style="font-weight: normal;text-align: right;float: right;text-transform: none"><?php echo $_orderData["amount"]["grand_total"]?></span>
                                </p>
                                <p style="font-weight: bold;margin: 0;padding: 0 10px;text-transform: uppercase">
                                    Due Date : <span style="font-weight: normal;text-align: right;float: right;text-transform: none"><?php echo $_orderData["due_date"]?></span>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr style="height: 10px;"></tr>
                    <!--order-->
                    <tr>
                        <td colspan="3" align="center">
                        <table cellspacing="0" cellpadding="0" border="0" width="650" style="border:1px solid #eaeaea">
                            <thead>
                                <tr>
                                    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Sku</th>
                                    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Item</th>
                                    <th align="center" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Qty</th>
                                    <th align="left" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Unit Per Qty</th>
                                    <th align="right" bgcolor="#EAEAEA" style="font-size:13px;padding:3px 9px">Subtotal</th>
                                </tr>
                            </thead>
                            <?php
                            if ($_orderItems = getOrderItems($_order)):
                                foreach ($_orderItems as $_item) :
                            ?>
                            <tbody bgcolor="#F6F6F6">
                                <tr>
                                    <td height="30" align="left" valign="top" style="padding:3px 9px;border-bottom:1px dotted #cccccc"><strong style="font-size:11px"><?php echo $_item["sku"]?></strong></td>
                                    <td align="left" valign="top" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc"><?php echo $_item["name"]?></td>
                                    <td align="center" valign="top" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc"><?php echo $_item["qty"]?></td>
                                    <td align="center" valign="top" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc"><?php echo $_item["price"]?></td>
                                    <td align="right" valign="top" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc"><span class="m_-9064054173632497493price"><?php echo $_item["subtotal"]?></span></td>
                                </tr>
                            </tbody>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </table>
                        <table width="100%" border="0" cellspacing="5" cellpadding="0">
                          <tfoot style="padding:20px;">
                            <tr class="m_-9064054173632497493subtotal">
                              <td colspan="3" align="right" style="padding:3px 9px">Subtotal</td>
                              <td width="24%" align="right" style="padding:3px 9px"><?php echo $_orderData["amount"]["subtotal"]?></td>
                            </tr>
                            <tr class="m_-9064054173632497493shipping">
                              <td colspan="3" align="right" style="padding:3px 9px">Shipping &amp; Handling</td>
                              <td align="right" style="padding:3px 9px"><?php echo $_orderData["amount"]["shipping"]?></td>
                            </tr>
                            <tr class="m_-9064054173632497493grand_total">
                              <td colspan="3" align="right" style="padding:3px 9px"><strong>Grand Total</strong></td>
                              <td align="right" style="padding:3px 9px"><strong><?php echo $_orderData["amount"]["grand_total"]?></strong></td>
                            </tr>
                          </tfoot>
                        </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table class="outer" width="650" style="" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff">
                    <!--footer-->
                    <tr>
                        <td width="450" style="vertical-align: top;">
                            <h5 style="text-transform: uppercase;padding: 25px 0 0 10px;">SHIPPING INFO:</h5>
                            <div style="font-size: 10px;padding:0 12px;">
                                <p>Shipping Carrier : <?php echo $_orderData["carrier"]['carrier']?><?php //echo $_orderData["amount"]["grand_total"]?></p>
                                <p>Shipping Cost    : <?php echo $_orderData["amount"]["shipping"]?></p>
                                <p>Due Date         : <?php echo $_orderData["due_date"]?></p>
                            </div>
                        </td>
                        <td width="450" style="vertical-align: top;">
                            <h5 style="text-transform: uppercase;padding: 25px 0 0 10px;">Payment method:</h5>
                            <div style="font-size: 10px;padding:0 12px;border-left:1px solid #000;">
                                <?php echo $_orderData["payment_info"];?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center" valign="middle" style="vertical-align: top;"><br>Thank You! Aswanu.com</td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
