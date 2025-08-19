@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Thùng rác mail</h1>
    <a href="{{ route('admin.mails.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Tiêu đề</th>
                <th>Loại</th>
                <th>Đã xóa lúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach($mails as $mail)
            <tr>
                <td>{{ $mail->id }}</td>
                <td>{{ $mail->name }}</td>
                <td>{{ $mail->subject }}</td>
                <td>{{ $mail->type }}</td>
                <td>{{ $mail->deleted_at }}</td>
                <td>
                    <form action="{{ route('admin.mails.restore', $mail->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Khôi phục</button>
                    </form>
                    <form action="{{ route('admin.mails.forceDelete', $mail->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa vĩnh viễn mail này?')">Xóa vĩnh viễn</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
