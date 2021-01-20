<?php
##  Creating simplest ApPHP DataGrid - version 4.2.8
##  ------------------------------------------------

    // *** only relative (virtual) path (to the current document)
    define('DATAGRID_DIR', '../datagrid/');
    define('PEAR_DIR', '../datagrid/pear/');
    
    require_once(DATAGRID_DIR.'datagrid.class.php');
    require_once(PEAR_DIR.'PEAR.php');
    require_once(PEAR_DIR.'DB.php');
    
    // *** creating variables that we need for database connection 
  $DB_USER='u981758803_titob';            /* usually like this: prefix_name             */
  $DB_PASS='the_reborn';                /* must be already enscrypted (recommended)   */
  $DB_HOST='mysql.hostinger.es';       /* usually localhost                          */
  $DB_NAME='u981758803_activ';          /* usually like this: prefix_dbName           */
  
    $db_conn = DB::factory('mysql'); 
    $db_conn->connect(DB::parseDSN('mysql://'.$DB_USER.':'.$DB_PASS.'@'.$DB_HOST.'/'.$DB_NAME));
  
    $dgrid = new DataGrid(false, true, '', DATAGRID_DIR);
    $dgrid->dataSource($db_conn, 'SELECT * FROM gc_motonaves');	    

// Filter Settings
    $dgrid->allowFiltering(true);
    $filtering_fields = array(
        'Nombre' => array('table'=>'gc_motonaves', 'field'=>'nombre', 'show_operator'=>false, 'default_operator'=>'like%', 'type'=>'textbox'),
    );
    $dgrid->setFieldsFiltering($filtering_fields);

    // Export Settings
    $dgrid->AllowExporting(true);
    $dgrid->AllowExportingTypes(
        array('excel'=>'true', 'pdf'=>'true', 'xml'=>'true')
    );

	$dg_language = 'es';  
	$dgrid->SetInterfaceLang($dg_language);

    $dgrid->setTableEdit('gc_motonaves', 'motonaveId', '');
    $dgrid->setAutoColumnsInViewMode(true);  
    $dgrid->setAutoColumnsInEditMode(true);
    
    $dgrid->bind();        

?>