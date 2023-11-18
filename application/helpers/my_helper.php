<?php defined('BASEPATH') or exit('No direct script access allowed');

    function joe_helper(){
        return 'Hai Joe, this is helper';
    }
    function dot_set_line($char, $len) {
        // $lines=array();
        // foreach (explode("\n",wordwrap($kolom1,$len=32)) as $line)
        //     $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        // return implode("\n",$lines)."\n";
        $ret = '';
        for($a=0; $a<$len; $a++){
            $ret .= $char;
        }
        return $ret."\n";
    }
    function dot_set_wrap_0($len,$kolom1,$separator,$padding) {
        if($padding=='BOTH'){ $set_padding = STR_PAD_BOTH ;
        }else if($padding=='LEFT'){ $set_padding = STR_PAD_LEFT;
        }else if($padding=='RIGHT'){ $set_padding = STR_PAD_RIGHT;
        }
        $lines=array();
        foreach (explode("\n",wordwrap($kolom1,$len)) as $line)
            $lines[]=str_pad($line,$len,$separator,$set_padding);
        return implode("\n",$lines)."\n";
    }
    function dot_set_wrap_1($len,$kolom1) {
        $lines=array();
        foreach (explode("\n",wordwrap($kolom1,$len)) as $line)
            $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        return implode("\n",$lines)."\n";
    }
    function dot_set_wrap_2($len,$kolom1, $kolom2) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = $len/2;
        $lebar_kolom_2 = $len/2;
        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array));
        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();
        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {
            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);
            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            // $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            // $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);
            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2;
        }
        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    function dot_set_wrap_3($len,$kolom1, $kolom2, $kolom3) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 14;
        $lebar_kolom_2 = 1;
        $lebar_kolom_3 = 12;
        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);
        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));
        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();
        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {
            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");
            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
        }
        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    function dot_set_wrap_4($len,$kolom1, $kolom2, $kolom3, $kolom4) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 12;
        $lebar_kolom_2 = 8;
        $lebar_kolom_3 = 8;
        $lebar_kolom_4 = 9;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
        $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);
        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);
        $kolom4Array = explode("\n", $kolom4);
        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));
        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();
        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {
            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");
            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);
            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
        }
        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }    
    function upload_file($path = "", $file = "") {
        // if(!empty($file) and ($file !== 'undefined')){
            $image_height = 250;
            $image_width = 250;

            // $return          = new \stdClass();
            // $return->status  = 0;
            // $return->message = '';
            // $return->result  = '';

            $ci = &get_instance();
            // $path = (substr($path, -1) != "/" ? $path . "/" : $path); // konfig directory data
                            
            // $path = FCPATH . $this->folder_upload;                       
            $file_config = array(
                'upload_path' => $path,
                'allowed_types' => '*'
            ); 
            $ci->load->library('upload', $file_config);
            $ci->upload->initialize($file_config);

            if ($ci->upload->do_upload('source')) {
                $upload = $ci->upload->data();
                $raw_file = date("YmdHis") . $upload['file_ext']; //1231232.png
                // $old_name = $upload['full_path']; // abc/uoload/ABC.png
                // $new_name = $path . $raw_photo; // abc/upload/1231232.png

                if (rename($upload['full_path'], $path . $raw_file)) {
                    
                    if($upload['is_image'] == 1){ //If Data IMAGE
                        $file_compress = [
                            'image_library' => 'gd2',
                            'source_image' => $path . $raw_file,
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            // 'width' => $this->image_width,
                            // 'height' => $this->image_height,
                            'new_image' => $path . $raw_file
                        ];                                    
                        $ci->load->library('image_lib', $file_compress);
                        $ci->image_lib->resize();
                    }else{

                    }
                }
                $return['status'] = 1;
                $return['message'] = 'Success'; 
                $return['result'] = $upload;      
                $return['file'] = $raw_file;     
            }else{
                $return['status'] = 0;
                $return['message'] = $this->upload->display_errors();
            }
        // }else{
        //     $return['status'] = 0;
        //     $return['message'] = 'File not ready';
        // }
        return $return;
    }        
    //Backup
    function upload_image2($path = "", $file = "", $image_width = 250, $image_height = 250) {
        $ci = &get_instance();
        $path = (substr($path, -1) != "/" ? $path . "/" : $path); // konfig directory data
        $path_data = FCPATH . $path;
        $type_data = "gif|jpg|png|jpeg";
    
        if (empty($path)) {
            return "";
        }
        if (empty($file)) {
            return "";
        }
    
        $config = [
            'image_library' => 'gd2',
            'upload_path' => $path_data,
            'allowed_types' => $type_data
        ];
        $ci->load->library('upload', $config);
        $ci->upload->initialize($config);
    
        if ($ci->upload->do_upload($file)) {
            $file_data = $ci->upload->data();
            $raw_photo = date("YmdHis") . $file_data['file_ext'];
            $old_name = $file_data['full_path'];
            $new_name = $path_data . $raw_photo;
    
            // renaming file
            if (rename($old_name, $new_name)) {
                $compress = [
                    'image_library' => 'gd2',
                    'source_image' => $path . $raw_photo,
                    'create_thumb' => FALSE,
                    'maintain_ratio' => TRUE,
                    'width' => $image_width,
                    'height' => $image_height,
                    'new_image' => $path . $raw_photo
                ];
                $ci->load->library('image_lib', $compress);
    
                // resize ukuran image
                if ($ci->image_lib->resize()) {
                    // return $path . $raw_photo;
                    return $raw_photo;				
                } else {
                    return "";
                }
            } else {
                return "";
            }
        } else {
            return "";
        }
    }    
?>