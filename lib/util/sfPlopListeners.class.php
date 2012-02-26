<?php
class sfPlopListeners
{

  /**
   * Send email
   * @param sfEvent $event
   */
  public function listenToMessagingEvent(sfEvent $event)
  {
    $mailer = new sfPlopMessaging($event['options']);
    $mailer->sendEmail($event['mailer']);
  }
}
?>
