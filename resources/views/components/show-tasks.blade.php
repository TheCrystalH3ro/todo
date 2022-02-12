<div class="list">

    @foreach ($tasks as $task)
                            
        @include('components.task-card')

    @endforeach

</div>

<div class="original-inputs">
    <input type="hidden" name="orig_task_name" value="{{ $name ?: '' }}">
    <input type="hidden" name="orig_visibility" value="{{ $visibility ?: '' }}">
    <input type="hidden" name="orig_status" value="{{ $status ?: '' }}">
    <input type="hidden" name="orig_membership" value="{{ $membership ?: '' }}">
    <input type="hidden" name="orig_from" value="{{ $from ?: '' }}">
    <input type="hidden" name="orig_to" value="{{ $to ?: '' }}">

    @if (isset($category_form))
        @foreach ($category_form as $category)
            <input type="hidden" name="orig_category[]" value="{{ $category }}">
        @endforeach
    @endif

    @if (isset($shared_with))
        @foreach (array_filter($shared_with) as $shared)
            <input type="hidden" name="orig_shared_with[]" value="{{ $shared }}">
        @endforeach
    @endif
</div>

<ul class="pagination blue-grey lighten-5 z-depth-3">
    <li class="{{ $page == 1 ? 'disabled' : "waves-effect"}}">
        <a {{ $page != 1 ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page - 1), $page_url) ) : ( $page_url . 'page='. ($page - 1) ) ) ) : ''}}>
            <i class="material-icons" data-page="{{ ($page - 1) }}">chevron_left</i>
        </a>
    </li>

    @foreach ($pages as $page_i)

        @if ($page_i === '...')
            <li class="page disabled">
                <a>...</a>
            </li>

            @continue
        @endif
        
        <li class="page {{ ($page_i == $page) ? 'active' : 'waves-effect'  }}"> 
            <a data-page="{{ $page_i }}" {{ $page_i != $page ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page_i), $page_url) ) : ( $page_url . 'page='. ($page_i) ) ) ) : ''}}>{{ $page_i }}</a>
        </li>

    @endforeach

    <li class="{{ $page == $last_page ? 'disabled' : "waves-effect"}}">
        <a {{ $page != $last_page ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page + 1), $page_url) ) : ( $page_url . 'page='. ($page + 1) ) ) ) : ''}}>
            <i class="material-icons" data-page="{{ ($page + 1) }}">chevron_right</i>
        </a>
    </li>

</ul>