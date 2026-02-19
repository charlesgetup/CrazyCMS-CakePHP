<div class="row">
    <div class="col-xs-12">
        <div>
            <div class="col-sm-offset-1 col-sm-10">
                <div class="tabbable">
                	<ul class="nav nav-tabs padding-16">
                	    <li class="active">
                	       <a data-toggle="tab" href="#view-campaign-statistics">
                	           <i class="pink ace-icon fa fa-line-chart bigger-125"></i>
                	           <?php echo __('Campaign Statistics Summary'); ?>
                	       </a>
                	    </li>
                	</ul>
                	<div class="tab-content">
                	    <div class="tab-pane in active" id="view-campaign-statistics">
                	        <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Blacklisted Subscribers'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($statistics['EmailMarketingStatistic']['blacklisted']); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Duplicated Subscribers'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($statistics['EmailMarketingStatistic']['duplicated']); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Invalid Subscribers'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($statistics['EmailMarketingStatistic']['invalid']); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Processed Subscribers'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($statistics['EmailMarketingStatistic']['processed']); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Forwarded Subscribers'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($statistics['EmailMarketingStatistic']['forwarded']); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Bounced Subscribers'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($totalBounced); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Viewed Count'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($totalOpened); ?>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <?php echo __('Clicked Count'); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo h($totalClicked); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row hr hr-16 hr-dotted"></div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="widget-box">
                                        <div class="widget-header widget-header-flat widget-header-small">
                                            <h5 class="widget-title">
                                                <i class="ace-icon fa fa-pie-chart"></i>
                                                <?php echo __("Compaign Open Rate"); ?>
                                            </h5>
                                        </div>
                            
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div id="piechart-placeholder"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                	    </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $notViewed = (intval($statistics['EmailMarketingStatistic']['processed']) < $totalOpened) ? 0 : (intval($statistics['EmailMarketingStatistic']['processed']) - $totalOpened);
    $inlineJS = <<<EOF
        /* flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
           but sometimes it brings up errors with normal resize event handlers */
        $.resize.throttleWindow = false;
    
        var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
        var data = [
            { label: "Opened",  data: {$totalOpened}, color: "#68BC31"},
            { label: "Not Viewed",  data: {$notViewed}, color: "#DA5430"}
        ];
        var drawPieChart = function(placeholder, data, position) {
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        tilt:0.8,
                        highlight: {
                            opacity: 0.25
                        },
                        stroke: {
                            color: '#fff',
                            width: 2
                        },
                        startAngle: 2
                    }
                },
                legend: {
                    show: true,
                    position: position || "ne", 
                    labelBoxBorderColor: null,
                    margin:[-30,15]
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });
        };
        
        /* pie chart tooltip example */
        var \$tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
        var previousPoint = null;
        
        $(document).ready(function(){
            
            drawPieChart(placeholder, data);
    
            /**
                we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
                so that's not needed actually.
             */
            placeholder.data('chart', data);
            placeholder.data('draw', drawPieChart);
        
            placeholder.on('plothover', function (event, pos, item) {
                if(item) {
                    if (previousPoint != item.seriesIndex) {
                        previousPoint = item.seriesIndex;
                        var tip = item.series['label'] + " : " + item.series['percent']+'%';
                        \$tooltip.show().children(0).text(tip);
                    }
                    \$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
                } else {
                    \$tooltip.hide();
                    previousPoint = null;
                }
            });
            
        });
        
        $(document).one('ajaxloadstart.page', function(e) {
            \$tooltip.remove();
        });
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('flot-ace/jquery.flot.min.js', 'flot-ace/jquery.flot.pie.min.js', 'flot-ace/jquery.flot.resize.min.js'),
        'inlineJS' => $inlineJS
    )); 
?>