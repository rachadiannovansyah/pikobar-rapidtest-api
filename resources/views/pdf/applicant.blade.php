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
      <h1 style="font-size: 14pt; margin: 0;">Bukti Pendaftaran Tes Masif COVID-19</h1>
      <h2 style="font-size: 12pt; margin: 0; font-weight: normal">Pusat Informasi dan Koordinasi Jawa Barat (PIKOBAR)</h2>
    </th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td align="center" style="padding: 30px">
      <h1 style="font-size: 14pt; margin: 0; margin-top: 15px">Nama Peserta:</h1>
      <h1 style="font-size: 24pt; margin: 0; font-weight: normal">{{ $applicant->name }}</h1>
      <p><img src="data:image/png;base64, {{ base64_encode(QrCode::errorCorrection('H')->format('png')->merge('/storage/pikobar.png')->margin(0)->size(600)->generate($applicant->registration_code)) }}"></p>
      <h1 style="font-size: 14pt; margin: 0">Nomor Pendaftaran:</h1>
      <h1 style="font-size: 48pt; margin: 0">{{ $applicant->registration_code }}</h1>
    </td>
  </tr>
  <tr>
    <td style="padding: 30px">
      <h3 style="margin: 5px 0; font-size: 14px">Bukti Pendaftaran Ini Bukan Undangan</h3>
      <p style="margin: 0; font-size: 14px">Cek status pendaftaran Anda pada website <strong>https://tesmasif.pikobar.jabarprov.go.id</strong> <br /><br />
        Hanya yang sudah diverifikasi oleh Dinas Kesehatan Provinsi atau Dinas Kesehatan Kabupaten/Kota akan mendapatkan undangan untuk mengikuti tes masif COVID-19. Undangan akan dikirimkan melalui SMS, Whatsapp, dan Email yang digunakan untuk mendaftar.</p>
    </td>
  </tr>
  </tbody>
</table>
