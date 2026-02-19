<?php echo $this->Form->create(false); ?>

<div class="row">
    <div class="col-xs-12">
        <fieldset>
        
        	<?php
            	echo $this->Form->input('robots', array(
                    'label'         => __d('webmaster', "robots.txt"),
                    'type'          => 'textarea',
                    'div'           => false,
                    'class'         => 'col-xs-12 col-sm-12 height-220',
                    'rows'          => false,
                    'cols'          => false,
                    'tabindex'      => 1
                ));
            ?>
            
            <div class="clear">&nbsp;</div>
            
            <?php if (!$sitemap): ?>
            
                <div class="form-inline">
                    <?php
                	   echo $this->Form->input(
                	       'sitemap',
                		   array(
                		      'label'    => false,
                			  'type'     => 'checkbox',
                			  'default'  => true,
                			  'div'      => true,
                              'class'    => 'col-xs-12 col-sm-12',
                              'tabindex' => 2
                		   )
                	   );
                	?>
                	<label for="sitemap" class="col-left-offset-15"><?php echo __("Include sitemap"); ?></label>
            	</div>
        	
        	<?php endif; ?>
        
        	<div class="form-actions submit">
        
        		<?php
            		echo $this->Form->submit(
            			__("Save changes"),
            			array('div' => false, 'class' => 'btn btn-primary')
            		);
        		?>
        
        	</div>
        
        </fieldset>
    </div>
</div>

<?php echo $this->Form->end(); ?>
