-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2012 at 06:27 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `problem_grader`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cid`, `name`) VALUES
(1, 'USA Computing Olympiad'),
(2, 'Techstart'),
(3, 'Assignment 1: Getting Started');

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE IF NOT EXISTS `problems` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `level` varchar(10) NOT NULL,
  `code` varchar(20) NOT NULL COMMENT 'alpha num code (cows)',
  `date` varchar(20) NOT NULL,
  `solvers` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `point` int(11) NOT NULL,
  `timelimit` float NOT NULL DEFAULT '1',
  `memlimit` float NOT NULL DEFAULT '16',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`pid`, `cid`, `level`, `code`, `date`, `solvers`, `name`, `point`, `timelimit`, `memlimit`) VALUES
(2, 3, '', 'aplusb', 'July 2012', 0, 'A plus B', 1, 1, 16);

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE IF NOT EXISTS `submissions` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `res` varchar(5) NOT NULL,
  `pts` int(11) NOT NULL,
  `scorecard` varchar(30) NOT NULL,
  `path_submit` mediumtext NOT NULL,
  `compile_status` int(11) NOT NULL COMMENT '0:good 1:warn 2:error',
  `extrainfo` mediumtext,
  `lang` varchar(10) NOT NULL,
  `date` varchar(60) NOT NULL,
  `time` varchar(60) NOT NULL,
  `cpu` float NOT NULL,
  `mem` float NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`sid`, `uid`, `pid`, `res`, `pts`, `scorecard`, `path_submit`, `compile_status`, `extrainfo`, `lang`, `date`, `time`, `cpu`, `mem`) VALUES
(11, 4, 2, 'CE', 0, 'ccccc ccccc', '/home/stanleyc/westviewcs/submissions/aplusb_stanleyc_134508496944.cpp', 2, 'aplusb.cpp: In function ''int main()'':\naplusb.cpp:3:8: error: ''z'' was not declared in this scope\n', 'C++', 'Aug 15, 2012', '7:42:49pm', 0, 0),
(12, 4, 2, 'CE', 0, 'ccccc ccccc', '/home/stanleyc/westviewcs/submissions/aplusb_stanleyc_134508517894.cpp', 2, 'aplusb.cpp:1:1: error: ''asdfasf'' does not name a type\n', 'C++', 'Aug 15, 2012', '7:46:18pm', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'userid',
  `username` varchar(21) NOT NULL,
  `password` varchar(65) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(3, 'abcd', '4901d4e98e1b597a7eb15051fc2b41b08e8a557bc252027621f50697a5c0e674', 'abcd@e.com'),
(4, 'stanleyc', 'b8a8b45b6727d7a2ccba9d8d713a15f0760e4e573ed7184b2bc29a2713db978c', 'jiecenzhao@gmail.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
