<?
require_once("inc_security.php");

$new_id 				= getValue("new_id", "str", "GET", 0);
$sqlWhere = "";
//Get page break params
$page_size			= 30;
$page_prefix		= "Trang: ";
$normal_class		= "page";
$selected_class	= "page_current";
$previous			= '<img align="absmiddle" border="0" src="../../resource/images/grid/prev.gif">';
$next          	= '<img align="absmiddle" border="0" src="../../resource/images/grid/next.gif">';
$first				= '<img align="absmiddle" border="0" src="../../resource/images/grid/first.gif">';
$last          	= '<img align="absmiddle" border="0" src="../../resource/images/grid/last.gif">';
$break_type			= 1;
$url					= getURL(0,0,1,1,"page");
$total_quantity	= 0; //
$db_count			= new db_query("  SELECT count(*) AS count
												FROM tags
												WHERE 1 " . $sqlWhere);
$listing_count		= mysqli_fetch_assoc($db_count->result);
$total_record		= $listing_count["count"];
$current_page		= getValue("page", "int", "GET", 1);
if($total_record % $page_size == 0) $num_of_page = $total_record / $page_size;
else $num_of_page = (int)($total_record / $page_size) + 1;
if($current_page > $num_of_page) $current_page = $num_of_page;
if($current_page < 1) $current_page = 1;
unset($db_count);
//End get page break params

$db_listing	= new db_query("	SELECT *
										FROM tags
										WHERE 1 " . $sqlWhere . "
										ORDER BY tag_id DESC
										LIMIT " . ($current_page-1) * $page_size . "," . $page_size);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <?=$load_header?>
</head>
<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<? /*---------Body------------*/ ?>
<?=template_top("Lựa chọn tag <a style='display: inline-block; padding-left: 50px; font-size: 15px;' href='/tag/add.php'><span class='glyphicon glyphicon-plus-sign' style='font-size: 15px;' aria-hidden='true'></span>&nbsp;Thêm mới</a>")?>
<div class="listing">
    <div class="content">
        <table width="80%" cellspacing="0" cellpadding="0" class="table table-hover table-bordered">
            <tr class="warning">
                <td width="30" class="h">STT</td>
                <td class="h">Tên tag</td>
                <td class="h">Select</td>
            </tr>
            <?
            $No	= ($current_page - 1) * $page_size;
            while($listing = mysqli_fetch_assoc($db_listing->result)) {
                $No++;
                ?>
                <tr id="tr_<?=$listing["top_id"]?>">
                    <td title="STT" class="bold center"><?=$No?></td>
                    <td title="Name" class="bold center"><?=$listing["tag_name"]?></td>
                    <td title="Select" class="center" style="text-align: center;">
                       <?
                       $checked = "";
                       $check = checkTag($new_id,$listing['tag_id']);
                        if($check > 0){
                            $checked = "checked";
                        }
                       ?>
                        <input row-tag-id="<?=$listing['tag_id']?>" row-new-id="<?=$new_id?>" class="table-select" name="table-select-<?=$listing['tag_id']?>" id="pe_active_5845166" type="checkbox" <?=$checked?>>
                    </td>
                </tr>
            <? } ?>
        </table>
    </div>
    <div class="footer">
        <table class="page_break" width="100%">
            <tr>
                <td style="color: #15428B; font-weight: bold;">Tổng số bản ghi: <span style="color: #333333;"><?=$total_record?></span></td>
                <td width="150"></td>
                <td></td>
                <? if($total_record > $page_size){ ?>
                    <td><?=generatePageBar($page_prefix, $current_page, $page_size, $total_record, $url, $normal_class, $selected_class, $previous, $next, $first, $last, $break_type)?></td>
                <? } ?>
                <td class="align_r"><a title="Go to top" accesskey="T" class="top" href="#" style="font-weight: bold;">Lên trên<img align="absmiddle" border="0" hspace="5" src="<?=$fs_imagepath?>top.png"></a></td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
<? unset($db_listing); ?>
<script type="text/javascript">
    $('input.table-select').change(function () {
        var _input = $(this);

            $.ajax({
                type: 'POST',
                url: 'ajax_select_tag.php',
                data: {
                    tag_id: _input.attr('row-tag-id'),
                    new_id: _input.attr('row-new-id'),
                    status: _input.is(':checked') ? 1 : 0,
                },
                success: function (response) {
                }
            });
            $(this).is(':checked');

    });
</script>