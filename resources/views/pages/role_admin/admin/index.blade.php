@extends('layouts.app')
@section('title', "Admin")
@section('titleHeader', "Data Admin")
@section('addBtn')
<a href="{{ route('admin.create') }}" class="button blue">
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
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $page_length = isset($_GET['page_length']) ? $_GET['page_length'] : 5;
                        $i = $page == 1 ? 1 : $page * $page_length - $page_length + 1;
                        @endphp
                        @forelse ($admin as $item)
                        <tr>
                            <td data-label="No">{{ $i++ }}</td>
                            <td data-label="NIP">{{ $item->nip }}</td>
                            <td data-label="Name">{{ $item->nama }}</td>
                            <td data-label="Email">{{ $item->user->email }}</td>
                            <td data-label="Jk">{{ $item->jk == "L" ? "Laki-laki" : "Perempuan" }}</td>
                            <td class="actions-cell">
                                <div class="buttons right nowrap">
                                    <a href="{{ route('admin.edit', $item->id) }}" class="button small blue --jb-modal"
                                        data-target="sample-modal-2" type="button">
                                        <span class="icon">
                                            <x-icon name="edit" class="w-3 h-3 text-white" />
                                        </span>
                                    </a>
                                    <button type="button" class="button small red openModalBtn"
                                        data-form_id="{{ $item->id }}" data-form_user_id="{{ $item->user_id }}"
                                        data-form_name="{{ $item->nama }}">
                                        <span class="icon">
                                            <x-icon name="delete" class="w-3 h-3 text-white" />
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Data Kosong</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $admin->links() }}
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

    $('.openModalBtn').click(function () {
        var formId = $(this).data('form_id');
        var formUserId = $(this).data('form_user_id');
        var formName = $(this).data('form_name');
        
        $('#modalDelete').html(`
            <div id="myModal-${formId}" style="background-color: rgba(0,0,0,0.5);" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg w-96 shadow-lg relative">
                    <h2 class="text-xl font-semibold mb-4 text-orange-400">Warning!</h2>
                    <p class="mb-4">Apakah anda ingin menhapus data Siswa <b>${formName}</b>?</p>
                    <form action="{{ route('admin.delete') }}" method="POST">
                        @csrf
                        <input type="text" name="user_id" value="${formUserId}" hidden>
                        <input type="text" name="formid" value="${formId}" hidden>
                        <div class="flex justify-end space-x-2 mt-4">
                            <button id="submitModalBtn" type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2">
                                Submit
                            </button>
                            <button id="closeModalBtn" type="button" class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-4 py-2">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        `);

        $(document).on('click', '#closeModalBtn', function () {
            $(`#myModal-${formId}`).remove();
        });
    });
</script>
@endpush