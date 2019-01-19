$(document).ready(function () {

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

});

function submitType(id, type) {
    var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", type);
    $('#' + id ).append($(input));
    
    $( '#' + id ).submit();
}