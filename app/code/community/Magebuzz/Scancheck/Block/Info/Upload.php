<?php

class Magebuzz_Scancheck_Block_Info_Upload extends Mage_Payment_Block_Info {
  protected function _construct() {
    parent::_construct();
    $this->setTemplate('scancheck/sales/order/info.phtml');
  }
}