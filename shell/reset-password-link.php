<?php

	require_once ("../app/Mage.php");

	umask(0);
	Mage::app("default");

	/* Get customer model, run a query */
	$collection = Mage::getModel('customer/customer')
		->getCollection()
		->addAttributeToSelect('*');

	$out = '';

	foreach ($collection as $customer) {
		$newResetPasswordLinkToken =  Mage::helper('customer')->generateResetPasswordLinkToken();
		$customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
		$out .= '"'.$customer->getFirstname() . ' ' . $customer->getLastname() . '",';
		$out .= '"' . $customer->getEmail() . '",';
		$out .= '"' . Mage::getBaseUrl().'customer/account/resetpassword/?id='.$customer->getId().'&token='.$newResetPasswordLinkToken . '"';
		$out .= "\n";
	}

	file_put_contents('../var/export/reset-password-link.csv', $out);

?>
