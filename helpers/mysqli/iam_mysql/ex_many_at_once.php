<?php
/**
 *  EXAMPLE (iam_backup) This example file backs up several DBs at once (using the current date and time to create a directory to store the file dumps)
 *
 *  @author     Stefan Cenry
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

    /**
     * Creates a Database dump for all the DBs listed in the Array
     * @param Array Contains Strings identifying the DBs to dump
    */
    function toAllDB($dbs)
    {
        $now = gmdate('Y.m.d.H.i.s');

        if (!file_exists("./$now"))
        {
            mkdir("./$now");
        }

        while (list ($key, $val) = each ($dbs) )
        {
            echo "$key => $val<br>";
            $backup = new iam_backup("localhost", $val, "root", "", false, false, true, "./$now./$val.$now.sql.gz");
            $backup->perform_backup();
        }
    }
  #####################################################################################################################
  #  Set the DBnamos to backup and call the function                                                                  #
  #####################################################################################################################
    $fruits[0] = "intranetsk";
    $fruits[1] = "mysql";
    $fruits[2] = "law";

    toAllDB($fruits);

?>
