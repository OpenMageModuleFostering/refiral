<?php
/**
 * @author Refiral
 * @copyright Copyright (c) 2014 Refiral
 * @license GPLv2
 */

class Refiral_Campaign_Block_Campaign extends Mage_Core_Block_Template
{
	protected function _toHtml()
	{
		$campaignActive = Mage::getStoreConfig('general/campaign/active');
		// Check if campaign is enabled
		if(!empty($campaignActive)) 
		{
			// Get API Key
			$apiKey = Mage::getStoreConfig('general/campaign/apikey');
			
			/** Make sure jQuery is included **/
			?>
			<script type="text/javascript">
		   	if ( (typeof jQuery === 'undefined') && !window.jQuery ) 
		   	{
	       		document.write(unescape("%3Cscript type='text/javascript' src='http://code.jquery.com/jquery-latest.min.js'%3E%3C/script%3E"));
		    } 
		    else 
		    {
		        if((typeof jQuery === 'undefined') && window.jQuery) 
		        {
		            jQuery = window.jQuery;
		        } 
		        else if((typeof jQuery !== 'undefined') && !window.jQuery) 
		        {
		            window.jQuery = jQuery;
		        }
		    }
		    </script>
			<script>
				var $j = jQuery.noConflict();
			</script>
		    <?php
			/**********************************/
			$request = $this->getRequest();
			$module = $request->getModuleName();
			$controller = $request->getControllerName();
			$action = $request->getActionName();
			echo "<script>var apiKey = '$apiKey';</script>";
			if ($module == 'checkout' && $controller == 'onepage' && $action == 'success'){
				echo '<script>var showButton = false;</script>';
			}
			else
				echo '<script>var showButton = true;</script>';
			echo '<script src="http://refiral.com/api/all.js"></script>';
	    }
    }
}