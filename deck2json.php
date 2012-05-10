<?php
require_once 'includes/print-json.php';
require_once 'includes/command_line.php';
require_once 'includes/parsing.php';

// Retrieve the file (--in, or last argument)
$infile = input_filename($argv);
$data = file_get_contents($infile);

// Prepare the data 
$data_struct = data_parse($data);
$data_struct = json_encode($data_struct);

// Save the results (-o, or add ".json" to input name)
$outfile = output_filename($argv);
file_put_contents($outfile, $data_struct);
