<div class="switch">
    <label>
      {{ __('tasks.completed') }}:
      <input 
        @if (!$isEdit)
            disabled
        @endif 
      type="checkbox" name="status" {{ $task->status ? 'checked' : '' }}>
      <span class="lever"></span>
    </label>
</div>