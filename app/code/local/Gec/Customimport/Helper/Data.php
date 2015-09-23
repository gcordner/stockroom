<?php
/**
#    Copyright (C) 2013 Global Era (http://www.globalera.com). All Rights Reserved
#    @author Serenus Infotech <magento@serenusinfotech.com>
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Affero General Public License as
#    published by the Free Software Foundation, either version 3 of the
#    License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Affero General Public License for more details.
#
#    You should have received a copy of the GNU Affero General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

class Gec_Customimport_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function writeCustomLog($msg, $path = null) {
		if($path == null)
		{
			$path = Mage::getBaseDir('log').'/customimport-default.log';
		}
		error_log("[".date('Y:m:d H:i:s', time())."] : ".print_r($msg, true)."<br/> \r\n", 3, $path);			
	}
	
	public function sendLogEmailAndRemoveLog($logPath)
	{
		$logMessage = file_get_contents($logPath);
		if($logMessage) {						
			$finalImportStatus = null;
			$logSubject = 'Custom Import Log Report '.date('Y:m:d H:i:s', time()).' (UTC)';
			
			$emailTemplate = Mage::getModel('core/email_template')->loadDefault('import_status');

			//Getting the Store E-Mail Sender Name.
			$senderName = Mage::getStoreConfig('trans_email/ident_general/name');

			//Getting the Store General E-Mail.
			$senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');

			//Variables for Confirmation Mail.
			$emailTemplateVariables = array();
			$emailTemplateVariables['msgcol'] = $logMessage;
			$emailTemplateVariables['finalstatus'] = $finalImportStatus;
			//Appending the Custom Variables to Template.
			$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
			// Sender Email
			$reciverEmail= Mage::getStoreConfig('trans_email/ident_custom1/email');
			//Sending E-Mail to Customers.
			$mail = Mage::getModel('core/email')
			->setToName($senderName)
			->setToEmail($reciverEmail)
			->setBody($processedTemplate)
			->setSubject($logSubject)
			->setFromEmail($senderEmail)
			->setFromName($senderName)
			->setType('html');
			try{
				//Confimation E-Mail Send
				$mail->send();
				//unlink($logPath);
			}
			catch(Exception $error)
			{
				Mage::getSingleton('core/session')->addError($error->getMessage());
				return false;
			}	
		} else {
			Mage::getSingleton('core/session')->addError('there were no log report generated...!');
				return false;
		}
			
	}
	
	public function getCurrentLocaleDateTime($defaultUTCDate)
	{
		/*echo "Current Timezone : ".Mage::getStoreConfig('general/locale/timezone')."<br/>";
		$defaultUTCDate = '09/25/2015 22:35:48';
		echo "defaultUTCDate : ".$defaultUTCDate."<br/>";
		$storeTimezoneDate = date('m/d/Y H:m:s', Mage::getModel('core/date')->timestamp(strtotime($defaultUTCDate))); 
		echo "storeTimezoneDate : ".$storeTimezoneDate."<br/>";
		die('here');*/
		return date('Y-m-d H:i:s', Mage::getModel('core/date')->timestamp(strtotime($defaultUTCDate))); 
	}
}
