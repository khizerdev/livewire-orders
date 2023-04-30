<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class CategoriesList extends Component
{
    use WithPagination;

    public Category $category; 
 
    public bool $showModal = false;
    public array $active = [];
    public Collection $categories;
    public int $editedCategoryId = 0;

    protected $listeners = ['delete'];

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

        if ($this->editedCategoryId === 0) { 
            $this->category->position = Category::max('position') + 1;
        } 
 
        $this->category->save();
 
        $this->resetValidation(); 
        $this->reset('showModal', 'editedCategoryId'); 
    }

    public function toggleIsActive($categoryId) 
    {
        Category::where('id', $categoryId)->update([
            'is_active' => $this->active[$categoryId],
        ]);
    }

    public function updateOrder($list)
    {
        foreach ($list as $item) {
            $cat = $this->categories->firstWhere('id', $item['value']);
 
            if ($cat['position'] != $item['order']) {
                Category::where('id', $item['value'])->update(['position' => $item['order']]);
            }
        }
    }

    public function editCategory($categoryId)
    {
        $this->resetValidation();
        $this->editedCategoryId = $categoryId;
 
        $this->category = Category::find($categoryId);
    }

    public function cancelCategoryEdit() 
    {
        $this->resetValidation(); 
        $this->reset('editedCategoryId');
    }

    public function deleteConfirm($method, $id = null)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type'   => 'warning',
            'title'  => 'Are you sure?',
            'text'   => '',
            'id'     => $id,
            'method' => $method,
        ]);
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
    }
 
    public function render()
    {
        $cats = Category::orderBy('position')->paginate(5); 
        $links = $cats->links();
        $this->categories = collect($cats->items());

        $this->active = $this->categories->mapWithKeys( 
            fn (Category $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.categories-list' , [
            'links' => $links, 
        ]);
    }
}
