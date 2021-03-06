<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tugas 4 EAI Kelompok 02</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <?php include 'nav.php'; ?>

    <!-- Header -->
    <header id="top" class="header">
        <div class='text-vertical-center'>
            <h1>Get Cities</h1>
            <h4>View All Major Cities in a Country</h4>
            <form class="form-inline" method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="form-group">
                <input type="text" class="form-control" id="countryname" placeholder="Country" name="CountryName">
              </div>
              <button type="submit" class="btn btn-primary">Get Cities</button>
            </form>


            <?php
            if($_POST){
                require_once('lib/nusoap.php');
                $client = new nusoap_client('http://www.webservicex.net/globalweather.asmx?wsdl',true);

                //$GetCitiesByCountry = array('CountryName' => $_POST["CountryName"]);

                $response = $client->call('GetCitiesByCountry', array('CountryName' => $_POST["CountryName"]));                
                if ($client->fault) {
                    echo '<h2>Fault</h2>';
                    echo $response['faultstring'];
                } else {
                    // Check for errors
                    $err = $client->getError();
                    if ($err) {
                        // Display the error
                        echo '<h2>Error</h2> ' . $err;
                    } else {
                        //formatting result
                        $xml = simplexml_load_string($response['GetCitiesByCountryResult']);
                        

                        // Display the result
                        echo '<h2>Result</h2>';
                        if (sizeof($xml->Table) < 1 )
                        {
                            echo "Sorry, country ".$_POST["CountryName"]." not found.";
                        }
                        else
                        {
                            if (count($xml->Table) < 150)
                                $column = ceil(count($xml->Table) / 25);
                            else 
                                $column = ceil(count($xml->Table) / 250);
                            $columnSize = floor(12 / $column);
                            echo '<h3>Cities in '.$_POST["CountryName"].':</h3>';
                            echo '<div class = "row">';
                            for ($i=0; $i<$column; $i++)
                            {
                                echo '<div class="col-md-'.$columnSize.'">';
                                if (count($xml->Table) < 150)
                                    $col = 25;
                                else
                                    $col = 250;
                                for ($j=$i*$col; $j<($i + 1)*$col; $j++)
                                    if ($j < count($xml->Table))
                                        echo $xml->Table[$j]->City.'<br><br>';
                                    else break;
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                    }
                }
            }
                
            ?>
        </div>
    </header>


    <?php include 'footer.php'; ?>

</body>

</html>
