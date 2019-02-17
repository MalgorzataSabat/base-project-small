-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Wersja serwera: 5.7.14
-- Wersja PHP: 7.0.10

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Struktura tabeli dla tabeli `acl_role`
--

CREATE TABLE `acl_role` (
  `id_acl_role` int(10) UNSIGNED NOT NULL COMMENT 'Identyfikator',
  `id_parent` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator nadrzędny (rodzic)',
  `order` tinyint(3) UNSIGNED NOT NULL COMMENT 'Kolejność wyświetlania',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Czy rola jest widoczna na listach',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa systemowa roli'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Role aplikacji, np.: użytkownik, administrator';

--
-- Zrzut danych tabeli `acl_role`
--
INSERT INTO `acl_role` (`id_acl_role`, `object`, `order`, `is_visible`, `name`, `name_role`, `id_service`) VALUES
(1, NULL, 1, 1, 'Tester', 'tester', 0),
(2, NULL, 2, 1, 'Developer', 'developer', 0),
(3, NULL, 3, 1, 'Project manager', 'project-manager', 0);



--
-- Struktura tabeli dla tabeli `label`
--

CREATE TABLE `label` (
  `id_label` int(10) UNSIGNED NOT NULL COMMENT 'Identyfikator',
  `label` varchar(255) NOT NULL COMMENT 'Klucz, nazwa',
  `type` varchar(32) NOT NULL COMMENT 'Typ; label – opis; route – definicje routingu',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data dodania',
  `modified_at` timestamp NULL DEFAULT NULL COMMENT 'Data modyfikacji',
  `module` varchar(255) NOT NULL COMMENT 'Moduł',
  `from_import` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Czy dodany z importu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista etykiet aplikacji';


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `label_translation`
--

CREATE TABLE `label_translation` (
  `id_label` int(10) UNSIGNED NOT NULL COMMENT 'Identyfikator',
  `id_language` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identyfikator wersji językowej',
  `value` text NOT NULL COMMENT 'Tłumaczenie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista tłumaczeń dla etykiet';

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `language`
--

CREATE TABLE `language` (
  `id_language` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identyfikator',
  `is_active` tinyint(1) NOT NULL COMMENT 'Czy aktywny',
  `is_main` tinyint(1) NOT NULL COMMENT 'Czy główna wersja językowa',
  `code` varchar(8) NOT NULL COMMENT 'Kod języka',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa',
  `domain` varchar(255) NOT NULL COMMENT 'Domena',
  `locale` varchar(16) NOT NULL COMMENT 'Ustawienia lokalizacji'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista wersji językowych aplikacji';

--
-- Zrzut danych tabeli `language`
--

INSERT INTO `language` (`id_language`, `is_active`, `is_main`, `code`, `name`, `domain`, `locale`) VALUES
(2, 1, 1, 'pl', 'Polski', '', 'pl_PL');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `layout`
--

DROP TABLE IF EXISTS `layout`;
CREATE TABLE IF NOT EXISTS `layout` (
  `id_layout` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `id_service` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator serwisu',
  `type` varchar(255) NOT NULL COMMENT 'Typ layoutu, np dashboard',
  `id_layout_template` smallint(5) UNSIGNED NOT NULL COMMENT 'Identyfikator szablonu',
  `id_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator użytkowika',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa layoutu',
  `data_map` text NOT NULL COMMENT 'Domyślna definicja/ustawienia',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Czy domyślny',
  `is_public` tinyint(1) NOT NULL COMMENT 'Czy jest to układ publiczny',
  PRIMARY KEY (`id_layout`),
  KEY `id_user` (`id_user`),
  KEY `id_layout_template` (`id_layout_template`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='Lista layoutów aplikacji. Wykorzystywana np.: na stronie głównej systemu';

--
-- Zrzut danych tabeli `layout`
--

INSERT INTO `layout` (`id_layout`, `id_service`, `type`, `id_layout_template`, `id_user`, `name`, `data_map`, `is_default`, `is_public`) VALUES
(3, NULL, 'default', 1, NULL, 'layout', '[]', 1, 1),
(4, NULL, 'empty', 4, NULL, 'layout_empty', '[]', 1, 1),
(5, NULL, 'auth', 3, NULL, 'layout', '[]', 1, 1);

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `query`
--

CREATE TABLE `query` (
  `id_query` int(10) UNSIGNED NOT NULL COMMENT 'Identyfikator',
  `id_user` int(10) UNSIGNED NOT NULL COMMENT 'Identyfikator użytkownika do którego jest przypisana kwerenda',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data dodania',
  `is_public` tinyint(1) NOT NULL COMMENT 'Czy kwerenda jest pubiczna/dostępna dla wszystkich',
  `is_default` tinyint(1) NOT NULL COMMENT 'Czy jest to domyślna',
  `order` smallint(6) NOT NULL COMMENT 'Kolejność wyświetlania',
  `title` varchar(255) NOT NULL COMMENT 'Nazwa kwerendy',
  `form` varchar(255) NOT NULL COMMENT 'Formularz kwerendy',
  `data` text NOT NULL COMMENT 'Dane kwerendy dla formularza'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista kwerend użytkowników';


-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `session`
--

CREATE TABLE `session` (
  `id` char(32) NOT NULL COMMENT 'Identyfikator',
  `modified` int(11) NOT NULL COMMENT 'Data modyfikacji',
  `lifetime` int(11) NOT NULL COMMENT 'Czas życia sesji',
  `data` text NOT NULL COMMENT 'Dane sesji',
  `id_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator zalogowanego użytkownika',
  `ip` char(16) NOT NULL COMMENT 'Numer IP',
  `agent` text NOT NULL COMMENT 'Dane przeglądarki',
  `address` text NOT NULL COMMENT 'Obecnie przeglądany adres'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sesje użytkowników';



-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `setting`
--

CREATE TABLE `setting` (
  `id_setting` int(11) NOT NULL COMMENT 'Identyfikator',
  `key` varchar(255) NOT NULL COMMENT 'Klucz',
  `value` text NOT NULL COMMENT 'Wartość'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ustawienia aplikacji';


-- --------------------------------------------------------


--
-- Struktura tabeli dla tabeli `user`
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `hash` char(32) NOT NULL,
  `id_service` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data dodania użytkownika',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Data ostatniej modyfikacji',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Data usunięcia',
  `archived_at` timestamp NULL DEFAULT NULL COMMENT 'Data archiwizacji',
  `role` text NOT NULL COMMENT 'Rola użytkownika',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `name` text NOT NULL COMMENT 'Imię',
  `surname` text NOT NULL COMMENT 'Nazwisko',
  `description` varchar(255) DEFAULT NULL COMMENT 'Opis',
  `testing_systems` varchar(255) DEFAULT NULL,
  `reporting_systems` varchar(255) NOT NULL,
  `knowledge_of_selenium` tinyint(1) DEFAULT NULL,
  `ide_environment` varchar(255) NOT NULL,
  `programming_languages` varchar(255) NOT NULL,
  `knowledge_of_mysql` tinyint(1) DEFAULT NULL,
  `pm_methodologies` varchar(255) NOT NULL,
  `pm_reports_systems` varchar(255) NOT NULL,
  `knowledge_of_scrum` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8 COMMENT='Baza danych użytkowników aplikacji';
--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id_user`, `hash`, `id_service`, `created_at`, `updated_at`, `deleted_at`, `archived_at`, `role`, `email`, `name`, `surname`, `description`, `testing_systems`, `reporting_systems`, `knowledge_of_selenium`, `ide_environment`, `programming_languages`, `knowledge_of_mysql`, `pm_methodologies`, `pm_reports_systems`, `knowledge_of_scrum`) VALUES
(127, '43f4820e1b70262c53c1013d23d4d3ba', 0, '2019-02-12 21:59:32', NULL, '2019-02-15 09:13:48', NULL, '2', 'gosiek0011@tlen.pl', 'Adrian', 'Nowak', 'nowa rekrutacja', '', '', 0, 'zna', 'java', 1, '', '', 0),
(160, '8e860b9fc0dddfc0cab95dfbf6a6d58a', 0, '2019-02-13 18:11:23', '2019-02-17 11:47:13', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Adam', 'Kowalski', 'stanowisko Developera', '', '', 0, 'tak zna', 'java', 1, '', '', 0),
(163, 'fa1e25dd3e6668563200c3bdcfb68f91', 0, '2019-02-13 18:17:33', '2019-02-17 11:52:24', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Adam', 'Jarząbek', 'stanowisko testera', 'jira, jenkns', 'brak', 0, 'tak zna', 'java', 1, '', '', 0),
(167, 'bdb3648bb8f47298643581ff3bf2fd58', 0, '2019-02-13 18:31:23', '2019-02-17 11:52:45', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Małgosia', 'Nowakowska', 'nowe stanowisko', '', '', 0, 'różne zna', 'php, jqeury,html', 1, '', '', 0),
(181, '34fffa1181b78717951a651e2b66ac25', 0, '2019-02-13 19:11:08', '2019-02-17 11:53:23', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Krzysztof', 'Miles', 'full develop', '', '', 0, 'tak zna', '', 1, '', '', 0),
(182, '5fb9d560f2d76f37121700827ca13683', 0, '2019-02-14 12:14:25', '2019-02-17 11:53:39', NULL, NULL, '2', 'gosiek0011@o2.pl', 'Janusz', 'Karyś', 'stanowisko Developera', '', '', 0, 'nie praktykował', 'php, jqeury,html', 1, '', '', 0),
(183, '76a29c85f090c6a564f7a895a34cc628', 0, '2019-02-14 12:17:33', '2019-02-17 11:53:53', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Anna', 'Mielec', 'stanowisko testujące', 'php unity itp', 'brak', 1, '', '', 0, '', '', 0),
(184, 'a6d31ea7077fcb17bb47775aea6d7a88', 0, '2019-02-14 12:18:23', '2019-02-17 11:54:14', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Anna', 'Krzynówek', 'stanowisko testujące 2', 'php unity itp', 'nie zna', 1, '', '', 0, '', '', 0),
(185, '65d9c2519956afc042faeff127f0ce72', 0, '2019-02-14 12:18:59', '2019-02-17 11:54:45', NULL, NULL, '3', 'gosiek0011@tlen.pl', 'Tomasz', 'Kowalski', 'brak', '', '', 0, '', '', 0, 'tak', 'najróżniejsze', 0),
(189, '563e3559cbadc9d74f267c9f54d62f7d', 0, '2019-02-15 08:46:01', '2019-02-17 11:55:06', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Toamsz', 'Majewski', '', '', '', 0, 'tak zna', 'java, c#', 1, '', '', 0),
(190, '227cc375ff969a29004379872152576a', 0, '2019-02-15 08:46:50', '2019-02-17 11:55:27', NULL, NULL, '3', 'gosiek0011@tlen.pl', 'Karol', 'Nowak', '', '', '', 0, '', '', 0, 'najnowsze sytemy', 'report sys', 1),
(191, 'a3dbcc1916d4910fa4222329d58291ed', 0, '2019-02-15 08:50:02', '2019-02-17 11:55:46', NULL, NULL, '3', 'gosiek0011@tlen.pl', 'Henryk', 'Kania', '', 'php unit', '', 1, '', '', 0, 'tak', 'JIRa', 1),
(192, 'f497f77670a05dcf9d95ec066273d039', 0, '2019-02-15 08:53:34', '2019-02-17 11:56:18', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Henryk', 'Malinowski', 'stanowisko junior develop', '', '', 0, 'nie praktykował', 'java', 1, '', '', 0),
(195, '62c2f48a174367f5c4e27d2c665b9ff5', 0, '2019-02-15 09:57:14', '2019-02-17 11:57:01', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Wacław', 'Nowak', 'developer', '', '', 0, 'nie praktykował', 'C++', 1, '', '', 0),
(199, 'c9e83cb916466c06057560a345423f9a', 0, '2019-02-15 11:09:44', '2019-02-17 11:57:19', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Michał', 'Jurczyk', '', '', '', 0, 'rózne srodowiska ide', '', 0, '', '', 0),
(201, 'fa218f30fdf93794278157f1534a4b4f', 0, '2019-02-15 12:37:17', '2019-02-17 11:57:50', NULL, NULL, '2', 'gosiek0011@o2.pl', 'Katarzyna', 'Fidos', 'developer', '', '', 0, 'brak', 'JAVA', 1, '', '', 0),
(202, '6f2a08db2d39519ecb9d5c0cdebd0c55', 0, '2019-02-15 12:38:37', '2019-02-17 11:58:35', NULL, NULL, '2', 'gosiek0011@o2.pl', 'Piotr', 'Halisiński', 'junior develop', '', '', 0, 'brak', 'c#', 0, '', '', 0),
(203, '916ce9b9763796d62b05af9d7bddf7b0', 0, '2019-02-15 12:39:33', '2019-02-17 12:00:05', NULL, NULL, '2', 'gosiek0011@o2.pl', 'Tomasz', 'Molenda', 'nowe stanowisko', '', '', 0, '-', 'Python', 1, '', '', 0),
(204, 'eb0511d77e6f7f024dfcf1edbb35c0da', 0, '2019-02-15 12:40:15', '2019-02-17 12:00:38', NULL, NULL, '2', 'gosiek0011@o2.pl', 'Anna', 'Jarzyniecka', 'JAVA junior', '', '', 0, '', 'JAVA', 1, '', '', 0),
(208, '56ac702ff8fa8f675c1f276a9d3b8d28', 0, '2019-02-15 13:04:15', '2019-02-17 12:01:24', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Mirek', 'Biruta', 'Full Tester', 'php unit', 'jira', 1, '', '', 0, '', '', 0),
(212, 'f839d0c0c5af360276c357ab231f963a', 0, '2019-02-15 13:12:11', '2019-02-17 12:01:49', NULL, NULL, '3', 'gosiek0011@tlen.pl', 'Marek', 'Miodowicz', 'PM', '', '', 0, '', '', 0, '', '', 0),
(213, '074ad41158e0ff8910728cfc543e4181', 0, '2019-02-15 13:22:02', '2019-02-17 12:02:00', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Adam', 'Mokrzycki', 'nowe stanowisko', '', '', 0, '', '', 0, '', '', 0),
(214, '86f47d0a57722e72219a4e522518a976', 0, '2019-02-15 13:23:11', '2019-02-17 12:02:30', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Tomasz', 'Koniczyński', 'nowe stanowisko', '', '', 0, '', '', 1, '', '', 0),
(215, '259a6fa321494d4ee5b9534e4a93d06d', 0, '2019-02-15 13:25:58', '2019-02-17 12:03:14', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Paweł', 'Jurczyk', 'Junior Tester', 'jira', 'najnowsze', 1, '', '', 0, '', '', 0),
(216, '48f083571065309cd89ec2c5313fb8ac', 0, '2019-02-15 13:27:39', '2019-02-17 12:03:40', NULL, NULL, '2', 'gosiek0011@o2.pl', 'Tomasz', 'Mokrzycki', 'nowe stanowisko', '', '', 0, 'rózne srodowiska ide', 'java, c#', 0, '', '', 0),
(217, '49b7ffc7e51d78b159d94fbb4ee4a430', 0, '2019-02-15 13:29:23', '2019-02-17 12:04:07', NULL, NULL, '3', 'gosiek0011@tlen.pl', 'Adrian', 'Kowalski', 'stanowisko PM IT', '', '', 0, '', '', 0, 'zna', 'brak', 0),
(224, '5957fe96a308b3b367bf23a3a2dd2f33', 0, '2019-02-15 14:13:49', '2019-02-17 12:05:10', NULL, NULL, '3', 'gosiek0011@tlen.pl', 'Anna', 'Kowalska', '', '', '', 0, '', '', 0, '', 'wewn. firmowy', 1),
(225, '739fe63ae20a72fabbbfaed8cfda4178', 0, '2019-02-15 14:16:20', '2019-02-17 12:05:28', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Grzegorz', 'Nowak', '', 'php unit', 'brak', 1, '', '', 0, '', '', 0),
(228, '998aea5175fb605ba752e34f92ed2450', 0, '2019-02-15 14:43:12', '2019-02-17 12:05:48', NULL, NULL, '2', 'gosiek0011@tlen.pl', 'Adam', 'Haliszek', 'nowe stanowisko', '', '', 0, 'nie praktykował', 'php, jqeury,html', 1, '', '', 0),
(229, 'ca3726df460f33cdddf1b375d119dc13', 0, '2019-02-15 14:45:18', '2019-02-17 12:06:22', NULL, NULL, '1', 'gosiek0011@tlen.pl', 'Halina', 'Malec', 'stanowisko testera', 'php unit', 'różne', 1, '', '', 0, '', '', 0);


-- --------------------------------------------------------


--
-- Indexes for table `acl_role`
--
ALTER TABLE `acl_role`
  ADD PRIMARY KEY (`id_acl_role`),
  ADD KEY `name` (`name`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Indexes for table `label`
--
ALTER TABLE `label`
  ADD PRIMARY KEY (`id_label`);

--
-- Indexes for table `label_translation`
--
ALTER TABLE `label_translation`
  ADD PRIMARY KEY (`id_label`,`id_language`),
  ADD KEY `id_language` (`id_language`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id_language`);

--
-- Indexes for table `layout`
--
ALTER TABLE `layout`
  ADD PRIMARY KEY (`id_layout`),
  ADD UNIQUE KEY `key` (`key`);


--
-- Indexes for table `query`
--
ALTER TABLE `query`
  ADD PRIMARY KEY (`id_query`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id_setting`);


--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_profile_image` (`id_profile_image`),
  ADD KEY `id_language` (`id_language`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `acl_resource`
--
--
-- AUTO_INCREMENT dla tabeli `acl_role`
--
ALTER TABLE `acl_role`
  MODIFY `id_acl_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=5;
--
--
-- AUTO_INCREMENT dla tabeli `label`
--
ALTER TABLE `label`
  MODIFY `id_label` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=5201;
--
-- AUTO_INCREMENT dla tabeli `language`
--
ALTER TABLE `language`
  MODIFY `id_language` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `layout`
--
ALTER TABLE `layout`
  MODIFY `id_layout` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT dla tabeli `query`
--
ALTER TABLE `query`
  MODIFY `id_query` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `setting`
--
ALTER TABLE `setting`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator', AUTO_INCREMENT=33;


--
-- Ograniczenia dla tabeli `label_translation`
--
ALTER TABLE `label_translation`
  ADD CONSTRAINT `label_translation_ibfk_1` FOREIGN KEY (`id_label`) REFERENCES `label` (`id_label`) ON DELETE CASCADE,
  ADD CONSTRAINT `label_translation_ibfk_2` FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `query`
--
ALTER TABLE `query`
  ADD CONSTRAINT `query_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL;

--
-- Ograniczenia dla tabeli `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_profile_image`) REFERENCES `image` (`id_image`) ON DELETE SET NULL;

--
-- Ograniczenia dla tabeli `user_confirm_request`
--
ALTER TABLE `user_confirm_request`
  ADD CONSTRAINT `user_confirm_request_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `user_email`
--
ALTER TABLE `user_email`
  ADD CONSTRAINT `user_email_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
