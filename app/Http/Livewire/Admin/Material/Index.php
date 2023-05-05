<?php

namespace App\Http\Livewire\Admin\Material;

use App\Models\Measure;
use Livewire\Component;
use App\Models\Material;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\MaterialCategory;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $material_id, $name, $slug, $category_id, $unit_id;
    public $search;

    public function rules()
    {
        return [
            'name' => 'required|string',
            'category_id' => 'required|numeric|min:1',
            'unit_id' => 'required|numeric|min:1'
        ];
    }

    public function mount()
    {
        $this->categories = MaterialCategory::all();
        $this->units = Measure::all();
    }

    public function resetInput() {
        $this->name = NULL;
        $this->category_id = NULL;
        $this->unit_id = NULL;
    }

    public function storeMaterial()
    {
        $validatedData = $this->validate();
        $validatedData['slug'] = Str::slug($validatedData['name']);

        Material::create($validatedData);
        session()->flash('message', 'Material Added Successfully');

        $this->dispatchBrowserEvent('close-modal');
        $this->resetInput();
    }

    public function render()
    {
        $materials = Material::where('materials.name', 'like', '%'.$this->search.'%')
                ->join('material_category', 'materials.category_id', '=', 'material_category.id')
                ->join('measures', 'materials.unit_id', '=', 'measures.id')
                ->select('materials.*', 'material_category.category as category', 'measures.name as unit')
                ->orderBy('materials.name', 'ASC')
                ->paginate(10);
        return view('livewire.admin.material.index',
            [
                'materials' => $materials,
            ])
            ->extends('layouts.admin')->section('content');
    }
}
