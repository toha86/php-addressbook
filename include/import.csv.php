<?php

class ImportCsv {

   protected $ab = array();

function ImportCsv( $file_lines
                    , $delims = array(";", ",", "\t")
                    , $quotes = array('"', "'")
                    ) {
                    	
  function maxChar($chars, $testString) {
  	
  	$max_count = 0;		
    $the_char  = (count($chars) > 0 ? $chars[0] : " ");
    foreach($chars as $char) {
    	if(substr_count($testString, $char) > $max_count) {
    		$the_char = $char;
    	}
    }
    
    return $the_char;
  }

  $test_line = $file_lines[0];

  //
  // Detect the most probable delimiter.
  //
  $delim = maxChar($delims, $test_line);
  $quote = maxChar($quotes, $test_line);

  //
  // Detect if a quote is probable and remove the first + last
  //
  if(/*   substr($test_line,0,1) == substr($test_line,-1,1) 
     && substr($test_line,0,1) == $quote
     &&  */
     substr_count($test_line, $quote) > 2) {
  	// $quote = $quote;
  } else {
  	$quote = "";	
  }
  
/*
  function explodeWithQuotes($quote, $delim, $string) {
  	
  	if($quote != "") {
  	  	  	  
  	  //
  	  // Advanced RegExp for explosion:
  	  // * Ignores ""
  	  // * Includes ,"", as one field
  	  //
  	  $string = str_replace($delim, $delim.$delim, $string);
  	  
  	  $start_record = '(^|'.$delim.')';
  	  $match_value  = '([^'.$quote.']|'.$quote.$quote.')*';
      $endof_record = '('.$delim.'|$)';
      $string = preg_match_all( '/'.$start_record
                                   ."($quote".$match_value."$quote)?"
                                   .$endof_record.'/'
                              , $string, $matches);
      foreach($matches[2] as $match) {
      	$value    = preg_replace('/'."$quote(".$match_value.")$quote".'/', "$1", $match);
      	$value    = str_replace($quote.$quote, $quote, $value);
      	$value    = str_replace($delim.$delim, $delim, $value);
      	$values[] = $value;
      
      }

      return $values;
  	} else {  	
  		
      // Explode using delimiter only
      return(explode($delim, $string));
    }
  }
  
  
  //
  // Detect if a header line is probable
  //
  /*
  $first_line_fields = explodeWithQuotes($quote, $delim, $first_line);
  
  echo "<pre>";
  print_r($first_line_fields);
  */
  /*

  //
  // Detect if a header line is probable
  //
  $first_line_fields = explodeWithQuotes($quote, $delim, $first_line);  
  $cnt_uid_1st = count(array_unique($first_line_fields));
  $cnt_uid_2nd = count(array_unique(explodeWithQuotes($quote, $delim, $file_lines[1])));
  $cnt_uid_3rd = count(array_unique(explodeWithQuotes($quote, $delim, $file_lines[2])));  
  $may_have_header =   $cnt_uid_1st > $cnt_uid_2nd 
                    && $cnt_uid_1st > $cnt_uid_3rd;
                    
  // or see if no col starts with a number in first row, but some in 2nd
  // or see if all cols starts with a number in first row, but not all in 2nd
  
  
  //
  // Find the row with most fields filled
  //
  $count     = 0;
  $max_urows = 0;
  $max_rowid = 0;
  foreach($file_lines as $file_line) {
  	$line_fields = explodeWithQuotes($quote, $delim, $file_line);
 	  if(   ($count > 0  || ! $may_have_header)
 	     && $max_urows < count(array_unique($line_fields))
 	    ) {
 	  	$max_urows = count(array_unique($line_fields));
 	  	$max_rowid = $count;
 	  }
  	$count++;
  }
  $sample_fields = explodeWithQuotes($quote, $delim, $file_lines[$max_rowid]);


  //
  // Add samples for all other fields
  //
  $count     = 0;
  foreach($file_lines as $file_line) {
  	$line_fields = explodeWithQuotes($quote, $delim, $file_line);  	
 	  if($count > 0  || ! $may_have_header) {
 	  	for($i = 0; $i < min(count($sample_fields), count($line_fields)); $i++) {
 	  		if(strlen($sample_fields[$i]) == 0) {
 	  			$sample_fields[$i] = $line_fields[$i];
 	  		}
 	  	}
 	  }
  	$count++;
  }
  
  $count = 0;
  */
  
  include "parsecsv.lib.php";

	$csv = new parseCSV();	$input = implode("\r\n", $file_lines)."\r\n";
	// echo $input;
	$csv->delimiter = $delim;
	$csv->enclosure = $quote;
	$csv->file_data = &$input;
	$data = $csv->parse_string();
	
	// print_r($data);
	// print_r($data[0]);
	// print_r($data[0][0]);
  $first_line_fields = array_keys($data[0]);
  $sample_fields     = $data[0];
         
  $ab = array();
  $count = 0;
  foreach($data as $rec) {
  $val = array_values($rec);
  $rec = array_merge($rec, $val);
    include "import.csv.map-phpaddr.php";

  	 $this->ab[] = $addr;
  	 $count++;
  }
  print_r($ab);

  echo "<table>";
  foreach($sample_fields as $key => $value) {
   	echo "<tr>";
   	echo "<td>".$key."</td><td>".$value."</td>";

//   	if($i < count($sample_fields)) {
//   	  echo "<td>".$sample_fields[$i]."</td>";
//   	}
   	
   	$target_fields = array( "firstname"
   	                      , "lastname"
   	                      , "company"
   	                      , "address"
   	                      , "address:pobox"
   	                      , "address:street"
   	                      , "address:zip"
   	                      , "address:city"
   	                      , "address:state"
   	                      , "address:county"
   	                      , "address2"
   	                      , "home"
   	                      , "business"
   	                      , "mobile"
   	                      , "fax"
   	                      , "phone2"
   	                      , "email"
   	                      , "email2"
   	                      , "birthday"
   	                       );
  	echo "<td><select><option default></option>\n<option>";
   	echo implode("</option>\n<option>", $target_fields);
   	echo "</option>\n</td></tr>\n";
  }
  echo "</table>";
//*/  
  echo "Number of lines: $count";
  echo "</pre>";
}

  function getResult() {
  	return $this->ab;
  }
}
?>