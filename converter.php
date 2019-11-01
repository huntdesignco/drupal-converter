<?php

  require_once './drupal/includes/bootstrap.inc';
  require_once './drupal/includes/password.inc';
  $hash_count_log2 = 11;

  if (empty($argv[1])) { 
    echo "No input file specified.\n";
    die(1);
  }
  elseif (empty($argv[2])) { 
    echo "No output file specified.\n";
    die(1);
  }

  $input = $argv[1];
  $output = $argv[2];

  if (!file_exists($input)) { 
    echo "The input file does not exist: " . $input . "\n";
    die(1);
  }
  if (file_exists($output)) { 
    echo "The output file already exists: " . $output . "\n";
    die(1);
  }

  $in_handle = fopen($input, "r");
  $out_handle = fopen($output, "w");

  if ($in_handle && $out_handle) { 
    echo "Parsing and converting the input file..." . "\n";
    while (($line = fgets($in_handle)) !== false) { 
      $result = explode(" ", $line);
      if ($result[0] == "userPassword:") {
        $old_hash = $result[1];
        $hash_count_log2 = 11;

        $new_hash = user_hash_password($old_hash, $hash_count_log2);
        $newline = "userPassword: " . str_replace($line, $old_hash, $new_hash) . "\n";
      }
      else { $newline = $line; }
      fwrite($out_handle, $newline);
    }
    fclose($in_handle);
    fclose($out_handle);
    echo "Conversion complete.";
  }
  else {
    echo "There was an error reading the input file: " . $input;
  }

?>

