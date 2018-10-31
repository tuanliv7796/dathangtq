<?php

include_once "../classes/database.php";
$order_detail = new Order_Detail();

if(!empty($_SESSION["user_session"])){
	$user_session = $_SESSION["user_session"];
	//lay danh sách gio hang
	$sql = "SELECT orders.id, order_detail.*, status.name as status_name FROM order_detail 
            INNER JOIN orders ON order_detail.order_id = orders.id
            INNER JOIN status ON status.id = order_detail.status
			WHERE orders.user_id = " . $user_session['id'];

	$order_detail = $order_detail->queryRaw($sql);

}else{
	header('location: /trang-chu');
}


?>


<main id="main-wrap">
    <div class="all">
        <div class="main">
            <div class="sec form-sec">
                <div class="sec-tt">
                    <h2 class="tt-txt">Danh sách đơn hàng</h2>
                    <p class="deco">
                        <img src="../images/title-deco.png" alt="">
                    </p>
                </div>
                <div class="content-text">
                    <div id="primary" class="page orders-list">
                        <div class="container">
                            <ul class="statistics black">
                                <li>Tổng số đơn hàng: <b class="m-color">
                                    <? echo count($order_detail) ?></b>
                                </li>
                                <li>Tổng trị giá: <b class="m-color">
                                    <span id="total_price"></span></b> VNĐ 
                                </li>
                                <li>Số tiền để lấy hàng trong kho: <b class="m-color">
                                    0</b> VNĐ 
                                </li>
                            </ul>
                            <table class="stat-detail black table-custom">
                                <tbody>
                                    <tr>
                                        <th rowspan="4">Trong đó:</th>
                                        <td><b class="m-color">
                                            0</b> đơn hàng chưa đặt cọc.
                                        </td>
                                        <td><b class="m-color">
                                            0</b> đơn hàng đã đặt cọc.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b class="m-color">
                                            0</b> đơn hàng đã đặt hàng.
                                        </td>
                                        <td><b class="m-color">
                                            0</b> đơn hàng đã có hàng tại TQ.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b class="m-color">
                                            0</b> đơn hàng đã có hàng tại VN.
                                        </td>
                                        <td><b class="m-color">
                                            0</b> đơn hàng đã nhận hàng.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="container">
                            <table class="tbl-subtotal">
                                <tbody>
                                    <tr class="black b">
                                        <th rowspan="7" valign="top">Thông tin tiền hàng:</th>
                                        <td>Tổng tiền hàng chưa giao:	</td>
                                        <td>
                                            0
                                            VNĐ 
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền hàng cần đặt cọc: </td>
                                        <td>
                                            chờ cập nhật
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền hàng chờ về kho TQ: </td>
                                        <td>
                                            0
                                            VNĐ
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền hàng đã về kho TQ: </td>
                                        <td>
                                            0
                                            VNĐ
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền hàng đang ở kho VN : </td>
                                        <td>
                                            0
                                            VNĐ
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Tổng tiền hàng cần thanh toán để lấy hàng đã nhận về kho VN: </td>
                                        <td>
                                            0
                                            VNĐ
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="container">
                            <aside class="filters">
                                <ul>
                                    <li class="lbl">Tìm kiếm</li>
                                    <li>
                                        <select name="ctl00$ContentPlaceHolder1$ddlStatus" id="ContentPlaceHolder1_ddlStatus" class="form-control">
                                            <option value="-1">Tất cả</option>
                                            <option value="0">Chưa đặt cọc</option>
                                            <option value="1">Hủy đơn hàng</option>
                                            <option value="2">Đã đặt cọc</option>
                                            <option value="5">Đã mua hàng</option>
                                            <option value="6">Đã nhận hàng tại TQ</option>
                                            <option value="7">Đã nhận hàng tại VN</option>
                                            <option value="9">Khách đã thanh toán</option>
                                            <option value="10">Đã hoàn thành</option>
                                        </select>
                                    </li>
                                    <li class="width-20-per">
                                        <div id="ctl00_ContentPlaceHolder1_rFD_wrapper" class="RadPicker RadPicker_Default date" placeholder="Từ ngày" style="display:inline-block;width:100%;">
                                            <!-- 2013.3.1015.40 --><input style="visibility:hidden;display:block;float:right;margin:0 0 -1px -1px;width:1px;height:1px;overflow:hidden;border:0;padding:0;" id="ctl00_ContentPlaceHolder1_rFD" name="ctl00$ContentPlaceHolder1$rFD" type="text" class="rdfd_ radPreventDecorate" value="" title="Visually hidden input created for functionality purposes.">
                                            <table cellspacing="0" class="rcTable rcSingle" summary="Table holding date picker control for selection of dates." style="width:100%;">
                                                <caption style="display:none;">
                                                    RadDatePicker
                                                </caption>
                                                <thead style="display:none;">
                                                    <tr>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="rcInputCell" style="width:100%;"><span id="ctl00_ContentPlaceHolder1_rFD_dateInput_wrapper" class="riSingle RadInput RadInput_Default" style="display:block;width:100%;"><input id="ctl00_ContentPlaceHolder1_rFD_dateInput" name="ctl00$ContentPlaceHolder1$rFD$dateInput" class="riTextBox riEmpty radPreventDecorate" value="Từ ngày" type="text"><input id="ctl00_ContentPlaceHolder1_rFD_dateInput_ClientState" name="ctl00_ContentPlaceHolder1_rFD_dateInput_ClientState" type="hidden" autocomplete="off" value="{&quot;enabled&quot;:true,&quot;emptyMessage&quot;:&quot;Từ ngày&quot;,&quot;validationText&quot;:&quot;&quot;,&quot;valueAsString&quot;:&quot;&quot;,&quot;minDateStr&quot;:&quot;1980-01-01-00-00-00&quot;,&quot;maxDateStr&quot;:&quot;2099-12-31-00-00-00&quot;,&quot;lastSetTextBoxValue&quot;:&quot;Từ ngày&quot;}"></span></td>
                                                        <td>
                                                            <a title="Open the calendar popup." href="#" id="ctl00_ContentPlaceHolder1_rFD_popupButton" class="rcCalPopup">Open the calendar popup.</a>
                                                            <div id="ctl00_ContentPlaceHolder1_rFD_calendar" style="display: none" class="RadCalendar RadCalendar_Default">
                                                                <div class="rcTitlebar">
                                                                    <a id="ctl00_ContentPlaceHolder1_rFD_calendar_FNP" class="rcFastPrev" title="<<" href="#">&lt;&lt;</a><a id="ctl00_ContentPlaceHolder1_rFD_calendar_NP" class="rcPrev" title="<" href="#">&lt;</a><a id="ctl00_ContentPlaceHolder1_rFD_calendar_FNN" class="rcFastNext" title=">>" href="#">&gt;&gt;</a><a id="ctl00_ContentPlaceHolder1_rFD_calendar_NN" class="rcNext" title=">" href="#">&gt;</a><span id="ctl00_ContentPlaceHolder1_rFD_calendar_Title" class="rcTitle">October 2018</span>
                                                                </div>
                                                                <div class="rcMain">
                                                                    <table id="ctl00_ContentPlaceHolder1_rFD_calendar_Top" class="rcMainTable" cellspacing="0" summary="Table containing all dates for the currently selected month.">
                                                                        <caption>
                                                                            <span style="display:none;">October 2018</span>
                                                                        </caption>
                                                                        <thead>
                                                                            <tr class="rcWeek">
                                                                                <th class="rcViewSel" scope="col">&nbsp;</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_1" title="Sunday" scope="col" abbr="Sun">S</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_2" title="Monday" scope="col" abbr="Mon">M</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_3" title="Tuesday" scope="col" abbr="Tue">T</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_4" title="Wednesday" scope="col" abbr="Wed">W</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_5" title="Thursday" scope="col" abbr="Thu">T</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_6" title="Friday" scope="col" abbr="Fri">F</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_cs_7" title="Saturday" scope="col" abbr="Sat">S</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_rs_1" scope="row">40</th>
                                                                                <td class="rcOtherMonth" title="Sunday, September 30, 2018"><a href="#">30</a></td>
                                                                                <td title="Monday, October 01, 2018"><a href="#">1</a></td>
                                                                                <td title="Tuesday, October 02, 2018"><a href="#">2</a></td>
                                                                                <td title="Wednesday, October 03, 2018"><a href="#">3</a></td>
                                                                                <td title="Thursday, October 04, 2018"><a href="#">4</a></td>
                                                                                <td title="Friday, October 05, 2018"><a href="#">5</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 06, 2018"><a href="#">6</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_rs_2" scope="row">41</th>
                                                                                <td class="rcWeekend" title="Sunday, October 07, 2018"><a href="#">7</a></td>
                                                                                <td title="Monday, October 08, 2018"><a href="#">8</a></td>
                                                                                <td title="Tuesday, October 09, 2018"><a href="#">9</a></td>
                                                                                <td title="Wednesday, October 10, 2018"><a href="#">10</a></td>
                                                                                <td title="Thursday, October 11, 2018"><a href="#">11</a></td>
                                                                                <td title="Friday, October 12, 2018"><a href="#">12</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 13, 2018"><a href="#">13</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_rs_3" scope="row">42</th>
                                                                                <td class="rcWeekend" title="Sunday, October 14, 2018"><a href="#">14</a></td>
                                                                                <td title="Monday, October 15, 2018"><a href="#">15</a></td>
                                                                                <td title="Tuesday, October 16, 2018"><a href="#">16</a></td>
                                                                                <td title="Wednesday, October 17, 2018"><a href="#">17</a></td>
                                                                                <td title="Thursday, October 18, 2018"><a href="#">18</a></td>
                                                                                <td title="Friday, October 19, 2018"><a href="#">19</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 20, 2018"><a href="#">20</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_rs_4" scope="row">43</th>
                                                                                <td class="rcWeekend" title="Sunday, October 21, 2018"><a href="#">21</a></td>
                                                                                <td title="Monday, October 22, 2018"><a href="#">22</a></td>
                                                                                <td title="Tuesday, October 23, 2018"><a href="#">23</a></td>
                                                                                <td title="Wednesday, October 24, 2018"><a href="#">24</a></td>
                                                                                <td title="Thursday, October 25, 2018"><a href="#">25</a></td>
                                                                                <td title="Friday, October 26, 2018"><a href="#">26</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 27, 2018"><a href="#">27</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_rs_5" scope="row">44</th>
                                                                                <td class="rcWeekend" title="Sunday, October 28, 2018"><a href="#">28</a></td>
                                                                                <td title="Monday, October 29, 2018"><a href="#">29</a></td>
                                                                                <td title="Tuesday, October 30, 2018"><a href="#">30</a></td>
                                                                                <td title="Wednesday, October 31, 2018"><a href="#">31</a></td>
                                                                                <td class="rcOtherMonth" title="Thursday, November 01, 2018"><a href="#">1</a></td>
                                                                                <td class="rcOtherMonth" title="Friday, November 02, 2018"><a href="#">2</a></td>
                                                                                <td class="rcOtherMonth" title="Saturday, November 03, 2018"><a href="#">3</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rFD_calendar_Top_rs_6" scope="row">45</th>
                                                                                <td class="rcOtherMonth" title="Sunday, November 04, 2018"><a href="#">4</a></td>
                                                                                <td class="rcOtherMonth" title="Monday, November 05, 2018"><a href="#">5</a></td>
                                                                                <td class="rcOtherMonth" title="Tuesday, November 06, 2018"><a href="#">6</a></td>
                                                                                <td class="rcOtherMonth" title="Wednesday, November 07, 2018"><a href="#">7</a></td>
                                                                                <td class="rcOtherMonth" title="Thursday, November 08, 2018"><a href="#">8</a></td>
                                                                                <td class="rcOtherMonth" title="Friday, November 09, 2018"><a href="#">9</a></td>
                                                                                <td class="rcOtherMonth" title="Saturday, November 10, 2018"><a href="#">10</a></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <input type="hidden" name="ctl00_ContentPlaceHolder1_rFD_calendar_SD" id="ctl00_ContentPlaceHolder1_rFD_calendar_SD" value="[]"><input type="hidden" name="ctl00_ContentPlaceHolder1_rFD_calendar_AD" id="ctl00_ContentPlaceHolder1_rFD_calendar_AD" value="[[1980,1,1],[2099,12,30],[2018,10,27]]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a title="Open the time view popup." href="#" id="ctl00_ContentPlaceHolder1_rFD_timePopupLink" class="rcTimePopup">Open the time view popup.</a>
                                                            <div id="ctl00_ContentPlaceHolder1_rFD_timeView_wrapper" style="display:none;">
                                                                <div id="ctl00_ContentPlaceHolder1_rFD_timeView">
                                                                    <table id="ctl00_ContentPlaceHolder1_rFD_timeView_tdl" class="RadCalendarTimeView RadCalendarTimeView_Default" summary="Table holding time picker for selecting time of day." cellspacing="0">
                                                                        <caption>
                                                                            <span style="display: none">Time picker</span>
                                                                        </caption>
                                                                        <tbody>
                                                                            <tr>
                                                                                <th colspan="3" scope="col" class="rcHeader">Time Picker</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">12:00 AM</a></td>
                                                                                <td><a href="#">1:00 AM</a></td>
                                                                                <td><a href="#">2:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">3:00 AM</a></td>
                                                                                <td><a href="#">4:00 AM</a></td>
                                                                                <td><a href="#">5:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">6:00 AM</a></td>
                                                                                <td><a href="#">7:00 AM</a></td>
                                                                                <td><a href="#">8:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">9:00 AM</a></td>
                                                                                <td><a href="#">10:00 AM</a></td>
                                                                                <td><a href="#">11:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">12:00 PM</a></td>
                                                                                <td><a href="#">1:00 PM</a></td>
                                                                                <td><a href="#">2:00 PM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">3:00 PM</a></td>
                                                                                <td><a href="#">4:00 PM</a></td>
                                                                                <td><a href="#">5:00 PM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">6:00 PM</a></td>
                                                                                <td><a href="#">7:00 PM</a></td>
                                                                                <td><a href="#">8:00 PM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">9:00 PM</a></td>
                                                                                <td><a href="#">10:00 PM</a></td>
                                                                                <td><a href="#">11:00 PM</a></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <input id="ctl00_ContentPlaceHolder1_rFD_timeView_ClientState" name="ctl00_ContentPlaceHolder1_rFD_timeView_ClientState" type="hidden" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input id="ctl00_ContentPlaceHolder1_rFD_ClientState" name="ctl00_ContentPlaceHolder1_rFD_ClientState" type="hidden" autocomplete="off">
                                        </div>
                                    </li>
                                    <li class="width-20-per">
                                        <div id="ctl00_ContentPlaceHolder1_rTD_wrapper" class="RadPicker RadPicker_Default date" placeholder="Đến ngày" style="display:inline-block;width:100%;">
                                            <input style="visibility:hidden;display:block;float:right;margin:0 0 -1px -1px;width:1px;height:1px;overflow:hidden;border:0;padding:0;" id="ctl00_ContentPlaceHolder1_rTD" name="ctl00$ContentPlaceHolder1$rTD" type="text" class="rdfd_ radPreventDecorate" value="" title="Visually hidden input created for functionality purposes.">
                                            <table cellspacing="0" class="rcTable rcSingle" summary="Table holding date picker control for selection of dates." style="width:100%;">
                                                <caption style="display:none;">
                                                    RadDatePicker
                                                </caption>
                                                <thead style="display:none;">
                                                    <tr>
                                                        <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="rcInputCell" style="width:100%;"><span id="ctl00_ContentPlaceHolder1_rTD_dateInput_wrapper" class="riSingle RadInput RadInput_Default" style="display:block;width:100%;"><input id="ctl00_ContentPlaceHolder1_rTD_dateInput" name="ctl00$ContentPlaceHolder1$rTD$dateInput" class="riTextBox riEmpty radPreventDecorate" value="Đến ngày" type="text"><input id="ctl00_ContentPlaceHolder1_rTD_dateInput_ClientState" name="ctl00_ContentPlaceHolder1_rTD_dateInput_ClientState" type="hidden" autocomplete="off" value="{&quot;enabled&quot;:true,&quot;emptyMessage&quot;:&quot;Đến ngày&quot;,&quot;validationText&quot;:&quot;&quot;,&quot;valueAsString&quot;:&quot;&quot;,&quot;minDateStr&quot;:&quot;1980-01-01-00-00-00&quot;,&quot;maxDateStr&quot;:&quot;2099-12-31-00-00-00&quot;,&quot;lastSetTextBoxValue&quot;:&quot;Đến ngày&quot;}"></span></td>
                                                        <td>
                                                            <a title="Open the calendar popup." href="#" id="ctl00_ContentPlaceHolder1_rTD_popupButton" class="rcCalPopup">Open the calendar popup.</a>
                                                            <div id="ctl00_ContentPlaceHolder1_rTD_calendar" style="display: none" class="RadCalendar RadCalendar_Default">
                                                                <div class="rcTitlebar">
                                                                    <a id="ctl00_ContentPlaceHolder1_rTD_calendar_FNP" class="rcFastPrev" title="<<" href="#">&lt;&lt;</a><a id="ctl00_ContentPlaceHolder1_rTD_calendar_NP" class="rcPrev" title="<" href="#">&lt;</a><a id="ctl00_ContentPlaceHolder1_rTD_calendar_FNN" class="rcFastNext" title=">>" href="#">&gt;&gt;</a><a id="ctl00_ContentPlaceHolder1_rTD_calendar_NN" class="rcNext" title=">" href="#">&gt;</a><span id="ctl00_ContentPlaceHolder1_rTD_calendar_Title" class="rcTitle">October 2018</span>
                                                                </div>
                                                                <div class="rcMain">
                                                                    <table id="ctl00_ContentPlaceHolder1_rTD_calendar_Top" class="rcMainTable" cellspacing="0" summary="Table containing all dates for the currently selected month.">
                                                                        <caption>
                                                                            <span style="display:none;">October 2018</span>
                                                                        </caption>
                                                                        <thead>
                                                                            <tr class="rcWeek">
                                                                                <th class="rcViewSel" scope="col">&nbsp;</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_1" title="Sunday" scope="col" abbr="Sun">S</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_2" title="Monday" scope="col" abbr="Mon">M</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_3" title="Tuesday" scope="col" abbr="Tue">T</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_4" title="Wednesday" scope="col" abbr="Wed">W</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_5" title="Thursday" scope="col" abbr="Thu">T</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_6" title="Friday" scope="col" abbr="Fri">F</th>
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_cs_7" title="Saturday" scope="col" abbr="Sat">S</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_rs_1" scope="row">40</th>
                                                                                <td class="rcOtherMonth" title="Sunday, September 30, 2018"><a href="#">30</a></td>
                                                                                <td title="Monday, October 01, 2018"><a href="#">1</a></td>
                                                                                <td title="Tuesday, October 02, 2018"><a href="#">2</a></td>
                                                                                <td title="Wednesday, October 03, 2018"><a href="#">3</a></td>
                                                                                <td title="Thursday, October 04, 2018"><a href="#">4</a></td>
                                                                                <td title="Friday, October 05, 2018"><a href="#">5</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 06, 2018"><a href="#">6</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_rs_2" scope="row">41</th>
                                                                                <td class="rcWeekend" title="Sunday, October 07, 2018"><a href="#">7</a></td>
                                                                                <td title="Monday, October 08, 2018"><a href="#">8</a></td>
                                                                                <td title="Tuesday, October 09, 2018"><a href="#">9</a></td>
                                                                                <td title="Wednesday, October 10, 2018"><a href="#">10</a></td>
                                                                                <td title="Thursday, October 11, 2018"><a href="#">11</a></td>
                                                                                <td title="Friday, October 12, 2018"><a href="#">12</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 13, 2018"><a href="#">13</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_rs_3" scope="row">42</th>
                                                                                <td class="rcWeekend" title="Sunday, October 14, 2018"><a href="#">14</a></td>
                                                                                <td title="Monday, October 15, 2018"><a href="#">15</a></td>
                                                                                <td title="Tuesday, October 16, 2018"><a href="#">16</a></td>
                                                                                <td title="Wednesday, October 17, 2018"><a href="#">17</a></td>
                                                                                <td title="Thursday, October 18, 2018"><a href="#">18</a></td>
                                                                                <td title="Friday, October 19, 2018"><a href="#">19</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 20, 2018"><a href="#">20</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_rs_4" scope="row">43</th>
                                                                                <td class="rcWeekend" title="Sunday, October 21, 2018"><a href="#">21</a></td>
                                                                                <td title="Monday, October 22, 2018"><a href="#">22</a></td>
                                                                                <td title="Tuesday, October 23, 2018"><a href="#">23</a></td>
                                                                                <td title="Wednesday, October 24, 2018"><a href="#">24</a></td>
                                                                                <td title="Thursday, October 25, 2018"><a href="#">25</a></td>
                                                                                <td title="Friday, October 26, 2018"><a href="#">26</a></td>
                                                                                <td class="rcWeekend" title="Saturday, October 27, 2018"><a href="#">27</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_rs_5" scope="row">44</th>
                                                                                <td class="rcWeekend" title="Sunday, October 28, 2018"><a href="#">28</a></td>
                                                                                <td title="Monday, October 29, 2018"><a href="#">29</a></td>
                                                                                <td title="Tuesday, October 30, 2018"><a href="#">30</a></td>
                                                                                <td title="Wednesday, October 31, 2018"><a href="#">31</a></td>
                                                                                <td class="rcOtherMonth" title="Thursday, November 01, 2018"><a href="#">1</a></td>
                                                                                <td class="rcOtherMonth" title="Friday, November 02, 2018"><a href="#">2</a></td>
                                                                                <td class="rcOtherMonth" title="Saturday, November 03, 2018"><a href="#">3</a></td>
                                                                            </tr>
                                                                            <tr class="rcRow">
                                                                                <th id="ctl00_ContentPlaceHolder1_rTD_calendar_Top_rs_6" scope="row">45</th>
                                                                                <td class="rcOtherMonth" title="Sunday, November 04, 2018"><a href="#">4</a></td>
                                                                                <td class="rcOtherMonth" title="Monday, November 05, 2018"><a href="#">5</a></td>
                                                                                <td class="rcOtherMonth" title="Tuesday, November 06, 2018"><a href="#">6</a></td>
                                                                                <td class="rcOtherMonth" title="Wednesday, November 07, 2018"><a href="#">7</a></td>
                                                                                <td class="rcOtherMonth" title="Thursday, November 08, 2018"><a href="#">8</a></td>
                                                                                <td class="rcOtherMonth" title="Friday, November 09, 2018"><a href="#">9</a></td>
                                                                                <td class="rcOtherMonth" title="Saturday, November 10, 2018"><a href="#">10</a></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <input type="hidden" name="ctl00_ContentPlaceHolder1_rTD_calendar_SD" id="ctl00_ContentPlaceHolder1_rTD_calendar_SD" value="[]"><input type="hidden" name="ctl00_ContentPlaceHolder1_rTD_calendar_AD" id="ctl00_ContentPlaceHolder1_rTD_calendar_AD" value="[[1980,1,1],[2099,12,30],[2018,10,27]]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a title="Open the time view popup." href="#" id="ctl00_ContentPlaceHolder1_rTD_timePopupLink" class="rcTimePopup">Open the time view popup.</a>
                                                            <div id="ctl00_ContentPlaceHolder1_rTD_timeView_wrapper" style="display:none;">
                                                                <div id="ctl00_ContentPlaceHolder1_rTD_timeView">
                                                                    <table id="ctl00_ContentPlaceHolder1_rTD_timeView_tdl" class="RadCalendarTimeView RadCalendarTimeView_Default" summary="Table holding time picker for selecting time of day." cellspacing="0">
                                                                        <caption>
                                                                            <span style="display: none">Time picker</span>
                                                                        </caption>
                                                                        <tbody>
                                                                            <tr>
                                                                                <th colspan="3" scope="col" class="rcHeader">Time Picker</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">12:00 AM</a></td>
                                                                                <td><a href="#">1:00 AM</a></td>
                                                                                <td><a href="#">2:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">3:00 AM</a></td>
                                                                                <td><a href="#">4:00 AM</a></td>
                                                                                <td><a href="#">5:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">6:00 AM</a></td>
                                                                                <td><a href="#">7:00 AM</a></td>
                                                                                <td><a href="#">8:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">9:00 AM</a></td>
                                                                                <td><a href="#">10:00 AM</a></td>
                                                                                <td><a href="#">11:00 AM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">12:00 PM</a></td>
                                                                                <td><a href="#">1:00 PM</a></td>
                                                                                <td><a href="#">2:00 PM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">3:00 PM</a></td>
                                                                                <td><a href="#">4:00 PM</a></td>
                                                                                <td><a href="#">5:00 PM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">6:00 PM</a></td>
                                                                                <td><a href="#">7:00 PM</a></td>
                                                                                <td><a href="#">8:00 PM</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><a href="#">9:00 PM</a></td>
                                                                                <td><a href="#">10:00 PM</a></td>
                                                                                <td><a href="#">11:00 PM</a></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <input id="ctl00_ContentPlaceHolder1_rTD_timeView_ClientState" name="ctl00_ContentPlaceHolder1_rTD_timeView_ClientState" type="hidden" autocomplete="off">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input id="ctl00_ContentPlaceHolder1_rTD_ClientState" name="ctl00_ContentPlaceHolder1_rTD_ClientState" type="hidden" autocomplete="off">
                                        </div>
                                    </li>
                                    <li class="width-15-per">
                                        <input type="submit" name="ctl00$ContentPlaceHolder1$btnSear" value="LỌC TÌM KIẾM" id="ContentPlaceHolder1_btnSear" class="btn btn-success btn-block pill-btn primary-btn">
                                    </li>
                                </ul>
                            </aside>
                        </div>
                        <div class="container">
                            <p style="margin: 10px 0;"><a class="btn btn-success btn-block pill-btn primary-btn" onclick="requestall();" href="javascript:;">Yêu cầu giao hàng</a></p>
                            <input type="button" name="ctl00$ContentPlaceHolder1$btnAllrequest" value="" onclick="javascript:__doPostBack('ctl00$ContentPlaceHolder1$btnAllrequest','')" id="ContentPlaceHolder1_btnAllrequest" style="display: none">
                        </div>
                    </div>
                    <div class="table-panel">
                        <div class="table-panel-main full-width">
                            <table>
                                <tbody>
                                    <tr>
                                        <th class="id" style="width: 1%;">ID</th>
                                        <th class="pro" style="width: 1%;">Ảnh sản phẩm</th>
                                        <th class="pro" style="width: 5%;">Tên Shop</th>
                                        <th class="qty" style="width: 5%;">Website</th>
                                        <th class="price" style="width: 5%;">Tổng tiền</th>
                                        <th class="price" style="width: 5%;">Số Tiền phải cọc</th>
                                        <th class="price" style="width: 5%;">Tiền đã cọc</th>
                                        <th class="date" style="width: 5%;">Ngày đặt hàng</th>
                                        <th class="status" style="width: 5%;">Trạng thái đơn hàng</th>
                                        <th class="status" style="width: 5%;">Giao hàng</th>
                                        <th class="status" style="width: 5%;"></th>
                                    </tr>
                                    <?php
                                        $total_price = 0;
                                        $total_price_ship = 0;
                                        foreach ($order_detail as $key => $pro) {
                                        $total_price += $pro['price_vnd'] * $pro['quantity'];
                                        $total_price_ship += $pro['price_ship'];
                                    ?>
                                    <tr>
                                        <td style="text-align: center"><? echo $pro['id'] ?></td>
                                        <td style="text-align: center"><img src="<? echo $pro['image_origin'] ?>" alt=""></td>
                                        <td style="text-align: center"><a href="<? echo $pro['shop_link'] ?>" target="_blank"><? echo $pro['shop_name'] ?></a></td>
                                        <td style="text-align: center"><? echo $pro['site'] ?></td>
                                        <td style="text-align: center"><?php echo number_format($pro['price_vnd'] * $pro['quantity']); ?></td>
                                        <td style="text-align: center"><?php echo number_format(($pro['price_vnd'] * $pro['quantity']) * 0.5); ?></td>
                                        <td style="text-align: center">pending</td>
                                        <td style="text-align: center"><? echo $pro['date_order'] ?></td>
                                        <td style="text-align: center;white-space: nowrap;"><span class="bg-red"><? echo $pro['status_name'] ?></span></td>
                                        <td style="text-align: center"><input type="checkbox" class="ycgh-chk" data-id="73408" data-status="0" disabled="disabled"></td>
                                        <td style="text-align: center">
                                            <a href="/chi-tiet-don-hang/73408" class="viewmore-orderlist" style="margin-bottom:5px;">Chi tiết</a><br>
                                            <a href="javascript:;" onclick="depositOrder('73408')" class="bg-orange" style="float:left;width:100%;margin-bottom:5px;">Đặt cọc</a><br>
                                            <a href="javascript:;" onclick="deleteOrderItem(<? echo $pro['id'] ?>, <? echo $_SESSION['orders']['id'] ?>)" class=" bg-black" style="float:left;width:100%;margin-bottom:5px;">Hủy đơn hàng</a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                            <input type="hidden" value="<? echo $total_price + $total_price_ship ?>" class="total_price">
                            <div class="pagination">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function(){

        var total_price = $('.total_price').val();
        $('#total_price').text(total_price);

    })

    function deleteOrderItem(id) {

        if(confirm('Bạn chắc chắn muốn xóa sản phẩm này')) {
            $.ajax({
                url: "/home/delete_cart.php",
                data: {
                    'id' : id
                },
                type: 'POST',
                dataType : 'json',            
                success: function (val) {
                    if(val == 1) {
                        window.location.href = '/gio-hang';
                    }
                }
            });
        }

    }

</script>

<style>
    .black {
        color: #2a363b;
    }

    ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .m-color {
        color: #e84a5f;
    }

    b, strong, .b {
        font-weight: bold;
    }

    b, strong {
        font-weight: bolder;
    }

    .page.orders-list .statistics li:last-child {
        border: none;
    }

    .page.orders-list .statistics li {
        display: inline-block;
        padding-right: 10px;
        margin-right: 10px;
        border-right: 1px solid #2a363b;
        line-height: 1;
    }

    .page.orders-list .stat-detail {
        width: 100%;
        margin: 20px 0;
        border-top: 1px solid #e1e1e1;
        border-bottom: 1px solid #e1e1e1;
        display: block;
        padding: 5px 0;
    }

    table {
        border-collapse: collapse;
    }

    .page.orders-list .stat-detail th, .page.orders-list .stat-detail td {
        padding: 10px 0;
        vertical-align: top;
        text-align: left;
    }

    .page.orders-list .stat-detail th {
        padding-right: 35px;
    }

    .page.orders-list .stat-detail td {
        display: inline-block;
        width: 395px;
    }

    article, aside, details, figcaption, figure, footer, header, main, menu, nav, section {
        display: block;
    }

    .clear {
        zoom: 1;
    }

    input, select {
        border: 1px solid #e1e1e1;
        background: #fff;
        padding: 10px;
        height: 40px;
        line-height: 20px;
        color: #000;
        display: block;
        width: 100%;
        border-radius: 0;
    }

    .RadPicker_Default .rcCalPopup, .RadPicker_Default .rcTimePopup {
        display: none;
    }

    html body .riSingle .riTextBox[type="text"] {
        border: 1px solid #e1e1e1;
        background: #fff;
        padding: 10px;
        height: 40px;
        line-height: 20px;
        color: #000;
        display: block;
        width: 100%;
        border-radius: 0;
    }

    .page .filters {
        background: #ebebeb;
        border: 1px solid #e1e1e1;
        font-weight: bold;
        padding: 20px;
        margin-bottom: 20px;
    }

    .page.orders-list .filters .lbl {
        padding-right: 50px;
    }

    .page .filters ul li {
        display: inline-block;
        text-align: center;
        padding-right: 2px;
    }

    .page .filters ul li {
        padding-right: 4px;
    }

    .page .filters input {
        padding: 2px 10px;
    }

    .page.orders-list .filters input.order-id {
        width: 270px;
    }

    .page .status-list > li {
        display: block;
        float: left;
        margin: 0 1px 10px 0;
    }

    .page .status-list a {
        height: 40px;
        line-height: 40px;
        display: block;
        background: #f8f8f8;
        color: #959595;
        font-weight: bold;
        padding: 0 15px;
    }

        .page .status-list li.current > a, .page .status-list a:hover {
            background: #e84a5f;
            color: #fff;
        }

    .width-20-per {
        width: 20%;
    }

    .width-15-per {
        width: 15%;
    }

    .page.orders-list .tbl-subtotal {
        margin-bottom: 20px;
    }

        .page.orders-list .tbl-subtotal th {
            padding-right: 60px;
        }

        .page.orders-list .tbl-subtotal td {
            padding: 8px 30px 8px 0;
        }
</style>
