-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Сен 28 2018 г., 01:47
-- Версия сервера: 5.7.23-0ubuntu0.16.04.1-log
-- Версия PHP: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `testuser`
--

-- --------------------------------------------------------

--
-- Структура таблицы `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(2) NOT NULL,
  `inn` varchar(12) NOT NULL,
  `kpp` varchar(9) DEFAULT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `type`, `inn`, `kpp`, `phone`, `email`) VALUES
(11, 'testtesttest', 'UL', '1111111111', '11111', '1111111111', 'andre90094@gmail.com'),
(12, 'testtesttest', 'UL', '1111111132', '111113', '1111111111', 'andre90094@gmail.com'),
(13, '1111111', 'UL', '2111111111', '121111111', '1111111111', ''),
(14, '1111111', 'UL', '2111111112', '121111112', '1111111111', ''),
(15, 'fdfdfd', 'IP', '111111111119', NULL, '+7(986)565-6556', ''),
(18, 'CyberiaSport', 'IP', '123456789012', NULL, '+7(991)611-5165', 'admin@cyberiasport.ru'),
(20, 'тестовая организация', 'IP', '311118111111', '111112344', '+7(898)729-4848', 'andre90094@gmail.com');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
