<?php

class Magebuzz_Scancheck_Model_Observer {
  const XML_PATH_EMAIL_RECIPIENT = 'payment/scancheck/mailing_address';
  const XML_PATH_EMAIL_SENDER = 'payment/scancheck/email_admin_sender';
  const XML_PATH_EMAIL_TEMPLATE = 'payment/scancheck/email_admin_template';

  public function saveQuoteAfter($evt) {
    $quote = $evt->getQuote();

    $post = Mage::app()->getRequest()->getPost();

    if (isset($post['file_upload_path'])) {
      $quote_id = $quote->getId();
      $filename = $post['file_upload_path'];
      $type = $post['file_upload_type'];
      Mage::log($quote_id . 'xx' . $filename);
      Mage::getModel('scancheck/file')->saveFile($quote_id, $filename, $type);
    }

  }

  public function placeOrderAfter($evt) {
    $order = $evt->getOrder();
    $quote = $evt->getQuote();

    $quote_id = $quote->getId();
    $order_id = $order->getId();

    $collection = Mage::getModel('scancheck/file')->getCollection();
    $collection->addFieldToFilter('quote_id', $quote_id);

    Mage::log('Observer Place Order After Quote ID:' . $quote_id);

    foreach ($collection as $object) {
      Mage::getModel('scancheck/order')->saveFile($order_id, $object->getFilename(), $object->getType());
      $object->delete();
    }
    // send mail to admin
    $translate = Mage::getSingleton('core/translate');
    $translate->setTranslateInline(FALSE);
    $customerName = $order->getCustomerFirstname() . ' ' . $order->getCustomerLastname();
    $increment_id = $order->getIncrementId();
    $timeplaceorder = $order->getCreatedAtFormated('long');
    $fileUrl = Mage::getBaseUrl('media') . "uploads" . $object->getFilename();
    $imagePath = Mage::getBaseDir('media') . DS . "uploads" . DS . $object->getFilename();

    $fileName = basename($imagePath);
    try {
      $mailTemplate = Mage::getModel('core/email_template');
      /* Add Attachment */
      $mailTemplate->getMail()->createAttachment(file_get_contents($imagePath))->filename = $fileName;
      $mailTemplate->setDesignConfig(array('area' => 'frontend'))->setReplyTo($order->getCustomerEmail())->sendTransactional(Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER), Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT), null, array('increment_id' => $increment_id, 'place_order_time' => $timeplaceorder, 'file_attach_url' => $fileUrl, 'customer_name' => $customerName));

      if (!$mailTemplate->getSentSuccess()) {
        throw new Exception();
      }
    } catch (Exception $e) {
      echo $e->getMessage();
      return;
    }
    $translate->setTranslateInline(TRUE);
  }
}