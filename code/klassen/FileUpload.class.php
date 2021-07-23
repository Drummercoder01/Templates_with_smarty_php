<?php
class FileUpload {

/*************************************************************************************************
The FileUpload class can be used to easily manage file uploads with the PHP engine.
*************************************************************************************************/ 

/************************************************************************************************  
                                       Attriubutes    
*************************************************************************************************/  
    
/*@var array $aAllowedFileTypes All the filetypes (extension, mime-types) are stored here*/
  protected $aAllowedFileTypes = array();
        
/* @var array $aUploaded All the info about the uploaded files is stored in this array*/
  protected $aUploaded = array();
        
/* @var array $aErrors Stores the errors (if any) which are encountered*/
  protected $aErrors = array();
        
/*@var null|string|array $sNewName Holds the new name for an individual file or an array of names for multiple upload */
  protected $sNewName = null;
        
/*@var string $sDirectory Stores the path to the directory were all files should be stored*/
  protected $sDirectory;
        
/*@var integer $iMaxSize Sets the maximum allowed filesize of each file in bytes */
  protected $iMaxSize = 0;
        
/*@var string|integer $iBaseNumber Sets the number where the automatic numbering system must start counting*/
  protected $mStartNumber = 1;
        
/*@var boolean $bMultiple Is set to true when an array of files is being uploaded */
  protected $bMultiple = false;
        
/*@var boolean $bLowerCaseExtension If set to true all extension will be casted to lowercase*/
  protected $bLowerCaseExtension = false;
        
/*@var boolean $bIngoreEmptyUploads If set to true, empty uploads will not be classified as an error*/
  protected $bIgnoreEmptyUploads = false;
        
/*@var object $oFileObject The oFileObject stores the reference to the file object provided by the contructor */
  protected $oFileObject;
 
 
 
/************************************************************************************************  
                                       Methodes   
*************************************************************************************************/  


        
/*************************************************************************************************
* The contructor loads the $_FILES array as an reference into a local variable inside the class.
*************************************************************************************************/   
  function __construct(&$oFileObject) {
    if ($oFileObject != null){
	
      if (isset($oFileObject['name'], $oFileObject['type'], $oFileObject['size'])) {
        $this->oFileObject =& $oFileObject;
        $this->bMultiple = is_array($oFileObject['name']);    
      } 
	  else {
      die('The $_FILES array provided is not available!');    
      }
	 }  
   }
  
  
        
/*************************************************************************************************
Valid filetypes can be added to the object by using this function.
The function's first argument can accept a string together with an array of mimetypes as the second argument. 
Else one can input an array with extension => array(mimetypes) as the first argument.
*************************************************************************************************/ 
		 
    function addFileType($mFileType, $mMimeTypes = null) {
                             
      $this->aAllowedFileTypes = (is_array($mFileType) && $mMimeTypes === null
        ? array_merge($this->aAllowedFileTypes, $mFileType)
          : (is_string($mFileType) && is_array($mMimeTypes)
        ? array_merge($this->aAllowedFileTypes, array(strtolower($mFileType) => $mMimeTypes))
          : (is_string($mFileType) && is_string($mMimeTypes)
        ? array_merge($this->aAllowedFileTypes, array(strtolower($mFileType) => array($mMimeTypes)))
          : $this->aAllowedFileTypes)));    
                
        }
  
  
        
/*************************************************************************************************
Adds a new error to the array

@param string $sFileName The name of the file
@param integer $iFileSize The size of the file in bytes
@param integer $iErrorCode The errorcode
@return void
*************************************************************************************************/ 
    function addError($sFileName, $iFileSize, $iErrorCode) {
        
       $aErrorCodes = array(
         0 => 'There is no error, the file uploaded with success',
         1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
         2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
         3 => 'The uploaded file was only partially uploaded',
         4 => 'No file was uploaded',
         6 => 'Missing a temporary folder',
        11 => 'The filetype (extension/mime-type) of the file is not allowed',
        13 => 'The uploaded file is too large',
        14 => 'The file could not be moved',
        15 => 'The file was not uploaded with the PHP engine',
        16 => 'The file has been deleted due to a rollback'
        );
                                 
       if ($iErrorCode <> 4 || ($iErrorCode == 4 && $this->bIgnoreEmptyUploads === false)) {
           array_push($this->aErrors, array(
              'name'    => $sFileName,
              'size'    => $iFileSize,
              'error'   => $iErrorCode,
              'message' => (isset($aErrorCodes[$iErrorCode]) ? $aErrorCodes[$iErrorCode] : 'An unknown error occured')
             ));
            }    
        }
 
 
        
/*************************************************************************************************
Adds a new uploaded file to the array

@param string $sFileName The name of the file
@param integer $iFileSize The size of the file in bytes
@param string $sNewName The new name the file has been given
@param string $sMessage An additional message
@return void
*************************************************************************************************/ 
  function addUploaded($sFileName, $iFileSize, $sNewName = '', $sMessage = '') {
      array_push($this->aUploaded, array(
            'name'    => $sFileName,
            'newName' => $sNewName,
            'size'    => $iFileSize,
            'message' => $sMessage
           ));    
        }
  
  
        
/*************************************************************************************************
Sets the maximum size in bytes the uploaded file(s) 

@param integer $iMaxSize The maximum size in bytes
@return void
*************************************************************************************************/ 
  function setMaxSize($iMaxSize) {
      $this->iMaxSize = (is_numeric($iMaxSize) && $iMaxSize >= 0 ? (int)$iMaxSize : $this->iMaxSize);
        }
 
 
        
/*************************************************************************************************
Sets the new name(s) or naming method for the files being uploaded.

If only one file is being uploaded and the first argument is a string that name will be used for the uploaded file. 

If multiple files are being uploaded you can feed an array of names to this function which will be used for the uploaded files. If your array with names is shorter than the number of files being uploaded the class will use the original name for the files remaining.
 
You also have the option to enter 'alpha' or 'num' as a string when uploading an array of files. The new names of the files will beaccording to the type selected (alpha starts with 'a' and num starts with 1.
This function can be usefull if you are using the class for an image gallery.

@param string|array $sNewName New name for an individual file/names for multiple files or the naming method
@return void
*************************************************************************************************/ 
  function setNewName($sNewName) {
            
     if ($this->bMultiple === true && is_array($sNewName)) {
          $this->sNewName = $sNewName;
            } 
     else if ($this->bMultiple === true && in_array($sNewName, array('alpha', 'num'))) {
           switch($sNewName) {
               case 'alpha': $this->sNewName = SORT_STRING;
                             break;
							 
               case 'num':   $this->sNewName = SORT_NUMERIC;
                             break; 
                }    
              } 
		     else if ($this->bMultiple === false && is_string($sNewName)) {
                  $this->sNewName = $sNewName;    
            }
            
        }
 
 
        
/*************************************************************************************************
If the bLowerCaseExtension is set to true, extensions of file uploads will be casted to lowercase. This can be usefull if you do not wish to check if the file has the extension .JPG or .jpg for example.

@param boolean $bSet Set to true when extensions should be casted
@return void
*************************************************************************************************/ 
  function setLowerCaseExtension($bSet = true) {
     $this->bLowerCaseExtension = (isset($bSet) && is_bool($bSet) ? $bSet : $this->bLowerCaseExtension);    
        }
  
  
        
/*************************************************************************************************
If the bIgnoreEmptyUploads is set to true, errors which are caused with the errorcode 4 (no file uploaded) will not be added to the error array. This can be usefull if you use a static ammount of fields for multiple file uploads and you're using the rollback() function if any errors occured. Because a not-uploaded error doesn't always have to be a real error.

@param boolean $bIgnore
@return void
*************************************************************************************************/ 
  function setIgnoreEmptyUploads($bIgnore = true) {
       $this->bIgnoreEmptyUploads = (isset($bIgnore) && is_bool($bIgnore) ? $bIgnore : $this->bIgnoreEmptyUploads);    
        }
 
 
        
/*************************************************************************************************
This function sets the startnumber from where the automatic numbering  system should start counting. 
If you have enabled the automatic numbering and you set the startnumber to 8 (example) the first uploaded file will get the number 8. If you  set the startnumber to 'auto' it will autodetect the highest number a file has in the directory and will take that number +1 as a startnumber.

 
@param mixed $mNumber
@return void
*************************************************************************************************/ 
 function setStartNumber($mNumber = 'auto') {
    $this->mStartNumber = (is_numeric($mNumber) || (is_string($mNumber) && $mNumber == 'auto') ? $mNumber : $this->mStartNumber);    
        }
  
  
        
/*************************************************************************************************
This function returns an array with errors if they occured, if no errors were encountered this function will return false. 
Remember that rollback entries will only be entered after the move() has been initiated.
 
@return mixed
*************************************************************************************************/ 
  function getErrors() {
      return (count($this->aErrors) > 0 ? $this->aErrors : false);
        }
  
  
        
/*************************************************************************************************
         * Returns the array with succesfull uploads
         *
         * @author Gerard Klomp <gerard@theprodukt.com>
         * @version 1.0
         * @since 1.0
         * @return array
*************************************************************************************************/ 
  function getUploaded() {
      return $this->aUploaded;
        }
 
 
        
/*************************************************************************************************
Extracts the extension of the filename provided

@param string $sFileName The full name of the file
@return string The extension
*************************************************************************************************/ 
  function getExtension($sFileName) {
      return substr($sFileName, strrpos($sFileName, '.')+1, strlen($sFileName)-1);    
        }
 
 
        
/*************************************************************************************************
Checks if the file extension and mimetype are allowed


@param string $sFileName The full name of the file
@param string $sMimeType The MIME-Type of the file
@return boolean Returns true if extension and mimetype are allowed, else false
*************************************************************************************************/ 
  function isFileTypeAllowed($sFileName, $sMimeType) {
            
      $sExtension = strtolower($this->getExtension($sFileName));
       return (isset($this->aAllowedFileTypes[$sExtension]) && in_array($sMimeType, $this->aAllowedFileTypes[$sExtension]));    
        
        }
 
 
        
/*************************************************************************************************
Generates a directory structure on the server according to the full path given in the parameter. existing directories aren't touched.
New directories will be given default chmod.*

@param string $sDirPath The full path
@return void
*************************************************************************************************/ 
  function generateDirectoryStructure($sDirPath, $sChmod = '0777') {
        
    $sDirPath = trim($sDirPath, '/');
    $aDir = explode('/', $sDirPath);
    $sTempPath = '';
            
    for ($i=0; $i < count($aDir); $i++) {
        $sTempPath .= (strlen($sTempPath) == 0 ? $aDir[$i] : '/'.$aDir[$i]);
        if (!is_dir($sTempPath)) {
           mkdir($sTempPath, $sChmod);
         }
       }
                
     }
 
 
       
/*************************************************************************************************
This function prepares the filename. It checks if the extension should be casted to lowercase and returns the correct filename.

@param string $sFileName
@return string
*************************************************************************************************/ 
  function prepareFileName($sFileName) {
    
     return ($this->bLowerCaseExtension === true
            ? substr($sFileName, 0, strrpos($sFileName, '.')).'.'.strtolower($this->getExtension($sFileName))
             : $sFileName);
    
   }
   
   
        
/*************************************************************************************************
This is the main function which checks all the factors of the file(s) and moves them.

@param string $sDirPath The full path where the files should be stored
@return void
*************************************************************************************************/ 
  function move($sDirPath) {
        
      $this->sDirectory = rtrim($sDirPath, '/');
            
      if (!is_dir($this->sDirectory)) {
                $this->generateDirectoryStructure($this->sDirectory);    
       }
            
      if (is_string($this->mStartNumber) && $this->mStartNumber == 'auto') {
                $this->mStartNumber = ($this->getHighestFileNumber($this->sDirectory) + 1);    
       }
            
      if ($this->bMultiple === true) {
                
         $iFiles = count($this->oFileObject['name']);
                
         for ($i=0; $i < $iFiles; $i++) {
		 
		   if ($this->prepareFileName($this->oFileObject['name'][$i]) != NULL){
                    
           $this->oFileObject['name'][$i] = $this->prepareFileName($this->oFileObject['name'][$i]);
                
           if ($this->oFileObject['error'][$i] <> 0) {
                
              $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], $this->oFileObject['error'][$i]);
                        
                    } 
			else if ($this->isFileTypeAllowed($this->oFileObject['name'][$i], $this->oFileObject['type'][$i]) === false) {
                    
                 $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 11);    
                    
                  } 
				  else if ($this->iMaxSize <> 0 && $this->oFileObject['size'][$i] > $this->iMaxSize) {
                    
                     $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 13);
                    
                  }
				    else if (is_uploaded_file($this->oFileObject['tmp_name'][$i]) === false) {
                 
                        $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 15);
                       
                    } 
					  else if (is_null($this->sNewName) === true) {
                        
                        if (move_uploaded_file($this->oFileObject['tmp_name'][$i], $this->sDirectory . '/' . $this->oFileObject['name'][$i]) === false) {
                
                            $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 14);
                    
                        } 
						else {
                
                          $this->addUploaded($this->oFileObject['name'][$i], $this->oFileObject['size'][$i]);
                        
                        }
                        
                    } else if ($this->sNewName == SORT_NUMERIC) {
                    
                      if (move_uploaded_file($this->oFileObject['tmp_name'][$i], $this->sDirectory . '/' . ($this->mStartNumber + $i) . '.' . $this->getExtension($this->oFileObject['name'][$i])) === false) {
                
                           $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 14);
                    
                        }
						 else {
                
                            $this->addUploaded($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], (($this->mStartNumber + $i) . '.' . $this->getExtension($this->oFileObject['name'][$i])));
                        
                        }
                            
                      } 
					  else if ($this->sNewName == SORT_STRING) {
                      
                        if (move_uploaded_file($this->oFileObject['tmp_name'][$i], $this->sDirectory . '/' . chr(97 + $i) . '.' . $this->getExtension($this->oFileObject['name'][$i])) === false) {
                
                             $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 14);
                    
                        } 
						else {
                
                          $this->addUploaded($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], (chr(97 + $i) . '.' . $this->getExtension($this->oFileObject['name'][$i])));
                        
                        }
                          
                    } else if (is_array($this->sNewName)) {
                    
                        if (move_uploaded_file($this->oFileObject['tmp_name'][$i], $this->sDirectory . '/' . (isset($this->sNewName[$i]) ? $this->sNewName[$i] . '.' . $this->getExtension($this->oFileObject['name'][$i]) : $this->oFileObject['name'][$i])) === false) {
                
                            $this->addError($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], 14);
                    
                        } 
						else {
                
                            $this->addUploaded($this->oFileObject['name'][$i], $this->oFileObject['size'][$i], (isset($this->sNewName[$i]) ? $this->sNewName[$i] . '.' . $this->getExtension($this->oFileObject['name'][$i]) : $this->oFileObject['name'][$i]));
                        
                        }
                            
                    }
                        
                }
              }   
            } 
			else {
                
                $this->oFileObject['name'] = $this->prepareFileName($this->oFileObject['name']);
                
                if ($this->oFileObject['error'] <> 0) {
                
                    $this->addError($this->oFileObject['name'], $this->oFileObject['size'], $this->oFileObject['error']);
                        
                } 
				else if ($this->isFileTypeAllowed($this->oFileObject['name'], $this->oFileObject['type']) === false) {
                    
                    $this->addError($this->oFileObject['name'], $this->oFileObject['size'], 11);    
                    
                } 
				else if ($this->iMaxSize <> 0 && $this->oFileObject['size'] > $this->iMaxSize) {
                    
                    $this->addError($this->oFileObject['name'], $this->oFileObject['size'], 13);
                    
                } 
				else if (is_uploaded_file($this->oFileObject['tmp_name']) === false) {
                 
                   $this->addError($this->oFileObject['name'], $this->oFileObject['size'], 15);
                       
                } 
				else if (move_uploaded_file($this->oFileObject['tmp_name'], (is_null($this->sNewName) === true ? $this->sDirectory . '/' . $this->oFileObject['name'] : $this->sDirectory . '/' . $this->sNewName . '.' . $this->getExtension($this->oFileObject['name']))) === false) {
                
                    $this->addError($this->oFileObject['name'], $this->oFileObject['size'], 14);
                    
                } 
				else {
                
                 $this->addUploaded($this->oFileObject['name'], $this->oFileObject['size'], (!is_null($this->sNewName) ? $this->sNewName.'.'.$this->getExtension($this->oFileObject['name']) : ''));
                        
                }
                    
            }
                
        }
 
 
        
/*************************************************************************************************
Rollback deletes any uploaded pictures and destroys the empty directories which are left. It does not touch any other directories which are not empty and it doesn't delete any directories other than the ones provided when issuing the move() command.


@return void
*************************************************************************************************/ 
  function rollback() {
        
    $iUploaded = count($this->aUploaded);
	
    for ($i=0; $i < $iUploaded; $i++) {
        unlink($this->sDirectory . '/' . ($this->aUploaded[$i]['newName'] != '' ? $this->aUploaded[$i]['newName'] : $this->aUploaded[$i]['name']));
        $this->addError($this->aUploaded[$i]['name'], $this->aUploaded[$i]['size'], 16);    
    }
            
    $aDir = explode('/', $this->sDirectory);
    $iDirs = count($aDir);
            
    for ($i = 0; $i < $iDirs; $i++) {
                
        $sCurrentDir = implode('/', $aDir);
                
         if (is_dir($sCurrentDir) && $this->isEmptyDir($sCurrentDir) === true) {
                    rmdir($sCurrentDir);
          }
                
           array_pop($aDir);   
       }
                
     }
 
 
        
/*************************************************************************************************
Checks if a given directory is empty (has no files/dirs in it)


@param string $sDirPath
@return boolean Returns true if directory is empty, else false
*************************************************************************************************/ 
  function isEmptyDir($sDirPath) {
            
    $bEmpty = true;
            
    if (is_dir($sDirPath) && ($rDir = opendir($sDirPath)) !== false) {
      while (($sItem = readdir($rDir)) !== false) {
        if (in_array($sItem, array('.', '..')) === false) {
             $bEmpty = false;    
        }        
       }
      closedir($rDir);
                
       return $bEmpty;
       } 
	   
	   else {
         return false;
     }    
   }
        
/*************************************************************************************************
Returns the highest filenumber in the given directory. If the directory is empty, it will return the value 0.

@param string $sDirPath
@return integer
*************************************************************************************************/ 
  function getHighestFileNumber($sDirPath) {
            
    if ($this->isEmptyDir($sDirPath) === true) {
                return 0;
    } 
	else {
      $aDirListing = $this->readDirectory($sDirPath);
      sort($aDirListing, SORT_NUMERIC);
      return end($aDirListing);
     }
                
   }
 
 
        
/*************************************************************************************************
Lists the contents of a given directory.

@param string $sDirPath The path to the directory to be listed
@param boolean $bLoseExtensions Should extensions be stripped from the filenames
@param array $aSkipList An array of items which should not be listed
@return array
*************************************************************************************************/ 
  function readDirectory($sDirPath, $bLoseExtensions = true, $aSkipList = array('.', '..')) {
        
    $aListing = array();
            
    if (is_dir($sDirPath) && ($rDir = opendir($sDirPath)) !== false) {
                
       while (($sItem = readdir($rDir)) !== false) {
       
	     if (in_array($sItem, $aSkipList) === false) {
            $aListing[] = substr($sItem, 0, ($bLoseExtensions === true ? strrpos($sItem, '.') : strlen($sItem)));
         }
       }
                    
     }
            
      return $aListing;
                        
  } 
  
  
  
}
?>