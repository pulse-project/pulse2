-- -*- coding: utf-8; -*-
-- SPDX-FileCopyrightText: 2022-2024 Siveo <support@siveo.net>
-- SPDX-License-Identifier: GPL-3.0-or-later

START TRANSACTION;
USE `xmppmaster`;

-- glpi_computers_pulse on xmppmaster
drop table if exists local_glpi_machines;
create table if not exists local_glpi_machines (
    `id` int(10) NOT NULL DEFAULT 0, primary key(id),
    `entities_id` int(10) NOT NULL DEFAULT 0,
    `name` varchar(255) NULL DEFAULT NULL,
    `serial` varchar(255) NULL DEFAULT NULL,
    `otherserial` varchar(255) NULL DEFAULT NULL,
    `contact` varchar(255) NULL DEFAULT NULL,
    `contact_num` varchar(255) NULL DEFAULT NULL,
    `users_id_tech` int(10) NOT NULL DEFAULT 0,
    `groups_id_tech` int(10) NOT NULL DEFAULT 0,
    `comment` text NULL DEFAULT NULL,
    `date_mod` timestamp NULL DEFAULT NULL,
    `autoupdatesystems_id` int(10) NOT NULL DEFAULT 0,
    `locations_id` int(10) NOT NULL DEFAULT 0,
    `networks_id` int(10) NOT NULL DEFAULT 0,
    `computermodels_id` int(10) NOT NULL DEFAULT 0,
    `computertypes_id` int(10) NOT NULL DEFAULT 0,
    `is_template` tinyint(4) NOT NULL DEFAULT 0,
    `template_name` varchar(255) NULL DEFAULT NULL,
    `manufacturers_id` int(10) NOT NULL DEFAULT 0,
    `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
    `is_dynamic` tinyint(4) NOT NULL DEFAULT 0,
    `users_id` int(10) NOT NULL DEFAULT 0,
    `groups_id` int(10) NOT NULL DEFAULT 0,
    `states_id` int(10) NOT NULL DEFAULT 0,
    `ticket_tco` decimal(20,4) NULL DEFAULT 0.0,
    `uuid` varchar(255) NULL DEFAULT NULL,
    `date_creation` timestamp NULL DEFAULT NULL,
    `is_recursive` tinyint(4) NOT NULL DEFAULT 0,
    `domains_id` int(10) NULL DEFAULT 0,
    `operatingsystems_id` int(10) NULL DEFAULT 0,
    `operatingsystemversions_id` int(10) NULL DEFAULT 0,
    `operatingsystemservicepacks_id` int(10) NULL DEFAULT 0,
    `operatingsystemarchitectures_id` int(10) NULL DEFAULT 0,
    `license_number` varchar(255) NULL DEFAULT NULL,
    `licenseid` varchar(255) NULL DEFAULT NULL,
    `operatingsystemkernelversions_id` int(10) NULL DEFAULT 0
) ENGINE=FEDERATED DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CONNECTION='itsm_federated/glpi_computers_pulse';


-- glpi_entities on xmppmaster
drop talbe if exists local_glpi_entities;
create table if not exists local_glpi_entities(
    id int(10) unsigned not null default 0, primary key(id),
    name varchar(255) null default NULL,
    entities_id int(10) unsigned null default 0,
    completename text null default null,
    comment text null default null,
    level int(11) unsigned not null default 0,
    sons_cache longtext null default NULL,
    ancestors_cache longtext null default null
) ENGINE=FEDERATED DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci CONNECTION='itsm_federated/glpi_entities';

-- new view to simplify the querying on up_machine_windows
-- join some machine info, and allow to avoid the gray_list / white_list join on requests
drop view if exists up_machine_activated;
create view if not exists up_machine_activated as(
  select 
    (case when ugl.kb is NULL then uwl.kb else ugl.kb end) as kb,
    id_machine,
    substr(m.uuid_inventorymachine, 5) as glpi_id,
    m.hostname,
    m.jid,
    lgm.entities_id as entities_id,
    update_id,
    curent_deploy, 
    required_deploy, 
    start_date, 
    end_date, 
    intervals, 
    msrcseverity,
    (case when uwl.kb is NULL then "gray" else "white" end) as list
  from up_machine_windows umw
  join machines m on m.id = umw.id_machine
  join local_glpi_machines lgm on concat("UUID",lgm.id) = m.uuid_inventorymachine
  left join up_white_list uwl on uwl.updateid = umw.update_id
  left join up_gray_list ugl on ugl.updateid = umw.update_id
  where (ugl.valided = 1 or uwl.valided=1)
  and lgm.is_deleted =0 and lgm.is_template=0
);

UPDATE version SET Number = 91;
COMMIT;
