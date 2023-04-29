<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use WithPagination; 
 
    public function render()
    {
        $categories = Category::paginate(10); 
 
        return view('livewire.categories-list', [
            'categories' => $categories, 
        ]);
    }
}
