<table border="1" class="mainTable gg table table-striped table-bordered table-hover individual-item-report dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
    <thead>
    <tr role="row">
        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 424px;">
            Title
        </th>
        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="ASIN: activate to sort column ascending" style="width: 100px">
            ASIN
        </th>
        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Number days Amazon Out of Stock: activate to sort column ascending" style="width: 61px;">
            Duration Amazon was out of stock
        </th>
        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Out of Stock Date: activate to sort column ascending" style="width: 61px;">
            Out of Stock Date
        </th>
        <th class="text-center sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Back in Stock Date: activate to sort column ascending" style="width: 100px;">
            Back in Stock Date
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($reports as $product):
        $same_status = $product->amznotseller == $product->prev_amznotseller;
        $datetime1 = date_create($product->out_of_stock);
        $datetime2 = date_create($product->back_in_stock);
        $interval = ($product->out_of_stock === null || $product->back_in_stock === null)
            ? false
            : date_diff($datetime1, $datetime2);
        $timezone = $user->timezone ? $user->timezone : 'est';
        $timezoneObj = new DateTimeZone(TIMEZONES[$timezone]);
        $defaultTimezoneObj = new DateTimeZone('America/New_York');
        $outOfStock = new DateTime($product->out_of_stock, $defaultTimezoneObj);
        $outOfStock->setTimezone($timezoneObj);
        $backInStock = new DateTime($product->back_in_stock, $defaultTimezoneObj);
        $backInStock->setTimezone($timezoneObj);
        ?>
        <tr role="row" class="odd">
            <td class="text-center vertical-middle">
                    <?php echo $product->title_name; ?>
            </td>
            <td class="text-center vertical-middle">
                <a target="_blank" href="http://amazon.com/dp/<?php echo $product->asin; ?>">
                    <?php echo $product->asin; ?>
                </a>
            </td>
            <td class="text-center vertical-middle">
                <?php echo (!$same_status) ? ($interval ? $interval->format('%h hours %i minutes') : 'N/A') : 'Missed status change <br /> because tracking was turned off' ?>
            </td>
            <td class="text-center vertical-middle">
                <?php echo (!$same_status) ? ($product->out_of_stock ? $outOfStock->format('m/d/Y h:iA T') : 'N/A') : 'Missed status change <br /> because tracking was turned off' ?>
            </td>
            <td class="text-center vertical-middle">
                <?php echo $product->back_in_stock ? $backInStock->format('m/d/Y h:iA T') : 'N/A' ?>
            </td>
           
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>