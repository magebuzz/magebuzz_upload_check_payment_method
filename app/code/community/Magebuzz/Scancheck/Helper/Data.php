<?php

class Magebuzz_Scancheck_Helper_Data extends Mage_Core_Helper_Abstract {
  public function getAttachmentFolder($filename) {
    $attachment_path = Mage::getBaseDir('media') . DS . 'uploads' . DS . $filename;
    if (!is_dir($attachment_path)) {
      try {
        chmod(Mage::getBaseDir('media'), 0777);
        mkdir($attachment_path);
        chmod($attachment_path, 0777);
        return $attachment_path;

      } catch (Exception $e) {
        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
      }
    }
    return $attachment_path;
  }
}