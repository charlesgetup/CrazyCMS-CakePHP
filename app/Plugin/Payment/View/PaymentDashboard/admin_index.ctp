<div class="row">
    <div class="col-xs-12">
        <div class="center">
            
            <span class="btn btn-app btn-sm btn-light no-hover width-auto">
                <span class="line-height-1 bigger-170 blue ace-icon"><?php echo isset($totalInvoiceNumber) ? $totalInvoiceNumber : 0; ?></span>
                <br />
                <span class="line-height-1 smaller-90">&nbsp;&nbsp;<?php echo __('Issued Invoices'); ?>&nbsp;&nbsp;</span>
            </span>
            
            <span class="btn btn-app btn-sm btn-yellow no-hover width-auto">
                <span class="line-height-1 bigger-170 ace-icon">$<?php echo isset($totalPaidAmount) ? $totalInvoiceAmount : 0; ?></span>
                <br />
                <span class="line-height-1 smaller-90">&nbsp;&nbsp;<?php echo __('Invoice Paid Amount'); ?>&nbsp;&nbsp;</span>
            </span>
            
            <span class="btn btn-app btn-sm btn-primary no-hover width-auto">
                <span class="line-height-1 bigger-170 ace-icon"><?php echo isset($thisMonthTotalInvoiceNumber) ? $thisMonthTotalInvoiceNumber : 0; ?></span>
                <br />
                <span class="line-height-1 smaller-90">&nbsp;&nbsp;<?php echo __('Issued Invoices in ' .date('M')); ?>&nbsp;&nbsp;</span>
            </span>
            
            <span class="btn btn-app btn-sm btn-success no-hover width-auto">
                <span class="line-height-1 bigger-170 ace-icon">$<?php echo isset($thisMonthTotalPaidAmount) ? $thisMonthTotalInvoiceAmount : 0; ?></span>
                <br />
                <span class="line-height-1 smaller-90">&nbsp;&nbsp;<?php echo __('Invoice Paid Amount in ' .date('M')); ?>&nbsp;&nbsp;</span>
            </span>
            
        </div>
    </div>
</div>

<br />
<br />
<br />
<br />

<div class="row">
    <div class="col-xs-12">
        <div class="clearfix">
        
            <?php 
                $totalPaidPercentage = empty($totalInvoiceAmount) ? '0.00' : number_format (($totalPaidAmount / $totalInvoiceAmount * 100), 2); 
            ?>
            <div class="grid2 center">
                <div class="center easy-pie-chart percentage" data-percent="<?php echo $totalPaidPercentage; ?>" data-color="#9585BF">
                    <span class="percent"><?php echo $totalPaidPercentage; ?></span>%
                </div>
                <br />
                <br />
                <?php echo __('Total Invoice Paid Percentage'); ?>
            </div>
        
            <?php 
                $thisMonthPaidPercentage = empty($thisMonthTotalInvoiceAmount) ? '0.00' : number_format (($thisMonthTotalPaidAmount / $thisMonthTotalInvoiceAmount * 100), 2); 
            ?>
            <div class="grid2 center">
                <div class="easy-pie-chart percentage" data-percent="<?php echo $thisMonthPaidPercentage; ?>" data-color="#CA5952">
                    <span class="percent"><?php echo $thisMonthPaidPercentage; ?></span>%
                </div>
                <br />
                <br />
                <?php echo __('Invoice Paid Percentage in ' .date('M')); ?>
            </div>
        
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php 
    $inlineJS = <<<EOF
        $('.easy-pie-chart.percentage').each(function(){
            var barColor = $(this).data('color') || '#555';
            var trackColor = '#E2E2E2';
            var size = parseInt($(this).data('size')) || $(this).parent('div.grid2').width() * 0.5;
            $(this).easyPieChart({
                barColor: barColor,
                trackColor: trackColor,
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: parseInt(size/10),
                animate:false,
                size: size
            }).css('color', barColor);
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('jquery.easypiechart.js'),
        'inlineJS' => $inlineJS
    )); 
?>