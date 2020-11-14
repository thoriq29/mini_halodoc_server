@extends('layouts.app')

@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@elseif ($message = Session::get('error'))
<div class="alert alert-danger">
  <p>{{ $message }}</p>
</div>
@endif

<div class="container">
    <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Rumah Sakit</th>
            <th scope="col">Nama Pasien</th>
            <th scope="col">Nama Dokter</th>
            <th scope="col">Tanggal & Jam</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $key=> $booking)
            <tr>
            <th scope="row">{{++$key}}</th>
            <td>{{$booking->hospital->name}}</td>
            <td>{{$booking->patient->user->name}}</td>
            <td>{{$booking->doctor->name}}</td>
            <td>{{$booking->date}}</td>
            <td>
                @if($booking->status == "active")
                    <span class="badge badge-success">Aktif</span>
                @elseif($booking->status == "done")
                    <span class="badge badge-primary">Selesai</span>
                @else
                    <span class="badge badge-danger">Non Aktif</span>
                @endif
            </td>
            <td>
                @if($booking->status == "active")
                    <a class="btn btn-danger" href="{{ route('bookings.set_cancel',$booking->id) }}">Batalkan</a>
                    <a class="btn btn-primary" href="{{ route('bookings.send_notif',$booking->id) }}">Kirim Notifikasi</a>
                    <a class="btn btn-success" href="{{ route('bookings.set_done',$booking->id) }}">Selesai</a>
                @endif
            </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
