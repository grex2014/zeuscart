<?php

/**
* GNU General Public License.

* This file is part of ZeusCart V4.

* ZeusCart V4 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 4 of the License, or
* (at your option) any later version.
* 
* ZeusCart V4 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with Foobar. If not, see <http://www.gnu.org/licenses/>.
*
*/


/**
 * Add to cart  related  class
 *
 * @package   		Display_DAddCart
 * @category    	Display
 * @author    		AJ Square Inc Dev Team
 * @link   		http://www.zeuscart.com
  * @copyright 	        Copyright (c) 2008 - 2013, AJ Square, Inc.
 * @version   		Version 4.0
 */


class Display_DAddCart 
{
	/**
	 * This function is used to show the cart item.
	 * @param   array  	$arr	    array of items
	 * @param   array  	$result      array of country
	 * @return string
	 */	
	function showCart($arr,$result)
	{


		if(!(empty($arr)))
		{
		
		  	 if($Err->messages>0)
			{
				$output['val']=$Err->values;
				$output['msg']=$Err->messages;
			}
			$obj=new Display_DAddCart();
			$res=$obj->loadCountryDropDown($result,'selbillcountry');
			$resship=$obj->loadCountryDropDown($result,'selshipcountry');
		
			$out = '<input  type="hidden" name="prodid" value="'.(int)$_GET['prodid'].'">';
			
			$out.='<div id="myaccount_div">	'.$_SESSION['cartmsg'].'
			<table class="rt cf" id="rt1">
			<thead class="cf">
			<tr>
			<th></th>
			<th>Gallery View</th>
			<th>Product Name</th>
			<th>Qty</th>
			<th>Unit Price 	</th>
			<th>Sub Total</th>				
			</tr>
			</thead>
			<tbody>';

				if(isset($_SESSION['prowishlist']))
				{
					unset($_SESSION['prowishlist']);	
					$proid='';
				}

				$cnt=count($arr);
				
					for ($i=0;$i<$cnt;$i++)
					{
						$proid.=$arr[$i]['product_id'].',';
						$prqty=$arr[$i]['product_qty'];
						
						if ($arr[$i]['soh']<=0)
							$prqty=0;
							
									
						$original_price=$arr[$i]['product_unit_price'];
						
						if($arr[$i]['product_unit_price']!=0.00)
							$msrp=$arr[$i]['product_unit_price']; 
						elseif($arr[$i]['msrp1']!=0.00)
							$msrp=$arr[$i]['msrp1']; //$msrp calculated unitpirce
						else
							$msrp=$arr[$i]['msrp'];
							
						$subtotal[]=$prqty*$msrp;
						
						$total=array_sum($subtotal);
									
						$shippingcost[]=$arr[$i]['shipingamount'];
						$shipping=array_sum($shippingcost);
								
						$_SESSION['total']=$total;
						$thumbimage=$arr[$i]['thumb_image'];
						$temp=$arr[$i]['thumb_image'];
						$img=explode('/',$temp);
							
							$out.='<tr>
								<td><a href="'.$_SESSION['base_url'].'/index.php?do=addtocart&action=delete&prodid='.$arr[$i]['product_id'].'&id='.$arr[$i]['id'].'"><img src="'.$_SESSION['base_url'].'/assets/img/close_button.gif" alt="close">	</a>		  <div class="showcart_box"><a href="'.$_SESSION['base_url'].'/index.php?do=prodetail&action=showprod&prodid='.$arr[$i]['product_id'].'"></td><td>';
								if(file_exists($thumbimage))
		
								$out.='<img src="'.$_SESSION['base_url'].'/'.$thumbimage.'" alt='.$arr[$i]['title'].'  />';
								else
								$out.='<img src="images/noimage.jpg"  alt='.$arr[$i]['title'].' />';
		
		
								$out.='</a></div></td>
								<td ><a href="'.$_SESSION['base_url'].'/index.php?do=prodetail&action=showprod&prodid='.$arr[$i]['product_id'].'">'.$arr[$i]['title'].'</a></td>
								<td>'.$arr[$i]['product_qty'].'</td>
								<td><span class="label label-important"> '.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].'&nbsp;'.number_format($msrp*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
								<td><span class="label label-inverse">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].'&nbsp;'.number_format(($msrp*$arr[$i]['product_qty'])*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
								</tr>';
	
						}
					
					
					
			
				$_SESSION['prowishlist']=$proid;
				$grandtotal=$total+$shipping;
				$_SESSION['grandtotal']=$grandtotal;
			
			
			$out.='<tr>
				<td colspan="4" rowspan="3">&nbsp;</td>
			 	 <td><strong>Sub Total</strong></td>
				<td><span class="label label-success">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].'&nbsp;'.number_format($total*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
				</tr>
				<tr>
				<td><strong>Shipping Amount</strong></td>
					<td><span class="label label-warning">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].'&nbsp;'.number_format($shipping*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
				</tr>
				
				
				<tr>
				<td><strong>Grand Total</strong></td>
					<td><span class="label label-important">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].'&nbsp;'.number_format($grandtotal*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
				</tr>
				<tr>
				<td colspan="2"><a href="javascript:void(0);" onclick="callHome();"><input type="submit" class="btn btn-danger" value="Continue Shopping" name="Submit" ></a></td>';
				if($_SESSION['user_id']=='')
				{	
				$out.='<td colspan="4" align="right"><a href="javascript:void(0);" onclick="callRegister();" ><input type="button" name="Submit22" value="Proceed To Checkout" class="btn btn-inverse" ></a></td>';
				}
				elseif($_SESSION['user_id']!='')
				{
				$out.='<td colspan="4" align="right"><a href="javascript:void(0);" onclick="callContinue();" ><input type="button" name="Submit22" value="Proceed To Checkout" class="btn btn-inverse" ></a></td>';
				}
				$out.='</tr>
				</tbody>
				</table>
				</div>';
		}
		
		else
		{
			$out='<div class="alert alert-info">
			<button data-dismiss="alert" class="close" type="button">×</button>
			No Products Available in Your Shopping Cart.
			</div>';
		
		}
		return $out;	
	
	}
	
	/**
	 * This function is used to show the cart snap shot
	 * @param   int  	$grandtotal	   total amount
	 * @param   int  	$cnt              count of item
	 * 
	 * @return string
	 */
	
	function cartSnapShot($grandtotal,$cnt)
	{
		$output='<div class="viewcartTXT"><span>'.$cnt.'</span> items in the Cart<br />
		Sub Total : <span><!--$-->'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($grandtotal*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></div>
		
		<div style="padding-top:7px;"> <a href="?do=showcart"><input type="submit" name="Submit52" value="View Cart" class="button5" style="float:left; clear:right " /></a><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getaddressdetails"><input type="submit" name="Submit5" value="Check Out" class="button6" style="float:left; clear:right" /></a></div>';
			return $output;
		
	}
	/**
	 * This function is used to show the country drop down
	 * @param   array  	$result	      array of country
	 * @param   string  	$name         name of the select box
	 * @param    string     $selected      selected country code
	 *
	 * @return HTML data
	 */
	
	function loadCountryDropDown($result,$name,$selected='')
	{
		if(count($result)>0)
		{
				$output='<select name="'.$name.'" style="width:280px;"><option value="">Select</option>';
				foreach($result as $row)
				{			 
				$cou_name=$row['cou_name'];
				$cou_code=$row['cou_code'];
				
				$output.=($selected==$cou_code)? "<option value='$cou_code' selected='selected'>$cou_name</option>" : "<option value='$cou_code'>$cou_name</option>";			  
				}				 
		}
			$output.='</select>';	

		return $output;	
	}

	/**
	 * This function is used to show the cart snap shot if no item found
	 *
	 * @return string
	 */
	
	function cartSnapShotElse()
	{
		$output='<div class="viewcartTXT"><font color="orange"><b>There is no item in your cart.</b></font></div>';
		return $output;
		
	}
	/**
	 * This function is used to show the quick registration
	 * @param   array  	$result	
	 * @param   array    $err    contains both error messages and values 
	 *
	 * @return string
	 */
	
	function showQuickRegistration($result,$Err)
	{

		
	   	    $output='<div class="row-fluid">
        		<ul class="steps">';
			
			 	 $output.='<li class="act"><a href="#"><span>1. Email Login</span></a></li>'; if(count($_SESSION['mycart'])!=count($_SESSION['gift']) && isset($_SESSION['mycart']))
				{	
				$output.='<li class="inact"><a href="#"><span>2. Billing Address</span></a></li>
				<li class="inact"><a href="#"><span>3. Shipping Address</span></a></li>
				<li class="inact"><a href="#"><span>4. Shipping Method</span></a></li><li class="inact"><a href="#"><span>5. Order Confirmation</span></a></li>
				<li class="inact"><a href="#"><span>6. Payment Details</span></a></li>';
				}
				else
				{
				 $output.='<li class="inact"><a href="#"><span>2. Order Confirmation</span></a></li>
				<li class="inact"><a href="#"><span>3. Payment Details</span></a></li>';
				}
			
			  $output.='</ul>	
       			 </div><div class="row-fluid">
                    <div class="span6">
			<form name="f1" method="post" action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=doquickregistration&gvid='.$_GET['gvid'].'">
                    	<div id="loginfrm">
                        		<ul class="loginfourm">
                                	<li><b>E-mail Address</b></li>
                                	<li><input type="text" class="input-large" name="txtregemail" value='.$Err->values['txtregemail'].'><br />
	 				 <font color="#FF0000"><AJDF:output>'.$Err->messages['txtregemail'].'</AJDF:output></font></li>
                                    	<li><b>Password</b></li>
                                	<li><input type="password" name="txtregpass" class="input-large"><br />
	   				<font color="#FF0000"><AJDF:output>'.$Err->messages['txtregpass'].'</AJDF:output></font></li>
                                	<li><input type="checkbox" name="remlogin" value="on" > Remember me?</li>
                                	<li><input type="submit" value="Login" class="btn btn-danger"> </li>
                                    <li><a href="'.$_SESSION['base_url'].'/index.php?do=forgetpwd"> Forgot password? </a> <span>OR</span> <a href="'.$_SESSION['base_url'].'/index.php?do=faq" target="_blank">Need help?</a> </li>
                                </ul>
                        </div>
			</form>	
                        </div>
                    	<div class="span6">
                         <div class="followus_div">
			<a href="#" id="openfb" class="facebook_btn"></a>
                        <a href="#" id="opentw" class="twitter_btn"></a>
                        <a href="#" id="opengp" class="google_btn"></a>

			</div>
                    <p class="userlogin_fnt"><span>or log in using your username and Password</span></p>
                        	<div id="signin_acc">
                        	<ul class="signin-account">
                            	<li> <h5>SIGN UP FOR AN ACCOUNT</h5></li>
                                <li> <p>Registration is fast and FREE! </p></li>
                                <li><a href="'.$_SESSION['base_url'].'/index.php?do=userregistration"> <input type="button" value="Register Here" class="btn btn-inverse"></a></li>
                            </ul>
                            <b>Account Protection Tips </b>
                            <ul class="acctips">
                            	<li>Choose a strong password and change it often.</li>
                                <li>Avoid using the same password for other accounts.</li>
                                <li>Create a unique password by using a combination of letters and numbers that are not easily guessed.</li>
                            </ul>
                            </div>
                    </div>

                    </div>';

		return $output;

	}

	
	/**
	 * This function is used to show the PaymentPageForAuthorizenet
	 *
	 * @return string
	 */
	function showPaymentPageForAuthorizenet()
	{
		

		$output='<div id="myaccount_div">

		<form name="f1" method="post" action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=doauthorizenetpayment"  class="form-horizontal">
		
		<h3 class="accinfo_fnt">Authorize.net Payment Information</h3>
		
		<h4 class="red_fnt">Credit Card Information</h3>

		<h3 class="accinfo_fnt">Please enter details below:</h3>
		<div class="control-group">
		<label for="inputEmail" class="control-label">Credit Card Number  <i class="red_fnt">*</i></label>
		<div class="controls">
			<input type="text" name="txtCardNumber"  />
		</div>
		</div>
		<div class="control-group">
		<label for="inputPassword" class="control-label">Expiration Date<i class="red_fnt">*</i></label>
		<div class="controls">
			<select name="txt_cem" style="border:#99600c solid 1px;"> 
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>  <option value="06">06</option>  <option value="07">07</option>  <option value="08">08</option>  <option value="09">09</option>  <option value="10">10</option>  <option value="11">11</option>  <option value="12">12</option>  </select>  	
				&nbsp;&nbsp;&nbsp;&nbsp;<select name="txt_cey" style="border:#99600c solid 1px;">  <option value="07">2007</option>  <option value="08">2008</option>  <option value="09">2009</option>  <option value="10">2010</option>  <option value="11">2011</option>  <option value="12">2012</option>  <option value="13">2013</option>  <option value="14">2014</option>  <option value="15">2015</option>  <option value="16">2016</option>  <option value="17">2017</option>  <option value="18">2018</option>  <option value="19">2019</option>  <option value="20">2020</option>  <option value="21">2021</option>  <option value="22">2022</option>  <option value="23">2023</option>  <option value="24">2024</option>  <option value="25">2025</option>  <option value="26">2026</option>  <option value="27">2027</option>  <option value="28">2028</option>  <option value="29">2029</option>  <option value="30">2030</option>  </select>
		</div>
		</div>
		
	
		
		<div class="control-group">
		<div class="controls">
			<button class="btn btn-danger" type="submit">Submit</button>
		</div>
		</div>
		</form>      </div>';

		return $output;

	}
	/**
	 * This function is used to showPaymentPageForBluepay
	 *
	 * @return string
	 */
	function showPaymentPageForBluepay()
	{
				
		$bluepaydetails = explode("|",$_SESSION['bluepaydetails']);				
		$merchantid=$bluepaydetails[0];
		$sucess_url = $bluepaydetails[1];
		$cancel_url = $bluepaydetails[2];
		unset($_SESSION['bluepaydetails']);	
		

		
				$output='<div id="myaccount_div">

		<form action="https://secure.bluepay.com/interfaces/bp10emu" method=POST class="form-horizontal">
		
		<h3 class="accinfo_fnt">Blue Pay Payment Information</h3>
		
		<h4 class="red_fnt">Credit Card Information</h3>

		<h3 class="accinfo_fnt">Please enter details below:</h3>
		<div class="control-group">
		<label for="inputEmail" class="control-label">Name On the Card  <i class="red_fnt">*</i></label>
		<div class="controls">
			<input type="text" name="NAME"  size="25"/>
		</div>
		</div>

		

		<div class="control-group">
		<label for="inputEmail" class="control-label">Credit Card Number <i class="red_fnt">*</i></label>
		<div class="controls">
			<input type="text" name="CC_NUM" />
		</div>
		</div>

		<div class="control-group">
		<label for="inputEmail" class="control-label">Expiration Date (mm/yy) <i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="CC_EXPIRES"/>
		</div>
		</div>
		<div class="control-group">
		<label for="inputEmail" class="control-label">CVV2 <i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="CVCCVV2" />
		</div>
		</div>

		<!--<div class="control-group">
		<label for="inputEmail" class="control-label">Address <i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="ADDR1" />
		</div>
		</div>
	
		<div class="control-group">
		<label for="inputEmail" class="control-label">City <i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="CITY" />
		</div>
		</div>
		

		<div class="control-group">
		<label for="inputEmail" class="control-label">State<i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="STATE" />
		</div>
		</div>

		<div class="control-group">
		<label for="inputEmail" class="control-label">ZipCode<i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="ZIPCODE" />
		</div>
		</div>

		<div class="control-group">
		<label for="inputEmail" class="control-label">Phone <i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="PHONE" />
		</div>
		</div>
		
		<div class="control-group">
		<label for="inputEmail" class="control-label">Email <i class="red_fnt">*</i></label>
		<div class="controls">
		<input type="text" name="EMAIL" />
		</div>
		</div>-->

		<input type=hidden name=MERCHANT value="'.$merchantid.'">
					<input type=hidden name=TRANSACTION_TYPE value="AUTH">
					<input type=hidden name=TAMPER_PROOF_SEAL value="adfc2d7799ffa98fc18c301bd4476ab9">
					<input type=hidden name=APPROVED_URL value="'.$sucess_url.'&pay_type=17">
					<input type=hidden name=DECLINED_URL value="'.$cancel_url.'">
					<input type=hidden name=MISSING_URL  value="'.$sucess_url.'&pay_type=17">
					<input type=hidden name=MODE         value="TEST">
					<input type=hidden name=AUTOCAP      value="0">
					<input type=hidden name=REBILLING    value="">
					<input type=hidden name=REB_CYCLES   value="">
					<input type=hidden name=REB_AMOUNT   value="">
					<input type=hidden name=REB_EXPR     value="">
					<input type=hidden name=REB_FIRST_DATE value="">   
					<input type=hidden name=AMOUNT value="'.round($_SESSION['checkout_amount']).'">	
		
	
		
		<div class="control-group">
		<div class="controls">
			<button class="btn btn-danger" type="submit">Submit</button>
		</div>
		</div>
		</form>      </div>';

		return $output;

	}
	/**
	 * This function is used to showPaymentPageForWorldPay
	 * @param   array  	$arr	
	 * @return string
	 */
	
	function showPaymentPageForWorldPay($arr)
	{

			$output='<div id="myaccount_div">

		<form  action="https://select.worldpay.com/wcc/purchase" method=POST  class="form-horizontal">
		
			
		<h3 class="accinfo_fnt">WorldPay Payment Confirmation</h3>
		
		<h4 class="red_fnt">WorldPay Payment Information</h3>

		<h3 class="accinfo_fnt">Please enter details below:</h3>


		 <span class="label label-info">Your Checkout Amount is  '.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].''.$arr['amount'].'</span>
		
		
			<input type=hidden name="instId" value="'.$arr['instId'].'">
					<input type=hidden name="cartId" value=" 122 "> 
					<input type=hidden name="amount" value="'.$arr['amount'].'">
					<input type=hidden name="currency" value="USD">
					<input type=hidden name="desc" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'">
					<input type=hidden name="testMode" value="100"> 
					<input type="hidden" name="MC_callback" value="'.$arr['MC_callback'].'" />
		
		
	
		
		<div class="control-group">
		<div class="controls">
			<button class="btn btn-danger" type="submit">Submit</button>
		</div>
		</div>
		</form>      </div>';

		return $output;

	}
	/**
	 * This function is used to showPaymentPageFor2Checkout
	 * @param $arr array
	 * @return string
	 */
	function showPaymentPageFor2Checkout($arr)
	{
				$output='<div id="myaccount_div">

		<form   id="form2co" name="form2co" method="post" 						action="https://www.2checkout.com/2co/buyer/purchase"  class="form-horizontal">
		
			
		<h3 class="accinfo_fnt">2Checkout Payment Confirmation</h3>
		
	
		 <span class="label label-info">Your Checkout Amount is  '.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].' '.$arr['total'].'</span>
		
		
		<input type="hidden" name="sid" value="'.$arr['sid'].'" />
		<input type="hidden" name="cart_order_id" value="100" />
		<input type="hidden" name="total" value="'.$arr['total'].'" /><input type="hidden" name="demo" value="Y" />
		<input type="hidden" name="fixed" value="Y" /><input type="hidden" name="return_url" value="'.$arr['return_url'].'" />
		<input type="hidden" name="lang" value="en" />
		<input type="hidden" name="card_holder_name" value="" />			
							
		
		
	
		
		<div class="control-group">
		<div class="controls">
			<button class="btn btn-danger" type="submit">Submit</button>
		</div>
		</div>
		</form>      </div>';

		return $output;

	}
	/**
	 * This function is used to show the Billing address.
	 * @param   array  	$records	array of address
	 * @param   array       $result	        array of country 
	 * @param   array      $Err             contains both error messages and values
	 * @param  integer $billing_addess_id
	 * @return string
	 */	
	function showBillingDetails($records,$result,$Err,$billing_addess_id)
	{


		$obj=new Display_DAddCart();
		$res=$obj->loadCountryDropDown($result,'selbillcountry',$Err->values['selbillcountry']);
			

		$output='<div class="row-fluid">
        		<ul class="steps">';
			if($_SESSION['user_id']!='')
			{	
	
				$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=accountinfo"><span>1. My Account</span></a></li>';
			}	
			else
			{
				$output.='<li class="inact"><a href="#"><span>1. Email Login</span></a></li>';
			}
					
			$output.='<li class="act"><a href="#"><span>2. Billing Address</span></a></li>
			<li class="inact"><a href="#"><span>3. Shipping Address</span></a></li>
			<li class="inact"><a href="#"><span>4. Shipping Method</span></a></li>';
			
			$output.='<li class="inact"><a href="#"><span>5. Order Confirmation</span></a></li>
			<li class="inact"><a href="#"><span>6. Payment Details</span></a></li>
				        
			</ul>
        		</div><div class="row-fluid">
                       <div class="span4">
                         
                      <p class="billing_title">Select from previous address</p>

                      <ul class="addresslist">';

			if(count($records)>0)
			{	
				$i=0;
				while ($i < 4)
				{
					if($billing_addess_id==$records[$i]['id'])
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn-hov.gif';
					}
					else
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn.gif';
					}
					if($records[$i]['contact_name']!='')
					{
						$output.='<li><address>
						<h5>'.$records[$i]['contact_name'].'</h5>
						<p>'.$records[$i]['address'].'</p>
						<p>'.$records[$i]['city'].'</p>
						<p>'.$records[$i]['state'].'</p>
						<p>'.$records[$i]['zip'].'</p>	
						<a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingaddressdetails&bill_add_id='.$records[$i]['id'].'"><img src="'.$imageicon.'" alt="click"></a>
						</address></li>';
					}
					$i++;
				
				}
				$j=4;
				while ($j < count($records))
				{
					if($billing_addess_id==$records[$i]['id'])
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn-hov.gif';
					}
					else
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn.gif';
					}
					if($records[$i]['contact_name']!='')
					{
						$output.='<div style="display:none;" id="more_bill_addr"><li><address>
						<h5>'.$records[$j]['contact_name'].'</h5>
						<p>'.$records[$j]['address'].'</p>
						<p>'.$records[$j]['city'].'</p>
						<p>'.$records[$j]['state'].'</p>
						<p>'.$records[$j]['zip'].'</p>	
						<a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingaddressdetails&bill_add_id='.$records[$j]['id'].'"><img src="'.$imageicon.'" alt="click"></a>
						</address></li></div>';
					}
					$j++;
				
				}
				if(count($records)>4)
				{
					$output.='<div style="display:block;" style=""id="more_bill_addr_button"><a onclick="viewMoreBillAddress();" href="javascript:void(0);" >More addresses</a></div>';

				}
			}
	
                      $output.='</ul></div>
		 <div class="span8">
                    <div id="myaccount_div">
                    <div class="or_ribbion"><img src="'.$_SESSION['base_url'].'/assets/img/or.png" width="38" height="300" alt="or"></div>
                    <p class="billing_title">Enter a new billing address</p><div style="padding:0 50px">
                    	<form method="POST" action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=validatebillingaddress" name="billingaddress" class="form-horizontal">
                <fieldset>
                  <div class="control-group">
                    <div class="controls">
                      <p class="info_fnt">
                         Fields marked with an <span class="red_fnt">*</span> are required 
                      </p>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input01">Name <span class="red_fnt">*</span></label>
                    <div class="controls"><input type="text"  class="input-xlarge" id="txtname" name="txtname" value="'.$Err->values['txtname'].'"><br /><font color="#FF0000">'.$Err->messages['txtname'].'</font>

                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input01">Company</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge"  name="txtcompany" id="txtcompany" value="'.$Err->values['txtcompany'].'">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input01">Address<span class="red_fnt">*</span></label>
                    <div class="controls">
                     <textarea rows="3" style="width: 273px; height: 75px;" name="txtstreet"  id="txtstreet">'.$Err->values['txtstreet'].'</textarea>
		<br /> <font color="#FF0000">'.$Err->messages['txtstreet'].'</font>
                    </div>
                  </div>
		
		<div class="control-group">
                    <label class="control-label" for="input01"> City <span class="red_fnt">*</span></label>
                    <div class="controls"><input type="text"  class="input-xlarge" id="txtcity" name="txtcity" value="'.$Err->values['txtcity'].'"><br />
                  <br /><font color="#FF0000">'.$Err->messages['txtcity'].'</font>
                    </div>
                  </div>

	 	 <div class="control-group">
                    <label class="control-label" for="input01">SubUrb
			</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge"  name="txtsuburb" id="txtsuburb" value="'.$Err->values['txtsuburb'].'">
                    </div>
                  </div>

  		<div class="control-group">
                    <label class="control-label" for="input01">State/Province<span class="red_fnt">*</span></label>
                    <div class="controls">
                      <input type="text" class="input-xlarge"  name="txtstate" id="txtstate" value="'.$Err->values['txtstate'].'"><br /><font color="#FF0000">'.$Err->messages['txtstate'].'</font>
                    </div>
                  </div>
  		<div class="control-group">
                    <label class="control-label" for="input01">Country<span class="red_fnt">*</span></label>
                    <div class="controls">
                     '.$res.'<br /><font color="#FF0000">'.$Err->messages['selbillcountry'].'</font>
                    </div>
                  </div>

 		  <div class="control-group">
                    <label class="control-label" for="input01">Zip/Postal Code <span class="red_fnt">*</span></label>
                    <div class="controls">
                     <div id="txtHint"><input type="text"  class="input-xlarge" id="txtzipcode" name="txtzipcode" value="'.$Err->values['txtzipcode'].'"><br /><font color="#FF0000">'.$Err->messages['txtzipcode'].'</font></div>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="input01">Phone <span class="red_fnt">*</span> </label>
                    <div class="controls">
                      <input type="text"  class="input-xlarge" id="txtphone" name="txtphone" value="'.$Err->values['txtphone'].'"><br /><font color="#FF0000">'.$Err->messages['txtphone'].'</font>
                    </div>
                  </div>
   		
		
                  <div class="form-actions">
                    <button type="submit" class="btn btn-large btn-inverse">Submit</button>
                  </div>
                </fieldset>
              </form></div>
		</div>
                        </div>
          </div>';
		
		return $output;
	}
	/**
	 * This function is used to show the shipping address.
	 * @param   array  	$records	array of address
	 * @param   array  	$result	        array of country 
	 * @param   array      $Err            contains both error messages and values
	 * @param  integer $shipping_address_id
	 * @return string
	 */	
	function showShippingDetails($records,$result,$Err,$shipping_address_id)
	{

		$obj=new Display_DAddCart();
		$resship=$obj->loadCountryDropDown($result,'selshipcountry',$Err->values['selshipcountry']);

		$output='<div class="row-fluid">
        	<ul class="steps">';
			if($_SESSION['user_id']!='')
			{	
	
				$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=accountinfo"><span>1. My Account</span></a></li>';
			}	
			else
			{
				$output.='<li class="inact"><a href="#"><span>1. Email Login</span></a></li>';
			}		
			$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getaddressdetails"><span>2. Billing Address</span></a></li>
			<li class="act"><a href="#"><span>3. Shipping Address</span></a></li>
			<li class="inact"><a href="#"><span>4. Shipping Method</span></a></li>';
			
			$output.='<li class="inact"><a href="#"><span>5. Order Confirmation</span></a></li>
			
			<li class="inact"><a href="#"><span>6. Payment Details</span></a></li>
				        
		</ul>
       		 </div><div class="row-fluid">
                    <div class="span4">
                         
                      <p class="billing_title">Select from previous address</p>
                      
                      <ul class="addresslist">';

			if(count($records)>0)
			{
				$i=0;
				while ($i < 4)
				{
					if($shipping_address_id==$records[$i]['id'])
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn-hov.gif';
					}
					else
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn.gif';
					}
					if($records[$i]['contact_name']!='')
					{
						$output.='<li><address>
						<h5>'.$records[$i]['contact_name'].'</h5>
						<p>'.$records[$i]['address'].'</p>
						<p>'.$records[$i]['city'].'</p>
						<p>'.$records[$i]['state'].'</p>
						<p>'.$records[$i]['zip'].'</p>	
						<a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingmethod&ship_add_id='.$records[$i]['id'].'"><img src="'.$imageicon.'" alt="click"></a>
						</address></li>';
					}

					$i++;
				}

				$j=4;
				while ($j < count($records))
				{

					if($shipping_address_id==$records[$i]['id'])
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn-hov.gif';
					}
					else
					{
						$imageicon=''.$_SESSION['base_url'].'/assets/img/click-btn.gif';
					}
					if($records[$i]['contact_name']!='')
					{
						$output.='<div style="display:none;" id="more_ship_addr"><li><address>
						<h5>'.$records[$j]['contact_name'].'</h5>
						<p>'.$records[$j]['address'].'</p>
						<p>'.$records[$j]['city'].'</p>
						<p>'.$records[$j]['state'].'</p>
						<p>'.$records[$j]['zip'].'</p>	
						<a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingaddressdetails&ship_add_id='.$records[$j]['id'].'"><img src="'.$imageicon.'" alt="click"></a>
						</address></li></div>';
					}
					$j++;
				
				}
				if(count($records)>4)
				{
					$output.='<div style="display:block;" style=""id="more_ship_addr_button"><a onclick="viewMoreShipAddress();" href="javascript:void(0);" >More addresses</a></div>';

				}

			}
                     $output.='</ul>

        	   </div>	
                    <div class="span8">

                    <div id="myaccount_div">
                    <div class="or_ribbion"><img src="'.$_SESSION['base_url'].'/assets/img/or.png" width="38" height="300" alt="or"></div>
                    <p class="billing_title">Enter a new shipping address</p><div style="padding:0 50px">
                    	<form method="POST" action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=validateshippingaddress" name="register_form" class="form-horizontal">
                <fieldset>
                  <div class="control-group">
                    <div class="controls">
                      <p class="info_fnt">
                         Fields marked with an <span class="red_fnt">*</span> are required 
                      </p>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input01">Name <span class="red_fnt">*</span></label>
                    <div class="controls">
                     <input type="text"  class="input-xlarge" id="txtname" name="txtname" value="'.$Err->values['txtname'].'"><br /><font color="#FF0000">'.$Err->messages['txtname'].'</font>

                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input01">Company</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge"  name="txtcompany" id="txtcompany" value="'.$Err->values['txtcompany'].'">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input01">Address<span class="red_fnt">*</span></label>
                    <div class="controls">
                     <textarea rows="3" style="width: 273px; height: 75px;"  name="txtstreet"  id="txtstreet">'.$Err->values['txtstreet'].'</textarea>
		<br /> <font color="#FF0000">'.$Err->messages['txtstreet'].'</font>
                    </div>
                  </div>
		
		<div class="control-group">
                    <label class="control-label" for="input01"> City <span class="red_fnt">*</span></label>
                    <div class="controls">
                     <input type="text"  class="input-xlarge" id="txtcity" name="txtcity" value="'.$Err->values['txtcity'].'"><br /><font color="#FF0000">'.$Err->messages['txtcity'].'</font>
                    </div>
                  </div>

	  	<div class="control-group">
                    <label class="control-label" for="input01">SubUrb
			</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge"  name="txtsuburb" id="txtsuburb" value="'.$Err->values['txtsuburb'].'">
                    </div>
                  </div>

  		<div class="control-group">
                    <label class="control-label" for="input01">State/Province<span class="red_fnt">*</span></label>
                    <div class="controls">
                      <input type="text" class="input-xlarge"  name="txtstate" id="txtstate" value="'.$Err->values['txtstate'].'"><br /><font color="#FF0000">'.$Err->messages['txtstate'].'</font>
                    </div>
                  </div>
  		<div class="control-group">
                    <label class="control-label" for="input01">Country<span class="red_fnt">*</span></label>
                    <div class="controls">
                     '.$resship.'<br /><font color="#FF0000">'.$Err->messages['selshipcountry'].'</font>
                    </div>
                  </div>

 		  <div class="control-group">
                    <label class="control-label" for="input01">Zip/Postal Code <span class="red_fnt">*</span></label>
                    <div class="controls">
                     <div id="txtHint"><input type="text"  class="input-xlarge" id="txtzipcode" name="txtzipcode" value="'.$Err->values['txtzipcode'].'"><br /><font color="#FF0000">'.$Err->messages['txtzipcode'].'</font></div>
                    </div>
                  </div>

                  <div class="control-group">
                    <label class="control-label" for="input01">Phone <span class="red_fnt">*</span> </label>
                    <div class="controls">
                      <input type="text"  class="input-xlarge" id="txtphone" name="txtphone" value="'.$Err->values['txtphone'].'"><br /><font color="#FF0000">'.$Err->messages['txtphone'].'</font>
                    </div>
                  </div>
   		
		
                  <div class="form-actions">
                    <button type="submit" class="btn btn-large btn-inverse">Submit</button>
                  </div>
                </fieldset>
              </form></div>
		</div>
                        </div>
          </div>';
		
		return $output;
	}
	/**
	 * This function is used to show the shipping method.
	 * @param array $records
	 * @param array $Err
	 * @return string
	 */
	function showShippingMethod($records,$Err)
	{

		$output='<div class="row-fluid">
        	<ul class="steps">';
			if($_SESSION['user_id']!='')
			{	
	
				$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=accountinfo"><span>1. My Account</span></a></li>';
			}	
			else
			{
				$output.='<li class="inact"><a href="#"><span>1. Email Login</span></a></li>';
			}		
			$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getaddressdetails"><span>2. Billing Address</span></a></li>
			<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingaddressdetails"><span>3. Shipping Address</span></a></li>
			<li class="act"><a href="#"><span>4. Shipping Method</span></a></li>';
			
			$output.='<li class="inact"><a href="#"><span>5. Order Confirmation</span></a></li>
			
			<li class="inact"><a href="#"><span>6. Payment Details</span></a></li>
		</ul>
		</div><div class="row-fluid">
                   
                    <div class="span12">

                    <div id="myaccount_div">
             
                    <p class="billing_title">Select Shipping Method</p>';

		if($Err->messages['shipment_id']!='')
		{
				$output.='<div class="alert alert-error">
			<button data-dismiss="alert" class="close" type="button">×</button>
			'.$Err->messages['shipment_id'].'
			</div>';
		}
                    	$output.='<form method="POST" action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=validateshippingmethod" name="register_form" class="form-horizontal">
                <fieldset>
                  <div class="control-group">
                    <div class="controls">
                     
                    </div>
                  </div>';

			if(count($records)>0)
			{
				for($i=0;$i<count($records);$i++)
				{
			
					$output.='<div class="control-group">
					<label class="control-label" for="input01">'.$records[$i]['shipment_name'].' </label>
					<div class="controls">';

					if($_SESSION['shipment_id_selected']==$records[$i]['shipment_id'])
					{
					$output.='<input type="radio"  class="input-xlarge" name="shipment_id" id="shipment_id" value='.$records[$i]['shipment_id'].' checked="checked">';
					}
					else
					{
					$output.='<input type="radio"  class="input-xlarge" name="shipment_id" id="shipment_id" value='.$records[$i]['shipment_id'].' >	';

					}

					$output.='</div>
					</div>';
				}
			}
		
                  $output.='<div class="form-actions">
                    <button type="submit" class="btn btn-large btn-inverse" name="shipping_method">Submit</button>
                  </div>
                </fieldset>
              </form>
		</div>
                 </div>
          </div>';

		return $output;

	}
	
	/**
	 * This function is used to show the order confirmation.
	 * @param   array  	$arr	     array of items
	 * @param   array  	$result      array of country
	 * @param   array  	$taxarray     array of tax
	 * @param  string 	$message      
	 *
	 * @return string
	 */	
	function showOrderConfirmation($arr,$result,$taxarray,$message)
	{
 	

	 $out='<div class="row-fluid">
        	<ul class="steps">';
			if($_SESSION['user_id']!='')
			{	
	
				$out.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=accountinfo&vid='.$_GET['vid'].'"><span>1. My Account</span></a></li>';
			}	
			else
			{
				$out.='<li class="inact"><a href="#"><span>1. Email Login</span></a></li>';
			}
			if($_GET['vid']=='') 
			{		
			$out.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getaddressdetails"><span>2. Billing Address</span></a></li>
			<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingaddressdetails&chk=0"><span>3. Shipping Address</span></a></li>
			<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingmethod&chk=0"><span>4. Shipping Method</span></a></li><li class="act"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=showorderconfirmation"><span>5. Order Confirmation</span></a></li>
			<li class="inact"><a href="#"><span>6. Payment Details</span></a></li>';
			}
			else
			{	
			$out.='<li class="act"><a href="?do=showcart&action=showorderconfirmation&vid='.$_GET['vid'].'"><span>2. Order Confirmation</span></a></li>
			<li class="inact"><a href="#"><span>3. Payment Details</span></a></li>';
			}	        
			$out.='</ul>
		</div>'.$message.'<div id="myaccount_div"><form name="cart" action="?do=showcart&action=validatecoupon"  method="post">
           	 <table class="rt cf" id="rt1">
		<thead class="cf">
			<tr>
				<th></th>
				<th>Gallery View</th>
				<th>Product Name</th>
				<th>Quantity</th>
				<th>Unit Price</th>	
				<th>Sub Total</th>
				
			</tr>
		</thead>
		<tbody>';

		$cnt=count($arr);
		for ($i=0;$i<$cnt;$i++)
		{
			
			$prqty=$arr[$i]['product_qty'];
			
			if ($arr[$i]['soh']<=0)
				$prqty=0;
				

			$original_price=$arr[$i]['product_unit_price'];
			
			if($arr[$i]['product_unit_price']!=0.00)
				$msrp=$arr[$i]['product_unit_price']; 
			elseif($arr[$i]['msrp']!=0.00)
				$msrp=$arr[$i]['msrp']; //$msrp calculated unitpirce
			else
				$msrp=$arr[$i]['msrp1'];
			
			$subtotal[]=$prqty*$msrp;
			
			$total=array_sum($subtotal);
						
			$shippingcost[]=$arr[$i]['shipingamount'];
			$shipping=array_sum($shippingcost);
			
			/*-------------Tax Calculation-------------*/
			
			if ($taxarray['based_on_amount']!='')
			{
				if ($taxarray['based_on_amount']=='subtotal')
				{
					$tax=($taxarray['tax_rate_percent']*$total)/100;
				}
				elseif ($taxarray['based_on_amount']=='subtotal_and_shipping')
				{
					$tax=($taxarray['tax_rate_percent']*($total+$shipping))/100;
				}
			}
			else
			{
				$tax=0;
			}
			
			/*-------------Tax Calculation-------------*/		
			
			$_SESSION['total']=$total;
			$thumbimage=$arr[$i]['thumb_image'];
			$temp=$arr[$i]['thumb_image'];
			$img=explode('/',$temp);

		

			$out.='<tr>

			<td><a href="'.$_SESSION['base_url'].'/index.php?do=addtocart&action=delete&prodid='.$arr[$i]['product_id'].'&id='.$arr[$i]['id'].'"><img src="'.$_SESSION['base_url'].'/assets/img/close_button.gif" alt="close">	</a></td><td>';

			if(file_exists($thumbimage))
			  $out.='<img src="'.$_SESSION['base_url'].'/'.$thumbimage.'" alt="'.$arr[$i]['title'].'" />';
		 	else	  
		  	$out.='<img src="'.$_SESSION['base_url'].'/images/noimage.jpg" alt="'.$arr[$i]['title'].'"/>';

			$out.='</td>
			 <td ><a href="'.$_SESSION['base_url'].'/index.php?do=prodetail&action=showprod&prodid='.$arr[$i]['product_id'].'" name="prodname">'.$arr[$i]['title'].'</a></td>

			<td>'.$arr[$i]['product_qty'].'</td>
			<td><span class="label label-important">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($msrp*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
			<td><span class="label label-inverse">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($subtotal[$i]*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
			<input type="hidden" name="cartid[]"  value="'.$arr[$i]['cart_id'].'" />
			<input type="hidden" name="prodid[]" value='.$arr[$i]['product_id'].' />
			</tr>';
		}
			
		$grandtotal=$total+$shipping+$tax;
		$_SESSION['grandtotal']=$grandtotal;
			
		
			$out.='<tr>
				<td colspan="4" rowspan="4">&nbsp;</td>
			  <td><strong>Sub Total</strong></td>
				<td><span class="label label-success">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($total*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
			</tr>
			<tr>
			  <td><strong>Shipping Amount</strong></td>
				<td><span class="label label-inverse">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($shipping*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
			</tr>
			
			<tr>
			  <td><strong>'.$taxarray['tax_name'].' Tax Applied</strong></td>
				<td><span class="label label-important">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($tax*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
			</tr>
			<tr>
			  <td><strong>Grand Total</strong></td>
				<td><span class="label label-warning">'.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].number_format($grandtotal*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span></td>
			</tr>

			<tr>
			  <td colspan="4">
              <h4>Coupon Code</h4>
              <p>If you have a coupon code enter it in the box below and click \'Go\'.</p>
             <p> <input type="text" name="coupon_code"></p>
            <input type="submit" name="Submit3" value="Go"  class="btn btn-danger" ></td>
			  <td colspan="3" align="center" valign="middle"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=displaypaymentgateways&vid='.$_GET['vid'].'" class="btn btn-inverse">Proceed Checkout</a></td>
		  </tr>


		</tbody>
	</table></form>
        </div>';

	$_SESSION['checkout_amount']=$grandtotal;
	$_SESSION['order_tax']=$taxamount;
			return $out;	
	
	
	}
	/**
	 * This function is used to show the order confirmation.
	 * @param   array  	$onlinearr	 
	 * @param array 	$offlinearr
	 * @param string  $domain
	 *
	 * @return string
	 */
	function displayPaymentGateways($onlinearr,$offlinearr,$domain)
	{
		$output='<div class="row-fluid">
        	<ul class="steps">';
			if($_SESSION['user_id']!='')
			{	
	
				$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=accountinfo&vid='.$_GET['vid'].'"><span>1. My Account</span></a></li>';
			}	
			else
			{
				$output.='<li class="inact"><a href="#"><span>1. Email Login</span></a></li>';
			}
			if($_GET['vid']=='')
			{		
			$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getaddressdetails"><span>2. Billing Address</span></a></li>
			<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingaddressdetails&chk=0"><span>3. Shipping Address</span></a></li>
			<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=getshippingmethod&chk=0"><span>4. Shipping Method</span></a></li><li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=showorderconfirmation"><span>5. Order Confirmation</span></a></li>
			<li class="act"><a href="#"><span>6. Payment Details</span></a></li>';
			}
			else
			{
			$output.='<li class="inact"><a href="'.$_SESSION['base_url'].'/index.php?do=showcart&action=showorderconfirmation&vid='.$_GET['vid'].'"><span>2. Order Confirmation</span></a></li>
			<li class="act"><a href="#"><span>3. Payment Details</span></a></li>';
			}
				        
		$output.='</ul>
		</div><div class="row-fluid">
                    <div class="span12">
                         
                      <p class="billing_title">Choose your mode of payment</p>
                      
                      <div id="myaccount_div">
                      <span class="label label-info">Your Checkout Amount is    '.$_SESSION['currencysetting']['selected_currency_settings']['currency_tocken'].''.number_format($_SESSION['checkout_amount']*$_SESSION['currencysetting']['selected_currency_settings']['conversion_rate'],2).'</span>
                      <div id="paymentid">
                      <h6>Online Payment Gateways</h6>
                       <ul class="payment_det">';
			if(count($onlinearr)>0)
			{
				$cnt=count($onlinearr);
				for ($i=0;$i<$cnt;$i++)
				{
                        	$output.='<li><a>';
				$output.=Display_DAddCart::getPaymentGatewayForms($onlinearr[$i],$domain);
				$output.='</a></li>';
				}

			}
                        	$output.='</ul>
                            <div class="clear"></div>
                            </div>   </div>	';
			if($_GET['vid']=='')
			{		

                   		$output.='<div id="paymentid">
				<h6>Offline Payment Gateways</h6>
				<ul class="payment_det">';
				if(count($offlinearr)>0)
				{
					$cnt=count($offlinearr);
					for ($i=0;$i<$cnt;$i++)
					{
					$output.='<li><a>';
					$output.=Display_DAddCart::getPaymentGatewayForms($offlinearr[$i],$domain);
					$output.='</a></li>';
					}
	
				}
				
					$output.='</ul>
				<div class="clear"></div>
				</div>';
			}

        	    $output.=' </div> </div>';

		return $output;
	}
	/**
	 * This function is used to show the order confirmation.
	 * @param   array  	$arr	     array of records
	 * @param   string  	$domain	     domain name
	 *
	 * @return string
	 */
	function getPaymentGatewayForms($arr,$domain)
	{
		
		//$sucess_url='http://'.$_SERVER['SERVER_NAME'].'/zeuscartv21/?do=paymentgateway&action=success';
		//$cancel_url='http://'.$_SERVER['SERVER_NAME'].'/zeuscartv21/?do=paymentgateway&action=failure';
		
		
		$sucess_url=''.$_SESSION['base_url'].'/index.php?do=paymentgateway&action=success';
		$cancel_url=''.$_SESSION['base_url'].'/index.php?do=paymentgateway&action=failure';				
		
		
		$getMerchantId = new Core_CAddCart();
		$recordSet = $getMerchantId->getPaymentGatewaySettings($arr['gateway_id']);				

		for($i=0;$i<count($recordSet);$i++)
		{
			if($recordSet[$i]['setting_name']=='Merchant ID')
			{
				$merchantid = base64_decode($recordSet[$i]['setting_values']);
			}
		}				
		
		
		//$amount=$_SESSION['checkout_amount'];
		$amount=$_SESSION['checkout_amount']*$_SESSION['currencysetting']['default_currency']['conversion_rate']; //to covert into equivalent dollar values
		
		$payment_html['PayPal']='
					<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
					<input name="cmd" value="_xclick" type="hidden">
                    <input name="business" value="'.$merchantid.'" type="hidden">
                    <input name="item_name" value="'.$_SERVER['SERVER_NAME'].' Check out" type="hidden">
                    <input name="amount" value="'.$amount.'" type="hidden">
                    <input name="no_note" value="once" type="hidden">
                    <input name="currency_code" value="USD" type="hidden">
                    <input name="rm" value="2" type="hidden">

                    <input name="return" value="'.$sucess_url.'&pay_type=1" type="hidden">
                    <input name="cancel_return" value="'.$cancel_url.'" type="hidden">
					<input src="'.$_SESSION['base_url'].'/images/payment/paypal.jpg" name="submit" alt="PayPal" type="image" border="0" style="height:30;width:100px;"></form>';

			$payment_html['e-bullion']='<form name="atip" method="post" action="https://atip.e-bullion.com/process.php">
					<input type="hidden" name="ATIP_STATUS_URL" value="'.$_SERVER['SERVER_NAME'].'">
					<input type="hidden" name="ATIP_STATUS_URL_METHOD" value="POST">
					<input type="hidden" name="ATIP_BAGGAGE_FIELDS" value="">
					<input type="hidden" name="ATIP_SUGGESTED_MEMO" value="">
					<input type="hidden" name="ATIP_FORCED_PAYER_ACCOUNT" value="">
					<input type="hidden" name="ATIP_PAYER_FEE_AMOUNT" value="">
					<input type="hidden" name="ATIP_PAYMENT_URL" value="'.$sucess_url.'&pay_type=2">
					<input type="hidden" name="ATIP_PAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="ATIP_NOPAYMENT_URL" value="'.$cancel_url.'">
					<input type="hidden" name="ATIP_NOPAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="ATIP_PAYMENT_FIXED" value="1">
					<input type="hidden" name="ATIP_PAYEE_ACCOUNT" value="'.$merchantid.'">
					<input type="hidden" name="ATIP_PAYEE_NAME" value="'.$merchantid.'">
					<input type="hidden" name="ATIP_BUTTON" value="1">
					<input type="hidden" name="ATIP_PAYMENT_AMOUNT" value="'.$amount.'" size="10">
					<input type="hidden" name="ATIP_PAYMENT_UNIT" value="1">
					<input type="hidden" name="ATIP_PAYMENT_METAL" value="3">
					<input type="image" name="pay" src="'.$_SESSION['base_url'].'/images/payment/ebullion.jpg" style="height:30;width:100px;"></form>';
					
			$payment_html['e-gold']='<form action="https://www.e-gold.com/sci_asp/payments.asp" method=post>
					<input type=hidden name="PAYMENT_AMOUNT" value="'.$amount.'">
					<input type=hidden name="SUGGESTED_MEMO" value = "'.$_SERVER['SERVER_NAME'].' Check out">
					<input type="hidden" name="PAYEE_ACCOUNT" value="'.$merchantid.'">
					<input type="hidden" name="PAYEE_NAME" value="'.$_SERVER['SERVER_NAME'].'">
					<input type=hidden name="PAYMENT_UNITS" value=1>
					<input type=hidden name="PAYMENT_METAL_ID" value=1>
					<input type="hidden" name="STATUS_URL" value="mailto:'.$_SERVER['SERVER_NAME'].'">
					<input type="hidden" name="NOPAYMENT_URL" value="'.$cancel_url.'">
					<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="PAYMENT_URL" value="'.$sucess_url.'&pay_type=3">
					<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="BAGGAGE_FIELDS" value="PROGL">
					<input type="hidden" name="PROGL" value="01">
					<input type=image src="'.$_SESSION['base_url'].'/images/payment/egold.jpg" style="height:30;width:100px;">
					</form>	';
				
					
					$payment_html['google-checkout']=' <!--<form method="POST" 		
					action="https://sandbox.google.com/checkout/cws/v2/Merchant/'.$merchantid.'/checkoutForm"
     				 accept-charset="utf-8">--><form method="POST" 		
					action="https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/'.$arr['merchant_id'].'"
     				 accept-charset="utf-8">

					  <input type="hidden" name="item_name_1" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'"/>
					  <input type="hidden" name="item_description_1" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'"/>
					  <input type="hidden" name="item_quantity_1" value="1"/>
					  <input type="hidden" name="item_price_1" value="'.$amount.'"/>
					  <input type="hidden" name="item_currency_1" value="USD"/>
					
					  <input type="hidden" name="ship_method_name_1" value="UPS Ground"/>
					  <input type="hidden" name="ship_method_price_1" value="0.00"/>
					
					  <input type="hidden" name="tax_rate" value="0.00"/>
					  <input type="hidden" name="tax_us_state" value="NY"/>
					
					  <input type="hidden" name="_charset_" value=""/>
					<input type="hidden" name="checkout-flow-support.merchant-checkout-flow-support.continue-shopping-url" value="'.$sucess_url.'&pay_type=4">
					
					  <input type="image" name="Google Checkout" alt="Fast checkout through Google"
					src="http://checkout.google.com/buttons/checkout.gif?merchant_id='.$merchantid.'&w=180&h=46&style=white&variant=text&loc=en_US"
					height="46" width="180"/>
					
					</form>';
					
					/*$payment_html['2checkout']=' <form id="form2co" name="form2co" method="post" 		
					action="https://www.2checkout.com/2co/buyer/purchase">
					<input type="hidden" name="sid" value="'.$arr['merchant_id'].'" />
					<input type="hidden" name="cart_order_id" value="100" />
					<input type="hidden" name="total" value="'.$amount.'" /><input type="hidden" name="demo" value="Y" />
					<input type="hidden" name="fixed" value="Y" /><input type="hidden" name="return_url" value="'.$sucess_url.'" 
					/>
					<input type="hidden" name="lang" value="en" />
					<input type="hidden" name="card_holder_name" value="" /><input type="image" src="../logo_2co.gif" 
					name="submit" alt="2checkout" />
					</form>';*/
					
					$payment_html['2checkout']=' <form id="form2co" name="form2co" method="post" 		
					action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=show2checkout">
					<input type="hidden" name="sid" value="'.$merchantid.'" />
					<input type="hidden" name="cart_order_id" value="100" />
					<input type="hidden" name="total" value="'.$amount.'" /><!--<input type="hidden" name="demo" value="Y" />-->
		
					<INPUT TYPE="HIDDEN" NAME="x_test_request" VALUE="TRUE">
					<input type="hidden" name="fixed" value="Y" /><input type="hidden" name="return_url" value="'.$sucess_url.'" 
					/>
					<input type="hidden" name="lang" value="en" />
					<input type="hidden" name="card_holder_name" value="" /><input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/2checkout.gif" 
					name="submit" alt="2checkout" style="height:30;width:100px;"/>
					</form>';
					
					$payment_html['Authorize.net']=' <form id="form2co" name="form2co" method="post" 		
					action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=showauthorizenet">
					<INPUT TYPE="HIDDEN" NAME="x_test_request" VALUE="TRUE">
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/authorize.gif" 
					name="submit" alt="Authorize.net" style="height:30;width:100px;" />
					</form>';
					
					$payment_html['worldpay']=' <form id="worldpay" name="worldpay" method="post" 		
					action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=showworldpay">
					<input type=hidden name="instId" value="'.$merchantid.'">
					<input type=hidden name="cartId" value=" 122 "> 
					<input type=hidden name="amount" value="'.$amount.'">
					<input type=hidden name="currency" value="USD">
					<input type=hidden name="desc" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'">
					<!--<input type=hidden name="testMode" value="100"> -->
					<input type="hidden" name="MC_callback" value="'.$sucess_url.'" />
					
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/worldpay.gif" name="submit" alt="WorldPay" style="height:30;width:100px;">
					</form>';
					
					/*$payment_html['worldpay']=' <form action="https://select.worldpay.com/wcc/purchase" method=POST>
					<input type=hidden name="instId" value="'.$arr['merchant_id'].'">
					<input type=hidden name="cartId" value=" 122 "> 
					<input type=hidden name="amount" value="'.$amount.'">
					<input type=hidden name="currency" value="USD">
					<input type=hidden name="desc" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'">
					<input type=hidden name="testMode" value="100"> 
					<input type="hidden" name="MC_callback" value="'.$sucess_url.'" />
					
					<input type="image" src="../worldpay.gif" name="submit" alt="WorldPay">
					</form>
					';*/
					if (!(isset($_SESSION['digitalproducts'])) || ($_SESSION['digitalproducts']==0))
					{
					$payment_html['Pay in Store']=' <form action="'.$sucess_url.'&pay_type=8" method=POST>
					
					<input type=hidden name="amount" value="'.$amount.'">
					<input type=hidden name="currency" value="USD">
					<input type=hidden name="desc" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'">
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/payinstore.gif" name="submit" alt="Pay in Store" style="height:30;width:100px;">
					</form>
					';
					}
					if (!(isset($_SESSION['digitalproducts'])) || ($_SESSION['digitalproducts']==0))
					{
					$payment_html['Cash on Delivery']=' <form action="'.$sucess_url.'&pay_type=9" method=POST>
					
					<input type=hidden name="amount" value="'.$amount.'">
					<input type=hidden name="currency" value="USD">
					<input type=hidden name="desc" value="Payment For Shopping In '.$_SERVER['SERVER_NAME'].'">
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/cashondelivery.gif" name="submit" alt="Cash On Delivery" style="height:30;width:100px;">
					</form>
					';
					}
					
					$payment_html['Paymate'] = '
					<form action="https://www.paymate.com/PayMate/ExpressPayment?mid='.$merchantid.'" method="post">
					<input type="hidden"  name="amt" value="'.$amount.'">
					<input type="hidden"  name="amt_editable" value="N">
					<input type="hidden"  name="currency" value="USD">
					<input type="hidden"  name="ref" value="'.$_SERVER['SERVER_NAME'].' Check out">			
					<input type="hidden"  name="return" value="'.$sucess_url.'&pay_type=10">
					<input type="hidden"  name="popup" value="'.$cancel_url.'">
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/paymate.gif" name="submit" alt="Pay with Paymate Express" style="height:30;width:100px;">
					</form>';
					
					
					$payment_html['Moneybookers']='<form action="https://www.moneybookers.com/app/payment.pl" target="_blank" method="post" >
					<input type="hidden" name="pay_to_email" value="'.$merchantid.'">
					<input type="hidden" name="merchant_id" value="'.$arr['merchant_id'].'">					
					<input type="hidden" name="return_url" value="'.$sucess_url.'&pay_type=11">
					<input type="hidden" name="cancel_url" value="'.$cancel_url.'">
					<input type="hidden" name="language" value="EN">
					<input type="hidden" name="amount" value="'.$amount.'">
					<input type="hidden" name="currency" value="USD">
					<input type="image" src="'.$_SESSION['base_url'].'/images/payment/moneybookers.jpg" name="submit" alt="Money Bookers" style="height:30;width:100px;">
					</form>';
						
					
					$payment_html['PSIGate']='<!--<FORM ACTION="https://devcheckout.psigate.com/HTMLPost/HTMLMessenger" METHOD=post>--><FORM ACTION="https://checkout.psigate.com/HTMLPost/HTMLMessenger" METHOD=post>
					<INPUT TYPE=hidden NAME="MerchantID" VALUE="'.$merchantid.'">
					<INPUT TYPE=hidden NAME="ThanksURL" VALUE="'.$sucess_url.'&pay_type=12">
					<INPUT TYPE=hidden NAME="NoThanksURL" VALUE="'.$cancel_url.'">
					<INPUT TYPE=hidden NAME="PaymentType" VALUE="CC">
					<!--<INPUT TYPE=hidden NAME="TestResult" VALUE="1">-->					
					<INPUT TYPE=hidden NAME="OrderID" VALUE="">
					<INPUT TYPE=hidden NAME="SubTotal" VALUE="'.$amount.'">
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/psigate.gif" name="submit" alt="PSI Gate" style="height:30;width:100px;">
					</FORM>';	
					
					$payment_html['Strompay']='<form method="post" action="https://www.stormpay.com/stormpay/handle_gen.php">
					<input type="hidden" name="generic" value="1">
					<input type="hidden" name="payee_email" value="'.$merchantid.'" >
					<input type="hidden" name="product_name" value="Cart">
					<input type="hidden" name="user_id" value=1>
					<input type="hidden" name="amount" value="'.$amount.'">
					<input type="hidden" name="quantity" value="1">
					<input type="hidden" name="require_IPN" value="1">
					<input type="hidden" name="notify_URL" value="'.$domain.'">
					<input type="hidden" name="return_URL" value="'.$sucess_url.'&pay_type=13">
					<input type="hidden" name="cancel_URL" value="'.$cancel_url.'">
					<input type="hidden" name="subject_matter" value="Cart Payment">
					<input type=image src="'.$_SESSION['base_url'].'/assets/img/payment/strompay.jpg"  alt="Strompay" style="height:30;width:100px;">
					</form>';
					
					/*$payment_html['Alertpay']='<form action="https://www.alertpay.com/PayProcess.aspx" method="post">
					<input type="hidden" name="ap_purchasetype" value="Item">
					<input type="hidden" name="ap_merchant" value="'.$arr['merchant_id'].'">
					<input type="hidden"  name="ap_itemname" value="PTYW">
					<input type="hidden"  name="ap_currency" value="USD">
					<input type="hidden"  name="ap_returnurl" value="'.$sucess_url.'&pay_type=14">
					<input type="hidden"  name="ap_quantity" value="1">
					<input type="hidden" name="ap_description" value="PTYW">
					<input type="hidden"  name="ap_amount" value="0.26">
					<input type="hidden"  name="ap_cancelurl" value="'.$cancel_url.'">
					<input type="image" src="images/payment/alertpay.jpeg" alt="Alertpay">
					</form>';
					*/
					$payment_html['Alertpay']="<form action='https://www.alertpay.com/PayProcess.aspx' method='post'>
					<input type='hidden' name='ap_purchasetype' value='Item'>
					<input type='hidden' name='ap_merchant' value='".$merchantid."'>
					<input type='hidden'  name='ap_itemname' value='PTYW'>
					<input type='hidden'  name='ap_currency' value='USD'>
					<input type='hidden'  name='ap_returnurl' value='".$sucess_url."&pay_type=14'>
					<input type='image' src='".$_SESSION['base_url']."/images/payment/alertpay.jpeg' style='height:30;width:100px;'>
					<input type='hidden'  name='ap_quantity' value='1'>
					<input type='hidden' name='ap_description' value='PTYW'>
					<input type='hidden'  name='ap_amount' value='".$amount."'>
					<input type='hidden'  name='ap_cancelurl' value='".$cancel_url."'>
					</form>";
					/**
					* Yourpay connect settings:
					* 1. You need to log into Yourpay.
   					* 2. After you login, click on Customization at the top.
  					* 3. Then click on "Configure your Yourpay Connect."
   					* 4. Enter the URL address for the order page on your web site or the very last page during checkout for your shopping cart.
      				* You can enter multiple address but they need to be separated by a space.
      				* The Order Submission Form field has a limit of 250 characters.
					* By default Yourpay Connect will display the customer a confirmation page that they can print out for their records.
    				* If you do not create your own thank you and sorry pages then you can enter the address for your web site.
     					 For example: http://www.yourdomain.com
    				* You only need to enable "URL is a CGI script" if you want Yourpay to post the data back to your website so you can collect the information using a scripting language like PHP or ASP.
   					* If you do not want customers to see the Yourpay confirmaiton page then you need to enable the auto forwarding option.
					*/
					$payment_html['Yourpay']='<!--<form action="https://www.staging.linkpointcentral.com/lpcentral/servlet/lppay" method="post">--><form action="https://secure.linkpt.net/lpcentral/servlet/lppay" method="post">
					<input type="hidden" name="mode" value="fullpay">
					<input type="hidden" name="chargetotal" value="'.$amount.'">
					<input type="hidden" name="storename" value="'.$merchantid.'">
					<input type="hidden" name="txntype" value="sale">
					<input type="hidden" name="comments" value="'.$domain.'-Buy cart">
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/yourpay.jpeg" alt="Yourpay" style="height:30;width:100px;">
					<!--<input type="submit" name="btnup" value="Yourpay">-->
					</form>';
					
					
					$payment_html['iTransact'] = '<form method="post" 	
					action="https://secure.paymentclearing.com/cgi-bin/mas/split.cgi" >
					<input type="hidden" name="home_page" value="'.$domain.'">
					<input type="hidden" name="vendor_id" value="'.$merchantid.'">
					<input type="hidden" name="mername" value="Buy Cart">
					<input type="hidden" name="ret_addr" value="'.$sucess_url.'&pay_type=16">
					<input type="hidden" name="1-qty" value="1" />
					<input type="hidden" name="ret_mode" value="post">
					<input type="hidden" name="splitType" value="split" />
					<input type="hidden" name="1-cost" value="'.round($amount).'" />
					<input type="hidden" name="1-desc" value="Item" />
					<input type="hidden" name="first_name" value="venkat" />
					<input type="hidden" name="last_name" value="venkat"/>
					<input type="hidden" name="address" value="vilacheri"/>
					<input type="hidden" name="city" value="madurai"/>					
					<input type="hidden" name="phone" value="34343434"/>
					<input type="hidden" name="country" value="USA"/>
					<input type="hidden" name="email" value="'.$merchantid.'"/>
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/itransact.gif" alt="submit securely"  style="height:30;width:100px;"/>
					</form>';							
					
					
					
					$payment_html['Bluepay']=' <form id="formbluepay" name="formbluepay" method="post" 		
					action="'.$_SESSION['base_url'].'/index.php?do=showcart&action=showbluepay">					
					<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/bluepay.jpeg" name="submit" alt="BluePay" style="height:30;width:100px;"/>
					</form>';			
					if($arr['gateway_id'] == '17')
					$_SESSION['bluepaydetails'] = $merchantid.'|'.$sucess_url.'|'.$cancel_url;	
					
					$payment_html['Safepay'] = '<form action="https://www.safepaysolutions.com/index.php" method="post">
             		<input type="hidden" name="_ipn_act" value="_ipn_payment">
		            <input type="hidden" name="fid" value="'.$merchantid.'">
		            <input type="hidden" name="itestmode" value="off">
              		<input type="hidden" name="notifyURL" value="'.$sucess_url.'&pay_type=18">
		            <input type="hidden" name="returnURL" value="'.$sucess_url.'&pay_type=18" >
	                <input type="hidden" name="cancelURL" value="'.$cancel_url.'" >
 				    <input type="hidden" name="notifyEml" value="">
                    <input type="hidden" name="iowner" value="">
                    <input type="hidden" name="ireceiver" value="">
                    <input type="hidden" name="iamount" value="'.round($amount).'">
 		            <input type="hidden" name="itemName" value="Deposit Amount">
              		<input type="hidden" name="itemNum" value="">
              		<input type="hidden" name="idescr" value="">
             	 	<input type="hidden" name="idelivery" value="">
              		<input type="hidden" name="iquantity" value="">
             		<input type="hidden" name="imultiplyPurchase" value="y">
              		<input type="hidden" name="colortheme" value="LightBlueYellow">
              		<input type="image" src="'.$_SESSION['base_url'].'/assets/img/payment/safepay.gif" alt="Pay through SafePay Solutions" style="height:30;width:100px;">
		            </form>';							
					
					
			return $payment_html[$arr['gateway_name']];
	}
	
	
	
	
	
	
}	
?>
