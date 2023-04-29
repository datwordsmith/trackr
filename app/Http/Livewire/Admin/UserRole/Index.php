<?php

namespace App\Http\Livewire\Admin\UserRole;

use Livewire\Component;
use App\Models\UserRole;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $role_id, $role;

    public function rules()
    {
        return [
            'role' => 'required|string'
        ];
    }

    public function resetInput() {
        $this->name = NULL;
    }

    public function storeRole()
    {
        $validatedData = $this->validate();
        //dd($validatedData);
        UserRole::create($validatedData);

        session()->flash('message', 'User Role Added Successfully');

        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function editRole(int $role_id)
    {
        $user_role = UserRole::FindOrFail($role_id);
        $this->role_id = $role_id;
        $this->role = $user_role->role;
    }

    public function updateRole()
    {
        $validatedData = $this->validate();
        $user_role = UserRole::findOrFail($this->role_id);
        $user_role->update($validatedData);

        session()->flash('message', 'User Role Updated Successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function deleteRole($role_id)
    {
        $this->role_id = $role_id;
    }

    public function destroyRole()
    {
        $user_role = Project::FindOrFail($this->role_id);
        $user_role->delete();
        session()->flash('message', 'User Role deleted successfully');
        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function closeModal() {
        $this->resetInput();
    }

    public function openModal() {
        $this->resetInput();
    }

    public function render()
    {
        $user_roles = UserRole::orderBy('role', 'ASC')->paginate(5);
        return view('livewire.admin.user-role.index', ['user_roles' => $user_roles])->extends('layouts.admin')->section('content');
    }

}