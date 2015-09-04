<?php

if( isset($_POST['domains']) && !empty($_POST['domains'] ) ) {
  get_dns_records( $_POST['radio'], $_POST['domains'] );
}

function get_dns_records( $type, $ips ){

  $urls = preg_split( '/\s+/', $ips );

  if( $_POST['radio'] === "radio1" ){
    $record_type = DNS_A;
  } elseif( $_POST['radio'] === "radio2" ) {
    $record_type = DNS_AAAA;
  } elseif( $_POST['radio'] === "radio3" ) {
    $record_type = DNS_CNAME;
  } elseif( $_POST['radio'] === "radio4" ) {
    $record_type = DNS_MX;
  } elseif( $_POST['radio'] === "radio5" ) {
    $record_type = DNS_NS;
  } elseif( $_POST['radio'] === "radio6" ) {
    $record_type = DNS_PTR;
  } elseif( $_POST['radio'] === "radio7" ) {
    $record_type = DNS_SRV;
  } elseif( $_POST['radio'] === "radio8" ) {
    $record_type = DNS_TXT;
  } elseif( $_POST['radio'] === "radio9" ) {
    $record_type = DNS_ALL;
  } elseif( $_POST['radio'] === "radio10" ) {
    $record_type = "REVERSE";
  } else {
    $record_type = DNS_A;
  }

  foreach( $urls as $data ) {

    if( $record_type !== "REVERSE" ) {
      $record = dns_get_record( $data, $record_type );
    } else {
      if( preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $data ) ){
        $record = gethostbyaddr( $data );
      } else {
        $record = "Please enter a valid IP, not a hostname";
      }
    }

    if( empty( $record ) ) {
      // If the DNS entry doesn't exist then tell us
        echo "<tr><td>".$data."</td><td class=\"record\"><span class=\"norecord\"> No record available</span></td></tr>\r\n";
    } else {
      // Record type is set as TXT
      if( $record_type === DNS_TXT ) {
        echo "<tr><td>".$record[0]["host"]."</td><td class=\"record\"> ".$record[0]["txt"]."</td></tr>\r\n";
      }
      // Record type is set as A
      elseif( $record_type === DNS_A ) {
        echo "<tr><td>".$data."</td><td class=\"record\"> ".$record[0]["ip"]."</td></tr>\r\n";
      }
      // Record type is set as AAAA
      elseif( $record_type === DNS_AAAA ) {
        echo "<tr><td>".$data."</td><td class=\"record\"> ".$record[0]["ipv6"]."</td></tr>\r\n";
      }
      // Record type is set as CNAME
      elseif( $record_type === DNS_CNAME ) {
        echo "<tr><td>".$data."</td><td class=\"record\"> ".$record[0]["target"]."</td></tr>\r\n";
      }
      // Record type is set as MX
      elseif( $record_type === DNS_MX ) {
        foreach( $record as $mx ) {
          echo "<tr><td>".$mx["host"]."</td><td class=\"record\"> ".$mx["pri"]." - ".$mx["target"]."</td></tr>\r\n";
        }
      }
      // Record type is set as NS
      elseif( $record_type === DNS_NS ) {
        foreach( $record as $ns ) {
          echo "<tr><td>".$ns["host"]."</td><td class=\"record\"> ".$ns["target"]."</td></tr>\r\n";
        }
      }
      // Record type is set as ALL
      elseif( $record_type === DNS_ALL ) {
        echo "<pre>\r\n";
        foreach( $record as $all ){
          echo "<tr><td>";
          print_r( $all );
          echo "</td></tr>\r\n";
        }
        echo "</pre>\r\n";
      }
      // Record type is set as PTR
      elseif( $record_type === DNS_PTR ) {
        echo "<pre>\r\n";
        foreach( $record as $ptr ){
          echo "<tr><td>";
          print_r( $record );
          echo "</td></tr>\r\n";
        }
        echo "</pre>\r\n";
      }
      // Record type is set as ReverseDNS
      elseif( $record_type === "REVERSE" ) {
        echo "<tr><td>".$data."</td><td class=\"record\"> ".$record."</td></tr>\r\n";
      }
    }
  }
}
?>