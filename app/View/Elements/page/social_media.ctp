<ul class="list-services clearfix">
    <li>
        <a class="list-services-1" href="javascript: void(0)" onclick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $sharedTitle; ?>&amp;p[summary]=<?php echo $sharedSummary; ?>&amp;p[url]=<?php echo $sharedUrl; ?>&amp;p[images][0]=<?php echo $sharedImage; ?>','sharer','toolbar=0,status=0,width=500,height=350');"></a>
    </li>
    <li>
        <a href="#" class="list-services-2"></a>
    </li>
    <li>
        <a class="list-services-3" href="javascript: void(0)" onclick="window.open('https://twitter.com/share?url=http%3A%2F%2F<?php echo $companyEmail; ?>','twitter share','resizable=yes,width=500,height=350,scrollbars=yes,status=yes')"></a>
    </li>
    <li>
        <a class="list-services-4" href="https://plus.google.com/share?url={<?php echo $sharedUrl; ?>}" onclick="javascript:window.open(this.href, 'sharer', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"></a>
    </li>
</ul>