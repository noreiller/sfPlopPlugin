<?php

class sfPlopContactUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('name', null, sfCommandOption::PARAMETER_OPTIONAL, 'the new name'),
      new sfCommandOption('username', null, sfCommandOption::PARAMETER_OPTIONAL, 'the username'),
      new sfCommandOption('email', null, sfCommandOption::PARAMETER_OPTIONAL, 'the new email'),
    ));

    $this->namespace        = 'plop';
    $this->name             = 'contact-update';
    $this->briefDescription = 'Updates contact settings.';
    $this->detailedDescription = <<<EOF
The [contact-update|INFO] updates the settings related to the name and email.
Call it with:

  [php symfony plop:contact-update|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $con = $databaseManager->getDatabase($options['connection'])->getConnection();

    if (isset($options['email']))
    {
      $validatorEmail = new sfValidatorEmail();
      try {
        $options['email'] = $validatorEmail->clean($options['email']);
      } catch (Exception $e) {
        throw new sfCommandException('The email is invalid.');
      }

      $emails = array(
        'sf_plop_messaging_from_email',
        'sf_plop_messaging_to_email',
      );
      
      foreach($emails as $email)
        sfPlopConfigPeer::addOrUpdate($email, $options['email'], $con);
    }

    if (isset($options['email']))
    {
      $validatorName = new sfValidatorString();
      try {
        $options['name'] = $validatorName->clean($options['name']);
      } catch (Exception $e) {
        throw new sfCommandException('The name is invalid.');
      }

      $names = array(
        'sf_plop_messaging_from_name',
        'sf_plop_messaging_to_name',
      );

      foreach ($names as $name)
        sfPlopConfigPeer::addOrUpdate($name, $options['name'], $con);
    }

    if (isset($options['username'])) 
    {
      $user = sfGuardUserQuery::create()
        ->findOneByUsername($options['username']);

      if ($user)
			{
        if (isset($options['email']))
          $user->getProfile()->setEmail($options['email']);

        $names = explode(' ', $options['name']);
        $firstName = $names[0];
        unset($names[0]);
        $lastName = implode(' ', $names);

        $user->getProfile()->setFirstName($firstName);
        $user->getProfile()->setLastName($lastName);

        $user->getProfile()->save();
      }
      else
			{
        throw new sfCommandException('There is no user whith username "' . $options['username'] . '".');
      }
    }
  }

}
