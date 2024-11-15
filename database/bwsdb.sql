-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 02:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bwsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `payment_method` enum('walk-in','gcash') NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `appointment_date`, `appointment_time`, `payment_method`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(51, 48, '2024-11-12', '13:25:00', 'walk-in', 350.00, 'cancelled', '2024-11-12 05:20:57', '2024-11-12 05:30:54'),
(52, 39, '2024-11-30', '06:47:00', 'walk-in', 1340.00, 'cancelled', '2024-11-12 20:46:41', '2024-11-12 20:49:44'),
(53, 39, '2024-11-13', '06:40:00', 'walk-in', 3600.00, '', '2024-11-12 22:40:38', '2024-11-12 22:41:21'),
(54, 39, '2024-11-13', '00:12:00', 'walk-in', 1000.00, '', '2024-11-12 23:06:48', '2024-11-12 23:08:14');

--
-- Triggers `appointments`
--
DELIMITER $$
CREATE TRIGGER `update_approved_booking_date_time` AFTER UPDATE ON `appointments` FOR EACH ROW BEGIN
    IF OLD.appointment_date != NEW.appointment_date OR OLD.appointment_time != NEW.appointment_time THEN
        UPDATE approved_bookings
        SET appointment_date = NEW.appointment_date,
            appointment_time = NEW.appointment_time
        WHERE appointment_id = NEW.id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `approved_bookings`
--

CREATE TABLE `approved_bookings` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `service_names` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booked_services`
--

CREATE TABLE `booked_services` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booked_services`
--

INSERT INTO `booked_services` (`id`, `appointment_id`, `service_id`, `price`) VALUES
(70, 51, 166, 350.00),
(71, 52, 170, 1200.00),
(72, 52, 162, 500.00),
(73, 53, 192, 1000.00),
(74, 53, 184, 2600.00),
(75, 54, 192, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `cancelled_bookings`
--

CREATE TABLE `cancelled_bookings` (
  `appointment_id` int(11) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `service_names` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `cancelled_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancelled_bookings`
--

INSERT INTO `cancelled_bookings` (`appointment_id`, `full_name`, `service_names`, `appointment_date`, `appointment_time`, `payment_method`, `total_price`, `cancelled_at`) VALUES
(60, 'Karen 0', 'Foot Spa with pedicure and manicure', '2024-11-09', '22:11:00', 'walk-in', 450.00, '2024-11-09 18:12:25'),
(61, 'Karen 0', 'Foot Massage, Hot Stone With Massage', '2024-11-09', '21:17:00', 'walk-in', 800.00, '2024-11-09 18:21:40'),
(63, 'Karen 0', 'Ventusa, Foot Spa with pedicure and manicure, Foot Spa with  Foot Massage', '2024-11-19', '23:09:00', 'walk-in', 1400.00, '2024-11-10 11:20:16'),
(23, 'Vivian Bulahan', 'Hot Stone With Massage', '2024-11-09', '18:11:00', 'walk-in', 500.00, '2024-11-11 12:11:44'),
(41, 'Vivian Bulahan', 'Armpit Hair Removal, Arm Hair Remova( per Session)', '2024-11-11', '13:53:00', 'walk-in', 1700.00, '2024-11-11 13:48:57'),
(51, 'Gendel Empeynado', 'Foot Spa  with Pedicure', '2024-11-12', '13:25:00', 'walk-in', 350.00, '2024-11-12 13:30:54'),
(52, 'Vivian Bulahan', 'Avana Facial , Hot Stone With Massage', '2024-11-30', '06:47:00', 'walk-in', 1340.00, '2024-11-13 04:49:44');

-- --------------------------------------------------------

--
-- Table structure for table `complete_bookings`
--

CREATE TABLE `complete_bookings` (
  `appointment_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `service_names` varchar(255) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complete_bookings`
--

INSERT INTO `complete_bookings` (`appointment_id`, `full_name`, `service_names`, `appointment_date`, `appointment_time`, `payment_method`, `total_price`) VALUES
(59, 'Karen 0', 'Combi (Swedish and shitsu)', '2024-12-04', '22:04:00', '0', 450.00),
(32, 'Vivian Bulahan', 'Foot Spa  with Pedicure, Foot Spa  with Manicure', '2024-11-12', '16:14:00', '0', 700.00),
(33, 'Vivian Bulahan', 'Foot Massage, Ventusa, Foot Spa with pedicure and manicure', '2024-11-11', '16:11:00', '0', 1250.00),
(43, 'Chad Christian  0', 'Hot Stone With Massage', '2024-11-11', '23:44:00', '0', 500.00),
(42, 'Vivian Bulahan', 'Ventusa, Foot Spa with pedicure and manicure, Avana Facial ', '2024-11-12', '19:05:00', '0', 2150.00),
(44, 'Chad Christian  0', 'Ventusa, Foot Spa  with Pedicure, Foot Spa with pedicure and manicure, Foot Spa with  Foot Massage', '2024-11-11', '22:50:00', '0', 1750.00),
(53, 'Vivian Bulahan', 'Armpit Hair Removal, RF(Radio  Frequency  Per Session)', '2024-11-13', '06:40:00', '0', 3600.00);

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `discount_percentage` int(11) NOT NULL CHECK (`discount_percentage` between 0 and 100),
  `discounted_price` decimal(10,2) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `service_id`, `discount_percentage`, `discounted_price`, `start_time`, `end_time`) VALUES
(55, 192, 20, 800.00, '2024-11-13 12:35:00', '2024-11-13 15:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `no_show_bookings`
--

CREATE TABLE `no_show_bookings` (
  `appointment_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `service_names` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('Available','Low Stock','Out of Stock') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `quantity`, `category_id`, `image`, `status`) VALUES
(5, 'aid', 'sadsd', 8, 73, 'Screenshot 2024-11-13 045224.png', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `category_id`, `image`) VALUES
(158, 'Head and Back Massage', 'Enjoy a rejuvenating 30-minute head and back massage designed to relieve tension and promote relaxation. Our skilled therapists focus on easing muscle stiffness, improving circulation, and soothing stress, leaving you refreshed and revitalized.', 200.00, 70, 'head back.webp'),
(159, 'Foot Massage', 'Experience a soothing 30-minute foot massage that targets pressure points to relieve tiredness, improve circulation, and reduce stress. Our expert therapists provide a relaxing treatment to restore balance and leave your feet feeling refreshed and revitalized', 300.00, 72, 'foot.jpg'),
(161, 'Dry Massage', 'Enjoy a 30-minute dry massage, a therapeutic treatment performed without oils or lotions. Focusing on deep pressure and stretching techniques, this massage helps relieve muscle tension, improve circulation, and promote overall relaxation and flexibility.', 600.00, 70, 'dry massaGE.jpg'),
(162, 'Hot Stone With Massage', 'Indulge in a 30-minute hot stone massage, where smooth, heated stones are placed on key points of the body to melt away tension. Combined with soothing massage techniques, this treatment promotes deep relaxation, improves circulation, and eases muscle stiffness, leaving you feeling restored and rejuvenated.', 500.00, 70, 'hot stone.jpg'),
(163, 'Ventusa', 'Experience a 30-minute Ventusa therapy, a traditional treatment using heated cups to create suction on the skin. This therapy helps improve blood flow, reduce muscle tension, and detoxify the body, leaving you feeling relaxed and revitalized.', 500.00, 70, 'Ventusa.jpg'),
(164, 'Foot Spa', 'Treat your feet to a 30-minute foot spa, designed to cleanse, exfoliate, and rejuvenate tired feet. This relaxing treatment includes a soothing soak, gentle scrub, and massage, leaving your feet feeling soft, refreshed, and revitalized.', 300.00, 72, 'foot spa.avif'),
(165, 'Foot Spa  with Manicure', 'Pamper yourself with a 30-minute foot spa and manicure combo. This treatment includes a soothing foot soak, exfoliation, and massage, followed by a professional manicure to shape and polish your nails, leaving your feet and hands feeling soft, refreshed, and beautifully groomed.', 350.00, 72, 'Express+Pedicure.jpg'),
(166, 'Foot Spa  with Pedicure', 'Indulge in a 30-minute foot spa with pedicure, starting with a relaxing foot soak, exfoliation, and massage to refresh tired feet. Followed by expert nail care, shaping, and polishing, this treatment leaves your feet soft, smooth, and perfectly groomed.', 350.00, 72, 'bigstock-Female-feet-at-spa-pedicure-pr-98440622.jpg'),
(167, 'Foot Spa with pedicure and manicure', 'Enjoy a luxurious 30-minute foot spa that includes both a pedicure and manicure. Begin with a relaxing foot soak, exfoliation, and massage to refresh your feet, followed by expert nail care for both hands and feet, complete with shaping, buffing, and polishing, leaving you feeling pampered and perfectly groomed.', 450.00, 72, 'spa-treatment-product-female-feet-hand-spa-scaled.jpg'),
(168, 'Foot Spa with  Foot Massage', 'Delight in a 30-minute foot spa experience paired with a soothing foot massage. This treatment begins with a relaxing foot soak and gentle exfoliation, followed by a therapeutic massage that targets pressure points to relieve tension and enhance circulation. Your feet will feel revitalized, refreshed, and deeply pampered', 450.00, 72, 'best-foot-spa.webp'),
(169, 'Foot Spa with Pedicure + Manicure + Foot Massage ', 'Treat yourself to a luxurious experience that combines a foot spa with a pedicure, manicure, and foot massage. Start with a relaxing foot soak and gentle exfoliation, followed by a soothing foot massage to relieve tension. Enjoy expert nail care for both hands and feet, including shaping, buffing, and polishing. Leave feeling completely pampered, with soft, beautiful feet and hands.', 600.00, 72, 'Express+Pedicure.jpg'),
(170, 'Avana Facial ', 'Revitalize your skin with the Avana Facial, featuring a deep cleanse, gentle exfoliation, and a soothing massage, topped off with a customized mask. This treatment promotes hydration and leaves your skin glowing and refreshed.', 1200.00, 71, 'avana-face-fresh-natural-paper.png'),
(171, 'Regular Facial', 'Refresh your complexion with a Regular Facial that includes a deep cleanse, gentle exfoliation, and a hydrating mask. This treatment revitalizes your skin, leaving it smooth, balanced, and glowing', 350.00, 71, 'Dollarphotoclub_85186489-800x533-e1457061860266.jpg'),
(172, 'Diamond peel Face', 'Experience the rejuvenating effects of a Diamond Peel Facial, which uses microdermabrasion to gently exfoliate the skin. This treatment removes dead skin cells, promotes cell renewal, and reveals a brighter, smoother complexion, leaving your skin looking youthful and radiant', 600.00, 71, 'Diamond-Peel-The-Derm-Spa-Stansbury-Park-UT.jpeg'),
(173, 'Diamond peel Nick', 'Revitalize your skin with a Diamond Peel Neck treatment, designed to exfoliate and rejuvenate the delicate neck area. Using microdermabrasion techniques, this treatment removes dead skin cells and promotes a smoother, more radiant appearance, helping to reduce signs of aging and leaving your neck looking refreshed and youthful', 500.00, 71, 'diamond-peel.jpg'),
(174, 'Galvanic + Lesser', 'Experience the benefits of a Galvanic Facial combined with a Lesser treatment. This innovative approach uses galvanic currents to enhance product penetration, stimulate collagen production, and improve skin texture. Ideal for addressing fine lines and wrinkles, this treatment leaves your skin looking firmer, smoother, and more youthful.', 400.00, 71, 'DSC_3867-2048x1367.jpg'),
(175, 'Hydra Facial with Regular mask', 'Revitalize your skin with a Hydra Facial featuring a regular mask. This treatment deeply cleanses, exfoliates, and hydrates, leaving your complexion fresh, smooth, and glowing.', 499.00, 71, 'facial service.jpg'),
(176, 'Hydra Facial with Diamond Peel', 'Refresh your skin with a Hydra Facial combined with a Diamond Peel. This treatment cleanses, exfoliates, and hydrates, revealing a brighter, smoother complexion.', 800.00, 71, 'Hc01e44fc79ed4de7a683fcef8d99cf10p.avif'),
(177, 'Vampire Facial Per Session', 'Experience the rejuvenating Vampire Facial, which uses your own platelet-rich plasma (PRP) to promote collagen production and skin renewal. This innovative treatment enhances skin texture, reduces fine lines, and leaves your complexion looking youthful and revitalized.', 2500.00, 71, 'maxresdefault.jpg'),
(178, 'Pollogen Facial Oxygenio', 'Revitalize your skin with the Pollogen Oxygenio Facial, a cutting-edge treatment that combines oxygenation and radiofrequency technology. This facial deeply cleanses, hydrates, and stimulates collagen production, leaving your skin refreshed, smooth, and glowing.', 2500.00, 71, 'maxresdefault.jpg'),
(179, 'Warts  Removal', 'Safely eliminate warts with our professional Warts Removal treatment. Utilizing advanced techniques, this procedure effectively removes warts while minimizing discomfort, leaving your skin clear and smooth', 699.00, 71, 'wart-removal-1.jpg'),
(180, 'Milia Removal', 'Experience effective Milia Removal, a gentle treatment designed to eliminate those small, stubborn white bumps on the skin. Our skilled professionals use safe techniques to ensure smooth, clear skin without scarring', 350.00, 71, 'Milia-Removal.jpg'),
(181, 'Cold Hammer', 'Enjoy the soothing benefits of the Cold Hammer treatment, designed to calm and rejuvenate the skin. This technique uses cold therapy to reduce inflammation, minimize redness, and tighten pores, leaving your complexion refreshed and revitalized.', 100.00, 74, 'Hot-Cold-Hammer-Image.jpg'),
(182, 'Ultrasonic', 'Experience the Ultrasonic treatment, which utilizes high-frequency sound waves to deeply cleanse and exfoliate the skin. This non-invasive procedure removes dead skin cells, unclogs pores, and stimulates collagen production, resulting in a smoother, healthier complexion', 350.00, 74, 'Ultrasonic-Facial-San-Antonio.jpg'),
(183, 'RF(Radio  Frequency)', '\"Experience RF (Radio Frequency) therapy, a non-invasive treatment that stimulates collagen production and tightens the skin. This procedure improves elasticity, reduces wrinkles, and promotes a youthful appearance, leaving your skin firmer and rejuvenated.', 150.00, 74, 'young-woman-receiving-electric-rf-lifting-facial-massage-at-beauty-spa-with-electroporation-equipment-2EA3WGY.jpg'),
(184, 'RF(Radio  Frequency  Per Session)', '\"Experience RF (Radio Frequency) therapy, a non-invasive treatment that stimulates collagen production and tightens the skin. This procedure improves elasticity, reduces wrinkles, and promotes a youthful appearance, leaving your skin firmer and rejuvenated.\"', 2600.00, 74, 'young-woman-receiving-electric-rf-lifting-facial-massage-at-beauty-spa-with-electroporation-equipment-2EA3WGY.jpg'),
(185, 'Oxygen Infusion', 'Revitalize your skin with Oxygen Infusion, a luxurious treatment that delivers oxygen and nourishing serums directly into the skin. This non-invasive procedure hydrates, brightens, and rejuvenates your complexion, leaving you with a refreshed, glowing appearance', 350.00, 74, 'oxygen-facial.jpg'),
(186, 'Carbon  Lesser', 'Experience Carbon Lesser treatment, which combines carbon gel and laser technology to deeply cleanse and exfoliate, targeting impurities and enhancing skin texture for a clear, smooth complexion.\"', 1500.00, 74, 'sdbotox_carbon_laser_facial_01.jpg'),
(187, 'Led Mask', '\"Revitalize your skin with a LED Mask treatment that uses light therapy to promote healing and reduce inflammation, leaving your complexion radiant and refreshed.\"', 200.00, 74, 'light-therapy-mask.webp'),
(188, 'Gluta with Vitamin C', '\"Brighten your skin with the Gluta and Vitamin C treatment, combining glutathione and vitamin C for a radiant, even complexion.\"', 1500.00, 73, 'medworld-clinic-Glutathione-IV-Therapy.jpg'),
(189, 'Gluta with  Multivitamins', 'Revitalize your skin with the Gluta with Multivitamins treatment, combining glutathione and essential vitamins to brighten and nourish for a healthy, glowing complexion.', 1800.00, 73, 'Gluta-Tabs-1.webp'),
(190, 'Gluta with  Multivitamins Plus Vitamin C and Anti Aging collagen', '\"Revitalize your skin with Gluta, Multivitamins, Vitamin C, and Anti-Aging Collagen for a brighter, nourished, and youthful complexion.\"', 2000.00, 73, 'Gluta-Tabs-1.webp'),
(191, 'Multi Vitamins', 'Boost your skin’s health with a Multivitamin treatment, delivering essential nutrients for a radiant and revitalized complexion.', 1000.00, 73, 'Gluta-Tabs-1.webp'),
(192, 'Armpit Hair Removal', '\"Achieve smooth, hair-free underarms with our Armpit Hair Removal treatment, providing a safe and effective solution for long-lasting results.\"', 1000.00, 75, 'Benefits-of-Laser-Hair-Removal-for-Underarm.jpg'),
(193, 'Tattoo Removal (Per Session)', '\"Remove unwanted tattoos effectively with our Tattoo Removal treatment, offered per session for gradual and safe fading.\"', 600.00, 75, 'shutterstock_1092072329-scaled.webp'),
(194, 'Mustached  Hair Removal( Per Session)', '\"Achieve smooth results with our Mustache Hair Removal treatment, offered per session for effective and lasting hair removal.\"', 500.00, 75, 'male-depilation-laser-hair-removal-beard-mustache-procedure-treatment-salon-health-beauty-concept-male-depilation-laser-131342235.webp'),
(196, 'Legs Hair removal (per Session)', '\"Experience smooth, hair-free legs with our Legs Hair Removal treatment, offered per session for effective and lasting results.\"', 1500.00, 75, 'woman-spa-getting-leg-waxed-hair-removal-young-legs-34447089.webp');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `description`, `image`) VALUES
(70, 'Massage Services', 'A massage in a spa is a therapeutic treatment where trained professionals apply pressure and manipulate the muscles, tissues, and joints of the body to promote relaxation, relieve tension, and improve circulation. Different techniques are used to target specific areas or provide full-body relief, offering both physical and mental health benefits. Common types include Swedish, deep tissue, and hot stone massages, each tailored to meet individual needs for wellness and rejuvenation.', 'massage.jpg'),
(71, 'Facial Services', 'Facial services in a spa are skin treatments designed to cleanse, exfoliate, and nourish the face, promoting a clear, well-hydrated complexion. These treatments typically include a combination of cleansing, steaming, exfoliation, extraction, and moisturizing, as well as specialized masks and serums tailored to the client\'s skin type. Facials help improve skin texture, reduce blemishes or signs of aging, and provide relaxation while enhancing overall skin health. Common types of facials include deep cleansing, anti-aging, and hydrating facials.', 'facial service.jpg'),
(72, 'Foot Services', 'Foot services in a spa focus on pampering and treating the feet, promoting relaxation, and improving overall foot health. These services often include foot massages, exfoliation, moisturizing treatments, and nail care (pedicures). Foot massages help relieve tension, improve circulation, and target pressure points for a therapeutic effect. Pedicures may involve trimming, shaping, and polishing the nails, as well as removing calluses and softening the skin. Foot treatments are not only relaxing but can also help prevent common foot issues like dryness, cracked heels, and discomfort.', 'foot massage.jpg'),
(73, 'Gluta Drip', 'Gluta drip, also known as glutathione IV therapy, is a wellness treatment offered in some spas and clinics where glutathione, a powerful antioxidant, is administered intravenously. Glutathione helps detoxify the body, boost the immune system, and promote skin brightening by reducing melanin production. It\'s popular for its potential benefits in skin lightening, anti-aging, and overall wellness by protecting cells from oxidative stress and improving liver function. Regular sessions are often recommended to achieve desired results.', 'gluta.jpg'),
(74, 'Add Ons', 'Add-on services are extra treatments to enhance your spa experience, such as scalp massages, paraffin treatments, exfoliating scrubs, and aromatherapy. These options provide additional relaxation and personalized care during your visit.', 'add on.jpg'),
(75, 'Other Services', 'At Bernadette Wellness Spa, we offer a variety of hair removal services tailored to your needs, including armpit hair removal, tattoo removal, mustache hair removal, arm hair removal, and leg hair removal. Experience our professional care and enhance your natural beauty with smooth, hair-free skin.', 'other.webp');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `secret_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `username`, `password`, `secret_key`) VALUES
(2, 'admin123', '$2y$10$OnRUbRtGD8KWkLwOe.3lK.RLnpmRBvY1bN0ShovIa3JzSe6ejhX7i', '0c1ff2c4a550923caef0a640ab04c2ce');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_positions`
--

CREATE TABLE `tbl_positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_positions`
--

INSERT INTO `tbl_positions` (`position_id`, `position_name`, `description`) VALUES
(20, 'Therapist', 'Therapist Job Responsibilities:\r\nDiagnoses and treats mental health disorders. Creates individualized treatment plans according to patient needs and circumstances. Meets with patients regularly to provide counseling, treatment and adjust treatment plans as necessary. Conducts ongoing assessments of patient progress.'),
(21, 'Massage', 'Massage therapy is the practice of kneading or manipulating a person\'s muscles and other soft-tissue in order to improve their wellbeing or health. It is a form of manual therapy that includes holding, moving, and applying pressure to the muscles, tendons, ligaments and fascia.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staff`
--

CREATE TABLE `tbl_staff` (
  `staff_id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('Available','Not Available') NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_staff`
--

INSERT INTO `tbl_staff` (`staff_id`, `firstname`, `middlename`, `lastname`, `phone`, `address`, `dob`, `gender`, `position_id`, `image_path`, `status`) VALUES
(66, 'jomie', 'cruz', 'salvador', '9469041021', 'Tangub City', '2024-11-12', 'Female', 20, '6606703692128c0b5b0dff3e_How spa software improves staff performance and retention.webp', 'Available'),
(68, 'chandyy', 'cruz', 'agum', '9469041021', 'Lanao', '2024-11-12', 'Male', 21, 'male-spa-receptionist-happy-work-horizontal-42869385.webp', 'Not Available');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp` int(6) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `username`, `firstname`, `middlename`, `lastname`, `phone`, `address`, `age`, `dob`, `gender`, `password`, `created_at`, `otp`, `email`) VALUES
(39, 'Vebs123', 'Vivian', 'Agum', 'Bulahan', '9271551925', ' labinay p-2', 24, '2024-10-05', 'female', '$2y$10$b.Ho5W96r.e.lRUfL./BTuGKg0LC8SaiVo9lGfIi9/NqRdly4rSVC', '2024-10-05 13:19:52', 736967, 'otikagum@gmail.com'),
(43, 'ekoy182', 'Jerico', 'Lapar', '0', '9469041020', 'Purok 4, labinay, Ozamiz City', 23, '2024-10-11', 'male', '$2y$10$qUyUcwP1VJ7kbP0BlHZseO3G4F4Dw737iRnjEdCimcS58YDHhCaU.', '2024-10-11 02:47:35', 511951, 'agumchandy@gmail.com'),
(44, 'Chad11', 'Chad Christian ', 'ambot', '0', '9469041020', 'Sta.Maria, Tangub City', 24, '2024-10-21', 'male', '$2y$10$taBrh.88BJgDpTrl169KYOgU0hrljFuIWd7GyavBBB.vMHWMz.I9y', '2024-10-21 04:59:00', 675138, 'Chad@gmail.com'),
(47, 'Chandy1823', 'Chandy', 'Agum', '0', '9469041020', ' labinay p-2, Ozamiz City', 27, '2024-11-12', 'male', '$2y$10$xUt9NLzVFl5qhEHNAtuw9uZMnF/nO8XAOgTME6/kF2kjTFlDz0fdi', '2024-11-12 00:52:35', 103198, 'chandy.empeynado@gmail.com'),
(48, 'Gen123', 'Gendel', 'Agum', 'Empeynado', '9469041020', '0', 27, '2024-11-12', 'female', '$2y$10$R9gE5ntx5iB/1xkGamqXS.1dYKnp70G7t3vIgj0nZ3Zvp.StF2EMC', '2024-11-12 01:46:04', 114726, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `approved_bookings`
--
ALTER TABLE `approved_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `booked_services`
--
ALTER TABLE `booked_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_positions`
--
ALTER TABLE `tbl_positions`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `approved_bookings`
--
ALTER TABLE `approved_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `booked_services`
--
ALTER TABLE `booked_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_positions`
--
ALTER TABLE `tbl_positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);

--
-- Constraints for table `approved_bookings`
--
ALTER TABLE `approved_bookings`
  ADD CONSTRAINT `approved_bookings_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `approved_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);

--
-- Constraints for table `booked_services`
--
ALTER TABLE `booked_services`
  ADD CONSTRAINT `booked_services_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `booked_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`);

--
-- Constraints for table `tbl_staff`
--
ALTER TABLE `tbl_staff`
  ADD CONSTRAINT `tbl_staff_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `tbl_positions` (`position_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
