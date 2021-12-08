{{-- MEMBERS BUTTON --}}
<a class="waves-effect waves-light btn-floating modal-trigger teal lighten-2" href="#members">
    <i class="material-icons">people</i>
    {{ __('tasks.members') }}
</a>

{{-- MEMBERS MODAL --}}
<div id="members" class="modal">
    <div class="modal-content">
        <h4>{{ __('tasks.members') }}</h4>  
        
        {{-- ADD USER FORM --}}
        @if ($isOwner)
            
            <form action="" method="post">
                @csrf

                <div class="addPerson">

                    <h3> <i class="material-icons">reply</i> {{ __('tasks.shareWith') }} </h3>

                    <div class="input-field">

                        <div class="input-field inline">

                            <input type="text" name="username" id="username">
                            <label for="username">{{ __('tasks.user') }}</label>

                        </div>

                        <div class="input-field inline">
                            
                            <button type="submit" class="waves-effect waves-teal btn-flat text-teal text-lighten-2">
                                <i class="material-icons">send</i>
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        @endif

        {{-- MEMBER LIST --}}
        <div class="selection-box">

            <span>{{ __('tasks.members') }}:</span>

            <div class="member-box blue-grey lighten-5">

                <table class="highlight">
                    <tbody>

                        <tr class="member">

                            <td class="item">
                                <span>
                                    <i class="material-icons">person_pin</i>
                                    {{ $task->owner->name }}
                                </span>
                            </td>

                            <td class="control"></td>

                        </tr>

                        {{-- DELETE USER FORM --}}
                        @if ($isOwner)

                            <form action="" method="POST">
                                @csrf
                                @method('DESTROY')
                            
                        @endif

                        @foreach ($task->members as $member)
                            
                            <tr class="member member-{{ $member->id }}">
                                
                                <td class="item">
                                    <span>{{ $member->name }}</span>
                                </td>
                                
                                <td class="control">
                                    @if ($isOwner)
                                        
                                        <a class="modal-trigger" href="#member-delete-{{$member->id}}"> 
                                            <i class="material-icons">clear</i> 
                                        </a>

                                        <div id="member-delete-{{$member->id}}" class="modal">

                                            <div class="modal-content">

                                                <h5>{{ __('tasks.deleteMemberConfirmation', ['name' => $member->name]) }}</h5>

                                                <div class="input-field">

                                                    <button type="submit" name="member-remove" value="{{ $member->id }}">
                                                        {{ __('tasks.deleteMember') }}
                                                    </button>

                                                    <a class="modal-close waves-effect waves-light chip text-white btn modal-trigger teal lighten-2">
                                                        <span>{{ __('general.cancel') }}</span>
                                                    </a>

                                                </div>

                                            </div>

                                        </div>

                                    @endif
                                </td>

                            </tr>

                        @endforeach

                        @if ($isOwner)
                            </form>
                        @endif

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