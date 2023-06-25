    <!-- ASSIGN TEAM MODAL -->
    <div wire:ignore.self class="modal fade" id="assignTeamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-warning">
                    <h1 class="modal-title fs-5">Assign Project Team</h1>
                    <button type="button" class="btn-close" wire:click ="closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="storeProjectTeam()">
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($userRoles as $userRole)

                                <div class="col-6">
                                    <div class="mt-2">
                                        <label for="{{ $userRole->role }}"><small class="text-primary">{{ $userRole->role }}:</small></label>
                                        <select class="form-select" wire:model.defer="selectedUsers.{{ $userRole->id }}" required>
                                            <option value="">Select</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click ="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END ADD MODAL -->

    <!-- DELETE BUDGET MODAL -->
    <div wire:ignore.self class="modal fade" id="deleteBudgetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Delete Budget Item</h1>
                    <button type="button" class="btn-close" wire:click ="closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div wire:loading class="py-5">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-grow text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div wire:loading.remove>
                    <form wire:submit.prevent="destroyBudget()">
                        <div class="modal-body">
                            <h4>Are you sure you want to delete this Item?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click ="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END DELETE MODAL -->

    <!-- REQUISITION MODAL -->
    <div wire:ignore.self class="modal fade" id="requisitionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom border-warning">
                    <h1 class="modal-title fs-5">Make Requisition</h1>
                    <button type="button" class="btn-close" wire:click ="closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="saveRequisition()">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table align-items-center mb-0" style="width:100%">
                                    <tbody>
                                        <tr><td class="w-50"><label class="form-label">Material:</label></td> <td>{{ $budgetItemName }}</td></tr>
                                        <tr><td><label class="form-label">Category:</label></td> <td>{{ $budgetItemCategory }}</td></tr>
                                        <tr><td><label class="form-label">Unit:</label></td> <td>{{ $budgetItemUnit }}</td></tr>
                                        <tr><td><label class="form-label">Approved Quantity:</label></td> <td>{{ $budgetItemQuantity }}</td></tr>
                                        <tr><td><label class="form-label">Previous Requisitions:</label></td> <td>{{ $requisitionSum }}</td></tr>
                                        @if ($budgetBalance > 0)
                                            <tr>
                                                <td><label class="form-label">Requisition Quantity:</label></td>
                                                <td>
                                                    <input type="number" class="form-control" id="quantityInput" placeholder="Enter quantity"
                                                        wire:model.defer="requisitionQuantity" max="{{ $budgetBalance }}" min="1" pattern="[0-9]+" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <label class="form-label">Activity:</label>
                                                    <input type="text" class="form-control" id="activityInput" placeholder="Enter activity" wire:model.defer="budgetActivity" required>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-danger text-center">Item requisitions complete</td>
                                            </tr>
                                        @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click ="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @if ($budgetBalance > 0)
                            <button type="submit" class="btn btn-primary">Save</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END REQUISITION MODAL -->

    <!-- DELETE REQUISITION MODAL -->
    <div wire:ignore.self class="modal fade" id="deleteRequisitionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Delete Requisition</h1>
                    <button type="button" class="btn-close" wire:click ="closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div wire:loading class="py-5">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-grow text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div wire:loading.remove>
                    <form wire:submit.prevent="destroyRequisition()">
                        <div class="modal-body">
                            <h4>Are you sure you want to delete this Item?</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click ="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END DELETE MODAL -->

    <!-- APPROVE REQUISITION MODAL -->
    <div wire:ignore.self class="modal fade" id="approveRequisitionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Approve Requisitions</h1>
                    <button type="button" class="btn-close" wire:click ="closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div wire:loading class="py-5">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-grow text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div wire:loading.remove>
                    <form wire:submit.prevent="approveRequisition()">
                        <div class="modal-body">
                            <h6>Do you want to approve all pending requisitions for this project?</h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click ="closeModal" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success"><i class="fas fa-check-double"></i> Yes, Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END DELETE MODAL -->
