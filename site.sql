CREATE TABLE IF NOT EXISTS `pwd_accs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(535) NOT NULL,
  `username` varchar(535) NOT NULL,
  `password` varchar(535) NOT NULL,
  `extra` varchar(535) NOT NULL,
  `site` varchar(535) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

CREATE TABLE IF NOT EXISTS `pwd_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(535) NOT NULL,
  `value` varchar(535) NOT NULL,
  `value2` varchar(535) NOT NULL,
  `info` varchar(535) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `pwd_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(535) NOT NULL,
  `url` varchar(535) NOT NULL,
  `img` varchar(535) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;
