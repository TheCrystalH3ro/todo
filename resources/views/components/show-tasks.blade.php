<div class="list">

    @foreach ($tasks as $task)
                            
        @include('components.task-card')

    @endforeach

</div>

<ul class="pagination blue-grey lighten-5 z-depth-3">
    <li class="{{ $page == 1 ? 'disabled' : "waves-effect"}}">
        <a {{ $page != 1 ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page - 1), $page_url) ) : ( $page_url . 'page='. ($page - 1) ) ) ) : ''}}>
            <i class="material-icons">chevron_left</i>
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
            <a {{ $page_i != $page ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page_i), $page_url) ) : ( $page_url . 'page='. ($page_i) ) ) ) : ''}}>{{ $page_i }}</a>
        </li>

    @endforeach

    <li class="{{ $page == $last_page ? 'disabled' : "waves-effect"}}">
        <a {{ $page != $last_page ? ( 'href='. ($has_page ? ( str_replace("page=$page", 'page='. ($page + 1), $page_url) ) : ( $page_url . 'page='. ($page + 1) ) ) ) : ''}}>
            <i class="material-icons">chevron_right</i>
        </a>
    </li>

</ul>