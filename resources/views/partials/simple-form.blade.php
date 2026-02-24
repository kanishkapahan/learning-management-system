{{-- Form Card --}}
<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon primary"><i
                    class="fas fa-{{ $model->exists ? 'pen-to-square' : 'plus' }}"></i></span>
            {{ $model->exists ? 'Edit' : 'Create' }} {{ $entityName ?? 'Record' }}
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" action="{{ $model->exists ? route($updateRoute, $model) : route($storeRoute) }}"
            class="row g-3">
            @csrf @if($model->exists) @method('PUT') @endif

            @foreach($fields as $field)
            <div class="{{ $field['col'] ?? 'col-md-6' }}">
                <label class="form-label" for="field_{{ $field['name'] }}">{{ $field['label'] }}</label>

                @if(($field['type'] ?? 'text') === 'select')
                <select name="{{ $field['name'] }}" id="field_{{ $field['name'] }}" class="form-select">
                    @foreach(($field['options'] ?? []) as $value => $label)
                    <option value="{{ $value }}" @selected(old($field['name'], data_get($model,
                        $field['name']))==$value)>{{ $label }}</option>
                    @endforeach
                </select>
                @elseif(($field['type'] ?? 'text') === 'textarea')
                <textarea name="{{ $field['name'] }}" id="field_{{ $field['name'] }}" class="form-control"
                    rows="3">{{ old($field['name'], data_get($model, $field['name'])) }}</textarea>
                @else
                <input name="{{ $field['name'] }}" id="field_{{ $field['name'] }}" type="{{ $field['type'] ?? 'text' }}"
                    class="form-control" value="{{ old($field['name'], data_get($model, $field['name'])) }}">
                @endif
            </div>
            @endforeach

            <div class="col-12">
                <div class="form-actions">
                    <button type="submit" class="btn-action btn-action-primary">
                        <i class="fas fa-{{ $model->exists ? 'save' : 'plus' }}"></i>
                        {{ $model->exists ? 'Update' : 'Create' }}
                    </button>
                    <a href="{{ url()->previous() }}" class="btn-action btn-action-outline">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>