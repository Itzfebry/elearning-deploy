@extends('layouts.app')
@section('title', "Tahun Ajaran")
@section('titleHeader', "Data Tahun Ajaran")
@section('addBtn')
<a href="{{ route('tahun-ajaran.create') }}" class="button blue">
    <span>Tambah</span>
</a>
@endsection

@section('content')
<section class="section main-section">
    <form id="form" method="get">
        <div class="card has-table">
            <header class="card-header flex justify-between">
                <div class="px-4 py-3">
                    <div class="gap-2">
                        <label for="page_length" class="text-sm text-gray-700 mb-0">Show</label>
                        <select name="page_length" id="page_length"
                            class="w-20 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <option value="10" @isset($_GET['page_length']) {{ $_GET['page_length']==10 ? 'selected'
                                : '' }} @endisset>10</option>
                            <option value="20" @isset($_GET['page_length']) {{ $_GET['page_length']==20 ? 'selected'
                                : '' }} @endisset>20</option>
                            <option value="50" @isset($_GET['page_length']) {{ $_GET['page_length']==50 ? 'selected'
                                : '' }} @endisset>50</option>
                        </select>
                        <label for="page_length" class="text-sm text-gray-700 mb-0">entries</label>
                    </div>
                </div>
                <div class="px-4 py-3">
                    <div class="relative w-60">
                        <input type="text" name="search" placeholder="Search..."
                            value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"
                            class="w-full pl-10 pr-3 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm" />
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </header>
            <div class="card-content">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 5;
                        $i = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                        @endphp
                        @forelse ($tahunAjaran as $item)
                        <tr>
                            <td data-label="No">{{ $i++ }}</td>
                            <td data-label="Tahun">{{ $item->tahun }}</td>
                            <td data-label="status">
                                @if ($item->status == "aktif")
                                <span
                                    class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <span class="w-2 h-2 me-1 bg-green-500 rounded-full"></span>
                                    {{ $item->status }}
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <span class="w-2 h-2 me-1 bg-red-500 rounded-full"></span>
                                    {{ $item->status }}
                                </span>
                                @endif
                            </td>
                            <td class="actions-cell content-delete">
                                <div class="buttons right nowrap">
                                    <a href="{{ url('/tahun-ajaran/edit?tahun_ajaran='. urlencode($item->tahun)) }}"
                                        class="button small blue --jb-modal" data-target="sample-modal-2" type="button">
                                        <span class="icon">
                                            <x-icon name="edit" class="w-4 h-4 text-white" />
                                        </span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">Data Kosong</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $tahunAjaran->links() }}
                </div>
            </div>
        </div>
    </form>
    <div id="modalDelete"></div>
</section>
@endsection
@push('extraScript')
<script>
    $('#page_length').on('change', function() {
        $('#form').submit();
    });
</script>
@endpush