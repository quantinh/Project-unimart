//Lấy từ thư viện jquery tổng hợp form slider
$(document).ready(function () {
    //Click vào class mắt hiển thị mật khẩu
    $('.eye').click(function () {
        // thêm vào class open vào cha
        $(this).toggleClass('open');
        // thay đổi class mắt ko gạch thành mắt 1 gạch vào thằng con
        $(this).children('i').toggleClass('fa-eye-slash fa-eye');
        // Nếu có class open thì xuất ra text->pass
        if ($(this).hasClass('open')) {
            // alert('type text');
            // Gọi đến thằng bên trên(prev) và thay đổi thuộc tính type="text"
            $(this).prev().attr('type', 'text')
        } else {
            //Thao tác ngược lại gọi đến thằng bên trên(prev) và thay đổi thuộc tính type="password"
            $(this).prev().attr('type', 'password')
        }
    });

    //Slider-sub hiệu ứng
    $('#slide').owlCarousel({
        loop: true,
        dots: false,
        margin: 0,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            700: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });

    //Slider hiệu ứng 2
    var owl = $('#list-featured-post');
    owl.owlCarousel({
        items: 4,
        loop: true,
        dots: false,
        nav: true,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true
    });
    $('.play').on('click', function () {
        owl.trigger('play.owl.autoplay', [2000])
    });
    $('.stop').on('click', function () {
        owl.trigger('stop.owl.autoplay')
    });

    //Slide logo
    var owl = $('#slide-logo');
    owl.owlCarousel({
        items: 4,
        loop: true,
        dots: false,
        nav: false,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 1000,
        autoplayHoverPause: true
    });
    $('.play').on('click', function () {
        owl.trigger('play.owl.autoplay', [1000])
    });
    $('.stop').on('click', function () {
        owl.trigger('stop.owl.autoplay')
    });

    //Slider chi tiết sản phẩm
    var slider = $('#slider-wp .section-detail');
    slider.owlCarousel({
        autoPlay: 4500,
        navigation: false,
        navigationText: false,
        paginationNumbers: false,
        pagination: true,
        items: 1,
        itemsDesktop: [1000, 1],
        itemsDesktopSmall: [900, 1],
        itemsTablet: [600, 1],
        itemsMobile: true
    });

    //Phóng to thu nhỏ chi tiết sản phẩm
    $("#zoom").elevateZoom({
        gallery: 'list-thumb',
        cursor: 'pointer',
        galleryActiveClass: 'active',
        imageCrossfade: true,
        loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'
    });

    //Danh sách hình ảnh ảnh bên dưới chi tiết zoom
    var list_thumb = $('#list-thumb');
    list_thumb.owlCarousel({
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 5,
        itemsDesktop: [1000, 5],
        itemsDesktopSmall: [900, 5],
        itemsTablet: [768, 5],
        itemsMobile: true
    });

    //Chức năng ảnh
    var feature_product = $('#feature-product-wp .list-item');
    feature_product.owlCarousel({
        autoPlay: true,
        navigation: true,
        navigationText: false,
        paginationNumbers: false,
        pagination: false,
        stopOnHover: true,
        items: 4,
        itemsDesktop: [1000, 4],
        itemsDesktopSmall: [800, 3],
        itemsTablet: [600, 2],
        itemsMobile: [375, 1]
    });
});

//Xem thêm mô tả chi tiết sản phẩm
$(function () {
    $('#see-more').click(function () {
        $('#post-product-wp').toggleClass('show-full');
        var x = $('#see-more').text();
        if (x == "Thu gọn") {
            $('#see-more').text('Xem thêm');
            $('#see-more').css('top', '92%');
            $('.bg-article').css('display', 'block');
        } else {
            $('#see-more').text('Thu gọn');
            $('#see-more').css('top', '100%');
            $('.bg-article').css('display', 'none');
        }
    });
});

//Chọn số lượng tăng giảm trang chi tiết
$(function () {
    var $elm_value = $(".value");
    //Giảm số lượng
    $('.increase').click(function () {
        let $value_increase = parseInt($elm_value.val());
        if ($value_increase <= 1) {
            alert("Giá trị giảm không được bé hơn 1");
            return false;
        }
        $elm_value.val($value_increase - 1);
    })
    //Tăng số lượng
    $('.reduction').click(function () {
        let $value_increase = parseInt($elm_value.val());
        $elm_value.val($value_increase + 1);
    });
});

//Chọn số lượng tăng giảm trang giỏ hàng
$(function () {
    var $elm_value = $(".num-order");
    //Giảm số lượng
    $('.increase').click(function () {
        let $value_increase = parseInt($elm_value.val());
        if ($value_increase <= 1) {
            alert("Giá trị giảm không được bé hơn 1");
            return false;
        }
        $elm_value.val($value_increase - 1);
    })
    //Tăng số lượng
    $('.reduction').click(function () {
        let $value_increase = parseInt($elm_value.val());
        $elm_value.val($value_increase + 1);
    });
})

//Thêm sản phẩm vào giỏ hàng ajax
$(document).ready(function () {
    //Ajax thêm vào giỏ hàng
    $('.add-to-cart').click(function() {
        $.ajax({
            url: $('#url').attr('url'),
            method: "POST",
            data: {
                id: $(this).attr('data-id'),
                _token: $('#token').val(),
            },
            success: function(data) {
                swal("Đã thêm sản phẩm vào giỏ hàng", "", "success");
                $('.header-cart-wrap span#num').text(data.num);
                $('.header-cart-list').html(data.list_cart);
                console.log(data.list_cart);
            }
        });
    });
});

//Tăng giảm số lượng sản phẩm trong giỏ hàng ajax
$(document).ready(function () {
    //Ajax giảm giá trị số lượng
    $('.minus').click(function() {
        var num_order = $(this).next().attr('value');
        if (num_order > 1) {
            num_order--;
        }
        $(this).next().attr('value', num_order);
        var num_order = $(this).next().attr('value');
        var rowId = $(this).next().attr('data-id');
        $.ajax({
            url: $('#url').attr('url'),
            method: "POST",
            data: {
                rowId: rowId,
                num_order: num_order,
                _token: $('#token').val(),
            },
            success: function(data) {
                $('.sub' + rowId).text(data.sub_total);
                $('#total-price>span').text(data.total);
                $('.header-cart-list').html(data.list_cart);
                $('.header-cart-wrap span#num').text(data.num);
            }
        });
    });

    //Ajax tăng giá trị số lượng
    $('.plus').click(function() {
        var num_order = $(this).prev().attr('value');
        var max = $(this).prev().attr('max');
        if (num_order < parseInt(max)) {
            num_order++;
        }
        $(this).prev().attr('value', num_order);
        var num_order = $(this).prev().attr('value');
        var rowId = $(this).prev().attr('data-id');
        $.ajax({
            url: $('#url').attr('url'),
            method: "POST",
            data: {
                rowId: rowId,
                num_order: num_order,
                _token: $('#token').val(),
            },
            success: function(data) {
                $('.sub' + rowId).text(data.sub_total);
                $('#total-price>span').text(data.total);
                $('.header-cart-wrap span#num').text(data.num);
                $('.header-cart-list').html(data.list_cart);
            }
        });
    });
});

window.addEventListener("load", function () {
    //Thêm class is-show hiển thị popup
    const modal = document.querySelector(".main-modal");
    //Kiểm tra khi class = "main-modal" thì mới cho thực hiện sự kiện
    if(modal) {
        //Thiết lập thời gian thêm class = "is-show" là 3s
        setTimeout(function() {
            modal.classList.add("is-show");
        }, 3000);
        //Click vào thì xóa đi class hiển thị popup
        document.body.addEventListener("click", function(event) {
            if(event.target.matches(".sale-off-close")) {
                //Truy vấn vào cha nó và xóa chính nó đi là nút dấu X
                const modal = event.target.parentNode.parentNode;
                modal.parentNode.removeChild(modal);
                //Nếu cái selector chúng ta nhấn là .modal thì truy cập vào cha nó và xóa chính nó đi.
            } else if(event.target.matches(".main-modal")) {
                event.target.parentNode.removeChild(event.target);
            }
        });
    }
});

//Menu bars on mobile
$('html').on('click', function (event) {
    var target = $(event.target);
    var menu = $('.menu-responsive');
    //Nếu có id = "btn-responsive" thì kiểm tra nếu không có class = "show-responsive-menu" thì thêm class đó vào
    if (target.is('#btn-responsive')) {
        if (!menu.hasClass('show-responsive-menu')) {
            menu.addClass('show-responsive-menu');
        //Ngược lại nếu có thì xóa class đó đi.
        } else {
            menu.removeClass('show-responsive-menu');
        }
    //Nếu không có id = "btn-responsive" thì khi click vào class="app" thì tiếp tục kiểm tra có class = "show-responsive-menu" thì xóa đi.thể thiện click ra ngoài ẩn menu responsive
    } else {
        $('.app').click(function () {
            if (menu.hasClass('show-responsive-menu')) {
                menu.removeClass('show-responsive-menu');
                return false;
            }
        });
    }
});
