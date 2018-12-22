$('#roleform-users').on('blur', function () {
    $(this).val('');
}).on('autocompleteselect', function (e, ui) {
    var $tbody = $('#role-users tbody'),
        $tr = $tbody.find('input[value="' + ui.item.id + '"]').closest('tr');

    if ($tr.length == 0) {
        $tr = $tbody.find('tr:first').clone();
        $tr.find(':input').prop('disabled', false).val(ui.item.id);
        $tr.find('td:first > span').text(ui.item.value);
        $tbody.append($tr);
    }
    $tr.effect('highlight', {}, 1000);

    $(this).val('');
    return false;
});
$('#role-users').on('click', 'a.remove', function (e) {
    e.preventDefault();
    $(this).closest('tr').remove();
});
