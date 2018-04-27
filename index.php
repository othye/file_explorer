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
            
            $fileExt = null;

            if($kind == false) {

                $path = $folder.DIRECTORY_SEPARATOR.$name; //pour créer le hemin des fichier
                
                $filetype =  mime_content_type ($path); //definir le type des fichiers
                
                $ext = explode('.', $path); //récupéré l'extention de fichier
                $fileExt = end($ext);

            }

            
           

            // la taille des fichier:
            if($size >= 1048576){
                $size = round($size/1048576,1).' MB';
            }else{
                if ($size >= 1024){
                    $size = round($size/1024,1).' KB';
                }
            }


            array_push($mydata, [

                'filename' => $name,
                'path' => $path,
                'type' => $kind,
                'filetype' => $fileExt ,
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
        $chemin = explode('/', $data);
        //$lien = reset($chemin);
        //echo '<pre>'; var_dump($lien); echo '</pre>'; die();

        if($chemin[0] === 'My_Documents' && !strpos($data, '..') && file_exists($data)) {
           
            echo $twig->render('home.html', array(

                'mydata' => displayData($data),
                'folder' => $data,
                'racine' => $chemin[0]
                
                
            ));
            // echo '<pre>'; var_dump($chemin[0]); echo '</pre>'; die();
            
        } else {

            //potentielle attaque
            

        }

    } else {

        echo $twig->render('home.html', array(

            'mydata' => displayData('My_Documents'),
            
        ));
        
    }

   


?>