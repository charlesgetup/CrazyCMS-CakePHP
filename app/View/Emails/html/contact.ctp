<table class="table-row" width="600" bgcolor="#FFFFFF"
    style="table-layout: fixed; background-color: #ffffff;" cellspacing="0"
    cellpadding="0" border="0">
    <tbody>
        <tr>
            <td class="table-row-td"
                style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal; padding-left: 36px; padding-right: 36px;"
                valign="top" align="left">
                <table class="table-col" align="left" width="528" cellspacing="0"
                    cellpadding="0" border="0" style="table-layout: fixed;">
                    <tbody>
                        <tr>
                            <td class="table-col-td" width="528"
                                style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 13px; font-weight: normal;"
                                valign="top" align="left"><span
                                style="font-family: Arial, sans-serif; line-height: 19px; color: #444444; font-size: 14px;">
                                    <?php echo __("Invoice Content"); ?>:
                            </span>

                                <table class="table-space" height="12"
                                    style="height: 12px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;"
                                    width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
                                    border="0">
                                    <tbody>
                                        <tr>
                                            <td class="table-space-td" valign="middle" height="12"
                                                style="height: 12px; width: 528px; background-color: #ffffff;"
                                                width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table-space" height="16"
                                    style="height: 16px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;"
                                    width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
                                    border="0">
                                    <tbody>
                                        <tr>
                                            <td class="table-space-td" valign="middle" height="16"
                                                style="height: 16px; width: 528px; background-color: #ffffff;"
                                                width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div style="margin: 0px; padding: 5px 0px 5px 25px;">
                                    <?php
                                        echo '<p><span style="width:100px;">' .__("Visitor") .':</span><br />&nbsp;&nbsp;&nbsp;&nbsp;' . $data['author'] . '</p>' . "\n";
                                        echo '<br>' . "\n";
                                        echo '<p><span style="width:100px;">' .__("Email") .':</span><br />&nbsp;&nbsp;&nbsp;&nbsp;' . $data['email'] . '</p>' . "\n";
                                        echo '<br>' . "\n";
                                        echo '<p><span style="width:100px;">' .__("Telephone") .':</span><br />&nbsp;&nbsp;&nbsp;&nbsp;' . $data['phone'] . '</p>' . "\n";
                                        echo '<br>' . "\n";
                                        echo '<p><span style="width:100px;">' .__("Message") .':</span><br />' . $data['text'] . '</p>' . "\n";
                                    ?>
                                </div>

                                <table class="table-space" height="16"
                                    style="height: 16px; font-size: 0px; line-height: 0; width: 528px; background-color: #ffffff;"
                                    width="528" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0"
                                    border="0">
                                    <tbody>
                                        <tr>
                                            <td class="table-space-td" valign="middle" height="16"
                                                style="height: 16px; width: 528px; background-color: #ffffff;"
                                                width="528" bgcolor="#FFFFFF" align="left">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>