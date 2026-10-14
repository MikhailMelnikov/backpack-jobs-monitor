@extends(backpack_view('blank'))

@section('header')
    @php $ui = 'mikhailmelnikov.backpack-jobs-monitor::ui.schedule.'; @endphp
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none">
        <h1 class="mb-0">{{ $title }}</h1>
        <p class="mb-0 ms-2 ml-2">
            <small>{!! trans($ui . 'subtitle') !!}</small>
        </p>
    </section>
@endsection

@section('content')
    @php $ui = 'mikhailmelnikov.backpack-jobs-monitor::ui.schedule.'; @endphp
    @include('mikhailmelnikov.backpack-jobs-monitor::_tabs', ['active' => 'schedule', 'failedCount' => $failedCount])

    <div class="alert alert-info d-print-none">
        <i class="la la-info-circle"></i>
        {!! trans($ui . 'help', ['connection' => e($queueConnection)]) !!}
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                <tr>
                    <th>{{ trans($ui . 'task') }}</th>
                    <th>{{ trans($ui . 'schedule') }}</th>
                    <th>{{ trans($ui . 'next_run') }}</th>
                    <th class="text-end">{{ trans($ui . 'manual_run') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $task->name }}</div>
                            <div class="text-muted small">
                                <code>{{ $task->target }}</code>
                            </div>
                            <div class="mt-1">
                                @if($task->isRunning)
                                    <span class="badge bg-azure text-white">{{ trans($ui . 'running') }}</span>
                                @endif
                                @if($task->withoutOverlapping)
                                    <span class="badge bg-secondary text-white">{{ trans($ui . 'without_overlapping') }}</span>
                                @endif
                                @if($task->onOneServer)
                                    <span class="badge bg-secondary text-white">{{ trans($ui . 'on_one_server') }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>{{ $task->schedule }}</div>
                            <div class="text-muted small"><code>{{ $task->expression }}</code> · {{ $task->timezone }}</div>
                        </td>
                        <td>
                            @if($task->nextRunAt)
                                <div>{{ $task->nextRunAt->format('d.m.Y H:i') }}</div>
                                <div class="text-muted small">{{ $task->nextRunAt->diffForHumans() }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <form action="{{ route('jobs-monitor.schedule.run', ['task' => $task->id]) }}" method="POST"
                                  class="d-inline-block">
                                @csrf
                                @if($task->isJob)
                                    <button type="submit" name="mode" value="queue" class="btn btn-sm btn-primary">
                                        <i class="la la-paper-plane"></i> {{ trans($ui . 'queue_button') }}
                                    </button>
                                @endif
                                <button type="submit" name="mode" value="sync" class="btn btn-sm btn-outline-primary"
                                        onclick="return confirm({{ Js::from(trans($ui . 'sync_confirm', ['name' => $task->name])) }});">
                                    <i class="la la-play"></i> {{ trans($ui . 'sync_button') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">{{ trans($ui . 'empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
