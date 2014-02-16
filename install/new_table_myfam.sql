USE myfam;

DROP TABLE IF EXISTS `roster`;
DROP TABLE IF EXISTS `game`;
DROP TABLE IF EXISTS `event`;
DROP TABLE IF EXISTS `league`;
DROP TABLE IF EXISTS `team`;
DROP TABLE IF EXISTS `field`;
DROP TABLE IF EXISTS `season`;
DROP TABLE IF EXISTS `player`;
#DROP TRIGGER `update_standing`;

CREATE TABLE `league` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `age_cat` varchar(16) NOT NULL,
  UNIQUE (`name`, `age_cat`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `field` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `address` varchar(128),
  `city` varchar(64),
  `region` varchar(64),
  `pitch_type` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `team` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `homeid` integer NOT NULL,
  `city` varchar(32) NOT NULL,
  `region` varchar(32),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`homeid`) REFERENCES field(`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `season` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `start_date` date,
  `end_date` date,
  UNIQUE (`name`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `player` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `real_name` varchar(128) NOT NULL,
  `preferred_name` varchar(64),
  `pos1` varchar(16),
  `pos2` varchar(16),
  `pos3` varchar(16),
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  UNIQUE (`email`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `roster` (
  `pid` integer NOT NULL,
  `tid` integer NOT NULL,
  `sid` integer NOT NULL,
  `squad_number` integer NOT NULL,
  UNIQUE (`tid`, `sid`, `squad_number`),
  PRIMARY KEY (`pid`, `tid`, `sid`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`pid`) references player(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE `game` (
  `id` integer NOT NULL AUTO_INCREMENT,
  `sid` integer NOT NULL,
  `fid` integer NOT NULL,
  `lid` integer NOT NULL,
  `htid` integer NOT NULL,
  `atid` integer NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `h_g` integer NOT NULL DEFAULT 0,
  `a_g` integer NOT NULL DEFAULT 0,
  `has_been_played` boolean NOT NULL DEFAULT FALSE,
  PRIMARY KEY (`id`),
  UNIQUE (`sid`, `fid`, `lid`, `htid`, `atid`, `date`, `time`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`fid`) references field(`id`),
  FOREIGN KEY (`htid`) references team(`id`),
  FOREIGN KEY (`atid`) references team(`id`),
  FOREIGN KEY (`lid`) references league(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `event` (
  `tid` integer NOT NULL,
  `pid` integer NOT NULL,
  `gid` integer NOT NULL,
  `type` varchar(16) NOT NULL,
  `min` integer NOT NULL,
  PRIMARY KEY (`tid`, `pid`, `gid`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`pid`) references player(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

/*CREATE TABLE `standings` (
  `tid` integer NOT NULL,
  `sid` integer NOT NULL,
  `lid` integer NOT NULL,
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
  PRIMARY KEY (`lid`, `tid`, `sid`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`lid`) references league(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;*/

CREATE TABLE `league_reg` (
  `tid` integer NOT NULL,
  `sid` integer NOT NULL,
  `lid` integer NOT NULL,
  PRIMARY KEY (`lid`, `tid`, `sid`),
  FOREIGN KEY (`tid`) references team(`id`),
  FOREIGN KEY (`sid`) references season(`id`),
  FOREIGN KEY (`lid`) references league(`id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;

# The Standings table will be a view. We will revisit converting this into a table and triggers
# some other day.
CREATE OR REPLACE VIEW `standings` AS
  (select t.id tid, g1.sid,g1.lid,
     sum(case when (g1.has_been_played and t.id = htid and h_g > a_g) then 1 else 0 end) h_w,
     sum(case when (g1.has_been_played and t.id = htid and h_g = a_g) then 1 else 0 end) h_t,
     sum(case when (g1.has_been_played and t.id = htid and h_g < a_g) then 1 else 0 end) h_l,
     sum(case when (g1.has_been_played and t.id = htid) then h_g else 0 end) h_gf,
     sum(case when (g1.has_been_played and t.id = htid) then a_g else 0 end) h_ga,
     sum(case when (g1.has_been_played and t.id = atid and a_g > h_g) then 1 else 0 end) a_w,
     sum(case when (g1.has_been_played and t.id = atid and a_g = h_g) then 1 else 0 end) a_t,
     sum(case when (g1.has_been_played and t.id = atid and a_g < h_g) then 1 else 0 end) a_l,
     sum(case when (g1.has_been_played and t.id = atid) then a_g else 0 end) a_gf,
     sum(case when (g1.has_been_played and t.id = atid) then h_g else 0 end) a_ga
  from team t
    inner join game g1 on (t.id = g1.htid or t.id = g1.atid)
  group by t.id,sid,lid);

/**
 * This trigger will update an entry in 'standings' related to a
 * "first-time" update to a game row, in which the game is then
 * played.
 *
 * The current assumption is that if a second update is needed for
 * a game (for example, an error occurs)
 * 
 * This trigger is not being used right now. We will revisit it if
 * the stanigs view is dropped.
 */

/*delimiter /

CREATE TRIGGER update_standing AFTER UPDATE ON game
FOR EACH ROW
BEGIN
  # If a score has been recorded already, we'll reverse the old score
  # before updating the new score.
  IF OLD.has_been_played THEN
    # The Home Team previously recorded a win
    IF OLD.h_g > OLD.a_g THEN
      # Home win
      UPDATE standings s
      SET s.pld = s.pld - 1,
          s.h_w = s.h_w - 1,
          s.pts = s.pts - 3,
          s.h_gf = s.h_gf - OLD.h_g,
          s.h_ga = s.h_ga - OLD.a_g
      WHERE s.tid = OLD.htid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
      
      # Away loss
      UPDATE standings s
      SET s.pld = s.pld - 1,
          s.a_l = s.a_l - 1,
          s.a_gf = s.a_gf - OLD.a_g,
          s.a_ga = s.a_ga - OLD.h_g
      WHERE s.tid = OLD.atid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;


    # The Away Team previously recorded a win
    ELSEIF OLD.h_g < OLD.a_g THEN
      # Home loss
      UPDATE standings s
      SET s.pld = s.pld - 1,
          s.h_l = s.h_l - 1,
          s.h_gf = s.h_gf - OLD.h_g,
          s.h_ga = s.h_ga - OLD.a_g
      WHERE s.tid = OLD.htid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;
      
      # Away win
      UPDATE standings s
      SET s.pld = s.pld - 1,
          s.a_l = s.a_l - 1,
          s.pts = s.pts - 3,
          s.a_gf = s.a_gf - OLD.a_g,
          s.a_ga = s.a_ga - OLD.h_g
      WHERE s.tid = OLD.atid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;

    # The previous game resulted in a draw
    ELSEIF OLD.h_g = OLD.a_g THEN 
      # Home tie
      UPDATE standings s
      SET s.pld = s.pld - 1, s.h_t = s.h_t - 1,
          s.pts = s.pts - 1,
          s.h_gf = s.h_gf - OLD.h_g,
          s.h_ga = s.h_ga - OLD.a_g
      WHERE s.tid = OLD.htid AND s.sid = OLD.sid AND s.lid = OLD.lid;
      
      # Away tie
      UPDATE standings s
      SET s.pld = s.pld - 1,
          s.h_t = s.h_t - 1,
          s.pts = s.pts - 1,
          s.a_gf = s.a_gf - OLD.a_g,
          s.a_ga = s.a_ga - OLD.h_g
      WHERE s.tid = OLD.atid
        AND s.sid = OLD.sid
        AND s.lid = OLD.lid;

    END IF;
  END IF;

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
  ELSEIF NEW.h_g < NEW.a_g THEN
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
  ELSEIF NEW.h_g = NEW.a_g THEN
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
END;
/

delimiter ;
*/