
<div class="categories">
    <ul class="category-list">
        <li class="category-item">
            @foreach ($categories as $category)        
                <span class="waves-effect waves-light btn-small teal darken-4"> {{ $category->name }} </span>
            @endforeach
        </li>
    </ul>
    <a class="waves-effect waves-light btn modal-trigger teal lighten-2" href="#categories">{{ __('tasks.editCategories') }}</a>
</div>