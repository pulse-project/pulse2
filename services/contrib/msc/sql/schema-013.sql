--
-- (c) 2008 Mandriva, http://www.mandriva.com/
--
-- $Id$
--
-- This file is part of Medulla 2, http://medulla.mandriva.org
--
-- Medulla 2 is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- Medulla 2 is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with Medulla 2; if not, write to the Free Software
-- Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
-- MA 02110-1301, USA.

ALTER TABLE `commands_on_host` \
    ADD COLUMN `last_wol_attempt` DATETIME DEFAULT NULL, \
    ADD COLUMN `fk_use_as_proxy` INT DEFAULT NULL, \
    ADD COLUMN `order_in_proxy` SMALLINT DEFAULT NULL;

ALTER TABLE `commands` \
    ADD COLUMN `use_local_proxy` ENUM("no", "yes") DEFAULT "no" NOT NULL;

DELETE FROM version;
INSERT INTO version VALUES( "13" );
