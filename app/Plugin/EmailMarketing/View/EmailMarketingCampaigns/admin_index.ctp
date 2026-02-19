<?php
    $displayFields = array(
        'EmailMarketingCampaign.id'                 => array('ColumnName' => __('ID'),            'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingCampaign.name'               => array('ColumnName' => __('Name'),          'Sortable' => true, 'Searchable' => true),
        'EmailMarketingCampaign.from_email_address' => array('ColumnName' => __('Sender Email'),  'Sortable' => true, 'Searchable' => true),
        'EmailMarketingCampaign.send_format'        => array('ColumnName' => __('Email Format'),  'Sortable' => true, 'Searchable' => true),
        'EmailMarketingCampaign.scheduled_time'     => array('ColumnName' => __('Scheduled Time'),'Sortable' => true, 'Searchable' => true),
        'EmailMarketingCampaign.user_name'          => array('ColumnName' => __('Created By'),    'Sortable' => true, 'Searchable' => true),
        'EmailMarketingCampaign.modified'           => array('ColumnName' => __('Created/Modified'), 'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'ADD'           => array('/admin/email_marketing/email_marketing_campaigns/add/'),
        'View'          => array('/admin/email_marketing/email_marketing_campaigns/view/', 'EmailMarketingCampaign.id', null, array('class' => 'pink popup-view')),
        'Edit'          => array('/admin/email_marketing/email_marketing_campaigns/edit/', 'EmailMarketingCampaign.id', null, array('class' => 'green popup-edit')),
        'Delete'        => array('/admin/email_marketing/email_marketing_campaigns/delete/', 'EmailMarketingCampaign.id', null, array('class' => 'red popup-delete')),
        'Email'         => array('/admin/email_marketing/email_marketing_campaigns/sendEmail/', 'EmailMarketingCampaign.id', null, array('class' => 'purple popup-email')),
        'Statistics'    => array('/admin/email_marketing/email_marketing_statistics/viewCampaignHistory/', 'EmailMarketingCampaign.id', null, array('class' => 'orange popup-statistics')),
        'Schedule'      => array('/admin/email_marketing/email_marketing_campaigns/setScheduleTime/', 'EmailMarketingCampaign.id', null, array('class' => 'blue popup-schedule')),
    );
    
    echo $this->JqueryDataTable->createTable('EmailMarketingCampaign',
        $displayFields,
        "/admin/email_marketing/email_marketing_campaigns/index.json",
        $actions,
        __('No email marketing campaigns found'),
        $defaultSortDir,
        'email_marketing'
    );
?>

<!-- Send test email -->
<br />
<br />
<div class="row">
    <div class="col-xs-12">
        <button class="btn btn-white btn-info btn-bold send-test-email">
            <i class="ace-icon fa fa-envelope-o bigger-120 blue"></i>
            <?php echo __('Select one campaign & Send test email'); ?>
        </button>
    </div>
</div>
<br />
<br />
<div class="row">
    <div class="col-xs-12 report-title">
        <h3><?php echo __('Statistics Overview'); ?></h3>
    </div>
    <div class="col-xs-12 report-actions">
        <div class="input-group right">
            <button class="btn btn-primary load-selected-campaigns"><i class="ace-icon fa fa-refresh"></i></button>
        </div>
        <div class="input-group right">
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
            <?php
                // Set default values
                $endDate    = empty($endDate) ? date('d/m/Y') : date('d/m/Y', strtotime($endDate));
                $startDate  = empty($startDate) ? date('d/m/Y', strtotime('-30 days')) : date('d/m/Y', strtotime($startDate));
            ?>
            <input class="form-control active" type="text" name="date-range-picker" placeholder="<?php echo '&nbsp;' .__('Select date range') .''; ?>" value="<?php echo ((!empty($startDate) && !empty($endDate)) ? $this->Time->i18nFormat($startDate, '%x') .' - ' .$this->Time->i18nFormat($endDate, '%x') : ""); ?>">
        </div>
    </div>
</div>
<br />
<br />
<div class="row">
    <div class="col-xs-12">
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#overview">
                        <?php echo __('Overview'); ?>
                    </a>
                </li>
        
                <li>
                    <a data-toggle="tab" href="#geo">
                        <?php echo __('GEO stats'); ?>
                    </a>
                </li>
        
                <li class="dropdown">
                    <a data-toggle="tab" href="#device">
                        <?php echo __('Device & Browser stats'); ?>
                    </a>
                </li>
            </ul>
        
            <div class="tab-content">
                <div id="overview" class="tab-pane fade in active">
                    <br />
                    <br />
                    <div class="row">
                        <div class="col-xs-12 infobox-container">
                            <div class="infobox infobox-green">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-envelope"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number">0</span>
                                    <div class="infobox-content"><?php echo __('Email Requests'); ?></div>
                                </div>
                            </div>
                            <div class="infobox infobox-blue">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-send"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number">0</span>
                                    <div class="infobox-content"><?php echo __('Email Sent'); ?></div>
                                </div>
                                <div class="stat stat-blue no-pseudo"></div>
                            </div>
                            <div class="infobox infobox-pink">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-inbox"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number">0</span>
                                    <div class="infobox-content"><?php echo __('Email Opens'); ?></div>
                                </div>
                                <div class="stat stat-pink no-pseudo"></div>
                            </div>
                            <div class="infobox infobox-green2">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-hand-o-up"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number">0</span>
                                    <div class="infobox-content"><?php echo __('Email Clicked'); ?></div>
                                </div>
                                <div class="stat stat-green2 no-pseudo"></div>
                            </div>
                            <div class="infobox infobox-red">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-external-link"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number">0</span>
                                    <div class="infobox-content"><?php echo __('Email Bounces'); ?></div>
                                </div>
                                <div class="stat stat-important no-pseudo"></div>
                            </div>
                            <?php if(false): ?>
                                <div class="infobox infobox-grey">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-times"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <span class="infobox-data-number">0</span>
                                        <div class="infobox-content"><?php echo __('Email Unsubscribes'); ?></div>
                                    </div>
                                    <div class="stat no-pseudo"></div>
                                </div>
                                <div class="infobox infobox-grey">
                                    <div class="infobox-icon">
                                        <i class="ace-icon fa fa-times"></i>
                                    </div>
                                    <div class="infobox-data">
                                        <span class="infobox-data-number">0</span>
                                        <div class="infobox-content"><?php echo __('Email Spammed'); ?></div>
                                    </div>
                                    <div class="stat no-pseudo"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div class="row">
                        <div class="col-xs-12 report-grid">
                            <div id="overview-report"></div>
                        </div>
                    </div>
                </div>
        
                <div id="geo" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-12">
                            <div id="geo-report">
                                <h4><?php echo __('Activity map'); ?></h4>
                                <br />
                                <div id="visitfromworld" style="width:100%!important; height:410px !important;"></div>
                                <br />
                                <br />
                                <h4><?php echo __('Activity from countries'); ?></h4>
                                <br />
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo __('Country'); ?></th>
                                            <th><?php echo __('Email Opens'); ?></th>
                                            <th>%</th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div id="device" class="tab-pane fade">
                    <div class="row">
                        <div class="col-xs-12">
                            <div id="device-report">
                                <h4><?php echo __('Browser stats'); ?></h4>
                                <br />
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo __('Browser'); ?></th>
                                            <th><?php echo __('Device'); ?></th>
                                            <th><?php echo __('Email Opens'); ?></th>
                                            <th>%</th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
    
    $noCampaignSelectionMsg     = __("Please select one campaign first.");
    $selectTooManyCampaignMsg   = __("Please select just one campaign.");
    $pageTitle                  = __('Email marketing Campaign Test Email');
    $closeBtn                   = __("Close");
    $sendBtn                    = __("Confirm & Send email(s)");
    $applyBtn                   = __('Apply');
    $cancelBtn                  = __('Cancel');
    $today                      = date('d/m/Y');
    
    $opens                      = __('Opens');
    $clicks                     = __('Clicks');
    
    $desktop                    = __('Desktop');
    $mobile                     = __('Mobile');
    
    $requestTxt                 = __('Email Requests');
    $sentTxt                    = __('Email Sent');
    $openedTxt                  = __('Email Opens');
    $clickedTxt                 = __('Email Clicked');
    $bouncedTxt                 = __('Email Bounces');
    
    $requestKey                 = 'emailRequests';
    $sentKey                    = 'emailSent';
    $openedKey                  = 'emailOpened';
    $clickedKey                 = 'linkClicked';
    $bouncedKey                 = 'emailBounced';
    
    $labels                     = "'" .$requestTxt ."', '" .$sentTxt ."', '" .$openedTxt ."', '" .$clickedTxt ."', '" .$bouncedTxt ."'";
    $keys                       = "'" .$requestKey ."', '" .$sentKey ."', '" .$openedKey ."', '" .$clickedKey ."', '" .$bouncedKey ."'";
    $labelColors                = "'#9abc32', '#6fb3e0', '#cb6fd7', '#0490a6', '#d53f40'";
    
    $inlineJS = <<<JS
    
        $(".send-test-email").on(ace.click_event, function(event) {
            var selectedObjs = $('#EmailMarketingCampaign-data-table').find("tr.selected");
            if(selectedObjs == null || selectedObjs == "" || selectedObjs.length < 1){
                messageBox({"status": ERROR, "message": "{$noCampaignSelectionMsg}"});
                return false;
            }else if(selectedObjs.length > 1){
                messageBox({"status": ERROR, "message": "{$selectTooManyCampaignMsg}"});
                return false;
            }
            var campaignId = selectedObjs.children('td.all').children('a:first').data('link').split("/").pop();
            if(campaignId){
                bootbox.dialog({
                    message: '<iframe src="/admin/email_marketing/email_marketing_campaigns/sendTestEmail/'+campaignId+'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$pageTitle}',
                    onEscape: false,
                    buttons: {
                        "Send": {
                            "label" : '{$sendBtn}',
                            "className" : "btn-sm btn-success submit-iframe-form-btn",
                            "callback" : function(event){
                                submitIframeForm(event);
                                setTimeout(function(){actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));}, 4000);  
                            }
                        },
                        "Close" : {
                            "label" : '{$closeBtn}',
                            "className" : "btn-sm btn-sm"
                        }
                    }
                });
            }
        });

        /* Overview chart */

        var morrisChart = Morris.Area({
            element: 'overview-report',
            xkey: 'period',
            ykeys: [{$keys}],
            labels: [{$labels}],
            pointSize: 3,
            fillOpacity: 0,
            pointStrokeColors: [{$labelColors}],
            behaveLikeLine: true,
            gridLineColor: '#e0e0e0',
            lineWidth: 3,
            hideHover: 'auto',
            lineColors: [{$labelColors}],
            resize: true
        });

        /* Geo vector map */
        
        var geoMap, clicksByRegions, openByRegions;
        
        var loadVectorMap = function(){
        
            if(geoMap){
                geoMap.remove();
            }
            
            $('#visitfromworld').vectorMap({
                map: 'world_mill_en',
                backgroundColor: '#fff',
                borderColor: '#ccc',
                borderOpacity: 0.9,
                borderWidth: 1,
                zoomOnScroll: false,
                color: '#ddd',
                regionStyle : {
                    initial : {
                        fill : '#fff'
                    }
                },
                enableZoom: true,
                normalizeFunction: 'linear',
                showTooltip: true,
                series: {
                    regions: [{
                        values: openByRegions,
                        scale: ['#ebffff', '#0071A4'],
                        normalizeFunction: 'polynomial'
                    }]
                },
                onRegionTipShow: function(e, el, code){
                    el.html(el.html()+' ({$opens}: '+(openByRegions && openByRegions[code] ? openByRegions[code] : 0)+', {$clicks}: '+(clicksByRegions && clicksByRegions[code] ? clicksByRegions[code] : 0)+')');
                }
            });
            
            geoMap = $('#visitfromworld').vectorMap('get', 'mapObject');
        };
        
        $('#myTab a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        
            if($(e.target).attr('href') == "#geo"){
            
                loadVectorMap();
            
            }
            
        });

        var loadReport = function(startDate, endDate, campaignIds){
        
            if(!startDate){
                startDate = moment('{$startDate}', 'DD/MM/YYYY').format('YYYY-MM-DD');
            }
            
            if(!endDate){
                endDate = moment('{$endDate}', 'DD/MM/YYYY').format('YYYY-MM-DD');
            }
            
            if(!campaignIds){
                campaignIds = [];
            }
        
            $.ajax({
                async: false,
                cache: false,
                url: '/admin/email_marketing/email_marketing_statistics/getStatisticsReport',
                type: "POST",
                data: {"startDate": startDate, "endDate": endDate, "campaignIds": campaignIds},
                dataType: 'json',
                headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                beforeSend: function ( xhr ) {
                    $.gritter.removeAll();
                    if($.fn.ace_ajax){
                        $('[data-ajax-content=true]').ace_ajax('startLoading');
                    }
                }
            }).done(function ( data ) {

                if(data != undefined && data != null && data != "" && !$.isEmptyObject(data)){

                    openByRegions   = data['geo']['open'];
                    clicksByRegions = data['geo']['click'];

                    var statisticData = [];
                    var totalRequests = 0;
                    var totalSent     = 0;
                    var totalClicks   = 0;
                    var totalOpens    = 0;
                    var totalBounced  = 0;
                
                    for(var period in data['overview']){
                        var s = {
                            "period"        : period,
                            "{$requestKey}" : 0,
                            "{$sentKey}"    : 0,
                            "{$clickedKey}" : 0,
                            "{$openedKey}"  : 0,
                            "{$bouncedKey}" : 0
                        };
                        for(var k in data['overview'][period]){
                            switch(k){
                                case 'requests':
                                    s["{$requestKey}"]  = data['overview'][period][k];
                                    totalRequests       += parseInt(data['overview'][period][k]);
                                    break;
                                case "clicks":
                                    s["{$clickedKey}"]  = data['overview'][period][k];
                                    totalClicks         += parseInt(data['overview'][period][k]);
                                    break;
                                case "opens":
                                    s["{$openedKey}"]   = data['overview'][period][k];
                                    totalOpens          += parseInt(data['overview'][period][k]);
                                    break;
                                case "sents":
                                    s["{$sentKey}"]     = data['overview'][period][k];
                                    totalSent           += parseInt(data['overview'][period][k]);
                                    break;
                                case "bounces":
                                    s["{$bouncedKey}"]  = data['overview'][period][k];
                                    totalBounced        += parseInt(data['overview'][period][k]);
                                    break;
                            }
                        }
                        statisticData.push(s);
                    }
                    
                    $('.infobox.infobox-green > .infobox-data > .infobox-data-number').text( numeral(totalRequests).format('0.[00]a') );
            
                    $('.infobox.infobox-blue > .infobox-data > .infobox-data-number').text( numeral(totalSent).format('0.[00]a') );
                    $('.infobox.infobox-blue > .stat').text( numeral(totalSent / totalRequests).format('0.[00]%') );
                    
                    $('.infobox.infobox-pink > .infobox-data > .infobox-data-number').text( numeral(totalOpens).format('0.[00]a') );
                    $('.infobox.infobox-pink > .stat').text( numeral(totalOpens / totalSent).format('0.[00]%') );
                    
                    $('.infobox.infobox-green2 > .infobox-data > .infobox-data-number').text( numeral(totalClicks).format('0.[00]a') );
                    $('.infobox.infobox-green2 > .stat').text( numeral(totalClicks / totalSent).format('0.[00]%') );
                    
                    $('.infobox.infobox-red > .infobox-data > .infobox-data-number').text( numeral(totalBounced).format('0.[00]a') );
                    $('.infobox.infobox-red > .stat').text( numeral(totalBounced / totalSent).format('0.[00]%') );
            
                    morrisChart.setData(statisticData);
                    
                    if(data['geo'] && data['geo']['open']){
                        for(var country in data['geo']['open']){
                            var row = ' <tr>' +
                                      '     <td>' +
                                      '         <i class="flag-icon flag-icon-' + country.toLowerCase() + '"></i>' +
                                      '         <span class="country-name">' + objectGetKeyByValue(data['countryMap'], country) + '</span>' +
                                      '     </td>' +
                                      '     <td>' + data['geo']['open'][country] + '</td>' +
                                      '     <td class="progress-bar-column">' +
                                      '         <div class="progress progress-mini">' +
                                      '             <div class="progress-bar progress-danger" style="width: ' + numeral(data['geo']['open'][country] / totalOpens).format('0%') + '; background-color: ' + getRandomColorHex() + ';"></div>' +
                                      '         </div>' +
                                      '     </td>' +
                                      '     <td>' + numeral(data['geo']['open'][country] / totalOpens).format('0%') + '</td>' +
                                      ' </tr>';
                            $('#geo table tbody').append(row);
                        }
                    }
                    
                    if(data['devices']){
                        for(var browser in data['devices']){
                            var browserImg = '';
                            if(browser.toLowerCase().indexOf('chrome') >= 0){
                                browserImg = '<img src="/img/admin/browser/chrome-logo.png" alt="Chrome logo">';
                            }else if(browser.toLowerCase().indexOf('firefox') >= 0){
                                browserImg = '<img src="/img/admin/browser/firefox-logo.png" alt="Firefox logo">';
                            }else if(browser.toLowerCase().indexOf('microsoft') >= 0){
                                browserImg = '<img src="/img/admin/browser/internet-logo.png" alt="IE logo">';
                            }else if(browser.toLowerCase().indexOf('opera') >= 0){
                                browserImg = '<img src="/img/admin/browser/opera-logo.png" alt="Opera logo">';
                            }else if(browser.toLowerCase().indexOf('safari') >= 0){
                                browserImg = '<img src="/img/admin/browser/safari-logo.png" alt="Safari logo">';
                            }
                            for(var isMobile in data['devices'][browser]){
                                if(isMobile != 0 && isMobile != 1){
                                    continue;
                                }
                                var row = ' <tr>' +
                                          '     <td>' +
                                          '         ' + browserImg + 
                                          '         <span class="browser-name">' + browser + '</span>' +
                                          '     </td>' +
                                          '     <td>' + (isMobile == 1 ? '{$desktop}' : '{$mobile}') + '</td>' + /* Need to improve this part. Use real device name. */
                                          '     <td>' + data['devices'][browser][isMobile] + '</td>' +
                                          '     <td class="progress-bar-column">' +
                                          '         <div class="progress progress-mini">' +
                                          '             <div class="progress-bar progress-danger" style="width: ' + numeral(data['devices'][browser][isMobile] / totalOpens).format('0%') + '; background-color: ' + getRandomColorHex() + ';"></div>' +
                                          '         </div>' +
                                          '     </td>' +
                                          '     <td>' + numeral(data['devices'][browser][isMobile] / totalOpens).format('0%') + '</td>' +
                                          ' </tr>';
                                $('#device table tbody').append(row);
                            }
                        }
                    }
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxErrorHandler(jqXHR, textStatus, errorThrown);
            }).always(function() {
                if($.fn.ace_ajax){
                    $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                }
            });
        
        };
        
        var getSelectedCampaigns = function(){
            var campaignDataTable = $('#EmailMarketingCampaign-data-table').DataTable();
            var selectedCampaigns = campaignDataTable.rows(".selected").data();
            var ids               = [];
            for(var i = 0; i < selectedCampaigns.length; i++){
                ids.push(parseInt(selectedCampaigns[i].EmailMarketingCampaign.id));
            }
            return ids;
        };

        $('input[name="date-range-picker"]').daterangepicker({
            format: 'DD/MM/YYYY',
            locale: {
                applyLabel: '{$applyBtn}',
                cancelLabel: '{$cancelBtn}'
            },
            startDate : '{$startDate}',
            endDate : '{$endDate}',
            autoUpdateInput : false,
            applyClass : 'btn-sm btn-success',
            cancelClass : 'btn-sm btn-default',
            opens : 'left',
            maxDate : '{$today}',
        }, function(start, end, label) {
            var ids = getSelectedCampaigns();
            loadReport(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'), ids);
            if($('#geo.tab-pane').hasClass('active')){
                loadVectorMap();
            }
        });

        $('button.load-selected-campaigns').click(function(){
            var ids = getSelectedCampaigns();
            var selectedDateRange = $('input[name="date-range-picker"]').val();
            var selectedDateRanges = selectedDateRange.split(' - ');
            if(selectedDateRanges.length == 2){
                var startDate = moment(selectedDateRanges[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
                var endDate = moment(selectedDateRanges[1], 'DD/MM/YYYY').format('YYYY-MM-DD');
                loadReport(startDate, endDate, ids);
                if($('#geo.tab-pane').hasClass('active')){
                    loadVectorMap();
                }
            }
        });
        
        loadReport();
JS;
    echo $this->HTML->css(array(
        '/assets/morrisjs/morris.css',
        'admin/daterangepicker',
        'admin/flag-icon-css/flag-icon.min',
        '/js/admin/vectormap/jquery-jvectormap-2.0.2.css'
    ));
    echo $this->HTML->script(array(
        '/assets/raphael/raphael-min.js',
        '/assets/morrisjs/morris.min.js',
        'admin/date-time/daterangepicker',
        'admin/vectormap/jquery-jvectormap-2.0.2',
        'admin/vectormap/jquery-jvectormap-world-mill-en'
    ));
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    ));
?>