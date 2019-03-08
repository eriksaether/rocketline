<?php
/** Copyright (c) 2017 PayFast (Pty) Ltd You (being anyone who is not PayFast (Pty) Ltd) may download
 * and use this code for testing purposes, in conjunction with a registered and active PayFast account.
 * If your PayFast account is terminated for any reason, you may not use this code or part thereof.
 * Except as expressly indicated in this licence, you may not use, copy, modify or distribute this
 * code or part thereof in any way.
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Signature</title>
    <meta charset="utf-8">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>

</head>

<style>

    @font-face {
    font-family: 'pt sans';
    font-style: normal;
    font-weight: normal;
    src: local('PT Sans'), local('PTSans-Regular'),
      url('pt_sans/PT_Sans-Web-Regular.ttf');  }

    h1, p { font-family:'pt sans';
             font-size:15px;  }

</style>

<body>

    <div class="jumbotron text-center">

        <h1>Signature Tool</h1>
        <p>For testing purposes only.</p> 

    </div>

    <div class="container" >

    <?php

        $pfData = $_POST;

        //  View your variables used. Uncomment the code below to investigate what has been posted through
//        echo '<span style="font-size:30px;font-family:pt sans;">The Variables Available</span><br><br>';
//        var_dump( $pfData );
//        echo '<br><br>';

        // Create GET string
        $pfParamString = '';
        foreach ( $pfData as $key => $val )
        {
            if ( $val !='' && $key != 'submit' && $key != 'passphrase' )
            {
                $pfParamString .= $key .'='. urlencode( stripslashes( trim( $val ) ) ) . '&';
            }
        }
          
        // Remove the last '&' from the Parameter string
        $pfParamString = substr( $pfParamString, 0, -1 );

        // Add the passphrase
        if ( $pfData['passphrase'] )
        {
            $preSigString = $pfParamString . '&passphrase=' . urlencode( $pfData['passphrase'] );
        }
        else
        {
            $preSigString = $pfParamString;
        }

        echo '<span style="font-size:30px;font-family:pt sans;">Your Parameter String & Signature</span><br><br>';

        // Display parameter string
        echo '<span style="font-size:15px;font-family:pt sans;"><strong>Parameter String generated:</strong><br />' . htmlentities( $preSigString ) . '</span><br><br>';
        // Generate signature
        $signature = md5( $preSigString );
        $str_arr = explode ("&", $preSigString);
        //print_r($str_arr) but in pieces..;
        echo "<br>".count($str_arr);
        for($i=0;$i<count($str_arr);$i++){
            echo $str_arr[$i]."<br>";
        }
        // Display signature
        echo '<br><span style="font-size:15px;font-family:pt sans;"><strong>Signature Generated:</strong> ' . $signature . '</span><br><br>';

    ?>

    <span style="font-size:30px;font-family:pt sans;">Test this Transaction</span><br><br>         

    <form action="https://sandbox.payfast.co.za/eng/process" method="POST">
        <span style="font-size:15px;font-family:pt sans;">
            <?php
                foreach ( $pfData as $key => $val )
                {
                    if ( !empty( $val ) && $key != 'submit' && $key != 'passphrase' )
                    {
                        ?>
                        <input type="hidden" name="<?php echo $key?>" value="<?php echo $val?>"/>
                        <?php
                    }

                }
            ?>

            <input type="hidden" name="signature" value="<?php echo $signature?>" />
            <input type="submit" class="btn" name="submit" value='Submit to Sandbox' />

        </span>
    </form>

</div>

</html>