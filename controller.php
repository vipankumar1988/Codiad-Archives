<?php
/*
 * Copyright (c) Codiad & Rafasashi, distributed
 * as-is and without warranty under the MIT License. 
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */
    error_reporting(0);

    require_once('../../common.php');
    checkSession();
    
    switch($_GET['action']) {
        
        case 'unzip':
		
            if (isset($_GET['path'])) {
                
				$source = getWorkspacePath($_GET['path']);
                
				$contents = file_get_contents($source);
				
				$des    = dirname($source);
				
				if(strpos($contents, 'Rar') !== false){
					
					echo '{"status":"error","message":"Looks like a Rar"}';
				} 
				elseif (strpos($contents, 'PK') !== false) {
					
					//echo '{"status":"success","message":"Looks like a Zip"}';
					
					if ($zip = new ZipArchive) {
	

						if($res = $zip->open($source)){

							// extract it to the path we determined above
							if($zip->extractTo($des)){
								
								echo '{"status":"success","message":"File unziped"}';
							}
							else{
								
								echo '{"status":"error","message":"Failed to extract contents"}';
							}
							
							$zip->close();
						  
						} 
						else {
							
							echo '{"status":"error","message":"Could not open zip archive"}';
						}
					}
					else {
						
						echo '{"status":"error","message":"ZipArchive extension missing"}';
					}
				}
				else{
					
					echo '{"status":"error","message":"Not an archive"}';
				}

            } 
			else {
				
                echo '{"status":"error","message":"Missing Parameter"}';
            }
            break;
        
        default:
            echo '{"status":"error","message":"No Type"}';
            break;
    }
    
    
    function getWorkspacePath($path) {
		
		//Security check
		if (!Common::checkPath($path)) {
			die('{"status":"error","message":"Invalid path"}');
		}
        if (strpos($path, "/") === 0) {
            //Unix absolute path
            return $path;
        }
        if (strpos($path, ":/") !== false) {
            //Windows absolute path
            return $path;
        }
        if (strpos($path, ":\\") !== false) {
            //Windows absolute path
            return $path;
        }
        return WORKSPACE . "/" . $path;
    }
?>