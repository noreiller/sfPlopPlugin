#!/bin/bash
# @author: Aurélien MANCA
# @description: Install the sandbox

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

./symfony plugin:publish-assets
./symfony project:permissions
chmod -R 777 ./web/media/