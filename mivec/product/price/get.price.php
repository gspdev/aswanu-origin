<?php
require 'config.php';

//currency rate 6.53
$_rate = 6.2983;

$result = array();
if ($_cost = $app->getRequest()->getParam("cost")) {
    $result = setFormula($_cost);
    $result['USD'] = $result['price'] / $_rate;
}
?>

<style>
    .container {width:50%;}
/* Buttons
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
*/
button.button::-moz-focus-inner { padding:0; border:0; } /* FF Fix */
button.button { 
	-webkit-border-fit:lines; /* <- Safari & Google Chrome Fix */ 
	overflow:visible; width:auto; border:0; padding:0; margin:0; background:transparent; cursor:pointer;
}
button.button span {
	float:left;
	display:block;
	padding:0;
	font-size:12px;
	text-align:center; 
	white-space:nowrap;
	background-color:#ddd;
	color:#333;
	
	transition:			color 300ms ease-in-out, background-color 300ms ease-in-out;
	-moz-transition:	color 300ms ease-in-out, background-color 300ms ease-in-out;
	-webkit-transition:	color 300ms ease-in-out, background-color 300ms ease-in-out;
	-o-transition:		color 300ms ease-in-out, background-color 300ms ease-in-out;
}
button.button:hover span { background-color:#333; color:#fff; }
button.button span span { padding:0 25px; height:35px; line-height:35px; }

.warning.button span {background: #FF0000;color: #FFF}
/* Smaller button - for inline display with input boxes
-------------------------------------------------------------- */
button.button.btn-inline span span,
.product-view .box-tags .form-add button.button span span { padding:0 10px; line-height:36px; height:36px; }

/* Big button: Add to cart (product view) & Proceed to checkout
-------------------------------------------------------------- */
/*.product-view .btn-cart span,
button.btn-checkout span {}*/
.product-view .btn-cart span span,
button.btn-checkout span span { padding:5px 25px; font-size:1.1666em; /*14px*/ }

button.button1 {	-webkit-border-fit:lines; /* <- Safari & Google Chrome Fix */ 
	overflow:visible; width:auto; border:0; padding:0; margin:0; background:transparent; cursor:pointer;
}
ul{margin:0;padding:20px;}
.rate{padding:0 0 0 20px;}
</style>
<div class="container">
    <form method="get" action="?">
    <table width="100%" border="0" cellspacing="1" cellpadding="5" style="background:#CCC">
      <tr>
        <td colspan="2" bgcolor="#FBFBCA"><h2>Calculate Price</h2></td>
        </tr>
      <tr>
        <td width="21%" bgcolor="#FFFFFF">Cost</td>
          <td width="79%" bgcolor="#FFFFFF"><input type="text" name="cost" value="<?php echo $app->getRequest()->getParam("cost");?>"/> <span>&nbsp;rate: <?php print_r($_rate)?></span></td>
      </tr>
      <tr>
        <td bgcolor="#FFFFFF">Result</td>
        <td bgcolor="#FFFFFF"><?php print_r($result);?></td>
        </tr>
      <tr>
        <td colspan="2" bgcolor="#FFFFFF"><button id="submit" type="submit" class="button btn-cart"> <span><span>Query</span></span> </button></td>
      </tr>
      <tr bgcolor="#ffffff">
          <td colspan="2" bgcolor=#ffffff>
            <ul>
              <li>2018 04/03<span class="rate">6.28</span></li>
              <li>2018 03/27<span class="rate">6.26</span></li>
              <li>2018 03/20<span class="rate">6.33</span></li>
              <li>2018 04/09<span class="rate">6.2983</span></li>
            </ul>
          </td>
        </tr>
    </table>
    </form>
</div>