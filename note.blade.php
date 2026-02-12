Okay, but I want you to know that I modify my steps, categories and windows table in database. This is my setup now.

ALTER TABLE `windows`
ADD CONSTRAINT `windows_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET
NULL;
COMMIT;
