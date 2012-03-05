<?php

class sfPlopInstallTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('dsn', sfCommandArgument::REQUIRED, 'The database dsn'),
      new sfCommandArgument('username', sfCommandArgument::OPTIONAL, 'The database username', 'root'),
      new sfCommandArgument('password', sfCommandArgument::OPTIONAL, 'The database password'),
    ));

    $this->namespace        = 'plop';
    $this->name             = 'install';
    $this->briefDescription = 'Install and configure the sandbox.';
    $this->detailedDescription = <<<EOF
The [install|INFO] copy the required files from the samples, configure them and the database.
Call it with:

  [php symfony plop:install "mysql:host=localhost;dbname=plop" root mYsEcret|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $command1 = <<<EOF
cp config/ProjectConfiguration.class.php.sample config/ProjectConfiguration.class.php

cp apps/frontend/config/frontendConfiguration.class.php.sample apps/frontend/config/frontendConfiguration.class.php
cp apps/frontend/config/app.yml.sample apps/frontend/config/app.yml
cp apps/frontend/config/settings.yml.sample apps/frontend/config/settings.yml
cp apps/frontend/config/filters.yml.sample apps/frontend/config/filters.yml

cp config/databases.yml.sample config/databases.yml
cp config/propel.ini.sample config/propel.ini
cp config/properties.ini.sample config/properties.ini

cp plugins/sfGuardPlugin/data/fixtures/fixtures.yml.sample data/fixtures/1-sfGuardPlugin.yml
cp plugins/sfAssetsGalleryPlugin/data/fixtures/fixtures.yml.sample data/fixtures/2-sfAssetsGalleryPlugin.yml
cp plugins/sfPlopGuardPlugin/data/fixtures/fixtures.yml.sample data/fixtures/3-sfPlopGuardPlugin.yml
cp plugins/sfPlopPlugin/data/fixtures/fixtures.yml.sample data/fixtures/4-sfPlopPlugin.yml

cp config/databases.yml.sample config/databases.yml.local
cp config/databases.yml.sample config/databases.yml.preprod
cp config/databases.yml.sample config/databases.yml.production

cp config/propel.ini.sample config/propel.ini.local
cp config/propel.ini.sample config/propel.ini.preprod
cp config/propel.ini.sample config/propel.ini.production

cp web/.htaccess web/.htaccess.local
cp web/.htaccess web/.htaccess.preprod
cp web/.htaccess web/.htaccess.production

chmod -R 777 ./web/media/
EOF;

    $this->getFilesystem()->execute($command1);

    $this->runTask('plugin:publish-assets');
    $this->runTask('project:permissions');

    $args = array(
      'dsn' => $arguments['dsn'],
      'username' => $arguments['username'],
      'password' => $arguments['password']
    );
    $this->runTask('configure:database', $args);

    $path = str_replace('/', '\/', sfConfig::get('sf_root_dir'));
    $command2 =  <<<EOF
sed -i 's/\/path\/to\/project\/plop/$path/g' ./config/propel.ini
cp config/databases.yml config/databases.yml.local
cp config/propel.ini config/propel.ini.local
EOF;

    $this->getFilesystem()->execute($command2);

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
