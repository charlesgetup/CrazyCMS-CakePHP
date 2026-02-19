<?php
    echo $this->element('templates/email_marketing_template_editor', array(
        'customiseTemplateId'           => $customiseTemplateId,
        'emailMarketingUserRecordId'    => $emailMarketingUserRecordId,
        'assets'                        => $assets,
        'template'                      => $template
    ));
?>