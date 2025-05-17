<table>
    <thead>
        <tr>
            <th
                style="width: 30px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                No</th>
            <th
                style="width: 700px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                Pertanyaan</th>
            <th
                style="width: 100px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                Jawaban Benar</th>
            <th
                style="width: 70px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                Level</th>
            <th
                style="width: 300px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                opsi_a</th>
            <th
                style="width: 300px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                opsi_b</th>
            <th
                style="width: 300px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                opsi_c</th>
            <th
                style="width: 300px; height: 30px; font-size: medium; text-align: center; color: #FFFFFF; background-color: #158ef1;">
                opsi_d</th>
        </tr>
    </thead>
    <tbody>
        @for ($i=0; $i < 60; $i++) <tr>
            <td>{{ $i+1 }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            @endfor

    </tbody>
</table>