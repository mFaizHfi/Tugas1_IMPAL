<?php
/**
 * Implementasi PSPEC / Algoritma Proses Pesanan (Web Pesan Warkop99)
 * Kelas: Control_Pesanan
 */

class Control_Pesanan {
    private $dbConnection;

    // Inisialisasi koneksi database MySQL
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Algoritma #2: prosesPesanan
     * Memvalidasi ID pengguna, menyimpan pesanan, dan menampilkan notifikasi.
     * 
     * @param int $idUser
     * @param array $dataPesanan
     * @return string
     */
    public function prosesPesanan($idUser, $dataPesanan) {
        // 1. IF validasiUser(idUser) = false THEN Return "User tidak valid"
        if (!$this->validasiUser($idUser)) {
            return "User tidak valid";
        }

        // 2. simpanPesanan(dataPesanan)
        $isSaved = $this->simpanPesanan($idUser, $dataPesanan);

        if ($isSaved) {
            // 3. tampilkanNotifikasiBerhasil() & Return "Pesanan berhasil diproses"
            $this->tampilkanNotifikasiBerhasil();
            return "Pesanan berhasil diproses";
        } else {
            return "Gagal menyimpan pesanan ke database.";
        }
    }

    /**
     * Helper function untuk memvalidasi pengguna di database
     */
    private function validasiUser($idUser) {
        $query = "SELECT id_user FROM data_user WHERE id_user = ?";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Helper function untuk menyimpan data pesanan ke MySQL
     */
    private function simpanPesanan($idUser, $dataPesanan) {
        $query = "INSERT INTO data_pesanan (id_user, alamat, qty, total_harga, status) VALUES (?, ?, ?, ?, 'Menunggu')";
        $stmt = $this->dbConnection->prepare($query);
        $stmt->bind_param("isid", $idUser, $dataPesanan['alamat'], $dataPesanan['qty'], $dataPesanan['total_harga']);
        
        return $stmt->execute();
    }

    /**
     * Helper function untuk pemicu notifikasi berhasil
     */
    private function tampilkanNotifikasiBerhasil() {
        // Logika sederhana untuk menampilkan pesan / trigger notifikasi UI
        echo "Notification: Pesanan Berhasil Dibuat!\n";
    }
}

// ==========================================
// CONTOH PENGGUNAAN (TESTING RUNNER)
// ==========================================
/*
$mysqli = new mysqli("localhost", "root", "", "warkop99_db");

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

$controller = new Control_Pesanan($mysqli);

$sampleOrder = [
    'alamat' => 'Kost Laki Banget',
    'qty' => 2,
    'total_harga' => 25000
];

// Contoh pengujian untuk user ID = 1
echo $controller->prosesPesanan(1, $sampleOrder);
*/
?>