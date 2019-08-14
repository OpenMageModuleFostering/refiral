<?php
class Refiral_Campaign_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isActive()
    {
		$campaignActive = Mage::getStoreConfig('general/campaign/active');
		if(!empty($campaignActive))
			return true;
		else
			return false;
    }
	
    public function getKey()
    {
		return Mage::getStoreConfig('general/campaign/apikey');
    }
	
	public function getScript()
	{
		$request = Mage::app()->getRequest();
		$module = $request->getModuleName();
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$script = "<script>var apiKey = '".$this->getKey()."';</script>";
		if ($module == 'checkout' && $controller == 'onepage' && $action == 'success')
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
			$cartInfoString = serialize($cartInfo);
			$order_name = $order->getCustomerName(); // Get customer's name
			$order_email = $order->getCustomerEmail(); // Get customer's email id
				
			// Call invoiceRefiral function
			$scriptAppend = "<script>invoiceRefiral('$order_total', '$order_total', '$order_coupon', '$cartInfoString', '$order_name', '$order_email');</script>";
			$script .= '<script>var showButton = false;</script>';
		}
		else
		{
			$scriptAppend = '';
			$script .= '<script>var showButton = true;</script>';
		}
		$script .= '<script src="//rfer.co/api/v0/js/all.js"></script>';
		return $script.$scriptAppend;
	}
}
