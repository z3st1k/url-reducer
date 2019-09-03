@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (isset($url))
        <h3>Your url</h3>
        <p><a href="{{$url}}" target="_blank">{{$url}}</a></p>
    @endif

    <form action="{{url('/')}}" method="post">
        {{ csrf_field() }}

        <div class="form-group">
            <label>Enter url <b>*</b>:</label><br>
            <input type="text" name="url" class="form-control" placeholder="http://example.com" required>
        </div>
        <div class="form-group">
            <label>Url key <i>(optional)</i>:</label><br>
            <input type="text" name="key" class="form-control" placeholder="Alpha numeric (without spaces)">
        </div>
        <div class="form-group">
            <label>Expire <i>(optional)</i>:</label><br>
            <input type="text" name="expire" class="form-control" placeholder="Value in minutes">
        </div>

        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
@stop
