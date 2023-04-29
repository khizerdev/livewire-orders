<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoriesList extends Component
{
    use WithPagination;

    public Category $category; 
 
    public bool $showModal = false;

    public function openModal() 
    {
        $this->showModal = true;
 
        $this->category = new Category();
    }

    protected function rules(): array 
    {
        return [
            'category.name' => ['required', 'string', 'min:3'],
            'category.slug' => ['nullable', 'string'],
        ];
    }
    
    public function updatedCategoryName() 
    {
        $this->category->slug = Str::slug($this->category->name);
    }

    public function save() 
    {
        $this->validate();
 
        $this->category->save();
 
        $this->reset('showModal');
    } 
 
    public function render()
    {
        $categories = Category::paginate(10); 
 
        return view('livewire.categories-list', [
            'categories' => $categories, 
        ]);
    }
}
