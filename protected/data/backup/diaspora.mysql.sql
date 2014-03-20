/*
SQLyog Trial v10.2 
MySQL - 5.5.16-log : Database - diaspora_development
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`diaspora_development` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `diaspora_development`;

/*Table structure for table `account_deletions` */

DROP TABLE IF EXISTS `account_deletions`;

CREATE TABLE `account_deletions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `diaspora_handle` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `account_deletions` */

/*Table structure for table `aspect_memberships` */

DROP TABLE IF EXISTS `aspect_memberships`;

CREATE TABLE `aspect_memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aspect_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_aspect_memberships_on_aspect_id_and_contact_id` (`aspect_id`,`contact_id`),
  KEY `index_aspect_memberships_on_aspect_id` (`aspect_id`),
  KEY `index_aspect_memberships_on_contact_id` (`contact_id`),
  CONSTRAINT `aspect_memberships_aspect_id_fk` FOREIGN KEY (`aspect_id`) REFERENCES `aspects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aspect_memberships_contact_id_fk` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `aspect_memberships` */

/*Table structure for table `aspect_visibilities` */

DROP TABLE IF EXISTS `aspect_visibilities`;

CREATE TABLE `aspect_visibilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shareable_id` int(11) NOT NULL,
  `aspect_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `shareable_type` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Post',
  PRIMARY KEY (`id`),
  KEY `index_aspect_visibilities_on_aspect_id` (`aspect_id`),
  KEY `shareable_and_aspect_id` (`shareable_id`,`shareable_type`,`aspect_id`),
  KEY `index_aspect_visibilities_on_shareable_id_and_shareable_type` (`shareable_id`,`shareable_type`),
  CONSTRAINT `aspect_visibilities_aspect_id_fk` FOREIGN KEY (`aspect_id`) REFERENCES `aspects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `aspect_visibilities` */

/*Table structure for table `aspects` */

DROP TABLE IF EXISTS `aspects`;

CREATE TABLE `aspects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `contacts_visible` tinyint(1) NOT NULL DEFAULT '1',
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_aspects_on_user_id_and_contacts_visible` (`user_id`,`contacts_visible`),
  KEY `index_aspects_on_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `aspects` */

/*Table structure for table `blocks` */

DROP TABLE IF EXISTS `blocks`;

CREATE TABLE `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `blocks` */

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text COLLATE utf8_bin NOT NULL,
  `commentable_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `guid` varchar(255) COLLATE utf8_bin NOT NULL,
  `author_signature` text COLLATE utf8_bin,
  `parent_author_signature` text COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `likes_count` int(11) NOT NULL DEFAULT '0',
  `commentable_type` varchar(60) COLLATE utf8_bin NOT NULL DEFAULT 'Post',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_comments_on_guid` (`guid`),
  KEY `index_comments_on_person_id` (`author_id`),
  KEY `index_comments_on_commentable_id_and_commentable_type` (`commentable_id`,`commentable_type`),
  CONSTRAINT `comments_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `comments` */

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `sharing` tinyint(1) NOT NULL DEFAULT '0',
  `receiving` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_contacts_on_user_id_and_person_id` (`user_id`,`person_id`),
  KEY `index_contacts_on_person_id` (`person_id`),
  CONSTRAINT `contacts_person_id_fk` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `contacts` */

/*Table structure for table `conversation_visibilities` */

DROP TABLE IF EXISTS `conversation_visibilities`;

CREATE TABLE `conversation_visibilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `unread` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_conversation_visibilities_usefully` (`conversation_id`,`person_id`),
  KEY `index_conversation_visibilities_on_conversation_id` (`conversation_id`),
  KEY `index_conversation_visibilities_on_person_id` (`person_id`),
  CONSTRAINT `conversation_visibilities_conversation_id_fk` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_visibilities_person_id_fk` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `conversation_visibilities` */

/*Table structure for table `conversations` */

DROP TABLE IF EXISTS `conversations`;

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `guid` varchar(255) COLLATE utf8_bin NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `conversations_author_id_fk` (`author_id`),
  CONSTRAINT `conversations_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `conversations` */

/*Table structure for table `invitation_codes` */

DROP TABLE IF EXISTS `invitation_codes`;

CREATE TABLE `invitation_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `invitation_codes` */

/*Table structure for table `invitations` */

DROP TABLE IF EXISTS `invitations`;

CREATE TABLE `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_bin,
  `sender_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `aspect_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `service` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `identifier` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `admin` tinyint(1) DEFAULT '0',
  `language` varchar(255) COLLATE utf8_bin DEFAULT 'en',
  PRIMARY KEY (`id`),
  KEY `index_invitations_on_aspect_id` (`aspect_id`),
  KEY `index_invitations_on_recipient_id` (`recipient_id`),
  KEY `index_invitations_on_sender_id` (`sender_id`),
  CONSTRAINT `invitations_recipient_id_fk` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invitations_sender_id_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `invitations` */

/*Table structure for table `likes` */

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `positive` tinyint(1) DEFAULT '1',
  `target_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `guid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `author_signature` text COLLATE utf8_bin,
  `parent_author_signature` text COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `target_type` varchar(60) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_likes_on_guid` (`guid`),
  UNIQUE KEY `index_likes_on_target_id_and_author_id_and_target_type` (`target_id`,`author_id`,`target_type`),
  KEY `index_likes_on_post_id` (`target_id`),
  KEY `likes_author_id_fk` (`author_id`),
  CONSTRAINT `likes_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `likes` */

/*Table structure for table `mentions` */

DROP TABLE IF EXISTS `mentions`;

CREATE TABLE `mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_mentions_on_person_id_and_post_id` (`person_id`,`post_id`),
  KEY `index_mentions_on_person_id` (`person_id`),
  KEY `index_mentions_on_post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `mentions` */

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `guid` varchar(255) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `author_signature` text COLLATE utf8_bin,
  `parent_author_signature` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  KEY `index_messages_on_author_id` (`author_id`),
  KEY `messages_conversation_id_fk` (`conversation_id`),
  CONSTRAINT `messages_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `people` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_conversation_id_fk` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `messages` */

/*Table structure for table `notification_actors` */

DROP TABLE IF EXISTS `notification_actors`;

CREATE TABLE `notification_actors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_notification_actors_on_notification_id_and_person_id` (`notification_id`,`person_id`),
  KEY `index_notification_actors_on_notification_id` (`notification_id`),
  KEY `index_notification_actors_on_person_id` (`person_id`),
  CONSTRAINT `notification_actors_notification_id_fk` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `notification_actors` */

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_notifications_on_recipient_id` (`recipient_id`),
  KEY `index_notifications_on_target_id` (`target_id`),
  KEY `index_notifications_on_target_type_and_target_id` (`target_type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `notifications` */

/*Table structure for table `o_embed_caches` */

DROP TABLE IF EXISTS `o_embed_caches`;

CREATE TABLE `o_embed_caches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(1024) COLLATE utf8_bin NOT NULL,
  `data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_o_embed_caches_on_url` (`url`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `o_embed_caches` */

/*Table structure for table `participations` */

DROP TABLE IF EXISTS `participations`;

CREATE TABLE `participations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `target_type` varchar(60) COLLATE utf8_bin NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `author_signature` text COLLATE utf8_bin,
  `parent_author_signature` text COLLATE utf8_bin,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_participations_on_guid` (`guid`),
  KEY `index_participations_on_target_id_and_target_type_and_author_id` (`target_id`,`target_type`,`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `participations` */

/*Table structure for table `people` */

DROP TABLE IF EXISTS `people`;

CREATE TABLE `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) COLLATE utf8_bin NOT NULL,
  `url` text COLLATE utf8_bin NOT NULL,
  `diaspora_handle` varchar(255) COLLATE utf8_bin NOT NULL,
  `serialized_public_key` text COLLATE utf8_bin NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `closed_account` tinyint(1) DEFAULT '0',
  `fetch_status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_people_on_diaspora_handle` (`diaspora_handle`),
  UNIQUE KEY `index_people_on_guid` (`guid`),
  UNIQUE KEY `index_people_on_owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `people` */

/*Table structure for table `photos` */

DROP TABLE IF EXISTS `photos`;

CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmp_old_id` int(11) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `diaspora_handle` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `guid` varchar(255) COLLATE utf8_bin NOT NULL,
  `pending` tinyint(1) NOT NULL DEFAULT '0',
  `text` text COLLATE utf8_bin,
  `remote_photo_path` text COLLATE utf8_bin,
  `remote_photo_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `random_string` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `processed_image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `unprocessed_image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status_message_guid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `comments_count` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_photos_on_status_message_guid` (`status_message_guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `photos` */

/*Table structure for table `pods` */

DROP TABLE IF EXISTS `pods`;

CREATE TABLE `pods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ssl` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `pods` */

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `diaspora_handle` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `guid` varchar(255) COLLATE utf8_bin NOT NULL,
  `pending` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(40) COLLATE utf8_bin NOT NULL,
  `text` text COLLATE utf8_bin,
  `remote_photo_path` text COLLATE utf8_bin,
  `remote_photo_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `random_string` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `processed_image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `unprocessed_image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `object_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image_height` int(11) DEFAULT NULL,
  `image_width` int(11) DEFAULT NULL,
  `provider_display_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `actor_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `objectId` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `root_guid` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `status_message_guid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `likes_count` int(11) DEFAULT '0',
  `comments_count` int(11) DEFAULT '0',
  `o_embed_cache_id` int(11) DEFAULT NULL,
  `reshares_count` int(11) DEFAULT '0',
  `interacted_at` datetime DEFAULT NULL,
  `frame_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `favorite` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_posts_on_guid` (`guid`),
  UNIQUE KEY `index_posts_on_author_id_and_root_guid` (`author_id`,`root_guid`),
  KEY `index_posts_on_person_id` (`author_id`),
  KEY `index_posts_on_id_and_type_and_created_at` (`id`,`type`,`created_at`),
  KEY `index_posts_on_root_guid` (`root_guid`),
  KEY `index_posts_on_status_message_guid_and_pending` (`status_message_guid`,`pending`),
  KEY `index_posts_on_status_message_guid` (`status_message_guid`),
  KEY `index_posts_on_type_and_pending_and_id` (`type`,`pending`,`id`),
  CONSTRAINT `posts_author_id_fk` FOREIGN KEY (`author_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `posts` */

/*Table structure for table `profiles` */

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `diaspora_handle` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `first_name` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `last_name` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image_url_small` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image_url_medium` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `bio` text COLLATE utf8_bin,
  `searchable` tinyint(1) NOT NULL DEFAULT '1',
  `person_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `full_name` varchar(70) COLLATE utf8_bin DEFAULT NULL,
  `nsfw` tinyint(1) DEFAULT '0',
  `wallpaper` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_profiles_on_full_name_and_searchable` (`full_name`,`searchable`),
  KEY `index_profiles_on_full_name` (`full_name`),
  KEY `index_profiles_on_person_id` (`person_id`),
  CONSTRAINT `profiles_person_id_fk` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `profiles` */

/*Table structure for table `rails_admin_histories` */

DROP TABLE IF EXISTS `rails_admin_histories`;

CREATE TABLE `rails_admin_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_bin,
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `item` int(11) DEFAULT NULL,
  `table` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `month` smallint(6) DEFAULT NULL,
  `year` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_rails_admin_histories` (`item`,`table`,`month`,`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `rails_admin_histories` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `roles` */

/*Table structure for table `schema_migrations` */

DROP TABLE IF EXISTS `schema_migrations`;

CREATE TABLE `schema_migrations` (
  `version` varchar(255) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `unique_schema_migrations` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `schema_migrations` */

insert  into `schema_migrations`(`version`) values ('0'),('20110105051803'),('20110119060243'),('20110119221746'),('20110120181553'),('20110120182100'),('20110123210746'),('20110125190034'),('20110126015407'),('20110126200714'),('20110126225202'),('20110126232040'),('20110127000931'),('20110127000953'),('20110130072907'),('20110202015222'),('20110209204702'),('20110211021926'),('20110211204804'),('20110213052742'),('20110217044519'),('20110225190919'),('20110228180709'),('20110228201109'),('20110228220810'),('20110228233419'),('20110301014507'),('20110301202619'),('20110311000150'),('20110311183826'),('20110311220249'),('20110313015438'),('20110314043119'),('20110317222802'),('20110318000734'),('20110318012008'),('20110319005509'),('20110319172136'),('20110321205715'),('20110323213655'),('20110328175936'),('20110328202414'),('20110330175950'),('20110330230206'),('20110331004720'),('20110405170101'),('20110405171412'),('20110406202932'),('20110406203720'),('20110421120744'),('20110507212759'),('20110513175000'),('20110514182918'),('20110517180148'),('20110518010050'),('20110518184453'),('20110518222303'),('20110524184202'),('20110525213325'),('20110527135552'),('20110601083310'),('20110601091059'),('20110603181015'),('20110603212633'),('20110603233202'),('20110604012703'),('20110604204533'),('20110606192307'),('20110623210918'),('20110701215925'),('20110705003445'),('20110707221112'),('20110707234802'),('20110710102747'),('20110729045734'),('20110730173137'),('20110730173443'),('20110812175614'),('20110815210933'),('20110816061820'),('20110818212541'),('20110830170929'),('20110907205720'),('20110911213207'),('20110924112840'),('20110926120220'),('20110930182048'),('20111002013921'),('20111003232053'),('20111011193702'),('20111011194702'),('20111011195702'),('20111012215141'),('20111016145626'),('20111018010003'),('20111019013244'),('20111021184041'),('20111023230730'),('20111026173547'),('20111101202137'),('20111103184050'),('20111109023618'),('20111111025358'),('20111114173111'),('20111207230506'),('20111207233503'),('20111211213438'),('20111217042006'),('20120107220942'),('20120114191018'),('20120127235102'),('20120202190701'),('20120203220932'),('20120208231253'),('20120301143226'),('20120322223517'),('20120328025842'),('20120330103021'),('20120330144057'),('20120414005431'),('20120420185823'),('20120422072257'),('20120427152648'),('20120506053156'),('20120510184853'),('20120517014034'),('20120519015723'),('20120521191429'),('20120803143552');

/*Table structure for table `services` */

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL,
  `uid` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `access_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `access_secret` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_services_on_type_and_uid` (`type`,`uid`),
  KEY `index_services_on_user_id` (`user_id`),
  CONSTRAINT `services_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `services` */

/*Table structure for table `share_visibilities` */

DROP TABLE IF EXISTS `share_visibilities`;

CREATE TABLE `share_visibilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shareable_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL,
  `shareable_type` varchar(60) COLLATE utf8_bin NOT NULL DEFAULT 'Post',
  PRIMARY KEY (`id`),
  KEY `index_post_visibilities_on_contact_id` (`contact_id`),
  KEY `shareable_and_contact_id` (`shareable_id`,`shareable_type`,`contact_id`),
  KEY `shareable_and_hidden_and_contact_id` (`shareable_id`,`shareable_type`,`hidden`,`contact_id`),
  KEY `index_post_visibilities_on_post_id` (`shareable_id`),
  CONSTRAINT `post_visibilities_contact_id_fk` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `share_visibilities` */

/*Table structure for table `tag_followings` */

DROP TABLE IF EXISTS `tag_followings`;

CREATE TABLE `tag_followings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_tag_followings_on_tag_id_and_user_id` (`tag_id`,`user_id`),
  KEY `index_tag_followings_on_tag_id` (`tag_id`),
  KEY `index_tag_followings_on_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `tag_followings` */

/*Table structure for table `taggings` */

DROP TABLE IF EXISTS `taggings`;

CREATE TABLE `taggings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) DEFAULT NULL,
  `taggable_id` int(11) DEFAULT NULL,
  `taggable_type` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `tagger_id` int(11) DEFAULT NULL,
  `tagger_type` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `context` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_taggings_uniquely` (`taggable_id`,`taggable_type`,`tag_id`),
  KEY `index_taggings_on_created_at` (`created_at`),
  KEY `index_taggings_on_tag_id` (`tag_id`),
  KEY `index_taggings_on_taggable_id_and_taggable_type_and_context` (`taggable_id`,`taggable_type`,`context`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `taggings` */

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_tags_on_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `tags` */

/*Table structure for table `user_preferences` */

DROP TABLE IF EXISTS `user_preferences`;

CREATE TABLE `user_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `user_preferences` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `serialized_private_key` text COLLATE utf8_bin,
  `getting_started` tinyint(1) NOT NULL DEFAULT '1',
  `disable_mail` tinyint(1) NOT NULL DEFAULT '0',
  `language` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `encrypted_password` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '',
  `invitation_token` varchar(60) COLLATE utf8_bin DEFAULT NULL,
  `invitation_sent_at` datetime DEFAULT NULL,
  `reset_password_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `remember_created_at` datetime DEFAULT NULL,
  `sign_in_count` int(11) DEFAULT '0',
  `current_sign_in_at` datetime DEFAULT NULL,
  `last_sign_in_at` datetime DEFAULT NULL,
  `current_sign_in_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `last_sign_in_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `invitation_service` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `invitation_identifier` varchar(127) COLLATE utf8_bin DEFAULT NULL,
  `invitation_limit` int(11) DEFAULT NULL,
  `invited_by_id` int(11) DEFAULT NULL,
  `invited_by_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `authentication_token` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `confirm_email_token` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `locked_at` datetime DEFAULT NULL,
  `show_community_spotlight_in_stream` tinyint(1) NOT NULL DEFAULT '1',
  `auto_follow_back` tinyint(1) DEFAULT '0',
  `auto_follow_back_aspect_id` int(11) DEFAULT NULL,
  `hidden_shareables` text COLLATE utf8_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_users_on_authentication_token` (`authentication_token`),
  UNIQUE KEY `index_users_on_invitation_service_and_invitation_identifier` (`invitation_service`,`invitation_identifier`),
  UNIQUE KEY `index_users_on_remember_token` (`remember_token`),
  UNIQUE KEY `index_users_on_username` (`username`),
  KEY `index_users_on_email` (`email`),
  KEY `index_users_on_invitation_token` (`invitation_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
