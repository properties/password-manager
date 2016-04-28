CREATE TABLE IF NOT EXISTS `pwd_accs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(535) NOT NULL,
  `username` varchar(535) NOT NULL,
  `password` varchar(535) NOT NULL,
  `extra` varchar(535) NOT NULL,
  `site` varchar(535) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `pwd_accs` (`id`, `email`, `username`, `password`, `extra`, `site`) VALUES
(5, 'properties@github.com', 'Matthew de Groot', 'password', '', 'facebook.com');

CREATE TABLE IF NOT EXISTS `pwd_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(535) NOT NULL,
  `url` varchar(535) NOT NULL,
  `img` varchar(535) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `pwd_sites` (`id`, `name`, `url`, `img`) VALUES (2, 'Facebook', 'facebook.com', '/img/facebook.com.ico');

