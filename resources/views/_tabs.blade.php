{{-- Switch between the add-on pages --}}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link @if($active === 'schedule') active @endif" href="{{ route('jobs-monitor.schedule') }}">
            <i class="la la-clock me-1"></i> {{ trans('mikhailmelnikov.backpack-jobs-monitor::ui.tabs.schedule') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if($active === 'queue') active @endif" href="{{ route('jobs-monitor.queue') }}">
            <i class="la la-stream me-1"></i> {{ trans('mikhailmelnikov.backpack-jobs-monitor::ui.tabs.queue') }}
            @if($failedCount > 0)
                <span class="badge bg-danger text-white ms-1">{{ $failedCount }}</span>
            @endif
        </a>
    </li>
</ul>
