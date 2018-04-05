-- phpMyAdmin SQL Dump
-- version 4.7.6
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 05 2018 г., 14:45
-- Версия сервера: 5.7.21
-- Версия PHP: 7.1.13-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vending`
--

-- --------------------------------------------------------

--
-- Структура таблицы `machine`
--

CREATE TABLE `machine` (
  `coin` tinyint(4) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `machine`
--

INSERT INTO `machine` (`coin`, `count`) VALUES
(1, 100),
(2, 100),
(5, 100),
(10, 100);

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cost` decimal(10,0) NOT NULL,
  `amount` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `name`, `cost`, `amount`) VALUES
(1, 'Чай', '13', 10),
(2, 'Кофе', '18', 20),
(3, 'Кофе с молоком', '21', 20),
(4, 'Сок', '35', 15);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `reserve` decimal(10,0) NOT NULL DEFAULT '0',
  `coin_1` int(11) NOT NULL DEFAULT '10',
  `coin_2` int(11) NOT NULL DEFAULT '30',
  `coin_5` int(11) NOT NULL DEFAULT '20',
  `coin_10` int(11) NOT NULL DEFAULT '15'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `reserve`, `coin_1`, `coin_2`, `coin_5`, `coin_10`) VALUES
(1, '0', 10, 30, 20, 15);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `machine`
--
ALTER TABLE `machine`
  ADD PRIMARY KEY (`coin`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
