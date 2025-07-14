@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="container mx-auto max-w-4xl mt-10">
    <div class="card shadow-lg rounded-lg border-0">
        <div class="card-header bg-indigo-700 text-white flex items-center rounded-t-lg" style="padding: 1.2rem 1.5rem;">
            <span class="icon mr-2"><i class="mdi mdi-file-document-box-search" style="font-size: 1.5rem;"></i></span>
            <h3 class="text-lg font-bold">Audit Log</h3>
        </div>
        <div class="card-body p-6 bg-white rounded-b-lg">
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Tanggal & Jam</th>
                            <th class="px-4 py-2">User</th>
                            <th class="px-4 py-2">Aksi</th>
                            <th class="px-4 py-2">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td class="px-4 py-2">{{ $log->user ? $log->user->email : '-' }}</td>
                            <td class="px-4 py-2">{{ $log->action }}</td>
                            <td class="px-4 py-2">{{ $log->description }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4">Tidak ada data audit log.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</div>
@endsection 