UPDATE `configuration` SET `value` = 'prestashop.dev' WHERE `configuration`.`name` ='PS_SHOP_DOMAIN';
UPDATE `configuration` SET `value` = 'prestashop.dev' WHERE `configuration`.`name` ='PS_SHOP_DOMAIN_SSL';
UPDATE `configuration` SET `value` = 0 WHERE `configuration`.`name` ='PS_SMARTY_CACHE';
UPDATE `configuration` SET `value` = 0 WHERE `configuration`.`name` ='PS_CSS_THEME_CACHE';
UPDATE `configuration` SET `value` = 0 WHERE `configuration`.`name` ='PS_JS_THEME_CACHE';
UPDATE `configuration` SET `value` = 1 WHERE `configuration`.`name` ='PS_SMARTY_FORCE_COMPILE';
UPDATE `configuration` SET `value` = 0 WHERE `configuration`.`name` ='PS_HTML_THEME_COMPRESSION';
UPDATE `configuration` SET `value` = 0 WHERE `configuration`.`name` ='PS_JS_HTML_THEME_COMPRESSION';
