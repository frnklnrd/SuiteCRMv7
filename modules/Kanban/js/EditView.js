$(function () {
    $('#target_module, #target_field').on('change', function () {
        var form = document.getElementById('EditView');
        form.action.value = 'Save';
        form.return_module.value = 'Kanban';
        form.return_action.value = 'EditView';
        form.return_id.value = form.record.value;
        
        if (check_form('EditView')) {
            SUGAR.ajaxUI.submitForm(form);
        }
    });
});