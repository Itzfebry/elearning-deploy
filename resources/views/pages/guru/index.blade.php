@extends('layouts.app')
@section('title', "Guru")
@section('titleHeader', "Data Guru")
@section('addBtn')
<a href="{{ route('guru.create') }}" class="button blue">
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
                        @forelse ($guru as $item)
                        <tr>
                            <td data-label="No">{{ $i++ }}</td>
                            <td data-label="NIP">{{ $item->nip }}</td>
                            <td data-label="Name">{{ $item->nama }}</td>
                            <td data-label="Email">{{ $item->user->email }}</td>
                            <td data-label="Jk">
                                @if ($item->jk == "L")
                                Laki-laki
                                @else
                                Perempuan
                                @endif
                            </td>
                            <td class="actions-cell">
                                <div class="buttons right nowrap">
                                    <a href="{{ route('guru.edit', $item->id) }}" class="button small blue --jb-modal"
                                        data-target="sample-modal-2" type="button">
                                        <span class="icon">
                                            <x-icon name="edit" class="w-4 h-4 text-white" />
                                        </span>
                                    </a>
                                    <button class="button small red btn-delete-guru" data-formid="{{ $item->id }}"
                                        data-formuserid="{{ $item->user_id }}" data-formname="{{ $item->nama }}"
                                        type="button">
                                        <span class="icon">
                                            <x-icon name="delete" class="w-4 h-4 text-white" />
                                        </span>
                                    </button>

                                </div>
                            </td>
                            {{-- Modal Delete --}}
                            {{-- <div id="modal-{{ $item->id }}" class="modal modal-delete-item">
                                <div class="modal-background --jb-modal-close"></div>
                                <div class="modal-card">
                                    <header class="modal-card-head">
                                        <p class="modal-card-title">Warning!</p>
                                    </header>
                                    <section class="modal-card-body">
                                        <p>Apakah anda ingin menghapus Guru <b>{{ $item->nama }}</b>?</p>
                                    </section>
                                    <form action="{{ route('guru.delete') }}" method="POST">
                                        @csrf
                                        <input type="text" name="user_id" value="{{ $item->user_id }}" hidden>
                                        <input type="text" name="formid" value="{{ $item->id }}" hidden>
                                        <footer class="modal-card-foot">
                                            <button class="button --jb-modal-close">Cancel</button>
                                            <button type="submit" class="button red">Hapus</button>
                                        </footer>
                                    </form>
                                </div>
                            </div> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Data Kosong</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $guru->links() }}
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
@push('extraScript')
<script>
    $('#page_length').on('change', function() {
        $('#form').submit();
    });

    $(document).ready(function () {
        $('.btn-delete-guru').on('click', function () {
            var formId = $(this).data('formid');
            var formUserId = $(this).data('formuserid');
            var formname = $(this).data('formname');

            console.log("Form ID:", formId);
            console.log("Form User ID:", formUserId);
            console.log("Form Name:", formname);

            $(document).on('click', '.--jb-modal-close', function () {
                $(this).closest('.modal').remove(); // atau gunakan `.removeClass('is-active')` kalau modal reusable
            });

            // Buat modal dinamis
            $('body').append(`
                <div id="modal-${formId}" class="modal modal-delete-item is-active">
                    <div class="modal-background --jb-modal-close"></div>
                    <div class="modal-card">
                        <header class="modal-card-head">
                            <p class="modal-card-title">Warning!</p>
                        </header>
                        <section class="modal-card-body">
                            <p>Apakah anda ingin menghapus Guru <b>${formname}</b>?</p>
                        </section>
                        <form action="{{ route('guru.delete') }}" method="POST">
                            @csrf
                            <input type="text" name="user_id" value="${formUserId}" hidden>
                            <input type="text" name="formid" value="${formId}" hidden>
                            <footer class="modal-card-foot">
                                <button type="button" class="button --jb-modal-close">Cancel</button>
                                <button type="submit" class="button red">Hapus</button>
                            </footer>
                        </form>
                    </div>
                </div>
            `);
        });
    });



    // $(document).on('click', '.modal-delete-item', function() {
    //     console.log("TESS");
    //     var formId = $(this).data('formid');
    //     var formUserId = $(this).data('formuserid');
    //     var formname = $(this).data('formname');

    //     $(`#modal-${formId}`).remove();
    //     $('.actions-cell').after(`
    //         <div id="modal-${formId}" class="modal modal-delete-item">
    //             <div class="modal-background --jb-modal-close"></div>
    //             <div class="modal-card">
    //                 <header class="modal-card-head">
    //                     <p class="modal-card-title">Warning!</p>
    //                 </header>
    //                 <section class="modal-card-body">
    //                     <p>Apakah anda ingin menghapus Guru <b>${formname}</b>?</p>
    //                 </section>
    //                 <form action="{{ route('guru.delete') }}" method="POST">
    //                     @csrf
    //                     <input type="text" name="user_id" value="${formUserId}" hidden>
    //                     <input type="text" name="formid" value="${formId}" hidden>
    //                     <footer class="modal-card-foot">
    //                         <button class="button --jb-modal-close">Cancel</button>
    //                         <button type="submit" class="button red">Hapus</button>
    //                     </footer>
    //                 </form>
    //             </div>
    //         </div>
    //     `);
    //     console.log("TESS111");
    // });
</script>
@endpush