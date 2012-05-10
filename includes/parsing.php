<?php

function data_parse ($data) {
  
  $data = data_preprocess($data);
  
  $decks = data_prepare_decks($data);
  foreach ($decks as &$deck) {
    $deck['rounds'] = data_prepare_rounds($deck['rounds']); 
  }
  
  return $decks;
  
}

function data_preprocess($data) {
  $data = preprocess_remove_comments($data);
  $data = preprocess_remove_extra_empty_lines($data);
  $data = preprocess_trim_lines($data);
  return $data;
}

function data_prepare_decks($data) {
  $decks = array();
  $lines = explode("\n", $data);
  $currentDeck = array('rounds' => array());
  foreach ($lines as $line) {
    if ($line != '' && substr($line, 0, 1) == '[') {
      $decks []= deck_initialize($line);
      $currentDeck = &$decks[count($decks)-1];
    }
    else {
      $currentDeck['rounds'] []= $line;
    }
  }
  return $decks;
}

function deck_initialize($line) {
  $deck = array();
  $deck['title'] = str_remove_brackets($line);
  $deck['machineName'] = str_machine_name($deck['title']);
  $deck['rounds'] = array();
  return $deck;
}

function data_prepare_rounds($rounds) {
  $rounds = implode("\n", $rounds);
  $rounds = trim($rounds);
  $rounds = explode("\n\n", $rounds);
  foreach ($rounds as $roundIndex => &$round) {
    $round = data_prepare_round($roundIndex, $round);
  }
  return $rounds;
}

function data_prepare_round($roundIndex, $round) {
  $round = explode("\n", $round);
  foreach ($round as $cardIndex => &$card) {
    $cardID = str_pad($roundIndex, 2, '0', STR_PAD_LEFT)
            . STR_PAD($cardIndex,  2, '0', STR_PAD_LEFT);
    $card = data_prepare_card($cardID, $card);
  }
  return $round;
}

function data_prepare_card($cardID, $card) {
  $card_struct = array();
  $card_struct['id'] = $cardID;
  $card_struct['text'] = $card;
  $card_struct['machineName'] = str_machine_name($card);
  return $card_struct;
}

function str_machine_name($value) {
  
  $name = $value;
  
  // Remove mutiple consecutive spaces
  $name = preg_replace('/ +/s', ' ', $name);
  
  // Remove non-alphanumeric characters
  $name = preg_replace('/[\x0-\x1f\x21-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]/s', '', $name);
  
  // Lowercase
  $name = mb_strtolower($name, 'UTF-8');
  
  // Replace spaces with dashes
  $name = str_replace(' ', '-', $name);
  
  return $name;
  
}

function preprocess_remove_comments($data) {
  $lines = explode("\n", $data);
  foreach ($lines as &$line) {
    $clean_line = explode('#', $line);
    $line = $clean_line[0];
  }
  return implode("\n", $lines);
}

function preprocess_remove_extra_empty_lines($data) {
  $lines = explode("\n", $data);
  foreach ($lines as &$line) {
    $line = preg_replace('/\\n+/s', "\n", $line);
  }
  return implode("\n", $lines);
}

function preprocess_trim_lines($data) {
  $lines = explode("\n", $data);
  foreach ($lines as &$line) {
    $line = trim($line);
  }
  return implode("\n", $lines);
}

function str_remove_brackets($value) {
  // $matches = array();
  // preg_match('/\\[(.*)\\]/', $value, $matches);
  // return $matches[0];
  return preg_replace('/[\[\]]/s', '', $value);
}
