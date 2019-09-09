{{-- <table border="1">
    <tr>
        <th></th>
        @foreach ($kriterias['normalisasi'] as $key => $k)
            <th>{{ $key }}</th>
        @endforeach
        <th>Rerata</th>
        <th>Bobot</th>
    </tr>
    @foreach ($kriterias['normalisasi'] as $key => $kriteria)
        <tr>
            <th>{{ $key }}</th>
            @foreach ($kriteria as $item)
                <td>{{ $item }}</td>
            @endforeach
            <th>{{ $kriterias['rerata'][$key] }}</th>
            <th>{{ $kriterias['bobot'][$key] }}</th>
        </tr>
    @endforeach
</table>
@foreach ($alternativeValues as $key => $alternative)
    @php $users = array_keys($alternative); @endphp
    {{ $key }}
    <table border="1">
        <tr>
            <th></th>
            @foreach ($users as $user)
                <th>{{ $user }}</th>
            @endforeach
        </tr>
        @php $i = 0;@endphp
        @foreach ($alternative as $index => $row)
            <tr>
                <th>{{ $users[$i] }}</th>
                @foreach ($row as $col)
                    <td>{{ $col }}</td>
                @endforeach
            </tr>
            @php $i += 1; @endphp
        @endforeach
    </table>
@endforeach --}}


<table border="1">
    <tr>
        <th></th>
        @foreach ($kriterias['normalisasi'] as $key => $k)
            <th>{{ $key }}</th>
        @endforeach
        <th>Rerata</th>
        <th>Bobot</th>
    </tr>
    @foreach ($kriterias['normalisasi'] as $key => $kriteria)
        <tr>
            <th>{{ $key }}</th>
            @foreach ($kriteria as $item)
                <td>{{ $item }}</td>
            @endforeach
            <th>{{ $kriterias['rerata'][$key] }}</th>
            <th>{{ $kriterias['bobot'][$key] }}</th>
        </tr>
    @endforeach
</table>
@foreach ($alternativeValues as $key => $alternative)
    @php $users = array_keys($alternative['normalisasi']->toArray()); @endphp
    {{ $key }}
    <table border="1">
        <tr>
            <th></th>
            @foreach ($users as $user)
                <th>{{ $user }}</th>
            @endforeach
            <th>Rerata</th>
            <th>Bobot</th>
        </tr>
        @php $i = 0;@endphp
        @foreach ($alternative['normalisasi'] as $index => $row)
            <tr>
                <th>{{ $users[$i] }}</th>
                @foreach ($row as $col)
                    <td>{{ $col }}</td>
                @endforeach
                <th>{{ $alternative['rerata'][$index] }}</th>
                <th>{{ $alternative['bobot'][$index] }}</th>
            </tr>
            @php $i += 1; @endphp
        @endforeach
    </table>
@endforeach
{{ $pemenang }}