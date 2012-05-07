<?php

class sfPlopResetTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('no-confirmation', null, sfCommandOption::PARAMETER_NONE, 'Do not ask for confirmation')
    ));

    $this->namespace        = 'plop';
    $this->name             = 'reset';
    $this->briefDescription = 'Reset the sandbox.';
    $this->detailedDescription = <<<EOF
The [reset|INFO] task makes a copy of the fixtures and reload the database.
Call it with:

  [php symfony plop:reset|INFO]

To bypass the confirmation, you can pass the [--no-confirmation|COMMENT]
option:

  [./symfony plop:reset --no-confirmation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (
      !$options['no-confirmation']
      &&
      !$this->askConfirmation(array(
          'WARNING: The data in the database(s) will be removed.',
          '',
          'Are you sure you want to proceed? (y/N)',
        ), 'QUESTION_LARGE', false)
    )
    {
      $this->logSection('plop', 'Task aborted.');

      return 1;
    }

    $command = <<<EOF
rm data/fixtures/{1..4}*.yml
cp plugins/sfGuardPlugin/data/fixtures/fixtures.yml.sample data/fixtures/1-sfGuardPlugin.yml
cp plugins/sfAssetsGalleryPlugin/data/fixtures/fixtures.yml.sample data/fixtures/2-sfAssetsGalleryPlugin.yml
cp plugins/sfPlopGuardPlugin/data/fixtures/fixtures.yml.sample data/fixtures/3-sfPlopGuardPlugin.yml
cp plugins/sfPlopPlugin/data/fixtures/fixtures.yml.sample data/fixtures/4-sfPlopPlugin.yml
EOF;
    $this->getFilesystem()->execute($command);

    $this->runTask('propel:build-model');
    $this->runTask('propel:build-sql');
    $this->runTask('propel:insert-sql', array(), array('no-confirmation'));
    $this->runTask('propel:build-forms');
    $this->runTask('propel:build-filters');
    $this->runTask('propel:data-load');
    $this->runTask('cc');
    $this->runTask('asset:create-root');
  }

}
