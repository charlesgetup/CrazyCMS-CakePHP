<?php 
    $index          = (isset($user['Address'][1]['same_as']) && intval($user['Address'][1]['same_as']) != 0) ? 0 : 1;
    $streetAddress  = isset($user['Address'][$index]['street_address']) ? $user['Address'][$index]['street_address'] : "";
    $suburb         = isset($user['Address'][$index]['suburb']) ? $user['Address'][$index]['suburb'] : "";
    $state          = isset($user['Address'][$index]['state']) ? $user['Address'][$index]['state'] : "";
    $postcode       = isset($user['Address'][$index]['postcode']) ? $user['Address'][$index]['postcode'] : "";
    $country        = isset($user['Address'][$index]['Country']['name']) ? $user['Address'][$index]['Country']['name'] : ""; 
?>
<dl class="dl-horizontal">
    <dt><?php echo __('Street Address'); ?></dt>
    <dd>
        <?php echo h($streetAddress); ?>
    </dd>
    <dt><?php echo __('Suburb/City'); ?></dt>
    <dd>
        <?php echo h($suburb); ?>
    </dd>
    <dt><?php echo __('State/Province'); ?></dt>
    <dd>
        <?php echo h($state); ?>
    </dd>
    <dt><?php echo __('Postcode/Zip'); ?></dt>
    <dd>
        <?php echo h($postcode); ?>
    </dd>
    <dt><?php echo __('Country'); ?></dt>
    <dd>
        <?php echo h($country); ?>
    </dd>
</dl>