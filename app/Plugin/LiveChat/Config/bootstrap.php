<?php
/**
 * Live Chat plugin
 *
 * A CakePHP Plugin to manage live chat stuff
 *
 */

/**
 * System default setting type
 */
Configure::write('Config.type.livechat', "LIVE_CHAT");
Configure::write('LiveChat.client.group.id', "6");
Configure::write('LiveChat.staff.group.id', "11");
Configure::write('LiveChat.manager.group.id', "16");

Configure::write('LiveChat.configuration.introduction.field.name', "Introduction");

Configure::write('LiveChat.api.url', 'http://livechat.crazysoft.loc/index');

Configure::write('LiveChat.api.code.admin', 'sklBlJX8YeX4WUqO7J5qzEoXNoXjFYFP');

Configure::write('LiveChat.api.code.length', 32);

Configure::write('LiveChat.default.passpharse', 's#kfjsfj8-lsfjd84kl&230kK^hNN@dL');

Configure::write('LiveChat.default.salt', '?D?1s?q');