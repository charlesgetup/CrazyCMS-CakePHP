<?php
/**
 * Web development plugin
 *
 * A CakePHP Plugin to manage web development stuff
 *
 */

/**
 * System default setting type
 */
Configure::write('Config.type.webdevelopment', "WEB_DEVELOPMENT");
Configure::write('WebDevelopment.client.group.id', "2");
Configure::write('WebDevelopment.staff.group.id', "7");
Configure::write('WebDevelopment.manager.group.id', "12");

Configure::write('WebDevelopment.configuration.introduction.field.name', "Introduction");

Configure::write('WebDevelopment.project.status', array('NEW','IN-DEV','ON-HOLD','MORE-INFO-NEEDED','DISCUSSION','TESTING','COMPLETED','CLOSED'));
Configure::write('WebDevelopment.stage.status', array('NEW','IN-DEV','ON-HOLD','MORE-INFO-NEEDED','DISCUSSION','TESTING','COMPLETED','CLOSED'));

Configure::write('WebDevelopment.project.type', array('PRIVATE','PUBLIC'));