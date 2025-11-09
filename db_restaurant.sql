-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2025 at 06:42 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_restaurant`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `setup_final_restaurant_db` ()   BEGIN     CREATE TABLE IF NOT EXISTS kategori (         id_kategori INT AUTO_INCREMENT PRIMARY KEY,         nama_kategori VARCHAR(100) NOT NULL,         slug VARCHAR(100) NOT NULL UNIQUE,         created_at DATETIME DEFAULT CURRENT_TIMESTAMP     ) ENGINE=InnoDB;      IF (SELECT COUNT(*) = 0 FROM INFORMATION_SCHEMA.COLUMNS         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'menu' AND COLUMN_NAME = 'detail_lengkap') THEN         SET @sql = 'ALTER TABLE menu ADD COLUMN detail_lengkap TEXT NULL AFTER deskripsi';         PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;     END IF;      IF (SELECT COUNT(*) = 0 FROM INFORMATION_SCHEMA.COLUMNS         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'menu' AND COLUMN_NAME = 'kategori_id') THEN         SET @sql = 'ALTER TABLE menu ADD COLUMN kategori_id INT NULL AFTER detail_lengkap';         PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;     END IF;      IF (SELECT COUNT(*) = 0 FROM INFORMATION_SCHEMA.COLUMNS         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ulasan' AND COLUMN_NAME = 'id_menu') THEN         SET @sql = 'ALTER TABLE ulasan ADD COLUMN id_menu INT NULL AFTER id_pesanan';         PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;     END IF;      IF (SELECT COUNT(*) = 0 FROM INFORMATION_SCHEMA.COLUMNS         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ulasan' AND COLUMN_NAME = 'status_ulasan') THEN         SET @sql = 'ALTER TABLE ulasan ADD COLUMN status_ulasan ENUM(''pending'', ''disetujui'', ''ditolak'') NOT NULL DEFAULT ''pending'' AFTER komentar';         PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;     END IF;      IF (SELECT COUNT(*) = 0 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'menu' AND CONSTRAINT_NAME = 'fk_menu_kategori') THEN         SET @sql = 'ALTER TABLE menu ADD CONSTRAINT fk_menu_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id_kategori) ON DELETE SET NULL ON UPDATE CASCADE';         PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;     END IF;      IF (SELECT COUNT(*) = 0 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ulasan' AND CONSTRAINT_NAME = 'fk_ulasan_menu') THEN         SET @sql = 'ALTER TABLE ulasan ADD CONSTRAINT fk_ulasan_menu FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE SET NULL ON UPDATE CASCADE';         PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;     END IF;      INSERT IGNORE INTO kategori (nama_kategori, slug) VALUES         ('Makanan', 'makanan'),         ('Minuman', 'minuman'),         ('Dessert', 'dessert'),         ('Cemilan', 'cemilan'),         ('Paket', 'paket'); END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 2, '50000.00'),
(2, 2, 1, 1, '25000.00'),
(3, 2, 3, 1, '27000.00'),
(4, 3, 1, 1, '25000.00'),
(5, 3, 3, 1, '27000.00'),
(6, 4, 37, 3, '45000.00'),
(7, 4, 44, 1, '18000.00'),
(8, 5, 44, 1, '18000.00'),
(9, 5, 2, 1, '20000.00'),
(10, 5, 9, 1, '8000.00'),
(11, 6, 3, 2, '54000.00'),
(12, 7, 1, 2, '50000.00'),
(13, 7, 2, 2, '40000.00'),
(14, 7, 3, 2, '54000.00'),
(15, 7, 4, 2, '56000.00'),
(16, 7, 9, 2, '16000.00'),
(17, 7, 8, 1, '30000.00'),
(18, 7, 7, 1, '32000.00'),
(19, 7, 6, 1, '45000.00'),
(20, 8, 2, 1, '20000.00'),
(21, 8, 9, 1, '8000.00'),
(22, 9, 1, 1, '25000.00'),
(23, 9, 13, 1, '12000.00'),
(24, 9, 49, 1, '30000.00'),
(25, 9, 9, 2, '16000.00'),
(26, 10, 2, 1, '20000.00'),
(27, 10, 24, 1, '22000.00'),
(28, 11, 3, 1, '27000.00'),
(29, 12, 2, 2, '40000.00'),
(30, 12, 3, 2, '54000.00'),
(31, 13, 2, 1, '20000.00'),
(32, 13, 8, 1, '30000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `slug`, `gambar`, `created_at`) VALUES
(1, 'Makanan', 'makanan', 'kategori_1_1761030607.png', '2025-10-21 12:05:36'),
(2, 'Minuman', 'minuman', 'kategori_2_1761030613.png', '2025-10-21 12:07:12'),
(4, 'Cemilan', 'cemilan', 'kategori_4_1761030601.png', '2025-10-21 12:07:12'),
(10, 'Dessert', 'dessert', 'kategori_1761055144.png', '2025-10-21 20:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `deskripsi` text,
  `kategori` int DEFAULT NULL,
  `detail_lengkap` text COMMENT 'Konten rich text dari CKEditor',
  `stok` int DEFAULT '0',
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `harga`, `deskripsi`, `kategori`, `detail_lengkap`, `stok`, `gambar`) VALUES
(1, 'Nasi Goreng', '25000.00', 'Nasi goreng gurih dengan potongan ayam, udang, dan telur mata sapi.', 1, '<p>Nasi dimasak dengan kecap manis premium, bawang putih, dan sedikit cabai yang menghasilkan cita rasa manis pedas seimbang. Topping berupa telur orak-arik, ayam suwir, dan bawang goreng renyah menambah tekstur nikmat. Dihidangkan panas dengan acar segar dan kerupuk udang, menciptakan harmoni rasa tradisional Indonesia yang autentik.</p>', 48, 'menu_1_1761021801.jpeg'),
(2, 'Mie Ayam Bakso', '20000.00', 'Nasi goreng spesial', 1, '<p>Mie buatan tangan dimasak dengan kuah kaldu ayam yang jernih namun kaya rasa. Potongan ayam berbumbu manis gurih berpadu dengan bakso daging sapi yang empuk. Taburan daun bawang dan bawang goreng menambah aroma sedap. Setiap suapan memberikan sensasi hangat dan lembut di mulut.</p>', 43, 'menu_2_1761021845.jpeg'),
(3, 'Sate Ayam', '27000.00', 'Sate Ayam Lezat Dengan Kuah Yang Kental dan Berempah Nusantara', 1, '<p>Potongan daging ayam segar ditusuk rapi lalu dibakar perlahan di atas arang, menghasilkan aroma smoky yang menggoda. Siraman saus kacang halus dengan tambahan kecap manis dan bawang merah membuat rasanya semakin kaya. Disajikan dengan lontong hangat, sambal, dan sedikit perasan jeruk limau untuk keseimbangan rasa.</p>', 28, 'menu_3_1761021890.jpeg'),
(4, 'Ayam Geprek', '28000.00', 'Ayam Geprek Potongan Dada Besar dna Lezat Dengan Sambal Geprek Pedas', 1, '<p>Ayam goreng tepung yang renyah di luar dan juicy di dalam digeprek bersama sambal bawang segar yang diulek kasar. Tingkat kepedasan bisa disesuaikan sesuai selera. Dilengkapi dengan nasi putih hangat, timun, lalapan, dan tempe goreng. Setiap gigitan menghadirkan perpaduan renyah, pedas, dan gurih yang sempurna.</p>', 23, 'menu_4_1761021943.jpg'),
(6, 'Ayam Bakar Taliwang', '45000.00', 'Ayam bakar khas taliwang pedas manis.', 1, '<p>Ayam kampung dibumbui rempah tradisional seperti cabai merah, bawang merah, dan kemiri, lalu dibakar perlahan hingga bumbunya meresap sempurna. Hasilnya, daging ayam yang empuk dengan kulit karamelisasi beraroma khas. Disajikan bersama nasi hangat, sambal terasi, dan lalapan segar.</p>', 29, 'menu_6_1761032429.jpg'),
(7, 'Sate Ayam Madura', '32000.00', 'Sate ayam bumbu kacang, lontong dan sambal.', 1, '<p>Daging ayam dipotong kecil dan dibakar di atas bara api sambil dilumuri kecap manis. Disajikan dengan saus kacang halus, taburan bawang goreng, dan potongan lontong. Bumbunya meresap sempurna, menghadirkan cita rasa khas yang menggugah selera makan malam Anda.</p>', 39, 'menu_7_1761032463.jpeg'),
(8, 'Mie Goreng Seafood', '30000.00', 'Mie goreng dengan udang dan cumi segar.', 1, '<p>Mie telur lembut dimasak dengan saus oriental khas restoran yang kaya rasa. Udang dan cumi segar menambah tekstur kenyal, sementara sayuran seperti kol dan wortel memberikan kesegaran alami. Disajikan panas dengan telur orak-arik dan taburan bawang goreng.</p>', 33, 'menu_8_1761032542.jpg'),
(9, 'Es Teh Manis', '8000.00', 'Es teh manis segar.', 2, '<p>Terbuat dari daun teh pilihan yang diseduh dengan suhu sempurna untuk mempertahankan aroma khasnya. Gula cair alami memberikan rasa manis yang lembut di lidah. Disajikan dengan es batu dan daun mint sebagai sentuhan akhir yang menenangkan.</p>', 96, 'menu_9_1761032601.jpeg'),
(10, 'Es Jeruk Peras', '12000.00', 'Jeruk peras segar dengan es batu.', 2, '<p>Menggunakan jeruk peras alami tanpa bahan pengawet, diperas langsung saat dipesan. Rasanya manis-asam alami dengan aroma jeruk segar. Cocok diminum dingin untuk menghilangkan dahaga atau sebagai penutup makan siang yang menyegarkan.</p>', 80, 'menu_10_1761032663.jpg'),
(11, 'Kopi Tubruk', '15000.00', 'Kopi hitam tubruk khas.', 2, '<p>Bubuk kopi pilihan diseduh langsung dengan air panas tanpa saringan, menghasilkan rasa pahit mantap dan aroma kuat. Cocok untuk pencinta kopi sejati yang menyukai sensasi kopi murni dengan sedikit ampas di dasar cangkir.</p>', 60, 'menu_11_1761032739.jpeg'),
(12, 'Smoothie Mangga', '22000.00', 'Smoothie mangga segar, lembut dan manis.', 2, '<p>Dibuat dari mangga harum manis, susu segar, dan sedikit madu, menghasilkan tekstur lembut dan rasa manis alami. Disajikan dingin dengan whipped cream di atasnya. Menyegarkan sekaligus mengenyangkan di siang hari.</p>', 40, 'menu_12_1761032776.jpeg'),
(13, 'Pisang Goreng', '12000.00', 'Pisang goreng renyah, cocok untuk camilan.', 4, '<p>Setiap potong pisang digoreng hingga renyah di luar dan lembut di dalam. Adonannya sedikit gurih untuk menyeimbangkan rasa manis alami pisang. Dapat dinikmati dengan tambahan keju parut atau cokelat leleh sesuai selera.</p>', 60, 'menu_13_1761032810.jpeg'),
(14, 'Kentang Goreng', '18000.00', 'Kentang goreng renyah dengan saus.', 4, '<p>Kentang pilihan digoreng dua kali untuk menghasilkan kerenyahan sempurna. Disajikan panas dengan saus tomat, sambal, atau mayones. Tekstur ringan membuatnya cocok sebagai camilan maupun pendamping hidangan utama.</p>', 70, 'menu_14_1761032842.jpg'),
(15, 'Tahu Isi', '10000.00', 'Tahu isi sayur dan bumbu, digoreng garing.', 4, '<p>Tahu putih diisi dengan campuran wortel, kol, dan tauge yang dibumbui lembut. Digoreng hingga kulitnya garing dan berwarna keemasan. Cocok disajikan dengan cabai rawit segar untuk menambah sensasi pedas.</p>', 50, 'menu_15_1761032879.jpg'),
(16, 'Risol Mayo', '14000.00', 'Risol isi mayo creamy, gurih.', 4, '<p>Kulit risol tipis dan renyah membungkus isian creamy yang gurih. Perpaduan smoked beef dan mayo menciptakan rasa gurih manis yang seimbang. Disajikan hangat, cocok untuk camilan sore hari bersama minuman kopi atau teh.</p>', 45, 'menu_16_1761032919.jpg'),
(17, 'Tempe Crispy', '10000.00', 'Tempe Krispi Premium, Bukan Sekadar Camilan', 4, '<p>Kami hanya menggunakan tempe berkualitas terbaik dan bumbu rempah pilihan. Hasilnya? Tempe krispi dengan tekstur garing yang pas dan rasa gurih yang meresap sempurna. Jauh dari kata membosankan, camilan sehat kaya protein ini akan membuat Anda ketagihan sejak gigitan pertama.</p>', 40, 'menu_17_1761035436.jpg'),
(18, 'Jus Alpukat', '20000.00', 'Jus alpukat creamy dengan susu kental manis.', 2, '<p>Alpukat segar diblender halus dengan susu dan es batu, menghasilkan tekstur creamy dan rasa manis legit. Tambahan cokelat cair di sisi gelas menambah kenikmatan.</p>', 50, 'menu_18_1761055777.jpeg'),
(19, 'Kopi Susu Dingin', '18000.00', 'Kopi susu dingin kekinian dengan sentuhan karamel.', 2, '<p>Paduan kopi pilihan, susu segar, dan sedikit pemanis, disajikan dingin. Rasa kopi yang kuat berpadu dengan kelembutan susu.</p>', 60, 'menu_19_1761055836.jpeg'),
(20, 'Green Tea Latte', '25000.00', 'Matcha latte dingin dengan susu creamy.', 2, '<p>Bubuk matcha berkualitas tinggi dicampur dengan susu segar dan disajikan dingin. Rasa teh hijau yang khas berpadu dengan kelembutan susu.</p>', 40, 'menu_20_1761055874.jpg'),
(21, 'Thai Tea', '18000.00', 'Minuman teh Thailand manis creamy.', 2, '<p>Teh hitam Thailand yang khas dicampur dengan susu kental manis dan evaporasi, disajikan dingin. Rasa manis dan creamy yang menyegarkan.</p>', 55, 'menu_21_1761055955.jpg'),
(22, 'Lemon Tea Hangat', '15000.00', 'Teh hangat dengan perasan lemon segar.', 2, '<p>Teh hitam diseduh hangat dengan perasan lemon asli. Rasanya asam segar dan sedikit manis, cocok untuk menghangatkan tubuh.</p>', 45, 'menu_22_1761055987.jpeg'),
(23, 'Cokelat Panas', '20000.00', 'Minuman cokelat panas kaya rasa.', 2, '<p>Cokelat premium dilelehkan dan dicampur dengan susu panas, menghasilkan minuman cokelat yang kental, manis, dan menghangatkan.</p>', 35, 'menu_23_1761056027.jpg'),
(24, 'Es Kopi Susu Aren', '22000.00', 'Es kopi susu dengan gula aren khas.', 2, '<p>Paduan espresso, susu segar, dan gula aren asli, disajikan dingin. Rasa manis legit gula aren berpadu sempurna dengan pahitnya kopi.</p>', 65, 'menu_24_1761056064.jpg'),
(25, 'Bandrek', '15000.00', 'Minuman tradisional jahe dan rempah hangat.', 2, '<p>Minuman tradisional khas Sunda yang terbuat dari jahe, gula merah, dan rempah lainnya. Disajikan hangat, cocok untuk menghangatkan badan.</p>', 30, 'menu_25_1761056104.jpg'),
(26, 'Wedang Jahe', '12000.00', 'Minuman jahe hangat yang menenangkan.', 2, '<p>Irisan jahe segar direbus hingga sari-sarinya keluar, diberi sedikit gula. Disajikan hangat, sangat cocok untuk meredakan masuk angin atau sekadar menikmati kehangatan.</p>', 40, 'menu_26_1761056172.jpg'),
(27, 'Jus Jambu', '15000.00', 'Jus jambu segar dan manis.', 2, '<p>Jambu biji merah segar diblender halus, menghasilkan jus yang kaya serat dan vitamin. Rasanya manis alami dan menyegarkan.</p>', 48, 'menu_27_1761056203.jpeg'),
(28, 'Jus Melon', '17000.00', 'Jus melon segar dan manis.', 2, '<p>Melon segar diblender halus dengan sedikit es, menghasilkan jus yang manis, ringan, dan sangat menyegarkan di cuaca panas.</p>', 42, 'menu_28_1761056270.jpeg'),
(29, 'Es Kopi Hitam', '15000.00', 'Kopi hitam dingin tanpa susu.', 2, '<p>Espresso disajikan dengan es batu dan air, cocok bagi pecinta kopi hitam murni yang menyukai kesegaran.</p>', 55, 'menu_29_1761056297.jpeg'),
(30, 'Teh Tarik', '18000.00', 'Teh susu tarik hangat/dingin.', 2, '<p>Teh dicampur susu lalu ditarik berulang kali hingga berbusa, menghasilkan tekstur lembut dan rasa teh susu yang khas.</p>', 38, 'menu_30_1761056401.jpg'),
(31, 'Soda Gembira', '20000.00', 'Minuman soda dengan susu kental manis dan sirup.', 2, '<p>Soda bening dicampur dengan susu kental manis dan sirup merah, menghasilkan minuman yang unik, manis, dan berkarbonasi.</p>', 30, 'menu_31_1761056346.jpeg'),
(32, 'Milo Dinosaur', '25000.00', 'Es milo dingin dengan taburan bubuk milo melimpah.', 2, '<p>Minuman es milo dingin yang disajikan dengan taburan bubuk milo ekstra di atasnya, cocok untuk pecinta cokelat.</p>', 40, 'menu_32_1761056433.jpg'),
(33, 'Air Mineral', '5000.00', 'Air mineral kemasan 600ml.', 2, '<p>Air mineral alami yang bersih dan menyegarkan.</p>', 100, 'menu_33_1761056533.jpg'),
(34, 'Cireng Salju', '15000.00', 'Cireng kenyal dengan bumbu rujak pedas.', 4, '<p>Cireng goreng yang renyah di luar dan kenyal di dalam, disajikan dengan saus rujak pedas manis yang segar. Camilan tradisional yang nikmat.</p>', 40, 'menu_34_1761056597.png'),
(35, 'Singkong Keju', '18000.00', 'Singkong goreng empuk dengan taburan keju.', 4, '<p>Singkong pilihan direbus hingga empuk lalu digoreng, kemudian ditaburi keju parut. Rasa gurih singkong berpadu dengan keju yang lezat.</p>', 35, 'menu_35_1761056788.jpg'),
(36, 'Otak-Otak Bakar', '22000.00', 'Otak-otak ikan bakar dengan saus kacang.', 4, '<p>Olahan daging ikan yang dibungkus daun pisang lalu dibakar, disajikan dengan saus kacang pedas. Aroma bakaran yang khas menambah selera.</p>', 30, 'menu_36_1761056816.jpg'),
(37, 'Bakwan Jagung', '15000.00', 'Bakwan jagung manis renyah.', 4, '<p>Adonan tepung dengan irisan jagung manis, digoreng hingga garing. Cocok dinikmati dengan cabai rawit atau saus sambal.</p>', 45, 'menu_37_1761056847.jpg'),
(38, 'Combro', '12000.00', 'Combro isi oncom pedas.', 4, '<p>Singkong parut yang diisi oncom pedas, lalu digoreng hingga matang. Camilan tradisional khas Sunda yang gurih dan pedas.</p>', 30, 'menu_38_1761056873.jpg'),
(39, 'Misro', '12000.00', 'Misro isi gula merah lumer.', 4, '<p>Singkong parut yang diisi gula merah, lalu digoreng hingga gula merahnya lumer. Rasanya manis dan gurih, cocok untuk teman minum teh.</p>', 30, 'menu_39_1761056904.jpg'),
(40, 'Mendoan', '15000.00', 'Tempe mendoan hangat dengan sambal kecap.', 4, '<p>Irisan tempe tipis yang digoreng setengah matang dengan adonan tepung khusus, disajikan hangat dengan sambal kecap pedas. Camilan khas Banyumas.</p>', 40, 'menu_40_1761056931.jpeg'),
(41, 'Batagor Kuah', '25000.00', 'Batagor dengan kuah kaldu ikan.', 4, '<p>Batagor goreng disajikan dalam kuah kaldu ikan yang gurih, dilengkapi dengan tahu, bakso, dan taburan seledri. Pilihan menarik selain batagor kering.</p>', 25, 'menu_41_1761056974.jpg'),
(42, 'Cilok Bumbu Kacang', '18000.00', 'Cilok kenyal dengan saus kacang.', 4, '<p>Aci dicolok (cilok) yang kenyal, disajikan dengan saus kacang kental dan sedikit sambal. Camilan populer dari Jawa Barat.</p>', 35, 'menu_42_1761057190.jpg'),
(43, 'Tahu Crispy', '15000.00', 'Tahu goreng crispy dengan bumbu.', 4, '<p>Tahu putih dipotong dadu, dilumuri tepung berbumbu, lalu digoreng hingga super crispy. Disajikan dengan taburan bumbu aneka rasa.</p>', 40, 'menu_43_1761057232.jpg'),
(44, 'Jamur Crispy', '18000.00', 'Jamur tiram crispy dengan bumbu.', 4, '<p>Jamur tiram segar dilumuri tepung berbumbu dan digoreng hingga crispy. Camilan gurih dan sehat yang cocok untuk semua kalangan.</p>', 34, 'menu_44_1761057265.jpeg'),
(45, 'Tela-Tela', '15000.00', 'Singkong goreng dengan bumbu bubuk.', 4, '<p>Singkong yang dipotong memanjang, digoreng lalu ditaburi bubuk bumbu aneka rasa seperti balado atau keju. Camilan renyah dan gurih.</p>', 38, 'menu_45_1761057298.jpg'),
(46, 'Makaroni Pedas', '10000.00', 'Makaroni goreng pedas.', 4, '<p>Makaroni direbus lalu digoreng hingga kering dan renyah, kemudian dibumbui bubuk pedas. Cocok untuk pecinta camilan pedas.</p>', 50, 'menu_46_1761057408.jpg'),
(47, 'Kue Cubit', '15000.00', 'Kue cubit setengah matang dengan topping.', 4, '<p>Kue cubit yang empuk dengan bagian tengah yang lumer, disajikan dengan topping cokelat atau keju. Camilan manis yang disukai banyak orang.</p>', 30, 'menu_47_1761057439.jpg'),
(48, 'Donat Mini', '20000.00', 'Donat mini dengan glaze aneka rasa.', 4, '<p>Donat berukuran kecil yang empuk, dilapisi dengan berbagai rasa glaze dan taburan meses atau sprinkle. Cocok sebagai teman ngopi.</p>', 25, 'menu_48_1761057702.jpg'),
(49, 'Panna Cotta Beri', '30000.00', 'Panna cotta lembut dengan saus buah beri segar.', 10, '<p>Dessert Italia klasik dengan tekstur super lembut dan lumer di mulut. Disiram saus buah beri segar yang sedikit asam, menciptakan keseimbangan rasa manis dan segar yang sempurna.</p>', 25, 'menu_49_1761057734.jpeg'),
(50, 'Chocolate Lava Cake', '35000.00', 'Kue cokelat hangat dengan lelehan cokelat di dalamnya.', 10, '<p>Kue cokelat panggang dengan bagian luar yang renyah dan bagian dalam yang lumer meleleh saat dipotong. Disajikan hangat dengan es krim vanila, sangat memanjakan lidah.</p>', 20, 'menu_50_1761057785.jpg'),
(51, 'Es Krim Vanila', '20000.00', 'Es krim vanila lembut dengan topping pilihan.', 10, '<p>Es krim vanila premium yang lembut dan creamy, dapat dinikmati polos atau dengan tambahan topping seperti saus cokelat, kacang, atau meses.</p>', 50, 'menu_51_1761057865.jpg'),
(52, 'Tiramisu', '40000.00', 'Dessert Italia kopi dan keju mascarpone.', 10, '<p>Lapisan biskuit ladyfinger yang dicelup kopi, krim mascarpone lembut, dan taburan kakao. Rasa manis, pahit, dan creamy berpadu harmonis dalam setiap suapan.</p>', 18, 'menu_52_1761057938.jpg'),
(53, 'Brownies Kukus', '28000.00', 'Brownies cokelat kukus lembut dan lembap.', 10, '<p>Brownies cokelat yang dikukus, menghasilkan tekstur yang sangat lembut dan lembap. Kaya akan rasa cokelat dan cocok dinikmati sebagai camilan atau dessert.</p>', 30, 'menu_53_1761057964.jpg'),
(54, 'Fruit Salad with Yogurt', '25000.00', 'Salad buah segar dengan saus yogurt.', 10, '<p>Campuran buah-buahan segar pilihan seperti melon, semangka, stroberi, dan anggur, disiram dengan saus yogurt creamy yang sedikit asam. Dessert sehat dan menyegarkan.</p>', 35, 'menu_54_1761058002.jpg'),
(55, 'Cheesecake Slice', '38000.00', 'Sepotong cheesecake lembut dan creamy.', 10, '<p>Potongan cheesecake lembut dengan lapisan biskuit renyah di bagian bawah. Rasa keju yang kaya dan tekstur yang halus. Dapat disajikan dengan saus buah atau karamel.</p>', 22, 'menu_55_1761058033.jpg'),
(56, 'Crepes Cokelat Pisang', '30000.00', 'Crepes tipis dengan isian pisang dan cokelat.', 10, '<p>Crepes tipis yang renyah di bagian pinggir dan lembut di tengah, diisi dengan irisan pisang dan lelehan saus cokelat. Cocok sebagai dessert yang memuaskan.</p>', 28, 'menu_56_1761058080.jpg'),
(57, 'Matcha Pudding', '28000.00', 'Puding matcha lembut dengan saus vanilla.', 10, '<p>Puding lembut dengan rasa teh hijau matcha yang khas dan sedikit pahit. Disajikan dengan saus vanilla manis yang melengkapi rasa.</p>', 25, 'menu_57_1761058129.jpg'),
(58, 'Klepon Cake', '32000.00', 'Kue modern dengan inspirasi klepon tradisional.', 10, '<p>Kue lembut dengan aroma pandan, isian gula merah cair, dan taburan kelapa parut, terinspirasi dari kue tradisional klepon. Perpaduan rasa dan tekstur yang unik.</p>', 20, 'menu_58_1761058160.jpg'),
(59, 'Sop Buntut', '40000.00', 'Sop buntut sapi empuk dengan kuah rempah kaya rasa.', 1, '<p>Potongan buntut sapi pilihan direbus perlahan hingga empuk, disajikan dalam kuah kaldu bening yang kaya rempah. Ditaburi irisan wortel, kentang, dan daun bawang segar. Nikmat disantap selagi hangat dengan nasi putih.</p>', 40, 'menu_59_1761058699.jpg'),
(60, 'Gado-Gado', '25000.00', 'Salad sayur khas Indonesia dengan saus kacang lezat.', 1, '<p>Campuran aneka sayuran segar seperti kangkung, tauge, kol, dan labu siam, dilengkapi dengan tahu, tempe, telur rebus, dan lontong. Disiram saus kacang kental yang gurih manis. Sebuah hidangan sehat dan mengenyangkan.</p>', 30, 'menu_60_1761058737.jpeg'),
(61, 'Rawon', '38000.00', 'Sup daging hitam khas Jawa Timur dengan kluwek.', 1, '<p>Daging sapi empuk dimasak dalam kuah kluwek yang memberikan warna hitam pekat dan aroma khas. Disajikan dengan tauge pendek, telur asin, dan sambal, menciptakan perpaduan rasa umami yang mendalam.</p>', 35, 'menu_61_1761058748.jpeg'),
(62, 'Soto Ayam', '22000.00', 'Soto ayam berkuah kuning bening dengan suwiran ayam.', 1, '<p>Soto ayam dengan kuah kaldu bening kekuningan, disajikan dengan suwiran daging ayam, soun, irisan kentang, dan taburan bawang goreng. Tambahkan perasan jeruk nipis dan sambal untuk rasa yang lebih segar.</p>', 45, 'menu_62_1761058820.jpg'),
(63, 'Nasi Campur Bali', '35000.00', 'Nasi dengan lauk pauk khas Bali, pedas dan berempah.', 1, '<p>Sajian lengkap nasi putih dengan berbagai lauk khas Bali seperti ayam suwir bumbu betutu, sate lilit, telur balado, dan sayur urap. Kombinasi rasa pedas, gurih, dan manis yang autentik.</p>', 28, 'menu_63_1761058853.jpg'),
(64, 'Gurame Bakar', '65000.00', 'Ikan gurame segar dibakar dengan bumbu kecap pedas manis.', 1, '<p>Ikan gurame pilihan dibumbui rempah khusus dan dibakar hingga matang sempurna, menghasilkan daging ikan yang lembut dengan kulit renyah beraroma smoky. Cocok disantap dengan sambal matah.</p>', 20, 'menu_64_1761058911.jpg'),
(65, 'Udang Bakar Madu', '55000.00', 'Udang segar dibakar dengan lumuran madu dan bumbu.', 1, '<p>Udang segar berukuran besar dibumbui dan dibakar dengan olesan madu, menghasilkan rasa manis gurih yang unik. Sangat cocok bagi pecinta seafood.</p>', 25, 'menu_65_1761058922.jpg'),
(66, 'Tumis Kangkung Belacan', '18000.00', 'Kangkung segar ditumis dengan terasi pedas.', 1, '<p>Kangkung segar ditumis cepat dengan bumbu belacan (terasi) yang harum dan pedas. Tekstur kangkung yang renyah berpadu dengan bumbu gurih, cocok sebagai pendamping lauk.</p>', 30, 'menu_66_1761058974.jpg'),
(67, 'Capcay Kuah', '28000.00', 'Capcay dengan berbagai sayuran dan kuah kental gurih.', 1, '<p>Campuran beragam sayuran segar seperti brokoli, wortel, kembang kol, dan sawi hijau, dimasak dengan kuah kental yang gurih. Ditambah potongan bakso dan ayam, hidangan ini kaya nutrisi.</p>', 32, 'menu_67_1761058983.jpg'),
(68, 'Fuyunghai', '32000.00', 'Telur dadar tebal isi udang dan sayuran dengan saus asam manis.', 1, '<p>Telur dadar tebal yang diisi dengan udang cincang, wortel, dan bawang bombay. Disajikan dengan saus asam manis spesial yang gurih, cocok untuk dinikmati bersama nasi.</p>', 27, 'menu_68_1761059054.jpg'),
(69, 'Ayam Goreng Kalasan', '30000.00', 'Ayam goreng empuk dengan bumbu manis gurih khas Kalasan.', 1, '<p>Ayam pilihan diungkep dengan bumbu rempah dan air kelapa hingga empuk, lalu digoreng hingga keemasan. Rasa manis gurih yang meresap sempurna, cocok disantap dengan sambal dan lalapan.</p>', 38, 'menu_69_1761059062.jpg'),
(70, 'Pepes Ikan', '35000.00', 'Ikan dibungkus daun pisang dengan bumbu rempah lalu dikukus/bakar.', 1, '<p>Ikan segar dibumbui rempah pedas, kemangi, dan tomat, lalu dibungkus daun pisang dan dikukus hingga matang. Aroma harum dari daun pisang dan bumbu membuat hidangan ini sangat menggoda.</p>', 25, 'menu_70_1761059124.jpeg'),
(71, 'Sayur Asem', '15000.00', 'Sayur asem segar dengan kuah asam manis.', 1, '<p>Perpaduan sayuran seperti labu siam, kacang panjang, jagung, dan melinjo dimasak dalam kuah asam manis pedas yang menyegarkan. Sangat cocok sebagai pendamping hidangan utama.</p>', 33, 'menu_71_1761059133.jpg'),
(72, 'Tempe Penyet', '18000.00', 'Tempe goreng penyet dengan sambal bawang pedas.', 1, '<p>Tempe goreng yang digeprek bersama sambal bawang pedas segar. Rasanya gurih pedas yang sederhana namun nikmat, cocok untuk pecinta masakan tradisional.</p>', 40, 'menu_72_1761059204.jpg'),
(73, 'Tongseng Ayam', '30000.00', 'Tongseng ayam dengan kuah kental pedas manis.', 1, '<p>Daging ayam dimasak dengan bumbu tongseng khas, kol, tomat, dan kecap manis hingga kuahnya mengental. Rasa pedas, manis, dan gurihnya sangat kuat dan khas.</p>', 30, 'menu_73_1761059215.jpg'),
(74, 'Kari Ayam', '35000.00', 'Kari ayam dengan kuah kental rempah India.', 1, '<p>Potongan ayam dimasak dalam kuah kari kental yang kaya rempah India. Aroma harum dan rasa yang kuat cocok disantap dengan nasi putih hangat.</p>', 28, 'menu_74_1761093434.jpg'),
(75, 'Nasi Bakar', '27000.00', 'Nasi pulen dibakar dengan isian ayam/teri.', 1, '<p>Nasi yang sudah dibumbui dan diisi dengan suwiran ayam atau teri, lalu dibungkus daun pisang dan dibakar. Aroma daun pisang yang harum meresap ke nasi, menciptakan cita rasa yang unik.</p>', 35, 'menu_75_1761093532.jpg'),
(76, 'Pepes Tahu Jamur', '20000.00', 'Tahu dan jamur dibungkus daun pisang, dikukus.', 1, '<p>Tahu lembut dicampur irisan jamur, kemangi, dan bumbu rempah, dibungkus daun pisang lalu dikukus. Hidangan sehat yang gurih dan beraroma.</p>', 30, 'menu_76_1761093565.jpg'),
(77, 'Oseng Mercon', '38000.00', 'Daging sapi pedas nampol.', 1, '<p>Irisan daging sapi dimasak dengan cabai rawit merah yang melimpah, menghasilkan rasa pedas yang sangat kuat dan membakar lidah. Cocok untuk pecinta tantangan pedas.</p>', 20, 'menu_77_1761093639.jpg'),
(78, 'Ayam Woku', '33000.00', 'Ayam dimasak bumbu woku khas Manado.', 1, '<p>Potongan ayam dimasak dengan bumbu woku khas Manado yang kaya rempah, kemangi, dan tomat. Rasanya pedas, asam, dan gurih, sangat menyegarkan.</p>', 25, 'menu_78_1761093761.jpg'),
(79, 'Cumi Saus Padang', '42000.00', 'Cumi segar dimasak dengan saus Padang pedas manis.', 1, '<p>Cumi segar dimasak dengan saus Padang yang kaya rempah, pedas, dan sedikit manis. Tekstur cumi yang kenyal berpadu sempurna dengan saus yang kuat.</p>', 22, 'menu_79_1761093816.jpg'),
(81, 'Mille Crepes', '45000.00', 'Crepes berlapis tipis dengan krim lembut dan topping.', 10, '<p>Puluhan lapisan crepes tipis disusun rapi dengan selang-seling krim lembut. Hadir dengan berbagai varian rasa seperti vanila, cokelat, atau matcha. Sebuah karya seni yang lezat.</p>', 20, 'menu_81_1761094181.jpg'),
(82, 'Es Campur Pelangi', '28000.00', 'Minuman es dengan aneka buah, agar-agar, dan sirup.', 10, '<p>Campuran es serut, buah-buahan segar seperti alpukat, nangka, dan kolang-kaling, serta agar-agar, roti, dan mutiara. Disiram sirup manis dan susu kental manis, sangat menyegarkan.</p>', 35, 'menu_82_1761094238.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_meja` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `email`, `password`, `no_meja`) VALUES
(1, 'Syahril May Mubdi', 'syahrilmaymubdi2505@gmail.com', NULL, '2'),
(2, 'Paruhum', NULL, NULL, '4'),
(3, 'rasyah', NULL, NULL, '3'),
(4, 'Syahril May Mubdi', 'syahrilmaymubdi2505@gmail.com', NULL, '15'),
(5, 'bangfiz', NULL, NULL, '10'),
(6, 'al', NULL, NULL, '7'),
(7, 'Yaisy Faturrohman', NULL, NULL, '7'),
(8, 'y', NULL, NULL, '9'),
(9, 'Syahril May Mubdi', NULL, NULL, '12'),
(10, 'Syahril Maimubdy Mandai', NULL, NULL, '6'),
(11, 'Nsy', NULL, NULL, '8'),
(12, 'piss', 'orangasing@gmail.com', NULL, '9'),
(13, 'Mubdi', 'xrpltritech@gmail.com', NULL, '5');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','diproses','dikirim','selesai','batal') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `tanggal`, `total`, `status`) VALUES
(1, 1, '2025-10-21 11:48:23', '50000.00', 'selesai'),
(2, 2, '2025-10-21 11:51:19', '52000.00', 'batal'),
(3, 3, '2025-10-21 15:47:45', '52000.00', 'dikirim'),
(4, 4, '2025-10-22 12:22:18', '63000.00', 'pending'),
(5, 5, '2025-10-27 09:42:51', '46000.00', 'selesai'),
(6, 6, '2025-10-27 09:53:33', '54000.00', 'selesai'),
(7, 7, '2025-10-27 10:14:39', '323000.00', 'pending'),
(8, 8, '2025-10-27 10:15:07', '28000.00', 'selesai'),
(9, 9, '2025-10-27 11:29:55', '83000.00', 'batal'),
(10, 10, '2025-10-27 11:57:45', '42000.00', 'batal'),
(11, 11, '2025-10-27 12:59:24', '27000.00', 'diproses'),
(12, 12, '2025-10-27 15:38:48', '94000.00', 'pending'),
(13, 13, '2025-11-08 18:53:08', '50000.00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_promos`
--

CREATE TABLE `tbl_promos` (
  `id_promo` int NOT NULL,
  `nama_promo` varchar(255) NOT NULL,
  `deskripsi_promo` text,
  `harga_paket` decimal(10,2) NOT NULL,
  `gambar_promo` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_promo_items`
--

CREATE TABLE `tbl_promo_items` (
  `id_promo_item` int NOT NULL,
  `id_promo` int NOT NULL,
  `id_menu` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `banner1` varchar(255) DEFAULT NULL,
  `banner2` varchar(255) DEFAULT NULL,
  `banner3` varchar(255) DEFAULT NULL,
  `alamat` text,
  `jam_senin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jam_selasa` varchar(255) DEFAULT NULL,
  `jam_rabu` varchar(255) DEFAULT NULL,
  `jam_kamis` varchar(255) DEFAULT NULL,
  `jam_jumat` varchar(255) DEFAULT NULL,
  `jam_sabtu` varchar(255) DEFAULT NULL,
  `jam_minggu` varchar(255) DEFAULT NULL,
  `google_maps_link` text,
  `all_categories_icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `logo`, `banner1`, `banner2`, `banner3`, `alamat`, `jam_senin`, `jam_selasa`, `jam_rabu`, `jam_kamis`, `jam_jumat`, `jam_sabtu`, `jam_minggu`, `google_maps_link`, `all_categories_icon`) VALUES
(1, 'logo.webp', 'banner1.webp', 'banner2.webp', 'banner3.webp', 'Jl. Raya Rilstaurant No. 123, Jakarta', '10:00 - 22:00', '10:00 - 22:00', '10:00 - 22:00', '10:00 - 22:00', '10:00 - 22:00', '09:00 - 23:00', '09:00 - 23:00', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.576684832552!2d106.8271528147891!3d-6.19442019552222!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f4236d2b086d%3A0x7b23d8b4e2a7a9b2!2sMonumen%20Nasional!5e0!3m2!1sen!2sid!4v1623123456789!5m2!1sen!2sid', 'all-categories.webp');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text,
  `status_ulasan` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `nama_pelanggan`, `id_pesanan`, `id_menu`, `rating`, `komentar`, `status_ulasan`) VALUES
(3, 'Syahril May Mubdi', NULL, 1, 5, 'Sangat Mantap', 'disetujui'),
(4, 'Syahril May Mubdi', NULL, 11, 5, 'Mantap Kopinya', 'ditolak'),
(5, 'Farah Ayla Manha', NULL, 1, 3, 'Enak Banget Nasi Gorengnya Tapi Porsinya Terlalu Sedikit', 'disetujui'),
(6, 'bangfiz', NULL, 2, 5, 'kuah nya udh mantep gurih di tambah sambel ijo makin gurih dan bikin ketagihan mie nya juga ga kalah mantap mie nya kenyal dan enak di seruput dan ayam nya juga the best dan ayam nya kurang banyak aja nya', 'disetujui'),
(7, 'Al', NULL, 3, 5, 'Bagus mantap jos', 'disetujui'),
(8, 'lai', NULL, 2, 5, 'Penjulanya ganteng kekar bikin nafsu', 'ditolak');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `tbl_promos`
--
ALTER TABLE `tbl_promos`
  ADD PRIMARY KEY (`id_promo`);

--
-- Indexes for table `tbl_promo_items`
--
ALTER TABLE `tbl_promo_items`
  ADD PRIMARY KEY (`id_promo_item`),
  ADD KEY `id_promo` (`id_promo`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_pelanggan` (`nama_pelanggan`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `fk_ulasan_menu` (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_promos`
--
ALTER TABLE `tbl_promos`
  MODIFY `id_promo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_promo_items`
--
ALTER TABLE `tbl_promo_items`
  MODIFY `id_promo_item` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_promo_items`
--
ALTER TABLE `tbl_promo_items`
  ADD CONSTRAINT `tbl_promo_items_ibfk_1` FOREIGN KEY (`id_promo`) REFERENCES `tbl_promos` (`id_promo`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_promo_items_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE;

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `fk_ulasan_menu` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
