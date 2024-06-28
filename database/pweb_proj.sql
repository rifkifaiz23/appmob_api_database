-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jun 2024 pada 14.20
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pweb_proj`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `noHP` varchar(20) NOT NULL,
  `tgl_checkin` date NOT NULL,
  `tgl_checkout` date NOT NULL,
  `id_room` int(11) DEFAULT NULL,
  `harga` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `fullname`, `noHP`, `tgl_checkin`, `tgl_checkout`, `id_room`, `harga`, `gambar`) VALUES
(53, 'ready', '08629', '2024-06-28', '2024-06-29', 102, 'Rp.900 rb', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg'),
(55, 'Aqmal Syarif', '0897152717778', '2024-06-27', '2024-06-29', 103, 'Rp.600 rb', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg'),
(56, 'Rifki Faiz', '08976162919', '2024-06-30', '2024-07-02', 118, 'Rp.600 rb', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg'),
(57, 'Ready Malik', '08916735186', '2024-07-02', '2024-07-04', 117, 'Rp.900 rb', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg');

--
-- Trigger `reservasi`
--
DELIMITER $$
CREATE TRIGGER `before_insert_reservasi` BEFORE INSERT ON `reservasi` FOR EACH ROW BEGIN
    DECLARE room_price VARCHAR(255);
    -- Ambil harga dari tabel room berdasarkan id_room
    SELECT `harga` INTO room_price
    FROM `room`
    WHERE `id_room` = NEW.`id_room`;

    -- Set harga di tabel reservasi
    SET NEW.`harga` = room_price;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `gambar_reservasi` BEFORE INSERT ON `reservasi` FOR EACH ROW BEGIN
    DECLARE room_gambar VARCHAR(255);
    -- Ambil harga dari tabel room berdasarkan id_room
    SELECT `gambar` INTO room_gambar
    FROM `room`
    WHERE `id_room` = NEW.`id_room`;

    -- Set harga di tabel reservasi
    SET NEW.`gambar` = room_gambar;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `room`
--

CREATE TABLE `room` (
  `id_room` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` varchar(255) NOT NULL,
  `fasilitas` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `room`
--

INSERT INTO `room` (`id_room`, `kategori`, `harga`, `fasilitas`, `gambar`, `status`) VALUES
(101, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(102, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'booked'),
(103, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'booked'),
(104, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(105, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'available'),
(106, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'available'),
(107, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(108, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'available'),
(109, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'available'),
(110, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(111, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'available'),
(112, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'available'),
(113, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(114, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'available'),
(115, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'available'),
(116, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(117, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'booked'),
(118, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'booked'),
(119, 'Deluxe', 'Rp.1,1 jt', 'TV, AC, 2 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub), Wardrobe', 'https://i.pinimg.com/564x/47/f7/77/47f7772ed6ceff3f3692573e4ed96ed8.jpg', 'available'),
(120, 'Premium', 'Rp.900 rb', 'TV, AC, 1 Ruang Tidur, 1 Ruang Utama, 2 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e3/25/8e/e3258e136d6ceba3cd01b24ac91bc428.jpg', 'available'),
(121, 'Regular', 'Rp.600 rb', 'TV, AC, 1 Ruang Tidur, 1 Kamar Mandi (Bathtub)', 'https://i.pinimg.com/564x/e1/2e/ae/e12eaecefdcfe8136740b7abc26bee57.jpg', 'available');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`email`, `password`, `fullname`, `profil`) VALUES
('caca23@gmail.com', 'Salsa123', 'Salsabila Aurelia', 'uploads/profile_image_1719559552112.jpg'),
('kemal@gmail.com', 'Aqmal123', 'Kemal', 'uploads/profile_image_1719545342548.jpg'),
('manda@gmail.com', 'Manda123', 'Amanda Leony Zelvi', 'uploads/profile_image_1719545877385.jpg'),
('ready@gmail.com', '1234567', 'ready', 'uploads/profile_image_1719561958839.jpg'),
('readyputra@gmail.com', 'Ready123', 'Ready Malik Putra', 'uploads/profile_image_1719559699464.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `id_room` (`id_room`);

--
-- Indeks untuk tabel `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id_room`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`id_room`) REFERENCES `room` (`id_room`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
