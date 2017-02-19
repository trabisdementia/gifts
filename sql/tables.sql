CREATE TABLE gifts_logs (
  log_id int(8) unsigned NOT NULL auto_increment,
  log_gid int(8) unsigned NOT NULL default '0',
  log_ruid int(8) unsigned NOT NULL default '0',
  log_suid int(8) unsigned NOT NULL default '0',
  log_message varchar(255) NOT NULL default '',
  log_created int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (log_id),
  KEY gid (log_gid),
  KEY ruid (log_ruid),
  KEY suid (log_suid)
) ENGINE=MyISAM;


CREATE TABLE gifts_gifts (
  gift_id int(8) unsigned NOT NULL auto_increment,
  gift_title varchar(255) NOT NULL default '',
  gift_description varchar(255) NOT NULL default '',
  gift_price int(10) unsigned NOT NULL default '0',
  gift_image_url varchar(255) NOT NULL default '',
  PRIMARY KEY (gift_id),
  KEY price (gift_price)
) ENGINE=MyISAM;

INSERT INTO gifts_gifts (gift_id, gift_title, gift_description, gift_price, gift_image_url) VALUES
(1, 'Bloco de notas purpura', 'Para que tenha caneta e papel à mão quando se sentir inspirada.', 10, 'bloco_notas_purpura.png'),
(2, 'Moeda de ouro', 'Uma moeda de ouro para que possa comprar um presente ao seu gosto.', 100, 'moeda.png'),
(3, 'Lenços de papel', 'Um caixa de lenços de papel para enxugar as suas lágrimas.', 4, 'lencos.png'),
(4, 'Bouquet de rosas', 'Um bouquet de rosas para colocar na sua cabeceira.', 20, 'flores.png'),
(5, 'Café', 'Um café sempre acompanha um bom poema.', 2, 'cafe.png'),
(6, 'Bolo de aniversário', 'Parabéns pelo seu aniversário. Que a vida lhe traga muitas alegrias!', 20, 'bolo_aniversario.png'),
(7, 'Beijo', 'Um beijo com muito respeito e carinho.', 2, 'labios.png'),
(8, 'Presente', 'Não sabia o que escolher por isso deixei à sua imaginação.', 20, 'presente.png'),
(9, 'Cerveja', 'Uma cerveja fresquinha para agradecer a sua bela poesia.', 4, 'cerveja.png'),
(10, 'Dicionário', 'Um dicionário para aprender palavras maravilhosas e embelezar os seus poemas.', 40, 'dicionario.png'),
(11, 'Vestido', 'Um vestido maravilhoso para uma senhora maravilhosa.', 40, 'vestido.png'),
(12, 'Fato', 'Um fato a condizer com um autêntico cavalheiro.', 40, 'fato.png'),
(13, 'Bloco de notas preto', 'Para que tenha caneta e papel à mão quando se sentir inspirado.', 10, 'bloco_notas_preto.png'),
(14, 'Copo de vinho', 'Um copo de vinho para se aquecer numa noite fria.', 4, 'vinho.png'),
(15, 'Bolo de chocolate', 'Um doce tão doce quanto você.', 4, 'bolo_chocolate.png');

CREATE TABLE gifts_credits (
  credit_id int(8) unsigned NOT NULL auto_increment,
  credit_uid int(10) unsigned NOT NULL default '0',
  credit_credits int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (credit_id),
  UNIQUE KEY (credit_uid)
) ENGINE=MyISAM;