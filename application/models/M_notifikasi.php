<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_notifikasi extends CI_Model {

    // Kirim Notifikasi (Insert)
    public function send($user_id, $tipe, $judul, $pesan, $link = '#') {
        $data = [
            'user_id' => $user_id,
            'tipe'    => $tipe,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'link'    => $link,
            'is_read' => 0
        ];
        return $this->db->insert('notifikasi', $data);
    }

    // Ambil Notifikasi User (Untuk Tampilan Lonceng)
    public function get_by_user($user_id, $limit = 10) {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('notifikasi')->result();
    }

    // Hitung yang belum dibaca (Untuk Badge Merah)
    public function count_unread($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('notifikasi');
    }
    
    // Tandai sudah dibaca
    public function mark_read($id) {
        $this->db->where('id', $id);
        return $this->db->update('notifikasi', ['is_read' => 1]);
    }
}