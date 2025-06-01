-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jun 01, 2025 at 09:36 AM
-- Server version: 8.4.5
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anishelf`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id_genre` int NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id_genre`, `genre_name`) VALUES
(1, 'romance'),
(2, 'drama'),
(3, 'action'),
(4, 'comedy'),
(5, 'fantasy');

-- --------------------------------------------------------

--
-- Table structure for table `relation_types`
--

CREATE TABLE `relation_types` (
  `id_relation_type` int NOT NULL,
  `type_key` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `relation_types`
--

INSERT INTO `relation_types` (`id_relation_type`, `type_key`) VALUES
(4, 'adaptation'),
(2, 'prequel'),
(1, 'sequel'),
(3, 'side_story');

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `id_series` int NOT NULL,
  `series_name` varchar(69) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `series`
--

INSERT INTO `series` (`id_series`, `series_name`) VALUES
(1, 'My Dress-Up Darling'),
(2, 'Chunibyo'),
(3, 'Demon Slayer'),
(4, 'Frieren'),
(5, 'I want to eat your pancreas'),
(6, 'Naruto');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id_status` int NOT NULL,
  `status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id_status`, `status_name`) VALUES
(1, 'airing'),
(2, 'finished'),
(3, 'not yet aired');

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE `titles` (
  `id_title` int NOT NULL,
  `en_name` varchar(100) NOT NULL,
  `jp_name` varchar(100) NOT NULL,
  `synopsis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `id_type` int NOT NULL,
  `id_status` int NOT NULL,
  `episodes` int NOT NULL,
  `id_series` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `titles`
--

INSERT INTO `titles` (`id_title`, `en_name`, `jp_name`, `synopsis`, `image`, `id_type`, `id_status`, `episodes`, `id_series`) VALUES
(1, 'Love, Chunibyo and Other Delusions!', 'Chuunibyou demo Koi ga Shitai!', 'Yuuta Togashi is a high school freshman who chose a new school far away from his middle school, due to his mortifying past there. Yuuta was what people would classify as a chuunibyou—a \"disease\" that causes people to fantasize about themselves and their surroundings—with him using the alias of \"Dark Flame Master.\" He enters high school looking to start anew or, at least, that is what he hoped for.\r\n\r\nEverything had been going smoothly, and Yuuta was relishing the feeling of a normal high school life until he met Rikka Takanashi—who despite being a high schooler, is still a chuunibyou—subsequently crumbling Yuuta\'s short-lived regular high school experience. It would seem that his chuunibyou past is not leaving him just yet.\r\n\r\nChuunibyou demo Koi ga Shitai! follows Yuuta and Rikka\'s relationship as they experience an eccentric high school journey, with chuunibyou constantly haunting Yuuta.', 'chuunibyo_ln.jpg', 4, 2, 4, 2),
(2, 'My Dress-Up Darling', 'Sono Bisque Doll wa Koi wo Suru', 'High school student Wakana Gojou spends his days perfecting the art of making hina dolls, hoping to eventually reach his grandfather\'s level of expertise. While his fellow teenagers busy themselves with pop culture, Gojou finds bliss in sewing clothes for his dolls. Nonetheless, he goes to great lengths to keep his unique hobby a secret, as he believes that he would be ridiculed were it revealed.\n\nEnter Marin Kitagawa, an extraordinarily pretty girl whose confidence and poise are in stark contrast to Gojou\'s meekness. It would defy common sense for the friendless Gojou to mix with the likes of Kitagawa, who is always surrounded by her peers. However, the unimaginable happens when Kitagawa discovers Gojou\'s prowess with a sewing machine and brightly confesses to him about her own hobby: cosplay. Because her sewing skills are pitiable, she decides to enlist his help.\n\nAs Gojou and Kitagawa work together on one cosplay outfit after another, they cannot help but grow close—even though their lives are worlds apart.', 'dress_up_darling.jpg', 1, 2, 12, 1),
(3, 'Demon Slayer', 'Kimetsu no Yaiba', 'Ever since the death of his father, the burden of supporting the family has fallen upon Tanjirou Kamado\'s shoulders. Though living impoverished on a remote mountain, the Kamado family are able to enjoy a relatively peaceful and happy life. One day, Tanjirou decides to go down to the local village to make a little money selling charcoal. On his way back, night falls, forcing Tanjirou to take shelter in the house of a strange man, who warns him of the existence of flesh-eating demons that lurk in the woods at night.\r\n\r\nWhen he finally arrives back home the next day, he is met with a horrifying sight—his whole family has been slaughtered. Worse still, the sole survivor is his sister Nezuko, who has been turned into a bloodthirsty demon. Consumed by rage and hatred, Tanjirou swears to avenge his family and stay by his only remaining sibling. Alongside the mysterious group calling themselves the Demon Slayer Corps, Tanjirou will do whatever it takes to slay the demons and protect the remnants of his beloved sister\'s humanity.\r\n', 'demon_slayer.jpg', 1, 2, 12, 3),
(4, 'My Dress-Up Darling Season 2', 'Sono Bisque Doll wa Koi wo Suru Season 2', 'Second season of Sono Bisque Doll wa Koi wo Suru', 'dress_up_darling_2.jpg', 1, 3, 12, 1),
(5, 'Frieren: Beyond Journey\'s End', 'Sousou no Frieren', 'During their decade-long quest to defeat the Demon King, the members of the hero\'s party—Himmel himself, the priest Heiter, the dwarf warrior Eisen, and the elven mage Frieren—forge bonds through adventures and battles, creating unforgettable precious memories for most of them.\r\n\r\nHowever, the time that Frieren spends with her comrades is equivalent to merely a fraction of her life, which has lasted over a thousand years. When the party disbands after their victory, Frieren casually returns to her \"usual\" routine of collecting spells across the continent. Due to her different sense of time, she seemingly holds no strong feelings toward the experiences she went through.\r\n\r\nAs the years pass, Frieren gradually realizes how her days in the hero\'s party truly impacted her. Witnessing the deaths of two of her former companions, Frieren begins to regret having taken their presence for granted; she vows to better understand humans and create real personal connections. Although the story of that once memorable journey has long ended, a new tale is about to begin.', 'frieren.jpg', 1, 2, 28, 4),
(6, 'Demon Slayer: The Movie: Mugen Train', 'Kimetsu no Yaiba Movie: Mugen Ressha-hen', 'The devastation of the Mugen Train incident still weighs heavily on the members of the Demon Slayer Corps. Despite being given time to recover, life must go on, as the wicked never sleep: a vicious demon is terrorizing the alluring women of the Yoshiwara Entertainment District. The Sound Hashira, Tengen Uzui, and his three wives are on the case. However, when he soon loses contact with his spouses, Tengen fears the worst and enlists the help of Tanjirou Kamado, Zenitsu Agatsuma, and Inosuke Hashibira to infiltrate the district\'s most prominent houses and locate the depraved Upper Rank Demon.', 'mugen_train.jpg', 2, 2, 1, 3),
(7, 'Demon Slayer: Entertainment District Arc', 'Kimetsu no Yaiba: Yuukaku-hen', 'The devastation of the Mugen Train incident still weighs heavily on the members of the Demon Slayer Corps. Despite being given time to recover, life must go on, as the wicked never sleep: a vicious demon is terrorizing the alluring women of the Yoshiwara Entertainment District. The Sound Hashira, Tengen Uzui, and his three wives are on the case. However, when he soon loses contact with his spouses, Tengen fears the worst and enlists the help of Tanjirou Kamado, Zenitsu Agatsuma, and Inosuke Hashibira to infiltrate the district\'s most prominent houses and locate the depraved Upper Rank Demon.', '11111.jpg', 1, 2, 11, 3),
(8, 'Demon Slayer: Swordsmith Village Arc', 'Kimetsu no Yaiba: Katanakaji no Sato-hen', 'For centuries, the Demon Slayer Corps has sacredly kept the location of Swordsmith Village a secret. As the village of the greatest forgers, it provides Demon Slayers with the finest weapons, which allow them to fight night-crawling fiends and ensure the safety of humans. After his sword was chipped and deemed useless, Tanjirou Kamado, along with his precious little sister Nezuko, is escorted to the village to receive a new one.\r\n\r\nMeanwhile, the death of an Upper Rank Demon disturbs the idle order in the demon world. As Tanjirou becomes acquainted with Mist Hashira Muichirou Tokitou and Love Hashira Mitsuri Kanroji, ferocious powers creep from the shadows and threaten to shatter the Demon Slayers\' greatest line of defense.', '135099.jpg', 1, 2, 11, 3),
(9, 'Demon Slayer', 'Kimetsu no Yaiba', 'Tanjirou Kamado lives with his impoverished family on a remote mountain. As the oldest sibling, he took upon the responsibility of ensuring his family\'s livelihood after the death of his father. On a cold winter day, he goes down to the local village in order to sell some charcoal. As dusk falls, he is forced to spend the night in the house of a curious man who cautions him of strange creatures that roam the night: malevolent demons who crave human flesh.\r\n\r\nWhen he finally makes his way home, Tanjirou\'s worst nightmare comes true. His entire family has been brutally slaughtered with the sole exception of his sister Nezuko, who has turned into a flesh-eating demon. Engulfed in hatred and despair, Tanjirou desperately tries to stop Nezuko from attacking other people, setting out on a journey to avenge his family and find a way to turn his beloved sister back into a human.\r\n', '179023.jpg', 3, 2, 23, 3),
(10, 'Demon Slayer Novelize', 'Kimetsu no Yaiba Novelize', 'As long as anyone can remember, there have been rumors of man-eating demons living in the woods. Even in the more enlightened Taishou era, the townspeople never go near there at night. Tanjirou Kamado, a kind boy who has been supporting his family as a coal seller since his father died, quickly learns that the local superstition is more than just rumors when he returns home one day to find his family slaughtered by demons. Only he and his sister Nezuko manage to survive, but she is transformed into a demon. Determined and resolute, he is recruited into the Demon Slayer Corps and his quest to help turn his sister human again and avenge his family\'s deaths begins.\r\n', '285425.jpg', 4, 2, 10, 3),
(11, 'Frieren: Beyond Journey\'s End', 'Sousou no Frieren', 'The Demon King has been defeated, and the victorious hero party returns home before disbanding. The four—mage Frieren, hero Himmel, priest Heiter, and warrior Eisen—reminisce about their decade-long journey as the moment to bid each other farewell arrives. But the passing of time is different for elves, thus Frieren witnesses her companions slowly pass away one by one.\r\n\r\nBefore his death, Heiter manages to foist a young human apprentice called Fern onto Frieren. Driven by the elf\'s passion for collecting a myriad of magic spells, the pair embarks on a seemingly aimless journey, revisiting the places that the heroes of yore had visited. Along their travels, Frieren slowly confronts her regrets of missed opportunities to form deeper bonds with her now-deceased comrades.', '232121.jpg', 3, 1, 8, 4),
(12, 'I Want To Eat Your Pancreas', 'Kimi no Suizou wo Tabetai', 'The aloof protagonist: a bookworm who is deeply detached from the world he resides in. He has no interest in others and is firmly convinced that nobody has any interest in him either. His story begins when he stumbles across a handwritten book, titled Living with Dying. He soon identifies it as a secret diary belonging to his popular, bubbly classmate Sakura Yamauchi. She then confides in him about the pancreatic disease she is suffering from and that her time left is finite. Only her family knows about her terminal illness; not even her best friends are aware. Despite this revelation, he shows zero sympathy for her plight, but caught in the waves of Sakura\'s persistent buoyancy, he eventually concedes to accompanying her for her remaining days.\r\n\r\nAs the pair of polar opposites interact, their connection strengthens, interweaving through their choices made with each passing day. Her apparent nonchalance and unpredictability disrupts the protagonist\'s impassive flow of life, gradually opening his heart as he discovers and embraces the true meaning of living.\r\n', '93291.jpg', 2, 2, 1, 5),
(13, 'Naruto', 'Naruto', 'Moments before Naruto Uzumaki\'s birth, a huge demon known as the Nine-Tailed Fox attacked Konohagakure, the Hidden Leaf Village, and wreaked havoc. In order to put an end to the demon\'s rampage, the leader of the village, the Fourth Hokage, sacrificed his life and sealed the monstrous beast inside the newborn Naruto.\r\n\r\nIn the present, Naruto is a hyperactive and knuckle-headed ninja growing up within Konohagakure. Shunned because of the demon inside him, Naruto struggles to find his place in the village. His one burning desire to become the Hokage and be acknowledged by the villagers who despise him. However, while his goal leads him to unbreakable bonds with lifelong friends, it also lands him in the crosshairs of many deadly foes.', '142503.jpg', 1, 2, 220, 6);

-- --------------------------------------------------------

--
-- Table structure for table `title_genres`
--

CREATE TABLE `title_genres` (
  `id_title` int NOT NULL,
  `id_genre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `title_genres`
--

INSERT INTO `title_genres` (`id_title`, `id_genre`) VALUES
(1, 1),
(2, 1),
(4, 1),
(12, 1),
(12, 2),
(3, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(13, 3),
(1, 4),
(13, 4),
(5, 5),
(6, 5),
(7, 5),
(8, 5),
(9, 5),
(10, 5),
(11, 5),
(13, 5);

-- --------------------------------------------------------

--
-- Table structure for table `title_relations`
--

CREATE TABLE `title_relations` (
  `id_relation` int NOT NULL,
  `id_title` int NOT NULL,
  `id_related_title` int NOT NULL,
  `id_relation_type` int NOT NULL,
  `relation_order` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `title_relations`
--

INSERT INTO `title_relations` (`id_relation`, `id_title`, `id_related_title`, `id_relation_type`, `relation_order`) VALUES
(1, 4, 2, 1, 1),
(2, 6, 3, 1, NULL),
(3, 7, 6, 1, NULL),
(4, 8, 7, 1, NULL),
(5, 9, 3, 4, NULL),
(6, 10, 3, 4, NULL),
(7, 11, 5, 4, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id_type` int NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id_type`, `type_name`) VALUES
(1, 'TV'),
(2, 'movie'),
(3, 'manga'),
(4, 'LN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id_genre`);

--
-- Indexes for table `relation_types`
--
ALTER TABLE `relation_types`
  ADD PRIMARY KEY (`id_relation_type`),
  ADD UNIQUE KEY `type_key` (`type_key`);

--
-- Indexes for table `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`id_series`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id_status`);

--
-- Indexes for table `titles`
--
ALTER TABLE `titles`
  ADD PRIMARY KEY (`id_title`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_status` (`id_status`),
  ADD KEY `id_series` (`id_series`);

--
-- Indexes for table `title_genres`
--
ALTER TABLE `title_genres`
  ADD PRIMARY KEY (`id_title`,`id_genre`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Indexes for table `title_relations`
--
ALTER TABLE `title_relations`
  ADD PRIMARY KEY (`id_relation`),
  ADD KEY `id_title` (`id_title`),
  ADD KEY `id_related_title` (`id_related_title`),
  ADD KEY `id_relation_type` (`id_relation_type`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id_genre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `relation_types`
--
ALTER TABLE `relation_types`
  MODIFY `id_relation_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `series`
--
ALTER TABLE `series`
  MODIFY `id_series` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `titles`
--
ALTER TABLE `titles`
  MODIFY `id_title` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `title_relations`
--
ALTER TABLE `title_relations`
  MODIFY `id_relation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `titles`
--
ALTER TABLE `titles`
  ADD CONSTRAINT `titles_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`),
  ADD CONSTRAINT `titles_ibfk_2` FOREIGN KEY (`id_status`) REFERENCES `statuses` (`id_status`),
  ADD CONSTRAINT `titles_ibfk_3` FOREIGN KEY (`id_series`) REFERENCES `series` (`id_series`);

--
-- Constraints for table `title_genres`
--
ALTER TABLE `title_genres`
  ADD CONSTRAINT `title_genres_ibfk_1` FOREIGN KEY (`id_title`) REFERENCES `titles` (`id_title`) ON DELETE CASCADE,
  ADD CONSTRAINT `title_genres_ibfk_2` FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id_genre`) ON DELETE CASCADE;

--
-- Constraints for table `title_relations`
--
ALTER TABLE `title_relations`
  ADD CONSTRAINT `title_relations_ibfk_1` FOREIGN KEY (`id_title`) REFERENCES `titles` (`id_title`) ON DELETE CASCADE,
  ADD CONSTRAINT `title_relations_ibfk_2` FOREIGN KEY (`id_related_title`) REFERENCES `titles` (`id_title`) ON DELETE CASCADE,
  ADD CONSTRAINT `title_relations_ibfk_3` FOREIGN KEY (`id_relation_type`) REFERENCES `relation_types` (`id_relation_type`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
