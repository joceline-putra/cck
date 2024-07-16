# cck
Cecekost.com

CECE
=====
	- Gambar Bukti Bayar wajib
	- Gambar KTP wajib
	- Gambar Plat wajib
- Diskon
- Biaya Parkir Mobil Rp 500.000
- Jumlah Mobil yg di bawa
- Nomor Plat Mobil

#Cece 2
    - Non AC (4 kamar)
#Cece 3
    - Besar 3jt
    - Sedang 1 2.2jt 
    - Sedang 2 1.950jt
    - Kecil 1.6jt
#Cece 6
    - Sedang 2
    - Sedang 3
    - Sedang 4
	
LILY
=====
- Gambar Bukti Bayar Opsional
- Gambar KTP Opsional
#Lily 1
    - Besar 
    - Sedang
#Lily 2
    - Besar
    - Sedang   

Upload File -> files -> function helper upload_file()
Upload Bukti Bayar -> order_paids -> function helper upload_file()

Fom Lily
Upload KTP -> files -> core/MY -> $this->file_upload_image()
Upload Bukti Bayar -> files -> core/MY -> $this->file_upload_image()

Form Cece
Upload KTP -> files -> core/MY -> $this->file_upload_image()
Upload Bukti Bayar -> files -> core/MY -> $this->file_upload_image()
Upload FORM  -> files -> core/MY -> $this->file_upload_image()


Tgl 2 Juli 2024
Reminder Cece yang akan habis
Auto renewal Cece bulanan 
Checkin sisa kunci
checkout kunci kamar


//Jika ada penambahan Cabang Baru
UPDATE accounts SET account_flag=4 WHERE account_group_sub='16' AND account_code NOT IN(
'6-60219',
'6-60217',
'6-60218',
'6-60101',
'6-60102',
'6-60103',
'6-60104',
'6-60202');