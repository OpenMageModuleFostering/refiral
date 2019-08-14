<?php
/**
 * Refiral_Campaign extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Refiral
 * @package        Refiral_Campaign
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Campaign default helper
 *
 * @category    Refiral
 * @package     Refiral_Campaign
 * @author      Ultimate Module Creator
 */
class Refiral_Campaign_Helper_Data extends Mage_Core_Helper_Abstract {
    public function isActive()
    {
        $campaignActive = Mage::getStoreConfig('general/refiral_campaign/active');
        if(!empty($campaignActive))
            return true;
        else
            return false;
    }
    
    public function getKey()
    {
        return Mage::getStoreConfig('general/refiral_campaign/apikey');
    }
    
    public function getScript()
    {
        $request = Mage::app()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $script = "<script>var apiKey = '".$this->getKey()."';</script>"."\n";
        if ($module == 'checkout' && $controller == 'onepage' && $action == 'success')
        {
            $order = new Mage_Sales_Model_Order();
            $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
            $order->loadByIncrementId($orderId);    // Load order details
            $order_total = round($order->getGrandTotal(), 2); // Get grand total
            $order_coupon = $order->getCouponCode();    // Get coupon used
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
            $scriptAppend = "<script>invoiceRefiral('$order_total', '$order_total', '$order_coupon', '$cartInfoString', '$order_name', '$order_email', '$orderId');</script>"."\n";
            $script .= '<script>var showButton = false;</script>'."\n";
        }
        else
        {
            $scriptAppend = '';
            $script .= '<script>var showButton = true;</script>'."\n";
        }
        $script .= '<script src="//rfer.co/api/v1/js/all.js"></script>'."\n";
        return $script.$scriptAppend;
    }
}
