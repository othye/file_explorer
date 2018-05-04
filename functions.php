<?php

    //---------Converting file size-------//
    function filesize_formatted($path)
    {
        $bytes = filesize($path);
    
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 1) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }


    //-------Display folder content------//
    function displayData($folder) {

        $mydata = [];
        $directories = array_diff(scandir($folder), ['.', '..']);
        
        foreach ($directories as $name) {

            $path = $folder.DIRECTORY_SEPARATOR.$name;
            $kind = is_dir($path);
         
            //$date =  date("F d y - H:i:s", filemtime($path));
            //$date = filemtime($folder.DIRECTORY_SEPARATOR.$name);
            //$datemodif = date ("F d Y - H:i:s", $date);
            //$userId = getmyuid();
            //$userInfo = posix_getpwuid(getmyuid());
            //$user = $userInfo['name'];
            //$size = filesize_formatted($path);
            //$permission = decoct(fileperms($path) & 0777); 
            //$permission = fileperms($folder.DIRECTORY_SEPARATOR.$name);

            
            
            // $fileExt = null;

            // if($kind == false) {

            //     $path = $folder.DIRECTORY_SEPARATOR.$name; // file path
                
            //     $filetype =  mime_content_type ($path); // file type
                
            //     $ext = explode('.', $path); //recover file extension 
            //     $fileExt = end($ext);

            // }
    
            array_push($mydata, [

                'filename' => $name,
                'path' => $path,
                'type' => $kind,
                //there are three methods for file type
                // 1 'filetype' => $fileExt,//with if as defined above
                //2 'filetype' => finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path),
                'filetype' => mime_content_type($path),
                'date' => date("F d y - H:i:s", filemtime($path)),
                'user' => posix_getpwuid(getmyuid())['name'],
                'size' => filesize_formatted($path),
                'perm' => decoct(fileperms($path) & 0777),
                'icon' => getImage ($path), 

            ]);
            
        }
        
        return $mydata ;

    }



    function getImage (string $path): string{
        $path = explode('.', $path); //recover file extension 
        $ext = end($path);

        if($ext == 'jpg' || $ext == 'jpeg') 
        {
            $image_name = 'img/jpg.png';  
        }elseif($ext == 'png' )
        {
            $image_name = 'img/png.png';

        }elseif( $ext == 'gif'){

            $image_name = 'img/gif.png';

        }elseif($ext == 'doc' || $ext == 'docx'|| $ext == 'odt')
        {
            $image_name = 'img/doc.png';  

        }elseif($ext == 'ppt'|| $ext == 'odp')
        {
            $image_name = 'img/ppt.png';

        }elseif ($ext == 'zip')
        {
            $image_name = 'img/zip.png';
        }else 
        {
            $image_name = 'img/cat.png';
        }
        return $image_name;

    }


?>



