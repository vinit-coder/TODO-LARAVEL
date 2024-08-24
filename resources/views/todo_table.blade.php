@extends('layout')
@section('content')
@section('page_title', 'Todo List')
<div class="row">
    <div class="col-lg-12">
        <h2 class="text-info">PHP - Simple To Do List APP</h2>

        <hr class="my-4">
        <div class="alert alert-success d-none"></div>

        <div class="text-center">
            <input type="text" class="title" name="title" style="max-width: 300px;height: 30px;">
            <button class="btn btn-primary btn-sm subbtn">Add Task</button>
            <button class="btn btn-primary btn-sm allBtn taskbtn" task="1">Show All Tasks</button>
        </div>
        <table class="table" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th width="80px">#</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody class="tbody">
                @forelse ($todo as $note)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="task-title">{{ $note->title }}</td>
                    <td class="task-status">{{ $note->status == '1' ? 'Done' : '' }}</td>
                    <td>
                        @if ($note->status == 0)
                        <button class="btn btn-success btn-sm statusUpdate" data-id="{{ $note->id }}"><i
                                class="bi bi-check2-square icon-bold"></i></button>
                        <span class="vertical-line"></span>
                        @endif
                        <button type="button" class="btn btn-danger btn-sm deletebtn"
                            data-id="{{ $note->id }}"><i class="bi bi-x icon-bold"></i></button>
                    </td>
                </tr>
                @empty
                <tr class="no-data-row">
                    <td colspan="4">There are no tasks.</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modalView">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="delete_id">
                Are you sure you want to delete this task?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete_data">Delete Task</button>
                <button class="btn btn-secondary cancelBtn" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Confirmation Modal -->
@endsection
@section('script')
<script>
    $(document).ready(function() {


        // Add Task
        $('.subbtn').click(function() {
            var title = $('.title').val();

            $.ajax({
                url: "{{ route('task.create') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    title: title
                },
                success: function(response) {
                    if (response.status == true) {
                        var statusText = response.data.status == 1 ? 'Done' : '';
                        if ($('.tbody tr').hasClass('no-data-row')){
                            var rowCount = 0;
                        } else {
                            var rowCount = $('.tbody tr').length;
                        }
                        $('.tbody').append(`
                        <tr>
                            <td>${rowCount + 1}</td>
                            <td class="task-title">${response.data.title}</td>
                            <td class="task-status"> ${statusText}</td>
                            <td>
                                <button class="btn btn-success btn-sm statusUpdate" data-id="${response.data.id}"><i class="bi bi-check2-square icon-bold"></i></button>
                                <span class="vertical-line"></span>
                                <button type="button" class="btn btn-danger btn-sm deletebtn" data-id="${response.data.id}"><i class="bi bi-x icon-bold"></i></button>
                            </td>
                        </tr>
                    `);
                        $('.no-data-row').remove();
                        $('.title').val('');
                        $('.alert').removeClass('d-none alert-danger').addClass(
                            'alert-success').text(response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    var errorResponse = JSON.parse(xhr.responseText);
                    $('.alert').removeClass('d-none alert-success').addClass('alert-danger')
                        .text(errorResponse.message);
                }
            });
        });

        // Show tasks according to btn
        $(document).on('click', '.allBtn, .pnBTn', function() {
            var $button = $(this);
            var task = $button.attr('task');

            $.ajax({
                url: "all_tasks/" + task,
                type: 'GET',
                success: function(response) {
                    if (response.status === true) {
                        var allData = response.data;
                        var tData = '';
                        if (allData.length != 0) {
                            tData = tableData(allData);
                        } else {
                            tData += `<tr class="no-data-row">
                            <td colspan="4">There are no tasks.</td>
                            </tr>`;
                        }

                        $('.tbody').html(tData);
                        $('.alert-success').addClass('d-none');

                        if (task == '1') {

                            $button.removeClass('allBtn').addClass('pnBTn');
                            $button.removeClass('btn-primary').addClass('btn-info');
                            $button.text('Pending Tasks');
                            $button.attr('task', '0');
                        } else {

                            $button.removeClass('pnBTn').addClass('allBtn');
                            $button.removeClass('btn-info').addClass('btn-primary');
                            $button.text('Show All Tasks');
                            $button.attr('task', '1');
                        }
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    var errorResponse = JSON.parse(xhr.responseText);
                    $('.alert').removeClass('d-none alert-success').addClass('alert-danger')
                        .text(errorResponse
                            .message || 'An error occurred. Please try again!');
                }
            });
        });


        // Status Update on Button Click
        $(document).on('click', '.statusUpdate', function() {
            var id = $(this).data('id');
            var $button = $(this);
            var task = $('.taskbtn').attr('task');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "update_task/" + id,
                type: 'PUT',
                data: {
                    task: task
                },
                success: function(response) {
                    if (response.status === true) {
                        var allData = response.data;
                        var tData = '';
                        if (allData.length != 0) {
                            tData = tableData(allData);
                        }
                    } else {
                        tData = `<tr class="no-data-row">
                    <td colspan="4">There are no tasks.</td>
                </tr>`;
                    }

                    $('.tbody').html(tData);
                    $('.alert').removeClass('d-none alert-danger').addClass('alert-success')
                        .text(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $('.alert').removeClass('d-none alert-success').addClass('alert-danger')
                        .text(xhr.responseText || 'An error occurred');
                }
            });
        });

        // Show Confirmation Modal for Deletion
        $(document).on('click', '.deletebtn', function() {
            var delete_id = $(this).data('id');
            $('#delete_id').val(delete_id);
            $('#confirmationModal').modal('show');
        });

        // Cancel Button
        $('.cancelBtn').click(function() {
            $('#confirmationModal').modal('hide');
        });

        // Delete Task
        $('.delete_data').click(function() {
            var delete_id = $('#delete_id').val();
            var task = $('.taskbtn').attr('task');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "delete_task/" + delete_id,
                type: 'DELETE',
                data: {
                    task: task
                },
                success: function(response) {
                    if (response.status === true) {

                        var allData = response.data;
                        var tData = '';
                        if (allData.length != 0) {
                            tData = tableData(allData);
                        } else {
                            tData += `<tr class="no-data-row">
                            <td colspan="4">There are no tasks.</td>
                            </tr>`;
                        }

                        $('.tbody').html(tData);
                    }
                    $('#confirmationModal').modal('hide');
                    $('.alert').removeClass('d-none alert-danger').addClass('alert-success')
                        .text(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    var errorResponse = JSON.parse(xhr.responseText);
                    $('.alert').removeClass('d-none alert-success').addClass('alert-danger')
                        .text(errorResponse.message);
                }
            });
        });


        function tableData(data) {
            var tData = '';
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                var statusText = item.status == 1 ? 'Done' : '';
                var statusButtonHtml = '';
                if (item.status == '0') {
                    statusButtonHtml =
                        `<button class="btn btn-success btn-sm statusUpdate" data-id="${item.id}"><i class="bi bi-check2-square icon-bold"></i></button><span class="vertical-line" style="margin-left: 0.5rem; margin-right: 0;"></span>`;
                }
                tData += `<tr>
                        <td>${i + 1}</td>
                        <td class="task-title">${item.title}</td>
                        <td class="task-status">${statusText}</td>
                        <td>
                            ${statusButtonHtml}
                            <button type="button" class="btn btn-danger btn-sm deletebtn" data-id="${item.id}"><i class="bi bi-x icon-bold"></i></button>
                        </td>
                    </tr>`;
            }

            return tData;
        }
    });
</script>
@endsection