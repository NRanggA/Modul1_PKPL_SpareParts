@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-5">
    <h4>Tambah Barang</h4>
    <form method="POST" action="{{ route('inventory.create') }}" enctype="multipart/form-data">
      @csrf
      <div class="mb-2">
        <input type="text" name="nama_barang" class="form-control" placeholder="Nama Spare Part" required>
      </div>
      <div class="mb-2">
        <input type="number" name="stok" class="form-control" placeholder="Stok awal" required min="0">
      </div>
      <div class="mb-2">
        <select name="kategori" class="form-select">
          <option value="">-- Pilih Kategori --</option>
          <option value="Mekanis">Mekanis</option>
          <option value="Elektrikal">Elektrikal</option>
          <option value="Aksesoris">Aksesoris</option>
          <option value="OEM">OEM</option>
          <option value="Aftermarket">Aftermarket</option>
          <option value="KW">KW</option>
        </select>
      </div>

      <!--Upload Gambar-->
      <div class="mb-2">
        <input type="file" name="gambar" class="form-control" accept="image/*">
      </div>
      <button class="btn btn-primary">Tambah</button>
    </form>
  </div>

  <div class="col-md-7">
    <h4>Daftar Barang</h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Kategori</th>
          <th>Stok</th>
          <th>Gambar</th>
          <th>Jumlah</th>
       </tr>
      </thead>
      <tbody>
        @foreach($items as $i)
        <tr>
          <td>{{ $i->nama_barang }}</td>
          <td>{{ $i->kategori ?? '-' }}</td>
          <td>{{ $i->stok }}</td>
          <td>
            @if($i->gambar)
            <img src="{{ asset('storage/'.$i->gambar) }}" alt="gambar {{ $i->nama_barang }}" style="max-width: 100px; max-height: 100px; object-fit: contain;">
            @else
            <span class="text-muted">Tidak ada gambar</span>
            @endif
          </td>
          <td>
            <form method="POST" action="{{ route('inventory.update', $i->id) }}" class="d-inline">
              @csrf
              <input type="number" name="stok" value="{{ $i->stok }}" class="form-control d-inline w-50" style="display:inline-block">
              <button class="btn btn-warning btn-sm">Update</button>
            </form>
            <a href="{{ route('inventory.delete', $i->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang ini?')">Hapus</a>
          </td>
        </tr>
        @endforeach
        @if($items->isEmpty())
        <tr><td colspan="5" class="text-center">Belum ada barang.</td></tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection
