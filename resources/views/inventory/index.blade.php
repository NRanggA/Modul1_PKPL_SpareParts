@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-5">
    <h4 dusk="form-tambah-title">Tambah Barang</h4>
    <form method="POST" action="{{ route('inventory.store') }}" enctype="multipart/form-data" dusk="form-tambah">
      @csrf
      <div class="mb-2">
        <input type="text" name="nama_barang" dusk="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" 
        placeholder="Nama Spare Part" value="{{ old('nama_barang') }}" required>
        @error('nama_barang')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Field stok -->
      <div class="mb-2">
        <input type="text" name="stok" dusk="stok" class="form-control @error('stok') is-invalid @enderror"
               placeholder="Stok awal" value="{{ old('stok') }}" required>
        @error('stok')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      
      <div class="mb-2">
        <select name="kategori" dusk="kategori" class="form-select" required>
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
        <input type="file" name="gambar" dusk="gambar" class="form-control" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary" dusk="tombol-tambah">Tambah</button>
    </form>
  </div>

  <div class="col-md-7">
    <h4>Daftar Barang</h4>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Stok</th>
          <th>Kategori</th>
          <th>Gambar</th>
          <th>Barang</th>
       </tr>
      </thead>
      <tbody>
        @foreach($items as $i)
        <tr dusk="barang-{{ $i->id }}">
          <td>{{ $i->nama_barang }}</td>

          <td>
            @if($i->stok == 0)
              <span class="text-danger fw-bold">Stok Habis</span>
            @elseif($i->stok < 10)
              <span class="text-warning fw-bold">Stok Menipis ({{ $i->stok }})</span>
            @else
              <span class="text-success fw-bold">Stok Aman ({{ $i->stok }})</span>
            @endif
          </td>

          <td>
            @if($i->kategori == 'OEM')
              <span class="badge bg-success">Original (OEM)</span>
            @elseif($i->kategori == 'KW')
              <span class="badge bg-danger">Imitasi (KW)</span>
            @else
              <span class="badge bg-secondary">{{ $i->kategori }}</span>
            @endif
          </td>

          <td>
            @if($i->gambar)
              <img src="{{ asset('storage/'.$i->gambar) }}" alt="gambar {{ $i->nama_barang }}" 
                   style="max-width: 100px; max-height: 100px; object-fit: contain;">
            @elseif($i->kategori == 'OEM')
              <span class="text-info">Belum ada gambar (Barang Original)</span>
            @else
              <span class="text-muted">Tidak ada gambar</span>
            @endif
          </td>

          <td>
            <form method="POST" action="{{ route('inventory.update', $i->id) }}" class="d-inline" dusk="form-edit-{{ $i->id }}">
              @csrf
              @method('PUT')

              <div class="mb-1">
                <input type="text" name="nama_barang" value="{{ $i->nama_barang }}" 
                       class="form-control form-control-sm" dusk="edit-nama-{{ $i->id }}" required>
              </div>

              <div class="mb-1">
                <select name="kategori" class="form-select form-select-sm" dusk="edit-kategori-{{ $i->id }}" required>
                  <option value="">-- Pilih Kategori --</option>
                  <option value="Mekanis" {{ $i->kategori == 'Mekanis' ? 'selected' : '' }}>Mekanis</option>
                  <option value="Elektrikal" {{ $i->kategori == 'Elektrikal' ? 'selected' : '' }}>Elektrikal</option>
                  <option value="Aksesoris" {{ $i->kategori == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                  <option value="OEM" {{ $i->kategori == 'OEM' ? 'selected' : '' }}>OEM</option>
                  <option value="Aftermarket" {{ $i->kategori == 'Aftermarket' ? 'selected' : '' }}>Aftermarket</option>
                  <option value="KW" {{ $i->kategori == 'KW' ? 'selected' : '' }}>KW</option>
                </select>
              </div>

              <div class="input-group input-group-sm mb-1">
                <input type="number" name="stok" value="{{ $i->stok }}" 
                       class="form-control d-inline w-50" dusk="edit-stok-{{ $i->id }}">
                <!-- ✅ Diperbaiki: ganti "tombol-update" jadi "update-button" -->
                <button type="submit" class="btn btn-warning btn-sm" dusk="update-button-{{ $i->id }}">Update</button>
              </div>
            </form>

            <form action="{{ route('inventory.delete', $i->id) }}" method="POST" 
                  class="d-inline" onsubmit="return confirm('Hapus barang ini?')" dusk="form-hapus-{{ $i->id }}">
              @csrf
              @method('DELETE')
              <!-- ✅ Diperbaiki: ganti "tombol-hapus" jadi "delete-button" -->
              <button type="submit" class="btn btn-danger btn-sm" dusk="delete-button-{{ $i->id }}">Hapus</button>
            </form>
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