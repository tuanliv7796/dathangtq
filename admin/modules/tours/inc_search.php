<form action="" method="GET">
<div class="search">
	<table>
		<tr>
			<td valign="top">
				<table width="100%" cellpadding="2">
					<tr>
						<td class="text">Tên tour</td>
						<td>
							<input type="text" id="tou_keyword" name="tou_keyword" class="form-control" style="width: 150px;" value="<?=$tou_keyword?>" />
						</td>
					</tr>
					<tr>
						<td class="text">Mã tour</td>
						<td>
							<input type="text" id="tou_code" name="tou_code" class="form-control" style="width: 150px;" value="<?=$tou_code?>" />
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table width="100%" cellpadding="2">
					<tr>
						<td class="text">Danh mục</td>
						<td>
							<select id="iCat" class="form-control" name="iCat" style="width: 150px;">
								<option value="0">Danh mục</option>
								<?
			               foreach($arrCategory as $rowCat) {
			               	$text_line	= "";
									for($j=0;$j<$rowCat["level"];$j++) $text_line	.= "--";
									$style	= ($rowCat["level"] == 0) ? "bold" : "";
			                  echo('<option style="font-weight: ' . $style . '" value="' . $rowCat["cat_id"] . '"' . ($rowCat["cat_id"] == $iCat ? ' selected="selected"' : '') . '>' . $text_line . $rowCat["cat_name"] . '</option>');
			               }
			               ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="text">Chủ đề</td>
						<td>
							<select id="iSeason" class="form-control" name="iSeason" style="width: 150px;">
								<?
			               foreach($arraySeason as $key => $value) {
			                  echo '<option value="' . $key . '"' . ($key == $iSeason ? ' selected="selected"' : '') . '>' . $value . '</option>';
			               }
			               ?>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table width="100%" cellpadding="2">
					<tr>
						<td class="text">Nơi khởi hành</td>
						<td>
							<select id="iSource" class="form-control" name="iSource" style="width: 150px;">
								<?
			               foreach($arrayDeparturesTour as $key => $value) {

			                  echo '<option value="' . $key . '"' . ($key == $iSource ? ' selected="selected"' : '') . '>' . $value . '</option>';
			               }
			               ?>
							</select>
						</td>
					</tr>
					<tr>
						<td class="text">Thời lượng</td>
						<td>
							<select id="iTime" class="form-control" name="iTime" style="width: 150px;">
								<?
			               foreach($arrayTimeTour as $key => $value) {

			                  echo '<option value="' . $key . '"' . ($key == $iTime ? ' selected="selected"' : '') . '>' . $value . '</option>';
			               }
			               ?>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table width="100%" cellpadding="2">
					<tr>
						<td class="text">Trạng thái</td>
						<td>
							<select id="iStatus" class="form-control" name="iStatus" style="width: 150px;">
								<option value="0">--Trạng thái--</option>
								<?
			               foreach($arrCat as $rowCat) {
			               	$text_line	= "";
									for($j=0;$j<$rowCat["level"];$j++) $text_line	.= "--";
									$color	= isset($ArrayColor[$rowCat["level"]]) ? $ArrayColor[$rowCat["level"]] : "#000";
									$style	= ($rowCat["level"] == 0) ? "bold" : "";
			                  echo('<option style="color: ' . $color . '; font-weight: ' . $style . '" value="' . $rowCat["toc_id"] . '"' . ($rowCat["toc_id"] == $iCat ? ' selected="selected"' : '') . '>' . $text_line . $rowCat["toc_title"] . '</option>');
			               }
			               ?>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td style="padding-top: 2px;">
							<input style="width: 100%; font-weight: bold;" type="submit" class="btn btn-info" value="Tìm kiếm" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</form>