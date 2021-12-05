<ul class="category-list">
    {{-- DISPLAY TASK'S CATEGORIES --}}
    @isset($task)    
        @foreach ($task->categories as $category)        
            <li class="category-item category-item-{{ $category->id }}">
                <span class="chip"> {{ $category->name }} </span>
            </li>
        @endforeach
    @endisset
</ul>