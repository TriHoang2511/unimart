$(document).ready(function(){
    // ====== Khi load trang: cập nhật tổng giá ban đầu ======
    var initial_ids = [];
    $('.cart_selected:checked').each(function(){
        initial_ids.push($(this).val());
    });

    if (initial_ids.length > 0) {
        $.ajax({
            url: '?mod=cart&action=updateStatusCart',
            method: 'POST',
            data: { init_ids: initial_ids }, // gửi mảng ban đầu
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $("#total-price span").text(res.total);
                }
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    }
    // Cập nhật số lượng
    $(".num-order").change(function(){
        var num_order = $(this).val();
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: '?mod=cart&action=updateCart',
            method: 'POST',
            data: {num_order: num_order, id: id},
            dataType: 'json',
            success: function(data) {
                $(".sub-total-" + id).text(data.sub_total);
                $("#total-price span").text(data.total);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });
    });

    // CheckAll
    $('#checkAll').change(function() {
        var status = $(this).prop('checked') ? 1 : 0;

        // Set tất cả checkbox sản phẩm theo trạng thái checkAll
        $('.cart_selected').prop('checked', status ? true : false);

        // Lấy danh sách id các sp
        var ids = [];
        $('.cart_selected').each(function(){
            ids.push($(this).val());
        });

        // AJAX cập nhật cho tất cả
        $.ajax({
            url: '?mod=cart&action=updateStatusCartAll',
            method: 'POST',
            data: {status: status, ids: ids},
            dataType: 'json',
            success: function(res){
                $("#total-price span").text(res.total);
            }
        });
    });

    // Khi tick từng sản phẩm
    $('.cart_selected').change(function () {
        var status = $(this).prop('checked') ? 1 : 0;
        var id = $(this).val();

        $.ajax({
            url: '?mod=cart&action=updateStatusCart',
            method: 'POST',
            data: {status: status, id: id},
            dataType: 'json',
            success: function(data) {
                if(data.success){
                    $("#total-price span").text(data.total);
                }
                // Đồng bộ lại checkAll (nếu tất cả sp đều được tick thì checkAll cũng tick)
                let total = $('.cart_selected').length;
                let checked = $('.cart_selected:checked').length;
                $('#checkAll').prop('checked', total === checked);
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });
    
});
