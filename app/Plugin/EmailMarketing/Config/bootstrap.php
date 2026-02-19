<?php
/**
 * Email marketing plugin
 *
 * A CakePHP Plugin to manage email marketing stuff
 *
 */

/**
 * System default setting type
 */
Configure::write('Config.type.emailmarketing', "EMAIL_MARKETING");
Configure::write('EmailMarketing.client.group.id', "4");
Configure::write('EmailMarketing.staff.group.id', "9");
Configure::write('EmailMarketing.manager.group.id', "14");

Configure::write('EmailMarketing.configuration.introduction.field.name', "Introduction");

Configure::write('EmailMarketing.email.DKIM.privateKeyFileName', "default.private");
Configure::write('EmailMarketing.email.DKIM.publicKeyFileName', "default.txt");
Configure::write('EmailMarketing.email.DKIM.remoteKeyDir', "/etc/opendkim/keys/");

Configure::write('EmailMarketing.email.DKIM.publicKeyFile.path', "email-marketing" .DS ."{user_id}" .DS ."DKIM");

Configure::write('EmailMarketing.email.html.template.assets.path', "email-marketing" .DS ."{user_id}" .DS ."templates" .DS ."thumbs");
Configure::write('EmailMarketing.email.html.template.preview.path', "email-marketing" .DS ."{user_id}" .DS ."templates" .DS ."{template_id}" .DS ."preview");

Configure::write('EmailMarketing.email.type.text', "TXT");
Configure::write('EmailMarketing.email.type.html', "HTML");
Configure::write('EmailMarketing.email.type.both', "BOTH");

Configure::write('EmailMarketing.email.softBounce.type', "SOFT");
Configure::write('EmailMarketing.email.hardBounce.type', "HARD");

Configure::write('EmailMarketing.plan.no.sender', [1, 2]); // No sender plan ID
Configure::write('EmailMarketing.plan.prepaid', 1); // Prepaid plan ID

Configure::write('EmailMarketing.email.server.host', '13.211.111.106');
Configure::write('EmailMarketing.email.server.user', 'ec2-user');
Configure::write('EmailMarketing.email.server.priv', ROOT . DS . APP_DIR . DS .'tmp' .DS .'aws_shared_server_sydney.pem');
Configure::write('EmailMarketing.email.server.pubk', ROOT . DS . APP_DIR . DS .'tmp' .DS .'aws_shared_server_sydney.pub');
Configure::write('EmailMarketing.email.server.port', '22');

Configure::write('EmailMarketing.email.track.image', ROOT . DS . APP_DIR . DS .'Plugin' .DS .'EmailMarketing' .DS .'webroot' .DS .'images' .DS .'FFFFFF-0.png');