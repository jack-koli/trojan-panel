@extends('layouts.app')

@section('content')

@include('human')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">

@if (Auth::user()->id == 1)
    @if (\Illuminate\Support\Facades\Redis::get('trojan.register') == '1')
    <button class="register-toggle btn btn-sm btn-primary float-right" data-value="0">关闭注册</button>
        @else
                            <button class="register-toggle btn btn-sm btn-primary float-right" data-value="1">开启注册</button>
        @endif
@endif
    <h5>User List</h5>
</div>
<div class="card-body">
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th nowrap scope="col">#</th>
                <th nowrap scope="col">Username</th>
                <th nowrap scope="col">Email</th>
                <th nowrap scope="col">Is Admin</th>
                <th nowrap scope="col">Quota</th>
                <th nowrap scope="col">Download</th>
                <th nowrap scope="col">Upload</th>
                <th nowrap scope="col">Remain</th>
                <th nowrap scope="col">Edit</th>
                <th nowrap scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @php
                    list($quota, $download, $upload, $remain) = human_string($user->quota, $user->download, $user->upload);
                @endphp
                <tr>
                    <th nowrap scope="row">{{ $user->id }}</th>
                    <td nowrap>{{ $user-> username }}</td>
                    <td nowrap>{{ $user-> email }}</td>
                    <td nowrap>{{ $user-> is_admin ? 'True' : 'False'}}</td>
                    <td nowrap>{{ $quota }}</td>
                    <td nowrap>{{ $download }}</td>
                    <td nowrap>{{ $upload }}</td>
                    <td nowrap>{{ $remain }}</td>
                    <td nowrap><a href="users/{{ $user->id }}/edit" class="btn btn-info btn-sm">Edit</button></td>
                    <td nowrap>
                        @if (Auth::user()->id != $user->id)
                            <form action="users/{{ $user->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $users->links() }}
</div>
</div>
</div>
</div>
</div>
@endsection
@section('after_js')
<script src="{!!url('/js/jquery-3.5.1.min.js')!!}"></script>
<script>
jQuery(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.register-toggle').click(function (){
        $.post('/users/toggle', {
            status : $(this).data('value')
        }, function(){
            window.location.reload();
        }, 'json')
    })
})
</script>
@endsection
