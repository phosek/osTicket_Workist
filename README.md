# Workist <img src = "https://osticket.com/wp-content/uploads/2021/03/osticket-supsys-new-1-e1616621912452.png" style="float: right;" />

#### Modification of ostTicket


___



#### Technischer Hintergrund

* PHP 8.2.+

> PHP must be at least the version listed. Otherwise, saving the settings to the database will not work. The new function [execute_query](https://www.php.net/manual/en/mysqli.execute-query.php) is used as a defense against SQL injection.


---


#### Description

The modification makes it possible to send all attachments from the ticket by e-mail to the selected address.
In the selected ticket, just press the more button and select the workist item in the menu.

The modification also adds a new menu in the osTicket administration, where it is possible to set the behavior of the modification.


Both the database and the table can be created with the following SQL query:
```
CREATE DATABASE cis_extra;

DROP TABLE IF EXISTS `cis_workist`;
CREATE TABLE IF NOT EXISTS `cis_workist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `DeptName` text COLLATE utf8mb4_general_ci NOT NULL,
  `tmpDir` text COLLATE utf8mb4_general_ci NOT NULL,
  `adressTo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `subject` text COLLATE utf8mb4_general_ci NOT NULL,
  `body` text COLLATE utf8mb4_general_ci NOT NULL,
  `charset` text COLLATE utf8mb4_general_ci NOT NULL,
  `host` text COLLATE utf8mb4_general_ci NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `Password` text COLLATE utf8mb4_general_ci NOT NULL,
  `SMTPsecure` text COLLATE utf8mb4_general_ci NOT NULL,
  `port` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```
