<?php
/**
 * Task management plugin
 *
 * A CakePHP Plugin to manage task stuff
 *
 */

/**
 * System default setting type
 */
Configure::write('Config.type.taskmanagement', "TASK_MANAGEMENT"); // This plugin will not only used by Web Development people, some general features will be used by every one, like create a ticket

//TODO later we need more task type and these are related to certain departments
// Configure::write('TaskManagement.type.emailmarketing', 'EMAIL_MARKETING');
// Configure::write('TaskManagement.type.payment', 'PAYMENT');
Configure::write('TaskManagement.type.webdev', 'WEB-DEV');
Configure::write('TaskManagement.type.ticket', 'TICKET');

Configure::write('TaskManagement.status', array('NEW','IN-DEV','MORE-INFO-NEEDED','COMPLETED','CLOSED'));
Configure::write('TaskManagement.invisible-status', array('COMPLETED','CLOSED'));

Configure::write('TaskManagement.comment.type.autogen', 'AUTO-GEN');
Configure::write('TaskManagement.comment.type.manual', 'MANUAL');

Configure::write('TaskManagement.aws.s3.path', 'tasks');