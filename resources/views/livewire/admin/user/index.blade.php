<div>
    @include('livewire.admin.user.modal-form')

    @section('pagename')
        <i class="fas fa-users"></i> Users
    @endsection

    @section('breadcrumbs')
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">All Users</li>
            <li class="breadcrumb-item"><a href="{{ url('t/users/active')}}">Active</a></li>
            <li class="breadcrumb-item"><a href="{{ url('t/users/inactive')}}">Inactive</a></li>
        </ol>
    @endsection

    <div class="row">
        <div class="col-md-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <div class="col-md-12 d-flex mb-2">
                        {{-- <div>
                            <h5 class="card-title fw-semibold mb-4">Project Listing</h5>
                        </div> --}}
                        <div class="ms-auto">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-sm btn-primary text-white"><i class="fas fa-plus-square pr-4"></i>&nbsp;&nbsp; Add User</a>
                        </div>
                    </div>
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <input type="text" class="form-control" wire:model="search" placeholder="Search...">
                    </div>
                    <div class="table-responsive">
                        <table id="category_table" class="table table-striped align-items-center mb-0" style="width:100%">
                            <thead class="table-dark">
                                <tr class="">
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 w-40">Name</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 w-40">Email Address</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 w-10 text-center">Status</th>
                                    <th class="text-secondary text-xs font-weight-semibold opacity-7 w-10">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td class="text-center">

                                            @if($user->status == '1')
                                                <i class="far fa-check-circle text-success"></i>
                                                {{-- <div class="d-inline-block me-1">Inactive</div>
                                                <div class="form-check form-switch d-inline-block">
                                                    <input type="checkbox" class="form-check-input" id="site_state" style="cursor: pointer;" {{$user->status}}>
                                                    <label for="site_state" class="form-check-label">On</label>
                                                </div> --}}
                                            @else
                                                <i class="fas fa-ban text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="">


                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="#" wire:click="editUser({{$user->id}})" data-bs-toggle="modal" data-bs-target="#editUserModal" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i></a>
                                                <a href="#" wire:click="deleteUser({{$user->id}})" data-bs-toggle="modal" data-bs-target="#deleteUserModal" class="btn btn-sm btn-danger text-white"><i class="fas fa-trash-alt"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-danger">No Project Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="row mt-2">
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@section('scripts')
    <script>
        $(document).ready(function() {
            window.addEventListener('close-modal', event => {
                $('#addUserModal').modal('hide');
                $('#editUserModal').modal('hide');
                $('#deleteUserModal').modal('hide');
            });
        });
    </script>
@endsection
