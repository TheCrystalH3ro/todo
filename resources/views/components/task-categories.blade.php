<div class="categories">

    @include('components.category-list')
    
    <a class="waves-effect waves-light text-white chip btn modal-trigger teal lighten-2" href="#categories">{{ __('tasks.editCategories') }}</a>
</div>

<div id="categories" class="modal">
    <div class="modal-content">
        <h4>{{ __('tasks.categories') }}</h4>  
        
        <div class="input-field">
            <select id="category-select">
                <option value="" disabled selected>{{ __('tasks.categories') }}</option>

                @foreach ($categories as $category)

                    <option class="category-select-{{ $category->id }}" value="{{ $category->id }}">{{ $category->name }}</option>

                @endforeach

            </select>
            <label>{{ __('tasks.categories-select') }}</label>
        </div>

        <div class="selection-box">

            <span>{{ __('tasks.categories-selected') }}</span>

            <div class="category-box blue-grey lighten-5">

                <table class="highlight">
                    <tbody>

                        @isset($task)    
                            @foreach ($task->categories as $category)

                                <tr class="category category-{{ $category->id }}">

                                    <td class="item">
                                        <span>{{ $category->name }}</span>
                                    </td>

                                    <td class="control">
                                        <a class="delete-category" data-category="{{ $category->id }}"> <i class="material-icons">clear</i> </a>
                                    </td>
                                </tr>

                            @endforeach
                        @endisset

                    </tbody>
                </table>

            </div>

        </div>
        
    </div>
    <div class="modal-footer">
        <a class="modal-close waves-effect waves-light chip text-white btn modal-trigger teal lighten-2">
            <span>{{ __('general.done') }}</span>
        </a>
    </div>
</div>

<div class="delete-categories">

</div>