--
-- (c) 2023 Siveo, http://www.siveo.net/
--
--
-- Pulse 2 is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- Pulse 2 is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with Pulse 2; if not, write to the Free Software
-- Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
-- MA 02110-1301, USA.


-- check exist index before creation




START TRANSACTION;

SET FOREIGN_KEY_CHECKS=0;

-------------------------------------------
-- Save msc tables
-------------------------------------------

create table  if not exists save_phase select * from phase;
create table  if not exists save_commands_on_host select * from commands_on_host;
create table  if not exists save_commands select * from commands;
create table  if not exists save_target select * from target;

-------------------------------------------
-- Clean msc tables
-------------------------------------------

delete from phase;
delete from commands_on_host;
delete from commands;
delete from target;

-- ----------------------------------------------------------------------
-- Add foreign key on tables
-- ----------------------------------------------------------------------
ALTER TABLE `msc`.`commands_on_host` 
ADD CONSTRAINT `fk_commands_on_host_commands`
  FOREIGN KEY IF NOT EXISTS (`fk_commands`)
  REFERENCES `msc`.`commands` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


ALTER TABLE `msc`.`phase` 
ADD CONSTRAINT `fk_phase_command_on_host`
  FOREIGN KEY IF NOT EXISTS (`fk_commands_on_host`)
  REFERENCES `msc`.`commands_on_host` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


ALTER TABLE msc.commands_on_host
ADD CONSTRAINT `fk_commands_on_host_target` 
  FOREIGN KEY (`fk_target`) 
  REFERENCES `target` (`id`) 
  ON DELETE NO ACTION 
  ON UPDATE NO ACTION;


-------------------------------------------
-- Clean msc tables
-------------------------------------------
insert into commands (select * from save_commands);
insert into target (select * from save_target);
insert into commands_on_host (select * from save_commands_on_host);
insert into phase (select * from save_phase);

-------------------------------------------
-- Clean msc tables
-------------------------------------------

drop table if exists save_commands;
drop table if exists save_target;
drop table if exists save_commands_on_host;
drop table if exists save_phase;

SET FOREIGN_KEY_CHECKS=1;


-- ----------------------------------------------------------------------
-- Database version
-- ----------------------------------------------------------------------
UPDATE version SET Number = 32;

COMMIT;

