<?php $isClient = stristr($userGroupName, Configure::read('System.client.group.name')); ?>

<div class="row dashboard ">

    <?php if($isClient === FALSE): ?>
        
    <?php else: ?>
           
        <h1 class="grey"><?php echo __('We Make You Look Wonderful Online'); ?></h1>
        
        <div class="space-24"></div>

        <div class="row center">
            <img src="/img/admin/mobile-devices-2017978__340.png" border="0" />
        </div>

        <div class="space-24"></div>
        
        <div class="row">
            <h2 class="grey"><strong><?php echo __('Our Services'); ?></strong></h2>
        </div>
        
        <div class="space-12"></div>
        
        <div class="row">
            <ul>
                <li>
                    <?php echo __('Build responsive websites with or without frameworks'); ?>
                </li>
                <li>
                    <?php echo __('Implement some awesome features, like REST API, framework plugins & extensions, and so on'); ?>
                </li>
                <li>
                    <?php echo __('Join your project and work as a remote contractor'); ?>
                </li>
                <li>
                    <?php echo __('Always be prepared to work by your side. To explore more about what we can do, just click the button below and let us know what you want.'); ?>
                </li>
            </ul>
        </div>

        <div class="space-12"></div>
        
        <div class="row">
            <button class="btn btn-success btn-block activate"><?php echo __('Get a free quote today'); ?></button> //link to live chat
        </div>
        
    <?php endif; ?>
    
</div>