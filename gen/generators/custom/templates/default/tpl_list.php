<?
    $cols = 1;
    foreach ($fields as $value) {
        if (isset($value["list"]) && "on" == $value["list"]){
            $cols ++;
        }
    }
    $primary = strtoupper($page[0]);
?>
<style type="text/css">
    .footer,div{
        overflow: visible;
        display: block;
    }
</style>
<div class="page-<?=@$page?>-list page-list">
    <div class="">
        <div  ui-grid="<?=$primary?>LC.grid.options" style="width: 100%; height: 500px; text-align: center;" ui-grid-edit ui-grid-pagination
              ui-grid-selection ui-grid-exporter ui-grid-resize-columns ui-grid-auto-resize  ui-grid-pinning >
        </div>
    </div>
</div>
