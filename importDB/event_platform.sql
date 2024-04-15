-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 15 2024 г., 16:49
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `event_platform`
--

-- --------------------------------------------------------

--
-- Структура таблицы `capabilities`
--

CREATE TABLE `capabilities` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `number_seats` int NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `name`, `price`, `number_seats`, `date`) VALUES
(1, 'Conference on Technology Trends', '100.00', 200, '2024-04-10'),
(2, 'Music Festival \"Summer Sounds\"', '75.00', 300, '2024-04-15'),
(3, 'Art Exhibition \"Modern Perspectives\"', '50.00', 150, '2024-04-20'),
(4, 'Food and Wine Tasting Event', '60.00', 120, '2024-04-25'),
(5, 'Charity Gala for Children in Need', '120.00', 250, '2024-04-30'),
(6, 'Fitness and Wellness Expo', '40.00', 100, '2024-05-05'),
(7, 'Fashion Show \"Trendsetters\"', '80.00', 180, '2024-05-10'),
(8, 'Business Networking Breakfast', '30.00', 80, '2024-05-15'),
(9, 'Film Screening \"Cinematic Delights\"', '50.00', 150, '2024-05-20'),
(12, 'Not enaugh seats', '9999.00', 1, '2024-04-25');

-- --------------------------------------------------------

--
-- Структура таблицы `event_records`
--

CREATE TABLE `event_records` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `event_records`
--

INSERT INTO `event_records` (`id`, `user_id`, `event_id`) VALUES
(1, 1, 1),
(2, 1, 12),
(3, 1, 3),
(4, 1, 9),
(5, 1, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `roles_capabilities`
--

CREATE TABLE `roles_capabilities` (
  `id` int NOT NULL,
  `role_id` int NOT NULL,
  `capability_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tokens`
--

CREATE TABLE `tokens` (
  `id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `user_id`) VALUES
(36, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJuYW1lIjoiTmljb2xhaSIsInN1cm5hbWUiOiJDYWxpbiIsImVtYWlsIjoia2FsaW5uaWtvbGF5MDJAZ21haWwuY29tIiwidGltZSI6MTcxMzE4ODg1N30=.fpPpU8ih2M8/prL6wR+WWXQpqD3OEdPQH382FCWDhyI=', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `role_id`) VALUES
(1, 'Nicolai', 'Calin', 'kalinnikolay02@gmail.com', '$2y$10$WGf7pCxKQyGv7hP4gTUequBw51sDxh0I.EsjWTLrxwMSShv2wVOly', 2),
(2, 'Ruger', 'Forger', 'ruger@gmail.com', '$2y$10$QfXTGcMip0rVhdThtA/PEuLdwAB5R1ekhLG96.ABCGjhBUTHxH3O2', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `capabilities`
--
ALTER TABLE `capabilities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `event_records`
--
ALTER TABLE `event_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles_capabilities`
--
ALTER TABLE `roles_capabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `capability_id` (`capability_id`);

--
-- Индексы таблицы `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `capabilities`
--
ALTER TABLE `capabilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `event_records`
--
ALTER TABLE `event_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `roles_capabilities`
--
ALTER TABLE `roles_capabilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `event_records`
--
ALTER TABLE `event_records`
  ADD CONSTRAINT `event_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `event_records_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Ограничения внешнего ключа таблицы `roles_capabilities`
--
ALTER TABLE `roles_capabilities`
  ADD CONSTRAINT `roles_capabilities_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `roles_capabilities_ibfk_2` FOREIGN KEY (`capability_id`) REFERENCES `capabilities` (`id`);

--
-- Ограничения внешнего ключа таблицы `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
