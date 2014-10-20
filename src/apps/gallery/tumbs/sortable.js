/**
 * Created by storchovyy on 20.10.2014.
 */

$(".scms-gallery").children().uniqueId().end().sortable({
    update: function (event, ui) {
        var data = $(this).sortable('serialize');
        s.trace(data);
        var id = $('#MaterialID').val();
        $.ajax({
            data: {data: data, id: id},
            headers: { 'SJSAsync': 'true' },
            type: 'POST',
            url: 'gallery/sort',
            success: function(response) {
                s.trace(response);
                return response;

            }
        });
    }
});