-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 22, 2026 at 05:58 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vouch_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account_Linking`
--

CREATE TABLE `Account_Linking` (
  `link_id` int(11) NOT NULL,
  `single_user_id` int(11) NOT NULL,
  `matchmaker_user_id` int(11) DEFAULT NULL,
  `link_code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Account_Linking`
--

INSERT INTO `Account_Linking` (`link_id`, `single_user_id`, `matchmaker_user_id`, `link_code`) VALUES
(54, 37, 40, '21619'),
(55, 38, 39, '63494'),
(57, 41, 42, '52925'),
(58, 43, 44, '12637'),
(59, 45, 46, '34498'),
(60, 47, 48, '03250'),
(61, 49, 50, '52450'),
(71, 51, 52, '75953'),
(72, 53, 54, '22944'),
(73, 55, 56, '14625'),
(75, 57, 58, '56064'),
(76, 59, 60, '76091'),
(77, 61, 62, '10017'),
(78, 63, 64, '19301'),
(79, 65, 66, '33478'),
(82, 67, 68, '48998'),
(83, 69, 70, '24291'),
(85, 71, 72, '67764'),
(86, 73, 74, '50202'),
(87, 75, 76, '21390');

-- --------------------------------------------------------

--
-- Table structure for table `Profiles`
--

CREATE TABLE `Profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `picture_url` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `occupation` varchar(150) DEFAULT NULL,
  `hobbies` varchar(150) DEFAULT NULL,
  `hook` varchar(255) DEFAULT NULL,
  `relationship_to_single` varchar(255) DEFAULT NULL,
  `credentials` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Profiles`
--

INSERT INTO `Profiles` (`profile_id`, `user_id`, `full_name`, `picture_url`, `bio`, `gender`, `birth_year`, `location`, `occupation`, `hobbies`, `hook`, `relationship_to_single`, `credentials`) VALUES
(52, 37, 'Nicole Singleton', 'https://res.cloudinary.com/pssabnrs/image/upload/v1786885651/vouch/profiles/rabbgimffv5yepzwiapy.jpg', 'Must be willing to share dessert and hold my hand in public', 'Queer', 2005, 'Centurion, South Africa', 'UX/UI Student', 'gaming', 'A partner who values personal growth and wants to evolve with me', NULL, NULL),
(54, 38, 'Claire Tucker', 'https://res.cloudinary.com/pssabnrs/image/upload/v1786886809/vouch/profiles/cnpabwwzehnhhxr1ostz.jpg', 'Just looking for someone kind, funny and down to earth to navigate life with', 'Woman', 2006, 'Sodwana, South Africa', 'Scuba Dive Instructor', 'thrifting', 'Kindness, emotional maturity and a genuine smile', NULL, NULL),
(56, 39, 'Nathan Tucker', 'https://res.cloudinary.com/pssabnrs/image/upload/v1786886979/vouch/profiles/xlhdsrktm5kbsm73vqmj.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'Born and raised babbyyyy'),
(58, 40, 'Tayla Lamarque', 'https://res.cloudinary.com/pssabnrs/image/upload/v1786890717/vouch/profiles/whhcumdgzzxkdlxlpkzo.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Friend', 'We\'ve spent our years of Uni together and I know her track record with past partners!!'),
(60, 41, 'Tannah Harms', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787140635/vouch/profiles/wby9zmchmqmqnjs9z77t.jpg', 'Movie night enthusiast who is open to building an actual blanket fort', 'Woman', 2006, 'Benoni, South Africa', 'Film Director', 'cooking', 'A partner in crime who is down to try weird new restaurants with me', NULL, NULL),
(62, 42, 'Nina Lili', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787140941/vouch/profiles/lagwkhswiowbf0qvferu.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'I’ve seen them at their absolute worst and best. I know who they think they want versus who they actually need'),
(64, 43, 'Josceline Perez', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787209985/vouch/profiles/r9swiceogabdegtjcakb.jpg', 'Seeking a best friend first, partner second', 'Woman', 2003, 'Edenvale, South Africa', '3D Designer', 'coffee', 'Curiosity about the world and a passion for whatever it is they love doing', NULL, NULL),
(66, 44, 'Lily Garcia', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787210158/vouch/profiles/kgftzmcr75fs9c1jl76k.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'I’ve known her since birth, how she handles family chaos and what makes her feel loved'),
(68, 45, 'Savannah Alliston', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211763/vouch/profiles/lwbmz3gi710bj7zeqldy.jpg', 'Current hyperfixations: learning how to bake sourdough and finding the best matcha', 'Woman', 2004, 'Centurion, South Africa', 'Freelancer', 'reading', 'I value honesty, good humor, and high emotional intelligence', NULL, NULL),
(70, 46, 'Sarah Alliston', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211846/vouch/profiles/qiczgoyko9qm2o2n0bp1.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'They can\'t fool me'),
(72, 47, 'Celeste Ebling', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787228801/vouch/profiles/hx1z4tuabwdl6fcxnp2o.jpg', 'My love language is sending you 15 reels a day with no context', 'Woman', 2004, 'Pretoria, South Africa', 'Videographer', 'art', 'A person who can make a boring grocery run feel like a fun little outing', NULL, NULL),
(74, 48, 'Esme Rousell', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787228987/vouch/profiles/mgb4zodvyoafezftctjn.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Friend', 'If you can make them laugh harder than i do, you win'),
(76, 49, 'Alex Daulton', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229233/vouch/profiles/x165jmud3nhoikk2aeyy.jpg', 'Looking for someone to hold hands with while the world burns', 'Non-Binary', 2001, 'Pretoria, South Africa', 'Content Creator', 'travel', 'Someone I can have deep 2 a.m. conversations with about everything and nothing', NULL, NULL),
(78, 50, 'Aaron Daulton', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229356/vouch/profiles/ssag8rc4qrlzobooasyl.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'Handing you a absolute gem on a silver platter'),
(80, 51, 'Jess Singh', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229530/vouch/profiles/rve2zqowe2lvkbudmhbn.jpg', 'Unmatched vibes, immaculate aux control, slightly unhinged humor', 'Non-Binary', 2002, 'Benoni, South Africa', 'IT Support Specialist', 'gaming', 'A good communicator who isn’t afraid to talk through things when they get tricky', NULL, NULL),
(82, 52, 'Kendall Metier', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229816/vouch/profiles/pfiv7kkjbb2fwjw2e4wd.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Coworker', 'Their personal PR manager'),
(84, 53, 'Sage Prescoat', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230014/vouch/profiles/xtu9vsvo6lwyoal954wg.jpg', 'Looking for a co-pilot for late night drive-thru runs and bad jokes', 'Queer', 2004, 'Sodwana, South Africa', 'Retail Cashier', 'plants', 'Someone who remembers the small details', NULL, NULL),
(86, 54, 'Emma Prescoat', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230129/vouch/profiles/qczmibridbyspicljdrr.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'I\'m unhinged, but my sister is super normal and deserves the world'),
(88, 55, 'Jade Garcia', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230419/vouch/profiles/oukdhhyg3t1mvyldoncm.jpg', 'Hot, funny, emotionally available (after my coffee)', 'Queer', 2005, 'Cape Town, South Africa', 'Civil Engineer', 'fitness', 'Consistent communication and someone who means what they say', NULL, NULL),
(90, 56, 'Haeun Lee', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230527/vouch/profiles/fm1sa3ay5adjs669qvdm.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'If you\'re not going to match their effort, respectfully step aside'),
(92, 57, 'Rory Locklear', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787244918/vouch/profiles/hs5zxxcpiuqghhxbzikz.jpg', 'Huge win if you like good banter and late night food runs', 'Non-Binary', 2003, 'Midrand, South Africa', '3D Designer', 'thrifting', 'A great hugger who feels like home after a long week', NULL, NULL),
(94, 58, 'Jennie Smith', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787245262/vouch/profiles/o00oeocuyyute0qt2ktn.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Friend', 'Serious inquiries only'),
(96, 59, 'Riley Keller', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311053/vouch/profiles/apkkmsdfg1d5ulx7dlal.jpg', 'Big fan of long walks that accidentally turn into 6 miles and having a solid rotation of 3 good podcasts', 'Non-Binary', 2003, 'Johannesburg, South Africa', 'Brand Strategist', 'thrifting', 'Good energy, high emotional intelligence and someone who appreciates low-stress hangs just as much as a night out', NULL, NULL),
(98, 60, 'Rachel Keller', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311274/vouch/profiles/f2gwnb36ngijxlmtv4uz.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'I\'m doing charity work for my sibling because their taste in women is currently in the trenches'),
(100, 61, 'Felix Pierce', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311493/vouch/profiles/lsxk30ejbplc44j7mnvz.jpg', 'I like clear plans, high effort conversations and people who actually mean what they say', 'Queer', 2004, 'Durban, South Africa', 'UX/UI Designer', 'reading', 'Someone who is secure, funny without trying too hard and ready for a healthy relationship', NULL, NULL),
(102, 62, 'Liam Wilson', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311733/vouch/profiles/hifjntdqatpsovoayhpx.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Other', 'I live with them I can personally verify their habits and red/green flags'),
(104, 63, 'Clara Hassan', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312008/vouch/profiles/jx13jhmb4mgqkvylkekd.jpg', 'Mostly spent my week learning a niche hobby I’ll drop in a month and pretending I\'m going to the gym', 'Queer', 2005, 'Centurion, South Africa', 'Aspiring Musician', 'plants', 'Someone who doesn\'t take themselves too seriously and is always down for a spontaneous late night food run', NULL, NULL),
(106, 64, 'Grace Adams', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312126/vouch/profiles/iiczedcmkkzpbq5lsczk.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'I\'ve held their phone hostage to prevent bad decisions. I am taking over the vetting process permanently'),
(108, 65, 'Olivia Tran', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312343/vouch/profiles/afrycyocwyxeymeyjfkm.jpg', 'I’m the friend who remembers everyone’s order and knows all the best local spots', 'Woman', 2006, 'Durban, South Africa', 'Software Developer', 'art', 'Parallel play compatibility, sitting in the same room doing our own thing without it feeling weird', NULL, NULL),
(110, 66, 'Evelyn Nguyen', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312470/vouch/profiles/m8hgr13uxkkx5o7dl8wb.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'I survived every chaotic era and know their true red & green flags better than their therapist'),
(112, 67, 'Hazel Jackson', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325070/vouch/profiles/pyciwex0qqd2ixxlmsrj.jpg', 'A good listener with an open mind, strong opinions on music, and the kind of chill energy that makes sitting in silence feel totally comfortable', 'Woman', 2006, 'Johannesburg, South Africa', 'Graphic Designer', 'cooking', 'Currently on a mission to find the best local pastries and spending way too much money on books I fully intend to read eventually', NULL, NULL),
(114, 68, 'Lina Harrison', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325144/vouch/profiles/swolwp0purjpkdo60byx.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'I’ve heard the debriefs for every single date they’ve been on. I know their exact icks and dealbreakers'),
(116, 69, 'Ivy Anant', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325358/vouch/profiles/yv2rmvchj8bo8yj2b7y3.jpg', 'Someone grounded and self aware who actually knows what they want', 'Woman', 2006, 'Pretoria, South Africa', 'Content Creator', 'music', 'Terminally aware of internet culture but I promise I’m a normal human being in person', NULL, NULL),
(118, 70, 'Amelia Lawan', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325472/vouch/profiles/feq12kakuygjxullueug.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'Invested in their happiness, mostly so they stop stealing my clothes and start annoying someone else'),
(120, 71, 'Haewon Jung', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325673/vouch/profiles/bnnjmodcdoi7kolhsrj9.jpg', 'My friends say I’m the \'reliable one,\' which just means I always carry hand sanitizer and snacks', 'Woman', 2004, 'Cape Town, South Africa', 'Producer', 'reading', 'High emotional intelligence with clear communication. Bonus points if you can make me laugh so hard I lose my composure', NULL, NULL),
(122, 72, 'Haebin Jung', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325829/vouch/profiles/v4pitl1gqarvu0yysyzh.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'I\'m tired of her moaning about bad partners, lemme handle it dawg'),
(124, 73, 'Violet Carter', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787340961/vouch/profiles/ntpwnwchpzophppaj7rm.jpg', 'I am very good at making quick decisions when picking where to eat', 'Woman', 2002, 'Cape Town, South Africa', 'Digital Marketer', 'art', 'A good listener who is always open to sharing each other\'s feelings', NULL, NULL),
(126, 74, 'Stella Mason', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787341342/vouch/profiles/ihkmtsrhb1hqjpouxh6m.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Best Friend', 'Zero ability to hide their weird habits or fake a persona around me'),
(128, 75, 'Adela Evans', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396072/vouch/profiles/qnfhr4jjfaecfcntaiv5.jpg', 'Convincing myself I need another house plant as we speak', 'Woman', 2003, 'Cape Town, South Africa', 'Interior Designer', 'plants', 'Consistency, kindness and someone who means what they say', NULL, NULL),
(130, 76, 'Arabella Evans', 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396149/vouch/profiles/eo8ccbyh9pv7ty5mx8r6.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sibling', 'If you get past my standards, you\'re getting my absolute gem of a sister');

-- --------------------------------------------------------

--
-- Table structure for table `Profile_Photos`
--

CREATE TABLE `Profile_Photos` (
  `photo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `photo_url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Profile_Photos`
--

INSERT INTO `Profile_Photos` (`photo_id`, `user_id`, `photo_url`) VALUES
(106, 37, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786885652/vouch/gallery/xikmneuungzlo3ecve44.jpg'),
(107, 37, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786885655/vouch/gallery/bgjmz0fkfjafilrcbega.jpg'),
(108, 37, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786885657/vouch/gallery/ki5czsvhalaz8ambnqkb.jpg'),
(109, 37, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786885658/vouch/gallery/gfxvv0obdq2smzbrj5om.jpg'),
(110, 38, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786886811/vouch/gallery/npbddkeohbccs8hew1ox.jpg'),
(111, 38, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786886813/vouch/gallery/onbhwdf4tsoa4n50gxon.jpg'),
(112, 38, 'https://res.cloudinary.com/pssabnrs/image/upload/v1786886815/vouch/gallery/sn2yxa7pyagvtxospoet.jpg'),
(113, 41, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787140638/vouch/gallery/og9ocmpytbzvd00nxf9d.jpg'),
(114, 41, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787140640/vouch/gallery/xlasgzynwmkbqyhncupy.jpg'),
(115, 41, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787140642/vouch/gallery/dcce0ok4dmseercr1dbn.jpg'),
(116, 41, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787140645/vouch/gallery/vodcj9rsstaw5nongbju.jpg'),
(117, 43, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787209988/vouch/gallery/io27ipncvgkssiofe5hg.jpg'),
(118, 43, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787209991/vouch/gallery/wj5ocxdy8ghgt5ziwicr.jpg'),
(119, 43, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787209993/vouch/gallery/m2yxzyoogqdbri7oxysj.jpg'),
(120, 43, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787209995/vouch/gallery/ou3kewo2jkv0qwpftqbu.jpg'),
(121, 43, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787209997/vouch/gallery/fbjkfwb9jblgtl6etqjw.jpg'),
(122, 45, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211765/vouch/gallery/qpdnevvmkjjux08hw5sx.jpg'),
(123, 45, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211767/vouch/gallery/jx602df0zifxhctdj0ur.jpg'),
(124, 45, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211770/vouch/gallery/eqgbnbsnqkm04pwostft.jpg'),
(125, 45, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211772/vouch/gallery/dd0yfgzz3wjvuunfijd4.jpg'),
(126, 45, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787211774/vouch/gallery/rmrwlmdlcrrvpjanegiq.jpg'),
(127, 47, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787228803/vouch/gallery/hd4venveq3asimwoonwu.jpg'),
(128, 47, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787228806/vouch/gallery/go5yhwhggqioxh6sn7gp.jpg'),
(129, 47, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787228808/vouch/gallery/hjdiz2p5fh8nrpt00kyv.jpg'),
(130, 47, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787228810/vouch/gallery/pmixlttd2r01kg8w6fu1.jpg'),
(131, 49, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229236/vouch/gallery/n0k8yq9lkocmelmntx55.jpg'),
(132, 49, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229238/vouch/gallery/hf0hw2phciij7bkaprqz.jpg'),
(133, 49, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229240/vouch/gallery/wbodr88lpolmzgm39vyq.jpg'),
(134, 49, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229243/vouch/gallery/vewg3r7rva8j9rhqv6q9.jpg'),
(135, 51, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229532/vouch/gallery/ip7x5gvmncbkjsnocyfh.jpg'),
(136, 51, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229535/vouch/gallery/wqnnh1hs8kywtnqxbqmt.jpg'),
(137, 51, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229538/vouch/gallery/fyai5l1712qqsjfzwdhv.jpg'),
(138, 51, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787229540/vouch/gallery/s5txo8dvsenttlwl6ljw.jpg'),
(139, 53, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230016/vouch/gallery/bkfmj5bldy7gi8n5pykx.jpg'),
(140, 53, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230018/vouch/gallery/dq5b1dkcutvyhjj3pyp6.jpg'),
(141, 53, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230021/vouch/gallery/pfld7t0rxw41ydofy1ov.jpg'),
(142, 53, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230023/vouch/gallery/hevvspf4ek6djhxpnogd.jpg'),
(143, 55, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230421/vouch/gallery/htnmctzragxercsrxgoe.jpg'),
(144, 55, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230423/vouch/gallery/l8dagl04knfjhqnpynof.jpg'),
(145, 55, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230426/vouch/gallery/raqh6pmwkybctuea7dot.jpg'),
(146, 55, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230428/vouch/gallery/ragshc9u7pexbdlzcfob.jpg'),
(147, 55, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787230430/vouch/gallery/u9hzksbrdqjih1fjsdde.jpg'),
(148, 57, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787244920/vouch/gallery/iap78pttmhiwxkdzjqrq.jpg'),
(149, 57, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787244922/vouch/gallery/b4e3ytuimbogm9szbzmj.jpg'),
(150, 57, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787244924/vouch/gallery/u6pkrqdwwbpsrxhy6kpe.jpg'),
(151, 57, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787244927/vouch/gallery/hqq43l6pllloyzaaflpe.jpg'),
(152, 59, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311055/vouch/gallery/by1em539iwnfkpickpgw.jpg'),
(153, 59, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311058/vouch/gallery/jzf2qdqplcxufkb20jmk.jpg'),
(154, 59, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311061/vouch/gallery/gsbqp2wcthbx4n3mzqdr.jpg'),
(155, 59, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311063/vouch/gallery/kipbx9igt4yher2ojdzv.jpg'),
(156, 59, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311065/vouch/gallery/xgxk0aiiju6ckx9ms2ej.jpg'),
(157, 61, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311495/vouch/gallery/hkcvr8x7v0fdv4zynvz4.jpg'),
(158, 61, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311498/vouch/gallery/iqva55zo4n3cmmgnugsc.jpg'),
(159, 61, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311500/vouch/gallery/dfd3mhqlswljfzakymz9.jpg'),
(160, 61, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311502/vouch/gallery/sonnx74g6d6mkcliba2m.jpg'),
(161, 61, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787311505/vouch/gallery/e7ykhcoxxezmojqag8wc.jpg'),
(162, 63, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312011/vouch/gallery/lrueie5uhu7bdtgywgxs.jpg'),
(163, 63, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312013/vouch/gallery/eauktyh26488cmo4guf6.jpg'),
(164, 63, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312015/vouch/gallery/rsxylvrsoph54ikcb0sp.jpg'),
(165, 63, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312018/vouch/gallery/gbrmzlmlb4wljxys78vf.jpg'),
(166, 63, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312020/vouch/gallery/mjyt1evhxttpeuhlmree.jpg'),
(167, 65, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312346/vouch/gallery/syxq73zmsqfsbcboj1rn.jpg'),
(168, 65, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312348/vouch/gallery/zcevsckcpbsdibwtqzcl.jpg'),
(169, 65, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312350/vouch/gallery/tsfzqle1htocbomu0bhx.jpg'),
(170, 65, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312353/vouch/gallery/jbxjodjskqvvhuedhpui.jpg'),
(171, 65, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787312355/vouch/gallery/tmveu7rgxzlwmeynkoit.jpg'),
(172, 67, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325072/vouch/gallery/kaa90frpsun6cpaiu5hl.jpg'),
(173, 67, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325074/vouch/gallery/e7glrbymswi9a2qfkc59.jpg'),
(174, 67, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325076/vouch/gallery/usyrgbwbb4g9pndplbdc.jpg'),
(175, 67, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325078/vouch/gallery/wja3wwov9eynkl0vxykk.jpg'),
(176, 67, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325080/vouch/gallery/itmwxlpvsjmxkcntwtya.jpg'),
(177, 69, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325360/vouch/gallery/bc5dfmzzvo1otz6gafcj.jpg'),
(178, 69, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325362/vouch/gallery/d3njyy0rbefgic8sqpnx.jpg'),
(179, 69, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325364/vouch/gallery/thvhd6easmmkiha0bloi.jpg'),
(180, 69, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325367/vouch/gallery/nh0iv1lsut59eqbhw2pi.jpg'),
(181, 69, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325369/vouch/gallery/l445ndxmmpvi5oza5qy6.jpg'),
(182, 71, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325676/vouch/gallery/v6hf7i2ybca3wiulxscy.jpg'),
(183, 71, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325678/vouch/gallery/mxeobqsgdwo5yhufryxz.jpg'),
(184, 71, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325680/vouch/gallery/munhhyqjrnaoagjgf2fg.jpg'),
(185, 71, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325683/vouch/gallery/pei30cwt6d7cwfmepevm.jpg'),
(186, 71, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787325685/vouch/gallery/uxhzmgor95t1xrc7zbzb.jpg'),
(187, 73, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787340964/vouch/gallery/ggmgmghgpp59u6oclucd.jpg'),
(188, 73, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787340966/vouch/gallery/swwsjrauiidc4mush8lq.jpg'),
(189, 73, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787340968/vouch/gallery/nrjvpe4vbx7d20tevxzz.jpg'),
(190, 73, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787340970/vouch/gallery/b8nxfq8oakzkawiwaxdz.jpg'),
(191, 75, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396076/vouch/gallery/dsfkp34fueuamilm60mv.jpg'),
(192, 75, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396078/vouch/gallery/aodh4pgrs2tg8whtccmg.jpg'),
(193, 75, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396080/vouch/gallery/qgyisz61v0llojgpmsbu.jpg'),
(194, 75, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396082/vouch/gallery/bv3mabwq0vm91n8kk9gb.jpg'),
(195, 75, 'https://res.cloudinary.com/pssabnrs/image/upload/v1787396085/vouch/gallery/szxqydyuigbrxohdnhk3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `Singles_Preferences`
--

CREATE TABLE `Singles_Preferences` (
  `preference_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_gender` varchar(50) DEFAULT NULL,
  `target_ages` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Singles_Preferences`
--

INSERT INTO `Singles_Preferences` (`preference_id`, `user_id`, `target_gender`, `target_ages`) VALUES
(30, 37, 'All Sapphics', '18-25'),
(31, 38, 'Women', '18-25'),
(32, 41, 'Women', '18-25'),
(33, 43, 'Women', '18-25'),
(34, 45, 'All Sapphics', '18-25'),
(35, 47, 'Women', '18-25'),
(36, 49, 'All Sapphics', '18-25'),
(37, 51, 'Women', '18-25'),
(38, 53, 'All Sapphics', '18-25'),
(39, 55, 'Women', '18-25'),
(40, 57, 'All Sapphics', '18-25'),
(41, 59, 'Women', '18-25'),
(42, 61, 'Trans Women', '18-25'),
(43, 63, 'Women', '18-25'),
(44, 65, 'All Sapphics', '18-25'),
(45, 67, 'All Sapphics', '18-25'),
(46, 69, 'All Sapphics', '18-25'),
(47, 71, 'Non-Binary Sapphics', '18-25'),
(48, 73, 'All Sapphics', '18-25'),
(49, 75, 'All Sapphics', '18-25');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `email`, `password`, `user_role`) VALUES
(37, 'nicole@gmail.com', '$2y$10$r2sQMlJDZWs.kDZTdpmR9OQm0t8jlcWPigHYkGTf7w87LqmZEuWaS', 'single'),
(38, 'claire@gmail.com', '$2y$10$BBfKuLMkLeAYKQ5pWJMXfOpyoz2KWDr0krtFYOARadCoWT7IAh2qC', 'single'),
(39, 'nathan@gmail.com', '$2y$10$W3FGn29V2xXyAPZMjuDGg.OtjdwWPoC6BxDnCvxH7zR.TVZNycl52', 'matchmaker'),
(40, 'tayla@gmail.com', '$2y$10$a.37v7.3i8WsGm.GIqRBses4Ai21yz64XCX1tocnrPaGCU5pQSe9S', 'matchmaker'),
(41, 'tannah@gmail.com', '$2y$10$HSaQD6g9l7rwc3kwdezJQeqpCPedgwzfMTHU6lpZVhrwDvfPDXh7u', 'single'),
(42, 'nina@gmail.com', '$2y$10$MSH2yBontGxsRPr.FOws4OkTIqHS2xcI93u2nqAftYB1/uVdB9RyO', 'matchmaker'),
(43, 'josceline@gmail.com', '$2y$10$wZTOonK287DyZ4o3M6TYUeskf2gZad8fUlpWXNd6ZYyn84M0fghli', 'single'),
(44, 'lily@gmail.com', '$2y$10$AYHj9guabgYBBKDQRTvOmut54jK3L/awnXO5TXGcaT/iQdv1qr7FS', 'matchmaker'),
(45, 'savannah@gmail.com', '$2y$10$CQ4tqXkTz0oq6BzMOJ9HGeru.mdpvktXVcv.J9gJHsCFK79Z2J.ue', 'single'),
(46, 'sarah@gmail.com', '$2y$10$0n5Me43nCG3KmIFn7TxSkekY9lqt6ySw4.WGTuMRaYfAPeRQkZIga', 'matchmaker'),
(47, 'celeste@gmail.com', '$2y$10$h01zlNmxQh2JU0Nup7SfTOje3Y5yb95l0BFzdVtwHNcKxRuKasYo2', 'single'),
(48, 'esme@gmail.com', '$2y$10$zFbciWwVLwHPH2dKcyuhh.CVJFPYiZh/BRqZrq2tlKKPUwg96s1Vu', 'matchmaker'),
(49, 'alex@gmail.com', '$2y$10$SuYXohXxwtUICf6VO.0GLekmZ6BFN8MWxoTXKDn6Hg5Er8DU3Ajny', 'single'),
(50, 'aaron@gmail.com', '$2y$10$FUiwE5w5ZCI6aAUn3ESX6utNKT5bFTxNP6.YgDKuXcWWYd2hVWjIa', 'matchmaker'),
(51, 'jess@gmail.com', '$2y$10$8GS9tcKXFwO9TmidFd9.lehc0HToHBubR2xMbwAgtr.XjjRrPn4KG', 'single'),
(52, 'kendall@gmail.com', '$2y$10$IEiTlV2.JBkVF8xsZaiPLedz05uehPGRIfO0c5yCF96Y.cnHRtfL2', 'matchmaker'),
(53, 'sage@gmail.com', '$2y$10$EXjTzqvtS1EtYdgOKBRiLeX8E74puZ0VvTJMBnA.XLpq5R1Opdieu', 'single'),
(54, 'emma@gmail.com', '$2y$10$rX.JHhIprUh/UxXFZgosZeflNbSIqpKzjYVNIP1LokgxDslqPYR/2', 'matchmaker'),
(55, 'jade@gmail.com', '$2y$10$24QNhbtBU80iP5kV4f7FreaGu3yQUkZ3K6hjaocNhTmb37W/mjf.C', 'single'),
(56, 'haeun@gmail.com', '$2y$10$FcjkX0QvOi2cagiOYcjau.2Ibpn/10GHycIbLGx1ZV0EXPsuqQem6', 'matchmaker'),
(57, 'rory@gmail.com', '$2y$10$SSTEgFMShjZ4TSutxGqROOC5XM/olV1DkF1OAsOiY5n7HMu7n9NL6', 'single'),
(58, 'jennie@gmail.com', '$2y$10$WUJaWfKHBKMdr29g6AnspullR6CRri/hlTeVn1fdXIDTLhgw7Oqxu', 'matchmaker'),
(59, 'riley@gmail.com', '$2y$10$xi9Ml5/UC4HGdG3irZfJoOqFKilu8IHr2bdj0ajEKlheq9X36zdYa', 'single'),
(60, 'rachel@gmail.com', '$2y$10$o3CU6KLpNtQiDtU4rmugG.cpHh./xqPYvDtDYoe8tgUC6/mI5DJoO', 'matchmaker'),
(61, 'felix@gmail.com', '$2y$10$swzJ0znj9BQDY8BxYnNnheROvtVI45CRuixp.G8jovAfqt815Fc.2', 'single'),
(62, 'liam@gmail.com', '$2y$10$itWzq1iZTEwbr43zwWLBFeeDyk8ygVZwRQ7GXlSXP13w0QbdmNRSu', 'matchmaker'),
(63, 'clara@gmail.com', '$2y$10$4sii6x/pWAiji45lfAoI6.Z.vyGOp/nhJTcQApZ9aLIkL1b77eDBW', 'single'),
(64, 'grace@gmail.com', '$2y$10$TdVmHxxKSENKQyaLsLFcX.Q0NNu7ofvMnu.hn4bvri4zP4fOr.Equ', 'matchmaker'),
(65, 'olivia@gmail.com', '$2y$10$bW.VXYrzsiEc8qF4dBEb9.W1wE.Huqs/eQBoiijGkhwoWfvfE1kOq', 'single'),
(66, 'evelyn@gmail.com', '$2y$10$gkbM/V4/Bj2TcMr1./e/repGt6Ed2svIfQF1NByaLDa1Vu423XM7e', 'matchmaker'),
(67, 'hazel@gmail.com', '$2y$10$m3WLLAneD4rJ1yHseh7Z3u157X0ofzOmWTInXNlWukZKqRbzw4.JW', 'single'),
(68, 'lina@gmail.com', '$2y$10$s6y.Tk2gaf5uaD4p7KFXh.W8zfVXTNPoFgm/xlHyh1GJq1HyDlEve', 'matchmaker'),
(69, 'ivy@gmail.com', '$2y$10$mWZ.07aun37sDmd/xQBBsOdfZqlNc8SlDkLJNjdKLv3u/5zP.zuVG', 'single'),
(70, 'amelia@gmail.com', '$2y$10$cD3S1p0RR41sv72WLbQqxOOSVseVL7iKAMdDdSTO/3D2SBnm3HYdi', 'matchmaker'),
(71, 'haewon@gmail.com', '$2y$10$lZ9EsXy9uDqKWBlRdcO1ge1Grp/Cp2uIZU/RNkN9ONthlxrccdFgK', 'single'),
(72, 'haebin@gmail.com', '$2y$10$0svlC7y2hQloXIR8toOFJev7GvZ2ObgxvC9kzh7/Gl9GSIhtKbn7u', 'matchmaker'),
(73, 'violet@gmail.com', '$2y$10$xOOph2FrLcWCmwVcRc62cOuPpFzd/ldNUmZz3AbbHMRaAll6KDery', 'single'),
(74, 'stella@gmail.com', '$2y$10$1sXaLaeBcgxsMZ4j55hpbeLHYoCO1wag8a/MZI4YYSZ7llSTTYuE6', 'matchmaker'),
(75, 'adela@gmail.com', '$2y$10$ZyyPBi.qRhuRw2ZPRYBvrexNnPg6DQrqhlbVQvojANuE3yQh9jcJy', 'single'),
(76, 'arabella@gmail.com', '$2y$10$tYPDb2smrV67ncRXaWu8ZumCpBJqXqgMIWc9mdnl0AqBXHREy8pBK', 'matchmaker');

-- --------------------------------------------------------

--
-- Table structure for table `Vouching`
--

CREATE TABLE `Vouching` (
  `vouching_id` int(11) NOT NULL,
  `sender_matchmaker_id` int(11) NOT NULL,
  `reciever_matchmaker_id` int(11) DEFAULT NULL,
  `requesting_single_id` int(11) NOT NULL,
  `candidate_user_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `matchmaker_note` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Vouching`
--

INSERT INTO `Vouching` (`vouching_id`, `sender_matchmaker_id`, `reciever_matchmaker_id`, `requesting_single_id`, `candidate_user_id`, `status`, `matchmaker_note`, `timestamp`) VALUES
(1, 46, 44, 45, 43, 'accepted', 'She loves coffee too and wants to find a best friend in her partner, I\'m in support!!', '2026-08-20 10:08:16'),
(2, 46, 40, 45, 37, 'pending', NULL, '2026-08-20 12:21:22'),
(3, 50, 48, 49, 47, 'accepted', 'She seems very cute and lovable', '2026-08-20 12:57:26'),
(4, 50, 46, 49, 45, 'accepted', 'She\'s soooo fine dude', '2026-08-20 12:57:33'),
(5, 50, 42, 49, 41, 'rejected', 'Dawg she lives too far :(', '2026-08-20 12:57:36'),
(6, 50, 40, 49, 37, 'accepted', 'I like her she also enjoys gaming hahah', '2026-08-20 12:57:39'),
(7, 50, 44, 49, 43, 'accepted', 'She is so your type', '2026-08-20 13:39:30'),
(8, 50, 39, 49, 38, 'rejected', 'Once again, she lives too far. Long distance doesn\'t work for you', '2026-08-20 13:39:33'),
(9, 50, 54, 49, 53, 'rejected', 'Don\'t play THEY IN SODWANA BUD give up the long distance...', '2026-08-20 15:44:11'),
(10, 50, 52, 49, 51, 'accepted', 'I see the vision, they a smarty pants in IT', '2026-08-20 17:04:48'),
(11, 50, 56, 49, 55, 'accepted', 'They enjoy fitness too which is cool so y\'all can do that together', '2026-08-21 09:54:53'),
(12, 56, NULL, 55, 49, 'pending', NULL, '2026-08-21 09:55:57'),
(13, 66, 54, 65, 53, 'matched', 'They seem nice and cute!!', '2026-08-21 11:44:06'),
(14, 54, NULL, 53, 65, 'matched', 'I think you\'d like her she seems super cool!!', '2026-08-21 11:44:58'),
(15, 54, 44, 53, 43, 'pending', NULL, '2026-08-21 11:48:59'),
(16, 54, 46, 53, 45, 'pending', NULL, '2026-08-21 11:49:11'),
(17, 66, 52, 65, 51, 'rejected', 'She lives quite far queen...', '2026-08-21 13:36:11'),
(18, 66, 62, 65, 61, 'matched', 'They live close and do UI its a match made in heaven ahah', '2026-08-21 13:44:44'),
(19, 66, 39, 65, 38, 'rejected', 'No', '2026-08-21 13:44:52'),
(20, 62, NULL, 61, 65, 'matched', 'Ayy she does dev and y\'all live close this could work out', '2026-08-21 13:45:43'),
(21, 64, 44, 63, 43, 'matched', 'She\'s very pretty, just your type', '2026-08-21 21:02:52'),
(22, 64, 66, 63, 65, 'rejected', 'She lives too far, you hate long distance...', '2026-08-21 21:02:54'),
(23, 64, 72, 63, 71, 'rejected', 'Producer??? You want to nepo baby your way in??', '2026-08-21 21:02:57'),
(24, 64, 70, 63, 69, 'accepted', 'Wow so baddie diva mama house boots', '2026-08-21 21:03:01'),
(25, 44, NULL, 43, 63, 'matched', 'Sooo bonita wow!!', '2026-08-21 21:03:26'),
(26, 70, NULL, 69, 63, 'pending', NULL, '2026-08-21 21:05:21'),
(27, 64, 68, 63, 67, 'pending', NULL, '2026-08-21 21:05:37'),
(30, 76, 52, 75, 51, 'rejected', 'They live too far??', '2026-08-22 11:08:35'),
(31, 76, 66, 75, 65, 'matched', 'She also likes art, you two would get along!', '2026-08-22 11:08:41'),
(32, 66, NULL, 65, 75, 'matched', 'She also like planst, you\'d get along well', '2026-08-22 11:21:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account_Linking`
--
ALTER TABLE `Account_Linking`
  ADD PRIMARY KEY (`link_id`),
  ADD UNIQUE KEY `link_code` (`link_code`),
  ADD KEY `single_user_id` (`single_user_id`),
  ADD KEY `matchmaker_user_id` (`matchmaker_user_id`);

--
-- Indexes for table `Profiles`
--
ALTER TABLE `Profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD UNIQUE KEY `user_id_unique` (`user_id`);

--
-- Indexes for table `Profile_Photos`
--
ALTER TABLE `Profile_Photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `fk_photo_user` (`user_id`);

--
-- Indexes for table `Singles_Preferences`
--
ALTER TABLE `Singles_Preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Vouching`
--
ALTER TABLE `Vouching`
  ADD PRIMARY KEY (`vouching_id`),
  ADD KEY `sender_matchmaker_id` (`sender_matchmaker_id`),
  ADD KEY `reciever_matchmaker_id` (`reciever_matchmaker_id`),
  ADD KEY `requesting_single_id` (`requesting_single_id`),
  ADD KEY `candidate_user_id` (`candidate_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Account_Linking`
--
ALTER TABLE `Account_Linking`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `Profiles`
--
ALTER TABLE `Profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `Profile_Photos`
--
ALTER TABLE `Profile_Photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `Singles_Preferences`
--
ALTER TABLE `Singles_Preferences`
  MODIFY `preference_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `Vouching`
--
ALTER TABLE `Vouching`
  MODIFY `vouching_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Account_Linking`
--
ALTER TABLE `Account_Linking`
  ADD CONSTRAINT `account_linking_ibfk_1` FOREIGN KEY (`single_user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_linking_ibfk_2` FOREIGN KEY (`matchmaker_user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Profiles`
--
ALTER TABLE `Profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Profile_Photos`
--
ALTER TABLE `Profile_Photos`
  ADD CONSTRAINT `fk_photo_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Singles_Preferences`
--
ALTER TABLE `Singles_Preferences`
  ADD CONSTRAINT `singles_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Vouching`
--
ALTER TABLE `Vouching`
  ADD CONSTRAINT `vouching_ibfk_1` FOREIGN KEY (`sender_matchmaker_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vouching_ibfk_2` FOREIGN KEY (`reciever_matchmaker_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vouching_ibfk_3` FOREIGN KEY (`requesting_single_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vouching_ibfk_4` FOREIGN KEY (`candidate_user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
