CREATE TABLE tt_content (
denacharts_data_file int(11) unsigned DEFAULT '0' NOT NULL,
denacharts_aspect_ratio tinytext,
denacharts_container_width tinytext,
denacharts_show_points tinyint(4) unsigned DEFAULT 0 NOT NULL,
denacharts_stack tinyint(4) unsigned DEFAULT 0 NOT NULL,
denacharts_color_scheme tinytext,
denacharts_colors text,
);

