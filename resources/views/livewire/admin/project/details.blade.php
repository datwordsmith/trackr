<div>
    {{--@include('livewire.admin.project.modal-form')--}}

    @section('pagename')
        <i class="fas fa-project-diagram"></i> {{$project->name}}
    @endsection

    @section('breadcrumbs')
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('projects')}}">All Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page"> {{$project->name}}</li>
        </ol>
    @endsection

    <div class="row">
        <!-- ROW 1, COL 1 -->
        <div class="col-md-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="border-bottom border-success mb-3">
                        <h5 class="card-title fw-semibold pb-2">Project Info</h5>
                    </div>

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
                    <div class="border-bottom border-warning mb-3">
                        <h5 class="card-title fw-semibold pb-2">Project Team</h5>
                    </div>
                    <div class="row">
                        <!-- LEFT COL -->
                        <div class="col-md-6">
                            <!-- Project Manager -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-1">Project Manager</h6>
                                <div class="d-inline">
                                    @if(!$editPM)
                                        {{ $project->users->first()->name ?? 'Not Assigned' }}
                                        {{-- <div>User Role: {{ $project->users->first()->pivot->user_role ?? 'N/A' }}</div> --}}
                                        <button class="btn btn-sm btn-warning ms-2" wire:click="togglePM"><i class="fas fa-pencil-alt"></i></button>
                                    @else
                                        <select class="form-select form-control-sm d-inline-block w-auto" wire:model.defer="projectManager" wire:keydown.enter.prevent="updatePM($event.target.value)" wire:keydown.escape="togglePM">
                                            <option value="">Select Project Manager</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-primary ms-2" wire:click="updatePM"><i class="fas fa-save"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="togglePM"><i class="fas fa-times"></i></button>
                                    @endif
                                </div>
                            </div>

                            <!-- Budget Officer -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-1">Budget Officer</h6>
                                <div class="d-inline">
                                    @if(!$editBO)
                                        {{ $project->users->first()->name ?? 'Not Assigned' }}
                                        {{-- <div>User Role: {{ $project->users->first()->pivot->user_role ?? 'N/A' }}</div> --}}
                                        <button class="btn btn-sm btn-warning ms-2" wire:click="toggleBO"><i class="fas fa-pencil-alt"></i></button>
                                    @else
                                        <select class="form-select form-control-sm d-inline-block w-auto" wire:model.defer="budgetOfficer" wire:keydown.enter.prevent="updateBO($event.target.value)" wire:keydown.escape="toggleBO">
                                            <option value="">Select Budget Officer</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-primary ms-2" wire:click="updateBO"><i class="fas fa-save"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="toggleBO"><i class="fas fa-times"></i></button>
                                    @endif
                                </div>
                            </div>

                            <!-- Quantity Surveyor -->
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-1">Quantity Surveyor</h6>
                                <div class="d-inline">
                                    @if(!$editPM)
                                        {{ $project->users->first()->name ?? 'Not Assigned' }}
                                        {{-- <div>User Role: {{ $project->users->first()->pivot->user_role ?? 'N/A' }}</div> --}}
                                        <button class="btn btn-sm btn-warning ms-2" wire:click="togglePM"><i class="fas fa-pencil-alt"></i></button>
                                    @else
                                        <select class="form-select form-control-sm d-inline-block w-auto" wire:model.defer="projectManager" wire:keydown.enter.prevent="updatePM($event.target.value)" wire:keydown.escape="togglePM">
                                            <option value="">Select Project Manager</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-primary ms-2" wire:click="updatePM"><i class="fas fa-save"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="togglePM"><i class="fas fa-times"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COL -->
                        <div class="col-md-6">
                            <!-- Project Manager -->
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-1">Project Manager</h6>
                                <div class="d-inline">
                                    @if(!$editPM)
                                        {{ $project->users->first()->name ?? 'Not Assigned' }}
                                        {{-- <div>User Role: {{ $project->users->first()->pivot->user_role ?? 'N/A' }}</div> --}}
                                        <button class="btn btn-sm btn-warning ms-2" wire:click="togglePM"><i class="fas fa-pencil-alt"></i></button>
                                    @else
                                        <select class="form-select form-control-sm d-inline-block w-auto" wire:model.defer="projectManager" wire:keydown.enter.prevent="updatePM($event.target.value)" wire:keydown.escape="togglePM">
                                            <option value="">Select Project Manager</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-primary ms-2" wire:click="updatePM"><i class="fas fa-save"></i></button>
                                        <button class="btn btn-sm btn-danger" wire:click="togglePM"><i class="fas fa-times"></i></button>
                                    @endif
                                </div>
                            </div>
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
            $('#addProjectModal').modal('hide');
            $('#editProjectModal').modal('hide');
            $('#deleteProjectModal').modal('hide');
        })
    </script>
@endsection
