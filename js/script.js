$(document).ready(function() {
    console.log('Dashboard initialized');

    loadDomains();
    loadAliases();
    loadEmails();

    $('.list-group-item').click(function(e) {
        e.preventDefault();
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
        
        var target = $(this).attr('href');
        $('.tab-pane').removeClass('show active');
        $(target).addClass('show active');
    });

    $('#domainFilter').change(function() {
        loadEmails();
    });

    let searchTimer;
    $('#searchEmail').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            loadEmails();
        }, 500);
    });

    $('#resetFilters').click(function() {
        $('#domainFilter').val('');
        $('#searchEmail').val('');
        loadEmails();
        showAlert('Filters reset', 'info');
    });

    function loadDomains() {
        showLoading('domainsTable');
        
        $.ajax({
            url: 'api.php?action=get_domains',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let options = '<option value="">All Domains</option>';
                let domainsTable = '';
                
                if (Array.isArray(data)) {
                    data.forEach(domain => {
                        options += `<option value="${domain.id}">${domain.name}</option>`;
                        domainsTable += `
                            <tr>
                                <td>${domain.id}</td>
                                <td><span class="badge bg-success">${domain.name}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning edit-domain" data-id="${domain.id}" title="Edit Domain">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-domain" data-id="${domain.id}" title="Delete Domain">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#domainsSummary').text(`${data.length} domains found`);
                    $('#domainsCount').text(data.length);
                } else {
                    domainsTable = `<tr><td colspan="3" class="text-center py-4 text-muted">No domains found</td></tr>`;
                    $('#domainsSummary').text('No domains');
                    $('#domainsCount').text('0');
                }
                
                $('#aliasDomain, #emailDomain, #domainFilter, #editAliasDomain, #editEmailDomain').html(options);
                $('#domainsTable tbody').html(domainsTable);
            },
            error: function(xhr, status, error) {
                showError('domainsTable', 'Error loading domains');
                $('#domainsSummary').text('Error loading domains');
            }
        });
    }

    function loadAliases() {
        showLoading('aliasesTable');
        
        $.ajax({
            url: 'api.php?action=get_aliases',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let table = '';
                
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(alias => {
                        table += `
                            <tr>
                                <td>${alias.id}</td>
                                <td>${alias.domain_name || 'N/A'}</td>
                                <td><code>${alias.source}</code></td>
                                <td><code>${alias.destination}</code></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning edit-alias" data-id="${alias.id}" title="Edit Alias">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-alias" data-id="${alias.id}" title="Delete Alias">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#aliasesSummary').text(`${data.length} aliases found`);
                    $('#aliasesCount').text(data.length);
                } else {
                    table = `<tr><td colspan="5" class="text-center py-4 text-muted">No aliases found</td></tr>`;
                    $('#aliasesSummary').text('No aliases');
                    $('#aliasesCount').text('0');
                }
                
                $('#aliasesTable tbody').html(table);
            },
            error: function(xhr, status, error) {
                showError('aliasesTable', 'Error loading aliases');
                $('#aliasesSummary').text('Error loading aliases');
            }
        });
    }

    function loadEmails() {
        let domainFilter = $('#domainFilter').val();
        let search = $('#searchEmail').val();
        let url = `api.php?action=get_emails&domain_id=${domainFilter}&search=${encodeURIComponent(search)}`;
        
        showLoading('emailsTable');
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let table = '';
                
                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(email => {
                        let passwordDisplay = email.password ? 
                            `<code title="${email.password}">${email.password.substring(0, 25)}...</code>` : 
                            '<span class="text-muted">No password</span>';
                        
                        table += `
                            <tr>
                                <td>${email.id}</td>
                                <td><span class="badge bg-info">${email.domain_name || 'N/A'}</span></td>
                                <td><i class="fas fa-envelope text-muted me-1"></i>${email.email}</td>
                                <td>${passwordDisplay}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning edit-email" data-id="${email.id}" title="Edit Email">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-email" data-id="${email.id}" title="Delete Email">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#emailsSummary').text(`${data.length} email accounts found`);
                    $('#emailsCount').text(data.length);
                } else {
                    table = `<tr><td colspan="5" class="text-center py-4 text-muted">No email accounts found</td></tr>`;
                    $('#emailsSummary').text('No email accounts');
                    $('#emailsCount').text('0');
                }
                
                $('#emailsTable tbody').html(table);
                updateTotalCount();
            },
            error: function(xhr, status, error) {
                showError('emailsTable', 'Error loading emails');
                $('#emailsSummary').text('Error loading emails');
            }
        });
    }

    $(document).on('submit', '#addDomainForm', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.post('api.php?action=add_domain', formData, function(response) {
            if (response.success) {
                $('#addDomainModal').modal('hide');
                $('#addDomainForm')[0].reset();
                loadDomains();
                showAlert('Domain added successfully!', 'success');
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        }, 'json').fail(function() {
            showAlert('Error adding domain', 'danger');
        });
    });

    $(document).on('submit', '#addAliasForm', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.post('api.php?action=add_alias', formData, function(response) {
            if (response.success) {
                $('#addAliasModal').modal('hide');
                $('#addAliasForm')[0].reset();
                loadAliases();
                showAlert('Alias added successfully!', 'success');
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        }, 'json').fail(function() {
            showAlert('Error adding alias', 'danger');
        });
    });

    $(document).on('submit', '#addEmailForm', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.post('api.php?action=add_email', formData, function(response) {
            if (response.success) {
                $('#addEmailModal').modal('hide');
                $('#addEmailForm')[0].reset();
                loadEmails();
                showAlert('Email account added successfully!', 'success');
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        }, 'json').fail(function() {
            showAlert('Error adding email', 'danger');
        });
    });

    $(document).on('submit', '#editDomainForm', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.post('api.php?action=update_domain', formData, function(response) {
            if (response.success) {
                $('#editDomainModal').modal('hide');
                loadDomains();
                showAlert('Domain updated successfully!', 'success');
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        }, 'json');
    });

    $(document).on('submit', '#editAliasForm', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.post('api.php?action=update_alias', formData, function(response) {
            if (response.success) {
                $('#editAliasModal').modal('hide');
                loadAliases();
                showAlert('Alias updated successfully!', 'success');
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        }, 'json');
    });

    $(document).on('submit', '#editEmailForm', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.post('api.php?action=update_email', formData, function(response) {
            if (response.success) {
                $('#editEmailModal').modal('hide');
                loadEmails();
                showAlert('Email account updated successfully!', 'success');
            } else {
                showAlert('Error: ' + response.message, 'danger');
            }
        }, 'json');
    });

    $(document).on('click', '.delete-domain', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this domain?')) {
            $.post('api.php?action=delete_domain', {id: id}, function(response) {
                if (response.success) {
                    loadDomains();
                    loadEmails();
                    loadAliases();
                    showAlert('Domain deleted successfully!', 'success');
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            }, 'json');
        }
    });

    $(document).on('click', '.delete-alias', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this alias?')) {
            $.post('api.php?action=delete_alias', {id: id}, function(response) {
                if (response.success) {
                    loadAliases();
                    showAlert('Alias deleted successfully!', 'success');
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            }, 'json');
        }
    });

    $(document).on('click', '.delete-email', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this email account?')) {
            $.post('api.php?action=delete_email', {id: id}, function(response) {
                if (response.success) {
                    loadEmails();
                    showAlert('Email account deleted successfully!', 'success');
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            }, 'json');
        }
    });

    $(document).on('click', '.edit-domain', function() {
        let id = $(this).data('id');
        $.get('api.php?action=get_domain&id=' + id, function(data) {
            $('#editDomainId').val(data.id);
            $('#editDomainName').val(data.name);
            $('#editDomainModal').modal('show');
        }, 'json');
    });

    $(document).on('click', '.edit-alias', function() {
        let id = $(this).data('id');
        $.get('api.php?action=get_alias&id=' + id, function(data) {
            $('#editAliasId').val(data.id);
            $('#editAliasDomain').val(data.domain_id);
            $('#editSource').val(data.source);
            $('#editDestination').val(data.destination);
            $('#editAliasModal').modal('show');
        }, 'json');
    });

    $(document).on('click', '.edit-email', function() {
        let id = $(this).data('id');
        $.get('api.php?action=get_email&id=' + id, function(data) {
            $('#editEmailId').val(data.id);
            $('#editEmailDomain').val(data.domain_id);
            $('#editEmail').val(data.email);
            $('#editPassword').val('');
            $('#editEmailModal').modal('show');
        }, 'json');
    });

    function showLoading(tableId) {
        const loadingHtml = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0 text-muted">Loading data...</p>
                </td>
            </tr>
        `;
        $('#' + tableId + ' tbody').html(loadingHtml);
    }

    function showError(tableId, message) {
        const errorHtml = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
                    <p class="text-danger">${message}</p>
                    <button class="btn btn-sm btn-outline-danger" onclick="location.reload()">
                        <i class="fas fa-refresh me-1"></i> Retry
                    </button>
                </td>
            </tr>
        `;
        $('#' + tableId + ' tbody').html(errorHtml);
    }

    function updateTotalCount() {
        const domains = parseInt($('#domainsCount').text()) || 0;
        const emails = parseInt($('#emailsCount').text()) || 0;
        const aliases = parseInt($('#aliasesCount').text()) || 0;
        const total = domains + emails + aliases;
        $('#totalCount').text(total);
    }

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${getAlertIcon(type)} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }

    function getAlertIcon(type) {
        const icons = {
            'success': 'check-circle',
            'danger': 'exclamation-triangle',
            'warning': 'exclamation-circle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
});