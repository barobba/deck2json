<?php

function input_filename($argv) {
  $option = getopt('', array('in:'));
  if (!empty($option)) {
    $infile = $option['in'];
  }
  else {
    $infile = $argv[count($argv)-1];
  }
  return $infile;
}

function output_filename($argv) {
  $option = getopt('o:');
  if (!empty($option)) {
    $outfile = $option['o'];
  }
  else {
    $outfile = $argv[count($argv)-1].'.json';
  }
  return $outfile;
}
