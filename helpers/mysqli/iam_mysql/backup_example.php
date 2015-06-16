<?php
/**
 *  EXAMPLE (iam_backup)
 *
 *  @author     Iván Ariel Melgrati <phpclasses@imelgrat.mailshell.com>
 *  @version    1.0
 *  @package    iam_backup
 *
 *  A class form performing a database backup and sending it to the browser
 *  or setting it or download. Usage example file
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
  #                      Include the class                                                                            #
  #####################################################################################################################
  require_once("iam_backup.php");



  #####################################################################################################################
  #  Set the parameters: hostname, databasename, dbuser and password(must have SELECT permission to the mysql DB)     #
  #  Note that this produces a GZip compressed file. You should set the $compress parameter to false to get SQL file  #
  #  This will dump the database and prompt the user to download it. No compression is applied here.                  #
  #####################################################################################################################
  $backup = new iam_backup("localhost", "idolos2011", "root", "", true, false, true, "./file.sql.gz");

  #####################################################################################################################
  #  Call the perform backup function  and that's it!!!                                                               #
  #####################################################################################################################
  $backup->perform_backup();
  exit;
  #####################################################################################################################
  #  Set the parameters: We only set the Database connection. The connection procedure could be in an include file    #                                                     #
  #  This will dump the database and prompt the user to download it. No compression is applied here.                  #
  #####################################################################################################################

  $conn = @mysql_pconnect("localhost","root","");
  if(!$result)     // If no connection, return 0
  {
    echo "An error has occured. Could not connect to the server";
  }
  if(!@mysql_select_db("idolos2011"))  // If db not set, return 0
  {
    echo "An error has occured. Could not select the MySQL Database";
  }

  $backup = new iam_backup($conn);

  #####################################################################################################################
  #  Call the perform backup function  and that's it!!!                                                               #
  #####################################################################################################################
  $backup->perform_backup();

  #####################################################################################################################
  #  Set the parameters: hostname, databasename, dbuser and password(must have SELECT permission to the mysql DB)     #
  #  Note that this produces a GZip compressed file. You should set the third parameter to false to get an SQL file   #
  #  This will dump the database into the file located in "./file.sql.gz"                                             #
  #####################################################################################################################
  $backup = new iam_backup("localhost", "idolos2011", "root", "", true, false, true, "./file.sql.gz");

  #####################################################################################################################
  #  Call the perform backup function  and that's it!!!                                                               #
  #####################################################################################################################
  $backup->perform_backup();

?>