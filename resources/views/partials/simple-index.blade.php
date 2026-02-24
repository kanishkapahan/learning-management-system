{{-- Page Toolbar --}}
<div class="page-toolbar animate-in">
    <div></div>
    <div class="page-toolbar-actions">
        <a href="{{ $createRoute }}" class="btn-action btn-action-primary">
            <i class="fas fa-plus"></i> Add {{ rtrim($title, 's') }}
        </a>
    </div>
</div>

{{-- Data Card --}}
<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon primary"><i class="fas fa-{{ $icon ?? 'list' }}"></i></span>
            {{ $title }}
        </h6>
        <span class="text-muted" style="font-size:.75rem">{{ $rows->total() }} total</span>
    </div>
    <div class="page-card-body p-0">
        @if($rows->count())
        <div class="table-responsive">
            <table class="enhanced-table">
                <thead>
                    <tr>
                        @foreach($columns as $label)
                        <th>{{ $label }}</th>
                        @endforeach
                        <th style="width:100px" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr>
                        @foreach(array_keys($columns) as $key)
                        <td>
                            @if($key === 'status')
                            <span class="status-badge {{ data_get($row, $key) }}">{{ data_get($row, $key) }}</span>
                            @else
                            {{ data_get($row, $key) }}
                            @endif
                        </td>
                        @endforeach
                        <td>
                            <div class="action-group justify-content-end">
                                <a href="{{ route($editRouteName, $row) }}" class="action-btn edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route($deleteRouteName, $row) }}"
                                    class="js-confirm-action" data-confirm="Delete this record?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($rows->hasPages())
        <div class="px-3 pb-3 pt-2 d-flex justify-content-end">
            {{ $rows->links() }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No {{ strtolower($title) }} found.</p>
        </div>
        @endif
    </div>
</div>