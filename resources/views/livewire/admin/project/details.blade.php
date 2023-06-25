<div>
    @include('livewire.admin.project.details-modal-form')
    {{-- @include('livewire.admin.project.budget-modal-form')--}}

    @section('pagename')
        <i class="fas fa-project-diagram"></i> {{$project->name}}
    @endsection

    @section('breadcrumbs')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('t/projects')}}">All Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page"> {{$project->name}}</li>
        </ol>
    @endsection

    <!-- ROW #1 -->
    <div class="row">
        <!-- ROW 1, COL 1 -->
        <div class="col-md-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header text-light bg-success">
                    Project Info
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Project Title</h6>
                        <span class="fw-normal">{{$project->name}}</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Description</h6>
                        <span class="fw-normal">{{$project->description}}</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">Client</h6>
                        @if(!$editClient)
                            <span class="fw-normal">{{$project->client}}</span>
                            <button class="btn btn-sm btn-warning ms-2" wire:click="toggleClient"><i class="fas fa-pencil-alt"></i></button>
                        @else
                            <input type="text" class="form-control form-control-sm d-inline-block w-auto" wire:model.defer="client" wire:keydown.enter.prevent="updateClient" wire:keydown.escape="toggleClient">
                            <button class="btn btn-sm btn-primary ms-2" wire:click="updateClient"><i class="fas fa-save"></i></button>
                            <button class="btn btn-sm btn-danger" wire:click="toggleClient"><i class="fas fa-times"></i></button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 1, COL 2 -->
        <div class="col-md-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="border-bottom border-warning mb-3 d-flex">
                        <div>
                            <h5 class="card-title fw-semibold pb-2">Project Team</h5>
                        </div>
                        <div class="ms-auto">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#assignTeamModal" class="btn btn-sm btn-primary text-white"><i class="fas fa-users-cog"></i> Project Team</a>
                        </div>
                    </div>
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="row">
                        <!-- LEFT COL -->
                        @foreach ($projectUsers->sortBy('role.id') as $projectUser)
                            @if ($projectUser->project_id == $project->id)
                                    <div class="col-6 pb-4">
                                        <strong>{{ $projectUser->role->role }}</strong><br/>
                                        <small>{{ $projectUser->user->name }}</small>
                                    </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ROW #2 - BUDGET -->
    <div class="row">

        <!-- ROW 2, COL 1 -->
        <div class="col-md-4">
            <div class="card w-100">
                <div class="card-header text-light bg-warning">
                    Add Budget Items
                </div>
                <div class="card-body p-4">
                    @if (session('budgeterror'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('budgeterror') }}
                        </div>
                    @endif
                    <form wire:submit.prevent="saveBudget()">
                        <div class="mb-3">
                            <select wire:model="selectedCategory" class="form-select" required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <select wire:model="selectedMaterial" class="form-control" @if (empty($selectedCategory))  disabled @endif required>
                                <option value="">Select a material</option>
                                @if (!empty($materials))
                                    @foreach ($materials as $material)
                                        @if ($material->category_id == $selectedCategory)
                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- ROW 2, COL 2 -->
        <div class="col-md-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="border-bottom border-danger mb-3 d-flex">
                        <div>
                            <h5 class="card-title fw-semibold pb-2">Project Budget</h5>
                        </div>

                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" wire:model="search" placeholder="Search...">
                    </div>
                    <div class="table-responsive">
                        <table id="budget_table" class="table table-striped align-items-center mb-0" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-3">Material</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-2">Category</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-1">Unit</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-3">Quantity</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($budgetItems->sortBy('material.name') as $budgetItem)
                                    <tr>
                                        <td>{{ $budgetItem->material->name }}</td>
                                        <td>{{ $budgetItem->material->category->category }}</td>
                                        <td>{{ $budgetItem->material->unit->name }}</td>
                                        <td>
                                            @if($editQtyId !== $budgetItem->id)
                                                <button class="btn btn-sm btn-warning ms-2" wire:click="toggleQty({{ $budgetItem->id }})"><i class="fas fa-pencil-alt"></i></button>
                                                <span class="fw-normal">{{ $budgetItem->quantity }}</span>
                                            @else
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm d-inline-block w-auto-fit" wire:model.defer="budgetqty" wire:keydown.enter.prevent="updateQty({{ $budgetItem->id }})" wire:keydown.escape="toggleQty">
                                                    <button class="btn btn-sm btn-primary ms-2" wire:click="updateQty({{ $budgetItem->id }})"><i class="fas fa-save"></i></button>
                                                    <button class="btn btn-sm btn-danger" wire:click="toggleQty(null)"><i class="fas fa-times"></i></button>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Action buttons for the budget item -->
                                            <div class="btn-group" role="group" aria-label="">
                                                <a href="#" wire:click="makeRequisition({{ $budgetItem->id }})" data-bs-toggle="modal" data-bs-target="#requisitionModal" class="btn btn-sm btn-success text-white"> Request</a>
                                                <a href="#" wire:click="deleteBudget({{ $budgetItem->id }})" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal" class="btn btn-sm btn-danger text-white"><i class="fas fa-trash-alt"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                        <div class="row mt-2">
                            {{ $budgetItems->links() }}
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <!-- ROW #3 - REQUISITION -->
    <div class="row">

        <!-- ROW 3, COL 1 -->
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="border-bottom border-warning mb-3 d-flex">
                        <div>
                            <h5 class="card-title fw-semibold pb-2">Project Requisitions</h5>
                        </div>
                        <div class="ms-auto">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#approveRequisitionModal" class="btn btn-sm btn-success text-white"><i class="fas fa-check-double"></i> Approve All</a>
                        </div>
                    </div>
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <input type="text" class="form-control" wire:model="requisitionSearch" placeholder="Search...">
                    </div>
                    <div class="table-responsive">
                        <table id="budget_table" class="table table-striped align-items-center mb-0" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-2">Date</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-2">Material</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-1">Category</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-1">Quantity</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-2">Status</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-1 text-center">Approval</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 col-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allRequisitions->sortByDesc('created_at') as $requisition)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($requisition->created_at)->format('d-M-Y') }}</td>
                                        <td>{{ $requisition->budget->material->name }} ({{ $requisition->budget->material->unit->name }})</td>
                                        <td>{{ $requisition->budget->material->category->category }}</td>
                                        <td>{{ $requisition->quantity }}</td>
                                        <td>{{ $requisition->activity }}</td>

                                        <td class="text-center">
                                            @if($requisition->status == '1')
                                                <i class="far fa-check-circle text-success"></i> Approved
                                            @else
                                                <i class="fas fa-ban text-danger"></i> Pending
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$requisition->status == '1')
                                                <div class="btn-group" role="group" aria-label="">
                                                    <a href="#" wire:click="deleteRequisition({{ $requisition->id }})" data-bs-toggle="modal" data-bs-target="#deleteRequisitionModal" class="btn btn-sm btn-danger text-white"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                        <div class="row mt-2">
                            {{ $allRequisitions->links() }}
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        window.addEventListener('close-modal', event => {
            $('#assignTeamModal').modal('hide');
            $('#deleteBudgetModal').modal('hide');
            $('#requisitionModal').modal('hide');
            $('#approveRequisitionModal').modal('hide');
            $('#deleteRequisitionModal').modal('hide');
        });
    </script>
@endsection
