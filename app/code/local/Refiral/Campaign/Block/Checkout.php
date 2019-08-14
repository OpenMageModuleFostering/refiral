<?php
/**
 * @author Refiral
 * @copyright Copyright (c) 2014 Refiral
 * @license GPLv2
 */

class Refiral_Campaign_Block_Checkout extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
		$campaignActive = Mage::getStoreConfig('general/campaign/active');
		// Check if campaign is enabled
		if(!empty($campaignActive))
		{
	    	$order = new Mage_Sales_Model_Order();
	    	$orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
	    	$order->loadByIncrementId($orderId);	// Load order details
			$order_total = round($order->getGrandTotal(), 2); // Get grand total
			$order_coupon = $order->getCouponCode();	// Get coupon used
			$items = $order->getAllItems(); // Get items info
			$cartInfo = array();
			// Convert object to string
			foreach($items as $item) {
				$product = Mage::getModel('catalog/product')->load($item->getProductId());
				$name = $item->getName();
				$qty = $item->getQtyToInvoice();
				$cartInfo[] = array('id' => $item->getProductId(), 'name' => $name, 'quantity' => $qty);
			}
			$cartInfo = serialize($cartInfo);
			$order_name = $order->getCustomerName(); // Get customer's name
			$order_email = $order->getCustomerEmail(); // Get customer's email id
			
			// Call invoiceRefiral function
			echo "<script>invoiceRefiral('$order_total', '$order_total', '$order_coupon', '$cartInfo', '$order_name', '$order_email');</script>";
		}
    }

}