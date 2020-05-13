<style type="text/css">
  body {
    font-family: Arial, sans-serif;
  }

  table {
    width: 100%;
    font-size: 8pt;
    border-collapse: collapse;
  }

  table, table th, table td {
    border: 1px solid black;
    padding: 5px;
  }
</style>

<table>
  <thead>
  <tr>
    <th style="padding: 30px">
      <h1 style="font-size: 14pt; margin: 0;">Bukti Pendaftaran COVID-19 Test</h1>
      <h2 style="font-size: 12pt; margin: 0; font-weight: normal">Pusat Informasi dan Koordinasi Jawa Barat (PIKOBAR)</h2>
    </th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td align="center" style="padding: 30px">
      <p><img src="data:image/png;base64, {{ base64_encode(QrCode::errorCorrection('H')->format('png')->merge('/storage/pikobar.png')->margin(0)->size(320)->generate($applicant->registration_code)) }}"></p>
      <h1 style="font-size: 48pt; margin: 0">{{ $applicant->registration_code }}</h1>
    </td>
  </tr>
  <tr>
    <td style="padding: 30px">
      <h3 style="margin: 5px 0; font-size: 14px">Keterangan</h3>
      <p style="margin: 0; font-size: 14px">Cek status pendaftaran Anda pada website <strong>https://rapidtest.pikobar.jabarprov.go.id</strong> <br /><br />
        Hanya yang sudah diverifikasi oleh Dinas Kesehatan Provinsi atau Dinas Kesehatan Kabupaten/Kota akan mendapatkan undangan untuk mengikuti Rapid Test.</p>
    </td>
  </tr>
  </tbody>
</table>
