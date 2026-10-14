@extends(backpack_view('blank'))

@section('header')
    @php $ui = 'mikhailmelnikov.backpack-jobs-monitor::ui.queue.'; @endphp
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none">
        <h1 class="mb-0">{{ $title }}</h1>
        <p class="mb-0 ms-2 ml-2">
            <small>{!! trans($ui . 'subtitle', ['connection' => e($overview['connection'])]) !!}</small>
        </p>
    </section>
@endsection

@section('content')
    @php $ui = 'mikhailmelnikov.backpack-jobs-monitor::ui.queue.'; @endphp
    @include('mikhailmelnikov.backpack-jobs-monitor::_tabs', ['active' => 'queue', 'failedCount' => $overview['failed']])

    <div class="row row-cards mb-3">
        @php
            $cards = [
                [trans($ui . 'waiting'), $overview['waiting'], 'bg-blue'],
                [trans($ui . 'reserved'), $overview['reserved'], 'bg-azure'],
                [trans($ui . 'delayed'), $overview['delayed'], 'bg-yellow'],
                [trans($ui . 'failed'), $overview['failed'], 'bg-red'],
            ];
        @endphp
        @foreach($cards as [$label, $value, $color])
            <div class="col-6 col-md-3">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <span class="badge {{ $color }} text-white me-2">&nbsp;</span>
                        <div>
                            <div class="h1 mb-0">{{ $value }}</div>
                            <div class="text-muted">{{ $label }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($overview['queues'] !== [])
        <div class="mb-3 text-muted">
            {{ trans($ui . 'by_queue') }}
            @foreach($overview['queues'] as $queue => $total)
                <span class="badge bg-secondary text-white">{{ $queue }}: {{ $total }}</span>
            @endforeach
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">{{ trans($ui . 'queued_title', ['count' => $queuedJobs->total()]) }}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                <tr>
                    <th>{{ trans($ui . 'id') }}</th>
                    <th>{{ trans($ui . 'job') }}</th>
                    <th>{{ trans($ui . 'queue') }}</th>
                    <th>{{ trans($ui . 'attempts') }}</th>
                    <th>{{ trans($ui . 'created_at') }}</th>
                    <th>{{ trans($ui . 'available_at') }}</th>
                    <th class="text-end"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($queuedJobs as $job)
                    <tr>
                        <td class="text-muted">{{ $job->id }}</td>
                        <td>
                            <code>{{ $job->name }}</code>
                            @if($job->isReserved)
                                <span class="badge bg-azure text-white">{{ trans($ui . 'reserved_since', ['time' => $job->reservedAt?->format('H:i:s')]) }}</span>
                            @endif
                            <details class="mt-1">
                                <summary class="text-muted small" style="cursor: pointer;">payload</summary>
                                <pre class="small mb-0 mt-1" style="max-height: 300px; overflow: auto;">{{ $job->payload }}</pre>
                            </details>
                        </td>
                        <td>{{ $job->queue }}</td>
                        <td>{{ $job->attempts }}</td>
                        <td class="text-nowrap">{{ $job->createdAt->format('d.m.Y H:i:s') }}</td>
                        <td class="text-nowrap">{{ $job->availableAt->format('d.m.Y H:i:s') }}</td>
                        <td class="text-end">
                            @unless($job->isReserved)
                                <form action="{{ route('jobs-monitor.queue.delete', ['id' => $job->id]) }}" method="POST"
                                      class="d-inline-block"
                                      onsubmit="return confirm({{ Js::from(trans($ui . 'delete_confirm', ['id' => $job->id])) }});">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="la la-trash"></i>
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">{{ trans($ui . 'empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{-- bootstrap-5 explicitly: the default paginator view does not match the Backpack themes --}}
        @if($queuedJobs->hasPages())
            <div class="card-footer">{{ $queuedJobs->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ trans($ui . 'failed_title', ['count' => $failedJobs->total()]) }}</h3>
            @if($failedJobs->total() > 0)
                <div class="d-flex f-gap-1">
                    <form action="{{ route('jobs-monitor.failed.retry-all') }}" method="POST" class="d-inline-block"
                          onsubmit="return confirm({{ Js::from(trans($ui . 'retry_all_confirm')) }});">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="la la-redo"></i> {{ trans($ui . 'retry_all') }}
                        </button>
                    </form>
                    <form action="{{ route('jobs-monitor.failed.flush') }}" method="POST" class="d-inline-block ms-1"
                          onsubmit="return confirm({{ Js::from(trans($ui . 'flush_confirm')) }});">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="la la-trash"></i> {{ trans($ui . 'flush') }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                <tr>
                    <th>{{ trans($ui . 'job') }}</th>
                    <th>{{ trans($ui . 'recipient') }}</th>
                    <th>{{ trans($ui . 'queue') }}</th>
                    <th>{{ trans($ui . 'failed_at') }}</th>
                    <th>{{ trans($ui . 'error') }}</th>
                    <th class="text-end"></th>
                </tr>
                </thead>
                <tbody>
                @forelse($failedJobs as $job)
                    <tr>
                        <td>
                            <code>{{ $job->name }}</code>
                            <div class="text-muted small">{{ $job->uuid }}</div>
                            <details class="mt-1">
                                <summary class="text-muted small" style="cursor: pointer;">payload</summary>
                                <pre class="small mb-0 mt-1" style="max-height: 300px; overflow: auto;">{{ $job->payload }}</pre>
                            </details>
                        </td>
                        <td>
                            @if($job->recipient)
                                <span class="text-break">{{ $job->recipient }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-nowrap">{{ $job->connection }} / {{ $job->queue }}</td>
                        <td class="text-nowrap">
                            <div>{{ $job->failedAt->format('d.m.Y H:i:s') }}</div>
                            <div class="text-muted small">{{ $job->failedAt->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="text-danger">{{ Str::limit($job->message, 200) }}</div>
                            <details class="mt-1">
                                <summary class="text-muted small" style="cursor: pointer;">{{ trans($ui . 'stack_trace') }}</summary>
                                <pre class="small mb-0 mt-1" style="max-height: 400px; overflow: auto;">{{ $job->exception }}</pre>
                            </details>
                        </td>
                        <td class="text-end text-nowrap">
                            <form action="{{ route('jobs-monitor.failed.retry', ['uuid' => $job->uuid]) }}" method="POST"
                                  class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" title="{{ trans($ui . 'retry') }}">
                                    <i class="la la-redo"></i>
                                </button>
                            </form>
                            <form action="{{ route('jobs-monitor.failed.delete', ['uuid' => $job->uuid]) }}" method="POST"
                                  class="d-inline-block"
                                  onsubmit="return confirm({{ Js::from(trans($ui . 'delete_failed_confirm')) }});">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ trans($ui . 'delete_failed') }}">
                                    <i class="la la-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">{{ trans($ui . 'failed_empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($failedJobs->hasPages())
            <div class="card-footer">{{ $failedJobs->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
