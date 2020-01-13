$(document).ready(function () {

    var base_url = $("#group_id").val();
    var controller = $("#controller").val();
    var method = $("#method").val();

    $("#button-access").css('display', 'none');

    $("#group_id").change(function () {
        id = $("#group_id").val();
        if (id == '') {
            id = 0;
        }
        $.ajax({
            type: "POST",
            url: base_url + "setting_system/list_accses_ajax/" + id,
            data: id,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            beforeSend: function () {
                $("#loading-image").css("display", "block");
                $("#button-access").css('display', 'none');
            },
            success: function (data) {
                $("#loading-image").css("display", "none");
                $("#content_ajax").html(data);
                $("#button-access").css('display', 'block');
            },
            error: function (jqXHR, textStatus, errorThrown, data) {
                alert('error');
            }
        });
    });

});