<?php
  
  class Notifications {
    
    private $configuraton;
    private $database;
    
    private $mailer;
    
    public function __construct(&$configuraton, &$database) {
      $this->configuration = &$configuraton;
      $this->database = &$database;
    }
    
    public function notifyDriverUpload($driver, $user, $queue = true) {
      return $this->_notifyUpload(
        'driver_' . (($queue)? '' : 'no') . 'queue',
        'New driver uploaded to ' . (($queue)? 'the queue' : 'openprinting.org'),
        'A new driver "' . $driver . '" was uploaded by ' . $user . '.'
      );
    }
    
    public function notifyPrinterUpload($model, $manufacturer, $user, $queue = true) {      
      return $this->_notifyUpload(
        'printer_' . (($queue)? '' : 'no') . 'queue',
        'New printer uploaded to ' . (($queue)? 'the queue' : 'openprinting.org'),
        'A new printer "' . $model . '" by "' . $manufacturer . '" was uploaded by ' . $user . '.'
      );
    }
    
    public function getSettings($currentUser) {
      $result = $this->database->query('SELECT web_notifications.* FROM web_notifications INNER JOIN web_user ON web_user.id = web_notifications.web_user_id WHERE web_user.username = ?', $currentUser);
      
      if ($result->numRows() > 0) {
        return $result->getRow();
      }
      
      return array('email' => null, 'printer_queue' => 0, 'printer_noqueue' => 0, 'driver_queue' => 0, 'driver_noqueue' => 0);
    }
    
    public function saveSettings($currentUser, $settings) {
      if (empty($settings['email'])) {
        return false;
      }
      
      $id = $this->_getUserId($currentUser);
      
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
    
    private function _notifyUpload($action, $subject, $body) {
      if ($this->mailer === null) {
        $this->_initMailer();
      }
      
      $result = $this->database->query('SELECT email FROM web_notifications WHERE ' . $action . ' = 1');
      $to = $result->toArray();
      
      $this->mailer->Subject = $subject;
      $this->mailer->Body = $body;
      
      foreach ($to as $receipent) {
        $this->mailer->AddAddress($receipent['email']);
        $this->mailer->Send();
        $this->mailer->ClearAddresses();
      }
      
      return true;
    }
    
    private function _initMailer() {
      $this->mailer = new PHPMailer();
      $this->mailer->IsSMTP();
      
      $this->mailer->Host = $this->configuration->mailhost;
      $this->mailer->SMTPAuth = true;
      $this->mailer->Username = $this->configuration->mailusername;
      $this->mailer->Password = $this->configuration->mailpassword;
      
      $this->mailer->From = $this->configuration->mailfrom_printer;
      $this->mailer->FromName = $this->configuration->mailfromname_printer;
    }
    
    private function _userHasSettings($id) {
      $result = $this->database->query('SELECT web_notifications.email FROM web_notifications WHERE web_notifications.web_user_id = ?', $id);
      return $result->numRows() > 0;
    }
    
    private function _getUserId($currentUser) {
      $result = $this->database->query('SELECT web_user.id FROM web_user WHERE web_user.username = ?', $currentUser);
      $row = $result->getRow();
      
      return $row['id'];
    }
    
  }
  
?>