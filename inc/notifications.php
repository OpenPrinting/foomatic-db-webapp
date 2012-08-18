<?php
  
  class Notifications {
    
    private $database;
    private $currentUser;
    
    public function __construct($database, $currentUser) {
      
      $this->database = $database;
      $this->currentUser = $currentUser;
    }
    
    public function getSettings() {
      $result = $this->database->query('SELECT web_notifications.* FROM web_notifications INNER JOIN web_user ON web_user.id = web_notifications.web_user_id WHERE web_user.username = ?', $this->currentUser);
      
      if ($result->numRows() > 0) {
        return $result->getRow();
      }
      
      return array('email' => null, 'printer_queue' => 0, 'printer_noqueue' => 0, 'driver_queue' => 0, 'driver_noqueue' => 0);
    }
    
    public function saveSettings($settings) {
      if (empty($settings['email'])) {
        return false;
      }
      
      $id = $this->_getCurrentUserId();
      
      if (!$this->_userHasSettings($id)) {
        $this->database->query('INSERT INTO web_notifications (web_user_id, email, printer_queue, printer_noqueue, driver_queue, driver_noqueue) VALUES (?,?,?,?,?,?)',
          $id,
          $settings['email'],
          !empty($settings['printer_queue']),
          !empty($settings['printer_noqueue']),
          !empty($settings['driver_queue']),
          !empty($settings['driver_noqueue'])
        );
      } else {
        $this->database->query('UPDATE web_notifications SET email = ?, printer_queue = ?, printer_noqueue = ?, driver_queue = ?, driver_noqueue = ? WHERE web_user_id = ?',
          $settings['email'],
          !empty($settings['printer_queue']),
          !empty($settings['printer_noqueue']),
          !empty($settings['driver_queue']),
          !empty($settings['driver_noqueue']),
          $id
        );
      }
      
      return true;
    }
    
    private function _userHasSettings($id) {
      $result = $this->database->query('SELECT web_notifications.email FROM web_notifications WHERE web_notifications.web_user_id = ?', $id);
      return $result->numRows() > 0;
    }
    
    private function _getCurrentUserId() {
      $result = $this->database->query('SELECT web_user.id FROM web_user WHERE web_user.username = ?', $this->currentUser);
      $row = $result->getRow();
      
      return $row['id'];
    }
    
  }
  
?>