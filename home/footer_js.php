<script src="/js/js_web/sweet-alert.js" type="text/javascript"></script>
<script src="/js/js_web/SmoothScroll.js"></script>
<script src="/plugin/slick/slick.min.js"></script>
<script src="/js/js_web/wow.min.js"></script>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_roqaeKE7ULRUNw5wG0i8TqvWRsSJ2JY"></script>
<script src="/js/js_web/master.js"></script>
<script src="/js/js_web/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        new WOW().init();
        LoadSelect();
        setActiveMenu(window.location.pathname);
    });


    function setActive() {
        $('body').addClass('child-page');
    }

    function setActiveMenu(path) {

        if (path.toLowerCase().indexOf('/trang-chu') != -1) {
            $('.main-nav li.trangchu').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/ve-chung-toi') != -1) {
            $('.main-nav li.gioithieu').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/huong-dan') != -1) {
            $('.main-nav li.huongdan').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/bieu-phi') != -1) {
            $('.main-nav li.bieuphi').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/chinh-sach') != -1) {
            $('.main-nav li.chinhsach').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/tin-tuc') != -1) {
            $('.main-nav li.tintuc').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/cong-cu') != -1) {
            $('.main-nav li.congcudathang').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/dat-hang-nhanh') != -1) {
            $('.main-nav li.dathangnhanh').addClass("active");
        }
        else if (path.toLowerCase().indexOf('/lien-he') != -1) {
            $('.main-nav li.lienhe').addClass("active");
        }
    }

    $('.dropdown-custom').click(function () {
        var parent = $(this);
        if (parent.hasClass('open')) {
            parent.children('.sub-menu-wrap').stop().slideUp(300, function () {
                parent.removeClass('open');
            });
        }
        else {
            parent.find('.sub-menu-wrap').stop().slideDown(300, function () {
                parent.addClass('open');
            });
        }
    });
    $("html").click(function () {
        var parent = $('.dropdown-custom');
        if (parent.hasClass('open')) {
            parent.children('.sub-menu-wrap').stop().slideUp(300, function () {
                parent.removeClass('open');
            });
        }
    });


    $('.prefix_number').select2();

</script>
<style>
    .dropdown-custom {
        position: relative;
    }
</style>