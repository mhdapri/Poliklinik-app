<x-layouts.app title="Data Pembayaran">

    

       <table class="table">
    <thead>
        <tr>
            <th>Pasien</th>
            <th>Total Tagihan</th>
            <th>Bukti Bayar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembayaran as $item)
        <tr>
            <td>{{ $item->daftarPoli->pasien->nama }}</td>
            <td>Rp {{ number_format($item->daftarPoli->periksa->biaya_periksa ?? 0, 0, ',', '.') }}</td>
            <td>
                <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">
                    Lihat Bukti
                </a>
            </td>
            <td>
                <form action="{{ route('admin.pembayaran.verify', $item->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="verified">
                    <button type="submit" class="btn btn-success btn-sm">Konfirmasi Lunas</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</x-layouts.app>