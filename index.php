<?php
    require 'vendor/autoload.php';

    // rendu du template
    $loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
    $twig = new Twig_Environment($loader, [
        'cache' => false,   //__DIR__ . '/tmp'
        'debug' => true,

    ]);
    $twig->addExtension(new Twig_Extension_Debug());

    function displayData($folder) {

        $mydata = [];
        $directories = array_diff(scandir($folder), ['.', '..']);
        
        foreach ($directories as $name) {

            $path = $folder.DIRECTORY_SEPARATOR.$name;

            // dossier ou fichier
            $kind = is_dir($path);
            $date = filemtime($folder.DIRECTORY_SEPARATOR.$name);
            $datemodif = date ("F d Y - H:i:s", $date);
            $userId = getmyuid();
            $userInfo = posix_getpwuid($userId);
            $user = $userInfo['name'];
            $size = filesize($path). ' bytes';
            //$permission = fileperms($folder.DIRECTORY_SEPARATOR.$name);
            
            $filetype = null;

            if($kind == false) {

                $path = $folder.DIRECTORY_SEPARATOR.$name; //pour créer le hemin des fichier

                $filetype =  mime_content_type ($path); //definir le type des fichiers

                
                
            }

            array_push($mydata, [

                'filename' => $name,
                'path' => $path,
                'type' => $kind,
                'filetype' => $filetype ,
                'date' => $datemodif,
                'user' => $user,
                'size' => $size,
                //'perm' => $permission,

            ]);
            
        }
        
        return $mydata;

    }

    if(isset($_GET['data'])) {
    
        $data = $_GET['data'];

        if(substr($data, 0, 6) === 'upload' && !strpos($data, '..') && file_exists($data)) {

            echo $twig->render('home.html', array(

                'mydata' => displayData($data),
                
            ));
            
        } else {

            //potentielle attaque
            

        }

    } else {

        echo $twig->render('home.html', array(

            'mydata' => displayData('upload'),
            
        ));
        
    }

   


?>