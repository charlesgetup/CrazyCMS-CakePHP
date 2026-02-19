<?php echo __('Thank you for using ' .$companyName .' online service!'); ?>

<?php echo __("Visit the following link address in your browser to reset your account password"); ?>.

http://<?php echo $companyDomain; ?>/account/reset_password/<?php echo $user['User']['token']; ?>