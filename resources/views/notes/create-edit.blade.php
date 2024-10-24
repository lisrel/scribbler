@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Note') }}</div>

                    <div class="card-body">
                        <form action="">

                            <div class="row mb-3">
                                <label for="title" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Title') }}
                                </label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control "  name="title" required >
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-md-4 col-form-label text-md-end">
                                    {{ __('Description') }}
                                </label>

                                <div class="col-md-6">
                                    <textarea id="description" type="text" class="form-control" name="description" required ></textarea>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
