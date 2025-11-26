<div class="modal fade" id="addDomainModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addDomainForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="domainName" class="form-label">Domain Name</label>
                        <input type="text" class="form-control" id="domainName" name="name" required placeholder="example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Domain</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editDomainModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Domain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDomainForm">
                <div class="modal-body">
                    <input type="hidden" id="editDomainId" name="id">
                    <div class="mb-3">
                        <label for="editDomainName" class="form-label">Domain Name</label>
                        <input type="text" class="form-control" id="editDomainName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Domain</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addAliasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Alias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAliasForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="aliasDomain" class="form-label">Domain</label>
                        <select class="form-select" id="aliasDomain" name="domain_id" required>
                            <option value="">Select Domain</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="source" class="form-label">Source Email</label>
                        <input type="email" class="form-control" id="source" name="source" required placeholder="alias@domain.com">
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Destination Email</label>
                        <input type="email" class="form-control" id="destination" name="destination" required placeholder="real@domain.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Alias</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAliasModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Alias</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAliasForm">
                <div class="modal-body">
                    <input type="hidden" id="editAliasId" name="id">
                    <div class="mb-3">
                        <label for="editAliasDomain" class="form-label">Domain</label>
                        <select class="form-select" id="editAliasDomain" name="domain_id" required>
                            <option value="">Select Domain</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editSource" class="form-label">Source Email</label>
                        <input type="email" class="form-control" id="editSource" name="source" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDestination" class="form-label">Destination Email</label>
                        <input type="email" class="form-control" id="editDestination" name="destination" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Alias</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Email Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addEmailForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="emailDomain" class="form-label">Domain</label>
                        <select class="form-select" id="emailDomain" name="domain_id" required>
                            <option value="">Select Domain</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="user@domain.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Email Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEmailForm">
                <div class="modal-body">
                    <input type="hidden" id="editEmailId" name="id">
                    <div class="mb-3">
                        <label for="editEmailDomain" class="form-label">Domain</label>
                        <select class="form-select" id="editEmailDomain" name="domain_id" required>
                            <option value="">Select Domain</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Enter new password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Email</button>
                </div>
            </form>
        </div>
    </div>
</div>