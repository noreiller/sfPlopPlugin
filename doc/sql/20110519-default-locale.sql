# sf_plop_page_i18n
ALTER TABLE `sf_plop_page_i18n` CHANGE `locale` `locale` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fr';
UPDATE `sf_plop_page_i18n` SET `locale` = 'en' WHERE `locale` = 'en_EN';
UPDATE `sf_plop_page_i18n` SET `locale` = 'fr' WHERE `locale` = 'fr_FR';

# sf_plop_slot_config_i18n
ALTER TABLE `sf_plop_slot_config_i18n` CHANGE `locale` `locale` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'fr';
UPDATE `sf_plop_slot_config_i18n` SET `locale` = 'en' WHERE `locale` = 'en_EN';
UPDATE `sf_plop_slot_config_i18n` SET `locale` = 'fr' WHERE `locale` = 'fr_FR';
