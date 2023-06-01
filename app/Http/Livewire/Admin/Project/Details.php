<?php

namespace App\Http\Livewire\Admin\Project;

use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use App\Models\UserRole;

class Details extends Component
{
    public $projectId, $project, $client, $editClient = false, $editPM = false, $editBO = false, $editQS = false, $projectUsers;

    protected $rules = [
        'projectManager' => 'required',
        'budgetOfficer' => 'required',
        'quantitySurveyor' => 'required',
    ];

    public function mount($slug)
    {
        $this->project = Project::where('slug', $slug)->firstOrFail();
        $this->client = $this->project->client;
        $this->users = User::where('status', 1)->get(); //Fetch only active users
        $this->projectUsers = $this->project->users()->with('role')->get();
        $this->projectManager = $this->project->users->first();
        $this->budgetOfficer = $this->project->users->first();
    }

    public function toggleClient()
    {
        $this->editClient = !$this->editClient;
    }

    public function updateClient()
    {
        $validatedData = $this->validate([
            'client' => 'required|string',
        ]);
        $this->project->update([
            'client' => $validatedData['client'],
        ]);

        $this->editClient = false;
    }

    public function togglePM()
    {
        $this->editPM = !$this->editPM;
    }

    public function updatePM()
    {
        $validatedData = $this->validate([
            'projectManager' => 'required|exists:users,id',
        ]);

        $userRole = UserRole::where('role', 'Project Manager')->first(); // Retrieve the appropriate role for the project manager

        $this->project->users()->sync([
            $validatedData['projectManager'] => ['role_id' => $userRole->id],
        ]);

        $this->projectManager = $this->users->find($validatedData['projectManager']);

        $this->editPM = false;
        // Update the $project property with the latest data
        $this->project = $this->project->fresh();
    }

    public function toggleBO()
    {
        $this->editBO = !$this->editBO;
    }

    public function updateBO()
    {
        $validatedData = $this->validate([
            'budgetOfficer' => 'required|exists:users,id',
        ]);

        $userRole = UserRole::where('role', 'Budget Officer')->first(); // Retrieve the appropriate role for the Budget Officer

        $this->project->users()->sync([
            $validatedData['budgetOfficer'] => ['role_id' => $userRole->id],
        ]);

        $this->budgetOfficer = $this->users->find($validatedData['budgetOfficer']);

        $this->editBO = false;
        // Update the $project property with the latest data
        $this->project = $this->project->fresh();
    }

    public function render()
    {
        $users = $this->users;
        return view('livewire.admin.project.details',[
            'users' => $users,
        ])->extends('layouts.admin')->section('content');
    }
}
