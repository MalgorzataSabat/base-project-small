-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 22 Lis 2017, 09:40
-- Wersja serwera: 5.7.14
-- Wersja PHP: 5.6.25

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `base-crm`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acl_resource`
--

CREATE TABLE IF NOT EXISTS `acl_resource` (
  `id_acl_resource` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator rekordu',
  `id_parent` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator nadrzędny (rodzic) ',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa systemowa zasobu',
  `text` varchar(255) NOT NULL COMMENT 'Nazwa zasobu',
  `module` varchar(255) DEFAULT NULL COMMENT 'Moduł',
  `desc` text NOT NULL COMMENT 'Dodatkowy opis zasobu',
  PRIMARY KEY (`id_acl_resource`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB AUTO_INCREMENT=54875 DEFAULT CHARSET=utf8 COMMENT='Zasoby aplikacji. Lista zawiera wszystkie elementy do których może zostać nadany/odebrany dostęp';

--
-- Tabela Truncate przed wstawieniem `acl_resource`
--

TRUNCATE TABLE `acl_resource`;
--
-- Zrzut danych tabeli `acl_resource`
--

INSERT INTO `acl_resource` (`id_acl_resource`, `id_parent`, `name`, `text`, `module`, `desc`) VALUES
(54578, 54843, 'default', 'Default', 'default', ''),
(54579, 54578, 'default_error', '', NULL, ''),
(54580, 54579, 'default_error_error', '', NULL, ''),
(54581, 54579, 'default_error_error404', '', NULL, ''),
(54582, 54579, 'default_error_error403', '', NULL, ''),
(54583, 54579, 'default_error_error503', '', NULL, ''),
(54584, 54578, 'default_index', '', NULL, ''),
(54585, 54584, 'default_index_index', '', NULL, ''),
(54586, 54578, 'default_tools', '', NULL, ''),
(54587, 54586, 'default_tools_gus', '', NULL, ''),
(54650, NULL, 'api', 'Api', 'api', ''),
(54706, 54843, 'image', 'Zdjecia', 'image', ''),
(54707, 54706, 'image_cdn', '', NULL, ''),
(54708, 54707, 'image_cdn_main', '', NULL, ''),
(54709, 54707, 'image_cdn_serve', '', NULL, ''),
(54710, 54706, 'image_index', '', NULL, ''),
(54711, 54710, 'image_index_list', '', NULL, ''),
(54712, 54710, 'image_index_upload', '', NULL, ''),
(54713, 54710, 'image_index_order', '', NULL, ''),
(54714, 54710, 'image_index_edit', '', NULL, ''),
(54715, 54710, 'image_index_delete', '', NULL, ''),
(54716, 54710, 'image_index_cropper', '', NULL, ''),
(54733, 54844, 'layout', 'Layouty', 'layout', ''),
(54741, 54871, 'log', 'Logi', 'log', ''),
(54791, NULL, 'tag', 'Tagi', 'tag', ''),
(54831, NULL, 'client', 'Klient', 'client', ''),
(54832, 54831, 'client_read', 'Przeglądanie', 'client', ''),
(54833, 54831, 'client_write', 'Zarządzanie', 'client', ''),
(54834, NULL, 'department', 'Spółki', 'department', ''),
(54835, 54834, 'department_read', 'Przeglądanie', 'department', ''),
(54836, 54834, 'department_write', 'Zarządzanie', 'department', ''),
(54837, NULL, 'person', 'Osoby kontaktowe', 'person', ''),
(54838, 54837, 'person_read', 'Przeglądanie', 'person', ''),
(54839, 54837, 'person_write', 'Zarządzanie', 'person', ''),
(54840, NULL, 'task', 'Zadania', 'task', ''),
(54841, 54840, 'task_read', 'Przeglądanie', 'task', ''),
(54842, 54840, 'task_write', 'Zarządzanie', 'task', ''),
(54843, NULL, 'guest_access', 'Użytkownik niezalogowany', NULL, ''),
(54844, NULL, 'user_access', 'Użytkownik zalogowany', NULL, ''),
(54845, NULL, 'project', 'Projekt', 'project', ''),
(54846, 54845, 'project_read', 'Przeglądanie', 'project', ''),
(54847, 54845, 'project_write', 'Zarządzanie', 'project', ''),
(54848, NULL, 'invoice', 'Faktury', 'invoice', ''),
(54849, 54848, 'invoice_read', 'Przeglądanie', 'invoice', ''),
(54850, 54848, 'invoice_write', 'Zarządzanie', 'invoice', ''),
(54851, NULL, 'quickmail', 'Wysyłka emaili', 'quickmail', ''),
(54852, NULL, 'stage', 'Etapy sprzedaży', 'stage', ''),
(54853, 54852, 'stage_read', 'Przeglądanie', 'stage', ''),
(54854, 54852, 'stage_write', 'Zarządzanie', 'stage', ''),
(54855, 54844, 'note', 'Notatki', 'note', ''),
(54856, 54871, 'dictionary', 'Słownik', 'dictionary', ''),
(54857, NULL, 'template', 'Szablony dokumentów', 'template', ''),
(54858, 54857, 'template_read', 'Przeglądanie', 'template', ''),
(54859, 54857, 'template_write', 'Zarządzanie', 'template', ''),
(54860, 54831, 'client_api', 'Klient Api', 'client', ''),
(54861, NULL, 'document', 'Dokumenty', 'document', ''),
(54862, 54861, 'document_read', 'Przeglądanie', 'document', ''),
(54863, 54861, 'document_write', 'Zarządzanie', 'document', ''),
(54864, 54871, 'industry', 'Branże', 'industry', ''),
(54865, NULL, 'user', 'Użytkownicy', 'user', ''),
(54866, 54865, 'user_read', 'Przeglądanie', 'user', ''),
(54867, 54865, 'user_write', 'Zarządzanie', 'user', ''),
(54868, 54844, 'user_account', 'Konto użytkownika', 'user', ''),
(54869, 54843, 'address', 'Adres', 'address', ''),
(54870, 54843, 'auth_login', 'Logowanie', 'auth', ''),
(54871, NULL, 'setting', 'Administacja', 'admin', ''),
(54872, 54871, 'admin', 'Admin', 'admin', ''),
(54873, 54871, 'auth', 'Auth', 'auth', ''),
(54874, 54844, 'user_access_admin', 'Admin', 'admin', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acl_resource_item`
--

CREATE TABLE IF NOT EXISTS `acl_resource_item` (
  `id_acl_resource_item` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `id_acl_resource` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator zasobu',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa systemowa elementu zasobu,np.: <moduł>_<kontroler>_<akcja>',
  PRIMARY KEY (`id_acl_resource_item`),
  KEY `id_acl_privilege` (`id_acl_resource`)
) ENGINE=InnoDB AUTO_INCREMENT=54448 DEFAULT CHARSET=utf8 COMMENT='Szczegółowe elementy zasobu, np.: poszczególna akcja';

--
-- Tabela Truncate przed wstawieniem `acl_resource_item`
--

TRUNCATE TABLE `acl_resource_item`;
--
-- Zrzut danych tabeli `acl_resource_item`
--

INSERT INTO `acl_resource_item` (`id_acl_resource_item`, `id_acl_resource`, `name`) VALUES
(54175, 54578, 'default'),
(54176, 54579, 'default_error'),
(54177, 54580, 'default_error_error'),
(54178, 54581, 'default_error_error404'),
(54179, 54582, 'default_error_error403'),
(54180, 54583, 'default_error_error503'),
(54181, 54584, 'default_index'),
(54182, 54585, 'default_index_index'),
(54183, 54586, 'default_tools'),
(54184, 54587, 'default_tools_gus'),
(54185, 54869, 'address'),
(54186, 54869, 'address_ajax'),
(54187, 54869, 'address_ajax_get-province-list'),
(54188, 54872, 'admin'),
(54189, 54872, 'admin_cron'),
(54190, 54872, 'admin_cron_index'),
(54191, 54872, 'admin_cron_run'),
(54192, 54872, 'admin_developer'),
(54193, 54872, 'admin_developer_test-list'),
(54194, 54872, 'admin_developer_test-form'),
(54195, 54872, 'admin_developer_index'),
(54196, 54872, 'admin_developer_adminer'),
(54197, 54872, 'admin_developer_start-adminer'),
(54198, 54872, 'admin_developer_create-layouts-from-base'),
(54199, 54872, 'admin_developer_create-layout'),
(54200, 54872, 'admin_email-send'),
(54201, 54872, 'admin_email-send_index'),
(54202, 54872, 'admin_email-send_show'),
(54203, 54872, 'admin_email-send_show-html'),
(54204, 54872, 'admin_email-send_send'),
(54205, 54872, 'admin_email-send_delete'),
(54206, 54872, 'admin_email-send-mass'),
(54207, 54872, 'admin_email-send-mass_delete'),
(54208, 54874, 'admin_index'),
(54209, 54874, 'admin_index_index'),
(54210, 54874, 'admin_index_cms-share-elements'),
(54211, 54872, 'admin_label'),
(54212, 54872, 'admin_label_index'),
(54213, 54872, 'admin_label_new'),
(54214, 54872, 'admin_label_edit'),
(54215, 54872, 'admin_label_delete'),
(54216, 54872, 'admin_label_export'),
(54217, 54872, 'admin_label_import'),
(54218, 54872, 'admin_language'),
(54219, 54872, 'admin_language_index'),
(54220, 54872, 'admin_language_new'),
(54221, 54872, 'admin_language_edit'),
(54222, 54872, 'admin_language_delete'),
(54223, 54872, 'admin_logs'),
(54224, 54872, 'admin_logs_index'),
(54225, 54872, 'admin_logs_logs'),
(54226, 54872, 'admin_logs_delete'),
(54227, 54872, 'admin_migration'),
(54228, 54872, 'admin_migration_acl-resource'),
(54229, 54872, 'admin_modules'),
(54230, 54872, 'admin_modules_index'),
(54231, 54872, 'admin_modules_show'),
(54232, 54872, 'admin_query'),
(54233, 54872, 'admin_query_index'),
(54234, 54872, 'admin_query_new'),
(54235, 54872, 'admin_query_edit'),
(54236, 54872, 'admin_query_save-order'),
(54237, 54872, 'admin_query_delete'),
(54238, 54872, 'admin_session'),
(54239, 54872, 'admin_session_index'),
(54240, 54872, 'admin_session_delete'),
(54241, 54872, 'admin_session_delete-all'),
(54242, 54872, 'admin_setting'),
(54243, 54872, 'admin_setting_clear-cache'),
(54244, 54872, 'admin_setting_list'),
(54245, 54872, 'admin_setting_edit'),
(54246, 54872, 'admin_setting_set-template'),
(54247, 54650, 'api'),
(54248, 54873, 'auth'),
(54249, 54873, 'auth_acl'),
(54250, 54873, 'auth_acl_index'),
(54251, 54873, 'auth_acl_save-rule'),
(54252, 54873, 'auth_acl_new-resource'),
(54253, 54873, 'auth_acl_delete-resource'),
(54254, 54873, 'auth_acl_load-resource'),
(54255, 54873, 'auth_acl-resource'),
(54256, 54873, 'auth_acl-resource_new'),
(54257, 54873, 'auth_acl-resource_edit'),
(54258, 54873, 'auth_acl-resource_delete'),
(54259, 54873, 'auth_acl-resource-item'),
(54260, 54873, 'auth_acl-resource-item_index'),
(54261, 54873, 'auth_acl-resource-item_new'),
(54262, 54873, 'auth_acl-resource-item_edit'),
(54263, 54873, 'auth_acl-resource-item_delete'),
(54264, 54873, 'auth_acl-resource-item-mass'),
(54265, 54873, 'auth_acl-resource-item-mass_delete'),
(54266, 54873, 'auth_acl-resource-item-mass_resource'),
(54267, 54873, 'auth_acl-role'),
(54268, 54873, 'auth_acl-role_new'),
(54269, 54873, 'auth_acl-role_edit'),
(54270, 54873, 'auth_acl-role_delete'),
(54271, 54870, 'auth_index'),
(54272, 54870, 'auth_index_index'),
(54273, 54870, 'auth_index_recover-password'),
(54274, 54870, 'auth_index_change-pass'),
(54275, 54870, 'auth_index_logout'),
(54276, 54832, 'client'),
(54277, 54832, 'client_ajax'),
(54278, 54832, 'client_ajax_get-client'),
(54279, 54832, 'client_index'),
(54280, 54832, 'client_index_index'),
(54281, 54833, 'client_index_new'),
(54282, 54833, 'client_index_edit'),
(54283, 54832, 'client_index_show'),
(54284, 54833, 'client_index_delete'),
(54285, 54833, 'client_index_archive'),
(54286, 54833, 'client_index-mass'),
(54287, 54833, 'client_index-mass_archive'),
(54288, 54833, 'client_index-mass_status'),
(54289, 54835, 'department'),
(54290, 54835, 'department_index'),
(54291, 54835, 'department_index_index'),
(54292, 54835, 'department_index_show'),
(54293, 54836, 'department_index_new'),
(54294, 54836, 'department_index_edit'),
(54295, 54836, 'department_index_archive'),
(54296, 54836, 'department_index_delete'),
(54297, 54856, 'dictionary'),
(54298, 54856, 'dictionary_index'),
(54299, 54856, 'dictionary_index_index'),
(54300, 54856, 'dictionary_index_new'),
(54301, 54856, 'dictionary_index_edit'),
(54302, 54856, 'dictionary_index_delete'),
(54303, 54706, 'image'),
(54304, 54707, 'image_cdn'),
(54305, 54708, 'image_cdn_main'),
(54306, 54709, 'image_cdn_serve'),
(54307, 54710, 'image_index'),
(54308, 54711, 'image_index_list'),
(54309, 54712, 'image_index_upload'),
(54310, 54713, 'image_index_order'),
(54311, 54714, 'image_index_edit'),
(54312, 54715, 'image_index_delete'),
(54313, 54716, 'image_index_cropper'),
(54314, 54864, 'industry'),
(54315, 54864, 'industry_index'),
(54316, 54864, 'industry_index_index'),
(54317, 54864, 'industry_index_new'),
(54318, 54864, 'industry_index_edit'),
(54319, 54864, 'industry_index_delete'),
(54320, 54849, 'invoice'),
(54321, 54849, 'invoice_index'),
(54322, 54849, 'invoice_index_index'),
(54323, 54850, 'invoice_index_new'),
(54324, 54850, 'invoice_index_edit'),
(54325, 54849, 'invoice_index_show'),
(54326, 54850, 'invoice_index_delete'),
(54327, 54849, 'invoice_index_payment'),
(54328, 54849, 'invoice_pdf'),
(54329, 54849, 'invoice_pdf_get'),
(54330, 54733, 'layout'),
(54331, 54733, 'layout_index'),
(54332, 54733, 'layout_index_index'),
(54333, 54733, 'layout_index_new'),
(54334, 54733, 'layout_index_edit'),
(54335, 54733, 'layout_index_widget-list'),
(54336, 54733, 'layout_index_widget-get'),
(54337, 54733, 'layout_index_load-template'),
(54338, 54741, 'log'),
(54339, 54741, 'log_index'),
(54340, 54741, 'log_index_load'),
(54341, 54855, 'note'),
(54342, 54855, 'note_index'),
(54343, 54855, 'note_index_new'),
(54344, 54855, 'note_index_edit'),
(54345, 54855, 'note_index_load'),
(54346, 54855, 'note_index_get-file'),
(54347, 54838, 'person'),
(54348, 54838, 'person_index'),
(54349, 54838, 'person_index_index'),
(54350, 54839, 'person_index_new'),
(54351, 54839, 'person_index_edit'),
(54352, 54838, 'person_index_show'),
(54353, 54839, 'person_index_archive'),
(54354, 54846, 'project'),
(54355, 54846, 'project_index'),
(54356, 54846, 'project_index_index'),
(54357, 54847, 'project_index_new'),
(54358, 54847, 'project_index_edit'),
(54359, 54846, 'project_index_show'),
(54360, 54847, 'project_index_archive'),
(54361, 54851, 'quickmail'),
(54362, 54851, 'quickmail_email'),
(54363, 54851, 'quickmail_email_index'),
(54364, 54851, 'quickmail_email_new'),
(54365, 54851, 'quickmail_email_edit'),
(54366, 54851, 'quickmail_index'),
(54367, 54851, 'quickmail_index_index'),
(54368, 54851, 'quickmail_index_new'),
(54369, 54851, 'quickmail_index_edit'),
(54370, 54851, 'quickmail_index_send'),
(54371, 54851, 'quickmail_index_attachment-upload'),
(54372, 54851, 'quickmail_index_show'),
(54373, 54851, 'quickmail_index_archive'),
(54374, 54851, 'quickmail_index_delete'),
(54375, 54851, 'quickmail_index_get-file'),
(54376, 54851, 'quickmail_mass'),
(54377, 54851, 'quickmail_mass_archive'),
(54378, 54851, 'quickmail_mass_confirm'),
(54379, 54851, 'quickmail_mass_add-mass'),
(54380, 54853, 'stage'),
(54381, 54853, 'stage_generate'),
(54382, 54853, 'stage_generate_index'),
(54383, 54853, 'stage_index'),
(54384, 54854, 'stage_index_new'),
(54385, 54854, 'stage_index_edit'),
(54386, 54854, 'stage_index_delete'),
(54387, 54853, 'stage_index_save-order'),
(54388, 54791, 'tag'),
(54389, 54841, 'task'),
(54390, 54841, 'task_index'),
(54391, 54841, 'task_index_index'),
(54392, 54842, 'task_index_new'),
(54393, 54842, 'task_index_edit'),
(54394, 54841, 'task_index_status'),
(54395, 54841, 'task_index_show'),
(54396, 54842, 'task_index_update'),
(54397, 54841, 'task_index_get-list'),
(54398, 54842, 'task_index_archive'),
(54399, 54841, 'task_index_ajax-status-change'),
(54400, 54842, 'task_index-mass'),
(54401, 54842, 'task_index-mass_archive'),
(54402, 54842, 'task_index-mass_status'),
(54403, 54842, 'task_index-mass_user'),
(54404, 54858, 'template'),
(54405, 54858, 'template_index'),
(54406, 54858, 'template_index_index'),
(54407, 54859, 'template_index_new'),
(54408, 54859, 'template_index_edit'),
(54409, 54859, 'template_index_archive'),
(54410, 54859, 'template_index_delete'),
(54411, 54866, 'user'),
(54412, 54868, 'user_account'),
(54413, 54868, 'user_account_edit-profile'),
(54414, 54868, 'user_account_change-pass'),
(54415, 54868, 'user_account_manage-emails'),
(54416, 54868, 'user_account_confirm-new-email'),
(54417, 54866, 'user_index'),
(54418, 54866, 'user_index_index'),
(54419, 54867, 'user_index_new'),
(54420, 54867, 'user_index_edit'),
(54421, 54866, 'user_index_show'),
(54422, 54867, 'user_index_delete'),
(54423, 54867, 'user_index_delete-email'),
(54424, 54867, 'user_index_archive'),
(54425, 54867, 'user_index_unarchive'),
(54426, 54867, 'user_index-mass'),
(54427, 54867, 'user_index-mass_archive'),
(54428, 54650, 'api_index'),
(54429, 54650, 'api_index_index'),
(54430, 54650, 'api_index_new'),
(54431, 54650, 'api_index_edit'),
(54432, 54650, 'api_index_show'),
(54433, 54860, 'client_api'),
(54434, 54860, 'client_api_create'),
(54435, 54860, 'client_api-test'),
(54436, 54860, 'client_api-test_create'),
(54437, 54862, 'document'),
(54438, 54862, 'document_index'),
(54439, 54863, 'document_index_new'),
(54440, 54863, 'document_index_edit'),
(54441, 54863, 'document_index_archive'),
(54442, 54862, 'document_index_download'),
(54443, 54851, 'quickmail_email_delete'),
(54444, 54851, 'quickmail_email-mass'),
(54445, 54851, 'quickmail_email-mass_delete'),
(54446, 54851, 'quickmail_email-mass_add-mass'),
(54447, 54851, 'quickmail_index_confirm');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `acl_rule`
--

CREATE TABLE IF NOT EXISTS `acl_rule` (
  `id_acl_rule` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `is_allow` tinyint(1) NOT NULL COMMENT 'Określenie dostępu: 0 – brak dostępu; 1 – nadanie dostępu',
  `role` varchar(255) NOT NULL COMMENT 'Nazwa roli',
  `resource` varchar(255) NOT NULL COMMENT 'Nazwa zasobu',
  PRIMARY KEY (`id_acl_rule`),
  KEY `id_acl_role` (`role`),
  KEY `id_acl_resource` (`resource`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='Zasady uprawnień. Jaka rola ma dostęp/nie ma dostępu do zasobu';

--
-- Tabela Truncate przed wstawieniem `acl_rule`
--

TRUNCATE TABLE `acl_rule`;
--
-- Zrzut danych tabeli `acl_rule`
--

INSERT INTO `acl_rule` (`id_acl_rule`, `is_allow`, `role`, `resource`) VALUES
(1, 1, 'guest', 'guest_access'),
(2, 1, 'user', 'user_access'),
(3, 1, 'admin', 'setting'),
(4, 1, 'admin', 'admin'),
(5, 1, 'admin', 'auth'),
(7, 1, 'admin', 'user'),
(8, 1, 'user', 'template'),
(9, 1, 'user', 'stage'),
(10, 1, 'user', 'project'),
(11, 1, 'user', 'task'),
(13, 1, 'admin', 'person'),
(14, 1, 'admin', 'department'),
(15, 1, 'admin', 'client'),
(16, 1, 'admin', 'api'),
(17, 1, 'admin', 'invoice'),
(18, 1, 'admin', 'quickmail'),
(19, 1, 'user', 'document'),
(20, 1, 'user', 'user_access_admin'),
(21, 1, 'guest', 'default'),
(22, 0, 'guest', 'default_tools'),
(23, 1, 'user', 'default_tools'),
(26, 1, 'admin', 'industry'),
(27, 1, 'admin', 'dictionary'),
(29, 1, 'admin', 'log');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
