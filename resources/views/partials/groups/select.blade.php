<select class="form-control" name="{{ $field ?? 'group_id' }}" id="{{ $field ?? 'group_id' }}">
    <option value="" disabled selected>{{ __('Select your option') }}</option>
    @foreach($groups as $item)
        <option value="{{ $item->id }}"
                @if(isset($$selected) && $item->id === $$selected->group_id) selected @endif>{{ $item->name }}</option>
    @endforeach
</select>
