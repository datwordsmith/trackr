<?php

namespace App\Http\Livewire\Admin\Project;

use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use App\Models\Material;
use App\Models\UserRole;
use App\Models\ProjectUser;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Models\ProjectBudget;
use App\Models\MaterialCategory;
use Illuminate\Support\Facades\DB;

class Details extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $budgetItemsPagination, $allRequisitionsPagination;

    public $projectId, $project, $client, $editClient = false, $userRoles, $projectUsers, $selectedUsers = [], $budgetId, $budgetqty;
    public $editQtyId = null, $editQty = false;
    //public $selectedCategory, $selectedMaterial, $unassignedMaterials, $assignedMaterials;
    public $search, $categories = [], $selectedMaterial, $materials = [], $selectedCategory, $materialsByCategory;
    public $selectedBudgetItem, $budgetItemName, $budgetItemCategory, $budgetItemQuantity, $budgetItemUnit;
    public $budgetBalance, $requisitionSum, $requisitionQuantity, $budgetActivity, $requisitionId;
    public $requisitionSearch;
    protected $budgetItems, $allRequisitions;


    protected $rules = [

    ];

    public function mount($slug)
    {
        $this->project = Project::where('slug', $slug)->firstOrFail();
        $this->projectId = $this->project->id;
        $this->client = $this->project->client;
        $this->users = User::where('status', 1)->get(); //Fetch only active users

        $this->projectUsers = ProjectUser::all();

        $this->userRoles = UserRole::all();

        $this->categories = MaterialCategory::all(); // Fetch all material categories
        $this->materials = Material::with('unit')->get(); // Fetch all materials with their associated units
        $this->fetchRequisitions();
    }

    //-- CLIENT OPS --//
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
    //-- END CLIENT OPS --//

    public function resetInput()
    {
        $this->selectedUsers = []; // Reset selected users for each user role
        $this->budgetItemId = null; // Reset budgetItemId
        $this->requisitionQuantity = null; // Reset requisitionQuantity
        $this->budgetActivity = null; // Reset budgetActivity

    }

    //-- MODAL --//
    public function resetModal()
    {
        $this->reset([
            'selectedUsers',
            'selectedMaterials',
        ]);

        $this->resetValidation();
    }

    public function closeModal() {
        $this->resetInput();
    }

    public function closeModalAndRefresh()
    {
        $this->resetInput();
        $this->fetchAssignedAndUnassignedMaterials(); // Refresh assigned and unassigned materials
        $this->dispatchBrowserEvent('close-modal');
    }

    public function openModal() {
        $this->resetInput();
    }
    //-- END MODAL --//

    public function storeProjectTeam()
    {
        try {
            $projectId = $this->project->id;

            foreach ($this->selectedUsers as $userRoleId => $userId) {
                // Check if the project_user record already exists
                $existingRecord = DB::table('project_user')
                    ->where('project_id', $projectId)
                    ->where('role_id', $userRoleId)
                    ->first();

                if ($existingRecord) {
                    // Update the existing record
                    DB::table('project_user')
                        ->where('project_id', $projectId)
                        ->where('role_id', $userRoleId)
                        ->update(['user_id' => $userId]);
                } else {
                    // Create a new record
                    DB::table('project_user')->insert([
                        'project_id' => $projectId,
                        'user_id' => $userId,
                        'role_id' => $userRoleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            session()->flash('message', 'Project Team updated.');
            $this->dispatchBrowserEvent('close-modal');
            $this->resetInput();
            $this->refreshProjectUsers();

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database query exceptions
            // Log the error, show an error message, or perform any other necessary actions
        } catch (\Exception $e) {
            // Handle other exceptions
            // Log the error, show an error message, or perform any other necessary actions
        }
    }

    public function refreshProjectUsers()
    {
        $this->projectUsers = ProjectUser::where('project_id', $this->project->id)->get();
    }

    //-- BUDGETING --//
    public function fetchBudgetItems()
    {
        $this->budgetItems = ProjectBudget::with(['material', 'material.category', 'material.unit'])
            ->where('project_id', $this->projectId)
            ->paginate(10);
    }

    public function getRequisitions()
    {
        $requisitions = Requisition::whereHas('budget', function ($query) {
            $query->where('project_id', $this->projectId);
        })
        ->with('budget.material', 'budget.material.category', 'budget.material.unit')
        ->get();

        return $requisitions;
    }

    public function saveBudget()
    {
        // Check if a record with the same material_id and project_id already exists
        $existingRecord = ProjectBudget::where('material_id', $this->selectedMaterial)
        ->where('project_id', $this->projectId)
        ->exists();

        if ($existingRecord) {
        // Show an error message or perform any other necessary action
        session()->flash('budgeterror', 'Item already exists.');
        } else {
        // Create a new instance of ProjectBudget
        $projectBudget = new ProjectBudget();
        $projectBudget->material_id = $this->selectedMaterial;
        $projectBudget->project_id = $this->projectId;
        $projectBudget->quantity = 0; // Set the initial quantity value if needed
        $projectBudget->save();

        // Reset the form inputs
        $this->selectedCategory = null;
        $this->selectedMaterial = null;

        // Fetch the budget items again to update the table
        $this->fetchBudgetItems();

        // Emit an event to trigger JavaScript function
        $this->emit('budgetSaved');
        }
    }

    public function deleteBudget($budgetId)
    {
        $this->budgetId = $budgetId;
    }

    public function destroyBudget()
    {
        // Find the budget item by its ID
        $budgetItem = ProjectBudget::findOrFail($this->budgetId);

        // Delete the budget item
        $budgetItem->delete();

        // Fetch the budget items again to update the table
        $this->fetchBudgetItems();

        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();

    }
    //-- END BUDGETING -- //

    //-- BUDGET QTY --//
        public function toggleQty($budgetItemId)
        {
            $this->editQtyId = $budgetItemId;

            if ($budgetItemId) {
                $budgetItem = ProjectBudget::findOrFail($budgetItemId);
                $this->budgetqty = $budgetItem->quantity;
            } else {
                $this->budgetqty = '';
            }
        }

        public function updateQty($budgetItemId)
        {
            $validatedData = $this->validate([
                'budgetqty' => 'required|integer',
            ]);

            $budgetItem = ProjectBudget::findOrFail($budgetItemId);
            $budgetItem->quantity = $validatedData['budgetqty'];
            $budgetItem->save();

            $this->editQtyId = null; // Reset the edited budget item ID
        }
    //-- END BUDGET QTY --//

    //-- REQUISITION --//
        public function makeRequisition($budgetItemId)
        {
            $this->selectedBudgetItem = ProjectBudget::with('material', 'material.category', 'material.unit')->findOrFail($budgetItemId);
            $this->budgetItemId = $budgetItemId;
            $selectedBudgetItem = $this->selectedBudgetItem;
            $this->budgetItemName = $selectedBudgetItem->material->name;
            $this->budgetItemCategory = $selectedBudgetItem->material->category->category;
            $this->budgetItemUnit = $selectedBudgetItem->material->unit->name;
            $this->budgetItemQuantity = $selectedBudgetItem->quantity;

            $this->requisitionSum = Requisition::where('budget_id', $budgetItemId)->sum('quantity');

            $this->budgetBalance = $this->budgetItemQuantity - $this->requisitionSum;
        }

        public function saveRequisition()
        {
            // Validate the form data
            $this->validate([
                'requisitionQuantity' => 'required|numeric|min:1',
                'budgetActivity' => 'required|string|max:255',
            ]);

            // Create a new Requisitions instance
            $requisition = new Requisition();
            $requisition->budget_id = $this->budgetItemId;
            $requisition->quantity = $this->requisitionQuantity;
            $requisition->activity = $this->budgetActivity;
            $requisition->save();

            session()->flash('requisitionmessage', 'Requisition Successful');

            $this->dispatchBrowserEvent('close-modal');
            $this->resetInput();

            // Reset the form fields
            $this->requisitionQuantity = null;
            $this->budgetActivity = null;

        }

        public function deleteRequisition($requisitionId)
        {
            $this->requisitionId = $requisitionId;
        }

        public function destroyRequisition()
        {
            // Find the budget item by its ID
            $requisitionItem = Requisition::findOrFail($this->requisitionId);

            // Delete the budget item
            $requisitionItem->delete();

            // Fetch the budget items again to update the table
            $this->fetchRequisitions();

            $this->dispatchBrowserEvent('close-modal');
            $this->resetInput();

        }

        public function approveRequisition()
        {
            Requisition::whereHas('budget', function ($query) {
                $query->where('project_id', $this->projectId);
            })
            ->where('status', 0)
            ->update(['status' => 1]);

            // Refresh the allRequisitions data
            $this->fetchRequisitions();

            $this->dispatchBrowserEvent('close-modal');
            $this->resetInput();
        }

        public function fetchRequisitions()
        {
            $this->allRequisitions = Requisition::with(['budget.material', 'budget.material.category', 'budget.material.unit'])
                ->join('project_budgets', 'requisitions.budget_id', '=', 'project_budgets.id')
                ->where('project_budgets.project_id', $this->projectId)
                ->select('requisitions.*')
                ->paginate(10);
        }
    //-- END REQUISITION --//

    public function render()
    {
        $users = $this->users;

        $budgetItems = ProjectBudget::with(['material', 'material.category', 'material.unit'])
        ->whereHas('material', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhereHas('category', function ($q) {
                    $q->where('category', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('unit', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
        })
        ->where('project_id', $this->projectId)
        ->paginate(5, ['*'], 'budgetItemsPage');

        $allRequisitions = Requisition::with(['budget.material', 'budget.material.category', 'budget.material.unit'])
            ->join('project_budgets', 'requisitions.budget_id', '=', 'project_budgets.id')
            ->where('project_budgets.project_id', $this->projectId)
            ->where(function ($query) {
                $query->whereHas('budget.material', function ($q) {
                    $q->where('name', 'like', '%' . $this->requisitionSearch . '%')
                        ->orWhereHas('category', function ($q) {
                            $q->where('category', 'like', '%' . $this->requisitionSearch . '%');
                        })
                        ->orWhereHas('unit', function ($q) {
                            $q->where('name', 'like', '%' . $this->requisitionSearch . '%');
                        });
                })
                ->orWhere('activity', 'like', '%' . $this->requisitionSearch . '%');
            })
            ->select('requisitions.*')
            ->orderBy('created_at', 'desc') // Order by date (newest first)
            ->paginate(10, ['*'], 'allRequisitionsPage');

        $categories = $this->categories;
        $materials = $this->materials->where('category_id', $this->selectedCategory); // Filter materials by selected category

        return view('livewire.admin.project.details', [
            'users' => $users,
            'budgetItems' => $budgetItems,
            'categories' => $categories,
            'materials' => $materials,
            'allRequisitions' => $allRequisitions,
        ])->extends('layouts.admin')->section('content');
    }

}
