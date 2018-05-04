<?php

    //--------------twig code-----------//
    require 'vendor/autoload.php';
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader, [
        'cache' => false,   //__DIR__ . '/tmp'
        'debug' => true,
    ]);

    $twig->addExtension(new Twig_Extension_Debug());

    include 'functions.php';

    if(isset($_GET['data'])) {
         
        $data = $_GET['data'];
        $path = explode('/', $data);

        if($path[0] === 'My_Documents' && !strpos($data, '..') && file_exists($data)) {
           
            echo $twig->render('home.html', array(

                'mydata' => displayData($data),
                'folder' => $data,
                'racine' => $path[0]
                               
            ));
            
        } else {
            echo "<h1> Sorry, you do not have permission</h1>";
        }

    } else {

        echo $twig->render('home.html', array(

            'mydata' => displayData('My_Documents'),
            
        ));
        
    }

?>