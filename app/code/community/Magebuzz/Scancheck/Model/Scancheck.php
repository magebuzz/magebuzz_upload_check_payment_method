<?php

class Magebuzz_Scancheck_Model_Scancheck extends Mage_Payment_Model_Method_Abstract {
  protected $_code = 'scancheck';
  protected $_formBlockType = 'scancheck/form_upload';
  protected $_infoBlockType = 'scancheck/info_upload';


  public function assignData($data) {
    if (!($data instanceof Varien_Object)) {
      $data = new Varien_Object($data);
    }
    $info = $this->getInfoInstance();
    $info->setFileCheck($data->getFileCheck());
    return $this;
  }


  public function validate() {
    parent::validate();

    $info = $this->getInfoInstance();

    $fileupload = $info->getFileCheck();
    if ($errorMsg) {
      Mage::throwException($errorMsg);
    }
    return $this;
  }
}