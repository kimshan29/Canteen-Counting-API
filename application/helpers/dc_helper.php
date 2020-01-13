<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function get_client_ip_server()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_bln($date)
{
    $bln = substr($date, 5, 2);
    $tgl = substr($date, 8, 2);
    $thn = substr($date, 0, 4);
    $monthNum = $bln;
    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
//  $date = $monthName . " " . $tgl . ", " . $thn;
	$date = $tgl . " " . substr($monthName, 0, 3) . " " . $thn;
    return $date;
}

function get_date($date)
{
    $yrdata = strtotime($date);
    return date('D, d M Y ', $yrdata);
}

function get_name_admin($id)
{
    $CI =& get_instance();
    $CI->load->database();
    $query = $CI->db->query("select * from " . $this->tbl_user . " where id='" . $id . "'")->row();
    $name = $query->username;
    return $name;
}

function idr($angka)
{
    $angka = "IDR. " . number_format($angka, 2, ',', '.');
    $duitnya = str_replace(",00", "", $angka);
    return $duitnya;

}

function persen($data1, $data2)
{
    $data = $data2 * 100 / $data1;
    return $data;
}

function get_days_left($day)
{
    $date1 = new DateTime(substr($day, 0, 10));
    $date2 = new DateTime(date('Y-m-d'));
    $diff = $date2->diff($date1)->format("%a");
    $days = intval($diff);
    return $days;
}

function url($val)
{
    $a = str_replace(' ', '-', $val);
    $b = str_replace(',', '', $a);
    $c = str_replace('.', '', $b);
    return $c;

}

function get_count($table)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    return $ci->db->get()->num_rows();
}

function select_all($table)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $data = $ci->db->get();
    return $data->result();
}

function select_all_where($table, $column, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
	$ci->db->where($column, $where);
    $data = $ci->db->get();
    return $data->result();
}

function select_where($table, $column, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->where($column, $where);
    $data = $ci->db->get();
    return $data;
}

function select_where_like($table, $column, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->like($column, $where);
    $data = $ci->db->get();
    return $data;
}

function select_where_like_array($table, $where_array, $column, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->where($where_array);
    $ci->db->like($column, $where);
    $data = $ci->db->get();
    return $data;
}


function select_where_order($table, $column, $where, $order_by, $order_type)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->where($column, $where);
    $ci->db->order_by($order_by, $order_type);
    $data = $ci->db->get();
    return $data;
}

function select_where_limit_order($table, $column, $where, $limit, $order_by, $order_type)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->where($column, $where);
    $ci->db->order_by($order_by, $order_type);
    $data = $ci->db->get($table, $limit);
    return $data;
}

function select_where_array_limit_order($table, $array, $limit, $order_by, $order_type)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->where($array);
    $ci->db->order_by($order_by, $order_type);
    $data = $ci->db->get($table, $limit);
    return $data;
}

function select_where_offset_limit_order($table, $column, $where, $offset, $limit, $order_by, $order_type)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->where($column, $where);
    $ci->db->order_by($order_by, $order_type);
    $ci->db->limit($offset, $limit);
    $data = $ci->db->get($table);
    return $data;
}

function select_where_array($table, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->where($where);
    $data = $ci->db->get();
	//echo $str = $ci->db->last_query(); die;
    return $data;
}

function select_where_array_order($table, $where, $order_by, $order_type)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->where($where);
    $ci->db->order_by($order_by, $order_type);
    $data = $ci->db->get();
    return $data;
}

function insert_all($table, $data)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    if (!$ci->db->insert($table, $data))
        return FALSE;
    $data["id"] = $ci->db->insert_id();
    return (object)$data;
}

function insert_all_batch($table, $data)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    if (!$ci->db->insert_batch($table, $data))
        return FALSE;
    $data["id"] = $ci->db->insert_id();
    return (object)$data;
}

function update($table, $data, $column, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->where($column, $where);
    return $ci->db->update($table, $data);
}

function update_one($table, $column, $where, $target, $action)
{
    $ci->db->set($target, $target . $action, FALSE);
    $ci->db->where($column, $where);
    return $ci->db->update($table);
}

function delete($table, $column, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->where($column, $where);
    return $ci->db->delete($table);
}

function delete_where_array($table, $where)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->where($where);
    return $ci->db->delete($table);
}

function select_all_limit($table, $limit)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $data = $ci->db->get($table, $limit);
    return $data;
}

function select_all_limit_order($table, $limit, $order_by, $order)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->order_by($order_by, $order);
    $data = $ci->db->get($table, $limit);
    return $data;
}

function select_all_order($table, $order_by, $order)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->order_by($order_by, $order);
    $data = $ci->db->get();
    return $data->result();
}

function get_paging($table, $limit, $from, $order, $type)
{
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->limit($limit, $from);
    $ci->db->order_by($order, $type);
    return $ci->db->get()->result();
}

function get_paging_where($table, $column, $where, $limit, $from, $order, $type)
{
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->limit($limit, $from);
    $ci->db->where($column, $where);
    $ci->db->order_by($order, $type);
    return $ci->db->get()->result();
}

function select_all_limit_random($table, $limit)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->order_by('id', 'RANDOM');
    $ci->db->limit($limit);
    $ci->db->from($table);
    $data = $ci->db->get();
    return $data->result();
}

function select_where_double_order($table, $column, $where, $column2, $where2, $order_by, $order_type)
{
    $ci =& get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select('*');
    $ci->db->from($table);
    $ci->db->where($column, $where);
    $ci->db->where($column2, $where2);
    $ci->db->order_by($order_by, $order_type);
    $data = $ci->db->get();
    return $data;
}

function debug_code($code)
{
    echo "<pre>";
    print_r($code);
    echo "</pre>";
    die();
}

function select_where_field_array($table, $field, $where)
{
    $ci = &get_instance();
    $ci->load->database('default', TRUE);
    $ci->db->select($field);
    $ci->db->from($table);
    $ci->db->where($where);
    $data = $ci->db->get();
    return $data;
}

function convert_image($data, $patch, $name)
{
    $base64ImageString = str_replace(' ', '+', $data);
    $file = $patch . '/' . $name;
    $decodedImage = base64_decode($base64ImageString);
    file_put_contents($file, $decodedImage);
    return $name;
}

function upload_image_server($data, $path, $name, $resize = false)
{
    $base64ImageString = str_replace(' ', '+', $data);

    $decoded = base64_decode($base64ImageString);

    $im = imagecreatefromstring($decoded);

    $source_width = imagesx($im);
    $source_height = imagesy($im);

    $image = imagecreatetruecolor($source_width, $source_height);

    imagecolortransparent($image, imagecolorallocatealpha($image, 0, 0, 0, 127));
    imagealphablending($image, false);
    imagesavealpha($image, true);

    imagecopyresampled($image, $im, 0, 0, 0, 0, $source_width, $source_height, $source_width, $source_height);
    imagepng($image, $path . '/' . $name, 9);

    if ($resize) {
        $imageSize = ratioImage($base64ImageString);
        foreach ($imageSize as $key => $value) {
            $thumb = imagecreatetruecolor($value['width'], $value['height']);

            imagecopyresampled($thumb, $im, 0, 0, 0, 0, $value['width'], $value['height'], $source_width, $source_height);
            imagepng($thumb, $path . '/' . $key . '/' . $name, 9);
        }
    }

    imagedestroy($im);
}

function ratioImage($imageUrl)
{
    $uri = 'data://application/octet-stream;base64,'. $imageUrl;
    $imageDimensions = getimagesize($uri);

    $width = $imageDimensions[0];
    $height = $imageDimensions[1];

    $imageSize['original'] = [
        'width' => $width,
        'height' => $height
    ];

    $maxWidthThumbn = 350;
    $widthThumbn = $width;
    $heightThumbn = $height;

    if($width > $maxWidthThumbn)
    {
        $heightThumbn = floor(($height / $width) * $maxWidthThumbn);
        $widthThumbn  = $maxWidthThumbn;
    }
    $imageSize['thumbnail'] = [
        'width' => $widthThumbn,
        'height' => $heightThumbn
    ];

    $maxWidthLarge = 500;
    $widthLarge = $width;
    $heightLarge = $height;

    if($width > $maxWidthLarge)
    {
        $heightLarge = floor(($height / $width) * $maxWidthLarge);
        $widthLarge  = $maxWidthLarge;
    }
    $imageSize['large'] = [
        'width' => $widthLarge,
        'height' => $heightLarge
    ];

    return $imageSize;
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulang',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' lalu' : ' saja';
}

function get_status_paket($waybill, $courier)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "waybill=" . $waybill . "&courier=" . $courier,
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: c0163d92a62e76b9714414d56221dbfc"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return json_decode($response);
    }
}

function change_status_bid_detail($status, $no_resi = NULL, $courier)
{
    switch ($status) {
        case "pending":
            return "Menunggu pembayaran";
        case "expire":
            return "Melebihi batas waktu pembayaran";
        case "payment_expired":
            return "Melebihi batas waktu pembayaran";
        case "payment_deny":
            return "Pembayaran ditolak";
        case "payment_waiting":
            return "Menunggu ACC pembayaran";
        case "paid":
            return "Menunggu respon pelelang";
        case "packing":
            return "Proses kurir";
        case "sending":
            $status_desc = get_status_paket($no_resi, $courier);
            if ($status_desc->rajaongkir->status->code == 200) {
                $manifest = $status_desc->rajaongkir->result->manifest;
                $txt = '';
                foreach ($manifest as $manifest) {
                    $txt = $manifest->manifest_description;
                }
                return $txt;
            } else {
                return "Resi tidak ditemukan";
            }
        case "received":
            return "Barang sudah diterima";
        case "payment_failure":
            return "Pembayaran gagal";
        case "not_send":
            return "Barang tidak dikirim";
        case "checkout":
            return "Menunggu checkout";
        case "cancel":
            return "Pembayaran dibatalkan";
    }
}

function change_status_bid($status, $no_resi = NULL, $courier= NULL)
{
    switch ($status) {
        case "pending":
            return "Menunggu pembayaran";
        case "expire":
            return "Melebihi batas waktu pembayaran";
        case "payment_expired":
            return "Melebihi batas waktu pembayaran";
        case "payment_deny":
            return "Pembayaran ditolak";
        case "payment_waiting":
            return "Menunggu ACC pembayaran";
        case "paid":
            return "Menunggu respon pelelang";
        case "packing":
            return "Proses kurir";
        case "sending":
    		$status_desc = get_status_paket($no_resi, $courier);
            if ($status_desc->rajaongkir->status->code == 200) {
//                $manifest = $status_desc->rajaongkir->result->manifest;
//                $txt = '';
//                foreach ($manifest as $manifest) {
//                    $txt = $manifest->manifest_description;
//                }
                return "Barang dalam perjalanan";
            } else {
                return "Resi tidak ditemukan";
            }
        case "received":
            return "Barang sudah diterima";
        case "payment_failure":
            return "Pembayaran gagal";
        case "not_send":
            return "Barang tidak dikirim";
        case "checkout":
            return "Menunggu checkout";
        case "cancel":
            return "Pembayaran dibatalkan";
    }
}

function change_status_pelelang($status, $no_resi = NULL, $courier= NULL)
{
    switch ($status) {
        case "pending":
            return "Menunggu pembayaran";
        case "expire":
            return "Melebihi batas waktu pembayaran";
        case "payment_expired":
            return "Melebihi batas waktu pembayaran";
        case "payment_deny":
            return "Pembayaran ditolak";
        case "payment_waiting":
            return "Menunggu ACC pembayaran";
        case "paid":
            return "Perlu dikirim";
        case "packing":
            return "Proses kurir";
        case "sending":
    		$status_desc = get_status_paket($no_resi, $courier);
            if ($status_desc->rajaongkir->status->code == 200) {
//                $manifest = $status_desc->rajaongkir->result->manifest;
//                $txt = '';
//                foreach ($manifest as $manifest) {
//                    $txt = $manifest->manifest_description;
//                }
                return "Barang dalam perjalanan";
            } else {
                return "Resi tidak ditemukan";
            }
        case "received":
            return "Barang sudah diterima";
        case "payment_failure":
            return "Pembayaran gagal";
        case "not_send":
            return "Barang tidak dikirim";
        case "checkout":
            return "Menunggu checkout";
        case "cancel":
            return "Pembayaran dibatalkan";
    }
}

function change_status_pelelang_detail($status, $no_resi = NULL, $courier)
{
    switch ($status) {
        case "pending":
            return "Menunggu pembayaran";
        case "expire":
            return "Melebihi batas waktu pembayaran";
        case "payment_expired":
            return "Melebihi batas waktu pembayaran";
        case "payment_deny":
            return "Pembayaran ditolak";
        case "payment_waiting":
            return "Menunggu ACC pembayaran";
        case "paid":
            return "Perlu dikirim";
        case "packing":
            return "Proses kurir";
        case "sending":
            $status_desc = get_status_paket($no_resi, $courier);
            if ($status_desc->rajaongkir->status->code == 200) {
                $manifest = $status_desc->rajaongkir->result->manifest;
                $txt = '';
                foreach ($manifest as $manifest) {
                    $txt = $manifest->manifest_description;
                }
                return $txt;
            } else {
                return "Resi tidak ditemukan";
            }
        case "received":
            return "Barang sudah diterima";
        case "payment_failure":
            return "Pembayaran gagal";
        case "not_send":
            return "Barang tidak dikirim";
        case "checkout":
            return "Menunggu checkout";
        case "cancel":
            return "Pembayaran dibatalkan";
    }
}

function get_favorite($id_member, $id_product)
{
    $favorite = select_where_array('dc_favorite_product', array('id_product' => $id_product, 'id_member' => $id_member))->num_rows();
    if ($favorite > 0) {
        return 1;
    } else {
        return 0;
    }
}

function add_notif($type, $txt, $id_member, $id_bid, $id_product, $id_transaction=null, $no_invoice=null)
{
	$id_transaction = isset($id_transaction) ? $id_transaction : '0';
	$no_invoice = isset($no_invoice) ? $no_invoice : '0';
	
    $insert = insert_all('dc_notif', array(
        'type_notif' => $type,
        'text' => $txt,
        'id_member' => $id_member,
        'id_bid' => $id_bid,
        'id_product' => $id_product,
    	'id_transaction' => $id_transaction,
    	'no_invoice' => $no_invoice,
        'date_created' => date('Y-m-d H:i:s')
    ));
    return $insert->id;
}

function ngepush_notif($token, $data)
{
    $url = "https://fcm.googleapis.com/fcm/send";
    $serverKey = 'AAAA31pasdU:APA91bGNL7gKQDf3ZFGoYSQHoM2VoGTKtL3fciMHwFUUDRpy3ZUzqT-YFBGCMXQPvUQSXJaCzln629-cmR5wkstkarivfftPjv0wSCesTIAMl8hnDdFGZMwsb-8UZas93p-46ypIEl3H';

    $arrayToSend = array('to' => $token, 'data' => $data, 'priority' => 'high');
    $json = json_encode($arrayToSend);
    //print_r($json);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key=' . $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //Send the request
    $response = curl_exec($ch);
//    var_dump($response); die;
    //Close request
    if ($response === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
}

function send_mail($email, $title, $message)
{
    $ci =& get_instance();
    /*$config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => '587',
        'smtp_user' => 'noreply.ngebid@gmail.com',
        'smtp_pass' => "Ngebid123#",
        'mailtype' => 'html',
        'charset' => 'utf-8'
    );*/
    $config = array(
        'protocol' => 'smtp',
        'smtp_host' => 'mail.smtp2go.com',
        'smtp_port' => '2525',
        'smtp_user' => 'noreply@ngebid.com',
        'smtp_pass' => "cHUxYWFzaW9xcGsw",
        'mailtype' => 'html',
        'charset' => 'utf-8'
    );
    $ci->load->library('email');
    $ci->email->initialize($config);
    $ci->email->set_mailtype("html");
    $ci->email->set_newline("\r\n");
    $ci->email->from('noreply@ngebid.com', 'Ngebid');
    $ci->email->to($email);
    $ci->email->subject($title);
    $ci->email->message($message);
    $ci->email->send();

    //   //noreply server
    //   $ci=& get_instance();
    //   $config = array(
    //       'protocol'  => 'smtp',
    //       'smtp_host' => 'smtp.ngebid.com',
    //       'smtp_port' =>  587,
    //       'smtp_user' => 'noreply@ngebid.com',
    //       'smtp_pass' => "YlBB}?5EvmKV",
    //       'mailtype'  => 'html',
    // 	  'charset'   => 'utf-8',
    //   );
    //   $ci->load->library('email');
    //   $ci->email->initialize($config);
    //   $ci->email->set_mailtype("html");
    //   $ci->email->set_newline("\r\n");
    //   $ci->email->from('noreply@ngebid.com', 'Ngebid');
    //   $ci->email->to($email);
    //   $ci->email->subject($title);
    //   $ci->email->message($message);
    //   $ci->email->send();
    //echo $ci->email->print_debugger();
}

function send_sms($code, $phone)
{
    //   $curl = curl_init();
    //   curl_setopt_array($curl, array(
    //       CURLOPT_RETURNTRANSFER => 1,
    //       CURLOPT_URL => 'http://api.nusasms.com/api/v3/sendsms/plain',
    //       CURLOPT_POST => true,
    //       CURLOPT_POSTFIELDS => array(
    //           'user' => 'ngebidku_api',
    //           'password' => 'e8aQ8sd',
    //           'SMSText' => "<#> Ko'de_OTP an'da ad,alah ".$code ." KqjBxz%2B5ur2",
    //           'GSM' => $phone
    //       )
    //   ));
    //   $resp = curl_exec($curl);
    //   if (!$resp) {
    //       die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    //   } else {
    //       // header('Content-type: text/xml'); if you want to output to be an xml
    //       // echo $resp;
    //   }
    //   curl_close($curl);
    //wavecell
    // $data = array(
    //   "destination" => $phone,
    //   "country" => "ID",
    //   "productname" => "Ngebid",
    //   "codeLength" => "6",
    //   "codeValidity" => 180,
    // "clientMessageId"=> "Ngebid",
    //     "source" => "Ngebid",
    //     "text" => "<#> Your verification code is ".$code.". It will remain valid for 3 minutes. Thank you \nKqjBxz+B5ur2",
    //     "encoding" =>"AUTO",
    //   "createNew" => true,
    //   "resendingInterval" => 15,
    // );
    // $data_string = json_encode($data);
    // $ch = curl_init('https://api.wavecell.com/sms/v1/ngebid_1qQ46_hq/single');
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //     'Content-Type: application/json',
    //     'Authorization: Bearer 2imgcKnKSnrb0drKIS4vzoLnJlKPGL3TNYobtZbg7A'
    //   )
    // );
    // $response = curl_exec($ch);

    // "{ \"from\":\"Ngebid\", \"to\":[ \"+" . $phone . "\"], \"text\":\"<#> Ngebid - Halo, Kode otentikasi kamu " . $code . ".  Ingat! kode ini bersifat rahasia. Demi keamanan, jangan berikan kode ini kepada siapa pun termasuk pihak Ngebid. RaV3dFP1PGr\" }"

    $phone = '+' . $phone;
    $send = [
        'from' => 'Ngebid',
        'to' => [$phone],
        'text' => '<#> Ngebid - Halo, Kode otentikasi kamu '. $code .'.  Ingat! kode ini bersifat rahasia. Demi keamanan, jangan berikan kode ini kepada siapa pun termasuk pihak Ngebid.'
    ];

    $username = 'Ngebid';
    $password = 'NgeBid2019*#';
    $base = base64_encode($username . ":" . $password);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://q52xm.api.infobip.com/sms/2/text/single",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($send),
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: Basic " . $base,
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }


}

function info_form($formKosong = false)
{
    if($formKosong) {
        return 'Data ini boleh kosong';
    } else {
        return 'Data ini tidak boleh dikosongkan';
    }
}

function get_omzet_now()
{
    $ci =& get_instance();

    $query = $ci->super_model->get_table_all($ci->view_omzet_now);
    $row = $query->row();

    if($query->num_rows() > 0) {
        $omzet = $row->price_total;
    } else {
        $omzet = 0;
    }

    return $omzet;
}

function sessions()
{
    $ci =& get_instance();

    $data = array(
        'id_user' => $ci->session->userdata['admin']['id'],
        'username' => $ci->session->userdata['admin']['username'],
        'email' => $ci->session->userdata['admin']['email'],
        'first_name' => $ci->session->userdata['admin']['first_name'],
        'last_name' => $ci->session->userdata['admin']['last_name'],
        'photo' => $ci->session->userdata['admin']['photo'],
        'user_group' => $ci->session->userdata['admin']['user_group']
    );

    return $data;
}

function active_menu($id_menu)
{
    $ci =& get_instance();

    $target = $ci->uri->segment(2);
    $id = $ci->super_model->get_type_name_by_id($ci->tbl_menu, 'target', $target, 'sub_menu');

    if($id == $id_menu) return true;

    return false;
}

function recaptcha_response()
{
    $ci =& get_instance();

    $recaptchaResponse = trim($ci->input->post('g-recaptcha-response'));

    $userIp = $ci->input->ip_address();

    $secret = $ci->config->item('recaptcha_secret_key');

    $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    $status = json_decode($output, true);

    return $status;
}

function acak_angka_huruf($panjang, $tipe_karakter = array())
{
    $angka = '123456789';
    $hurufkecil = 'abcdefghjklmnpqrstuvwxyz';
    $hurufbesar = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    $warna = '0123456789ABCDEF';

    $string = '';
    $karakter = '';
    if(empty($tipe_karakter)) {
        $karakter = $hurufkecil.$hurufbesar.$angka;
    } else {
        if(in_array('angka', $tipe_karakter)) {
            $karakter .= $angka;
        }
        if(in_array('hurufkecil', $tipe_karakter)) {
            $karakter .= $hurufkecil;
        }
        if(in_array('hurufbesar', $tipe_karakter)) {
            $karakter .= $hurufbesar;
        }
        if(in_array('warna', $tipe_karakter)) {
            $karakter .= $warna;
            $string = '#';
        }
    }

    for ($i = 0; $i < $panjang; $i++)
    {
        $pos = rand(0, strlen($karakter)-1);
        $string .= $karakter{$pos};
    }
    return $string;
}

function get_params($kecuali = array())
{
    $url = parse_url(base_url($_SERVER['REQUEST_URI']));
    if(isset($url['query'])) {
        if(empty($kecuali)) {
            $getParam = '?'.$url['query'];
        } else {
            $q = parse_query($_SERVER['REQUEST_URI'], $kecuali);
            if(!empty($q)) {
                foreach ($q as $r => $v) {
                    $uri[] = $r.'='.$v;
                }
                $getParam = '?'.implode('&', $uri);
            } else {
                $getParam = '';
            }
        }
    } else {
        $getParam = '';
    }

    return $getParam;
}

function parse_query($var, $kecuali = array())
{
    /**
     *  Use this function to parse out the query array element from
     *  the output of parse_url().
     */
    $var  = parse_url($var, PHP_URL_QUERY);
    $var  = html_entity_decode($var);
    $var  = explode('&', $var);
    $arr  = array();

    foreach($var as $val)
    {
        $x = explode('=', $val);

        if(!in_array($x[0], $kecuali)) {
            $arr[$x[0]] = $x[1];
        }

    }
    unset($val, $x, $var);
    return $arr;
}

function do_url()
{
	$ci =& get_instance();
	$url = $ci->config->item('do_url');
	
    //return 'https://nyc3.digitaloceanspaces.com/space-ngebid/';
    return $url;
}

function type_coin($type)
{
    if($type == '1') {
        return 'Coin Login';
    } else {
        return 'Coin Pelelang / Pemenang Bidder';
    }
}

function datediff($tgl1, $tgl2)
{
    $tgl1 = strtotime($tgl1);
    $tgl2 = strtotime($tgl2);
    $diff_secs = abs($tgl1 - $tgl2);
    $base_year = min(date("Y", $tgl1), date("Y", $tgl2));
    $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
    return array(
        "years"         => date("Y", $diff) - $base_year,
        "months_total"  => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,
        "months"        => date("n", $diff) - 1,
        "days_total"    => floor($diff_secs / (3600 * 24)),
        "days"          => date("j", $diff) - 1,
        "hours_total"   => floor($diff_secs / 3600),
        "hours"         => date("G", $diff),
        "minutes_total" => floor($diff_secs / 60),
        "minutes"       => (int) date("i", $diff),
        "seconds_total" => $diff_secs,
        "seconds"       => (int) date("s", $diff)
    );
}

function get_selisih_waktu($w, $h, $j, $m, $d)
{
    if($h >= 7) {
        $data = $w;
    } elseif($h >= 1) {
        $data = $h.' hari lalu';
    } elseif($j >= 1) {
        $data = $j.' jam lalu';
    } elseif($m >= 6) {
        $data = 'Beberapa menit lalu';
    } elseif($m >= 1 && $m <= 5) {
        $data = $m . ' menit lalu';
    } elseif($d >= 15) {
        $data = 'Beberapa detik lalu';
    } else {
        $data = $d.' detik lalu';
    }
    return $data;
}

function file_exist_url($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}

function get_intruction($type, $intruction = false)
{
    $data = select_where_like('dc_intruction', 'id_type_intruction', $type)->result();

    $result = [];
    foreach ($data as $key => $value) {
        $intruction = str_replace($type . '_', '', $value->id_type_intruction);

        if (isset($result[$intruction])) {
            $value->title = get_title_intruction($intruction);
            $result[$intruction][] = $value;
        }
        else {
            $value->title = get_title_intruction($intruction);
            $result[$intruction][] = $value;
        }
    }

    return $result;
}

function get_title_intruction($intruction)
{
    switch ($intruction) {
        case 'atm':
            $title = 'ATM';
            break;

        case 'mobile':
            $title = 'Mobile Banking';
            break;

        case 'ibanking':
            $title = 'Internet Banking';
            break;

        case 'kantor':
            $title = 'Kantor Bank';
            break;

        case 'other':
            $title = 'ATM Bank Lain';
            break;

        case 'outlet':
            $title = 'Kantor Cabang atau Outlet';
            break;

        case 'sms':
            $title = 'SMS Banking';
            break;

        case 'atmbersama':
            $title = 'ATM Bersama';
            break;

        case 'clicks':
            $title = 'Clicks';
            break;

        case 'ibankingother':
            $title = 'Internet Banking Bank Lain';
            break;

        case 'echannel':
            $title = 'Echannel';
            break;

        default:
            $title = 'ATM';
            break;
    }

    return $title;
}