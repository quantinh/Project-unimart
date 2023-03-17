$(document).ready(function() {
    //Đổ menu sub xuống trang admin.blade
    $('.nav-link.active .sub-menu').slideDown();
    $('.nav-link.active i').removeClass('fa-angle-right').addClass('fa-angle-down');
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function() {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function() {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });
});

$(document).ready(function () {
    //Thay đổi giá trị màu sắc
    $('#colorpicker').on('change', function () {
        $('#hexcolor').val(this.value);
    });
});


