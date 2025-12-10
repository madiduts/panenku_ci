<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_validasi extends CI_Model {

    // Hitung Pending (Universal)
    public function count_pending($table) {
        $this->db->where('status_validasi', 'Pending');
        return $this->db->count_all_results($table);
    }

    // 1. GET LAHAN (Sesuai SQL kamu: lahan JOIN users)
    public function get_lahan($status = 'Pending') {
        $this->db->select('
            lahan.lahan_id AS id,
            lahan.luas_lahan,
            lahan.lokasi_desa,
            lahan.kategori_lahan,
            lahan.status_validasi,
            
            -- COALESCE mencegah error jika data user kosong/null
            COALESCE(users.full_name, "Petani Tidak Dikenal") AS nama_petani,
            COALESCE(users.phone_number, "-") AS kontak_petani
        ');
        $this->db->from('lahan');
        // Left Join Penting!
        $this->db->join('users', 'users.user_id = lahan.user_id', 'left');
        
        if($status == 'Pending') {
            $this->db->where('lahan.status_validasi', 'Pending');
            $this->db->order_by('lahan.lahan_id', 'DESC'); 
        } else {
            $this->db->where_in('lahan.status_validasi', ['Valid', 'Reject']);
            $this->db->order_by('lahan.tgl_validasi', 'DESC');
        }
        return $this->db->get()->result();
    }
    
    // 2. GET PANEN (Existing)
    public function get_panen($status = 'Pending') {
        $this->db->select('
            hasil_panen.panen_id AS id,
            hasil_panen.tanggal_realisasi,
            hasil_panen.jumlah_ton,
            hasil_panen.status_validasi,
            COALESCE(users.full_name, "Petani Tidak Dikenal") AS nama_petani,
            COALESCE(lahan.lokasi_desa, "-") AS lokasi_desa,
            COALESCE(ref_komoditas.nama_komoditas, "-") AS nama_komoditas
        ');
        $this->db->from('hasil_panen');
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = hasil_panen.siklus_id', 'left');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id', 'left');
        $this->db->join('users', 'users.user_id = lahan.user_id', 'left');
        $this->db->join('ref_komoditas', 'ref_komoditas.komoditas_id = siklus_tanam.komoditas_id', 'left');
        
        if($status == 'Pending') {
            $this->db->where('hasil_panen.status_validasi', 'Pending');
            $this->db->order_by('hasil_panen.tanggal_realisasi', 'ASC');
        } else {
            $this->db->where_in('hasil_panen.status_validasi', ['Valid', 'Reject']);
            $this->db->order_by('hasil_panen.tgl_validasi', 'DESC');
        }
        return $this->db->get()->result();
    }

    // 3. GET HAMA (Existing)
    public function get_hama($status = 'Pending') {
        $this->db->select('
            laporan_hama.laporan_id AS id,
            laporan_hama.tanggal_lapor,
            laporan_hama.tingkat_keparahan,
            laporan_hama.foto_bukti,
            laporan_hama.status_validasi,
            COALESCE(ref_jenis_hama.nama_hama, "Hama Tidak Dikenal") AS nama_hama,
            COALESCE(users.full_name, "Petani Tidak Dikenal") AS nama_petani, 
            COALESCE(lahan.lokasi_desa, "Lokasi Tidak Ada") AS lokasi_desa
        ');
        $this->db->from('laporan_hama');
        $this->db->join('ref_jenis_hama', 'ref_jenis_hama.hama_id = laporan_hama.hama_id', 'left');
        $this->db->join('siklus_tanam', 'siklus_tanam.siklus_id = laporan_hama.siklus_id', 'left');
        $this->db->join('lahan', 'lahan.lahan_id = siklus_tanam.lahan_id', 'left');
        $this->db->join('users', 'users.user_id = lahan.user_id', 'left');

        if($status == 'Pending') {
            $this->db->where('laporan_hama.status_validasi', 'Pending');
        } else {
            $this->db->where_in('laporan_hama.status_validasi', ['Valid', 'Reject']);
            $this->db->order_by('laporan_hama.tgl_validasi', 'DESC');
        }
        return $this->db->get()->result();
    }

    // UPDATE STATUS (Universal)
    public function update_status($table, $pk_col, $id, $data) {
        $this->db->where($pk_col, $id);
        return $this->db->update($table, $data);
    }
}