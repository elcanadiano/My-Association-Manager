USE myfam;

CREATE TABLE `league` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `age_cat` varchar(16) NOT NULL,
  `num_teams` integer NOT NULL,
  `max_roster_size` integer NOT NULL,
  `is_active` boolean NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `field` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `address` varchar(128),
  `city` varchar(64),
  `region` varchar(64),
  `pitch_type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `team` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `homeid` integer NOT NULL,
  `city` varchar(32) NOT NULL,
  `region` varchar(32),
  `roster_size` integer NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`homeid`) REFERENCES field(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `season` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `start_time` date,
  `end_time` date,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `player` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `real_name` varchar(128) NOT NULL,
  `preferred_name` varchar(64) NOT NULL,
  `pos1` varchar(16),
  `pos2` varchar(16),
  `pos3` varchar(16),
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `standings` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `tid` integer NOT NULL,
  `sid` integer NOT NULL,
  `lid` integer NOT NULL,
  `pld` integer NOT NULL DEFAULT 0,
  `pts` integer NOT NULL DEFAULT 0,
  `h_w` integer NOT NULL DEFAULT 0,
  `h_t` integer NOT NULL DEFAULT 0,
  `h_l` integer NOT NULL DEFAULT 0,
  `h_gf` integer NOT NULL DEFAULT 0,
  `h_ga` integer NOT NULL DEFAULT 0,
  `a_w` integer NOT NULL DEFAULT 0,
  `a_t` integer NOT NULL DEFAULT 0,
  `a_l` integer NOT NULL DEFAULT 0,
  `a_gf` integer NOT NULL DEFAULT 0,
  `a_ga` integer NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`lid`) references league(`id`),
  CONSTRAINT combination UNIQUE(`lid`, `tid`, `sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `roster` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `pid` integer NOT NULL,
  `tid` integer NOT NULL,
  `sid` integer NOT NULL,
  `squad_number` integer NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`pid`) references player(`id`),
  CONSTRAINT combination UNIQUE(`pid`, `tid`, `sid`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `match` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `sid` integer NOT NULL,
  `fid` integer NOT NULL,
  `lid` integer NOT NULL,
  `htid` integer NOT NULL,
  `atid` integer NOT NULL,
  `h_g` integer NOT NULL DEFAULT 0,
  `a_g` integer NOT NULL DEFAULT 0,
  `has_been_played` boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`fid`) references field(`id`),
  FOREIGN KEY (`htid`) references team(`id`),
  FOREIGN KEY (`atid`) references team(`id`),
  FOREIGN KEY (`lid`) references league(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `event` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `tid` integer NOT NULL,
  `pid` integer NOT NULL,
  `mid` integer NOT NULL,
  `type` varchar(16) NOT NULL,
  `min` integer NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`pid`) references playercd (`id`),
  FOREIGN KEY (`mid`) references match(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


/**
 * This trigger will update an entry in 'standings' related to a
 * "first-time" update to a match row, in which the game is then
 * played.
 *
 * The current assumption is that if a second update is needed for
 * a match (for example, an error occurs)
 */
CREATE TRIGGER `update_standing` BEFORE UPDATE ON `match`
FOR EACH ROW
BEGIN
  IF NOT OLD.has_been_played THEN
    # If the home team won, update the home team's record to
    # reflect a win, and the same for the visitors, who will
    # record a loss.
    IF NEW.h_g > NEW.a_g THEN
      # Home win
      UPDATE standings s
      SET s.pld = s.pld + 1,
          s.h_w = s.h_w + 1,
          s.pts = s.pts + 3,
          s.h_gf = s.h_gf + NEW.h_g,
          s.h_ga = s.h_ga + NEW.a_g
      WHERE s.tid = OLD.htid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
      
      # Away loss
      UPDATE standings s
      SET s.pld = s.pld + 1,
          s.a_l = s.a_l + 1,
          s.a_gf = s.a_gf + NEW.a_g,
          s.a_ga = s.a_ga + NEW.h_g
      WHERE s.tid = OLD.atid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
    # Away Team wins.
    ELSE IF NEW.h_g < NEW.a_g THEN
      # Home loss
      UPDATE standings s
      SET s.pld = s.pld + 1,
          s.h_l = s.h_l + 1,
          s.h_gf = s.h_gf + NEW.h_g,
          s.h_ga = s.h_ga + NEW.a_g
      WHERE s.tid = OLD.htid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
      
      # Away win
      UPDATE standings s
      SET s.pld = s.pld + 1,
          s.a_l = s.a_l + 1,
          s.pts = s.pts + 3,
          s.a_gf = s.a_gf + NEW.a_g,
          s.a_ga = s.a_ga + NEW.h_g
      WHERE s.tid = OLD.atid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;

    # If there was a draw.
    ELSE IF NEW.h_g = NEW.a_g THEN
      # Home tie
      UPDATE standings s
      SET s.pld = s.pld + 1,
          s.h_t = s.h_t + 1,
          s.pts = s.pts + 1,
          s.h_gf = s.h_gf + NEW.h_g,
          s.h_ga = s.h_ga + NEW.a_g
      WHERE s.tid = OLD.htid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
      
      # Away tie
      UPDATE standings s
      SET s.pld = s.pld + 1,
          s.h_t = s.h_t + 1,
          s.pts = s.pts + 1,
          s.a_gf = s.a_gf + NEW.a_g,
          s.a_ga = s.a_ga + NEW.h_g
      WHERE s.tid = OLD.atid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
    END IF;
  END IF;
END;