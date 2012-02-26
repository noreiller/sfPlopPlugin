<?php
class sfPlopMessaging
{
  protected $options = null;
  protected $count = 0;
  protected $mail = null;

  /**
   * Construct and load the options.
   * @param Array $options
   * @return mixed
   */
  public function __construct($options) {
    $this->options = $options;
    $this->check();
    $this->processEmail();
  }

  /**
   * Check the default options.
   */
  public function check() {
    $this->options['name'] = $this->getOption('name',  sfPlop::get('sf_plop_messaging_from_name'));
    if (!$this->getOption('email'))
    {
      $this->options['email'] =  sfPlop::get('sf_plop_messaging_from_email');
      if (isset($this->options['copy']))
        unset($this->options['copy']);
    }
  }

  /**
   * Prepare the email message.
   */
  public function processEmail() {
    $this->count = 1;
    
    $mail = new Swift_Message();
    $mail->setContentType('text/plain');
    $mail->setCharset('utf-8');

    if($this->getOption('use_complete_template', true)) {
      $mail->setBody(sprintf(<<<EOF
------
%s - %s
------
%s
------
EOF
        ,
        $this->options['name'],
        $this->options['email'],
        $this->options['message']
      ));
    }
    else
    {
      $mail->setBody($this->options['message']);
    }

    $mail->setSender(array(
      sfPlop::get('sf_plop_messaging_from_email') => sfPlop::get('sf_plop_messaging_from_name')
    ));

    $mail->setFrom(array(
      $this->options['email'] => $this->options['name']
    ));

    if ($this->getOption('copy')) {
      $mail->setCc(array(
        $this->options['email'] => $this->options['name']
      ));
      $this->count++;
    }

    if (is_integer($this->getOption('receiver')))
    {
      $receiver = sfGuardUserProfilePeer::retrieveByPK($this->getOption('receiver'));
      if ($receiver)
      {
        $mail->setTo(array(
          $receiver->getEmail() => $receiver->getFullName()
        ));
      }
      else
      {
        $mail->setTo(array(
          sfPlop::get('sf_plop_messaging_to_email') => sfPlop::get('sf_plop_messaging_to_name')
        ));
      }
    }
    else
    {
      $mail->setTo(array(
        sfPlop::get('sf_plop_messaging_to_email') => sfPlop::get('sf_plop_messaging_to_name')
      ));
    }

    if ($this->getOption('subject'))
      $mail->setSubject($this->getOption('subject'));
    else
      $mail->setSubject(sfPlop::get('sf_plop_messaging_subject'));

    $this->mail = $mail;
  }

  /**
   * Send an email given to the options
   * @return Array returns an array with the number of message to send
   * and the amount sent.
   */
  public function sendEmail($mailer =  null) {
    if(!$mailer)
      $mailer = $this->getMailer();
    
    $sent = $mailer->send($this->mail);

    return array(
      'count' => $this->count,
      'sent' => $sent
    );
  }

  /**
   * Retrieve the option for the given key or the default one.
   * @param String $name
   * @param Mixed $default
   * @return mixed
   */
  public function getOption($name, $default = null) {
     return isset($this->options[$name]) ? $this->options[$name] : $default;
  }

  /**
   * Returns the mailer instance.
   */
  protected function getMailer() {
    if (sfContext::hasInstance())
      return sfContext::getInstance()->getMailer();
  }

}
?>
