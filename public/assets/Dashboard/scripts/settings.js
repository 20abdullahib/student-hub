$(document).ready(function(){
    // Ajax for adding a new member via the nested form
    $('#addMemberForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: TEAM_MEMBERS_STORE_URL, // Defined in Blade via a script tag below
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('An error occurred while adding the team member.');
            }
        });
    });

    // Branch options for inline editing, provided by the server via a global variable
    var branchOptions = TEAM_BRANCHES;

    // Inline editing for text/select fields
    $('.editable').on('click', function(){
        var cell = $(this);
        // Prevent multiple inputs/selects in the same cell
        if(cell.find('input, select').length > 0) return;

        var originalText = cell.text().trim();
        var field = cell.data('field');
        var id = cell.closest('tr').data('id');
        var input;

        // Create a dropdown for role, branch_id, and year_joined; otherwise, use a text input
        if(field === 'role'){
            input = $('<select>', { class: 'form-select' });
            var roles = [
                { value: '', text: 'Select Role' },
                { value: 'OC', text: 'OC' },
                { value: 'BR', text: 'BR' },
                { value: 'HR', text: 'HR' },
                { value: 'IT', text: 'IT' }
            ];
            $.each(roles, function(index, role){
                var opt = $('<option>', { value: role.value, text: role.text });
                if(role.value === originalText){
                    opt.prop('selected', true);
                }
                input.append(opt);
            });
        } else if(field === 'branch_id'){
            input = $('<select>', { class: 'form-select' });
            input.append($('<option>', { value: '', text: 'Select Branch' }));
            $.each(branchOptions, function(index, branch){
                var opt = $('<option>', { value: branch.id, text: branch.name });
                if(String(branch.id) === originalText){
                    opt.prop('selected', true);
                }
                input.append(opt);
            });
        } else if(field === 'year_joined'){
            input = $('<select>', { class: 'form-select' });
            input.append($('<option>', { value: '', text: 'Select Year Joined' }));
            var startYear = 2018;
            var currentYear = new Date().getFullYear();
            for(var y = startYear; y <= currentYear; y++){
                var opt = $('<option>', { value: y, text: y });
                if(String(y) === originalText){
                    opt.prop('selected', true);
                }
                input.append(opt);
            }
        } else {
            input = $('<input>', {
                type: 'text',
                class: 'form-control',
                value: originalText
            });
        }

        cell.html(input);
        input.focus();

        // Use change event for selects and blur for text inputs
        if(input.is('select')){
            input.on('change', updateField);
        } else {
            input.on('blur', updateField);
        }

        function updateField(){
            var newValue = input.val();
            // If no change, revert to the original display
            if(newValue === originalText){
                cell.text(originalText);
                return;
            }
            $.ajax({
                url: TEAM_MEMBER_UPDATE_URL + '/' + id,
                method: 'PATCH',
                data: {
                    field: field,
                    value: newValue,
                    _token: CSRF_TOKEN // global variable set in Blade below
                },
                success: function(response){
                    if(response.status === 'success'){
                        cell.text(newValue);
                        alert(response.message);
                    } else {
                        cell.text(originalText);
                        alert('Error updating field: ' + response.message);
                    }
                },
                error: function(xhr){
                    cell.text(originalText);
                    alert('An error occurred while updating.');
                }
            });
        }
    });

    // Inline editing for publish checkbox
    $('.publish-checkbox').on('change', function(){
        var checkbox = $(this);
        var newValue = checkbox.is(':checked') ? '1' : '0';
        var id = checkbox.data('id');
        $.ajax({
            url: TEAM_MEMBER_UPDATE_URL + '/' + id,
            method: 'PATCH',
            data: {
                field: 'publish',
                value: newValue,
                _token: CSRF_TOKEN
            },
            success: function(response){
                if(response.status !== 'success'){
                    alert('Error updating field: ' + response.message);
                    // Revert change on error
                    checkbox.prop('checked', !checkbox.is(':checked'));
                }
            },
            error: function(xhr){
                alert('An error occurred while updating.');
                // Revert change on error
                checkbox.prop('checked', !checkbox.is(':checked'));
            }
        });
    });
});
