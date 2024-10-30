@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a class="btn btn-success float-end" href="{{route('notes.create')}}">Create new</a>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($notes as $item)
                            <tr>
                                <td>
                                    {{$item->title}}
                                </td>
                                <td>
                                    {{$item->created_at}}
                                </td>
                                <td>
                                    {{App\Models\User::find($item->user_id)->name}}
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{route('notes.show', $item->id)}}"> Show </a>
                                    @if($item->user_id == Auth::id())
                                        <a class="btn btn-secondary" href="{{route('notes.edit', $item->id)}}"> Edit </a>

                                        <form method="POST" action="{{route('notes.destroy', $item->id)}}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" type="submit">
                                                Delete
                                            </button>

                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
