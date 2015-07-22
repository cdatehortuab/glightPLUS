<?php

/**
 * Project:     Framework G - G Light
 * File:        gvar.php
 * 
 * For questions, help, comments, discussion, etc., please join to the
 * website www.frameworkg.com
 * 
 * @link http://www.frameworkg.com/
 * @copyright 2013-02-07
 * @author Group Framework G  <info at frameworkg dot com>
 * @version 1.2
 */
 
$gvar=array();

$gvar['environment'] = C_ENVIRONMENT;
$gvar['lang'] = array('en');

//Alert types with text
$gvar['success']['en'] = "Success";
$gvar['info']['en'] = "Info";
$gvar['warning']['en'] = "Warning";
$gvar['danger']['en'] = "Error";

//messages
$gvar['m_unavailable_option']['en'] = "This option is not available";

//links and names
$gvar['l_global'] = C_L_GLOBAL;
$gvar['n_global'] = C_N_GLOBAL;

$gvar['n_login'] = "Login";
$gvar['n_logout'] = "Logout";

$gvar['index']['link'] = $gvar['l_global']."index.php";
$gvar['index']['name']['en'] = "Home";
$gvar['index']['father'] = null;
$gvar['contact']['link'] = $gvar['l_global']."contact.php";
$gvar['contact']['name']['en'] = "Contact";
$gvar['contact']['father'] = 'index';

?>