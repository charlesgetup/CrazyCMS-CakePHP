<?php
    echo $this->element('templates/email_marketing_template_editor', array(
        'templateId'    => $templateId,
        'assets'        => $assets,
        'template'      => $template,
        'security'      => $security
    ));
?>