<?php
     namespace PhpSolutions\File;
     class Upload {
           protected $destination;
           protected $max = 104857600;
           protected $messages = [];
           protected $permitted = [
              'image/gif',
              'image/jpeg',
              'image/pjpeg',
              'image/png'
               ];

          public function __construct($path) {
              if (!is_dir($path) || !is_writable($path)) {
                  throw new \Exception("$path must be a valid,writable directory.");
                  }
                 $this->destination = $path;
              }
          
          public function upload() {
              $uploaded = current($_FILES);
              if ($this->checkFile($uploaded)) {
                  $this->moveFile($uploaded);
              }
          }
          
          protected function checkFile($file) {
               $accept = true;
               if ($file['error'] != 0) {
                   $this->getErrorMessage($file);
                   // stop checking if no file submitted
                   if ($file['error'] == 4) {
                       return false;
                       } else {
                           $accept = false;
                           }
                }
               if (!$this->checkSize($file)) {
                   $accept = false;
               }
               if (!$this->checkType($file)) {
                   $accept = false;
               }
               return $accept;
          }
 
          protected function moveFile($file) {
              $success = move_uploaded_file($file['tmp_name'],$this->destination . $file['name']);
              if ($success) {
                  $result = $file['name'] . ' was uploaded successfully';
                  $this->messages[] = $result;
              } else {
                    $this->messages[] = 'Could not upload ' . $file['name'];
                }
           }

          protected function getErrorMessage($file) {
              switch($file['error']) {
                  case 1:
                  case 2:
                      $this->messages[] = $file['name'] . ' is too big: (max: ' .
                          $this->getMaxSize() . ').';
                      break;
                  case 3:
                      $this->messages[] = $file['name'] . ' was only partially uploaded.';
                      break;
                  case 4:
                      $this->messages[] = 'No file submitted.';
                      break;
                  default:
                      $this->messages[] = 'Sorry, there was a problem uploading ' .$file['name'];
                      break;
              }
          }
         
          protected function checkSize($file) {
              if ($file['error'] == 1 || $file['error'] == 2 ) {
                   return false;
              } elseif ($file['size'] == 0) {
                   $this->messages[] = $file['name'] . ' is an empty file.';
                   return false;
              } elseif ($file['size'] > $this->max) {
                   $this->messages[] = $file['name'] . ' exceeds the maximum size for a file (' . $this->getMaxSize() . ').';
                   return false;
              } else { 
                   return true;
                }
          }

          protected function checkType($file) {
              if (in_array($file['type'], $this->permitted)) {
                  return true;
              } else {
                  $this->messages[] = $file['name'] . ' is not permitted type of file.';
                  return false;
                }
          }

          public function getMaxSize() {
              return number_format($this->max/1024, 1) . ' KB';
          }

      }
