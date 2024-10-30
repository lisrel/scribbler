@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-success float-end" href="{{route('notes.create')}}">Create new</a>
                <table id="notes_table" class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#notes_table').dataTable({
                "serverSide": true,
                "responsive": true,
                "ajax": "{{route('notes.data')}}"
            });
        });
    </script>
@endsection

