@extends('admin.layouts.app')

@section('title', 'Logs Administrateur')

@section('content')
<h2 class="text-xl font-semibold mb-4">Logs administrateur</h2>

<div class="bg-white rounded shadow overflow-hidden">
    @if($logs->isEmpty())
        <div class="p-4 text-gray-600">Aucun log trouvé.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Admin</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ optional($log->admin)->name ?? '—' }}</td>
                        <td class="p-3">{{ $log->action }}</td>
                        <td class="p-3">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
