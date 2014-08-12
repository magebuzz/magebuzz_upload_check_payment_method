<?php

class Magebuzz_Scancheck_Model_Order extends Mage_Core_Model_Abstract {
  public function _construct() {
    parent::_construct();
    $this->_init('scancheck/order');
  }

  public function saveFile($order_id, $filename, $type) {
    $this->getResource()->deleteFile($order_id, $filename, $type);
    $this->setFilename($filename);
    $this->setOrderId($order_id);
    $this->setType($type);
    $this->save();
  }

  public function getFiles($order) {
    $collection = Mage::getModel('scancheck/order')->getCollection();
    $collection->addFieldToFilter('order_id', $order->getId());
    return $collection;
  }
}