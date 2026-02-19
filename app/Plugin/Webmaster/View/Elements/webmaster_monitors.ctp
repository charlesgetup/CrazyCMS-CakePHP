<div class="row col-xs-12">
    <h3><?php echo __('Web Master Monitors'); ?></h3>
</div>

<div class="row  col-xs-12">

    <div class="col-sm-6 widget-container-col">
        
        <div id="coding-info"></div>

    </div>
    
    <div class="col-sm-6 widget-container-col">
        
        <div class="col-xs-12">
            <?php echo __('Time between updates'); ?>: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> milliseconds
            <br />
            <br />
            <br />
            <br />
        </div>
        
        <div class="col-xs-12">
            <div id="memory-info" class="plot-chart"></div>
        </div>
        
        <div class="col-xs-12">
            <div id="file-cache-info" class="plot-chart"></div>
        </div>
        
        <div class="col-xs-12">
            <div id="system-info" class="plot-chart"></div>
        </div>
        
    </div>
    
</div>

<!-- Load jQuery 1.8.3 for flot -->
<?php echo $this->Minify->script('admin/flot/jquery'); ?>
<?php 
    echo $this->Minify->script(array(
        'admin/flot/jquery.canvaswrapper',
        'admin/flot/jquery.colorhelpers',
        'admin/flot/jquery.flot',
        'admin/flot/jquery.flot.saturated',
        'admin/flot/jquery.flot.browser',
        'admin/flot/jquery.flot.drawSeries',
        'admin/flot/jquery.flot.errorbars',
        'admin/flot/jquery.flot.uiConstants',
        'admin/flot/jquery.flot.logaxis',
        'admin/flot/jquery.flot.symbol',
        'admin/flot/jquery.flot.flatdata',
        'admin/flot/jquery.flot.navigate',
        'admin/flot/jquery.flot.fillbetween',
        'admin/flot/jquery.flot.stack',
        'admin/flot/jquery.flot.touchNavigate',
        'admin/flot/jquery.flot.hover',
        'admin/flot/jquery.flot.touch',
        'admin/flot/jquery.flot.time',
        'admin/flot/jquery.flot.axislabels',
        'admin/flot/jquery.flot.selection',
        'admin/flot/jquery.flot.composeImages',
        'admin/flot/jquery.flot.legend',
        'admin/flot/jquery.flot.resize',
    )); 
?>

<!-- page specific plugin scripts -->
<?php

    $currentUsageTxt = __("Currrent Usage");
    $maxUsageTxt = __("Max Usage");
    $memoryLimitTxt = __("Memory Limit");
    $memoryMBTxt = __("Memory (MB)");
    $cacheUsageTxt = __("Cache Usage");
    $assetsUsageTxt = __("Assests Usage");
    $avaliableDiskSpaceTxt = __("Avaliable Disk Space");
    $totalAllocatedSpaceTxt = __("Total Allocated Space");
    $diskSpaceTxt = __("Disk Space (MB)");
    $oneMinLoadAvgTxt = __("1 min Load Average");
    $fiveMinLoadAvgTxt = __("5 mins Load Average");
    $fifteenthMinLoadAvgTxt = __("15 mins Load Average");
    $loadAvgTxt = __("Load Average");
    
    $inlineJS = <<<EOF
        
        var jQNoConflict = $.noConflict();
        
        (function( $ ) {
            $(function() {
        
                /* Make sure the following code is stopped before leave this page */
                var stopInterval = false;
                var monitorTimer;
                window.onbeforeunload = function(){
                    stopInterval = true;
                    window.clearTimeout(monitorTimer); /* Stop timer */
                };
        
                /* Set up the control widget */
                var updateInterval = 2000; /* 1 second interval */
                $("#updateInterval").val(updateInterval).change(function () {
                    var v = $(this).val();
                    if (v && !isNaN(+v)) {
                        updateInterval = +v;
                        if (updateInterval < 1000) {
                            updateInterval = 1000; /* Min interval is 1 sec */
                        }
                        $(this).val("" + updateInterval);
                    }
                });
                
                function createPlotOptions(xaxisSettings, yaxisSettings, legendContainer){
                    var plotOptions = {
                        series: {
                            lines: { show: true },
                            points: { show: false },
                            shadowSize: 0   /* Drawing is faster without shadows */
                        },
                        xaxis: xaxisSettings,
                        yaxis: yaxisSettings,
                        legend: {
                            position: "nw", 
                            show: true, 
                            /*backgroundColor: "#fff", // Set backgroundOpacity will remove background for now */ 
                            backgroundOpacity: 1,
                            strokeColor: "#000",
                            container: legendContainer
                        },
                        grid: {
                            backgroundColor: { colors: [ "#fff", "#eee" ] },
                            borderWidth: {
                                top: 1,
                                right: 1,
                                bottom: 2,
                                left: 2
                            },
                            clickable: true
                        }
                    };
                    return plotOptions;
                }
                
                function updatePlot(plotAjaxUrl, dataProc) {
                    
                    $.ajax({
                        async: false,
                        cache: false,
                        url: plotAjaxUrl,
                        type: "GET"
                    }).done(function ( data ) {
                        dataProc(data);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        ajaxErrorHandler(jqXHR, textStatus, errorThrown);
                    }).always(function() {
                        if(!stopInterval){
                            monitorTimer = window.setTimeout(function() { updatePlot(plotAjaxUrl, dataProc); }, updateInterval);                        
                        }
                    });
    
                }
                
                /* Get memory info */
                var memoryPlotData = [
                    {"label": "{$currentUsageTxt}", "data": []}, 
                    {"label": "{$maxUsageTxt}", "data": []}, 
                    {"label": "{$memoryLimitTxt}", "data": []}
                ];
                var memoeyPlotGenerator = function(plotData){
                    if(plotData){
                        var data        = $.parseJSON(plotData);
                        var usageLimit  = parseFloat(data.limit.toUpperCase().replace("M", "").replace("B", "").trim());
                        var maxVal      = Math.ceil(usageLimit); /* Set server max RAM */
                        var maxUsage    = parseFloat(data.max.toUpperCase().replace("M", "").replace("B", "").trim());
                        var curUsage    = parseFloat(data.current.toUpperCase().replace("M", "").replace("B", "").trim());
                        
                        var currentTime = parseInt(new Date().getTime());
                        
                        memoryPlotData[0].data.push([currentTime, curUsage]);
                        memoryPlotData[1].data.push([currentTime, maxUsage]);
                        memoryPlotData[2].data.push([currentTime, usageLimit]);
        
                        var xaxis = { autoScale: 'none', mode: "time", min: (currentTime - 1000 * 60 * 5), max: currentTime, minTickSize: [1, "second"], timeBase: "milliseconds", showTickLabels: 'major', showTicks: true, showMinorTicks: true, twelveHourClock: false, timezone: 'browser'};
                        var yaxis = { min: 0, max: maxVal, showTickLabels: 'all', showTicks: true, showMinorTicks: true, position: 'left', axisLabel: '{$memoryMBTxt}'};
                        var plotOptions = createPlotOptions(xaxis, yaxis, null);
                        $.plot('#memory-info', memoryPlotData, plotOptions);
                    }
                };
                updatePlot('/admin/webmaster/webmaster_configurations/memoryInfo', memoeyPlotGenerator);
                
                /* Get file cache info */
                var filePlotData = [
                    {"label": "{$cacheUsageTxt}", "data": []}, 
                    {"label": "{$assetsUsageTxt}", "data": []}, 
                    {"label": "{$avaliableDiskSpaceTxt}", "data": []},
                    {"label": "{$totalAllocatedSpaceTxt}", "data": []}
                ];
                var filePlotGenerator = function(plotData){
                    if(plotData){
                        var data        = $.parseJSON(plotData);
                        var cacheUsage  = parseFloat(data.files.used.toUpperCase().replace("M", "").replace("B", "").replace("G", "").trim());
                        var assetsUsage = parseFloat(data.assets.used.toUpperCase().replace("M", "").replace("B", "").replace("G", "").trim());
                        var availUsage  = parseFloat(data.files.available.toUpperCase().replace("M", "").replace("B", "").replace("G", "").trim());
                        var totalUsage  = parseFloat(data.files.total.toUpperCase().replace("M", "").replace("B", "").replace("G", "").trim());
                        var maxVal      = totalUsage + 10;
                        
                        var currentTime = parseInt(new Date().getTime());
                        
                        filePlotData[0].data.push([currentTime, cacheUsage]);
                        filePlotData[1].data.push([currentTime, assetsUsage]);
                        filePlotData[2].data.push([currentTime, availUsage]);
                        filePlotData[3].data.push([currentTime, totalUsage]);
                        
                        var xaxis = { autoScale: 'none', mode: "time", min: (currentTime - 1000 * 60 * 5), max: currentTime, minTickSize: [1, "second"], timeBase: "milliseconds", showTickLabels: 'major', showTicks: true, showMinorTicks: true, twelveHourClock: false, timezone: 'browser'};
                        var yaxis = { min: 0, max: maxVal, showTickLabels: 'all', showTicks: true, showMinorTicks: true, position: 'left', axisLabel: '{$diskSpaceTxt}'};
                        var plotOptions = createPlotOptions(xaxis, yaxis, null);
                        $.plot('#file-cache-info', filePlotData, plotOptions);
                    }
                };
                updatePlot('/admin/webmaster/webmaster_configurations/fileCacheInfo', filePlotGenerator);
        
                /* Get system info (TODO: add system uptime when moving to Linux server) */
                var sysPlotData = [
                    {"label": "{$oneMinLoadAvgTxt}", "data": []}, 
                    {"label": "{$fiveMinLoadAvgTxt}", "data": []},
                    {"label": "{$fifteenthMinLoadAvgTxt}", "data": []}
                ];
                var sysPlotGenerator = function(plotData){
                    if(plotData){
                        var data                = $.parseJSON(plotData);
                        var oneMinLoadAve       = parseFloat(data[0].trim());
                        var fiveMinLoadAve      = parseFloat(data[1].trim());
                        var fifteenMinLoadAve   = parseFloat(data[2].trim());
                        var maxVal      = fifteenMinLoadAve + 2;
                        
                        var currentTime = parseInt(new Date().getTime());
                        
                        sysPlotData[0].data.push([currentTime, oneMinLoadAve]);
                        sysPlotData[1].data.push([currentTime, fiveMinLoadAve]);
                        sysPlotData[2].data.push([currentTime, fifteenMinLoadAve]);
                        
                        var xaxis = { autoScale: 'none', mode: "time", min: (currentTime - 1000 * 60 * 5), max: currentTime, minTickSize: [1, "second"], timeBase: "milliseconds", showTickLabels: 'major', showTicks: true, showMinorTicks: true, twelveHourClock: false, timezone: 'browser'};
                        var yaxis = { min: 0, max: maxVal, showTickLabels: 'all', showTicks: true, showMinorTicks: true, position: 'left', axisLabel: '{$loadAvgTxt}'};
                        var plotOptions = createPlotOptions(xaxis, yaxis, null);
                        $.plot('#system-info', sysPlotData, plotOptions);
                    }
                };
                updatePlot('/admin/webmaster/webmaster_configurations/systemInfo', sysPlotGenerator);
                
            });
        })(jQNoConflict);    
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>

<!-- Change back to site jQuery -->
<?php echo $this->Minify->script('admin/jquery.min'); ?>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        
        $.noConflict();
        
        /* Get php info */
        $.ajax({
            headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
            url: '/admin/webmaster/webmaster_configurations/codingInfo',
            type: "POST"
        }).done(function ( data ) {
            if(data){
                var childNested = 0;
                var html = '<ul>';
                data = $.parseJSON(data);
    
                var loopJson = function(data){
                
                    var html = '';
                    for(var key in data){
                        if(data.hasOwnProperty(key)){
                            if(Array.isArray(data[key])){
                                html += '<li><strong class="inline-title">' + key.capitalizeFirstLetter() + '</strong><ul>';
                                for(var i = 0; i < data[key].length; i++){
                                    html += loopJson(data[key][i]);
                                }
                                html += '</ul></li>';
                            }else if(data[key] instanceof Object){
                                html += loopJson(data[key]);
                            }else{
                                html += '<li><strong class="inline-title">' + key.capitalizeFirstLetter() + '</strong>' + data[key] + '</li>';
                            }
                        }
                    }
                    
                    return html;
                };
                html += loopJson(data);
                html += '</ul>';
                $('#coding-info').html(html);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            ajaxErrorHandler(jqXHR, textStatus, errorThrown);
        }).always(function() {
            
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>