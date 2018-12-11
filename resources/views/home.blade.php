@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Files System</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            {{ Form::open(array('route' => array('home.create'), 'files' => 'true', 'class'=>'form-horizontal')) }}

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>

                                </div>
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>


                            {{ Form::close() }}

                            <table class="table table-hover table-responsive-lg">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Filename</th>
                                <th scope="col">Url</th>
                                <th scope="col">Delete</th>
                            </tr>
                            </thead>
                            <tbody>

                                @foreach($archives as $archive)
                            <tr>
                                <th scope="row">{{ $archive->id }}</th>
                                <td>{{ $archive->filename }}</td>
                                <td>{{ $archive->url }}</td>
                                <td>
                                    <a href="{{ route("home.delete", [$archive->id]) }}"><i class="material-icons">delete</i></a>
                                </td>
                            </tr>

                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
