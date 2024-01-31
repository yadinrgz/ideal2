$(document).ready(function () {
    $("#uploadForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "upload.php",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $("#response").html(response);
            },
        });
    });
});
