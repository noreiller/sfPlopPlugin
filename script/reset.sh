#!/bin/bash
# @author: Aurélien MANCA
# @description: Reset the sandbox

rm data/fixtures/{1..4}*.yml

cp plugins/sfGuardPlugin/data/fixtures/fixtures.yml.sample data/fixtures/1-sfGuardPlugin.yml
cp plugins/sfAssetsGalleryPlugin/data/fixtures/fixtures.yml.sample data/fixtures/2-sfAssetsGalleryPlugin.yml
cp plugins/sfPlopGuardPlugin/data/fixtures/fixtures.yml.sample data/fixtures/3-sfPlopGuardPlugin.yml
cp plugins/sfPlopPlugin/data/fixtures/fixtures.yml.sample data/fixtures/4-sfPlopPlugin.yml

plugins/sfPlopPlugin/config/build.sh