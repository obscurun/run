<?php
/**
 *  EXAMPLE (iam_restore)
 *
 *  @author     Iván Ariel Melgrati <phpclasses@imelgrat.mailshell.com>
 *  @version    1.0
 *  @package    iam_backup
 *
 *  A class form restoring a database backup. Usage example file
 *  Requires PHP v 4.0+ and MySQL 3.23+
 *
 *  Copyright (C) Iván Ariel Melgrati <phpclasses@imelgrat.mailshell.com>
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2 of the License, or (at your option) any later version.
 *
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 */


  #####################################################################################################################
  #                      Include the classes                                                                          #
  #####################################################################################################################
  require_once("iam_restore.php");

  #####################################################################################################################
  #  Set the parameters: backup_file, hostname, databasename, dbuser and password                                     #
  # (must have SELECT, INSERT, DELETE permission to the mysql DB)                                                     #
  #####################################################################################################################
            $restore = new iam_restore('file.sql', "localhost", "idolos__teste", "root", "");
            $restore->perform_restore();
?>