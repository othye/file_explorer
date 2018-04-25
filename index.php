<?php
    require 'vendor/autoload.php';

    // rendu du template
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader, [
        'cache' => false,   //__DIR__ . '/tmp'
        'debug' => true,

    ]);
    $twig->addExtension(new Twig_Extension_Debug());

    $dir = './upload/';
    $root = __DIR__;

    function is_in_dir($dir, $root, $recursive = true, $limit = 1000) {
        $directory = realpath($root);
        $parent = realpath($dir);
        $i = 0;
        while($parent) {
            if ($directory == $parent) return true;
            if ($parent == dirname($parent) || !$recursive) break;
            $parent = dirname($parent);
        }
        return false;
    } 


    if (isset($_GET['dir'])) {
        $dir = $_GET['dir'];
        if(!is_in_dir($dir, $root)){

            // il faut indiquer le dossier root ici 
            $dir= './upload/';
        
        }else{
            $dir='/'.$dir;
        }
    }

    $d = null;
    $mydata = array();
    foreach (scandir($dir) as $f) {
       
        if ($f !== '.' and $f !== '..'){
            $d =  realpath($dir.$f);
            $topdir = $d. "/" ;

            $type = is_dir($topdir);

            $mydata[] = array(
                'topdir' => $topdir,
                'file' => $f,
                'type' =>  $type
                
            );

            //echo "<a href='index.php?dir=".$topdir."'>$f </a>\n";
        }
    }
    
    //echo '<pre>'; var_dump($d); echo '</pre>'; die();
    
  


    // rooting
    $page = 'home';
    if (isset($_GET['p'])){
        $page = $_GET['p'];
    }

    if ($page === 'home'){
        echo $twig->render('home.html', array(
            'dir' => $dir,
            'mydata' => $mydata,
            'down' => $d,
        ));
        
    }

?>