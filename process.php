<?php

// Using Net_DNS2 library to complete the DNS requests
require_once('Net/DNS2.php');

// Create the request variable and choose the DNS server in use
if( isset( $_POST['dns-server'] ) ) {
  $r = new Net_DNS2_Resolver( array( 'nameservers' => array( $_POST['dns-server'] ) ) );
} else {
  $r = new Net_DNS2_Resolver( array( 'nameservers' => array( '8.8.8.8' ) ) );
}

// Make sure that the domains textbox has some domains in it
if( isset( $_POST['domains'] ) && !empty( $_POST['domains'] ) ) {
  get_dns_records( $_POST['radio'], $_POST['domains'] );
}

function is_valid_domain_name( $domain_name ) {
    return ( preg_match( "/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name ) //valid chars check
            && preg_match( "/^.{1,253}$/", $domain_name ) //overall length check
            && preg_match( "/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name ) ); //length of each label
}

// The main function
function get_dns_records( $type, $domains ){

  // Allow the request variable from above
  global $r;

  // Make array from list of domains
  $urls = preg_split( '/\s+/', $domains );

  // Check which radio button was selected and assign record type
  switch( $type ){
    case "radio1":
      $record_type = "A";
      break;
      default;
    case "radio2":
      $record_type = "AAAA";
      break;
    case "radio3":
      $record_type = "CNAME";
      break;
    case "radio4":
      $record_type = "MX";
      break;
    case "radio5":
      $record_type = "NS";
      break;
    case "radio6":
      $record_type = "PTR";
      break;
    case "radio7":
      $record_type = "SPF";
      break;
    case "radio8":
      $record_type = "TXT";
      break;
    case "radio9":
      $record_type = "REVERSE";
      break;
  }

  // Loop through the domains given
  foreach( $urls as $data ) {

    if ( is_valid_domain_name( $data ) ) {

      // If the record type isn't a reverse lookup then use Net_DNS2 to run the DNS query
      if( $record_type !== "REVERSE" ) {
        try {
          $record = $r->query( $data, $record_type );
        } catch( Net_DNS2_Exception $e ) {
          // If the query fails completely then let us know why
          $record = "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">". $e->getMessage()."</span></td></tr>\n";
        }

      // If the record type is reverse lookup make sure the data given in the textbox matches standard IPv4/v6 types
      } elseif( $record_type === "REVERSE" ) {
        if( filter_var( $data, FILTER_VALIDATE_IP ) ){
          $record = gethostbyaddr( $data );
        } else {
          $record = "Please enter a valid IPv4/v6 address";
        }
      }

      // If record type isn't REVERSE and there's no record for the query let us know
      if( $record_type !== "REVERSE" && empty( $record->answer ) ) {
        // If the DNS entry doesn't exist then tell us
        echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\"> No record available</span></td></tr>\r\n";

      // Otherwise, echo out the record results for each of the queries
      } else {
        switch( $record_type ) {
          // Record type is set as A or AAAA
          case "A":
          case "AAAA":
            foreach( $record->answer as $dnsr ) {
              if( isset( $dnsr->address ) ) {
                echo "<tr><td>".$data."</td><td class=\"record\">".$dnsr->address."</td></tr>\r\n";
              } elseif( isset( $dnsr->cname ) ) {
                echo "<tr><td>".$data."</td><td class=\"record\">".$dnsr->cname."</td></tr>\r\n";
              } else {
                echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
              }
            }
            default;
            break;
          // Record type is set as CNAME
          case "CNAME":
            foreach( $record->answer as $dnsr ) {
              if( isset( $dnsr->cname ) ) {
                echo "<tr><td>".$data."</td><td class=\"record\">".$dnsr->cname."</td></tr>\r\n";
              } else {
                echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
              }
            }
            break;
          // Record type is set as MX
          case "MX":
            foreach( $record->answer as $dnsr ) {
              if( isset( $dnsr->preference ) && isset( $dnsr->exchange ) ) {
                echo "<tr><td>".$data."</td><td class=\"record\">".$dnsr->preference."</td><td class=\"record\">".$dnsr->exchange."</td></tr>\r\n";
              } else {
                echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
              }
            }
            break;
          // Record type is set as NS
          case "NS":
            foreach( $record->answer as $dnsr ) {
              if( isset( $dnsr->nsdname ) ) {
                echo "<tr><td>".$data."</td><td class=\"record\">".$dnsr->nsdname."</td></tr>\r\n";
              } else {
                echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
              }
            }
            break;
          // Record type is set as PTR
          case "PTR":
            foreach( $record->answer as $dnsr ){
              if( isset( $dnsr->name ) && isset( $dnsr->ptrdname ) ) {
                echo "<tr><td>".$data."</td><td class=\"record\">".$dnsr->name."</td><td class=\"record\">".$dnsr->ptrdname."</td></tr>\r\n";
              } else {
                echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
              }
            }
            break;
          // Record type is set as SPF or TXT
          case "SPF":
          case "TXT":
            foreach( $record->answer as $dnsr ){
              if( isset( $dnsr->text ) ) {
                foreach( $dnsr->text as $dnsrtext ) {
                  if( isset( $dnsrtext ) ) {
                    echo "<tr><td>".$data."</td><td class=\"record\">".$dnsrtext."</td></tr>\r\n";
                  } else {
                    echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
                  }
                }
              } else {
                echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
              }
            }
            break;
          // Record type is set as ReverseDNS
          case "REVERSE":
            if( isset( $record ) ) {
              echo "<tr><td>".$data."</td><td class=\"record\"> ".$record."</td></tr>\r\n";
            } else {
              echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">No record available</span></td></tr>\r\n";
            }
            break;
        }
      }
    } else {
      echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\">Invalid domain name entered</span></td></tr>\r\n";
    }
  }
}
?>
