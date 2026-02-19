<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

<?php
    
    // Sometimes there will be controller action redirections triggered after submitting the form via JS
    // Then some other layout will be loaded in this AJAX LAYOUT.
    // To fix this issue, we will hide some page elements and pretend that the layout is correct.
    
    // Load iframe layout here
    if(!empty($loadInIframe)){
        $inlineCSS = <<<EOF
            <style>
                body {
                    overflow: hidden;
                    position: relative;
                    height: 100%;
                }
                
                #navbar, 
                #breadcrumbs, 
                .page-content > .page-content-area > .page-header, 
                #main-container > .footer {
                    display: none;
                }
            </style>
EOF;
        echo $inlineCSS; 
        
        $inlineJS = <<<EOF
            
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); 
    }
    
?>

<?php echo $this->fetch('content'); ?>