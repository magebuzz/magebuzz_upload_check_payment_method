<?php

class Magebuzz_Scancheck_Block_Form_Upload extends Mage_Payment_Block_Form {
  protected function _construct() {
    parent::_construct();
    $this->setTemplate('scancheck/form/upload.phtml');
  }
}